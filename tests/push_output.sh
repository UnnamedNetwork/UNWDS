#!/bin/bash
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
echo "PHP $BUILDPHPV detected. Pushing the phar into output repo..."
chmod 777 UNWDS.phar
git clone https://github.com/dtcu0ng/UNWDS_Output.git
cd UNWDS_Output
git checkout master
cd ci_build_output
# Checking if output branch folder exist.
[ ! -d "${GITHUB_REF##*/}" ] && mkdir ${GITHUB_REF##*/}
[ ! -d "${GITHUB_REF##*/}/latest" ] && mkdir ${GITHUB_REF##*/}/latest
[ ! -d "${GITHUB_REF##*/}/old" ] && mkdir ${GITHUB_REF##*/}/old
mkdir ${GITHUB_REF##*/}/old/$OLDBLD
cp ${GITHUB_REF##*/}/latest/UNWDS.phar ${GITHUB_REF##*/}/old/$OLDBLD
cd ../../
cp UNWDS.phar UNWDS_Output/ci_build_output/${GITHUB_REF##*/}/latest
cd UNWDS_Output
git add -A
git commit -m "${GITHUB_REF##*/} build update: $dateAndMonth (Build $GITHUB_RUN_NUMBER)"
git remote rm origin
# Add new "origin" with access token in the git URL for authentication
git remote add origin https://dtcu0ng:$GHTOKEN@github.com/dtcu0ng/UNWDS_Output.git > /dev/null 2>&1
git pull origin master --rebase
git push origin master --quiet
echo Push completed with 0 or more errors
echo Branch: ${GITHUB_REF##*/}
