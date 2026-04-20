param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$Question,
    [Parameter(Mandatory = $true, Position = 1)]
    [ValidateSet('HIGH', 'MED', 'LOW')]
    [string]$Priority,
    [Parameter(Mandatory = $true, Position = 2)]
    [string]$Blocks
)

$vaultRoot = Split-Path -Parent $PSScriptRoot
$questionsPath = Join-Path $vaultRoot '02-memory/OPEN_QUESTIONS.md'

if (-not (Test-Path $questionsPath)) {
    throw 'OPEN_QUESTIONS.md was not found.'
}

$entry = "- **$Priority** | $Question | $Blocks | Manual session follow-up"
$content = Get-Content -Path $questionsPath -Raw -Encoding UTF8
$linksMarker = "`n## Links"
if ($content.Contains($linksMarker)) {
    $content = $content.Replace($linksMarker, "`n$entry`n`n## Links")
} else {
    $content = $content.TrimEnd() + "`n$entry`n"
}

Set-Content -Path $questionsPath -Value $content -Encoding UTF8
Write-Host '✅ Question logged to vault.'
