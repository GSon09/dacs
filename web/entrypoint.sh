#!/usr/bin/env bash
set -o errexit

# Move to project dir
cd "$(dirname "$0")"

# Ensure permissions
chmod -R 0777 storage bootstrap/cache || true

# Create storage symlink if missing
php artisan storage:link || true

# Optionally run migrations when RUN_MIGRATIONS env var is true
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "RUN_MIGRATIONS=true -> running migrations"
  php artisan migrate --force || true
fi

# Cache config/routes/views if requested
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start the server
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
