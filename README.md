# Nativo Hospital Challenge

Sistema de registro de pacientes construida con Laravel + Vue.js

**Stack Tecnológico:** Laravel 11 · Vue 3 · MySQL 8.0 · Docker · Vite

---

##  Requisitos Previos

- **Docker Desktop** (versión 20.10+)
- **Git**
- **Recomendado para Windows:** WSL2 habilitado (para mejor rendimiento)

---

##   Instalación Rápida

```bash
# 1. Clonar repositorio
git clone https://github.com/UzielTzab/nativo-hospital-challenge.git
cd nativo-hospital-challenge

# 2. Copiar archivo de entorno
cp backend/.env.example backend/.env

# 3. Construir imágenes
docker-compose build

# 4. Levantar solo la base de datos
docker-compose up -d db

# 5. Instalar dependencias del backend (antes de levantar el servicio)
docker-compose run --rm backend composer install

# 6. Generar clave de aplicación
docker-compose run --rm backend php artisan key:generate

# 7. Levantar todos los servicios
docker-compose up -d

# 8. Ejecutar migraciones y seeders
docker-compose exec backend php artisan migrate:fresh --seed

# 9. Ejecutar seeders de pacientes
docker-compose exec backend php artisan db:seed --class=PatientSeeder

# 10. Instalar dependencias del frontend
docker exec -it app_frontend npm install
```

**Acceso:**
- Frontend: http://localhost:5173
- Backend API: http://localhost:8001

---

