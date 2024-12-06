FROM php:8.1-fpm-buster

RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libpq-dev libldap2-dev zip git wget \
    g++ cpp sudo python \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql pcntl sockets

RUN apt-get update && apt-get install -y software-properties-common

## Install Xdebug
RUN pecl install xdebug-3.2.0 && \
    docker-php-ext-enable xdebug && \
    echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

## xdebug.client_port must to be the same in PhpStorm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/www/html/

COPY . /var/www/html/

## The USER_ID must be the same in your Linux distro
ENV USER=admin USER_ID=1000 USER_GID=1000

RUN groupadd --gid "${USER_GID}" "${USER}" && \
    useradd \
        --uid ${USER_ID} \
        --gid ${USER_GID} \
        --create-home \
        --shell /bin/bash \
    ${USER}

# Set user permissions
RUN chown -R ${USER}:${USER_GID} /var/www/html/

RUN sed -i "s/www-data/$USER/" /usr/local/etc/php-fpm.d/www.conf

USER ${USER}

EXPOSE 9000
EXPOSE 9003


