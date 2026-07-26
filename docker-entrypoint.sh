#!/bin/sh
set -e

# Set default PORT if not provided by environment
export PORT="${PORT:-8080}"

echo "Starting deployment setup on port ${PORT}..."

# Substitute environment variables in nginx configuration template
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

# Generate or cache Laravel configuration if APP_KEY exists
if [ -n "$APP_KEY" ]; then
    echo "Caching Laravel configuration and routes..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run database migrations if enabled
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Start PHP-FPM in background
echo "Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "Starting Nginx..."
exec nginx -g "daemon off;"
