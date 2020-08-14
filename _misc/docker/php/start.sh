#!/usr/bin/env bash

composer install

wait-for-it database:3306 -t 600

cp .env.example .env
php artisan key:generate migrate
chmod 777 -R storage/
php-fpm
