@echo off
echo.

start "" "C:\xampp\php\php.exe" -S 127.0.0.1:8000

timeout /t 2 /nobreak >nul

start "" "http://127.0.0.1:8000"
