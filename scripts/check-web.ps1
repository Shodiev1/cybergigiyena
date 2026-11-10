param([string]$Browser = 'chrome', [string]$Grep = '')
$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$webRoot = Join-Path $projectRoot 'web'
$runtimeRoot = Join-Path $projectRoot '.runtime'
$phpExe = (Get-Command php -ErrorAction Stop).Source
$nodeExe = (Get-Command node -ErrorAction Stop).Source
$testExit = 1
$phpProcess = $null
$webProcess = $null
try {
    # The project MySQL instance must already be listening on 3308.
    # Run these services and the browser in the same execution environment.
    $phpProcess = Start-Process -FilePath $phpExe -ArgumentList @('-S', '127.0.0.1:8000', '-t', 'backend/public', 'backend/public/index.php') -WorkingDirectory $projectRoot -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $runtimeRoot 'qa-php.out.log') -RedirectStandardError (Join-Path $runtimeRoot 'qa-php.err.log')
    $webProcess = Start-Process -FilePath $nodeExe -ArgumentList @('node_modules/next/dist/bin/next', 'start', '--hostname', '127.0.0.1') -WorkingDirectory $webRoot -WindowStyle Hidden -PassThru -RedirectStandardOutput (Join-Path $runtimeRoot 'qa-web.out.log') -RedirectStandardError (Join-Path $runtimeRoot 'qa-web.err.log')
    $ready = $false
    for ($attempt = 0; $attempt -lt 30; $attempt++) {
        if ($phpProcess.HasExited -or $webProcess.HasExited) { throw 'QA server exited. See .runtime/qa-*.log; check occupied ports.' }
        try {
            $raw = & curl.exe --noproxy '*' -fsS --max-time 2 'http://127.0.0.1:3000/api/v1/health' 2>$null
            if ($LASTEXITCODE -eq 0 -and $raw) {
                $health = $raw | ConvertFrom-Json
                if ($health.ok) { $ready = $true; break }
            }
            Start-Sleep -Milliseconds 500
        } catch { Start-Sleep -Milliseconds 500 }
    }
    if (-not $ready) { throw 'The local web/API/database health check did not pass.' }
    $env:CG_BROWSER = $Browser
    Push-Location $webRoot
    try {
        if ($Grep) {
            & $nodeExe 'node_modules/@playwright/test/cli.js' test --grep $Grep
        } else {
            & $nodeExe 'node_modules/@playwright/test/cli.js' test
        }
        $testExit = $LASTEXITCODE
    } finally { Pop-Location }
} finally {
    if ($webProcess -and -not $webProcess.HasExited) { Stop-Process -Id $webProcess.Id }
    if ($phpProcess -and -not $phpProcess.HasExited) { Stop-Process -Id $phpProcess.Id }
}
exit $testExit
