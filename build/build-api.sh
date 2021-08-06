#!/bin/bash

#prepare variable we need.
BUILD_TOKEN="$GHTOKEN"
ApiRepoUrl="https://github.com/UnnamedNetwork/unnamednetwork.github.io"
ApiRepo="unnamednetwork.github.io"
Org="UnnamedNetwork"
DistroName=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\DISTRO_NAME;')
PhpVersion="7.4"
DistroVersion=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\DISTRO_VERSION;')
CalcedBuildNumber=1000+$GITHUB_RUN_NUMBER
sed -i "s/const BUILD_NUMBER = 0333/const BUILD_NUMBER = ${CalcedBuildNumber}/" src/pocketmine/VersionInfo.php
BuildNumber=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\BUILD_NUMBER;')
IsDev=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\IS_DEVELOPMENT_BUILD;')
Branch="${GITHUB_REF##*/}"
GitCommit="${GITHUB_SHA}"
TargetVersion=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\network\mcpe\protocol\ProtocolInfo::MINECRAFT_VERSION_NETWORK;')
PharName="$DistroName.phar"
Dummy=""
Date=$(date +"%s")
if [ "$IsDev" = "0" ]; then
    IsDev="false"
else
    IsDev="true"
fi

function BuildJSON {
    rm $APIFile.json
    git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
    git config --global user.name "github-actions[bot]"
    git clone $ApiRepoUrl
    cd $ApiRepo
    git checkout main
    cd $DistroName/version_control/

    # the work will here.
    MakeJSON=$(jo -p job=$DistroName php_version=$PhpVersion base_version=$DistroVersion build_number=$BuildNumber is_dev=$IsDev branch=$Branch git_commit=$GitCommit mcpe_version=$TargetVersion phar_name=$PharName dummy=$Dummy build=$BuildNumber date=$Date details_url=https://github.com/$Org/$DistroName/releases/v$DistroVersion download_url=https://github.com/$Org/$DistroName/releases/downloads/v$DistroVersion/$DistroName.phar)
    echo "$MakeJSON"
    echo "$MakeJSON" >> $APIFile

    git add $APIFile
    git commit -m "$APIFile: bumped to version $DistroVersion, build $BuildNumber"
    git remote rm origin
    # Add new "origin" with access token in the git URL for authentication
    git remote add origin https://dtcgalt:$BUILD_TOKEN@github.com/$Org/$ApiRepo > /dev/null 2>&1
    git pull origin main --rebase
    git push origin main --quiet
    echo OK.
    }

function Main {
	if [ "$Branch" = "$DistroVersion" ]; then
        APIFile="api.json"
		BuildJSON
	else
        APIFile="api_$Branch.json"
        BuildJSON
	fi
}
Main