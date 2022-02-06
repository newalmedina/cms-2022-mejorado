#!/bin/bash


echo "Instalando....."

composer config repositories.locations '{"type": "path", "url": "./packages/clavel/locations/"}'

composer require clavel/locations:@dev --no-scripts --no-update

echo "Composer ...."
COMPOSER_MEMORY_LIMIT=-1 composer update

echo "Instalando ficheros"
php artisan vendor:publish --provider="Clavel\Locations\LocationsServiceProvider" --force

echo "Composer ...."
composer dumpauto

echo "Base de datos ...."
php artisan migrate

echo "Instalando seeds ..."
php artisan db:seed --class=CountriesPermissionSeeder
php artisan db:seed --class=CcaasPermissionSeeder
php artisan db:seed --class=ProvincesPermissionSeeder
php artisan db:seed --class=CitiesPermissionSeeder
php artisan db:seed --class=LocationsSeeder
