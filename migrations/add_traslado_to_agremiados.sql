-- Agregar campos de traslado/incorporación a la tabla agremiados
-- Ejecutar este script si la tabla agremiados ya existe

ALTER TABLE agremiados
ADD COLUMN tipo_incorporacion ENUM('Normal', 'Traslado', 'Incorporación') DEFAULT 'Normal' AFTER fecha_colegiatura,
ADD COLUMN fecha_traslado DATE AFTER tipo_incorporacion;
