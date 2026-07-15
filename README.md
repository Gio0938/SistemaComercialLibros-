# 📚🎬 Sistema de Venta de Libros y Películas - Laravel

![Laravel](https://img.shields.io/badge/Laravel-10-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=flat-square&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=flat-square&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)
![Status](https://img.shields.io/badge/Status-Production%20Ready-success?style=flat-square)

---

## 📋 Descripción

Sistema de venta desarrollado en Laravel que permite administrar el catálogo e inventario de **libros y películas**, así como gestionar **ventas, promociones y clientes** de forma eficiente.
Incluye panel administrativo, sistema de reportes, punto de venta (POS) y una página pública para que los clientes exploren el catálogo.

---

## ✨ Características Principales

### 🛠️ Módulos del Sistema

| Módulo | Descripción |
|--------|-------------|
| 📚 Libros | CRUD con autor, editorial, género, ISBN y stock |
| 🎬 Películas | CRUD con director, género, clasificación, formato (DVD/Blu-ray/Digital) y stock |
| 🏷️ Promociones | Descuentos (porcentaje, fijo, 2x1, 3x2, envío gratis) |
| 🛒 Punto de Venta (POS) | Carrito dinámico para libros y películas, cálculo de totales |
| 📦 Inventario | Control de stock y alertas de bajo inventario |
| 📊 Dashboard | Métricas en tiempo real (ventas, títulos más vendidos, ingresos) |
| 📈 Reportes | Exportación a PDF (ventas, inventario, clientes) |
| 🌐 Página Pública | Catálogo dinámico con filtros por género, autor/director y precio |
| 👥 Usuarios | Roles: Admin y Empleado |
| 🧑‍🤝‍🧑 Clientes | Registro y historial de compras |

---

## 🎨 Interfaz de Usuario

- Diseño moderno con Bootstrap 5
- Tablas con búsqueda y paginación
- Notificaciones y modales
- Validación en tiempo real
- Carrito interactivo con distinción entre libros y películas

---

## 🛠️ Stack Tecnológico

### Backend

| Tecnología | Versión |
|------------|---------|
| Laravel | 10.x |
| PHP | 8.1+ |
| MySQL | 8.0+ |
| Eloquent ORM | - |
| Blade | - |
| Laravel DomPDF | ^2.0 |

### Frontend

| Tecnología | Versión |
|------------|---------|
| Bootstrap | 5.3 |
| FontAwesome | 6.0 |
| JavaScript | Vanilla |
| CSS3 | - |

### Herramientas

- Composer
- NPM
- Git
- PHPUnit

---

## 🚀 Instalación Local

### Requisitos

- PHP >= 8.1
- Composer >= 2.5
- MySQL >= 8.0
- Node.js >= 18
- Git

### Pasos

```bash
git clone https://github.com/Gio0938/sistema-venta-libros-peliculas.git
cd sistema-venta-libros-peliculas

composer install

npm install
npm run build

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan storage:link

php artisan serve
```

### Acceso

| URL | Descripción |
|-----|------------|
| http://localhost:8000 | Página pública (catálogo) |
| http://localhost:8000/login | Panel administrativo |

**Credenciales:**
- Email: admin@empresa.com
- Contraseña: password

---

## ⚙️ Configuración

### Variables `.env`

```env
APP_NAME="Venta de Libros y Películas"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=venta_libros_peliculas
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📚 Guía de Uso

### Panel Administrativo

| Sección | Función |
|--------|--------|
| Dashboard | Estadísticas generales |
| Libros | Gestión del catálogo de libros |
| Películas | Gestión del catálogo de películas |
| Promociones | Gestión de descuentos |
| POS | Ventas en mostrador |
| Clientes | Gestión de clientes |
| Reportes | Exportación de reportes |

---

## 📁 Estructura del Proyecto

```
app/
 ├── Http/Controllers/
 │    ├── LibroController.php
 │    ├── PeliculaController.php
 │    ├── VentaController.php
 │    └── PromocionController.php
 ├── Models/
 │    ├── Libro.php
 │    ├── Pelicula.php
 │    ├── Venta.php
 │    └── Cliente.php
database/
resources/views/
routes/web.php
storage/
public/
```

---

## 🗄️ Base de Datos

Tablas principales:

- usuarios
- clientes
- libros
- peliculas
- promociones
- ventas
- ventas_detalles
- categorias
- autores
- directores

---

## 🔌 API

### Login

```json
POST /api/login
{
  "email": "admin@empresa.com",
  "password": "password"
}
```

### Ejemplo: Listar libros

```json
GET /api/libros
```

### Ejemplo: Listar películas

```json
GET /api/peliculas
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🚀 Producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔒 Seguridad

- Hash de contraseñas (bcrypt)
- Protección CSRF
- Validación de datos
- Protección contra XSS
- Roles de usuario

---

## 🤝 Contribución

1. Fork del proyecto
2. Crear rama
3. Commit
4. Push
5. Pull Request

---

## 📄 Licencia

MIT

---

## 📞 Contacto

**Giovani Rojas**
GitHub: https://github.com/Gio0938

---

## 📊 Estado

| Módulo | Estado |
|--------|--------|
| Libros | ✅ |
| Películas | ✅ |
| Promociones | ✅ |
| POS | ✅ |
| Reportes | ✅ |

---

⭐ Si te sirve el proyecto, dale estrella en GitHub.
