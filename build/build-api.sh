#!/bin/bash

#prepare variable we need.
BUILD_TOKEN="$GHTOKEN"
ApiRepoUrl="https://github.com/UnnamedNetwork/unnamednetwork.github.io"
ApiRepo="unnamednetwork.github.io"
Org="UnnamedNetwork"
Name="UNWDS"
PHPVersion=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
DistroVersion=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\VersionInfo::DISTRO_VERSION;')
BuildNumber=${steps.version_info.outputs.BUILD_NUMBER}
IsDev=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\VersionInfo::IS_DEVELOPMENT_BUILD;')
Branch="${GITHUB_REF##*/}"
GitCommit="${GITHUB_SHA}"
TargetVersion=$(php -r 'require "vendor/autoload.php"; echo \pocketmine\network\mcpe\protocol\ProtocolInfo::MINECRAFT_VERSION_NETWORK;')
Date=$(date +"%s")
if [ "$IsDev" = "0" ]; then
    IsDev="false"
else
    IsDev="true"
fi


function Push {
    sed -i "s/public const BUILD_CHANNEL = \"\"/public const BUILD_CHANNEL = \"${Branch}\"/g" src/VersionInfo.php
    git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
    git config --global user.name "github-actions[bot]"
    git clone $ApiRepoUrl
    cd $ApiRepo
    git checkout main
    [ ! -d "$Name/update" ] && mkdir $Name/update
    cd $Name/update/
    rm -rf $APIFile

    # the work will here.
    jo -p php_version="${PHPVersion}" base_version=$DistroVersion build=$BuildNumber is_dev=$IsDev channel=$Branch git_commit=$GitCommit mcpe_version=$TargetVersion date=$Date details_url=$DetailsURL download_url=$DownloadURL source_url=$SourceURL >> $APIFile
    cat $APIFile

    git add $APIFile
    git commit -m "$APIFile: bumped to version $DistroVersion, build $BuildNumber"
    git remote rm origin
    # Add new "origin" with access token in the git URL for authentication
    git remote add origin https://dtcgalt:$BUILD_TOKEN@github.com/$Org/$ApiRepo > /dev/null 2>&1
    git pull origin main --rebase
    git push origin main --quiet
}

# Checking if this workflows run on allowed branch
function Main {
    APIFile="api_$Branch.json"
    SourceURL="https://github.com/$Org/$Name/tree/$GitCommit"
    DetailsURL="https://github.com/$Org/$Name/commit/$GitCommit"
    DownloadURL="https://github.com/$Org/build-repo/raw/master/$Name/branch/$Branch/$BuildNumber/UNWDS.phar"
	if [ "$Branch" = "master" ]; then
    	echo Branch detected: "$Branch" 
		echo OK.
		Push
	else
		if [ "$Branch" = "3.0-pm-4" ]; then
    		echo Branch detected: "$Branch" 
			echo OK.
			Push
		else
            if [ "$Branch" = "v$DistroVersion" ]; then
                APIFile="api.json"
                SourceURL="https://github.com/$Org/$Name/tree/v$DistroVersion"
                DetailsURL="https://github.com/$Org/$Name/releases/v$DistroVersion"
                DownloadURL="https://github.com/$Org/$Name/releases/download/v$DistroVersion/$Name.phar"
    		    echo Branch detected: "$Branch" 
			    echo OK.
                export Branch="stable"
			    Push
            else
			    echo Found unsupported branch: "$Branch"
			    echo Found CI ran on unsupported branch. Stopping... # this prevent push on unexpected branch, like Dependabot builds, but still build and upload server software to artifact
			    exit 0
            fi
		fi
	fi
}

Main
