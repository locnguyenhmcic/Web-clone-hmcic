#!/usr/bin/env sh
set -eu

php artisan optimize:clear
php artisan storage:link --force
php artisan migrate --force

exec php artisan queue:work --sleep=3 --tries=3 --max-time=3600 --verbose
