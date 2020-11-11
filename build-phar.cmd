@echo off
REM This script allow you to use the build.php from PocketMine to build the server software instead of using DevTools.
REM Made by dtcu0ng with love.
REM You can edit everything you want on this script.


set BUILD_SCRIPT=build\build.php
:PHPE
set PHP_BINARY=bin\php\php.exe
if exist %PHP_BINARY% (
echo PHP Binary found. Going to building progress.
goto BUILD
) else (
goto PHPV
)

:PHPV
set PHP_BINARY=php (
echo PHP Binary found. Going to building progress.
goto BUILD
) else (
echo PHP Binary not found. The progress can not continue.
echo Press any key to exit
)

:BUILD
if exist %BUILD_SCRIPT% (
echo Build script found. Building server software...
) else (
echo "Couldn't find build script. The progress can not continue."
echo Press any key to exit.
pause
exit 1
)
)
%PHP_BINARY% -c bin\php %BUILD_SCRIPT% %*
echo Build completed.
pause
exit