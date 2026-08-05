#!/bin/sh
set -e

cd /var/www/html

# Run migrations automatically on container startup.
php artisan migrate --force

# Create storage symlink for public assets.
php artisan storage:link

if [ "$QUEUE_WORKER" = "true" ]; then
  php artisan queue:work database --sleep=3 --tries=3 --timeout=90
else
  php artisan serve --host=0.0.0.0 --port=10000
fi
