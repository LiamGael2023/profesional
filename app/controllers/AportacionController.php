<?php
class AportacionController {
    private $db;
    private $aportacionModel;
    private $agremiadoModel;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->aportacionModel = new Aportacion($this->db);
        $this->agremiadoModel = new Agremiado($this->db);
        $this->auth = new AuthController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Generar aportaciones para un agremiado
    public function generar() {
        $this->auth->requireAuth();

        $agremiado_id = $_GET['agremiado_id'] ?? null;

        if (!$agremiado_id) {
            $_SESSION['error'] = 'Agremiado no especificado';
            header('Location: ' . APP_URL . '/agremiados');
            exit();
        }

        // Obtener datos del agremiado
        $agremiado = $this->agremiadoModel->getById($agremiado_id);

        if (!$agremiado) {
            $_SESSION['error'] = 'Agremiado no encontrado';
            header('Location: ' . APP_URL . '/agremiados');
            exit();
        }

        // Determinar la fecha de inicio según el tipo de incorporación
        if (!empty($agremiado['fecha_traslado']) && ($agremiado['tipo_incorporacion'] == 'Traslado' || $agremiado['tipo_incorporacion'] == 'Incorporación')) {
            $fecha_inicio = $agremiado['fecha_traslado'];
            $tipo_fecha = 'Traslado/Incorporación';
        } else {
            $fecha_inicio = $agremiado['fecha_colegiatura'];
            $tipo_fecha = 'Colegiatura';
        }

        // Generar aportaciones
        $resultado = $this->aportacionModel->generarAportacionesMensuales(
            $agremiado_id,
            $fecha_inicio,
            0.00, // Monto por defecto, se puede configurar después
            $_SESSION['user_id']
        );

        if ($resultado['creadas'] > 0) {
            $_SESSION['success'] = "Se generaron {$resultado['creadas']} aportaciones mensuales desde la fecha de {$tipo_fecha} ({$fecha_inicio})";
            if ($resultado['existentes'] > 0) {
                $_SESSION['success'] .= ". {$resultado['existentes']} aportaciones ya existían.";
            }
        } else {
            if ($resultado['existentes'] > 0) {
                $_SESSION['info'] = "Todas las aportaciones ya han sido generadas ({$resultado['existentes']} períodos).";
            } else {
                $_SESSION['error'] = 'No se generaron aportaciones';
            }
        }

        header('Location: ' . APP_URL . '/aportaciones?agremiado_id=' . $agremiado_id);
        exit();
    }

    // Listar aportaciones
    public function index() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $agremiado_id = $_GET['agremiado_id'] ?? null;
        $anio = $_GET['anio'] ?? date('Y');
        $estado = $_GET['estado'] ?? '';

        $filters = [];
        if ($agremiado_id) {
            $filters['agremiado_id'] = $agremiado_id;
            $agremiado = $this->agremiadoModel->getById($agremiado_id);
        } else {
            $agremiado = null;
        }

        if ($anio) $filters['anio'] = $anio;
        if ($estado) $filters['estado'] = $estado;

        $aportaciones = $this->aportacionModel->getAll($filters);

        // Marcar aportaciones vencidas
        $this->aportacionModel->marcarVencidas();

        require_once APP_PATH . '/views/aportaciones/index.php';
    }

    // Mostrar formulario de pago
    public function pagar() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $id = $_GET['id'] ?? null;
        $aportacion = $this->aportacionModel->getById($id);

        if (!$aportacion) {
            $_SESSION['error'] = 'Aportación no encontrada';
            header('Location: ' . APP_URL . '/aportaciones');
            exit();
        }

        require_once APP_PATH . '/views/aportaciones/pagar.php';
    }

    // Procesar pago
    public function procesarPago() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/aportaciones');
            exit();
        }

        $id = $_POST['id'];
        $agremiado_id = $_POST['agremiado_id'];

        $data = [
            'fecha_pago' => $_POST['fecha_pago'] ?? date('Y-m-d'),
            'metodo_pago' => trim($_POST['metodo_pago'] ?? ''),
            'numero_operacion' => trim($_POST['numero_operacion'] ?? ''),
            'observaciones' => trim($_POST['observaciones'] ?? '')
        ];

        if ($this->aportacionModel->registrarPago($id, $data)) {
            $_SESSION['success'] = 'Pago registrado correctamente';
        } else {
            $_SESSION['error'] = 'Error al registrar el pago';
        }

        header('Location: ' . APP_URL . '/aportaciones?agremiado_id=' . $agremiado_id);
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
