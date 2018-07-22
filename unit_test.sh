#!/bin/bash

YELLOW='\033[0;32m'
NC='\033[0m'

cd "/var/www/st/_test/phpunit"

if [ "$1" = "fast" ]; then
    printf "\n${YELLOW}Running only fast unit tests${NC}\n\n"
    ./phpunit --exclude-group slow
else
    printf "\n${YELLOW}Running all unit tests${NC}\n\n"
    ./phpunit
    cd "/var/www/st/_test/codeception"
    ./codecept run
fi