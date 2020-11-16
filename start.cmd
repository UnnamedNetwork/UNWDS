@echo off
TITLE Bootstraper
cd /d %~dp0
:UNWS2
if exist UNWDS.phar (
	set UNWDS_FILE=UNWDS.phar
    echo =========================
	echo = Server software found =
	echo = Server starting...    =
	echo =========================
	goto ServerStart
) else (
	goto UNWDS
)
:UNWDS
if exist UNWDelicatedSoftware.phar (
	set UNWDS_FILE=UNWDelicatedSoftware.phar
    echo =========================
	echo = Server software found =
	echo = Server starting...    =
	echo =========================
	goto ServerStart
) else (
	goto PMMP
)
:PMMP
if exist PocketMine-MP.phar (
	set UNWDS_FILE=PocketMine-MP.phar
	echo =========================
	echo = Server software found =
	echo = Server starting...    =
	echo =========================
	goto ServerStart
) else (
	cls
    echo =================================
	echo = Server software was not found = 
    echo =================================
	echo You can get the lastest build of PocketMine-MP in https://github.com/pmmp/PocketMine-MP/releases
	echo or
	echo You can get the lastest build of UNWDS in https://github.com/dtcu0ng/UnnamedNetwork/releases
	pause
	exit
)
:ServerStart
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
	%PHP_BINARY% -c bin\php %UNWDS_FILE% %*
        pause
)
