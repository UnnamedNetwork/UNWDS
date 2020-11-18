# UNWDS Installation Guide:
This will follow the PocketMine-MP install guide.
## Windows:
+ Follow the instruction from PocketMine-MP [here](https://pmmp.readthedocs.io/en/rtfd/installation.html)
+ Download ["start.cmd"](https://github.com/UnnamedNetwork/UNWDS/blob/stable/start.cmd) and your prefered ["UNWDS.phar"](https://github.com/UnnamedNetwork/UNWDS/releases) from UNWDS repo and replace on it.
+ Start the server!

## Linux/MacOS (use github.dtcg.xyz/UNWDS/installer.sh)
+ Create a directory which you want to install PocketMine-MP into, and cd into it.
+ Then use `curl` to install PocketMine-MP using the following command:
```sh

$ curl -sL https://github.dtcg.xyz/UNWDS/scripts/installer.sh | bash -s -

```
or, if you don’t have `curl`, try `wget`:
```sh

$ wget -q -O - https://github.dtcg.xyz/UNWDS/scripts/installer.sh | bash -s -

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
+ Then, download the UNWDS phar file and startup file by:
```sh

$ wget https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.sh && chmod +x ./start.sh

```
+ Next, download the UNWDS phar file:

If you want to use Stable release, use this:
```sh

$ wget https://github.com/UnnamedNetwork/UNWDS/releases/download/{YOUR_PREFERRED_VERESION}/UNWDS.phar

```
Lastest Stable version: v2.0.3

If you want to use Alpha release, use this:
```sh

$ wget https://github.com/UnnamedNetwork/UNWDS/releases/download/{YOUR_PREFERRED_VERESION}%2Bdev.{BUILD_NUMBER}/UNWDS.phar

```

Lastest Alpha version: v2.0.4%2Bdev.19 ("PREFERRED_VERSION" is v2.0.4, "BUILD_NUMBER" is 19)


+ Check release and version number [here](https://github.com/UnnamedNetwork/UNWDS)


Replace these to command above to download the server software phar file
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

$ chmod 600 ./start.sh

```
 ```sh

$ chmod 600 ./UNWDS.phar

```

+ And run ./start.sh again!

