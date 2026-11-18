$ErrorActionPreference = "Stop"
Set-Location (Join-Path $PSScriptRoot "..")

php composer.phar install --no-interaction
npm run build
php artisan migrate --force
php artisan db:seed --class=Database\Seeders\BlockLSeeder --force
Write-Host ""
Write-Host "Starting at http://127.0.0.1:8000 — admin: admin@blockl.local / BlockLAdmin2026!"
php artisan serve --host=127.0.0.1 --port=8000