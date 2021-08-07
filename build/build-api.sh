#!/bin/bash

#prepare variable we need.
BUILD_TOKEN="$GHTOKEN"
ApiRepoUrl="https://github.com/UnnamedNetwork/unnamednetwork.github.io"
ApiRepo="unnamednetwork.github.io"
Org="UnnamedNetwork"
DistroName=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\DISTRO_NAME;')
PhpVersion="7.4"
DistroVersion=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\DISTRO_VERSION;')
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
    git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
    git config --global user.name "github-actions[bot]"
    git clone $ApiRepoUrl
    cd $ApiRepo
    git checkout main
    [ ! -d "$DistroName/update" ] && mkdir $DistroName/update
    cd $DistroName/update/
    rm -rf $APIFile

    # the work will here.
    MakeJSON=$(jo -p job=$DistroName php_version=$PhpVersion base_version=$DistroVersion build_number=$BuildNumber is_dev=$IsDev branch=$Branch git_commit=$GitCommit mcpe_version=$TargetVersion phar_name=$PharName dummy=$Dummy build=$BuildNumber date=$Date details_url=$DetailsURL download_url=$DownloadURL)
    echo "$MakeJSON"
    echo "$MakeJSON" >> $APIFile

    git add $APIFile
    git commit -m "$APIFile: bumped to version $DistroVersion, build $BuildNumber"
    git remote rm origin
    # Add new "origin" with access token in the git URL for authentication
    git remote add origin https://dtcgalt:$BUILD_TOKEN@github.com/$Org/$ApiRepo > /dev/null 2>&1
    git pull origin main --rebase
    git push origin main --quiet
    }

function Main {
	if [ "$Branch" = "$DistroVersion" ]; then
        APIFile="api.json"
        DetailsURL="https://github.com/$Org/$DistroName/releases/v$DistroVersion"
        DownloadURL="https://github.com/$Org/$DistroName/releases/download/v$DistroVersion/$DistroName.phar"
	    BuildJSON
	else
        APIFile="api_$Branch.json"
        DetailsURL="https://github.com/$Org/$DistroName/commit/$GitCommit"
        DownloadURL="https://github.com/$Org/build-repo/raw/master/$DistroName/branch/$Branch/latest/$BuildNumber/UNWDS.phar"
        BuildJSON
	fi
}
Main
