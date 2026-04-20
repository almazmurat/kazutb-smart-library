param()

$vaultRoot = Split-Path -Parent $PSScriptRoot
$taskLog = Join-Path $vaultRoot '02-memory/TASK_LOG.md'
$currentState = Join-Path $vaultRoot '02-memory/CURRENT_STATE.md'
$openQuestions = Join-Path $vaultRoot '02-memory/OPEN_QUESTIONS.md'
$graphScript = Join-Path $PSScriptRoot 'rebuild_graph.ps1'
$graphReport = Join-Path $PSScriptRoot 'LAST_GRAPH_HEALTH.md'

Write-Host "Session start: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
Write-Host ''
Write-Host 'Recent TASK_LOG entries:'
$recentEntries = Get-Content -Path $taskLog -Encoding UTF8 | Where-Object { $_ -match '^(\d{4}-\d{2}-\d{2}|\[\d{4}-\d{2}-\d{2})' } | Select-Object -First 5
if ($recentEntries) { $recentEntries | ForEach-Object { Write-Host $_ } } else { Write-Host 'No recent task entries found.' }
Write-Host ''
Write-Host 'CURRENT_STATE.md:'
Get-Content -Path $currentState -Raw -Encoding UTF8
Write-Host ''
Write-Host 'HIGH priority open questions:'
$highLines = Get-Content -Path $openQuestions -Encoding UTF8 | Where-Object { $_ -match '^\- \*\*HIGH\*\*' }
if ($highLines) { $highLines | ForEach-Object { Write-Host $_ } } else { Write-Host 'No HIGH priority questions found.' }

if (Test-Path $graphScript) {
    & $graphScript *> $null
}

if (Test-Path $graphReport) {
    $report = Get-Content -Path $graphReport -Encoding UTF8
    $orphans = ($report | Where-Object { $_ -match 'Orphan files:' }) -replace '.*Orphan files:\s*', ''
    $broken = ($report | Where-Object { $_ -match 'Broken links:' }) -replace '.*Broken links:\s*', ''
    Write-Host "Graph health: orphans=$orphans broken=$broken"
}

Write-Host '📚 Vault loaded. You''re ready to work.'
