FROM php:8.1-apache

RUN a2enmod headers
RUN a2enmod rewrite
RUN a2enmod expires
RUN apt-get update && apt-get install -y \
        libxml2-dev wget libzip-dev libssl-dev acl \
    && docker-php-ext-install -j$(nproc) soap zip pdo pdo_mysql

RUN wget --no-check-certificate https://phar.phpunit.de/phpunit-9.5.25.phar && \
    mv phpunit*.phar phpunit.phar && \
    chmod +x phpunit.phar && \
    mv phpunit.phar /usr/local/bin/phpunit

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/html
COPY composer.json ./
RUN composer install --prefer-dist --no-scripts --no-progress --no-suggest

COPY build/php/apache2.conf /etc/apache2/apache2.conf

