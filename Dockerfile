FROM php:8.2-apache

# pdo dependancies for db connection
RUN docker-php-ext-install pdo pdo_mysql && docker-php-ext-enable pdo_mysql

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/