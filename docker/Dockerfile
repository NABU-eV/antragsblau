FROM php:8.2-fpm-bookworm
RUN apt-get update && apt-get install -y \
		libxml2-dev \
	&& docker-php-ext-install -j$(nproc) soap \
    && docker-php-ext-install pdo_mysql

RUN pecl install xdebug

COPY ext-xdebug.ini /usr/local/etc/php/conf.d
