## UNWDS (UNWDelicatedSoftware)	
[![CI](https://github.com/UnnamedNetwork/UNWDS/workflows/CI/badge.svg)](https://github.com/UnnamedNetwork/UNWDS/actions) [![download](https://img.shields.io/github/v/release/unnamednetwork/unwds)](https://github.com/UnnamedNetwork/UNWDS/releases)

A open-source server software for Minecraft: Bedrock Edition. UNWDS is a fork of [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).

## Documentation
- [Documentation](DOCUMENT.md)
- [How to install](INSTALL.md)
- [SpoonMask documentation](https://github.com/dtcu0ng/SpoonMask/blob/main/DOCUMENT.md)

## For developers
 * [Building and running from source](BUILDING.md)
 * [Developer documentation](https://devdoc.pmmp.io) - General documentation for PocketMine-MP plugin developers
 * [Latest API documentation](https://jenkins.pmmp.io/job/PocketMine-MP-doc/doxygen/) - Doxygen documentation generated from development
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins
 * [Latest Actions server software artifacts](https://nightly.link/UnnamedNetwork/UNWDS/workflows/main/stable/UNWDS.zip) - Where you can get the latest 'stable' development server phar on current commit. This is not recommended.
 * [Commit build history](https://github.com/UnnamedNetwork/unwds-builds/tree/master/branch/stable/) - Like artifacts link above, also contain older builds.
 
## About source code update
+ UNWDS is a PocketMine-MP distribution with pre-packaged SpoonMask into it, so when the PocketMine-MP (stable) have new source changes (except API major changes), that changes will go directly into UNWDS (stable) within 24-48 hours
+ If the newer source commit run tests fail (very rare), we will stop the update and will working at that commit. After we fixed that, the commit auto-update function will continue.

## About API & plugins
+ UNWDS is a modified PocketMine-MP distrobution to bypass the SpoonDetector. UNWDS is not heavily modified or touch into any PocketMine-MP API (just modified into server software's behaviour after adding some constants like the distro name or custom version number.)
+ Because PocketMine-MP consider the version number is the API version, so we need to make a fake (custom) version number. This fake (custom) number just displayed on the console, some messages player can get when use '/version' command, not affect the API system or something else.
+ This changes not affected with API or Plugins for PocketMine-MP.

## Contribution:
+ Feel free to open the issues, PR, etc...
+ But you MUST submit a bug with an console logs, system information, crashdumps, how to reproduce it,... 
+ This play a big role in our inprovement this project	

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.
