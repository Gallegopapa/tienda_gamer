# Tienda Gamer - Instrucciones de Instalación

## Requisitos Previos

- XAMPP (versión 7.4 o superior)
- PHP 7.4 o superior
- MySQL 5.7 o superior
- Navegador web moderno (Chrome, Firefox, Edge, etc.)

## Pasos de Instalación

### 1. Configuración de XAMPP

1. Descarga e instala XAMPP desde [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Inicia los servicios de Apache y MySQL desde el panel de control de XAMPP

### 2. Configuración del Proyecto

1. Clona o descarga este repositorio en la carpeta `htdocs` de XAMPP:
   ```bash
   cd C:\xampp\htdocs
   git clone [URL_DEL_REPOSITORIO] tienda_gamer
   ```

2. Asegúrate de que la estructura de carpetas sea la siguiente:
   ```
   C:\xampp\htdocs\tienda_gamer\
   ├── assets/
   ├── controllers/
   ├── models/
   ├── views/
   ├── config.php
   ├── index.php
   ├── install.sql
   └── README.md
   ```

### 3. Configuración de la Base de Datos

1. Abre el navegador y accede a phpMyAdmin:
   ```
   http://localhost/phpmyadmin
   ```

2. Crea una nueva base de datos llamada `tienda_gamer`:
   - Haz clic en "Nueva"
   - Nombre de la base de datos: `tienda_gamer`
   - Codificación: `utf8mb4_unicode_ci`
   - Haz clic en "Crear"

3. Importa el archivo de instalación:
   - Selecciona la base de datos `tienda_gamer`
   - Haz clic en "Importar"
   - Selecciona el archivo `install.sql`
   - Haz clic en "Continuar"

### 4. Configuración del Archivo config.php

1. Copia el archivo `config.example.php` a `config.php`:
   ```bash
   cp config.example.php config.php
   ```

2. Edita `config.php` y configura los siguientes valores:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'tienda_gamer');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('BASE_URL', 'http://localhost/tienda_gamer');
   ```

### 5. Permisos de Carpetas

Asegúrate de que la carpeta `assets/images` tenga permisos de escritura:
```bash
chmod 755 assets/images
```

### 6. Acceso al Sistema

1. Accede a la tienda:
   ```
   http://localhost/tienda_gamer
   ```

2. Credenciales del administrador por defecto:
   - Email: `admin@tiendagamer.com`
   - Contraseña: `admin123`

## Características Implementadas

- Gestión de usuarios (registro, login, perfil)
- Gestión de productos y categorías
- Carrito de compras
- Proceso de checkout
- Panel de administración
- Gestión de pedidos
- Eliminación de cuentas de usuario
- Gestión de stock automática

## Estructura de la Base de Datos

El script `install.sql` incluye:

- Tablas principales (users, products, categories, orders, order_items)
- Índices optimizados
- Triggers para gestión de stock
- Procedimientos almacenados para reportes
- Usuario administrador por defecto
- Categorías de ejemplo

## Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté corriendo
- Comprueba las credenciales en `config.php`
- Asegúrate de que la base de datos existe

### Error de permisos
- Verifica que la carpeta `assets/images` tenga permisos de escritura
- Comprueba que el usuario de MySQL tenga permisos suficientes

### Error 404
- Verifica que el módulo rewrite de Apache esté habilitado
- Comprueba que la configuración de BASE_URL sea correcta

## Soporte

Para reportar problemas o solicitar ayuda:
1. Revisa la documentación
2. Verifica los logs de error de PHP y MySQL
3. Contacta al equipo de soporte 