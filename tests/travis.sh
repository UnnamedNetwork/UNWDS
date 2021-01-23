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
NBPHPV="7.3.25"
OLDBLD=$(expr $TRAVIS_BUILD_NUMBER - 1)

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

cp -r tests/plugins/TesterPlugin "$PLUGINS_DIR"
echo -e "stop\n" | php UNWDS.phar --no-wizard --disable-ansi --disable-readline --debug.level=2 --data="$DATA_DIR" --plugins="$PLUGINS_DIR" --anonymous-statistics.enabled=0 --settings.async-workers="$PM_WORKERS" --settings.enable-dev-builds=1

output=$(grep '\[TesterPlugin\]' "$DATA_DIR/server.log")
if [ "$output" == "" ]; then
	echo TesterPlugin failed to run tests, check the logs
	echo Showing data on working folder and then exit
	ls
	echo Debugging vendor InstalledVersion then exit
	cat vendor/composer/InstalledVersions.php
	exit 1
fi

result=$(echo "$output" | grep 'Finished' | grep -v 'PASS')
if [ "$result" != "" ]; then
	echo "$result"
	echo Some tests did not complete successfully, changing build status to failed
	exit 1
elif [ $(grep -c "ERROR\|CRITICAL\|EMERGENCY" "$DATA_DIR/server.log") -ne 0 ]; then
	echo Server log contains error messages, changing build status to failed
	exit 1
else
	echo All tests passed.
fi

if [ "$BUILDPHPV" = "$NBPHPV" ]; then
    echo "PHP $BUILDPHPV detected. Ignore the phar push and then exit..."
else
    echo "PHP $BUILDPHPV detected. Pushing the phar into output repo..."
	git config --global user.name "Cuong Tien Dinh"
    git config --global user.email "deptteam.cuong@gmail.com"
	chmod 777 UNWDS.phar
    git clone https://github.com/dtcu0ng/UNWDS_Output.git
	cd UNWDS_Output
	git checkout master
	cd ci_build_output
	mkdir stable/old/$OLDBLD
	cp stable/latest/UNWDS.phar stable/old/$OLDBLD
    cd ../../
	cp UNWDS.phar UNWDS_Output/ci_build_output/stable/latest
	cd UNWDS_Output
	git add -A
	git commit -m "Stable build update: $dateAndMonth (Build $TRAVIS_BUILD_NUMBER)"
	git remote rm origin
  # Add new "origin" with access token in the git URL for authentication
    git remote add origin https://dtcu0ng:$GHTOKEN@github.com/dtcu0ng/UNWDS_Output.git > /dev/null 2>&1
	git pull origin master --rebase
    git push origin master --quiet
	echo Push completed with 0 or more errors
fi
