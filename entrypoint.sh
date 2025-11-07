#!/bin/bash

# Asegura que estamos en el directorio raíz del proyecto
cd /var/www/html

# =========================================================
# NOTA: Se asume que el frontend (YARN/BUILD) y las migraciones
# (php artisan migrate) se manejan manualmente o en otro servicio.
# =========================================================

echo "-> Borrando caché de configuración y vistas de Laravel..."
# Borra las cachés de Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 🚫 SE HA ELIMINADO EL COMANDO 'php artisan migrate --force'

# Inicia el servidor Apache en primer plano (el proceso principal del contenedor)
echo "-> Iniciando servidor web..."
exec apache2-foreground
