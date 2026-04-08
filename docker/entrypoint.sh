#!/bin/bash
set -e

# Ensure required Laravel runtime directories exist and are writable by php-fpm.
# This matters in live-sync mode because the repo is bind-mounted from the host,
# so `bootstrap/cache` and `storage` may otherwise inherit host ownership.
mkdir -p bootstrap/cache storage/framework/views storage/framework/sessions storage/framework/cache storage/logs
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

# Remove stale bootstrap cache manifests that may reference dev-only packages
# (e.g. laravel/pail) not installed in production image.
rm -f bootstrap/cache/*.php

# In production-style mode, remove any stale Vite hot-file so Blade uses the
# compiled assets in `public/build`. In live-sync mode we keep it, because the
# frontend-dev service uses `public/hot` to enable real-time JS/CSS updates.
if [ "${APP_LIVE_SYNC:-false}" != "true" ]; then
  rm -f public/hot
fi

# Run database migrations
echo "[entrypoint] Running migrations..."
php artisan migrate --force

# Clear or warm caches depending on runtime mode
if [ "${APP_LIVE_SYNC:-false}" = "true" ]; then
  echo "[entrypoint] Live sync mode enabled; clearing Laravel caches so route/view/code edits appear immediately..."
  php artisan optimize:clear || true
else
  echo "[entrypoint] Warming caches..."
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache || echo "[entrypoint] Warning: view:cache issue, continuing..."
fi

echo "[entrypoint] Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
