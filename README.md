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

# 4. Instalar dependencias del backend (antes de levantar el servicio)
docker-compose run --rm backend composer install

# 5. Generar clave de aplicación
docker-compose run --rm backend php artisan key:generate

# 6. Levantar todos los servicios
docker-compose up -d

# 7. Ejecutar migraciones y seeders
docker-compose exec backend php artisan migrate:fresh --seed

# 8. Instalar dependencias del frontend
docker exec -it app_frontend npm install
```

**Acceso:**
- Frontend: http://localhost:5173
- Backend API: http://localhost:8001

---

##   Diagrama de Base de Datos

[![Diagrama de Base de Datos](./bd_image/bd_normalizada.png)](./bd_image/bd_diagram.png)

