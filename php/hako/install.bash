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

# create Libs
mkdir app/Libs
cp ../libs/MattermostUtils.php app/Libs
cp ../libs/composer.json .
composer dump-autoload

# create command
php artisan make:command MattermostLogin
cp ../apps/MattermostLogin.php app/Console/Commands/

php artisan make:command MattermostCreateUser
cp ../apps/MattermostCreateUser.php app/Console/Commands/

php artisan make:command MattermostCreateTeam
cp ../apps/MattermostCreateTeam.php app/Console/Commands/

php artisan make:command MattermostCreateChannel
cp ../apps/MattermostCreateChannel.php app/Console/Commands/

php artisan make:command MattermostAddUserToTeam
cp ../apps/MattermostAddUserToTeam.php app/Console/Commands/

php artisan make:command MattermostAddUserToChannel
cp ../apps/MattermostAddUserToChannel.php app/Console/Commands/

php artisan make:command MattermostCreatePost
cp ../apps/MattermostCreatePost.php app/Console/Commands/

php artisan make:command MattermostCreatePostById
cp ../apps/MattermostCreatePostById.php app/Console/Commands/
