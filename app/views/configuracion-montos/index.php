<?php
$pageTitle = 'Configuración de Montos';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Configuración de Montos de Aportaciones</h3>
                    <div class="card-actions">
                        <a href="<?php echo APP_URL; ?>/configuracion-montos/create" class="btn btn-primary">
                            <i class="ti ti-plus"></i> Nueva Configuración
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle"></i>
                        <strong>Información:</strong> Aquí puede configurar los montos de las aportaciones por períodos de tiempo.
                        Si el período final es vacío, la configuración estará vigente hasta que se cree una nueva.
                        Al crear una nueva configuración sin período final, la anterior se cerrará automáticamente.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Monto</th>
                                    <th>Período Inicio</th>
                                    <th>Período Fin</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($configuraciones)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No hay configuraciones registradas
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $meses = [
                                        '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril',
                                        '05' => 'Mayo', '06' => 'Junio', '07' => 'Julio', '08' => 'Agosto',
                                        '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre', '12' => 'Diciembre'
                                    ];

                                    function formatearPeriodo($periodo, $meses) {
                                        if (!$periodo) return '-';
                                        $partes = explode('-', $periodo);
                                        return $meses[$partes[1]] . ' ' . $partes[0];
                                    }

                                    foreach ($configuraciones as $config):
                                        $periodo_actual = date('Y-m');
                                        $esta_vigente = ($config['periodo_inicio'] <= $periodo_actual) &&
                                                       (is_null($config['periodo_fin']) || $config['periodo_fin'] >= $periodo_actual);
                                    ?>
                                    <tr class="<?php echo $esta_vigente ? 'table-success' : ''; ?>">
                                        <td class="fw-bold text-success">S/ <?php echo number_format($config['monto'], 2); ?></td>
                                        <td><?php echo formatearPeriodo($config['periodo_inicio'], $meses); ?></td>
                                        <td>
                                            <?php if ($config['periodo_fin']): ?>
                                                <?php echo formatearPeriodo($config['periodo_fin'], $meses); ?>
                                            <?php else: ?>
                                                <span class="badge bg-primary">Vigente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($config['descripcion'] ?? '-'); ?></td>
                                        <td>
                                            <?php if ($esta_vigente): ?>
                                                <span class="badge bg-success">Vigente</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Histórico</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo APP_URL; ?>/configuracion-montos/edit?id=<?php echo $config['id']; ?>"
                                               class="btn btn-sm btn-primary" title="Editar">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <a href="<?php echo APP_URL; ?>/configuracion-montos/delete?id=<?php echo $config['id']; ?>"
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('¿Está seguro de eliminar esta configuración?')"
                                               title="Eliminar">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ejemplos de uso -->
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Ejemplos de Configuración</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Ejemplo 1: Cambio de monto a partir de un mes</h4>
                            <ul>
                                <li><strong>Monto:</strong> S/ 28.00</li>
                                <li><strong>Período Inicio:</strong> 2025-01</li>
                                <li><strong>Período Fin:</strong> 2025-11</li>
                                <li><strong>Descripción:</strong> Monto enero a noviembre 2025</li>
                            </ul>
                            <ul>
                                <li><strong>Monto:</strong> S/ 30.00</li>
                                <li><strong>Período Inicio:</strong> 2025-12</li>
                                <li><strong>Período Fin:</strong> (vacío)</li>
                                <li><strong>Descripción:</strong> Monto desde diciembre 2025 en adelante</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4>Ejemplo 2: Monto único vigente</h4>
                            <ul>
                                <li><strong>Monto:</strong> S/ 25.00</li>
                                <li><strong>Período Inicio:</strong> 2024-01</li>
                                <li><strong>Período Fin:</strong> (vacío)</li>
                                <li><strong>Descripción:</strong> Monto vigente desde enero 2024</li>
                            </ul>
                            <p class="text-muted">Este monto se aplicará a todas las aportaciones desde enero 2024 hasta que se cree una nueva configuración.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
