#!/bin/sh
set -eux

cd /var/www/html

# Sync live environment variables into .env so runtime uses Render's DB settings.
for key in DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD DB_SSLMODE DATABASE_URL; do
  value=$(printenv "$key")
  if [ -n "$value" ]; then
    grep -v "^${key}=" .env > .env.tmp
    printf '%s=%s\n' "$key" "$value" >> .env.tmp
    mv .env.tmp .env
  fi
 done

# Print debug values for deploy verification.
echo "Render startup: PORT=${PORT:-<unset>}"
echo "DB_CONNECTION=${DB_CONNECTION:-<unset>}"
echo "DATABASE_URL set=${DATABASE_URL:+yes}"
echo "DB_HOST=${DB_HOST:-<unset>}"
echo "DB_DATABASE=${DB_DATABASE:-<unset>}"

env | grep -E '^(DB|DATABASE_URL|PORT|APP_)' || true

php artisan config:clear

# Run migrations automatically on container startup.
php artisan migrate --force

# Create storage symlink for public assets.
php artisan storage:link

PORT=${PORT:-10000}

if [ "$QUEUE_WORKER" = "true" ]; then
  exec php artisan queue:work database --sleep=3 --tries=3 --timeout=90
else
  echo "Starting server on port $PORT"
  exec php artisan serve --host=0.0.0.0 --port="$PORT"
fi
