# UNWDS Installation Guide:
This will follow the PocketMine-MP install guide.
## Windows (manually):
+ Follow the instruction from PocketMine-MP [here](https://pmmp.readthedocs.io/en/rtfd/installation.html)
+ Download ["start.cmd"](https://github.com/UnnamedNetwork/UNWDS/blob/stable/start.cmd) and your prefered ["UNWDS.phar"](https://github.com/UnnamedNetwork/UNWDS/releases) from UNWDS repo and replace on it.
+ Start the server!

## Windows (using PowerShell script): faster, but for advanced user
+ Requirement: Windows PowerShell v5.1 or above installed (Windows 10/Server version 1607 or above have already pre-installed).
+ Older OS can grab new PowerShell version by [click here](https://github.com/PowerShell/PowerShell/releases/). Choose PowerShell-version-win-x64.msi. Once downloaded, install it.
+ On PowerShell window use this command:
```sh

Invoke-WebRequest -Uri "https://unnamednetwork.github.io/UNWDS/scripts/installer.ps1" -OutFile "installer.ps1"; powershell -ExecutionPolicy Bypass -File .\installer.ps1

```
+ Now, type ./start.cmd to run the server!

+ Error fix: 
```
"Invoke-WebRequest : The request was aborted: Could not create SSL/TLS secure channel."
```
to fix that, you just need use this command and re-run the installer:
```sh

[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12

```

+ Note: Do not remove either `current_version.info` or `remote_version.info` (if present). These files for update function.

## Linux/MacOS (use unnamednetwork.github.io/UNWDS/scripts/installer.sh)
+ Create a directory which you want to install PocketMine-MP into, and cd into it.
+ Then use `curl` to install PocketMine-MP using the following command:
```sh

$ curl -sL https://unnamednetwork.github.io/UNWDS/scripts/installer.sh | bash -s -

```
or, if you don’t have `curl`, try `wget`:
```sh

$ wget -q -O - https://unnamednetwork.github.io/UNWDS/scripts/installer.sh | bash -s -

```
+ Now, type ./start.sh to run the server!

## Linux/MacOS (manually)
+ Follow the instruction from PocketMine-MP for Linux/MacOS [here](https://pmmp.readthedocs.io/en/rtfd/installation/get-dot-pmmp-dot-io.html)
+ After complete, delete the PocketMine-MP phar file and "start.sh" by:
```sh

$ rm PocketMine-MP.phar

```
and
```sh

$ rm start.sh

```
+ Then, download the UNWDS startup file by:
```sh

$ wget https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.sh && chmod +x ./start.sh

```
+ Next, download the UNWDS phar file:

If you want to use Stable releases, use this:
```sh

$ wget https://github.com/UnnamedNetwork/UNWDS/releases/download/{YOUR_PREFERRED_VERESION}/UNWDS.phar

```

If you want to use latest stable development releases, use this:
```sh

$ wget https://github.com/UnnamedNetwork/unwds-builds/releases/download/stable-build/UNWDS.phar


```

or you want to download specific build, use this link:
```sh

$ wget https://github.com/UnnamedNetwork/unwds-builds/raw/master/branch/stable/old/<action-run-number>/UNWDS.phar

```


+ Check action-run-number [here](https://github.com/UnnamedNetwork/UNWDS/actions)
+ action-run-number is the number begin with # and it next to [CI]
+ Replace these to command above to download the server software phar file (don't include #)
+ Example: You want to download CI #91, you need this command:
```sh

$ wget https://github.com/UnnamedNetwork/unwds-builds/raw/master/branch/stable/old/91/UNWDS.phar

```

+ Now, type ./start.sh to run the server!

## Fix Linux/MacOS permission issues
+ If you are facing with somethings like this:
 ```sh

bash: ./start.sh: Permission denied

```
or 

 ```sh

bash: ./UNWDS.phar: Permission denied

```

try use these commands:

 ```sh

$ chmod +x ./start.sh

```
 ```sh

$ chmod +x ./UNWDS.phar

```

+ And run ./start.sh again!

