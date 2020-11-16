# UNWDS Installation Guide:
This will follow the PocketMine-MP install guide.
## Windows:
+ Follow the instruction from PocketMine-MP [here](https://pmmp.readthedocs.io/en/rtfd/installation.html)
+ Download ["start.cmd"](https://github.com/UnnamedNetwork/UNWDS/blob/stable/start.cmd) and your prefered ["UNWDS.phar"](https://github.com/UnnamedNetwork/UNWDS/releases) from UNWDS repo and replace on it.
+ Start the server!

## Linux/MacOS
+ Follow the instruction from PocketMine-MP for Linux/MacOS [here](https://pmmp.readthedocs.io/en/rtfd/installation/get-dot-pmmp-dot-io.html)
+ After complete, delete the PocketMine-MP phar file and "start.sh" by:
```sh

rm -rf ./PocketMine-MP.phar

```
and
```sh

rm -rf ./start.sh

```
+ Then, download the UNWDS phar file and startup file by:
```sh

wget https://raw.githubusercontent.com/UnnamedNetwork/UNWDS/stable/start.sh

```
```sh

wget https://github.com/UnnamedNetwork/UNWDS/releases/download/{YOUR_PREFERRED_VERESION}/UNWDS.phar

```
Lastest Stable version: v2.0.3

Lastest Alpha version: v2.0.4%2Bdev.19

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

chmod +x ./start.sh

```
 ```sh

chmod +x ./UNWDS.phar

```

+ And run ./start.sh again!

