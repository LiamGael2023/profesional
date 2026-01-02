-- Agregar campo urbanizacion a la tabla personas
-- Ejecutar este script si la tabla personas ya existe

ALTER TABLE personas
ADD COLUMN urbanizacion VARCHAR(100) AFTER direccion;
