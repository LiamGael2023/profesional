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

-- Tabla de personas
CREATE TABLE IF NOT EXISTS personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_documento ENUM('DNI', 'CE', 'Pasaporte', 'RUC') DEFAULT 'DNI',
    numero_documento VARCHAR(20) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100),
    fecha_nacimiento DATE,
    genero ENUM('Masculino', 'Femenino', 'Otro') DEFAULT 'Masculino',
    estado_civil ENUM('Soltero', 'Casado', 'Divorciado', 'Viudo', 'Conviviente') DEFAULT 'Soltero',
    nacionalidad VARCHAR(50) DEFAULT 'Peruana',
    email VARCHAR(100),
    telefono VARCHAR(20),
    celular VARCHAR(20),
    direccion TEXT,
    urbanizacion VARCHAR(100),
    distrito VARCHAR(100),
    provincia VARCHAR(100),
    departamento VARCHAR(100),
    foto VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de agremiados
CREATE TABLE IF NOT EXISTS agremiados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    persona_id INT NOT NULL,
    numero_colegiatura VARCHAR(20) NOT NULL UNIQUE,
    fecha_colegiatura DATE NOT NULL,
    tipo_incorporacion ENUM('Normal', 'Traslado', 'Incorporación') DEFAULT 'Normal',
    fecha_traslado DATE,
    universidad VARCHAR(200),
    especialidad VARCHAR(200),
    anio_graduacion INT,
    estado ENUM('Activo', 'Suspendido', 'Inhabilitado', 'Retirado') DEFAULT 'Activo',
    fecha_habilitacion DATE,
    fecha_inhabilitacion DATE,
    motivo_inhabilitacion TEXT,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (persona_id) REFERENCES personas(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices para mejorar rendimiento
CREATE INDEX idx_personas_documento ON personas(numero_documento);
CREATE INDEX idx_personas_nombres ON personas(nombres, apellido_paterno);
CREATE INDEX idx_agremiados_colegiatura ON agremiados(numero_colegiatura);
CREATE INDEX idx_agremiados_estado ON agremiados(estado);
CREATE INDEX idx_agremiados_persona ON agremiados(persona_id);

