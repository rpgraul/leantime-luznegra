# Usa a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala dependências do sistema, extensões PHP e Node.js
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libldap2-dev \
    libssl-dev \
    curl \
    gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
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

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Habilita o mod_rewrite do Apache
RUN a2enmod rewrite

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia apenas os arquivos de dependência primeiro (para melhor cache)
COPY composer.json composer.lock* ./

# Cria a pasta Shims (necessária para o autoload do Leantime)
RUN mkdir -p app/Core/Shims

# Instala as dependências do Composer (sem dev e com otimização)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copia o restante do código fonte
COPY . .

# Instala as dependências do Node e compila os assets
RUN npm install \
    && npm run build \
    && rm -rf node_modules

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/public \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configura o Apache para servir a pasta public/
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

# Configura o .htaccess para permitir URLs amigáveis
RUN echo '<IfModule mod_rewrite.c>\n\
    RewriteEngine On\n\
    # Se o arquivo ou diretório existir, sirva-o diretamente (ignorando query strings)\n\
    RewriteCond %{REQUEST_FILENAME} -f [OR]\n\
    RewriteCond %{REQUEST_FILENAME} -d\n\
    RewriteRule ^ - [L]\n\
    # Redireciona tudo para o index.php\n\
    RewriteRule ^ index.php [QSA,L]\n\
</IfModule>' > /var/www/html/public/.htaccess

# Expõe a porta 80
EXPOSE 80
