#!/bin/bash

YELLOW='\033[0;32m'
NC='\033[0m'

if [ "$1" = "fast" ]; then
    cd "/var/www/st/_test/phpunit"
    printf "\n${YELLOW}Running only fast unit tests${NC}\n\n"
    ./phpunit --exclude-group slow
elif [ "$1" = "phpunit" ]; then
    cd "/var/www/st/_test/phpunit"
    printf "\n${YELLOW}Running only fast unit tests${NC}\n\n"
    ./phpunit
elif [ "$1" = "codeception" ]; then
    cd "/var/www/st/_test/codeception"
    ./codecept run
elif [ "$1" = "db" ]; then
    cd "/var/www/st/_test/codeception"
    ./codecept run unit
elif [ "$1" = "acceptance" ]; then
    cd "/var/www/st/_test/codeception"
    ./codecept run acceptance
else
    printf "\n${YELLOW}Running all unit tests${NC}\n\n"
    ./phpunit
    cd "/var/www/st/_test/codeception"
    ./codecept run
fi