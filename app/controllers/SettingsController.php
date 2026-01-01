<?php
class SettingsController {
    private $db;
    private $settingModel;
    private $auth;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->settingModel = new Setting($this->db);
        $this->auth = new AuthController();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Mostrar página de configuración
    public function index() {
        $this->auth->requireAuth();

        // Obtener datos del usuario
        $user = [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'full_name' => $_SESSION['full_name'],
            'email' => $_SESSION['email'],
            'avatar' => $_SESSION['avatar'] ?? 'default-avatar.png'
        ];

        // Obtener configuraciones actuales
        $settings = $this->settingModel->getAll();

        require_once APP_PATH . '/views/settings/index.php';
    }

    // Actualizar configuraciones
    public function update() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/settings');
            exit();
        }

        $errors = [];
        $success = false;

        // Procesar logo desktop
        if (isset($_FILES['logo_desktop']) && $_FILES['logo_desktop']['error'] === UPLOAD_ERR_OK) {
            $logoDesktop = $this->uploadImage($_FILES['logo_desktop'], 'logo_desktop');
            if ($logoDesktop['success']) {
                $this->settingModel->set('logo_desktop', $logoDesktop['filename']);
            } else {
                $errors[] = $logoDesktop['error'];
            }
        }

        // Procesar logo mobile
        if (isset($_FILES['logo_mobile']) && $_FILES['logo_mobile']['error'] === UPLOAD_ERR_OK) {
            $logoMobile = $this->uploadImage($_FILES['logo_mobile'], 'logo_mobile');
            if ($logoMobile['success']) {
                $this->settingModel->set('logo_mobile', $logoMobile['filename']);
            } else {
                $errors[] = $logoMobile['error'];
            }
        }

        // Actualizar nombre de la aplicación
        if (isset($_POST['app_name']) && !empty($_POST['app_name'])) {
            $this->settingModel->set('app_name', trim($_POST['app_name']));
        }

        // Actualizar color del header
        if (isset($_POST['header_bg_color']) && !empty($_POST['header_bg_color'])) {
            $headerColor = trim($_POST['header_bg_color']);
            // Validar que sea un color hexadecimal
            if (preg_match('/^#[0-9A-F]{6}$/i', $headerColor)) {
                $this->settingModel->set('header_bg_color', $headerColor);
            }
        }

        if (empty($errors)) {
            $_SESSION['success'] = 'Configuración actualizada correctamente';
        } else {
            $_SESSION['error'] = implode('<br>', $errors);
        }

        header('Location: ' . APP_URL . '/settings');
        exit();
    }

    // Subir imagen
    private function uploadImage($file, $prefix = 'logo') {
        $uploadDir = PUBLIC_PATH . '/images/';

        // Crear directorio si no existe
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Validar tipo de archivo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        $fileType = $file['type'];

        if (!in_array($fileType, $allowedTypes)) {
            return [
                'success' => false,
                'error' => 'Tipo de archivo no permitido. Solo se permiten: JPG, PNG, GIF, WEBP, SVG'
            ];
        }

        // Validar tamaño (máximo 2MB)
        $maxSize = 2 * 1024 * 1024; // 2MB en bytes
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'error' => 'El archivo es demasiado grande. Tamaño máximo: 2MB'
            ];
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Eliminar logo anterior si existe
            $oldLogo = $this->settingModel->get($prefix);
            if ($oldLogo && file_exists($uploadDir . $oldLogo)) {
                unlink($uploadDir . $oldLogo);
            }

            return [
                'success' => true,
                'filename' => $filename
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Error al subir el archivo'
            ];
        }
    }

    // Eliminar logo
    public function deleteLogo() {
        $this->auth->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . APP_URL . '/settings');
            exit();
        }

        $logoType = $_POST['logo_type'] ?? '';

        if (in_array($logoType, ['logo_desktop', 'logo_mobile'])) {
            $logoFile = $this->settingModel->get($logoType);

            if ($logoFile) {
                $uploadDir = PUBLIC_PATH . '/images/';
                $filePath = $uploadDir . $logoFile;

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->settingModel->set($logoType, '');
                $_SESSION['success'] = 'Logo eliminado correctamente';
            }
        }

        header('Location: ' . APP_URL . '/settings');
        exit();
    }

    // Calcular si un color es oscuro o claro
    public static function isColorDark($hexColor) {
        // Remover el # si existe
        $hex = str_replace('#', '', $hexColor);

        // Convertir a RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        // Calcular luminosidad usando la fórmula de luminancia relativa
        // https://www.w3.org/TR/WCAG20/#relativeluminancedef
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

        // Si la luminancia es menor a 0.5, el color es oscuro
        return $luminance < 0.5;
    }

    // Obtener color de texto basado en el color de fondo
    public static function getTextColor($bgColor) {
        return self::isColorDark($bgColor) ? '#ffffff' : '#000000';
    }
}
