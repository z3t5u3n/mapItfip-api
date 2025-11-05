#!/bin/bash

# Asegura que estamos en el directorio raíz del proyecto
cd /var/www/html

echo "-> Ejecutando yarn install para dependencias de Node.js..."
# Instala las dependencias de JavaScript
yarn install

echo "-> Compilando el frontend (React/Vite) para crear la carpeta 'build'..."
# Ejecuta la compilación de producción. ESTO CREA main.js y main.css
yarn build

echo "-> Borrando caché de configuración y vistas de Laravel..."
# Borra las cachés de Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo "-> Ejecutando migraciones de base de datos..."
# Ejecuta las migraciones de la base de datos (con --force para producción)
php artisan migrate --force

# Inicia el servidor Apache en primer plano (el proceso principal del contenedor)
echo "-> Iniciando servidor web..."
exec apache2-foreground
