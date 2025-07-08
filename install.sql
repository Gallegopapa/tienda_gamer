-- Script de instalación para la tienda gamer
-- Ejecutar este script para configurar la base de datos

-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS tienda_gamer;
USE tienda_gamer;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'usuario') DEFAULT 'usuario',
    direccion TEXT,
    pais VARCHAR(100),
    ciudad VARCHAR(100),
    codigo_postal VARCHAR(20),
    contacto VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de categorías
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de productos
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    category_id INT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_nombre (nombre),
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de pedidos
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    estado ENUM('pendiente', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    direccion TEXT NOT NULL,
    pais VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    codigo_postal VARCHAR(20) NOT NULL,
    contacto VARCHAR(50) NOT NULL,
    metodo_pago VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de items de pedido
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id),
    INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (hasheada con password_hash)
INSERT INTO users (nombre, email, password, rol) 
VALUES ('Administrador', 'admin@tiendagamer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE id=id;

-- Crear algunas categorías de ejemplo
INSERT INTO categories (nombre, descripcion) VALUES 
('Consolas', 'Todo tipo de consolas de videojuegos'),
('Videojuegos', 'Juegos para diferentes plataformas'),
('Accesorios', 'Accesorios para gaming')
ON DUPLICATE KEY UPDATE id=id;

-- Triggers para mantener la integridad referencial

-- Trigger para verificar stock antes de insertar un item de pedido
DELIMITER //
CREATE TRIGGER IF NOT EXISTS check_stock_before_insert 
BEFORE INSERT ON order_items
FOR EACH ROW
BEGIN
    DECLARE available_stock INT;
    SELECT stock INTO available_stock FROM products WHERE id = NEW.product_id;
    IF available_stock < NEW.cantidad THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No hay suficiente stock disponible';
    END IF;
END//
DELIMITER ;

-- Trigger para actualizar stock después de insertar un item de pedido
DELIMITER //
CREATE TRIGGER IF NOT EXISTS update_stock_after_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock - NEW.cantidad 
    WHERE id = NEW.product_id;
END//
DELIMITER ;

-- Trigger para actualizar stock al cancelar un pedido
DELIMITER //
CREATE TRIGGER IF NOT EXISTS restore_stock_on_delete
BEFORE DELETE ON order_items
FOR EACH ROW
BEGIN
    UPDATE products 
    SET stock = stock + OLD.cantidad 
    WHERE id = OLD.product_id;
END//
DELIMITER ;

-- Procedimientos almacenados útiles

-- Procedimiento para obtener el total de ventas por período
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS get_sales_by_period(
    IN start_date DATE,
    IN end_date DATE
)
BEGIN
    SELECT 
        DATE(o.fecha) as fecha,
        COUNT(DISTINCT o.id) as total_pedidos,
        SUM(oi.cantidad * oi.precio) as total_ventas
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE DATE(o.fecha) BETWEEN start_date AND end_date
    AND o.estado != 'cancelado'
    GROUP BY DATE(o.fecha)
    ORDER BY fecha;
END//
DELIMITER ;

-- Procedimiento para obtener productos con bajo stock
DELIMITER //
CREATE PROCEDURE IF NOT EXISTS get_low_stock_products(
    IN stock_limit INT
)
BEGIN
    SELECT 
        p.id,
        p.nombre,
        p.stock,
        c.nombre as categoria
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.stock <= stock_limit
    ORDER BY p.stock ASC;
END//
DELIMITER ; 