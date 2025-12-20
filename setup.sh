#!/bin/bash

set -e  # Detener si hay errores

echo "Iniciando instalación de Nativo Hospital Challenge..."

# 1. Copiar archivo de entorno
echo "Copiando archivo de entorno..."
cp backend/.env.example backend/.env

# 2. Construir imágenes
echo "Construyendo imágenes Docker..."
docker-compose build

# 3. Instalar dependencias del backend
echo "Instalando dependencias del backend..."
docker-compose run --rm backend composer install

# 4. Generar clave de aplicación
echo "Generando clave de aplicación..."
docker-compose run --rm backend php artisan key:generate

# 5. Levantar servicios
echo "Levantando servicios..."
docker-compose up -d

# 6. Esperar a que MySQL esté listo
echo "Esperando a que MySQL esté listo..."
sleep 10

# 7. Ejecutar migraciones y seeders
echo "Ejecutando migraciones y seeders..."
docker-compose exec backend php artisan migrate:fresh --seed

# 8. Instalar dependencias del frontend
echo "Instalando dependencias del frontend..."
docker-compose exec frontend npm install

echo "¡Instalación completada!"
echo ""
echo "Accesos:"
echo "   Frontend: http://localhost:5173"
echo "   Backend:  http://localhost:8001"
echo "   MySQL:    localhost:3307"