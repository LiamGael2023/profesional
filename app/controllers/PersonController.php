<?php
class PersonController {
    private $db;
    private $personModel;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->personModel = new Person($this->db);
        $this->auth = new AuthController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Listar personas
    public function index() {
        $this->auth->requireAuth();

        $user = $this->getUserData();
        $search = $_GET['search'] ?? '';
        $filters = ['search' => $search, 'is_active' => 1];
        $personas = $this->personModel->getAll($filters);

        require_once APP_PATH . '/views/personas/index.php';
    }

    // Mostrar formulario de nueva persona
    public function create() {
        $this->auth->requireAuth();
        $user = $this->getUserData();
        require_once APP_PATH . '/views/personas/create.php';
    }

    // Guardar nueva persona
    public function store() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        $errors = $this->validatePerson($_POST);

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            $_SESSION['old'] = $_POST;
            header('Location: ' . APP_URL . '/personas/create');
            exit();
        }

        $data = $this->preparePersonData($_POST);
        $data['created_by'] = $_SESSION['user_id'];

        // Subir foto si existe
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $this->uploadFoto($_FILES['foto']);
            if ($foto['success']) {
                $data['foto'] = $foto['filename'];
            }
        }

        if ($this->personModel->create($data)) {
            $_SESSION['success'] = 'Persona registrada correctamente';
            header('Location: ' . APP_URL . '/personas');
        } else {
            $_SESSION['error'] = 'Error al registrar la persona';
            header('Location: ' . APP_URL . '/personas/create');
        }
        exit();
    }

    // Mostrar formulario de edición
    public function edit() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = 'ID no especificado';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        $persona = $this->personModel->getById($id);
        if (!$persona) {
            $_SESSION['error'] = 'Persona no encontrada';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        require_once APP_PATH . '/views/personas/edit.php';
    }

    // Actualizar persona
    public function update() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        $id = $_POST['id'] ?? null;
        $errors = $this->validatePerson($_POST, $id);

        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: ' . APP_URL . '/personas/edit?id=' . $id);
            exit();
        }

        $data = $this->preparePersonData($_POST);

        // Subir foto si existe
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $foto = $this->uploadFoto($_FILES['foto']);
            if ($foto['success']) {
                $data['foto'] = $foto['filename'];
            }
        }

        if ($this->personModel->update($id, $data)) {
            $_SESSION['success'] = 'Persona actualizada correctamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la persona';
        }

        header('Location: ' . APP_URL . '/personas');
        exit();
    }

    // Eliminar persona
    public function delete() {
        $this->auth->requireAuth();

        $id = $_POST['id'] ?? null;
        if ($id && $this->personModel->delete($id)) {
            $_SESSION['success'] = 'Persona eliminada correctamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar la persona';
        }

        header('Location: ' . APP_URL . '/personas');
        exit();
    }

    // Ver detalle de persona
    public function view() {
        $this->auth->requireAuth();
        $user = $this->getUserData();

        $id = $_GET['id'] ?? null;
        $persona = $this->personModel->getById($id);

        if (!$persona) {
            $_SESSION['error'] = 'Persona no encontrada';
            header('Location: ' . APP_URL . '/personas');
            exit();
        }

        // Verificar si es agremiado
        $agremiadoModel = new Agremiado($this->db);
        $agremiado = $agremiadoModel->getByPersonaId($id);

        require_once APP_PATH . '/views/personas/view.php';
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

    private function validatePerson($data, $excludeId = null) {
        $errors = [];

        if (empty($data['numero_documento'])) {
            $errors[] = 'El número de documento es obligatorio';
        } elseif ($this->personModel->documentExists($data['numero_documento'], $excludeId)) {
            $errors[] = 'El número de documento ya está registrado';
        }

        if (empty($data['nombres'])) {
            if ($data['tipo_documento'] === 'RUC') {
                $errors[] = 'La razón social es obligatoria';
            } else {
                $errors[] = 'Los nombres son obligatorios';
            }
        }

        // Solo validar apellido paterno para personas naturales (no RUC)
        if ($data['tipo_documento'] !== 'RUC' && empty($data['apellido_paterno'])) {
            $errors[] = 'El apellido paterno es obligatorio';
        }

        return $errors;
    }

    private function preparePersonData($post) {
        $tipoDocumento = $post['tipo_documento'] ?? 'DNI';

        // Si es RUC, usar razon_social para nombres y dejar apellidos vacíos
        if ($tipoDocumento === 'RUC') {
            $nombres = trim($post['razon_social'] ?? '');
            $apellidoPaterno = ''; // RUC no tiene apellidos
            $apellidoMaterno = '';
        } else {
            // Para DNI, CE, Pasaporte usar nombres y apellidos normales
            $nombres = trim($post['nombres'] ?? '');
            $apellidoPaterno = trim($post['apellido_paterno'] ?? '');
            $apellidoMaterno = trim($post['apellido_materno'] ?? '');
        }

        return [
            'tipo_documento' => $tipoDocumento,
            'numero_documento' => trim($post['numero_documento']),
            'nombres' => $nombres,
            'apellido_paterno' => $apellidoPaterno,
            'apellido_materno' => $apellidoMaterno,
            'fecha_nacimiento' => $post['fecha_nacimiento'] ?? null,
            'genero' => $post['genero'] ?? 'Masculino',
            'estado_civil' => $post['estado_civil'] ?? 'Soltero',
            'nacionalidad' => $post['nacionalidad'] ?? 'Peruana',
            'email' => trim($post['email'] ?? ''),
            'telefono' => trim($post['telefono'] ?? ''),
            'celular' => trim($post['celular'] ?? ''),
            'direccion' => trim($post['direccion'] ?? ''),
            'urbanizacion' => trim($post['urbanizacion'] ?? ''),
            'distrito' => trim($post['distrito'] ?? ''),
            'provincia' => trim($post['provincia'] ?? ''),
            'departamento' => trim($post['departamento'] ?? ''),
            'foto' => null
        ];
    }

    private function uploadFoto($file) {
        $uploadDir = PUBLIC_PATH . '/images/personas/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'error' => 'Tipo de archivo no permitido'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'persona_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename];
        }

        return ['success' => false, 'error' => 'Error al subir el archivo'];
    }
}
