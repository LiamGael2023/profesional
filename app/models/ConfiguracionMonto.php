<?php
class ConfiguracionMonto {
    private $conn;
    private $table = 'configuracion_montos';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las configuraciones
    public function getAll($filters = []) {
        $query = "SELECT cm.*, u.full_name as created_by_name
                  FROM " . $this->table . " cm
                  LEFT JOIN users u ON cm.created_by = u.id
                  WHERE 1=1";

        if (isset($filters['is_active'])) {
            $query .= " AND cm.is_active = :is_active";
        }

        $query .= " ORDER BY cm.periodo_inicio DESC, cm.created_at DESC";

        $stmt = $this->conn->prepare($query);

        if (isset($filters['is_active'])) {
            $stmt->bindParam(':is_active', $filters['is_active']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener configuración por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener monto para un período específico
    public function getMontoParaPeriodo($periodo) {
        $query = "SELECT monto FROM " . $this->table . "
                  WHERE is_active = 1
                  AND periodo_inicio <= :periodo1
                  AND (periodo_fin IS NULL OR periodo_fin >= :periodo2)
                  ORDER BY periodo_inicio DESC
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':periodo1', $periodo);
        $stmt->bindValue(':periodo2', $periodo);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['monto'] : 0.00;
    }

    // Crear configuración
    public function create($data) {
        // Primero, cerrar configuración anterior si existe
        if ($data['periodo_fin'] === null) {
            $this->cerrarConfiguracionesAnteriores($data['periodo_inicio']);
        }

        $query = "INSERT INTO " . $this->table . "
                  (monto, periodo_inicio, periodo_fin, descripcion, created_by)
                  VALUES
                  (:monto, :periodo_inicio, :periodo_fin, :descripcion, :created_by)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':monto', $data['monto']);
        $stmt->bindParam(':periodo_inicio', $data['periodo_inicio']);
        $stmt->bindParam(':periodo_fin', $data['periodo_fin']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':created_by', $data['created_by']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Actualizar configuración
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . "
                  SET monto = :monto,
                      periodo_inicio = :periodo_inicio,
                      periodo_fin = :periodo_fin,
                      descripcion = :descripcion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':monto', $data['monto']);
        $stmt->bindParam(':periodo_inicio', $data['periodo_inicio']);
        $stmt->bindParam(':periodo_fin', $data['periodo_fin']);
        $stmt->bindParam(':descripcion', $data['descripcion']);

        return $stmt->execute();
    }

    // Cerrar configuraciones anteriores (establecer periodo_fin)
    private function cerrarConfiguracionesAnteriores($periodo_inicio) {
        // Calcular el mes anterior al periodo_inicio
        $fecha_inicio = DateTime::createFromFormat('Y-m', $periodo_inicio);
        if (!$fecha_inicio) {
            return false;
        }

        $fecha_inicio->modify('-1 month');
        $periodo_fin = $fecha_inicio->format('Y-m');

        $query = "UPDATE " . $this->table . "
                  SET periodo_fin = :periodo_fin
                  WHERE periodo_fin IS NULL
                  AND periodo_inicio < :periodo_inicio
                  AND is_active = 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':periodo_fin', $periodo_fin);
        $stmt->bindParam(':periodo_inicio', $periodo_inicio);

        return $stmt->execute();
    }

    // Desactivar configuración
    public function deactivate($id) {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Activar configuración
    public function activate($id) {
        $query = "UPDATE " . $this->table . " SET is_active = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar configuración (soft delete mediante is_active)
    public function delete($id) {
        return $this->deactivate($id);
    }

    // Obtener todas las configuraciones vigentes ordenadas por fecha
    public function getConfiguracionesVigentes() {
        $query = "SELECT * FROM " . $this->table . "
                  WHERE is_active = 1
                  ORDER BY periodo_inicio ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
