<?php
class Person {
    private $conn;
    private $table = 'personas';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las personas
    public function getAll($filters = []) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];

        // Filtros opcionales
        if (!empty($filters['search'])) {
            $query .= " AND (numero_documento LIKE :search1
                        OR nombres LIKE :search2
                        OR apellido_paterno LIKE :search3
                        OR apellido_materno LIKE :search4
                        OR email LIKE :search5)";
        }

        if (isset($filters['is_active'])) {
            $query .= " AND is_active = :is_active";
        }

        $query .= " ORDER BY apellido_paterno ASC, apellido_materno ASC, nombres ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $stmt->bindValue(':search1', $searchTerm);
            $stmt->bindValue(':search2', $searchTerm);
            $stmt->bindValue(':search3', $searchTerm);
            $stmt->bindValue(':search4', $searchTerm);
            $stmt->bindValue(':search5', $searchTerm);
        }

        if (isset($filters['is_active'])) {
            $stmt->bindValue(':is_active', $filters['is_active']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener persona por ID
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtener persona por número de documento
    public function getByDocument($numero_documento) {
        $query = "SELECT * FROM " . $this->table . " WHERE numero_documento = :numero_documento LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_documento', $numero_documento);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear persona
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (tipo_documento, numero_documento, nombres, apellido_paterno, apellido_materno,
                   fecha_nacimiento, genero, estado_civil, nacionalidad, email, telefono, celular,
                   direccion, distrito, provincia, departamento, foto, created_by)
                  VALUES
                  (:tipo_documento, :numero_documento, :nombres, :apellido_paterno, :apellido_materno,
                   :fecha_nacimiento, :genero, :estado_civil, :nacionalidad, :email, :telefono, :celular,
                   :direccion, :distrito, :provincia, :departamento, :foto, :created_by)";

        $stmt = $this->conn->prepare($query);

        // Bind de parámetros
        $stmt->bindParam(':tipo_documento', $data['tipo_documento']);
        $stmt->bindParam(':numero_documento', $data['numero_documento']);
        $stmt->bindParam(':nombres', $data['nombres']);
        $stmt->bindParam(':apellido_paterno', $data['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $data['apellido_materno']);
        $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
        $stmt->bindParam(':genero', $data['genero']);
        $stmt->bindParam(':estado_civil', $data['estado_civil']);
        $stmt->bindParam(':nacionalidad', $data['nacionalidad']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':celular', $data['celular']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':distrito', $data['distrito']);
        $stmt->bindParam(':provincia', $data['provincia']);
        $stmt->bindParam(':departamento', $data['departamento']);
        $stmt->bindParam(':foto', $data['foto']);
        $stmt->bindParam(':created_by', $data['created_by']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Actualizar persona
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . "
                  SET tipo_documento = :tipo_documento,
                      numero_documento = :numero_documento,
                      nombres = :nombres,
                      apellido_paterno = :apellido_paterno,
                      apellido_materno = :apellido_materno,
                      fecha_nacimiento = :fecha_nacimiento,
                      genero = :genero,
                      estado_civil = :estado_civil,
                      nacionalidad = :nacionalidad,
                      email = :email,
                      telefono = :telefono,
                      celular = :celular,
                      direccion = :direccion,
                      distrito = :distrito,
                      provincia = :provincia,
                      departamento = :departamento";

        if (isset($data['foto'])) {
            $query .= ", foto = :foto";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':tipo_documento', $data['tipo_documento']);
        $stmt->bindParam(':numero_documento', $data['numero_documento']);
        $stmt->bindParam(':nombres', $data['nombres']);
        $stmt->bindParam(':apellido_paterno', $data['apellido_paterno']);
        $stmt->bindParam(':apellido_materno', $data['apellido_materno']);
        $stmt->bindParam(':fecha_nacimiento', $data['fecha_nacimiento']);
        $stmt->bindParam(':genero', $data['genero']);
        $stmt->bindParam(':estado_civil', $data['estado_civil']);
        $stmt->bindParam(':nacionalidad', $data['nacionalidad']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':celular', $data['celular']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':distrito', $data['distrito']);
        $stmt->bindParam(':provincia', $data['provincia']);
        $stmt->bindParam(':departamento', $data['departamento']);

        if (isset($data['foto'])) {
            $stmt->bindParam(':foto', $data['foto']);
        }

        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Eliminar persona (soft delete)
    public function delete($id) {
        $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Restaurar persona
    public function restore($id) {
        $query = "UPDATE " . $this->table . " SET is_active = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // Verificar si el documento ya existe
    public function documentExists($numero_documento, $excludeId = null) {
        $query = "SELECT id FROM " . $this->table . " WHERE numero_documento = :numero_documento";

        if ($excludeId) {
            $query .= " AND id != :excludeId";
        }

        $query .= " LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_documento', $numero_documento);

        if ($excludeId) {
            $stmt->bindParam(':excludeId', $excludeId);
        }

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Obtener personas sin agremiación
    public function getPersonasSinAgremiacion() {
        $query = "SELECT p.* FROM " . $this->table . " p
                  LEFT JOIN agremiados a ON p.id = a.persona_id
                  WHERE a.id IS NULL AND p.is_active = 1
                  ORDER BY p.apellido_paterno, p.apellido_materno, p.nombres";

        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener nombre completo
    public function getNombreCompleto($persona) {
        return trim($persona['apellido_paterno'] . ' ' .
                    $persona['apellido_materno'] . ' ' .
                    $persona['nombres']);
    }

    // Contar personas
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";

        if (isset($filters['is_active'])) {
            $query .= " AND is_active = :is_active";
        }

        $stmt = $this->conn->prepare($query);

        if (isset($filters['is_active'])) {
            $stmt->bindParam(':is_active', $filters['is_active']);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
