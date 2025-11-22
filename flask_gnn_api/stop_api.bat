@echo off
REM Stop Flask GNN API

echo Stopping Flask GNN API...

REM Find and kill Python processes running Flask
taskkill /F /IM python.exe /FI "WINDOWTITLE eq *app.py*" 2>nul

if %errorlevel% equ 0 (
    echo API stopped successfully
) else (
    echo No running API found or failed to stop
)

pause
