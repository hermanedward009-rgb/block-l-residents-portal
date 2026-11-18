#!/bin/sh
set -e

php artisan storage:link --force 2>/dev/null || true
php artisan config:clear --no-interaction 2>/dev/null || true

if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/html/storage/app/block-l.sqlite}"
    export DB_DATABASE="$DB_FILE"
    mkdir -p "$(dirname "$DB_FILE")"
    touch "$DB_FILE"
elif [ -n "$DATABASE_URL" ] || [ -n "$DB_URL" ]; then
    normalize_db_url() {
        url="$1"
        case "$url" in
            postgresql://*@dpg-*|postgres://*@dpg-*)
                if ! echo "$url" | grep -q 'postgres.render.com'; then
                    url=$(echo "$url" | sed -E 's@(postgres(ql)?://[^@]+@)dpg-([a-z0-9-]+)(/.*)?$@\1dpg-\3.oregon-postgres.render.com\4@')
                fi
                ;;
        esac
        case "$url" in
            *\?*) url="${url}&sslmode=require" ;;
            *) url="${url}?sslmode=require" ;;
        esac
        echo "$url"
    }
    if [ -n "$DATABASE_URL" ]; then
        export DATABASE_URL="$(normalize_db_url "$DATABASE_URL")"
    fi
    if [ -n "$DB_URL" ]; then
        export DB_URL="$(normalize_db_url "$DB_URL")"
    fi
else
    echo "ERROR: Set DB_CONNECTION=sqlite or provide DATABASE_URL for Postgres."
    exit 1
fi

php artisan migrate --force --no-interaction
php artisan db:seed --class=Database\\Seeders\\BlockLSeeder --force --no-interaction || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"