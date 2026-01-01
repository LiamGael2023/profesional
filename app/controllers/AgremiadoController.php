<?php
class AgremiadoController {
    private $db;
    private $agremiadoModel;
    private $personModel;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->agremiadoModel = new Agremiado($this->db);
        $this->personModel = new Person($this->db);
        $this->auth = new AuthController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Listar agremiados
    public function index() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $search = $_GET['search'] ?? '';
        $estado = $_GET['estado'] ?? '';
        $filters = ['search' => $search, 'estado' => $estado];

        $agremiados = $this->agremiadoModel->getAll($filters);
        $estados = $this->agremiadoModel->countByStatus();

        require_once APP_PATH . '/views/agremiados/index.php';
    }

    // Formulario para crear agremiado desde persona
    public function create() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $persona_id = $_GET['persona_id'] ?? null;
        if (!$persona_id) {
            $_SESSION['error'] = 'Debe seleccionar una persona';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        $persona = $this->personModel->getById($persona_id);
        if (!$persona) {
            $_SESSION['error'] = 'Persona no encontrada';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        // Verificar si ya es agremiado
        if ($this->agremiadoModel->getByPersonaId($persona_id)) {
            $_SESSION['error'] = 'Esta persona ya es agremiado';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        $siguiente_numero = $this->agremiadoModel->getNextNumeroColegiatura();

        require_once APP_PATH . '/views/agremiados/create.php';
    }

    // Guardar agremiado
    public function store() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/agremiados');
            exit();
        }

        $data = [
            'persona_id' => $_POST['persona_id'],
            'numero_colegiatura' => trim($_POST['numero_colegiatura']),
            'fecha_colegiatura' => $_POST['fecha_colegiatura'],
            'universidad' => trim($_POST['universidad'] ?? ''),
            'especialidad' => trim($_POST['especialidad'] ?? ''),
            'anio_graduacion' => $_POST['anio_graduacion'] ?? null,
            'estado' => $_POST['estado'] ?? 'Activo',
            'fecha_habilitacion' => $_POST['fecha_habilitacion'] ?? date('Y-m-d'),
            'observaciones' => trim($_POST['observaciones'] ?? ''),
            'created_by' => $_SESSION['user_id']
        ];

        if ($this->agremiadoModel->create($data)) {
            $_SESSION['success'] = 'Agremiado registrado correctamente';
            header('Location: ' . APP_URL . '/agremiados');
        } else {
            $_SESSION['error'] = 'Error al registrar el agremiado';
            header('Location: ' . APP_URL . '/agremiados/create?persona_id=' . $data['persona_id']);
        }
        exit();
    }

    // Ver detalle de agremiado
    public function view() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $id = $_GET['id'] ?? null;
        $agremiado = $this->agremiadoModel->getById($id);

        if (!$agremiado) {
            $_SESSION['error'] = 'Agremiado no encontrado';
            header('Location: ' . APP_URL . '/agremiados');
            exit();
        }

        require_once APP_PATH . '/views/agremiados/view.php';
    }

    // Cambiar estado
    public function changeStatus() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/agremiados');
            exit();
        }

        $id = $_POST['id'];
        $accion = $_POST['accion'];

        if ($accion === 'habilitar') {
            $this->agremiadoModel->habilitar($id);
            $_SESSION['success'] = 'Agremiado habilitado correctamente';
        } elseif ($accion === 'inhabilitar') {
            $motivo = $_POST['motivo'] ?? 'Sin motivo especificado';
            $this->agremiadoModel->inhabilitar($id, $motivo);
            $_SESSION['success'] = 'Agremiado inhabilitado correctamente';
        }

        header('Location: ' . APP_URL . '/agremiados/view?id=' . $id);
        exit();
    }

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
