## UNWDS (UNWDelicatedSoftware)	
[![Build Status](https://travis-ci.com/UnnamedNetwork/UNWDS.svg?branch=stable)](https://travis-ci.com/UnnamedNetwork/UNWDS)

A open-source server software for Minecraft: Bedrock Edition. UNWDS is a fork of [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).
You can test lastest version of UNWDS in: play.dtcg.xyz:19132

## Documentation
- [Documentation](DOCUMENT.md)
- [How to install](INSTALL.md)
- [SpoonMask documentation](https://github.com/dtcu0ng/SpoonMask/blob/main/DOCUMENT.md)

## For developers
 * [Building and running from source](BUILDING.md)
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins

## UNWDS source code update info:
+ UNWDS is just a PocketMine-MP distro with pre-packaged SpoonMask into it, so when the PocketMine-MP (stable) have new source changes (except Submodules), that changes will go directly into UNWDS (stable) within 24-48 hours
+ If the newer source commit run tests fail, we will stop the update and will working at that commit. After that, the commit auto-update function will continue.
+ Submodules and test configs update will be tested manually. If submodules is OK, we will push an update into that submodules.

## About plugins:
+ UNWDS is a PocketMine-MP bypass SpoonDetector distro. I don't modified into the PocketMine-MP important cores (just modified for software's behaviour after adding some constants like the distro name or custom version.)
+ I will write a document describe how the SpoonMask work as soon as possible.
+ Read [this](https://github.com/dtcu0ng/UNWDS/releases/tag/2.0.1-RC2) release note to get more info (if you're running version <2.0.1-RC2).
+ Read [this](https://github.com/dtcu0ng/UNWDS/releases/tag/2.0.4%2Bdev.19) release note to get more info with the Spoon included plugins.

## Contribution:
+ Feel free to open the issues, PR, etc...
+ But you MUST submit a bug with an console logs, system information, crashdumps, how to reproduce it,... 
+ This play a big role in our inprovement this project	

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.
