#!bin/sh

cd /treten

php artisan migrate

cd /

exec php-fpm
