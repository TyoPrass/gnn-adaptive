@echo off
REM Quick Test Flask GNN API

echo ========================================
echo   Testing Flask GNN API
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python is not installed or not in PATH
    pause
    exit /b 1
)

REM Check if Flask API is running
echo Checking if Flask API is running...
curl -s http://localhost:5000/health >nul 2>&1
if %errorlevel% neq 0 (
    echo.
    echo WARNING: Flask API is not running!
    echo Please start Flask API first:
    echo    python app.py
    echo.
    echo Or run: start_api.bat
    echo.
    pause
    exit /b 1
)

echo Flask API is running!
echo.
echo ========================================
echo   Running All Tests
echo ========================================
echo.

REM Run tests
python test_api.py

echo.
echo ========================================
echo   Tests Completed
echo ========================================
echo.

pause
