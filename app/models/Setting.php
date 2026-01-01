<?php
class Setting {
    private $conn;
    private $table = 'settings';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener un valor de configuración por clave
    public function get($key, $default = null) {
        $query = "SELECT setting_value FROM " . $this->table . "
                  WHERE setting_key = :key LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['setting_value'] !== null) {
            return $result['setting_value'];
        }

        return $default;
    }

    // Establecer un valor de configuración
    public function set($key, $value) {
        $query = "INSERT INTO " . $this->table . "
                  (setting_key, setting_value)
                  VALUES (:key, :value)
                  ON DUPLICATE KEY UPDATE
                  setting_value = :value2";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':value2', $value);

        return $stmt->execute();
    }

    // Obtener todas las configuraciones
    public function getAll() {
        $query = "SELECT setting_key, setting_value FROM " . $this->table;
        $stmt = $this->conn->query($query);

        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    // Obtener múltiples configuraciones
    public function getMultiple($keys) {
        $placeholders = str_repeat('?,', count($keys) - 1) . '?';
        $query = "SELECT setting_key, setting_value FROM " . $this->table . "
                  WHERE setting_key IN ($placeholders)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($keys);

        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    // Actualizar múltiples configuraciones
    public function setMultiple($settings) {
        $this->conn->beginTransaction();

        try {
            foreach ($settings as $key => $value) {
                $this->set($key, $value);
            }
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Eliminar una configuración
    public function delete($key) {
        $query = "DELETE FROM " . $this->table . " WHERE setting_key = :key";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':key', $key);
        return $stmt->execute();
    }
}
