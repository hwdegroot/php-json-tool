FROM php:8.1-fpm

MAINTAINER Rik de Groot <hwdegroot@gmail.com>

ARG DEBIAN_FRONTEND=noninteractive
ARG PORT=8000

ENV COMPOSER_HOME=/usr/share/composer
ENV PATH=/var/opt/vendor/bin:/var/opt/.bin:$PATH:$COMPOSER_HOME/vendor/bin:$COMPOSER_HOME/bin:
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update -qqy && \
    apt-get install -qqy \
      autoconf \
      gcc \
      git \
      libc-dev \
      libmcrypt-dev \
      libmemcached-dev \
      libonig-dev \
      libxml2-dev \
      make \
      nginx \
      pkg-config \
      wget \
      zlib1g-dev \
      zip

RUN docker-php-source extract && \
    pecl install \
        xdebug \
        memcached \
        mcrypt && \
    docker-php-ext-enable memcached && \
    docker-php-source delete

RUN docker-php-ext-install \
      bcmath \
      mbstring  \
      xml

RUN echo "memory_limit = -1" > /usr/local/etc/php/conf.d/zz-php-memory.ini && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/zz-xdebug.ini && \
    echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/zz-xdebug.ini && \
    echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/zz-xdebug.ini

RUN mkdir -p $COMPOSER_HOME/bin /run/php/ && \
    wget -q https://getcomposer.org/installer -O $COMPOSER_HOME/composer-setup.php && \
    php $COMPOSER_HOME/composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('$COMPOSER_HOME/composer-setup.php');" && \
    ln -s /usr/local/bin/composer /usr/local/bin/composer.phar

RUN rm -rf /var/cache/apt/* \
    $COMPOSER_HOME/composer-setup.php \
    /tmp/*

COPY .docker/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
COPY .docker/nginx-default-site /etc/nginx/sites-available/default

VOLUME /var/opt
WORKDIR /var/opt

EXPOSE $PORT

ENTRYPOINT ["entrypoint.sh"]
