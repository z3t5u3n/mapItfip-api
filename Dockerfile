FROM php:8.3-apache

# 1. INSTALAR DEPENDENCIAS DE LINUX (Solo para el backend PHP)
# Se mantienen las dependencias para bases de datos comunes (ej: PostgreSQL en Render).
# Si tu base de datos final es MySQL, puedes cambiar 'libpq-dev' por 'libmysqlclient-dev'.
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# 2. INSTALAR EXTENSIONES DE PHP
# Se mantiene pdo_pgsql por la nota anterior. Para MySQL, usar 'pdo_mysql'.
RUN docker-php-ext-install pdo_pgsql zip gd

# Instala Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto a la carpeta de Apache
# ¡Asegúrate de usar el archivo .dockerignore (ver abajo) para excluir archivos grandes!
COPY . /var/www/html/

# Configura las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Establece los permisos correctos (esenciales para Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cambia el directorio de trabajo
WORKDIR /var/www/html

# Ajusta la configuración del servidor web (Apache)
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copia el script de inicio (simplificado) y le da permisos de ejecución
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
