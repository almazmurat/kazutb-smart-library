param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$Summary
)

$VaultRoot = Split-Path -Parent $PSScriptRoot
$TaskLog = Join-Path $VaultRoot '02-memory/TASK_LOG.md'
$date = Get-Date -Format 'yyyy-MM-dd'
$entry = "$date | $Summary"

if (-not (Test-Path $TaskLog)) {
    throw 'TASK_LOG.md was not found.'
}

$content = Get-Content -Path $TaskLog -Raw -Encoding UTF8
$marker = '---'
if ($content -match [regex]::Escape($marker)) {
    $parts = $content -split [regex]::Escape($marker), 2
    $newContent = $parts[0] + $marker + "`n`n" + $entry + "`n" + $parts[1].TrimStart("`r", "`n")
} else {
    $newContent = $entry + "`n`n" + $content
}

Set-Content -Path $TaskLog -Value $newContent -Encoding UTF8
Write-Host "Prepended session entry: $entry"
