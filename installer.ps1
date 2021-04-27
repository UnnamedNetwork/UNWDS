#Requires -Version 5.1
# require PowerShell v5.1 or above to prevent errors. Windows 10/Server version 1607 or above already have PowerShell v5.1 built-in.

# First, set PS's security protocol to TLS1.2 to avoid Github Releases download problems.
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12

# Clean up old files
function CleanUp {
Write "[*] Cleaning up old files for install or because installation errors..";
Write "`n"
Remove-Item PHP-7.4-Windows-x64 -Recurse
Remove-Item bin -Recurse
Remove-Item PHP-7.4-Windows-x64.zip
Remove-Item UNWDS.phar
Remove-Item start.cmd
Remove-Item vc_redist.x64.exe
Write "`n"
Write "[*] If you see any error(s) above THIS message, don't worry because that's normal for cleanup progress.";
Write "[*] Any error(s) appear below this message, that would be considered as error(s).";
Write "`n"
}

# Prepare variables
$repoName = "UnnamedNetwork/UNWDS"
$assetPattern = "UNWDS.phar"
$releasesUri = "https://api.github.com/repos/$repoName/releases/latest"
$asset = (Invoke-WebRequest $releasesUri | ConvertFrom-Json).assets | Where-Object name -like $assetPattern
$downloadUri = $asset.browser_download_url

function Main {
    # First, download the server software phar and startup script...
    Write "[*] Let's install UNWDS!";
    Write "`n"
    Write "[*] Downloading UNWDS (stable) latest release... (UNWDS.phar)";
    Invoke-WebRequest -Uri $downloadUri -OutFile "UNWDS.phar"
    Write "[*] Downloaded (UNWDS.phar)";
    Write "`n"
    Write "[*] Downloading (start.cmd)";
    Invoke-WebRequest -Uri "https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.cmd" -OutFile "start.cmd"
    Write "[*] Downloaded start script (start.cmd)";
    Write "`n"
    # Second, download PHP and extract it.
    Write "[*] Downloading PHP 7.4 (Windows x64)...";
    Invoke-WebRequest -Uri "https://jenkins.pmmp.io/job/PHP-7.4-Aggregate/lastSuccessfulBuild/artifact/PHP-7.4-Windows-x64.zip" -OutFile "PHP-7.4-Windows-x64.zip"
    Write "[*] Downloaded (PHP-7.4-Windows-x64.zip)";
    Write "`n"
    Write "[*] Unzipping (PHP-7.4-Windows-x64.zip)";
    Expand-Archive -LiteralPath PHP-7.4-Windows-x64.zip -Force
    Get-ChildItem -Path "PHP-7.4-Windows-x64" -Recurse |  Move-Item -Destination .
    Remove-Item PHP-7.4-Windows-x64.zip
    Remove-Item PHP-7.4-Windows-x64 -Recurse
    Write "[*] Unzipped (PHP-7.4-Windows-x64.zip)";
    Write "`n"
}

function BeforeExit {
    if (-not (Test-Path -Path UNWDS.phar)) {
        CleanUp
        throw 'The file (UNWDS.phar) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
        Write-Host 'Check file (UNWDS.phar) OK'
    }
    if (-not (Test-Path -Path start.cmd)) {
        CleanUp
        throw 'The file (start.cmd) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
        Write-Host 'Check file (start.cmd) OK'
    }
    if (-not (Test-Path -Path bin\php\php.exe)) {
        CleanUp
        throw 'The file (php.exe) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
        Write-Host 'Check file (php.exe) OK'
    }
    Write "`n"
    Write "[*] Everything done! Run ./start.cmd to start UNWDS";
}

if ([Environment]::Is64BitProcess -ne [Environment]::Is64BitOperatingSystem)
{
    Write "You are running 32-bit system, that is not supported by UNWDS.";
    Write "Please consider upgrade to 64-bit system to run UNWDS.";
    exit
}
else
{
    Write "Windows x64 detected. Continue installing...";
    CleanUp
    Main
    BeforeExit
}