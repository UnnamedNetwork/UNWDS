# UNWDS Installation Guide:
This will follow the PocketMine-MP install guide.
## Windows:
+ Follow the instruction from PocketMine-MP [here](https://pmmp.readthedocs.io/en/rtfd/installation.html)
+ Download ["start.cmd"](https://github.com/UnnamedNetwork/UNWDS/blob/stable/start.cmd) and your prefered ["UNWDS.phar"](https://github.com/UnnamedNetwork/UNWDS/releases) from UNWDS repo and replace on it.
+ Start the server!

## Linux/MacOS (use dtcu0ng.github.io/UNWDS/scripts/installer.sh)
+ Create a directory which you want to install PocketMine-MP into, and cd into it.
+ Then use `curl` to install PocketMine-MP using the following command:
```sh

$ curl -sL https://dtcu0ng.github.io/UNWDS/scripts/installer.sh | bash -s -

```
or, if you don’t have `curl`, try `wget`:
```sh

$ wget -q -O - https://dtcu0ng.github.io/UNWDS/scripts/installer.sh | bash -s -

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

$ wget https://github.com/UnnamedNetwork/unwds-builds/tree/master/branch/stable/old/<action-run-number>/UNWDS.phar

```


+ Check action-run-number [here](https://github.com/UnnamedNetwork/UNWDS/actions)
+ action-run-number is the number begin with # and it next to [CI]
+ Replace these to command above to download the server software phar file (don't include #)
+ Example: You want to download CI #91, you need this command:
```sh

$ wget https://github.com/UnnamedNetwork/unwds-builds/tree/master/branch/stable/old/91/UNWDS.phar

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

