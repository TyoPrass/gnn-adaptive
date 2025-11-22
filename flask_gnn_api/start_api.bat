@echo off
REM Start Flask GNN API

echo ========================================
echo   Starting GNN Adaptive Learning API
echo ========================================
echo.

REM Check if Python is installed
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: Python is not installed or not in PATH
    echo Please install Python 3.8+ first
    pause
    exit /b 1
)

REM Check if virtual environment exists
if not exist "venv" (
    echo Creating virtual environment...
    python -m venv venv
    echo.
)

REM Activate virtual environment
echo Activating virtual environment...
call venv\Scripts\activate.bat
echo.

REM Install requirements
echo Installing/Updating dependencies...
pip install -r requirements.txt
echo.

REM Create models directory if not exists
if not exist "models" (
    echo Creating models directory...
    mkdir models
    echo.
)

REM Create logs directory if not exists
if not exist "logs" (
    echo Creating logs directory...
    mkdir logs
    echo.
)

REM Start Flask API
echo ========================================
echo   API Starting on http://localhost:5000
echo ========================================
echo.
echo Press Ctrl+C to stop the server
echo.

python app.py

pause
