FROM alpine:3.13

ENV TERM=linux

ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8

#remove?
RUN apk --no-cache add tzdata && \
    cp /usr/share/zoneinfo/Etc/UTC /etc/localtime && \
    echo "UTC" | tee /etc/timezone && \
    apk del tzdata

RUN apk --no-cache add bash


#I probably dont need all these extensions

RUN apk add --no-cache --repository "http://dl-cdn.alpinelinux.org/alpine/v3.13/community" \
    icu-libs \
    ca-certificates \
    curl \
    git \
    openssh \
    yaml \
    pcre \
    libmemcached-libs \
    zlib \
    mariadb-client \
    php8 \
    php8-common \
    php8-bcmath \
    php8-bz2 \
    php8-cgi \
    php8-ctype \
    php8-curl \
    php8-dom \
    php8-exif \
    php8-fileinfo \
    php8-fpm \
    php8-gd \
    php8-iconv \
    php8-intl \
    php8-json \
    php8-openssl \
    php8-opcache \
    php8-mbstring \
    php8-mysqlnd \
    php8-mysqli \
    php8-pcntl \
    php8-pdo \
    php8-pdo_mysql \
    php8-pdo_sqlite \
    php8-phar \
    php8-phpdbg \
    php8-posix \
    php8-redis \
    php8-session \
    php8-simplexml \
    php8-soap \
    php8-sockets \
    php8-tokenizer \
    php8-xml \
    php8-xmlreader \
    php8-xmlwriter \
    php8-zip \
    php8-zlib \
    supervisor \
    vim \
    ffmpeg


#symlink php8 to php as composer e.t.c looks for just "php"
RUN ln -s /usr/bin/php8 /usr/bin/php
RUN ln -s /usr/sbin/php-fpm8 /usr/sbin/php-fpm
RUN ln -s /etc/php8 /etc/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN sed -i 's/memory_limit = 128M/memory_limit = 512M/' /etc/php/php.ini
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 2050M/' /etc/php/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 500M/' /etc/php/php.ini

