<?php
// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mvc_login');

// Configuración de la aplicación
define('APP_NAME', 'MVC Login System');
define('APP_URL', 'http://localhost/profesional');

// Configuración de sesión
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos

// Rutas
define('ROOT_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
