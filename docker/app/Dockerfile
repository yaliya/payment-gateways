# Vendor Build Stage
FROM composer:latest as vendor
COPY composer.lock composer.json /var/www/

WORKDIR /var/www

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist

# App Build Image
FROM php:8.2-fpm-alpine3.18

WORKDIR /var/www/html

# Install system dependencies
RUN apk update && \
    apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    libzip-dev \
    libtool \
    libxml2-dev \
    libmcrypt-dev \
    zlib-dev \
    icu-dev \
    && apk add --no-cache \
    libzip \
    bash \
    curl \
    libcurl \
    libintl \
    icu \
    perl \
    libcrypto3 \
    libssl3 \
    openssl \
    binutils \
    gnu-libiconv \
    && docker-php-ext-configure exif \
    && docker-php-ext-install \
    bcmath \
    exif \
    intl \
    pcntl \
    zip \
    && apk del -f .build-deps && \
    apk upgrade

COPY . /var/www/html

COPY --from=vendor /var/www/vendor/ /var/www/html/vendor/
COPY --from=vendor /usr/bin/composer /usr/bin/composer

# Add a non-root user to prevent files being created with root permissions on host machine.
ENV USER=user
ENV UID 1000
ENV GID 1000

RUN addgroup --gid "$GID" "$USER" \
    && adduser \
    --disabled-password \
    --gecos "" \
    --home "$(pwd)" \
    --ingroup "$USER" \
    --no-create-home \
    --uid "$UID" \
    "$USER" && \
    mkdir -p /home/$USER/.composer && \
    chown -R $USER:$USER /home/$USER && \
    chown -R $USER:$GID /var/www && \
    chmod -R 0755 /var/www/html/var/cache /var/www/html/var/log

# Set working directory
WORKDIR /var/www/html

USER $USER
