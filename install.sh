#!/bin/bash

YELLOW='\033[0;32m'
NOCOLOR='\033[0m'

path=/var/www/st

printf "\n\n${YELLOW}Installing NPM and GULP${NOCOLOR}\n\n"
sleep 1

# Install npm and gulp
#cd "$path/_gulp"
#npm install
#npm install -g gulp

printf "\n\n${YELLOW}Installing PHPUNIT${NOCOLOR}\n\n"
sleep 1

# Install phpunit
cd "$path/_test/phpunit"
wget -O phpunit https://phar.phpunit.de/phpunit-7.phar
chmod +x phpunit

# check phpunit version
./phpunit --version

printf "\n\n${YELLOW}Installing CODECEPTION${NOCOLOR}\n\n"
sleep 1

# Install codeception
cd "$path/_test/codeception"
wget -O codecept https://codeception.com/codecept.phar
chmod +x codecept

# check version of codeception
./codecept --version

# Install PHP code sniffer
cd "$path/_test/phpcs"
wget -O phpcs https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
chmod +x phpcs.phar

# check version of phpcs
./phpcs --version