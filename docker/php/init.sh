#!/bin/sh
set -eu

mkdir -p storage/app/private storage/app/public \
    storage/framework/cache/data storage/framework/sessions \
    storage/framework/views storage/logs bootstrap/cache

# The mounted Docker environment is separate from the native WSL .env.
export COMPOSER_ALLOW_SUPERUSER=1
composer install --no-interaction --prefer-dist --no-progress
php artisan config:clear --no-interaction
if ! grep -Eq '^APP_KEY=.+$' .env; then
    php artisan key:generate --no-interaction
fi
chown -R www-data:www-data vendor storage bootstrap/cache
