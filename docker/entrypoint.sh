#!/bin/sh
cp -a /var/www/public/. /shared_public/
chown -R www-data:www-data /shared_public/
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache
exec php-fpm