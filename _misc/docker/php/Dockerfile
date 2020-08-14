FROM php:7.4-fpm

 RUN apt-get update -y \
     && apt-get install -y zip git wait-for-it libmagickwand-dev exiftool \
     && rm -r /var/lib/apt/lists/*

 RUN pecl install imagick xdebug \
     && docker-php-ext-configure opcache \
     && docker-php-ext-install pdo_mysql opcache exif \
     && docker-php-ext-enable imagick xdebug exif opcache \
        echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;

 COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
 ENV COMPOSER_ALLOW_SUPERUSER=1

 RUN echo "[www]\naccess.log = /proc/self/fd/1" \
     > /usr/local/etc/php-fpm.d/yy-access.conf

 COPY php.ini /usr/local/etc/php/

 WORKDIR /app

 COPY start.sh /opt/docker/
 RUN chmod +x /opt/docker/start.sh
 CMD ["/opt/docker/start.sh"]
