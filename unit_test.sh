#!/bin/bash

YELLOW='\033[0;32m'
NC='\033[0m'

path=/var/www/st

if [ "$1" = "fast" ]; then
    printf "\n${YELLOW}Running only fast unit tests${NC}\n\n"
    cd "$path/_test/phpunit"
    ./phpunit --exclude-group slow
elif [ "$1" = "phpunit" ]; then
    printf "\n${YELLOW}Running all phpunit tests${NC}\n\n"
    cd "$path/_test/phpunit"
    ./phpunit
elif [ "$1" = "codeception" ]; then
    printf "\n${YELLOW}Running all codeception tests${NC}\n\n"
    cd "$path/_test/codeception"
    ./codecept run
elif [ "$1" = "phpcs" ]; then
    printf "\n${YELLOW}Running all PHP Code Sniffer tests${NC}\n\n"
    cd "$path/_test/phpcs"
    ./phpcs
elif [ "$1" = "db" ]; then
    printf "\n${YELLOW}Running all db tests${NC}\n\n"
    cd "$path/_test/codeception"
    ./codecept run unit
elif [ "$1" = "acceptance" ]; then
    printf "\n${YELLOW}Running all acceptance tests${NC}\n\n"
    cd "$path/_test/codeception"
    ./codecept run acceptance
else
    printf "\n${YELLOW}Running all unit tests${NC}\n\n"
    cd "$path/_test/phpcs"
    ./phpcs
    cd "$path/_test/phpunit"
    ./phpunit
    cd "$path/_test/codeception"
    ./codecept run
fi