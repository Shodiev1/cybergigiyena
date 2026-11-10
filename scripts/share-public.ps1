$ErrorActionPreference = 'Stop'
$projectRoot = Split-Path -Parent $PSScriptRoot
$webRoot = Join-Path $projectRoot 'web'
$runtimeRoot = Join-Path $projectRoot '.runtime'
New-Item -ItemType Directory -Force -Path $runtimeRoot | Out-Null

Write-Host "1. Starting PHP API (127.0.0.1:8000) and Next.js (127.0.0.1:3000)..."
$phpExe = (Get-Command php -ErrorAction Stop).Source
$nodeExe = (Get-Command node -ErrorAction Stop).Source
$phpProc = Start-Process -FilePath $phpExe -ArgumentList @('-S','127.0.0.1:8000','-t','backend/public','backend/public/index.php') -WorkingDirectory $projectRoot -WindowStyle Hidden -PassThru
$webProc = Start-Process -FilePath $nodeExe -ArgumentList @('node_modules/next/dist/bin/next','start','--hostname','127.0.0.1','--port','3000') -WorkingDirectory $webRoot -WindowStyle Hidden -PassThru

try {
    Start-Sleep -Seconds 2
    Write-Host "2. Opening free HTTPS public tunnel via localhost.run (Press Ctrl+C to stop)..."
    $tunnelLog = Join-Path $runtimeRoot 'tunnel.log'
    if (Test-Path $tunnelLog) { Remove-Item $tunnelLog -Force }
    & ssh.exe -o StrictHostKeyChecking=no -o UserKnownHostsFile=NUL -o ServerAliveInterval=30 -T -R 80:127.0.0.1:3000 nokey@localhost.run 2>&1 | ForEach-Object {
        $line = $_.ToString()
        Write-Host $line
        if ($line -match '(https://[a-z0-9]+\.lhr\.life)') {
            $publicUrl = $Matches[1]
            Set-Content -Path (Join-Path $runtimeRoot 'public-origin.txt') -Value $publicUrl -NoNewline
            Write-Host "`n=======================================================" -ForegroundColor Green
            Write-Host " Ommaviy HTTPS havola tayyor: $publicUrl" -ForegroundColor Green
            Write-Host "=======================================================`n" -ForegroundColor Green
        }
    }
} finally {
    if ($webProc -and -not $webProc.HasExited) { Stop-Process -Id $webProc.Id -Force }
    if ($phpProc -and -not $phpProc.HasExited) { Stop-Process -Id $phpProc.Id -Force }
}
