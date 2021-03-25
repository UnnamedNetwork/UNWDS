## UNWDS (UNWDelicatedSoftware)	
[![CI](https://github.com/UnnamedNetwork/UNWDS/workflows/CI/badge.svg)](https://github.com/UnnamedNetwork/UNWDS/actions) [![download](https://img.shields.io/github/v/release/unnamednetwork/unwds)](https://github.com/UnnamedNetwork/UNWDS/releases)

A open-source server software for Minecraft: Bedrock Edition. UNWDS is a fork of [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).

## Documentation
- [Documentation](DOCUMENT.md)
- [How to install](INSTALL.md)
- [SpoonMask documentation](https://github.com/dtcu0ng/SpoonMask/blob/main/DOCUMENT.md)

## For developers
 * [Building and running from source](BUILDING.md)
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins
 * [Latest source commit built](https://github.com/dtcu0ng/UNWDS_Output/tree/master/ci_build_output/stable/latest/) - for who want to get latest some supported branch development commit build, it may have some unexpected errors. I do not recommend you to use this version. These server software are processed by PreProcessor and it's like live preview...
 
## About UNWDS source code update:
+ UNWDS is just a PocketMine-MP distro with pre-packaged SpoonMask into it, so when the PocketMine-MP (stable) have new source changes (except Major updates, Submodules), that changes will go directly into UNWDS (stable) within 24-48 hours
+ If the newer source commit run tests fail, we will stop the update and will working at that commit. After that, the commit auto-update function will continue.
+ Submodules and test configs update will be tested manually. If submodules is OK, we will push an update into that submodules.
+ If a major API updates released, we don't gurantee anything with you. We need to change the SpoonMask and the base source code, optimize it,... So we don't gurantee for the time we release the final version, but the source code will be upload ASAP.

## About plugins:
+ UNWDS is a modifiled PocketMine-MP to bypass SpoonDetector. I don't modified into the PocketMine-MP important cores (just modified for software's behaviour after adding some constants like the distro name or custom version.)
+ Read [this](https://github.com/dtcu0ng/UNWDS/releases/tag/2.0.1-RC2) release note to get more info (if you're running version <2.0.1-RC2).
+ Read [this](https://github.com/dtcu0ng/UNWDS/releases/tag/2.0.4%2Bdev.19) release note to get more info with the Spoon included plugins.

## Contribution:
+ Feel free to open the issues, PR, etc...
+ But you MUST submit a bug with an console logs, system information, crashdumps, how to reproduce it,... 
+ This play a big role in our inprovement this project	

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.
