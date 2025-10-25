# PHP-FPM for Laravel
FROM php:8.4-fpm

# Install system deps (if needed) and PHP extensions
RUN set -eux \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install opcache

# Recommended PHP settings for production-like env
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

# Note: Code is bind-mounted by docker-compose for local use
# Ensure www-data owns storage and cache at runtime via entrypoint or manual chown if needed