-- Crear base de datos
CREATE DATABASE IF NOT EXISTS mvc_login CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE mvc_login;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de configuración del sistema
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuarios de prueba (password: admin123)
-- Nota: Si los usuarios ya existen, se actualizarán
INSERT INTO users (username, email, password, full_name) VALUES
('admin', 'admin@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpDRTJxjgPTU0YdF3zzcqI2mFxJHXZe', 'Administrador del Sistema'),
('usuario1', 'usuario1@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFpDRTJxjgPTU0YdF3zzcqI2mFxJHXZe', 'Usuario Demo')
ON DUPLICATE KEY UPDATE
    password = VALUES(password),
    full_name = VALUES(full_name);

-- Insertar configuraciones por defecto
INSERT INTO settings (setting_key, setting_value) VALUES
('app_name', 'MVC Login System'),
('logo_desktop', ''),
('logo_mobile', ''),
('header_bg_color', '#206bc4'),
('header_text_color', 'auto')
ON DUPLICATE KEY UPDATE
    setting_value = VALUES(setting_value);
