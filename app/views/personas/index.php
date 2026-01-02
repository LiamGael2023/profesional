<?php
$pageTitle = 'Gestión de Personas';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Gestión de Personas</h2>
                </div>
                <div class="col-auto">
                    <a href="<?php echo APP_URL; ?>/personas/create" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Nueva Persona
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

            <!-- Búsqueda -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por documento, nombre..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button class="btn btn-primary" type="submit"><i class="ti ti-search"></i> Buscar</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Listado -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Apellidos y Nombres</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Estado</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personas as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['numero_documento']); ?></td>
                                <td>
                                    <?php
                                    // Si es RUC, mostrar solo razón social (nombres), si es persona mostrar apellidos y nombres
                                    if ($p['tipo_documento'] === 'RUC') {
                                        echo htmlspecialchars($p['nombres']);
                                    } else {
                                        echo htmlspecialchars($p['apellido_paterno'] . ' ' . $p['apellido_materno'] . ', ' . $p['nombres']);
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($p['email']); ?></td>
                                <td><?php echo htmlspecialchars($p['celular']); ?></td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="<?php echo APP_URL; ?>/personas/view?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary"><i class="ti ti-eye"></i></a>
                                        <a href="<?php echo APP_URL; ?>/personas/edit?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info"><i class="ti ti-edit"></i></a>
                                        <a href="<?php echo APP_URL; ?>/agremiados/create?persona_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success"><i class="ti ti-user-plus"></i> Afiliar</a>
                                    </div>
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
