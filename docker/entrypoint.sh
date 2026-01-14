#!/usr/bin/env sh
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache database

if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
fi

rm -f bootstrap/cache/*.php

php artisan migrate --force --seed

php artisan package:discover --ansi || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec php artisan serve --host=0.0.0.0 --port=8000
