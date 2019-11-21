@echo off
TITLE UNWDelicatedSoftware for Minecraft: Bedrock Edition
cd /d %~dp0

if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist PocketMine-MP.phar (
	set SOFTWARE_FILE=PocketMine-MP.phar
) else if exist UNWDelicatedSoftware.phar (
    set SOFTWARE_FILE=UNWDelicatedSoftware.phar
) else if exist src\pocketmine\PocketMine.php (
    set SOFTWARE_FILE=start.php
) else (
	echo UNWDelicatedSoftware.phar not found
	echo Downloads can be found at github.com/CuongZ/UNWDelicatedSoftware/releases
	pause
	exit 1
)

if exist bin\mintty.exe (
	start %PHP_BINARY% %SOFTWARE_FILE% --enable-ansi %*
) else (
	REM pause on exitcode != 0 so the user can see what went wrong
	%PHP_BINARY% -c bin\php %SOFTWARE_FILE %* || pause
)
