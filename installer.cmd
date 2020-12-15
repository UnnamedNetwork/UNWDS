@echo off
title Install UNWDS (for Windows)
echo[
echo checking that you have already PHP binary downloaded...
echo[
if exist bin\php\php.exe (
    echo detected.
    goto STEP2
) else (
    echo not found.
    echo[
    echo you can get pre-built PHP binary from PocketMine-MP: https://jenkins.pmmp.io/job/PHP-7.4-Aggregate/lastSuccessfulBuild/artifact/PHP-7.4-Windows-x64.zip
    echo[
    echo script can't continue without PHP binary.
    pause
    exit
)
:STEP2
echo[
echo checking that you have already server software downloaded...
echo[
if exist UNWDS.phar (
    echo detected.
    goto STEP3
) else (
    echo not found.
    echo[
    echo downloading now...
    curl -sLJO https://github.com/UnnamedNetwork/UNWDS/releases/latest/download/UNWDS.phar -m 3 -O UNWDS.phar
    goto STEP3
)
:STEP3
echo[
echo checking that you have already server start script downloaded...
echo[
if exist start.cmd (
    echo detected.
    goto START
) else (
    echo not found.
    echo[
    echo downloading now...
    curl -sLJ https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.cmd -m 3 -O start.cmd
    goto START
)
:START
echo[
echo cleaning up...
echo UNWDS is installed. Run start.cmd to start the server!
pause
del install.cmd
exit