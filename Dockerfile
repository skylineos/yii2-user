FROM php:8.0.6-fpm
RUN pecl install xdebug && docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable xdebug
WORKDIR /app
RUN mkdir -p runtime/assets && chmod -R 777 runtime