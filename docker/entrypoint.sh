#!/bin/sh
set -e

php artisan storage:link --force 2>/dev/null || true
php artisan config:clear --no-interaction 2>/dev/null || true
php artisan migrate --force --no-interaction
php artisan db:seed --class=Database\\Seeders\\BlockLSeeder --force --no-interaction || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"