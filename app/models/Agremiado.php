<?php
class Agremiado {
    private $conn;
    private $table = 'agremiados';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los agremiados con datos de persona
    public function getAll($filters = []) {
        $query = "SELECT a.*, p.tipo_documento, p.numero_documento, p.nombres,
                         p.apellido_paterno, p.apellido_materno, p.email, p.celular, p.foto
                  FROM " . $this->table . " a
                  INNER JOIN personas p ON a.persona_id = p.id
                  WHERE 1=1";

        // Filtros opcionales
        if (!empty($filters['search'])) {
            $query .= " AND (a.numero_colegiatura LIKE :search
                        OR p.numero_documento LIKE :search
                        OR p.nombres LIKE :search
                        OR p.apellido_paterno LIKE :search
                        OR p.apellido_materno LIKE :search
                        OR a.especialidad LIKE :search)";
        }

        if (!empty($filters['estado'])) {
            $query .= " AND a.estado = :estado";
        }

        $query .= " ORDER BY a.numero_colegiatura ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $stmt->bindParam(':search', $searchTerm);
        }

        if (!empty($filters['estado'])) {
            $stmt->bindParam(':estado', $filters['estado']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener agremiado por ID
    public function getById($id) {
        $query = "SELECT a.*, p.tipo_documento, p.numero_documento, p.nombres,
                         p.apellido_paterno, p.apellido_materno, p.fecha_nacimiento,
                         p.genero, p.estado_civil, p.nacionalidad, p.email,
                         p.telefono, p.celular, p.direccion, p.distrito, p.provincia,
                         p.departamento, p.foto
                  FROM " . $this->table . " a
                  INNER JOIN personas p ON a.persona_id = p.id
                  WHERE a.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener agremiado por persona_id
    public function getByPersonaId($persona_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE persona_id = :persona_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':persona_id', $persona_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener agremiado por número de colegiatura
    public function getByColegiatura($numero_colegiatura) {
        $query = "SELECT a.*, p.nombres, p.apellido_paterno, p.apellido_materno
                  FROM " . $this->table . " a
                  INNER JOIN personas p ON a.persona_id = p.id
                  WHERE a.numero_colegiatura = :numero_colegiatura
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_colegiatura', $numero_colegiatura);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear agremiado
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (persona_id, numero_colegiatura, fecha_colegiatura, universidad,
                   especialidad, anio_graduacion, estado, fecha_habilitacion,
                   observaciones, created_by)
                  VALUES
                  (:persona_id, :numero_colegiatura, :fecha_colegiatura, :universidad,
                   :especialidad, :anio_graduacion, :estado, :fecha_habilitacion,
                   :observaciones, :created_by)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':persona_id', $data['persona_id']);
        $stmt->bindParam(':numero_colegiatura', $data['numero_colegiatura']);
        $stmt->bindParam(':fecha_colegiatura', $data['fecha_colegiatura']);
        $stmt->bindParam(':universidad', $data['universidad']);
        $stmt->bindParam(':especialidad', $data['especialidad']);
        $stmt->bindParam(':anio_graduacion', $data['anio_graduacion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_habilitacion', $data['fecha_habilitacion']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        $stmt->bindParam(':created_by', $data['created_by']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Actualizar agremiado
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . "
                  SET numero_colegiatura = :numero_colegiatura,
                      fecha_colegiatura = :fecha_colegiatura,
                      universidad = :universidad,
                      especialidad = :especialidad,
                      anio_graduacion = :anio_graduacion,
                      estado = :estado,
                      fecha_habilitacion = :fecha_habilitacion,
                      fecha_inhabilitacion = :fecha_inhabilitacion,
                      motivo_inhabilitacion = :motivo_inhabilitacion,
                      observaciones = :observaciones
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':numero_colegiatura', $data['numero_colegiatura']);
        $stmt->bindParam(':fecha_colegiatura', $data['fecha_colegiatura']);
        $stmt->bindParam(':universidad', $data['universidad']);
        $stmt->bindParam(':especialidad', $data['especialidad']);
        $stmt->bindParam(':anio_graduacion', $data['anio_graduacion']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_habilitacion', $data['fecha_habilitacion']);
        $stmt->bindParam(':fecha_inhabilitacion', $data['fecha_inhabilitacion']);
        $stmt->bindParam(':motivo_inhabilitacion', $data['motivo_inhabilitacion']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Cambiar estado de agremiado
    public function changeStatus($id, $estado, $motivo = null, $fecha_inhabilitacion = null) {
        $query = "UPDATE " . $this->table . "
                  SET estado = :estado,
                      fecha_inhabilitacion = :fecha_inhabilitacion,
                      motivo_inhabilitacion = :motivo_inhabilitacion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fecha_inhabilitacion', $fecha_inhabilitacion);
        $stmt->bindParam(':motivo_inhabilitacion', $motivo);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Habilitar agremiado
    public function habilitar($id) {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'Activo',
                      fecha_habilitacion = CURDATE(),
                      fecha_inhabilitacion = NULL,
                      motivo_inhabilitacion = NULL
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Inhabilitar agremiado
    public function inhabilitar($id, $motivo) {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'Inhabilitado',
                      fecha_inhabilitacion = CURDATE(),
                      motivo_inhabilitacion = :motivo
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':motivo', $motivo);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Eliminar agremiado
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Verificar si el número de colegiatura ya existe
    public function colegiaturaExists($numero_colegiatura, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE numero_colegiatura = :numero_colegiatura";

        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }

        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_colegiatura', $numero_colegiatura);

        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Contar agremiados por estado
    public function countByStatus() {
        $query = "SELECT estado, COUNT(*) as total FROM " . $this->table . " GROUP BY estado";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Contar total de agremiados
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";

        if (!empty($filters['estado'])) {
            $query .= " AND estado = :estado";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['estado'])) {
            $stmt->bindParam(':estado', $filters['estado']);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // Generar siguiente número de colegiatura
    public function getNextNumeroColegiatura() {
        $query = "SELECT numero_colegiatura FROM " . $this->table . "
                  ORDER BY id DESC LIMIT 1";

        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Extraer número y incrementar
            $ultimo = intval(preg_replace('/[^0-9]/', '', $result['numero_colegiatura']));
            $siguiente = $ultimo + 1;
            return str_pad($siguiente, 6, '0', STR_PAD_LEFT);
        }

        return '000001';
    }
}
