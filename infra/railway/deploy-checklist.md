# Railway Deploy Checklist

## 1) Create Services

Create 3 Railway services from the same source (`temp-laravel`):

- `web` service
- `worker` service
- `scheduler` service

## 2) Set Environment Variables

Set these variables for all services:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://<your-railway-domain>`
- `FILESYSTEM_DISK=public`
- `APP_PUBLIC_STORAGE_URL=/storage`
- DB variables (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)

Set role per service:

- `web`: `APP_ROLE=web`
- `worker`: `APP_ROLE=worker`
- `scheduler`: `APP_ROLE=scheduler`

## 3) Verify Runtime

After deploy:

- `web` should serve app on HTTPS.
- `worker` should run `queue:work` continuously.
- `scheduler` should execute `schedule:run` every minute.

## 4) Storage Validation

Run on the web service shell:

```sh
php artisan storage:link --force
php artisan media:audit-missing
```

If `media:audit-missing` reports missing files:

- Re-upload files in admin; or
- Attach persistent volume / switch to object storage (S3/R2) for media.

## 5) SSL

Railway provides HTTPS by default. Confirm all image URLs are served via `https://...`.

## 6) Backup

- Enable automated backups on your database provider.
- Store `.env` values in Railway variables and keep an external secret backup.
