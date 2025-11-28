# Dockerfile - PHP 8.3 FPM untuk Laravel
FROM php:8.3-fpm

# system deps
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    curl \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm /tmp/composer-setup.php

WORKDIR /var/www/html

RUN useradd -G www-data,root -u 1000 -m laraveluser || true

RUN mkdir -p /var/log/php && ln -sf /dev/stderr /var/log/php/php-fpm.log

EXPOSE 9000

CMD ["php-fpm"]
