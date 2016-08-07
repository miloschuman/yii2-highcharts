#!/usr/bin/env php

<?php

/**
 * Highlights important output
 * @param string $msg
 */
function exclaim($msg)
{
    echo "\n\033[1;36m" . $msg . "\033[0m\n";
}

date_default_timezone_set('America/New_York');
$date = date('Y-m-d');


/* Get version number of latest releases */
$products = file_get_contents('http://code.highcharts.com/products.js');
preg_match_all('/\d\.\d+\.\d+/', $products, $matches);
$ver = $matches[0][0];
$stockVer = $matches[0][1];
$mapsVer = $matches[0][2];

/* Prompt for version numbers */
echo "\nEnter the HIGHCHARTS version [$ver]: ";
$ver = trim(fgets(STDIN)) ?: $ver;
echo "\nEnter the HIGHSTOCK version [$stockVer]: ";
$stockVer = trim(fgets(STDIN)) ?: $stockVer;
echo "\nEnter the HIGHMAPS version [$mapsVer]: ";
$mapsVer = trim(fgets(STDIN)) ?: $mapsVer;

exclaim("Fetching archives");
$dir = dirname(dirname(__FILE__)) . '/src/assets';
if (!is_dir($dir) || !chdir($dir)) {
    echo "Error: Directory '$dir' is inaccessible.\n";
    return false;
}

echo `
rm -rfv $dir/*
wget http://code.highcharts.com/zips/Highcharts-$ver.zip
wget http://code.highcharts.com/zips/Highstock-$stockVer.zip
wget http://code.highcharts.com/zips/Highmaps-$mapsVer.zip
unzip Highcharts-$ver.zip js/\*
unzip -n Highstock-$stockVer.zip js/\*
unzip -n Highmaps-$mapsVer.zip js/\*
mv js/* .
rmdir js
rm *.zip
`;

exclaim("Purging extraneous assets");
echo `rm -rfv parts* *debug* .htaccess`;


exclaim("Creating missing src files");
echo `
for file in $(find . -name '*.js' ! -name '*.src.js'); do 
     cp -nv "\$file" \${file%.js}.src.js
done
`;

exclaim("Updating Changelog");
$fileName = dirname(dirname(__FILE__)) . '/CHANGELOG.md';
$changelogLink = " See the Highcharts [changelog](http://highcharts.com/documentation/changelog) for more information about what's new in this version.";
$changelogEntry = "### [v$ver](https://github.com/miloschuman/yii2-highcharts/releases/tag/v$ver) ($date) ###\n"
    . "* Upgraded Highcharts JS library to the latest release ($ver).$changelogLink";
$contents = file_get_contents($fileName);
$contents = str_replace($changelogLink, '', $contents);
$contents = str_replace('=========================', "=========================\n\n$changelogEntry", $contents);
file_put_contents($fileName, $contents);


exclaim("Done, bitch!");
exclaim("Don't forget to UPDATE README.md!");