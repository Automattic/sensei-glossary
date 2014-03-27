#!/bin/sh

printf "Extension name: "
read NAME

printf "Destination folder: "
read FOLDER

DEFAULT_NAME="Sensei Extension Template"
DEFAULT_TOKEN="sensei-extension-template"
DEFAULT_CLASS="Sensei_Extension_Template"
DEFAULT_GLOBAL="sensei_extension_template"

CLASS=${NAME// /_}
GLOBAL=$( tr '[A-Z]' '[a-z]' <<< $CLASS)
TOKEN=$( tr '[A-Z]' '[a-z]' <<< $NAME)
TOKEN=${TOKEN// /-}

git clone git@github.com:woothemes/$DEFAULT_TOKEN.git $FOLDER/$TOKEN

echo "Removing git files..."

cd $FOLDER/$TOKEN

rm -rf .git
rm README.md

echo "Updating files..."

mv $DEFAULT_TOKEN.php $TOKEN.php

cp $TOKEN.php $TOKEN.tmp
sed "s/$DEFAULT_NAME/$NAME/g" $TOKEN.tmp > $TOKEN.php
rm $TOKEN.tmp

cp $TOKEN.php $TOKEN.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" $TOKEN.tmp > $TOKEN.php
rm $TOKEN.tmp

cp $TOKEN.php $TOKEN.tmp
sed "s/$DEFAULT_GLOBAL/$GLOBAL/g" $TOKEN.tmp > $TOKEN.php
rm $TOKEN.tmp

cp $TOKEN.php $TOKEN.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" $TOKEN.tmp > $TOKEN.php
rm $TOKEN.tmp

cp changelog.txt changelog.tmp
sed "s/$DEFAULT_NAME/$NAME/g" changelog.tmp > changelog.txt
rm changelog.tmp

cd lang
rm $DEFAULT_TOKEN.pot

cd ../classes
mv class-$DEFAULT_TOKEN.php class-$TOKEN.php

cp class-$TOKEN.php class-$TOKEN.tmp
sed "s/$DEFAULT_CLASS/$CLASS/g" class-$TOKEN.tmp > class-$TOKEN.php
rm class-$TOKEN.tmp

cp class-$TOKEN.php class-$TOKEN.tmp
sed "s/$DEFAULT_TOKEN/$TOKEN/g" class-$TOKEN.tmp > class-$TOKEN.php
rm class-$TOKEN.tmp

cp class-$TOKEN.php class-$TOKEN.tmp
sed "s/$DEFAULT_GLOBAL/$GLOBAL/g" class-$TOKEN.tmp > class-$TOKEN.php
rm class-$TOKEN.tmp

echo "Creating new repo..."

cd ..
git init