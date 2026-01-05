<?php
class Aportacion {
    private $conn;
    private $table = 'aportaciones';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todas las aportaciones con datos del agremiado
    public function getAll($filters = []) {
        $query = "SELECT ap.*, ag.numero_colegiatura, ag.estado as estado_agremiado,
                         p.nombres, p.apellido_paterno, p.apellido_materno, p.numero_documento
                  FROM " . $this->table . " ap
                  INNER JOIN agremiados ag ON ap.agremiado_id = ag.id
                  INNER JOIN personas p ON ag.persona_id = p.id
                  WHERE 1=1";

        // Filtros opcionales
        if (!empty($filters['agremiado_id'])) {
            $query .= " AND ap.agremiado_id = :agremiado_id";
        }

        if (!empty($filters['estado'])) {
            $query .= " AND ap.estado = :estado";
        }

        if (!empty($filters['anio'])) {
            $query .= " AND ap.anio = :anio";
        }

        if (!empty($filters['mes'])) {
            $query .= " AND ap.mes = :mes";
        }

        // Filtrar solo hasta un período máximo (ej: 2026-01 para mostrar solo hasta enero 2026)
        if (!empty($filters['periodo_max'])) {
            $query .= " AND ap.periodo <= :periodo_max";
        }

        $query .= " ORDER BY ap.anio DESC, ap.mes DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['agremiado_id'])) {
            $stmt->bindParam(':agremiado_id', $filters['agremiado_id']);
        }

        if (!empty($filters['estado'])) {
            $stmt->bindParam(':estado', $filters['estado']);
        }

        if (!empty($filters['anio'])) {
            $stmt->bindParam(':anio', $filters['anio']);
        }

        if (!empty($filters['mes'])) {
            $stmt->bindParam(':mes', $filters['mes']);
        }

        if (!empty($filters['periodo_max'])) {
            $stmt->bindParam(':periodo_max', $filters['periodo_max']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener aportación por ID
    public function getById($id) {
        $query = "SELECT ap.*, ag.numero_colegiatura, ag.estado as estado_agremiado,
                         p.nombres, p.apellido_paterno, p.apellido_materno, p.numero_documento, p.tipo_documento
                  FROM " . $this->table . " ap
                  INNER JOIN agremiados ag ON ap.agremiado_id = ag.id
                  INNER JOIN personas p ON ag.persona_id = p.id
                  WHERE ap.id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crear aportación
    public function create($data) {
        $query = "INSERT INTO " . $this->table . "
                  (agremiado_id, periodo, anio, mes, monto, estado, fecha_vencimiento, created_by)
                  VALUES
                  (:agremiado_id, :periodo, :anio, :mes, :monto, :estado, :fecha_vencimiento, :created_by)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':agremiado_id', $data['agremiado_id']);
        $stmt->bindParam(':periodo', $data['periodo']);
        $stmt->bindParam(':anio', $data['anio']);
        $stmt->bindParam(':mes', $data['mes']);
        $stmt->bindParam(':monto', $data['monto']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
        $stmt->bindParam(':created_by', $data['created_by']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    // Verificar si ya existe una aportación para un período
    public function existePeriodo($agremiado_id, $periodo) {
        $query = "SELECT id FROM " . $this->table . " WHERE agremiado_id = :agremiado_id AND periodo = :periodo LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':agremiado_id', $agremiado_id);
        $stmt->bindParam(':periodo', $periodo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Generar aportaciones mensuales desde una fecha hasta hoy + 3 meses futuros
    public function generarAportacionesMensuales($agremiado_id, $fecha_inicio, $monto_default = 0.00, $created_by) {
        $fecha_inicio_obj = new DateTime($fecha_inicio);
        $fecha_actual = new DateTime();

        // Generar hasta 3 meses en el futuro
        $fecha_limite = clone $fecha_actual;
        $fecha_limite->modify('+3 months');

        $aportaciones_creadas = 0;
        $aportaciones_existentes = 0;

        // Obtener modelo de configuración de montos
        require_once APP_PATH . '/models/ConfiguracionMonto.php';
        $configuracionMontoModel = new ConfiguracionMonto($this->conn);

        // Iterar desde la fecha de inicio hasta 3 meses en el futuro
        while ($fecha_inicio_obj <= $fecha_limite) {
            $anio = (int)$fecha_inicio_obj->format('Y');
            $mes = (int)$fecha_inicio_obj->format('m');
            $periodo = $fecha_inicio_obj->format('Y-m');

            // Verificar si ya existe
            if (!$this->existePeriodo($agremiado_id, $periodo)) {
                // Obtener monto configurado para este período
                $monto = $configuracionMontoModel->getMontoParaPeriodo($periodo);

                // Si no hay configuración, usar monto por defecto
                if ($monto === null || $monto === false) {
                    $monto = $monto_default;
                }

                // Calcular fecha de vencimiento (último día del mes)
                $fecha_vencimiento = $fecha_inicio_obj->format('Y-m-t');

                $data = [
                    'agremiado_id' => $agremiado_id,
                    'periodo' => $periodo,
                    'anio' => $anio,
                    'mes' => $mes,
                    'monto' => $monto,
                    'estado' => 'Pendiente',
                    'fecha_vencimiento' => $fecha_vencimiento,
                    'created_by' => $created_by
                ];

                if ($this->create($data)) {
                    $aportaciones_creadas++;
                }
            } else {
                $aportaciones_existentes++;
            }

            // Avanzar al siguiente mes
            $fecha_inicio_obj->modify('+1 month');
        }

        return [
            'creadas' => $aportaciones_creadas,
            'existentes' => $aportaciones_existentes
        ];
    }

    // Registrar pago de aportación
    public function registrarPago($id, $data) {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'Pagado',
                      fecha_pago = :fecha_pago,
                      metodo_pago = :metodo_pago,
                      numero_operacion = :numero_operacion,
                      observaciones = :observaciones
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fecha_pago', $data['fecha_pago']);
        $stmt->bindParam(':metodo_pago', $data['metodo_pago']);
        $stmt->bindParam(':numero_operacion', $data['numero_operacion']);
        $stmt->bindParam(':observaciones', $data['observaciones']);

        return $stmt->execute();
    }

    // Actualizar estado vencidas (llamar periódicamente)
    public function marcarVencidas() {
        $query = "UPDATE " . $this->table . "
                  SET estado = 'Vencido'
                  WHERE estado = 'Pendiente'
                  AND fecha_vencimiento < CURDATE()";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    // Actualizar montos de aportaciones según configuración de montos
    public function actualizarMontosPorConfiguracion($agremiado_id = null, $solo_pendientes = true) {
        // Obtener modelo de configuración de montos
        require_once APP_PATH . '/models/ConfiguracionMonto.php';
        $configuracionMontoModel = new ConfiguracionMonto($this->conn);

        // Construir query base
        $query = "SELECT id, periodo, monto FROM " . $this->table . " WHERE 1=1";

        // Filtrar por agremiado si se especifica
        if ($agremiado_id) {
            $query .= " AND agremiado_id = :agremiado_id";
        }

        // Solo actualizar aportaciones pendientes o vencidas (no las pagadas)
        if ($solo_pendientes) {
            $query .= " AND estado IN ('Pendiente', 'Vencido')";
        }

        $stmt = $this->conn->prepare($query);

        if ($agremiado_id) {
            $stmt->bindParam(':agremiado_id', $agremiado_id);
        }

        $stmt->execute();
        $aportaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $actualizadas = 0;
        $sin_cambios = 0;

        foreach ($aportaciones as $aportacion) {
            // Obtener monto configurado para este período
            $monto_nuevo = $configuracionMontoModel->getMontoParaPeriodo($aportacion['periodo']);

            // Si hay configuración y es diferente al monto actual, actualizar
            if ($monto_nuevo !== null && $monto_nuevo !== false && $monto_nuevo != $aportacion['monto']) {
                $updateQuery = "UPDATE " . $this->table . " SET monto = :monto WHERE id = :id";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(':monto', $monto_nuevo);
                $updateStmt->bindParam(':id', $aportacion['id']);

                if ($updateStmt->execute()) {
                    $actualizadas++;
                }
            } else {
                $sin_cambios++;
            }
        }

        return [
            'actualizadas' => $actualizadas,
            'sin_cambios' => $sin_cambios,
            'total' => count($aportaciones)
        ];
    }
}
