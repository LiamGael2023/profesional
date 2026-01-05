<?php
$pageTitle = 'Aportaciones';
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

            <?php if (isset($_SESSION['info'])): ?>
                <div class="alert alert-info alert-dismissible">
                    <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php if ($agremiado): ?>
                            Aportaciones - <?php echo htmlspecialchars($agremiado['apellido_paterno'] . ' ' . $agremiado['apellido_materno'] . ', ' . $agremiado['nombres']); ?>
                            <span class="badge bg-primary"><?php echo htmlspecialchars($agremiado['numero_colegiatura']); ?></span>
                        <?php else: ?>
                            Todas las Aportaciones
                        <?php endif; ?>
                    </h3>
                    <div class="card-actions">
                        <?php if ($agremiado): ?>
                            <a href="<?php echo APP_URL; ?>/agremiados/view?id=<?php echo $agremiado['id']; ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Volver a Agremiado
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtros -->
                    <form method="GET" class="row mb-3">
                        <?php if ($agremiado): ?>
                            <input type="hidden" name="agremiado_id" value="<?php echo $agremiado['id']; ?>">
                        <?php endif; ?>
                        <div class="col-md-3">
                            <label class="form-label">Año</label>
                            <select name="anio" class="form-select">
                                <?php
                                $anioActual = date('Y');
                                $anioSeleccionado = $_GET['anio'] ?? $anioActual;
                                for ($i = $anioActual; $i >= ($anioActual - 10); $i--):
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $anioSeleccionado == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="Pendiente" <?php echo ($_GET['estado'] ?? '') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="Pagado" <?php echo ($_GET['estado'] ?? '') == 'Pagado' ? 'selected' : ''; ?>>Pagado</option>
                                <option value="Vencido" <?php echo ($_GET['estado'] ?? '') == 'Vencido' ? 'selected' : ''; ?>>Vencido</option>
                                <option value="Exonerado" <?php echo ($_GET['estado'] ?? '') == 'Exonerado' ? 'selected' : ''; ?>>Exonerado</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <?php if (!$agremiado): ?>
                                        <th>Agremiado</th>
                                    <?php endif; ?>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Vencimiento</th>
                                    <th>Fecha Pago</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($aportaciones)): ?>
                                    <tr>
                                        <td colspan="<?php echo $agremiado ? '6' : '7'; ?>" class="text-center text-muted">
                                            No hay aportaciones registradas
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                    foreach ($aportaciones as $ap):
                                        $badgeClass = 'bg-secondary';
                                        if ($ap['estado'] == 'Pagado') $badgeClass = 'bg-success';
                                        if ($ap['estado'] == 'Pendiente') $badgeClass = 'bg-warning';
                                        if ($ap['estado'] == 'Vencido') $badgeClass = 'bg-danger';
                                        if ($ap['estado'] == 'Exonerado') $badgeClass = 'bg-info';
                                    ?>
                                    <tr>
                                        <td><?php echo $meses[$ap['mes']] . ' ' . $ap['anio']; ?></td>
                                        <?php if (!$agremiado): ?>
                                            <td>
                                                <?php echo htmlspecialchars($ap['apellido_paterno'] . ' ' . $ap['apellido_materno'] . ', ' . $ap['nombres']); ?>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($ap['numero_colegiatura']); ?></small>
                                            </td>
                                        <?php endif; ?>
                                        <td>S/ <?php echo number_format($ap['monto'], 2); ?></td>
                                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $ap['estado']; ?></span></td>
                                        <td><?php echo $ap['fecha_vencimiento'] ? date('d/m/Y', strtotime($ap['fecha_vencimiento'])) : '-'; ?></td>
                                        <td><?php echo $ap['fecha_pago'] ? date('d/m/Y', strtotime($ap['fecha_pago'])) : '-'; ?></td>
                                        <td>
                                            <?php if ($ap['estado'] == 'Pendiente' || $ap['estado'] == 'Vencido'): ?>
                                                <a href="<?php echo APP_URL; ?>/aportaciones/pagar?id=<?php echo $ap['id']; ?>" class="btn btn-sm btn-success">
                                                    <i class="ti ti-cash"></i> Registrar Pago
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
