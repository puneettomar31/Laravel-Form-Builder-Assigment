#!/bin/sh
set -e

cd /var/www/html

# Sync live environment variables into .env so runtime uses Render's DB settings.
for key in DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD DB_SSLMODE DATABASE_URL; do
  if [ -n "${!key}" ]; then
    grep -v "^${key}=" .env > .env.tmp
    printf '%s=%s\n' "$key" "${!key}" >> .env.tmp
    mv .env.tmp .env
  fi
 done

# Run migrations automatically on container startup.
php artisan migrate --force

# Create storage symlink for public assets.
php artisan storage:link

if [ "$QUEUE_WORKER" = "true" ]; then
  php artisan queue:work database --sleep=3 --tries=3 --timeout=90
else
  php artisan serve --host=0.0.0.0 --port=10000
fi
