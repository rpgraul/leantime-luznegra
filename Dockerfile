# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala dependências do sistema, extensões PHP (incluindo OPcache e APCu) e Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libldap2-dev \
    libssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        bcmath \
        pcntl \
        ldap \
        opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurações de performance para o OPcache e PHP
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_file_override=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Configurações gerais de otimização no php.ini
RUN { \
    echo 'memory_limit=512M'; \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'max_execution_time=120'; \
    echo 'realpath_cache_size=4096k'; \
    echo 'realpath_cache_ttl=600'; \
} > /usr/local/etc/php/conf.d/custom-performance.ini

# Instala Node.js 20 (LTS) para compilar os assets CSS/JS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita módulos essenciais do Apache para performance (rewrite, headers, deflate, expires)
RUN a2enmod rewrite headers deflate expires

# Define o diretório de trabalho
WORKDIR /var/www/html

# ── Dependências PHP (camada com melhor cache) ──────────────────────────────
COPY composer.json composer.lock* ./

# Cria a pasta Shims (necessária para o autoload do Leantime)
RUN mkdir -p app/Core/Shims

# Instala as dependências do Composer otimizadas para produção
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction

# ── Dependências Node e build dos assets ────────────────────────────────────
COPY package.json package-lock.json* ./
RUN npm ci --prefer-offline

# Copia o restante do código fonte
COPY . .

# Compila os assets CSS/JS (prod minificado)
RUN npx mix --production

# ── Permissões ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/public \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ── Apache: VirtualHost otimizado com compressão gzip e cache estático ──────
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    # Otimização de Compressão Gzip/Deflate\n\
    <IfModule mod_deflate.c>\n\
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json application/xml\n\
    </IfModule>\n\
    \n\
    # Cache HTTP para arquivos estáticos (CSS, JS, Imagens, Fontes)\n\
    <IfModule mod_expires.c>\n\
        ExpiresActive On\n\
        ExpiresByType image/jpg "access plus 1 year"\n\
        ExpiresByType image/jpeg "access plus 1 year"\n\
        ExpiresByType image/gif "access plus 1 year"\n\
        ExpiresByType image/png "access plus 1 year"\n\
        ExpiresByType image/svg+xml "access plus 1 year"\n\
        ExpiresByType text/css "access plus 1 month"\n\
        ExpiresByType application/javascript "access plus 1 month"\n\
        ExpiresByType text/javascript "access plus 1 month"\n\
        ExpiresByType font/woff2 "access plus 1 year"\n\
    </IfModule>\n\
    \n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expõe a porta 80
EXPOSE 80
