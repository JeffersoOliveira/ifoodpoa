#!/bin/bash

./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan optimize:clear
composer dump-autoload
