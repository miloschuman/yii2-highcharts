#!/bin/bash
set -e

# Highlights important output
vomit() {
	echo $'\n'$(tput bold)$(tput setaf 4)[ $1 ]$(tput sgr0)$'\n'
}

# Get version number of latest releases
read latestVer latestHsver latestMapsver <<< `curl --silent http://code.highcharts.com/products.js | sed -r "s/.+'([0-9\.]+)'.+'([0-9\.]+)'.+'([0-9\.]+)'.+/\1 \2 \3/"`;


# Prompt for version numbers
read -p "Enter the HIGHCHARTS version: " -i $latestVer -e ver
read -p "Enter the HIGHSTOCK  version: " -i $latestHsver -e hsver
read -p "Enter the HIGHMAPS   version: " -i $latestMapsver -e mapsver


vomit "Fetching archives"
rm -rf ../src/assets/*
cd ../src/assets/
wget http://code.highcharts.com/zips/Highcharts-${ver}.zip
wget http://code.highcharts.com/zips/Highstock-${hsver}.zip
wget http://code.highcharts.com/zips/Highmaps-${mapsver}.zip
unzip Highmaps-${mapsver}.zip js/\*
unzip Highstock-${hsver}.zip js/\*
unzip Highcharts-${ver}.zip js/\*
mv js/* .
rmdir js
rm *.zip


vomit "Purging extraneous assets..."
rm -rfv parts* *debug* .htaccess


vomit "Creating missing src files"
for file in $(find . -name '*.js' ! -name '*.src.js'); do 
     cp -nv "$file" ${file%.js}.src.js
done


vomit "Updating Version Identifiers"
cd ..
sed -i -r "s/@version [0-9\.]+/@version $ver/" *.php
cd ..
sed -i -r "s/ See the Highcharts \[changelog\]\(http:\/\/highcharts.com\/documentation\/changelog\) for more information about what's new in this version.//" README.md
now=$(date +"%F")
sed -i "/^----------$/ a\\
\\
### [v$ver](https://github.com/miloschuman/yii2-highcharts/releases/tag/v${ver}) (${now}) ###\\
* Upgraded Highcharts core library to the latest release ($ver). See the Highcharts [changelog](http://highcharts.com/documentation/changelog) for more information about what's new in this version.\
" README.md


vomit "Done, bitch!"
vomit "Don't forget to UPDATE README.md!"
