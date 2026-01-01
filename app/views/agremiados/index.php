<?php
$pageTitle = 'Gestión de Agremiados';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col"><h2 class="page-title">Gestión de Agremiados</h2></div>
                <div class="col-auto">
                    <a href="<?php echo APP_URL; ?>/personas" class="btn btn-primary">
                        <i class="ti ti-user-plus"></i> Afiliar Persona
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Nº Colegiatura</th>
                                <th>Documento</th>
                                <th>Apellidos y Nombres</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agremiados as $a): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($a['numero_colegiatura']); ?></strong></td>
                                <td><?php echo htmlspecialchars($a['numero_documento']); ?></td>
                                <td><?php echo htmlspecialchars($a['apellido_paterno'] . ' ' . $a['apellido_materno'] . ', ' . $a['nombres']); ?></td>
                                <td><?php echo htmlspecialchars($a['especialidad']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = ['Activo' => 'success', 'Suspendido' => 'warning', 'Inhabilitado' => 'danger'][$a['estado']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $badgeClass; ?>"><?php echo $a['estado']; ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo APP_URL; ?>/agremiados/view?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
