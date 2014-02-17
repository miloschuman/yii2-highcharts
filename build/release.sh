#!/bin/bash
set -e

echo -n "Enter the version for this release: "

read ver

if [ ! $ver ]; then
	echo "Invalid version."
	exit
fi

name="select2"
js="$name.js"
mini="$name.min.js"
css="$name.css"
release="$name-$ver"
tag="$ver"
branch="build-$ver"
#curbranch=`git branch | grep "*" | sed "s/* //"`
timestamp=$(date)
tokens="s/@@ver@@/$ver/g;s/\@@timestamp@@/$timestamp/g"
remote="https://github.com/highslide-software/highcharts.com.git"

# Text color variables
txtund=$(tput sgr 0 1)          # Underline
txtbld=$(tput bold)             # Bold
bldred=${txtbld}$(tput setaf 1) #  red
bldblu=${txtbld}$(tput setaf 4) #  blue
bldwht=${txtbld}$(tput setaf 7) #  white
txtrst=$(tput sgr0)             # Reset
info=${bldwht}*${txtrst}        # Feedback
pass=${bldblu}*${txtrst}
warn=${bldred}*${txtrst}
ques=${bldblu}?${txtrst}

#shopt -s extglob  #Enables extglob

echo $bldblu"Fetching remote"$txtrst
cd ../src/assets/
rm -rf *

#svn export --force https://github.com/highslide-software/highcharts.com/tags/v"$ver"/js/ ./
wget http://code.highcharts.com/zips/Highcharts-${ver}.zip
unzip Highcharts-${ver}.zip js/*


echo $bldblu"Purging extraneous assets..."$txtrst
rm -rfv parts* *debug* .htaccess


echo $bldblu"Creating missing src files"$txtrst
for file in $(find . -name '*.js' ! -name '*.src.js'); do 
     cp -nv "$file" ${file%.js}.src.js
done


echo $bldblu"Updating Version Identifiers"$txtrst
cd ..
sed -i "s/@version ([0-9\.]+)/@version $ver/g" *.php


#echo "Pulling from origin"
#
#git pull
#
#echo "Updating Version Identifiers"
#
#sed -E -e "s/\"version\": \"([0-9\.]+)\",/\"version\": \"$ver\",/g" -i "" bower.json select2.jquery.json component.json composer.json package.json
#git add bower.json
#git add select2.jquery.json
#git add component.json
#git add composer.json
#git add package.json
#git commit -m "modified version identifiers in descriptors for release $ver"
#git push
#
#git branch "$branch"
#git checkout "$branch"
#
#echo "Tokenizing..."
#
#find . -name "$js" | xargs -I{} sed -e "$tokens" -i "" {}
#find . -name "$css" | xargs -I{} sed -e "$tokens" -i "" {}
#sed -e "s/latest/$ver/g" -i "" bower.json
#
#git add "$js"
#git add "$css"
#
#echo "Minifying..."
#
#echo "/*" > "$mini"
#cat LICENSE | sed "$tokens" >> "$mini"
#echo "*/" >> "$mini"
#
#curl -s \
#	--data-urlencode "js_code@$js" \
#	http://marijnhaverbeke.nl/uglifyjs \
#	>> "$mini"
#
#git add "$mini"
#
#git commit -m "release $ver"
#
#echo "Tagging..."
#git tag -a "$tag" -m "tagged version $ver"
#git push "$remote" --tags
#
#echo "Cleaning Up..."
#
#git checkout "$curbranch"
#git branch -D "$branch"
#
#echo "Done"
