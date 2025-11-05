#!/bin/bash

# Este script se ejecuta ANTES de que el servidor web Apache inicie.
# En este punto, el contenedor ya tiene acceso a las Variables de Entorno de Render (APP_KEY, DB_HOST).

echo "-> Ejecutando migraciones de base de datos..."
php artisan migrate --force

echo "-> Borrando caché de configuración..."
php artisan config:clear
php artisan cache:clear

# Inicia el servidor Apache en primer plano (el proceso principal del contenedor)
echo "-> Iniciando servidor web..."
exec apache2-foreground