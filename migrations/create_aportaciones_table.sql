-- Crear tabla de aportaciones mensuales
-- Ejecutar este script si la tabla aportaciones no existe

CREATE TABLE IF NOT EXISTS aportaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agremiado_id INT NOT NULL,
    periodo VARCHAR(7) NOT NULL, -- Formato: YYYY-MM
    anio INT NOT NULL,
    mes INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('Pendiente', 'Pagado', 'Vencido', 'Exonerado') DEFAULT 'Pendiente',
    fecha_vencimiento DATE,
    fecha_pago DATE,
    metodo_pago VARCHAR(50),
    numero_operacion VARCHAR(100),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (agremiado_id) REFERENCES agremiados(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_periodo (agremiado_id, periodo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Crear índices
CREATE INDEX idx_aportaciones_periodo ON aportaciones(periodo);
CREATE INDEX idx_aportaciones_estado ON aportaciones(estado);
CREATE INDEX idx_aportaciones_agremiado ON aportaciones(agremiado_id);
