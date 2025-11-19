#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

php artisan config:cache
php artisan route:cache
php artisan view:cache

npm ci
npm run build

# Ensure storage and cache are writable and linked
chmod -R 0777 storage bootstrap/cache || true
php artisan storage:link || true
