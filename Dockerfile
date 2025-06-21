FROM php:8.4-fpm-alpine

# Argumentos de build
ARG user=www
ARG uid=1000

# Instalar dependências do sistema Alpine
RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite \
    sqlite-dev \
    libzip-dev \
    supervisor \
    dcron \
    shadow \
    autoconf \
    gcc \
    g++ \
    make \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd zip

# Instalar Redis extension para PHP (conecta ao container Redis)
RUN pecl install redis-5.3.7 \
    && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário do sistema para executar comandos do Composer e Artisan
# Alpine: criar grupo e usuário de forma robusta
RUN addgroup -g 82 -S www-data 2>/dev/null || true
RUN addgroup -g $uid -S $user 2>/dev/null || true
RUN adduser -D -s /bin/sh -u $uid -G $user $user 2>/dev/null || true
RUN adduser $user www-data 2>/dev/null || true
RUN mkdir -p /home/$user/.composer
RUN chown -R $user:$user /home/$user

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos de configuração customizados do PHP
COPY ./docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Copiar arquivos do projeto
COPY . /var/www

# Instalar dependências do Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Dar permissões apropriadas
RUN chown -R $user:$user /var/www
RUN chmod -R 775 /var/www/storage
RUN chmod -R 775 /var/www/bootstrap/cache
RUN chmod 664 /var/www/database/database.sqlite || touch /var/www/database/database.sqlite && chmod 664 /var/www/database/database.sqlite

# Mudar para o usuário criado
USER $user

# Expor porta 9000 e iniciar php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
