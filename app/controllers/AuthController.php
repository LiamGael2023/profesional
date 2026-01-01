<?php
class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);

        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Mostrar formulario de login
    public function showLogin() {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->isAuthenticated()) {
            header('Location: ' . APP_URL . '/dashboard');
            exit();
        }

        require_once APP_PATH . '/views/auth/login.php';
    }

    // Procesar login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validación
        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = 'Por favor, ingrese usuario y contraseña';
            header('Location: ' . APP_URL . '/login');
            exit();
        }

        // Intentar login
        $user = $this->userModel->login($identifier, $password);

        if ($user) {
            // Login exitoso
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['avatar'] = $user['avatar'];
            $_SESSION['last_activity'] = time();

            // Cookie para "Recordarme"
            if ($remember) {
                setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), '/'); // 30 días
            }

            $_SESSION['success'] = 'Bienvenido, ' . $user['full_name'];
            header('Location: ' . APP_URL . '/dashboard');
            exit();
        } else {
            $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            header('Location: ' . APP_URL . '/login');
            exit();
        }
    }

    // Logout
    public function logout() {
        // Eliminar todas las variables de sesión
        $_SESSION = array();

        // Eliminar cookie de sesión
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Eliminar cookie de recordar
        if (isset($_COOKIE['user_id'])) {
            setcookie('user_id', '', time() - 3600, '/');
        }

        // Destruir sesión
        session_destroy();

        header('Location: ' . APP_URL . '/login');
        exit();
    }

    // Verificar si está autenticado
    public function isAuthenticated() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            // Verificar timeout de sesión
            if (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
                $this->logout();
                return false;
            }

            // Actualizar última actividad
            $_SESSION['last_activity'] = time();
            return true;
        }

        // Verificar cookie de "Recordarme"
        if (isset($_COOKIE['user_id'])) {
            $user = $this->userModel->findById($_COOKIE['user_id']);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['avatar'] = $user['avatar'];
                $_SESSION['last_activity'] = time();
                return true;
            }
        }

        return false;
    }

    // Middleware para proteger rutas
    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: ' . APP_URL . '/login');
            exit();
        }
    }
}
