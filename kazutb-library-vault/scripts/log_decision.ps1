param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$Title,
    [Parameter(Mandatory = $true, Position = 1)]
    [string]$Decision,
    [Parameter(Mandatory = $true, Position = 2)]
    [string]$Why
)

$vaultRoot = Split-Path -Parent $PSScriptRoot
$decisionsPath = Join-Path $vaultRoot '02-memory/DECISIONS.md'
$date = Get-Date -Format 'yyyy-MM-dd'

if (-not (Test-Path $decisionsPath)) {
    throw 'DECISIONS.md was not found.'
}

$entry = @"
## $date — $Title
**Decision:** $Decision
**Reason:** $Why
**Alternatives considered:** Manually capture later.
**Impact:** Recorded through log_decision.ps1 for future session continuity.

---
"@

$content = Get-Content -Path $decisionsPath -Raw -Encoding UTF8
$linksMarker = "`n## Links"
if ($content.Contains($linksMarker)) {
    $content = $content.Replace($linksMarker, "`n`n$entry$linksMarker")
}
else {
    $content = $content.TrimEnd() + "`n`n$entry"
}

Set-Content -Path $decisionsPath -Value $content -Encoding UTF8
Write-Host '✅ Decision logged to vault.'
