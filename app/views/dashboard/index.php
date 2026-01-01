<?php
$pageTitle = 'Dashboard';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Bienvenido
                    </div>
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-check alert-icon"></i>
                        </div>
                        <div class="ms-2">
                            <?php
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <!-- Tarjetas de estadísticas -->
            <div class="row row-deck row-cards">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Total de Usuarios</div>
                            </div>
                            <div class="h1 mb-3">2,456</div>
                            <div class="d-flex mb-2">
                                <div>Tasa de conversión</div>
                                <div class="ms-auto">
                                    <span class="text-green d-inline-flex align-items-center lh-1">
                                        12% <i class="ti ti-trending-up ms-1"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-primary" style="width: 75%" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                    <span class="visually-hidden">75% Completo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Sesiones Activas</div>
                            </div>
                            <div class="h1 mb-3">132</div>
                            <div class="d-flex mb-2">
                                <div>Usuarios en línea</div>
                                <div class="ms-auto">
                                    <span class="text-green d-inline-flex align-items-center lh-1">
                                        8% <i class="ti ti-trending-up ms-1"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" style="width: 54%" role="progressbar" aria-valuenow="54" aria-valuemin="0" aria-valuemax="100">
                                    <span class="visually-hidden">54% Completo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Ingresos</div>
                            </div>
                            <div class="h1 mb-3">$24,789</div>
                            <div class="d-flex mb-2">
                                <div>vs mes anterior</div>
                                <div class="ms-auto">
                                    <span class="text-red d-inline-flex align-items-center lh-1">
                                        -3% <i class="ti ti-trending-down ms-1"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-yellow" style="width: 62%" role="progressbar" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100">
                                    <span class="visually-hidden">62% Completo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Conversiones</div>
                            </div>
                            <div class="h1 mb-3">1,789</div>
                            <div class="d-flex mb-2">
                                <div>Tasa de éxito</div>
                                <div class="ms-auto">
                                    <span class="text-green d-inline-flex align-items-center lh-1">
                                        15% <i class="ti ti-trending-up ms-1"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-red" style="width: 89%" role="progressbar" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100">
                                    <span class="visually-hidden">89% Completo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido adicional -->
            <div class="row row-deck row-cards mt-3">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Actividad Reciente</h3>
                        </div>
                        <div class="card-body">
                            <div class="divide-y">
                                <div class="row">
                                    <div class="col-auto">
                                        <span class="avatar">JL</span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>John López</strong> inició sesión en el sistema
                                        </div>
                                        <div class="text-muted">Hace 10 minutos</div>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="badge bg-primary"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-auto">
                                        <span class="avatar" style="background-image: url(https://ui-avatars.com/api/?name=Maria+Garcia&background=28a745&color=fff)"></span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>María García</strong> actualizó su perfil
                                        </div>
                                        <div class="text-muted">Hace 2 horas</div>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="badge bg-success"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-auto">
                                        <span class="avatar" style="background-image: url(https://ui-avatars.com/api/?name=Pedro+Ramirez&background=dc3545&color=fff)"></span>
                                    </div>
                                    <div class="col">
                                        <div class="text-truncate">
                                            <strong>Pedro Ramírez</strong> generó un nuevo reporte
                                        </div>
                                        <div class="text-muted">Hace 5 horas</div>
                                    </div>
                                    <div class="col-auto align-self-center">
                                        <div class="badge bg-warning"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información del Usuario</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <span class="avatar avatar-lg" style="background-image: url(https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name']); ?>&background=206bc4&color=fff&size=128)"></span>
                                </div>
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                    <div class="text-muted"><?php echo htmlspecialchars($user['email']); ?></div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <strong>Usuario:</strong> <?php echo htmlspecialchars($user['username']); ?>
                            </div>
                            <div class="mb-2">
                                <strong>ID:</strong> <?php echo htmlspecialchars($user['id']); ?>
                            </div>
                            <div class="mt-3">
                                <a href="#" class="btn btn-primary w-100">
                                    <i class="ti ti-user me-2"></i>
                                    Ver Perfil Completo
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Accesos Rápidos</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="ti ti-settings me-2"></i>
                                Configuración
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="ti ti-shield-lock me-2"></i>
                                Seguridad
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="ti ti-bell me-2"></i>
                                Notificaciones
                            </a>
                            <a href="<?php echo APP_URL; ?>/logout" class="list-group-item list-group-item-action text-danger">
                                <i class="ti ti-logout me-2"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
