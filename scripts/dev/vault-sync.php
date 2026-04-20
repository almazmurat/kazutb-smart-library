#!/usr/bin/env php
<?php

declare(strict_types=1);

function ensure_dir(string $path): void
{
    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function shell_text(string $command, string $cwd): string
{
    $output = shell_exec('cd ' . escapeshellarg($cwd) . ' && ' . $command . ' 2>/dev/null');

    return trim((string) $output);
}

function normalize_text(string $text, int $limit = 1400): string
{
    $text = str_replace(["\r\n", "\r"], "\n", $text);
    $text = preg_replace('/[\t ]+/', ' ', $text) ?? $text;
    $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;
    $text = trim($text);

    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) > $limit) {
            return rtrim(mb_substr($text, 0, $limit - 1)) . '…';
        }

        return $text;
    }

    if (strlen($text) > $limit) {
        return rtrim(substr($text, 0, $limit - 1)) . '…';
    }

    return $text;
}

function slugify_excerpt(string $text): string
{
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? 'memory';
    $text = trim($text, '-');

    if ($text === '') {
        return 'memory';
    }

    return substr($text, 0, 48);
}

function find_latest_transcript(): ?string
{
    $home = getenv('HOME') ?: '';

    if ($home === '') {
        return null;
    }

    $files = glob($home . '/.vscode-server/data/User/workspaceStorage/*/GitHub.copilot-chat/transcripts/*.jsonl');

    if (! $files) {
        return null;
    }

    usort($files, static fn (string $a, string $b): int => (filemtime($a) ?: 0) <=> (filemtime($b) ?: 0));

    $latest = end($files);

    return $latest === false ? null : $latest;
}

function read_transcript(?string $path): array
{
    if (! $path || ! is_file($path)) {
        return [];
    }

    $messages = [];
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

    foreach ($lines as $line) {
        $decoded = json_decode($line, true);

        if (! is_array($decoded)) {
            continue;
        }

        $type = $decoded['type'] ?? '';
        $data = $decoded['data'] ?? [];

        if ($type === 'user.message') {
            $content = normalize_text((string) ($data['content'] ?? ''));
            if ($content !== '') {
                $messages[] = [
                    'role' => 'User',
                    'content' => $content,
                    'timestamp' => (string) ($decoded['timestamp'] ?? ''),
                ];
            }
        }

        if ($type === 'assistant.message') {
            $content = normalize_text((string) ($data['content'] ?? ''));
            if ($content !== '') {
                $messages[] = [
                    'role' => 'Assistant',
                    'content' => $content,
                    'timestamp' => (string) ($decoded['timestamp'] ?? ''),
                ];
            }
        }
    }

    return $messages;
}

function format_messages(array $messages): string
{
    if ($messages === []) {
        return "- No transcript messages were available during this sync.\n";
    }

    $chunks = [];

    foreach ($messages as $message) {
        $role = $message['role'] ?? 'Entry';
        $content = trim((string) ($message['content'] ?? ''));

        if ($content === '') {
            continue;
        }

        $chunks[] = '### ' . $role . "\n" . $content;
    }

    return implode("\n\n", $chunks) . "\n";
}

function prepend_under_heading(string $path, string $heading, string $entry): void
{
    $entry = rtrim($entry) . "\n";

    if (! is_file($path)) {
        file_put_contents($path, $heading . "\n\n" . $entry);
        return;
    }

    $current = (string) file_get_contents($path);

    if (str_contains($current, $entry)) {
        return;
    }

    if (! str_starts_with($current, $heading)) {
        $current = $heading . "\n\n" . ltrim($current);
    }

    $pattern = '/^' . preg_quote($heading, '/') . "\\n\\n?/";
    $body = preg_replace($pattern, '', $current, 1) ?? $current;

    file_put_contents($path, $heading . "\n\n" . $entry . "\n" . ltrim($body));
}

function upsert_section(string $path, string $heading, string $block, string $defaultHeader): void
{
    $block = rtrim($block) . "\n";

    if (! is_file($path)) {
        file_put_contents($path, $defaultHeader . "\n\n" . $block);
        return;
    }

    $current = (string) file_get_contents($path);

    $pattern = '/' . preg_quote($heading, '/') . '.*?(?=\n## |\z)/s';

    if (preg_match($pattern, $current) === 1) {
        $updated = preg_replace($pattern, rtrim($block), $current, 1) ?? $current;
        file_put_contents($path, $updated);
        return;
    }

    file_put_contents($path, rtrim($current) . "\n\n" . $block);
}

$options = getopt('', ['root:', 'vault:', 'transcript::', 'trigger::', 'force']);
$root = realpath($options['root'] ?? getcwd()) ?: getcwd();
$vault = $options['vault'] ?? ($root . '/artifacts/obsidian/vault-mirror');
$transcript = $options['transcript'] ?? find_latest_transcript();
$trigger = $options['trigger'] ?? 'manual';
$force = array_key_exists('force', $options);

foreach (['00-system', '01-master', '02-memory', '03-inbox', 'scripts'] as $dir) {
    ensure_dir($vault . '/' . $dir);
}

$branch = shell_text('git branch --show-current', $root) ?: 'unknown';
$commit = shell_text('git rev-parse --short HEAD', $root) ?: 'none';
$commitSubject = shell_text('git log -1 --pretty=%s', $root) ?: 'No commit message available';
$gitStatus = shell_text('git status --short', $root);
$changedFiles = [];

foreach (preg_split('/\R+/', $gitStatus) ?: [] as $line) {
    $line = trim((string) $line);
    if ($line === '') {
        continue;
    }

    $changedFiles[] = trim((string) preg_replace('/^([ MADRCU\?]{1,3})\s+/', '', $line));
}

$messages = read_transcript($transcript);
$recentMessages = array_slice($messages, -6);
$latestUserText = '';

for ($i = count($messages) - 1; $i >= 0; $i--) {
    if (($messages[$i]['role'] ?? '') === 'User') {
        $latestUserText = (string) ($messages[$i]['content'] ?? '');
        break;
    }
}

$signature = sha1(json_encode([
    'trigger' => $trigger,
    'branch' => $branch,
    'commit' => $commit,
    'git_status' => $gitStatus,
    'messages' => $recentMessages,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?: $trigger . $branch . $commit);

$stateFile = $vault . '/00-system/.copilot-sync-state.json';
$state = [];

if (is_file($stateFile)) {
    $decodedState = json_decode((string) file_get_contents($stateFile), true);
    if (is_array($decodedState)) {
        $state = $decodedState;
    }
}

if (! $force && ($state['last_signature'] ?? '') === $signature) {
    fwrite(STDOUT, "No new memory to sync.\n");
    exit(0);
}

$timestamp = gmdate('Y-m-d H:i:s') . ' UTC';
$noteName = gmdate('Ymd-His') . '-copilot-memory-' . substr($signature, 0, 8);
$focusSlug = slugify_excerpt($latestUserText !== '' ? $latestUserText : $trigger);
$noteTitle = $noteName . '-' . $focusSlug;
$notePath = $vault . '/03-inbox/' . $noteTitle . '.md';
$links = '[[MASTER_CONTEXT]], [[CURRENT_STATE]], [[DECISIONS]], [[NEXT_ACTIONS]], [[SESSION_MEMORY]], [[TASK_LOG]], [[GRAPH_INDEX]]';
$changedFilesMarkdown = $changedFiles === []
    ? "- No repository file changes detected during this sync.\n"
    : implode("\n", array_map(static fn (string $file): string => '- ' . $file, array_slice($changedFiles, 0, 30))) . "\n";

$recentMessagesMarkdown = format_messages($recentMessages);
$latestUserSummary = $latestUserText !== '' ? normalize_text($latestUserText, 220) : 'No user message was available yet.';

$noteContent = "---\n"
    . "title: {$noteTitle}\n"
    . "type: copilot-memory\n"
    . "trigger: {$trigger}\n"
    . "branch: {$branch}\n"
    . "commit: {$commit}\n"
    . "generated: {$timestamp}\n"
    . "tags:\n"
    . "  - copilot\n"
    . "  - session-memory\n"
    . "  - obsidian-sync\n"
    . "---\n\n"
    . "# {$noteTitle}\n\n"
    . "## Graph links\n"
    . $links . "\n\n"
    . "## Sync metadata\n"
    . "- Trigger: {$trigger}\n"
    . "- Branch: {$branch}\n"
    . "- Commit: {$commit} — {$commitSubject}\n"
    . "- Transcript source: " . ($transcript ?: 'not found') . "\n"
    . "- Last user focus: {$latestUserSummary}\n\n"
    . "## Changed files\n"
    . $changedFilesMarkdown . "\n"
    . "## Recent conversation\n"
    . $recentMessagesMarkdown;

file_put_contents($notePath, $noteContent);

prepend_under_heading(
    $vault . '/02-memory/SESSION_MEMORY.md',
    '# SESSION_MEMORY',
    "## {$timestamp} • {$trigger}\n- Linked node: [[{$noteTitle}]]\n- User focus: {$latestUserSummary}\n- Branch: {$branch}\n- Commit: {$commit}"
);

prepend_under_heading(
    $vault . '/02-memory/TASK_LOG.md',
    '# TASK_LOG',
    "- {$timestamp} — {$trigger} sync captured in [[{$noteTitle}]] on {$branch} at {$commit}"
);

upsert_section(
    $vault . '/01-master/CURRENT_STATE.md',
    '## Auto Sync Status',
    "## Auto Sync Status\n- Last sync: {$timestamp}\n- Trigger: {$trigger}\n- Current branch: {$branch}\n- Current commit: {$commit}\n- Latest generated node: [[{$noteTitle}]]\n- Memory hubs: {$links}",
    "# CURRENT_STATE\n\nLiving snapshot of the current project state."
);

if (! is_file($vault . '/01-master/MASTER_CONTEXT.md')) {
    file_put_contents(
        $vault . '/01-master/MASTER_CONTEXT.md',
        "# MASTER_CONTEXT\n\nCore project context hub.\n\n## Linked hubs\n- [[CURRENT_STATE]]\n- [[DECISIONS]]\n- [[NEXT_ACTIONS]]\n- [[SESSION_MEMORY]]\n- [[TASK_LOG]]\n- [[GRAPH_INDEX]]\n"
    );
}

if (! is_file($vault . '/01-master/DECISIONS.md')) {
    file_put_contents($vault . '/01-master/DECISIONS.md', "# DECISIONS\n\nStable technical and product decisions belong here.\n\n## Linked hubs\n- [[MASTER_CONTEXT]]\n- [[CURRENT_STATE]]\n- [[NEXT_ACTIONS]]\n");
}

if (! is_file($vault . '/01-master/NEXT_ACTIONS.md')) {
    file_put_contents($vault . '/01-master/NEXT_ACTIONS.md', "# NEXT_ACTIONS\n\nUpcoming work and follow-ups belong here.\n\n## Linked hubs\n- [[MASTER_CONTEXT]]\n- [[CURRENT_STATE]]\n- [[TASK_LOG]]\n");
}

$recentNotes = glob($vault . '/03-inbox/*-copilot-memory-*.md') ?: [];
rsort($recentNotes);
$recentLinks = array_map(
    static fn (string $path): string => '- [[' . basename($path, '.md') . ']]',
    array_slice($recentNotes, 0, 40)
);

upsert_section(
    $vault . '/scripts/GRAPH_INDEX.md',
    '## Auto-created memory nodes',
    "## Auto-created memory nodes\n" . implode("\n", $recentLinks),
    "# GRAPH_INDEX\n\nNavigation hub for the Obsidian graph.\n\n## Core hubs\n- [[MASTER_CONTEXT]]\n- [[CURRENT_STATE]]\n- [[DECISIONS]]\n- [[NEXT_ACTIONS]]\n- [[SESSION_MEMORY]]\n- [[TASK_LOG]]\n"
);

if (! is_file($vault . '/scripts/START_HERE.md')) {
    file_put_contents(
        $vault . '/scripts/START_HERE.md',
        "# START_HERE\n\nThis vault receives auto-synced Copilot memory nodes.\n\n## Open first\n- [[MASTER_CONTEXT]]\n- [[CURRENT_STATE]]\n- [[NEXT_ACTIONS]]\n- [[GRAPH_INDEX]]\n"
    );
}

file_put_contents(
    $stateFile,
    json_encode([
        'last_signature' => $signature,
        'last_note' => $noteTitle,
        'last_timestamp' => gmdate(DATE_ATOM),
        'last_trigger' => $trigger,
        'last_transcript' => $transcript,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

fwrite(STDOUT, "Synced vault memory to {$vault}\n");
