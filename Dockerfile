FROM php:7.4.26-cli

RUN apt-get update \
    && apt-get install -y libmcrypt-dev libevent-dev libssl-dev libzip-dev zip unzip \
    && docker-php-ext-install -j$(nproc) bcmath calendar dba exif \
        gettext mysqli pcntl sockets pdo_mysql shmop sysvmsg sysvsem sysvshm \
    && pecl install mcrypt-1.0.3 nsq-3.5.0 zip-1.19.0 redis-5.3.1 \
        mongodb-1.8.0 swoole-4.5.3 \
    && docker-php-ext-enable mcrypt nsq zip redis mongodb swoole
