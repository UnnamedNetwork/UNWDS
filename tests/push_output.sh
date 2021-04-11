#!/bin/bash
CURRENT_BRANCH="${GITHUB_REF##*/}"
# Checking if this workflows run on allowed branch

if [ "$CURRENT_BRANCH" = "stable" ]; then
    echo Branch detected: "$CURRENT_BRANCH". Build & push now...
else
	if ["$CURRENT_BRANCH" = "master"]; then
		echo Branch detected: "$CURRENT_BRANCH". Build & push now...
	else
		echo Found unsupported branch: "$CURRENT_BRANCH". Exitting
		exit 0
	fi
fi

PM_WORKERS="auto"

while getopts "t:" OPTION 2> /dev/null; do
	case ${OPTION} in
		t)
			PM_WORKERS="$OPTARG"
			;;
	esac
done

#Run-the-server tests
DATA_DIR="$(pwd)/test_data"
PLUGINS_DIR="$DATA_DIR/plugins"

dateAndMonth=`date`
BUILDPHPV=$(php -r 'echo PHP_VERSION;')
OLDBLD=$(expr $GITHUB_RUN_NUMBER - 1)
OUTPUT_REPO="UNWDS_Output/branch"
CURRENT_BRANCH="${GITHUB_REF##*/}"

rm -rf "$DATA_DIR"
rm UNWDS.phar 2> /dev/null
mkdir "$DATA_DIR"
mkdir "$PLUGINS_DIR"

cd tests/plugins/DevTools
php -dphar.readonly=0 ./src/DevTools/ConsoleScript.php --make ./ --relative ./ --out "$PLUGINS_DIR/DevTools.phar"
cd ../../..
composer make-server

if [ -f UNWDS.phar ]; then
	echo Server phar created successfully.
else
	echo Server phar was not created!
	exit 1
fi

git config --global user.email "41898282+github-actions[bot]@users.noreply.github.com"
git config --global user.name "github-actions[bot]"
chmod 777 UNWDS.phar
git clone https://github.com/dtcu0ng/UNWDS_Output.git
cd UNWDS_Output
git checkout master
cd branch
# Checking if output branch folder exist.
[ ! -d "$CURRENT_BRANCH" ] && mkdir $CURRENT_BRANCH
[ ! -d "$CURRENT_BRANCH/latest" ] && mkdir $CURRENT_BRANCH/latest
[ ! -d "$CURRENT_BRANCH/old" ] && mkdir $CURRENT_BRANCH/old
mkdir $CURRENT_BRANCH/old/$OLDBLD
cp $CURRENT_BRANCH/latest/UNWDS.phar $CURRENT_BRANCH/old/$OLDBLD
cd ../../
cp UNWDS.phar $OUTPUT_REPO/$CURRENT_BRANCH/latest
cd UNWDS_Output
git add -A
git commit -m "Build from $CURRENT_BRANCH: $dateAndMonth (CI #$GITHUB_RUN_NUMBER)"
git remote rm origin
# Add new "origin" with access token in the git URL for authentication
git remote add origin https://dtcu0ng:$GHTOKEN@github.com/dtcu0ng/UNWDS_Output.git > /dev/null 2>&1
git pull origin master --rebase
git push origin master --quiet
echo Pushed on: "$OUTPUT_REPO"/"$CURRENT_BRANCH"
