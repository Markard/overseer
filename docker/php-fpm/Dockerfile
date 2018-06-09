FROM php:7.2-fpm-alpine

ARG DOCKER_HOST_UID
RUN set -xe && if [ -z $DOCKER_HOST_UID ]; then echo 'DOCKER_HOST_UID is not specified.'; exit 1; fi

ARG MEMCACHED_VERSION
RUN set -xe && if [ -z $MEMCACHED_VERSION ]; then echo 'MEMCACHED_VERSION is not specified.'; exit 1; fi

ARG COMPOSER_VERSION
RUN set -xe && if [ -z $COMPOSER_VERSION ]; then echo 'COMPOSER_VERSION is not specified.'; exit 1; fi

ENV BUILD_DEPS_PACKAGE .build-deps

RUN set -xe \
    \
    # installing build essentials
    && apk add --no-cache --virtual $BUILD_DEPS_PACKAGE build-base autoconf \
    \
    # installing memcached extension
    # 1. install its dependencies
    && apk add --no-cache --virtual $BUILD_DEPS_PACKAGE zlib-dev \
    && apk add --no-cache libmemcached-dev \
    && apk add --no-cache cyrus-sasl-dev \
    # 2. the extension itself doesn't come out of the box, so first download it
    # git is one of the dependencies of composer and thus should not be removed after building
    && apk add --no-cache git \
    && git clone --branch v$MEMCACHED_VERSION https://github.com/php-memcached-dev/php-memcached /usr/src/php/ext/memcached/ \
    # 3. configure it properly - the libmemcached doesn't come with sasl support
    && docker-php-ext-configure memcached --disable-memcached-sasl \
    && docker-php-ext-install memcached \
    \
    # installing essential extensions for symfony
    && apk add --no-cache icu-dev \
    && docker-php-ext-install bcmath intl pdo \
    \
    # installing postgresql pdo extension
    && apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/lib \
    && docker-php-ext-install pdo_pgsql pgsql \
    \
    # installing composer
    # 1. download it
    && apk add --no-cache --virtual $BUILD_DEPS_PACKAGE openssl \
    && wget https://getcomposer.org/download/$COMPOSER_VERSION/composer.phar \
    # 2. install it and its dependencies
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && apk add --no-cache git \
    \
    # installing xdebug
    && pecl install xdebug \
    # the extension itself is turned on by custom ini file (see xdebug.ini)
    \
    # mapping www-data uid to the host uid
    && echo http://mirror.yandex.ru/mirrors/alpine/edge/community/ >> /etc/apk/repositories \
    && apk add --no-cache --virtual $BUILD_DEPS_PACKAGE shadow \
    && usermod -u $DOCKER_HOST_UID www-data \
    # prepare shared folder for composer
    && mkdir /home/www-data/.composer \
    && chown -R www-data:www-data /home/www-data/.composer \
    \
    # some dependencies of codeception require zip extension to be installed
    && apk add --no-cache --virtual $BUILD_DEPS_PACKAGE zlib-dev \
    && docker-php-ext-install zip \
    \
    # cleaning up
    && pecl clear-cache \
    && apk del $BUILD_DEPS_PACKAGE \
    && docker-php-source delete

COPY php.ini /usr/local/etc/php/php.ini
COPY php-fpm.conf /usr/local/etc/php-fpm.conf
COPY pool.d/default.conf /usr/local/etc/pool.d/default.conf

WORKDIR /var/www/project
