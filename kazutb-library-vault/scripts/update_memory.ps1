param()

$VaultRoot = Split-Path -Parent $PSScriptRoot
$CurrentState = Join-Path $VaultRoot '02-memory/CURRENT_STATE.md'
$TaskLog = Join-Path $VaultRoot '02-memory/TASK_LOG.md'

Write-Host 'Update memory reminder'
Write-Host '1. Review CURRENT_STATE.md'
Write-Host '2. Update task statuses and blockers'
Write-Host '3. Add today''s summary to TASK_LOG.md'
Write-Host '4. Record any decisions in DECISIONS.md'
Write-Host ''

if (Test-Path $CurrentState) {
    Write-Host "Current state file: $CurrentState"
} else {
    Write-Warning 'CURRENT_STATE.md is missing.'
}

if (Test-Path $TaskLog) {
    $lastWrite = (Get-Item $TaskLog).LastWriteTime.Date
    if ($lastWrite -ne (Get-Date).Date) {
        Write-Warning '⚠️ TASK_LOG not updated today. Update it before closing session.'
    } else {
        Write-Host 'TASK_LOG was updated today.'
    }
} else {
    Write-Warning 'TASK_LOG.md is missing.'
}
