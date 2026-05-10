@echo off
cd /d "c:\xampp\htdocs\fenosoa\S4-projet_trinome\regime"

REM Download composer.phar if it doesn't exist
if not exist composer.phar (
    echo Downloading Composer...
    powershell -Command "[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.SecurityProtocolType]::Tls12; (New-Object System.Net.WebClient).DownloadFile('https://getcomposer.org/composer.phar', 'composer.phar')"
    if errorlevel 1 (
        echo Failed to download Composer. Trying alternative method...
        bitsadmin /transfer myDownloadJob /download /resume "https://getcomposer.org/composer.phar" "%CD%\composer.phar"
    )
)

REM Install dependencies
echo Installing dependencies...
c:\xampp\php\php.exe composer.phar install --no-interaction

pause
