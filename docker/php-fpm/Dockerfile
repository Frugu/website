FROM php:7.2-fpm-alpine

RUN apk update && apk add \
    git \
    unzip \
    postgresql-dev

# Add custom php.ini
COPY php.ini /usr/local/etc/php

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/symfony