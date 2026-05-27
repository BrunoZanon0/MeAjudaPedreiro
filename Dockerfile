FROM php:8.4-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    librabbitmq-dev \
    libssl-dev \
    pkg-config

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar extensão Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instalar extensão RabbitMQ (amqp)
RUN pecl install amqp && docker-php-ext-enable amqp

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Criar diretório tmp com permissões corretas
RUN mkdir -p /tmp && chmod 777 /tmp

# Criar diretório de logs do PHP
RUN mkdir -p /var/log/php && chmod 777 /var/log/php

# Copiar arquivos do projeto
COPY . .

# Dar permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

    RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Configurar upload_tmp_dir no PHP
RUN echo "upload_tmp_dir = /tmp" > /usr/local/etc/php/conf.d/tmp.ini \
    && echo "sys_temp_dir = /tmp" >> /usr/local/etc/php/conf.d/tmp.ini

USER www-data

EXPOSE 9000
CMD ["php-fpm"]