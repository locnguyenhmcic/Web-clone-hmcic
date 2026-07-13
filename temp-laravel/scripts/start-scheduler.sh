#!/usr/bin/env sh
set -eu

php artisan optimize:clear
php artisan storage:link --force
php artisan migrate --force

# Run scheduler every minute in a long-running process.
while true; do
  php artisan schedule:run --verbose --no-interaction || true
  sleep 60
done
