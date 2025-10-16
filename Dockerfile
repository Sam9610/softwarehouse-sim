FROM php:8.3-fpm

# Dipendenze
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev

# Estensioni PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Installa Xdebug
# RUN pecl install xdebug
# RUN docker-php-ext-enable xdebug

# Copia la configurazione personalizzata di Xdebug
# COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www