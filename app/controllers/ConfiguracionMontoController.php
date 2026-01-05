<?php
class ConfiguracionMontoController {
    private $db;
    private $configuracionMontoModel;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->configuracionMontoModel = new ConfiguracionMonto($this->db);
        $this->auth = new AuthController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Listar configuraciones
    public function index() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $configuraciones = $this->configuracionMontoModel->getAll(['is_active' => 1]);

        require_once APP_PATH . '/views/configuracion-montos/index.php';
    }

    // Mostrar formulario de creación
    public function create() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        require_once APP_PATH . '/views/configuracion-montos/create.php';
    }

    // Guardar nueva configuración
    public function store() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/configuracion-montos');
            exit();
        }

        $data = [
            'monto' => floatval($_POST['monto']),
            'periodo_inicio' => trim($_POST['periodo_inicio']),
            'periodo_fin' => !empty($_POST['periodo_fin']) ? trim($_POST['periodo_fin']) : null,
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'created_by' => $_SESSION['user_id']
        ];

        // Validaciones
        if ($data['monto'] < 0) {
            $_SESSION['error'] = 'El monto no puede ser negativo';
            header('Location: ' . APP_URL . '/configuracion-montos/create');
            exit();
        }

        if (!preg_match('/^\d{4}-\d{2}$/', $data['periodo_inicio'])) {
            $_SESSION['error'] = 'Formato de período inicial inválido (debe ser YYYY-MM)';
            header('Location: ' . APP_URL . '/configuracion-montos/create');
            exit();
        }

        if ($data['periodo_fin'] && !preg_match('/^\d{4}-\d{2}$/', $data['periodo_fin'])) {
            $_SESSION['error'] = 'Formato de período final inválido (debe ser YYYY-MM)';
            header('Location: ' . APP_URL . '/configuracion-montos/create');
            exit();
        }

        if ($data['periodo_fin'] && $data['periodo_fin'] < $data['periodo_inicio']) {
            $_SESSION['error'] = 'El período final no puede ser anterior al período inicial';
            header('Location: ' . APP_URL . '/configuracion-montos/create');
            exit();
        }

        if ($this->configuracionMontoModel->create($data)) {
            $_SESSION['success'] = 'Configuración de monto creada exitosamente';
            header('Location: ' . APP_URL . '/configuracion-montos');
        } else {
            $_SESSION['error'] = 'Error al crear la configuración';
            header('Location: ' . APP_URL . '/configuracion-montos/create');
        }
        exit();
    }

    // Mostrar formulario de edición
    public function edit() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $id = $_GET['id'] ?? null;
        $configuracion = $this->configuracionMontoModel->getById($id);

        if (!$configuracion) {
            $_SESSION['error'] = 'Configuración no encontrada';
            header('Location: ' . APP_URL . '/configuracion-montos');
            exit();
        }

        require_once APP_PATH . '/views/configuracion-montos/edit.php';
    }

    // Actualizar configuración
    public function update() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/configuracion-montos');
            exit();
        }

        $id = $_POST['id'];

        $data = [
            'monto' => floatval($_POST['monto']),
            'periodo_inicio' => trim($_POST['periodo_inicio']),
            'periodo_fin' => !empty($_POST['periodo_fin']) ? trim($_POST['periodo_fin']) : null,
            'descripcion' => trim($_POST['descripcion'] ?? '')
        ];

        // Validaciones
        if ($data['monto'] < 0) {
            $_SESSION['error'] = 'El monto no puede ser negativo';
            header('Location: ' . APP_URL . '/configuracion-montos/edit?id=' . $id);
            exit();
        }

        if (!preg_match('/^\d{4}-\d{2}$/', $data['periodo_inicio'])) {
            $_SESSION['error'] = 'Formato de período inicial inválido (debe ser YYYY-MM)';
            header('Location: ' . APP_URL . '/configuracion-montos/edit?id=' . $id);
            exit();
        }

        if ($data['periodo_fin'] && !preg_match('/^\d{4}-\d{2}$/', $data['periodo_fin'])) {
            $_SESSION['error'] = 'Formato de período final inválido (debe ser YYYY-MM)';
            header('Location: ' . APP_URL . '/configuracion-montos/edit?id=' . $id);
            exit();
        }

        if ($data['periodo_fin'] && $data['periodo_fin'] < $data['periodo_inicio']) {
            $_SESSION['error'] = 'El período final no puede ser anterior al período inicial';
            header('Location: ' . APP_URL . '/configuracion-montos/edit?id=' . $id);
            exit();
        }

        if ($this->configuracionMontoModel->update($id, $data)) {
            $_SESSION['success'] = 'Configuración actualizada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la configuración';
        }

        header('Location: ' . APP_URL . '/configuracion-montos');
        exit();
    }

    // Eliminar configuración
    public function delete() {
        $this->auth->requireAuth();

        $id = $_GET['id'] ?? null;

        if ($this->configuracionMontoModel->delete($id)) {
            $_SESSION['success'] = 'Configuración eliminada exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la configuración';
        }

        header('Location: ' . APP_URL . '/configuracion-montos');
        exit();
    }

    // Métodos auxiliares
    private function getUserData() {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'email' => $_SESSION['email'],
            'avatar' => $_SESSION['avatar'] ?? 'default-avatar.png'
        ];
    }
}
