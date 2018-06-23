#!/bin/bash

echo $1;

cd "/var/www/st/_test"

if [ $1 == "fast" ] 
then
    ./phpunit --exclude-group slow
else
    ./phpunit 
fi