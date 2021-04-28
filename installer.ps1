#Requires -Version 5.1
# Require PowerShell v5.1 or above to prevent errors. Windows 10/Server version 1607 or newer have already pre-installed.

$scriptVersion = "1.0.1"
$host.ui.RawUI.WindowTitle = "UNWDS Installer (v$scriptVersion)"
$ProgressPreference = 'SilentlyContinue'

# First, set PS's security protocol to TLS1.2 to avoid Github Releases download problems.
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12

# Clean up old files
function CleanUp {
    Write "[*] Welcome to UNWDS Installer!";
    Write "`n"
    Write "[*] Windows x64 detected. Installing...";
    Write "`n"
    Write "[1/3] Cleaning up";
    Write "`n"
    Remove-Item PHP-7.4-Windows-x64 -Recurse -Force -erroraction 'silentlycontinue'
    Remove-Item bin -Recurse -Force -erroraction 'silentlycontinue'
    Remove-Item PHP-7.4-Windows-x64.zip -Force -erroraction 'silentlycontinue'
    Remove-Item UNWDS.phar -Force -erroraction 'silentlycontinue'
    Remove-Item start.cmd -erroraction 'silentlycontinue'
    Remove-Item vc_redist.x64.exe -erroraction 'silentlycontinue'
}

function ErrorCleanUp {
    Clear
    Write "[ERR] Installation was failed because an error occurred";
    Write "[*] Cleaning up files because installation errors...";
    Write "`n"
    Remove-Item PHP-7.4-Windows-x64 -Recurse -Force -erroraction 'silentlycontinue'
    Remove-Item bin -Recurse -Force -erroraction 'silentlycontinue'
    Remove-Item PHP-7.4-Windows-x64.zip -Force -erroraction 'silentlycontinue'
    Remove-Item UNWDS.phar -Force -erroraction 'silentlycontinue'
    Remove-Item start.cmd -erroraction 'silentlycontinue'
    Remove-Item vc_redist.x64.exe -erroraction 'silentlycontinue'
    Write "Some file does not exist. UNWDS will not run if missing files. Please try to run install script again."
    $host.ui.RawUI.WindowTitle = "Windows PowerShell" #set the window title back to default
}

# Prepare variables
$repoName = "UnnamedNetwork/UNWDS"
$assetPattern = "UNWDS.phar"
$releasesUri = "https://api.github.com/repos/$repoName/releases/latest"
$asset = (Invoke-WebRequest $releasesUri | ConvertFrom-Json).assets | Where-Object name -like $assetPattern
$downloadUri = $asset.browser_download_url

function Main {
    # First, download the server software phar and startup script...
    Write "[2/3] Downloading UNWDS latest release...";
    Invoke-WebRequest -Uri $downloadUri -OutFile "UNWDS.phar"
    Invoke-WebRequest -Uri "https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.cmd" -OutFile "start.cmd"
    Write "`n"
    # Second, download PHP and extract it.
    Write "[3/3] Downloading PHP 7.4 (Windows x64)...";
    Invoke-WebRequest -Uri "https://jenkins.pmmp.io/job/PHP-7.4-Aggregate/lastSuccessfulBuild/artifact/PHP-7.4-Windows-x64.zip" -OutFile "PHP-7.4-Windows-x64.zip"
    Expand-Archive -LiteralPath PHP-7.4-Windows-x64.zip -Force
    Get-ChildItem -Path "PHP-7.4-Windows-x64" -Recurse |  Move-Item -Destination .
    Remove-Item PHP-7.4-Windows-x64.zip -Force -erroraction 'silentlycontinue'
    Remove-Item PHP-7.4-Windows-x64 -Recurse -Force -erroraction 'silentlycontinue'
    Write "`n"
}

function CheckFiles {
    if (-not (Test-Path -Path UNWDS.phar)) {
        ErrorCleanUp
        throw 'The file (UNWDS.phar) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
    }
    if (-not (Test-Path -Path start.cmd)) {
        ErrorCleanUp
        throw 'The file (start.cmd) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
    }
    if (-not (Test-Path -Path bin\php\php.exe)) {
        ErrorCleanUp
        throw 'The file (php.exe) does not exist. UNWDS will not run if missing files. Please try to run install script again.'
    } else {
    }
    Write "[*] Everything done! Run ./start.cmd to start UNWDS";
    Write "[*] Make sure you have installed Microsoft Visual C++ 2015-19 Redistributable (x64) on your PC.";
    Write "[*] If not, you can run vc_redist.x64.exe in your cuurent folder to install it."
}

if ([Environment]::Is64BitProcess -ne [Environment]::Is64BitOperatingSystem)
{
    Write "You are running 32-bit system or you're running 32-bit PowerShell, that is not supported by UNWDS.";
    Write "Please consider upgrade to 64-bit system or use PowerShell (x64) to run UNWDS.";
    exit
}
else
{
    CleanUp
    Main
    CheckFiles
    $host.ui.RawUI.WindowTitle = "Windows PowerShell" #set the window title back to default
}