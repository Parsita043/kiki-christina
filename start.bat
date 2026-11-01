@echo off
cd /d "%~dp0"
set "PHPEXE="
for %%i in (php.exe) do set "PHPEXE=%%~$PATH:i"
if not defined PHPEXE (
  echo PHP is not installed. Run this, then open a new terminal and try again:
  echo     winget install PHP.PHP.8.3
  pause
  exit /b 1
)
for %%i in ("%PHPEXE%") do set "PHPDIR=%%~dpi"
echo Kiki Christina running at http://localhost:8000  - close this window to stop
start "" http://localhost:8000
php -d extension_dir="%PHPDIR%ext" -d extension=pdo_sqlite -d extension=sqlite3 -S localhost:8000
