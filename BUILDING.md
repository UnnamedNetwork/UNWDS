# Building & Running from source
This will follow the PocketMine-MP Building guide.
## Pre-requisites
- A bash shell (git bash is sufficient for Windows)
- [`git`](https://git-scm.com) available in your shell
- PHP 7.4 or newer available in your shell

## Custom PHP binaries
Because PocketMine-MP requires several non-standard PHP extensions and configuration, PMMP provides scripts to build custom binaries for running PocketMine-MP, as well as prebuilt binaries.

- [Prebuilt binaries](https://jenkins.pmmp.io/job/PHP-7.4-Aggregate)
- [Compile scripts](https://github.com/pmmp/php-build-scripts) are provided as a submodule in the path `build/php`

If you use a custom binary, you'll need to replace `composer` usages in this guide with `path/to/your/php path/to/your/composer.phar`.

## Building `UNWDS.phar`
Download the source code from [Github](https://github.com/UnnamedNetwork/UNWDS/)

Run `build/server-phar.php` using your preferred PHP binary or use `./build-phar.sh` (Linux/MacOS) `build-phar.cmd`(Windows). It'll drop a `UNWDS.phar` into the current working directory.

You can also use the `--out` option to change the output filename.

## Running UNWDS from source code
Run `src/pocketmine/PocketMine.php` using your preferred PHP binary or you can using `./startsrc.sh` (Linux), `startsrc.cmd`(Windows) to run UNWDS from source code.
