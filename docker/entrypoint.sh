#!/bin/bash
set -e

# Ensure required storage directories exist
mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache storage/logs
chmod -R 775 storage/
chown -R www-data:www-data storage/

# Remove stale bootstrap cache manifests that may reference dev-only packages
# (e.g. laravel/pail) not installed in production image.
rm -f bootstrap/cache/*.php

# Run database migrations
echo "[entrypoint] Running migrations..."
php artisan migrate --force

# Clear and warm caches
echo "[entrypoint] Warming caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache || echo "[entrypoint] Warning: view:cache issue, continuing..."

echo "[entrypoint] Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
