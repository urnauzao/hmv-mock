ARG PHP_VERSION=8.0.17-fpm
FROM php:${PHP_VERSION}

RUN apt-get update
RUN apt-get install -y --no-install-recommends apt-utils
RUN apt-get install -y libpq-dev

RUN apt-get install -y --no-install-recommends supervisor
COPY ./docker/supervisord/supervisord.conf /etc/supervisor
# uncomment to enable crontab
# COPY supervisord/conf /etc/supervisord.d/
RUN apt-get install -y libxml2-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql pgsql session xml 

# uncomment to enable redis
# RUN pecl install redis-5.3.4 \
#     && docker-php-ext-enable redis 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libpng-dev

RUN docker-php-ext-configure gd
RUN docker-php-ext-install zip iconv simplexml pcntl gd

#COPY php.ini-production /usr/local/etc/php/php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
COPY ./ /var/www/html/
WORKDIR /var/www/html/
RUN cd /var/www/html/
# RUN touch /var/www/html/.env
### RUN chown -r www-data: *
RUN chmod 755 -R storage/*
RUN chown -R www-data: storage/*
### RUN rm -rf composer.lock
### RUN rm -rf vendor
RUN composer update --no-plugins --no-scripts --no-dev
RUN composer dump-autoload

# gera a chave encriptografada do laravel
### RUN php artisan key:generate

# acesso o banco de dados e checa se existe as devidas tabelas
# RUN php artisan migrate

# limpa e carrega os caches recomendados
### RUN php artisan optimize:clear

# garante a inicialização do cron
# RUN cron

RUN apt install nginx -y

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/sites.conf /etc/nginx/sites-available/sites.conf


RUN apt-get clean

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
# EXPOSE 9000 9001
EXPOSE 80