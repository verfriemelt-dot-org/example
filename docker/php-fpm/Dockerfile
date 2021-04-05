FROM php:8.0-fpm

ARG USER_ID=1000

RUN cat /etc/passwd
RUN usermod --uid ${USER_ID} www-data

COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install ; php-fpm 
EXPOSE 9000
