@echo off
REM Quick Start - Run Flask API and Test

echo ========================================
echo   Flask GNN API - Quick Start
echo ========================================
echo.

cd /d "%~dp0"

REM Check Python
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python not found!
    echo Please install Python 3.8+ first
    pause
    exit /b 1
)

REM Create venv if not exists
if not exist "venv" (
    echo Creating virtual environment...
    python -m venv venv
    echo.
)

REM Activate venv
echo Activating virtual environment...
call venv\Scripts\activate.bat

REM Install requirements
echo Installing dependencies...
pip install -r requirements.txt >nul 2>&1

REM Create directories
if not exist "models" mkdir models
if not exist "logs" mkdir logs

echo.
echo ========================================
echo   Starting Flask API in background...
echo ========================================
echo.

REM Start Flask in background
start /B python app.py

REM Wait for API to start
echo Waiting for API to start...
timeout /t 5 /nobreak >nul

REM Test API
echo.
echo ========================================
echo   Testing API
echo ========================================
echo.

python test_api.py

echo.
echo ========================================
echo   Quick Start Completed
echo ========================================
echo.
echo Flask API is running in background
echo To stop: run stop_api.bat
echo.

pause
