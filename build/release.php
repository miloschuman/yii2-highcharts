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

exclaim("Updating Version Identifiers");
echo `
cd ..
sed -i -r "s/@version [0-9\.]+/@version $ver/" *.php
cd ..
sed -i -r "s/ See the Highcharts \[changelog\]\(http:\/\/highcharts.com\/documentation\/changelog\) for more information about what's new in this version.//" README.md
now=$(date +"%F")
sed -i "/^----------$/ a\\
\\
### [v$ver](https://github.com/miloschuman/yii2-highcharts/releases/tag/v$ver) (\${now}) ###\\
* Upgraded Highcharts core library to the latest release ($ver). See the Highcharts [changelog](http://highcharts.com/documentation/changelog) for more information about what's new in this version.\
" README.md
`;


exclaim("Done, bitch!");
exclaim("Don't forget to UPDATE README.md!");