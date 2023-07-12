FROM php:7.4-fpm-bullseye

RUN apt-get update && apt-get install -y \
    curl \
    build-essential \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libxml2-dev \
    g++ \
    make \
    autoconf \
    openssl \
    git \
    bash \
    zip \
    unzip \
    libonig-dev \
    libcurl3-dev \
    imagemagick libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && docker-php-ext-enable imagick

RUN git clone -b 4.2.0 --depth 1 https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis

RUN docker-php-ext-install \
    pdo_mysql \
    opcache \
    gd \
  && docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg
RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

RUN docker-php-ext-install curl
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install gd
RUN docker-php-ext-install dom

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && chmod +x /usr/local/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -- &&\
    apt-get install -y nodejs

RUN rm -rf /var/cache/apk/*

WORKDIR /usr/share/nginx/html/awa
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
