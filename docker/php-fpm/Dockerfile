FROM php:8.0-fpm

ARG USER_ID=1000
ARG GROUP_ID=1000

RUN addgroup --gid ${GROUP_ID} php
RUN useradd -u ${USER_ID} --gid ${GROUP_ID} php 

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip
        
RUN docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
USER php
CMD composer install ; php-fpm 
EXPOSE 9000
