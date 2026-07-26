#!/usr/bin/env bash
# exit on error
set -o errexit

echo "=== Installing Composer Dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Installing NPM & Building Frontend Assets ==="
npm ci
npm run build

echo "=== Preparing Database & Storage Directories ==="
mkdir -p database storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs
touch database/database.sqlite

echo "=== Running Database Migrations & Seeders ==="
php artisan migrate --force
php artisan db:seed --force

echo "=== Caching Configuration & Routes ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache
