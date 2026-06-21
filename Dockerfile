# ── Stage 1 : build assets Vite ──────────────────────────────────────────────
FROM node:20-alpine AS node-build

WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build


# ── Stage 2 : PHP-FPM production ─────────────────────────────────────────────
FROM php:8.2-fpm-alpine AS app

RUN apk add --no-cache \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        zip \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd opcache

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php.ini     /usr/local/etc/php/conf.d/app.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

COPY . .
COPY --from=node-build /app/public/build ./public/build

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN php artisan package:discover --ansi \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]


# ── Stage 3 : Nginx (copie public/ depuis le stage app) ──────────────────────
FROM nginx:1.27-alpine AS nginx

# Fichiers publics compilés (index.php, build/, favicon…)
COPY --from=app /var/www/html/public /var/www/html/public
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
