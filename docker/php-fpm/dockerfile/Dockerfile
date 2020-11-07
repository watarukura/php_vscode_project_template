FROM php:7.4-fpm-alpine3.12

ENV STAGE=dev
ENV PATH=$PATH:vendor/bin
ENV COMPOSER_HOME=/tmp/.composer

RUN apk add --no-cache \
        tzdata \
        gcc \
        g++ \
        autoconf \
        make \
    && cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN apk add --no-cache \
        libxml2-dev \
        libmcrypt \
        libmcrypt-dev \
        php7-dev \
        php7-mysqlnd \
        oniguruma-dev

RUN docker-php-ext-install \
        pdo \
        pdo_mysql \
        opcache \
        mbstring

RUN pecl install \
        xdebug \
        apcu \
        mcrypt \
    && docker-php-ext-enable \
        xdebug \
        apcu \
        mcrypt \
    && pecl clear-cache

ENV PHP_FPM_CONF_DIR=/usr/local/etc
COPY docker/php-fpm/config/php-fpm.conf ${PHP_FPM_CONF_DIR}/php-fpm.conf
COPY docker/php-fpm/config/www.conf ${PHP_FPM_CONF_DIR}/php-fpm.d/www.conf

USER www-data