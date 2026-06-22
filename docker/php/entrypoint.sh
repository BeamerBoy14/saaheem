#!/bin/sh
set -e

mkdir -p storage/framework/views \
         storage/framework/cache \
         storage/framework/sessions \
         storage/logs

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

touch /var/www/html/.env

echo "[entrypoint] APP_KEY set: $([ -n \"$APP_KEY\" ] && echo YES || echo NO)"
php artisan config:cache

exec "$@"
