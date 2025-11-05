# Usa una imagen base de PHP oficial con Apache
FROM php:8.1-apache

# Instala las dependencias necesarias de PHP para Laravel (ej: pdo, zip, gd)
RUN docker-php-ext-install pdo_mysql zip

# Instala Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia los archivos del proyecto a la carpeta de Apache
COPY . /var/www/html/

# Configura las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Establece los permisos correctos para las carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cambia el directorio de trabajo
WORKDIR /var/www/html

# Ajusta la configuración del servidor web (Apache) para que apunte a la carpeta /public de Laravel
RUN a2enmod rewrite
COPY .docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# El comando por defecto (Apache ya está configurado para servir)
CMD ["apache2-foreground"]