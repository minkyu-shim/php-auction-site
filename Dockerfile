FROM php:8.2-apache

RUN docker-php-ext-install mysqli

COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY . /var/www/html

RUN a2enmod rewrite

WORKDIR /var/www/html
