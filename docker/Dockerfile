FROM php:8.0-apache

COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN apt update && apt -y install git zip
RUN pecl install opentelemetry-beta
# RUN pecl install grpc
RUN docker-php-ext-install pdo_mysql

COPY ./000-default.conf /etc/apache2/sites-available
COPY ./php.ini /usr/local/etc/php/conf.d

RUN a2enmod rewrite

WORKDIR /var/www/html