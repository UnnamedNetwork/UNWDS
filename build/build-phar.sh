#!/bin/bash

CURRENT_BRANCH="${GITHUB_REF##*/}"
BUILD_TOKEN="$GHTOKEN"
CURR_BUILD_NUMBER=$(expr $GITHUB_RUN_NUMBER + 1000)
OLDBLD=$(expr $CURR_BUILD_NUMBER - 1)
OUTPUT_REPO="build-repo/UNWDS/branch"
CURRENT_BRANCH="${GITHUB_REF##*/}"

function Build {
if [ -f UNWDS.phar ]; then
	echo Server phar created successfully. Contiuning...
else
	echo Server phar was not created!
	exit 1
fi
}

function Push {
git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
git config --global user.name "github-actions[bot]"
git clone https://github.com/UnnamedNetwork/build-repo.git
cd build-repo
git checkout master
cd UNWDS/branch
# Checking if output branch folder exist.
[ ! -d "$CURRENT_BRANCH" ] && mkdir $CURRENT_BRANCH
[ ! -d "$CURRENT_BRANCH/$CURR_BUILD_NUMBER" ] && mkdir $CURRENT_BRANCH/$CURR_BUILD_NUMBER
cd ../../../
cp UNWDS.phar $OUTPUT_REPO/$CURRENT_BRANCH/$CURR_BUILD_NUMBER
cd build-repo
git add -A
git commit -m "Build from UNWDS ($CURRENT_BRANCH): $dateAndMonth (CI #$GITHUB_RUN_NUMBER)"
git remote rm origin
# Add new "origin" with access token in the git URL for authentication
git remote add origin https://dtcgalt:$BUILD_TOKEN@github.com/UnnamedNetwork/build-repo.git > /dev/null 2>&1
git pull origin master --rebase
git push origin master --quiet
echo Pushed on: "$OUTPUT_REPO"/"$CURRENT_BRANCH"
}

# Checking if this workflows run on allowed branch

function Main {
	Build
	if [ "$CURRENT_BRANCH" = "master" ]; then
    	echo Branch detected: "$CURRENT_BRANCH" 
		echo OK.
		Push
	else
		if [ "$CURRENT_BRANCH" = "stable" ]; then
    		echo Branch detected: "$CURRENT_BRANCH" 
			echo OK.
			Push
		else
			echo Found unsupported branch: "$CURRENT_BRANCH"
			echo Found CI ran on unsupported branch. Stopping... # this prevent push on unexpected branch, like Dependabot builds, but still build and upload server software to artifact
		fi
	fi
}

Main
