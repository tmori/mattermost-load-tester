#!/bin/bash

# create laravel project
export LARAVEL_VERSION="9.*"
mkdir mattermost-test
cd mattermost-test

COMPOSER_MEMORY_LIMIT=-1 composer create-project laravel/laravel=${LARAVEL_VERSION} .
composer config --no-plugins allow-plugins.kylekatarnls/update-helper true
composer require psr/simple-cache=^2.0
 
composer require gnello/laravel-mattermost-driver
php artisan vendor:publish --provider="Gnello\Mattermost\Laravel\MattermostServiceProvider"

# create command
php artisan make:command MattermostTest
cp ../apps/MattermostTest.php app/Console/Commands/
