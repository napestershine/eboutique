FROM php:8.3-fpm-alpine AS base

RUN apk add --no-cache \
    icu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    curl \
    git \
    zip \
    unzip \
    bash

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    intl \
    zip \
    opcache \
    gd \
    bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

FROM base AS dev

RUN apk add --no-cache --virtual .build-deps linux-headers autoconf g++ make \
    && pecl install xdebug-3.5.1 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY docker/php/php.dev.ini /usr/local/etc/php/conf.d/php.dev.ini

COPY . .

RUN composer install --no-interaction --no-progress

EXPOSE 9003

CMD ["php-fpm"]

FROM base AS prod

COPY . .

RUN composer install --no-dev --optimize-autoloader --classmap-authoritative \
    --no-interaction --no-progress --no-scripts

RUN php bin/console cache:clear --env=prod --no-warmup \
    && php bin/console cache:warmup --env=prod \
    && php bin/console assets:install public --env=prod

RUN composer dump-env prod

COPY docker/php/php.prod.ini /usr/local/etc/php/conf.d/php.prod.ini

RUN chown -R www-data:www-data /var/www/html/var \
    && chown -R www-data:www-data /var/www/html/public

EXPOSE 9000

CMD ["php-fpm"]
