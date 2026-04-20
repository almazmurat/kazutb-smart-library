param()

$projectRoot = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
$vaultRoot = Split-Path -Parent $PSScriptRoot
$inboxLog = Join-Path $vaultRoot '03-inbox/AUTO_CHANGES.md'
$currentState = Join-Path $vaultRoot '02-memory/CURRENT_STATE.md'
$graphScript = Join-Path $PSScriptRoot 'rebuild_graph.ps1'

if (-not (Test-Path $inboxLog)) {
    "# AUTO_CHANGES`n`n## Links`n- [[CURRENT_STATE]]`n- [[TASK_LOG]]`n" | Set-Content -Path $inboxLog -Encoding UTF8
}

$global:WatcherChanges = New-Object System.Collections.Generic.List[string]
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $projectRoot
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true
$watcher.NotifyFilter = [System.IO.NotifyFilters]'FileName, LastWrite, Size'
$watcher.Filter = '*.*'

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $context = $Event.MessageData
    if ($path -match '[\\/](node_modules|vendor|\.git)[\\/]') { return }
    if ($path -notmatch '\.(php|js)$' -and $path -notmatch '\.blade\.php$') { return }

    $stamp = Get-Date -Format 'yyyy-MM-dd HH:mm'
    $relative = $path.Replace($context.ProjectRoot + [System.IO.Path]::DirectorySeparatorChar, '')
    Add-Content -Path $context.InboxLog -Value "[$stamp] Modified: $relative"
    $global:WatcherChanges.Add("[$stamp] $relative") | Out-Null
}

$messageData = @{ ProjectRoot = $projectRoot; InboxLog = $inboxLog }
Register-ObjectEvent $watcher Changed -Action $action -MessageData $messageData | Out-Null
Register-ObjectEvent $watcher Created -Action $action -MessageData $messageData | Out-Null
Register-ObjectEvent $watcher Renamed -Action $action -MessageData $messageData | Out-Null

$lastSummary = Get-Date
Write-Host 'WATCHER.ps1 is running. Press Ctrl+C to stop.'

try {
    while ($true) {
        Start-Sleep -Seconds 5
        if (((Get-Date) - $lastSummary).TotalMinutes -ge 30) {
            $summary = if ($global:WatcherChanges.Count -gt 0) {
                ($global:WatcherChanges | Select-Object -Last 20) -join '; '
            } else {
                'No tracked PHP/Blade/JS changes in the last 30 minutes.'
            }

            $state = Get-Content -Path $currentState -Raw -Encoding UTF8
            $block = "## Watcher summary`n- Time: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')`n- Recent changes: $summary`n- Links: [[TASK_LOG]], [[GRAPH_INDEX]]`n"
            if ($state -match '## Watcher summary') {
                $state = [regex]::Replace($state, '## Watcher summary\n.*?(?=\n## |\Z)', $block, [System.Text.RegularExpressions.RegexOptions]::Singleline)
            } else {
                $state = $state.TrimEnd() + "`n`n$block"
            }
            Set-Content -Path $currentState -Value $state -Encoding UTF8

            if (Test-Path $graphScript) {
                & $graphScript *> $null
            }

            $global:WatcherChanges.Clear()
            $lastSummary = Get-Date
        }
    }
}
finally {
    $watcher.EnableRaisingEvents = $false
    Get-EventSubscriber | Where-Object { $_.SourceObject -eq $watcher } | Unregister-Event
    $watcher.Dispose()
}
