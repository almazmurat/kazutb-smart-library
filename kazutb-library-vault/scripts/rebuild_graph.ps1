param()

$VaultRoot = Split-Path -Parent $PSScriptRoot
$MarkdownFiles = Get-ChildItem -Path $VaultRoot -Recurse -File -Filter *.md |
Where-Object { $_.FullName -notmatch '[\\/]99-archive[\\/]' }

$NameMap = @{}
foreach ($file in $MarkdownFiles) {
    $base = [System.IO.Path]::GetFileNameWithoutExtension($file.Name)
    $NameMap[$base] = $file.FullName
}

$Incoming = @{}
$BrokenLinks = New-Object System.Collections.Generic.List[string]
$TotalLinks = 0

foreach ($file in $MarkdownFiles) {
    if (-not $Incoming.ContainsKey($file.FullName)) { $Incoming[$file.FullName] = 0 }
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    $matches = [regex]::Matches($content, '\[\[([^\]|#]+)')
    foreach ($m in $matches) {
        $target = $m.Groups[1].Value.Trim()
        if ([string]::IsNullOrWhiteSpace($target)) { continue }
        $TotalLinks++
        if ($NameMap.ContainsKey($target)) {
            $targetPath = $NameMap[$target]
            if (-not $Incoming.ContainsKey($targetPath)) { $Incoming[$targetPath] = 0 }
            $Incoming[$targetPath]++
        }
        else {
            $BrokenLinks.Add("$($file.Name) -> [[${target}]]")
        }
    }
}

$NoOutgoing = @()
foreach ($file in $MarkdownFiles) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    if ($content -notmatch '\[\[') { $NoOutgoing += $file.Name }
}

$Orphans = @()
foreach ($file in $MarkdownFiles) {
    $count = 0
    if ($Incoming.ContainsKey($file.FullName)) { $count = $Incoming[$file.FullName] }
    if ($count -eq 0) { $Orphans += $file.Name }
}

$summary = @()
$summary += '# Last Graph Health'
$summary += ''
$summary += "Generated: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
$summary += ''
$summary += '## Health Summary'
$summary += "- Total files: $($MarkdownFiles.Count)"
$summary += "- Total wiki-links: $TotalLinks"
$summary += "- Orphan files: $($Orphans.Count)"
$summary += "- Broken links: $($BrokenLinks.Count)"
$summary += "- Files with zero outgoing links: $($NoOutgoing.Count)"
$summary += ''
$summary += '## Orphans'
foreach ($item in $Orphans) { $summary += "- $item" }
$summary += ''
$summary += '## Broken Links'
foreach ($item in $BrokenLinks) { $summary += "- $item" }
$summary += ''
$summary += '## Zero Outgoing Links'
foreach ($item in $NoOutgoing) { $summary += "- $item" }
$summary += ''
$summary += '## Links'
$summary += '- [[GRAPH_INDEX]]'
$summary += '- [[VAULT_RULES]]'

$outPath = Join-Path $PSScriptRoot 'LAST_GRAPH_HEALTH.md'
$summary -join "`n" | Set-Content -Path $outPath -Encoding UTF8

Write-Host 'Vault graph health summary'
Write-Host "Total files: $($MarkdownFiles.Count)"
Write-Host "Total links: $TotalLinks"
Write-Host "Orphans: $($Orphans.Count)"
Write-Host "Broken links: $($BrokenLinks.Count)"
Write-Host "Zero outgoing links: $($NoOutgoing.Count)"
Write-Host "Report written to $outPath"
