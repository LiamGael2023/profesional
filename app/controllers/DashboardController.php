<?php
class DashboardController {
    private $auth;

    public function __construct() {
        $this->auth = new AuthController();

        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Mostrar dashboard
    public function index() {
        // Verificar autenticación
        $this->auth->requireAuth();

        // Obtener datos del usuario
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'email' => $_SESSION['email'],
            'avatar' => $_SESSION['avatar'] ?? 'default-avatar.png'
        ];

        require_once APP_PATH . '/views/dashboard/index.php';
    }
}
