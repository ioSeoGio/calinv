FROM php:8.4-rc-fpm

RUN apt-get update && \
	apt-get install -y \
		libzip-dev zlib1g-dev libicu-dev g++ unzip libpng-dev libpq-dev git

RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo_pgsql pgsql

RUN cd ~
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
