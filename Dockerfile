FROM php:7.4.6-apache

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug-3.1.3 && docker-php-ext-enable xdebug

WORKDIR /var/www/public
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install pdo pdo_mysql
#RUN pecl install xdebug-3.1.3 && docker-php-ext-enable xdebug
RUN apt-get -y update
RUN apt-get -y install git
# Install unzip utility and libs needed by zip PHP extension
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip
RUN docker-php-ext-install zip

RUN apt-get update && \
    apt-get install -y \
    libjpeg62-turbo-dev \
    libpng-dev

RUN docker-php-ext-install gd

RUN  a2enmod rewrite
RUN  service apache2 restart
