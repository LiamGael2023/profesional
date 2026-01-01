<?php
// Iniciar sesión
session_start();

// Cargar configuración
require_once dirname(__DIR__) . '/app/config/config.php';

// Autoload de clases
spl_autoload_register(function ($class) {
    // Buscar en controllers
    $controllerFile = APP_PATH . '/controllers/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }

    // Buscar en models
    $modelFile = APP_PATH . '/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    // Buscar en config
    $configFile = APP_PATH . '/config/' . $class . '.php';
    if (file_exists($configFile)) {
        require_once $configFile;
        return;
    }
});

// Cargar funciones helper
require_once APP_PATH . '/helpers/functions.php';

// Obtener la URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'login';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Router simple
$controller = $url[0] ?? 'login';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// Rutas disponibles
switch ($controller) {
    case 'login':
        $authController = new AuthController();
        if ($method === 'index' || $method === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $authController->login();
            } else {
                $authController->showLogin();
            }
        }
        break;

    case 'logout':
        $authController = new AuthController();
        $authController->logout();
        break;

    case 'dashboard':
        $dashboardController = new DashboardController();
        if (method_exists($dashboardController, $method)) {
            call_user_func_array([$dashboardController, $method], $params);
        } else {
            $dashboardController->index();
        }
        break;

    case 'settings':
        $settingsController = new SettingsController();
        if ($method === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingsController->update();
        } elseif ($method === 'deleteLogo' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $settingsController->deleteLogo();
        } else {
            $settingsController->index();
        }
        break;

    case '':
    case 'home':
    case 'index':
        // Redirigir a login
        header('Location: ' . APP_URL . '/login');
        exit();
        break;

    default:
        // Página 404
        http_response_code(404);
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>404 - Página no encontrada</title>
            <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet"/>
        </head>
        <body class="d-flex flex-column">
            <div class="page page-center">
                <div class="container-tight py-4">
                    <div class="empty">
                        <div class="empty-header">404</div>
                        <p class="empty-title">Oops… Página no encontrada</p>
                        <p class="empty-subtitle text-muted">
                            La página que estás buscando no existe.
                        </p>
                        <div class="empty-action">
                            <a href="' . APP_URL . '/login" class="btn btn-primary">
                                Volver al inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>';
        break;
}
