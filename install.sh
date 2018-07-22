#!/bin/bash

path=/var/www/st

# Install npm and gulp
cd "$path/_gulp"
npm install
npm install -g gulp

# Install phpunit
cd "$path/_test/phpunit"
wget -O phpunit https://phar.phpunit.de/phpunit-7.phar
chmod +x phpunit

# check phpunit
./phpunit --version
