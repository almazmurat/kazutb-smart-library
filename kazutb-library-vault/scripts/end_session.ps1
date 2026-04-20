param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$Summary
)

$vaultRoot = Split-Path -Parent $PSScriptRoot
$taskLog = Join-Path $vaultRoot '02-memory/TASK_LOG.md'
$inbox = Join-Path $vaultRoot '03-inbox'
$graphScript = Join-Path $PSScriptRoot 'rebuild_graph.ps1'
$graphReport = Join-Path $PSScriptRoot 'LAST_GRAPH_HEALTH.md'
$timestamp = Get-Date -Format 'yyyy-MM-dd HH:mm'
$entry = "$timestamp | Session end | $Summary"

$content = Get-Content -Path $taskLog -Raw -Encoding UTF8
$marker = '---'
if ($content.Contains($marker)) {
    $parts = $content -split [regex]::Escape($marker), 2
    $content = $parts[0] + $marker + "`n`n" + $entry + "`n" + $parts[1].TrimStart("`r", "`n")
} else {
    $content = $entry + "`n`n" + $content
}
Set-Content -Path $taskLog -Value $content -Encoding UTF8

$inboxFiles = Get-ChildItem -Path $inbox -Filter *.md -File -ErrorAction SilentlyContinue
if ($inboxFiles) {
    Write-Warning '⚠️ 03-inbox is not empty. Process or delete before closing.'
}

if (Test-Path $graphScript) {
    & $graphScript
}

if (Test-Path $graphReport) {
    Write-Host ''
    Get-Content -Path $graphReport -Encoding UTF8
}

Write-Host '✅ Session logged. Vault updated.'
