FROM php:7.4-fpm-alpine

# Установка расширений GD
RUN apk add --no-cache \
      freetype \
      libjpeg-turbo \
      libpng \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && docker-php-ext-configure gd \
      --with-freetype=/usr/include/ \
      --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && apk del --no-cache \
      freetype-dev \
      libjpeg-turbo-dev \
      libpng-dev \
    && php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && rm -rf /tmp/*

# Установка работы с MySQL
RUN apk add --no-cache \
      mysql-client \
      mysql-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && echo "extension=pdo_mysql.so" > /usr/local/etc/php/conf.d/docker-php-ext-pdo_mysql.ini

WORKDIR /var/www/html

# Копирование файлов приложения
COPY . .

# Установка дополнительных зависимостей
RUN apk add --no-cache libzip-dev \
    && docker-php-ext-install zip bcmath

EXPOSE 9000
CMD ["php-fpm"]
