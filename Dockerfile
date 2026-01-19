FROM php:8.2-fpm

# Instalar extensión MySQLi
RUN docker-php-ext-install mysqli

# Habilitar extensión
RUN docker-php-ext-enable mysqli

WORKDIR /var/www/html
