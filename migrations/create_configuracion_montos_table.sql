-- Tabla para configurar montos de aportaciones por rangos de fechas
-- Permite definir diferentes montos según el período
-- Ejemplo: De enero 2025 a noviembre 2025 = S/ 28.00
--          De diciembre 2025 en adelante = S/ 30.00

CREATE TABLE IF NOT EXISTS configuracion_montos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10,2) NOT NULL,
    periodo_inicio VARCHAR(7) NOT NULL COMMENT 'Formato YYYY-MM',
    periodo_fin VARCHAR(7) DEFAULT NULL COMMENT 'Formato YYYY-MM, NULL = vigente hasta nueva configuración',
    descripcion VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_periodo (periodo_inicio, periodo_fin),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar configuración inicial de ejemplo
-- Monto de S/ 0.00 desde enero 2024 (sin fin definido)
INSERT INTO configuracion_montos (monto, periodo_inicio, periodo_fin, descripcion, created_by)
VALUES (0.00, '2024-01', NULL, 'Monto inicial por defecto', 1);
