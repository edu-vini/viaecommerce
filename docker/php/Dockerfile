FROM php:8.3-fpm-alpine

# Instalar dependências de sistema e extensões PHP necessárias para Laravel
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    oniguruma-dev \
    nodejs \
    npm \
    icu-dev \
    linux-headers \
    $PHPIZE_DEPS

# Configurar e instalar extensões GD, PDO MySQL, bcmath, mbstring, zip, exif, pcntl, intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Instalar Redis via PECL
RUN pecl install redis \
    && docker-php-ext-enable redis

# Instalar Composer mais recente
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Configurações de permissão
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]
