FROM ubuntu:bionic

COPY --from=composer:1.7 /usr/bin/composer /usr/bin/composer

ENV PROJECT_PATH /var/www
ENV TZ="/usr/share/zoneinfo/NZ"
ENV DEBIAN_FRONTEND="noninteractive"

RUN apt-get update \
    && apt-get install -y nginx \
    && rm -rf /etc/nginx/sites-enabled/* \
    && rm -rf /var/lib/apt/lists/*

RUN apt-get update \
    && apt-get install -y \
        wget \
        git \
        vim \
        php \
        php-apcu \
        php-curl \
        php-fpm \
        php-intl \
        php-memcached \
        php-mbstring \
        php-xml \
        php-mysql \
        php-pdo-dblib \
        php-zip \
    && rm -rf /var/lib/apt/lists/*


COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/conf.d /etc/nginx/conf.d
COPY ./nginx/sites-enabled /etc/nginx/sites-enabled


RUN rm -rf /etc/php/7.2/fpm/pool.d/*
COPY ./php/fpm/pool.d /etc/php/7.2/fpm/pool.d
COPY ./php/docker-php-local.ini /etc/php/7.2/mods-available
RUN ln -s /etc/php/7.2/mods-available/docker-php-local.ini /etc/php/7.2/cli/conf.d \
    && ln -s /etc/php/7.2/mods-available/docker-php-local.ini /etc/php/7.2/fpm/conf.d


RUN mkdir -p /var/run/php/
RUN mkdir -p ${PROJECT_PATH}
RUN chown www-data:www-data -R ${PROJECT_PATH}

WORKDIR ${PROJECT_PATH}

CMD ["sh", "-c", "/usr/sbin/php-fpm7.2 -D && nginx -g 'daemon off;'"]
