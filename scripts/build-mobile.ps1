param(
    [string]$AppUrl = 'https://cybergigiyena.onrender.com',
    [ValidateSet('apk','appbundle')]
    [string]$Target = 'apk'
)
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$flutterRoot = Join-Path $projectRoot 'flutter'

if (-not $AppUrl.StartsWith('https://')) {
    throw "Production APP_URL faqat https:// bilan boshlanishi shart (masalan: https://cybergigiyena.onrender.com)"
}

Push-Location $flutterRoot
try {
    Write-Host "1. Running flutter analyze & flutter test..." -ForegroundColor Cyan
    & flutter analyze
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }
    & flutter test
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

    Write-Host "2. Building Android $Target with APP_URL=$AppUrl ..." -ForegroundColor Cyan
    & flutter build $Target --release "--dart-define=APP_URL=$AppUrl"
    if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

    if ($Target -eq 'apk') {
        $out = Join-Path $flutterRoot 'build\app\outputs\flutter-apk\app-release.apk'
    } else {
        $out = Join-Path $flutterRoot 'build\app\outputs\bundle\release\app-release.aab'
    }
    Write-Host "`nTayyor mobil fayl: $out" -ForegroundColor Green
} finally {
    Pop-Location
}
