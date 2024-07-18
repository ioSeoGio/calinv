FROM php:8.2.2-fpm

RUN apt-get update && \
     apt-get install -y \
         libzip-dev \
         && docker-php-ext-install zip

RUN apt-get update && \
    apt-get install -y \
      zlib1g-dev \
      libicu-dev g++ \
      libcurl4-openssl-dev

RUN apt-get install -y \
      pkg-config \
      libssl-dev \
&& docker-php-ext-configure intl \
&& docker-php-ext-install intl

RUN pecl install mongodb && docker-php-ext-enable mongodb

RUN cd ~
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
