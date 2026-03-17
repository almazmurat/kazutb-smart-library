param(
    [string]$EnvFile = ".env"
)

if (-not (Test-Path $EnvFile)) {
    Write-Error "Environment file not found: $EnvFile"
    exit 1
}

$requiredKeys = @(
    'DATABASE_URL',
    'JWT_SECRET',
    'LDAP_URL',
    'LDAP_BIND_DN',
    'LDAP_BIND_PASSWORD',
    'LDAP_BASE_DN'
)

$content = Get-Content $EnvFile
$missing = @()

foreach ($key in $requiredKeys) {
    if (-not ($content -match "^$key=")) {
        $missing += $key
    }
}

if ($missing.Count -gt 0) {
    Write-Error "Missing required env vars: $($missing -join ', ')"
    exit 1
}

Write-Host "Environment check passed for $EnvFile"
