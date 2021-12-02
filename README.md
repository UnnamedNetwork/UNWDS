## UNWDS (UNWDelicatedSoftware)	
[![CI](https://github.com/UnnamedNetwork/UNWDS/workflows/CI/badge.svg)](https://github.com/UnnamedNetwork/UNWDS/actions) [![download](https://img.shields.io/github/v/release/unnamednetwork/unwds)](https://github.com/UnnamedNetwork/UNWDS/releases)

A open-source server software for Minecraft: Bedrock Edition. UNWDS is a fork of [PocketMine-MP](https://github.com/pmmp/PocketMine-MP).

# About UNWDS v3 based on PM4:
- Currently, I'm  so busy that can't release the next release of UNWDS based on PM4. Development branch have been created, but I'm not sure I can do it soon.

## Documentation
- [Documentation](DOCUMENT.md)
- [How to install](INSTALL.md)

## For developers
 * [Building and running from source](BUILDING.md)
 * [Developer documentation](https://devdoc.pmmp.io) - General documentation for PocketMine-MP plugin developers
 * [Latest API documentation](https://jenkins.pmmp.io/job/PocketMine-MP-doc/doxygen/) - Doxygen documentation generated from development
 * [DevTools](https://github.com/pmmp/DevTools/) - Development tools plugin for creating plugins
 * [Latest Actions server software artifacts](https://nightly.link/UnnamedNetwork/UNWDS/workflows/main/stable/UNWDS.zip) - Where you can get the 'stable' development server phar on latest commit. This is not recommended for making server software. Avoid using development build where possible.
 * [Commit build history](https://github.com/UnnamedNetwork/build-repo/tree/master/UNWDS/branch/stable) - Like artifacts link above, but also contain older builds.

## Compatibility Mode notes
+ You must agree these note before you can run UNWDS. It's also included in UNWDS setup wizard.
```

Some plugin authors does not provide support for third-party builds of PocketMine-MP (spoons), included this version of UNWDS. Some spoons detract from the overall quality of the MCPE plugin environment, which is already lacking in quality. They force plugin developers to waste time trying to support conflicting APIs.

In order to begin using this server software you must understand that you will be offered no support for plugins.

Furthermore, the GitHub issue tracker for some plugins is targeted at vanilla PocketMine only. Any bugs you create which DO NOT affect with vanilla PocketMine will be deleted.

If you have read and agree the above, then you point your finger at us for embarrassing you when you open a plugin issue on Github while running UNWDS, we will laugh at you.

```

## About source code update & bugs
+ UNWDS is a PocketMine-MP distribution which have own Compatibility Mode built into it, so when the PocketMine-MP (stable) have new source changes (except API major changes), that changes will go directly into UNWDS-alt (stable) within 24-48 hours
+ UNWDS built from the original PocketMine-MP, but that's not mean UNWDS don't have own error, bugs,... Feel free to open issues, we will investigate that to fix as soon as possible.

## About API & plugins
+ UNWDS is a modified PocketMine-MP distribution to bypass the SpoonDetector by Compatibility Mode. UNWDS is not heavily modified or touch into any PocketMine-MP API (just modified into server software's behaviour after adding some constants like the distro name or codename.)
+ This changes not affected with API or Plugins for PocketMine-MP. 
+ And of course, no plugins developer will give you support when you run on UNWDS (like any other PocketMine-MP Spoons). Developers just support when you run their plugins on the original PocketMine-MP and encountered errors.

## Contribution:
+ Feel free to open the issues, PR, etc...
+ But you MUST submit a bug with an console logs, system information, crashdumps, how to reproduce it,... 
+ This play a big role in our inprovement this project	

## Licensing information
This project is licensed under LGPL-3.0. Please see the [LICENSE](/LICENSE) file for details.
