#!/usr/bin/env bash

composer install

php bin/console doctrine:database:create
php bin/console doctrine:schema:create

php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test