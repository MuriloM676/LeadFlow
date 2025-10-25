# PHP-FPM for Laravel
FROM php:8.4-fpm

# Install system deps and PHP extensions
RUN set -eux \
    && apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Recommended PHP settings for production-like env
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini

WORKDIR /var/www/html

# Note: Code is bind-mounted by docker-compose for local use
# Ensure www-data owns storage and cache at runtime via entrypoint or manual chown if needed