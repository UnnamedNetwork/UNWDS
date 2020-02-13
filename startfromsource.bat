@echo off
REM This code is used to run SteadFast2 on source code (using the src folder).
REM Set TIMEOUT to how many seconds you want in between when the server stops to when the next restart takes place
REM Prepare your PHP binary first.
REM This file from : https://github.com/Todo56/SteadFastScripts/blob/master/startsrc.cmd 
set TIMEOUT=0
cd /d %~dp0
netstat -o -n -a | findstr 0.0.0.0:19132 > NUL
if %ERRORLEVEL% equ 0 (
goto :loop
) else (
echo "Script has been initialized."
goto :start
)
:loop
ping 127.0.0.1 -n %TIMEOUT% > NUL
netstat -o -n -a | findstr 0.0:19132 > NUL
if %ERRORLEVEL% equ 0 (
goto :loop
) else (
ping 127.0.0.1 -n %TIMEOUT% > NUL
echo "Server stopped. It'll be restarted in %TIMEOUT% second(s). You can press Ctrl+C to stop the restart process if you don't want to restart."
goto :start
)
:start
if exist bin\php\php.exe (
set PHP_BINARY=bin\php\php.exe
) else (
set PHP_BINARY=php
)
if exist src\pocketmine\PocketMine.php (
set POCKETMINE_FILE=src\pocketmine\PocketMine.php
) else (
echo "Couldn't find Source Code installation..."
pause
exit 1
)
)
%PHP_BINARY% -c bin\php %POCKETMINE_FILE% %*


goto :loop