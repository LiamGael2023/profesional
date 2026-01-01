<?php
/**
 * Funciones Helper Globales
 */

// Cargar configuraciones del sistema
function loadSettings() {
    static $settings = null;

    if ($settings === null) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $settingModel = new Setting($conn);
            $settings = $settingModel->getAll();
        } catch (Exception $e) {
            // Si hay error, usar valores por defecto
            $settings = [
                'app_name' => 'MVC Login System',
                'logo_desktop' => '',
                'logo_mobile' => '',
                'header_bg_color' => '#206bc4',
                'header_text_color' => 'auto'
            ];
        }
    }

    return $settings;
}

// Obtener una configuración específica
function getSetting($key, $default = null) {
    $settings = loadSettings();
    return $settings[$key] ?? $default;
}

// Calcular si un color es oscuro
function isColorDark($hexColor) {
    $hex = str_replace('#', '', $hexColor);
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
    return $luminance < 0.5;
}

// Obtener color de texto según el fondo
function getTextColor($bgColor) {
    return isColorDark($bgColor) ? '#ffffff' : '#1f2937';
}

// Obtener URL del logo
function getLogoUrl($type = 'desktop') {
    $logo = getSetting('logo_' . $type);
    if (!empty($logo)) {
        return APP_URL . '/public/images/' . $logo;
    }
    return null;
}

// Obtener nombre de la aplicación
function getAppName() {
    return getSetting('app_name', 'MVC Login System');
}

// Obtener color del header
function getHeaderBgColor() {
    return getSetting('header_bg_color', '#206bc4');
}

// Obtener color del texto del header
function getHeaderTextColor() {
    $bgColor = getHeaderBgColor();
    return getTextColor($bgColor);
}
