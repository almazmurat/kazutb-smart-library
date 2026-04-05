# Final smoke test: verify Phase 1 deployment readiness

Write-Host "=== PHASE 1 DEPLOYMENT READINESS CHECK ===" -ForegroundColor Green
Write-Host ""

$errors = 0

Write-Host "1. PHP Syntax Check..." -ForegroundColor Cyan
@("app/Services/Library/IdentityMatchAudit.php",
  "app/Services/Library/AccountSummaryReadService.php",
  "app/Models/IdentityMatchLog.php") | ForEach-Object {
    $result = php -l $_  2>&1
    if ($result -like "*No syntax errors*") {
        Write-Host "   ✓ $_"
    } else {
        Write-Host "   ✗ $_ FAILED"
        $errors++
    }
}

Write-Host ""
Write-Host "2. File Check..." -ForegroundColor Cyan
@("database/migrations/2026_03_31_080810_create_identity_match_logs_table.php",
  "tests/Feature/Api/IdentityMatchAuditTest.php",
  "tests/Feature/Api/AccountSummaryWithAuditTest.php",
  "tests/Feature/Api/IdentityMappingE2ETest.php",
  "IDENTITY_MAPPING_HARDENING.md",
  "DEPLOYMENT_CHECKLIST_PHASE1.md") | ForEach-Object {
    if (Test-Path $_) {
        Write-Host "   ✓ $_"
    } else {
        Write-Host "   ✗ $_ NOT FOUND"
        $errors++
    }
}

Write-Host ""
Write-Host "3. Git Commits..." -ForegroundColor Cyan
Write-Host "   Last 8 commits:"
git log --oneline -8 | ForEach-Object { Write-Host "   $_" }

Write-Host ""
if ($errors -eq 0) {
    Write-Host "=== ✅ READINESS: READY FOR DEPLOYMENT ===" -ForegroundColor Green
} else {
    Write-Host "=== ✗ READINESS: $errors ISSUES FOUND ===" -ForegroundColor Red
}
