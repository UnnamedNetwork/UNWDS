@echo off
TITLE UNWDelicatedSoftware software for Minecraft: Bedrock Edition
cd /d %~dp0
:UNWDS
echo ==========UNWDelicatedSoftware software load==========
echo = - Server will start...
echo = - Checking Server file...
if exist UNWDelicatedSoftware.phar (
	set UNWDS_FILE=UNWDelicatedSoftware.phar
	echo = - Found
	echo = - Server starting...
	echo ==================================================
) else (
	echo = - UNWDelicatedSoftware.phar not found
	echo = - Server software can be found at github.com/CuongZ/UNWDelicatedSoftware/releases
	echo = - Finding UNWDSX software images...
    echo ==================================================
	goto UNWDSX
)
:UNWDSX
if exist UNWDSX.phar (
	set UNWDS_FILE=UNWDSX.phar
	echo = - Found
	echo = - Server starting...
	echo ==================================================
) else (
	echo = - UNWDSX.phar not found
	echo = - Server software can be found at github.com/CuongZ/UNWDelicatedSoftware/releases
    echo ==================================================
	pause
	exit 1
)
if exist bin\php\php.exe (
	set PHPRC=""
	set PHP_BINARY=bin\php\php.exe
) else (
	set PHP_BINARY=php
)

if exist bin\mintty.exe (
	start "" bin\mintty.exe -o Columns=88 -o Rows=32 -o AllowBlinking=0 -o FontQuality=3 -o Font="Consolas" -o FontHeight=10 -o CursorType=0 -o CursorBlinks=1 -h error -t "UNWDelicatedSoftware" -i bin/unwicon.ico -w max %PHP_BINARY% %UNWDS_FILE% --enable-ansi %*
) else (
	REM
	%PHP_BINARY% -c bin\php %UNWDS_FILE% %* || pause
)
