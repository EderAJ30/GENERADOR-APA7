# 🚀 Generador APA7: Laravel + Docker

Este repositorio contiene el entorno de desarrollo contenedorizado para el proyecto **Generador-APA7**. La arquitectura se basa en microservicios aislados para garantizar la paridad entre entornos de desarrollo (Fedora/macOS).

---

## 🏗️ Stack Tecnológico

- **Engine:** PHP 8.3-FPM  
- **Servidor Web:** Nginx (Puerto host: `8080`)  
- **Base de Datos:** MySQL 8.4 (Puerto host: `3306`)  
- **Frontend:** Vite + Tailwind CSS  
- **Runtime:** Node.js (vía contenedor de App)

---

## 🛠️ Requisitos Previos

- Docker Desktop o Docker Engine  
- Docker Compose V2  

---

## 🏁 Configuración Inicial

Sigue estos pasos para levantar el entorno desde cero:

### 1. Despliegue de Contenedores

Construye las imágenes y levanta los servicios en segundo plano:

```bash
docker compose up -d --build
```

---

### 2. Inicialización de la Aplicación

```bash
# Instalación de dependencias de Composer
docker compose exec app composer install

# Configuración del entorno (.env)
docker compose exec app cp .env.example .env

# Generación de la APP_KEY
docker compose exec app php artisan key:generate
```

---

### 3. Persistencia de Datos

```bash
docker compose exec app php artisan migrate --seed
```

---

### 4. Compilación de Assets (Frontend)

Para desarrollo con Hot Module Replacement (HMR):

```bash
docker compose exec app npm install
docker compose exec app npm run dev
```

> **Nota:** La aplicación será accesible en: http://localhost:8080

---

## 💾 Gestión de Base de Datos

### Acceso Directo (CLI)

```bash
docker exec -it laravel_db mysql -u eder -p1234 referenciasico
```

---

### Comandos de Ingeniería Inversa

```bash
# Generar modelos basados en tablas existentes
docker compose exec app php artisan code:models --table=usuarios
```

---

## 🔧 Mantenimiento y Troubleshooting

### Reset del Entorno

```bash
docker compose down -v
docker compose up -d
```

---

### Depuración de Caché

```bash
docker compose exec app php artisan optimize:clear
```

---

### Error: Vite Manifest Not Found

```bash
docker compose exec app npm run build
```

---

## 📝 Notas de Ingeniería

- **Seguridad:** El puerto `3306` está expuesto para facilitar el uso de clientes GUI (como TablePlus o DBeaver). Evita exponerlo en staging/producción.  
- **Mantenibilidad:** Se recomienda usar `docker compose exec` en lugar de `docker run` para mantener consistencia dentro de la red del proyecto.  
- **Entorno:** Configurado para flujos de trabajo en **Fedora** y **macOS**.

---

## ✅ ¿Cómo aplicar este archivo?

1. Crea o sobrescribe el archivo `README.md` en la raíz del proyecto  
2. Pega este contenido  
3. Listo 🚀