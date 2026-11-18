#!/bin/sh
set -e

php artisan storage:link --force 2>/dev/null || true
php artisan config:clear --no-interaction 2>/dev/null || true

if [ -z "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
    echo "ERROR: DATABASE_URL (or DB_URL) is not set — cannot connect to Postgres."
    exit 1
fi

# Render private hostnames (dpg-xxx-a) only resolve in the same region; use public hostname.
normalize_db_url() {
    url="$1"
    case "$url" in
        postgresql://*@dpg-*)
            if ! echo "$url" | grep -q 'postgres.render.com'; then
                url=$(echo "$url" | sed -E 's@(postgres(ql)?://[^@]+@)dpg-([a-z0-9-]+)(/.*)?$@\1dpg-\3.oregon-postgres.render.com\4@')
            fi
            ;;
        postgres://*@dpg-*)
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

php artisan migrate --force --no-interaction
php artisan db:seed --class=Database\\Seeders\\BlockLSeeder --force --no-interaction || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"