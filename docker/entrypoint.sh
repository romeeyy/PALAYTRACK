#!/usr/bin/env sh
set -eu

cd /var/www/html

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    public/uploads/profile-pictures

chown -R www-data:www-data storage bootstrap/cache public/uploads

php artisan package:discover --ansi
php artisan config:cache
php artisan migrate --force

if [ "${RUN_INITIAL_SEEDER:-false}" = "true" ]; then
    php artisan db:seed --force
fi

php artisan view:cache

exec "$@"
