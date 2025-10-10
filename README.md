# 🚀 Facbin - Sistema de Gestión Empresarial

Facbin es una aplicación web poderosa para gestión empresarial, desarrollada con **Laravel** (backend) y **Vue.js** (frontend) utilizando **Inertia.js**. Incluye módulos completos para clientes, productos, inventarios y facturación, junto con un panel de administración avanzado con **Filament**.

## ✨ Características Principales
- 👥 **Gestión de Usuarios**: Autenticación segura con Fortify, perfiles y roles personalizados.
- 🛒 **Clientes y Productos**: CRUD completo con búsquedas avanzadas y filtros inteligentes.
- 📦 **Inventarios**: Control preciso de stock, gestión de diferencias y selecciones múltiples.
- 💳 **Facturación**: Generación automática de invoices con formateo profesional.
- 🛠️ **Panel Admin**: Recursos Filament para una gestión avanzada y eficiente.
- 🎨 **Interfaz**: Componentes Vue reutilizables, diseño responsivo con Tailwind CSS.

## 🛠️ Tecnologías
- **Backend**: Laravel 11, PHP 🐘
- **Frontend**: Vue.js 3, Inertia.js ⚡
- **Admin**: Filament 📊
- **CSS**: Tailwind CSS 🎨
- **DB**: MySQL 🗄️
- **Build**: Vite ⚙️

## 📥 Instalación
1. Clona el repositorio: `git clone https://github.com/your-repo/facbin.git` 📥
2. Instala dependencias: `composer install && npm install` 📦
3. Configura `.env` y la base de datos.
4. Migra la DB: `php artisan migrate` 🗃️
5. Construye assets: `npm run build` 🔨
6. Inicia el servidor: `php artisan serve` 🚀

## 🎯 Uso
- Inicia sesión en `/login`. 🔐
- Gestiona clientes y productos en `/admin`. 👨‍💼
- Crea inventarios y facturas desde los módulos dedicados. 📄

## 📁 Estructura del Proyecto
- `app/`: Modelos, controladores y recursos Filament. 🏗️
- `js/`: Componentes Vue y páginas. ⚛️
- `routes/`: Rutas web y API. 🛤️
- `database/`: Migraciones y seeders. 💾

## 🤝 Contribución
Forkea el repo, crea una rama, haz commits, push y abre un PR. 🌟

## 📄 Licencia
MIT License. Consulta el archivo LICENSE. 📜