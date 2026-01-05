<?php
/**
 * Script para generar aportaciones mensuales de forma automática
 * Genera aportaciones desde la fecha de colegiatura/traslado hasta 3 meses en el futuro
 * Para ejecutar manualmente o mediante Windows Task Scheduler
 *
 * Ejecución manual:
 * php public/cron/generar-aportaciones-mensual.php
 */

// Cargar configuración y clases necesarias
require_once __DIR__ . '/../../app/config/config.php';
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/models/Aportacion.php';
require_once __DIR__ . '/../../app/models/Agremiado.php';

// Registrar inicio de ejecución
$fecha_inicio = date('Y-m-d H:i:s');
echo "==============================================\n";
echo "Generación Automática de Aportaciones\n";
echo "Fecha: {$fecha_inicio}\n";
echo "==============================================\n\n";

try {
    // Conectar a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    if (!$db) {
        throw new Exception("Error al conectar con la base de datos");
    }

    echo "✓ Conexión a base de datos establecida\n";

    // Instanciar modelos
    $agremiadoModel = new Agremiado($db);
    $aportacionModel = new Aportacion($db);

    // Primero, marcar aportaciones vencidas
    echo "\nActualizando estados de aportaciones vencidas...\n";
    $aportacionModel->marcarVencidas();
    echo "✓ Estados actualizados\n";

    // Obtener todos los agremiados activos
    echo "\nObteniendo lista de agremiados activos...\n";
    $agremiados = $agremiadoModel->getAll(['estado' => 'Activo']);
    $total_agremiados = count($agremiados);
    echo "✓ Se encontraron {$total_agremiados} agremiados activos\n";

    if ($total_agremiados == 0) {
        echo "\nNo hay agremiados activos. Finalizando proceso.\n";
        exit(0);
    }

    // Contadores
    $total_creadas = 0;
    $total_existentes = 0;
    $agremiados_procesados = 0;
    $agremiados_con_nuevas = 0;

    echo "\nGenerando aportaciones...\n";
    echo str_repeat("-", 70) . "\n";

    // Generar aportaciones para cada agremiado
    foreach ($agremiados as $agremiado) {
        $agremiados_procesados++;

        // Determinar fecha de inicio según tipo de incorporación
        if (!empty($agremiado['fecha_traslado']) &&
            ($agremiado['tipo_incorporacion'] == 'Traslado' ||
             $agremiado['tipo_incorporacion'] == 'Incorporación')) {
            $fecha_inicio = $agremiado['fecha_traslado'];
            $tipo_fecha = 'Traslado/Incorporación';
        } else {
            $fecha_inicio = $agremiado['fecha_colegiatura'];
            $tipo_fecha = 'Colegiatura';
        }

        echo "\n[{$agremiados_procesados}/{$total_agremiados}] ";
        echo "Agremiado: {$agremiado['numero_colegiatura']} - ";
        echo "{$agremiado['apellido_paterno']} {$agremiado['apellido_materno']}, {$agremiado['nombres']}\n";
        echo "    Fecha inicio: {$fecha_inicio} ({$tipo_fecha})\n";

        // Generar aportaciones (desde fecha_inicio hasta hoy + 3 meses)
        $resultado = $aportacionModel->generarAportacionesMensuales(
            $agremiado['id'],
            $fecha_inicio,
            0.00, // Monto por defecto, se puede configurar después
            1 // Usuario del sistema (ID 1)
        );

        if ($resultado['creadas'] > 0) {
            echo "    ✓ Creadas: {$resultado['creadas']} aportaciones\n";
            $agremiados_con_nuevas++;
        } else {
            echo "    - Sin aportaciones nuevas\n";
        }

        if ($resultado['existentes'] > 0) {
            echo "    - Existentes: {$resultado['existentes']} aportaciones\n";
        }

        $total_creadas += $resultado['creadas'];
        $total_existentes += $resultado['existentes'];
    }

    // Resumen final
    echo "\n" . str_repeat("=", 70) . "\n";
    echo "RESUMEN DE EJECUCIÓN\n";
    echo str_repeat("=", 70) . "\n";
    echo "Fecha finalización: " . date('Y-m-d H:i:s') . "\n";
    echo "Agremiados procesados: {$agremiados_procesados}\n";
    echo "Agremiados con nuevas aportaciones: {$agremiados_con_nuevas}\n";
    echo "Total aportaciones creadas: {$total_creadas}\n";
    echo "Total aportaciones existentes: {$total_existentes}\n";
    echo str_repeat("=", 70) . "\n";

    // Código de salida exitoso
    exit(0);

} catch (Exception $e) {
    echo "\n✗ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";

    // Código de salida con error
    exit(1);
}
