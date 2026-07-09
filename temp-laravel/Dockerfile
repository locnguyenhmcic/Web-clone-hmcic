# syntax=docker/dockerfile:1

FROM node:22-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY tsconfig.json ./

RUN npm run build

FROM php:8.3-cli-alpine AS app
WORKDIR /app

RUN apk add --no-cache git unzip icu-libs libzip libpq \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev postgresql-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql intl zip \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

COPY --from=frontend /app/public/build ./public/build

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 8080

CMD ["sh", "-c", "php artisan migrate --force && (php artisan storage:link || true) && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
