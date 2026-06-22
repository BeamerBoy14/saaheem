#!/bin/sh
set -e

mkdir -p storage/framework/views \
         storage/framework/cache \
         storage/framework/sessions \
         storage/logs

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

exec "$@"
