# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala dependências do sistema, extensões PHP e Node.js
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
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Node.js 20 (LTS) para compilar os assets CSS/JS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

# Define o diretório de trabalho
WORKDIR /var/www/html

# ── Dependências PHP (camada com melhor cache) ──────────────────────────────
COPY composer.json composer.lock* ./

# Cria a pasta Shims (necessária para o autoload do Leantime)
RUN mkdir -p app/Core/Shims

# Instala as dependências do Composer (sem dev e com otimização)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# ── Dependências Node e build dos assets ────────────────────────────────────
COPY package.json package-lock.json* ./
RUN npm ci --prefer-offline

# Copia o restante do código fonte
COPY . .

# Compila os assets CSS/JS (Less → CSS via laravel-mix)
RUN npx mix --production

# ── Permissões ──────────────────────────────────────────────────────────────
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/public \
    && chmod -R 755 /var/www/html/bootstrap/cache

# ── Apache: VirtualHost apontando para public/ ──────────────────────────────
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# NOTA: O public/.htaccess do repositório é preservado (não sobrescrever).
# Ele já contém as regras corretas para o Laravel/Leantime com suporte a
# query strings (?v=...) nos assets estáticos.

# Expõe a porta 80
EXPOSE 80
