<?php
// Cargar configuraciones dinámicas
$appName = getAppName();
$headerBgColor = getHeaderBgColor();
$headerTextColor = getHeaderTextColor();
$logoDesktop = getLogoUrl('desktop');
$logoMobile = getLogoUrl('mobile');
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - <?php echo $appName; ?></title>
    <!-- CSS de Tabler -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" rel="stylesheet"/>
    <style>
        .navbar-brand-image {
            height: 2rem;
        }
        .navbar-brand-image-mobile {
            height: 2rem;
            width: 2rem;
            object-fit: contain;
        }
        /* Estilos dinámicos del header */
        .navbar-custom {
            background-color: <?php echo $headerBgColor; ?> !important;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-brand a,
        .navbar-custom .nav-link,
        .navbar-custom .navbar-toggler-icon {
            color: <?php echo $headerTextColor; ?> !important;
        }
        .navbar-custom .nav-link:hover {
            opacity: 0.8;
        }
        .navbar-custom .dropdown-toggle::after {
            border-top-color: <?php echo $headerTextColor; ?>;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md d-print-none navbar-custom">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Logo -->
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="<?php echo APP_URL; ?>/dashboard" style="color: <?php echo $headerTextColor; ?>;">
                        <?php if ($logoDesktop): ?>
                            <img src="<?php echo $logoDesktop; ?>"
                                 alt="<?php echo $appName; ?>"
                                 class="navbar-brand-image d-none d-md-inline">
                            <?php if ($logoMobile): ?>
                                <img src="<?php echo $logoMobile; ?>"
                                     alt="<?php echo $appName; ?>"
                                     class="navbar-brand-image-mobile d-md-none">
                            <?php else: ?>
                                <img src="<?php echo $logoDesktop; ?>"
                                     alt="<?php echo $appName; ?>"
                                     class="navbar-brand-image-mobile d-md-none">
                            <?php endif; ?>
                        <?php else: ?>
                            <i class="ti ti-lock-square-rounded" style="font-size: 2rem; color: <?php echo $headerTextColor; ?>;"></i>
                            <span class="ms-2"><?php echo $appName; ?></span>
                        <?php endif; ?>
                    </a>
                </h1>

                <!-- Menú principal -->
                <div class="navbar-nav flex-row order-md-last">
                    <!-- Notificaciones -->
                    <div class="nav-item dropdown d-none d-md-flex me-3">
                        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                            <i class="ti ti-bell"></i>
                            <span class="badge bg-red"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Últimas notificaciones</h3>
                                </div>
                                <div class="list-group list-group-flush list-group-hoverable">
                                    <div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <i class="ti ti-info-circle text-info"></i>
                                            </div>
                                            <div class="col text-truncate">
                                                <div class="text-body d-block">No hay notificaciones nuevas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Perfil de usuario -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name=<?php echo urlencode($user['full_name']); ?>&background=206bc4&color=fff)"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div><?php echo htmlspecialchars($user['full_name']); ?></div>
                                <div class="mt-1 small text-muted"><?php echo htmlspecialchars($user['username']); ?></div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item">
                                <i class="ti ti-user me-2"></i>
                                Mi Perfil
                            </a>
                            <a href="<?php echo APP_URL; ?>/settings" class="dropdown-item">
                                <i class="ti ti-palette me-2"></i>
                                Personalización
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo APP_URL; ?>/logout" class="dropdown-item">
                                <i class="ti ti-logout me-2"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Menú de navegación -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <li class="nav-item active">
                                <a class="nav-link" href="<?php echo APP_URL; ?>/dashboard">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-home"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Inicio
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-package"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Módulos
                                    </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="#">
                                                <i class="ti ti-users me-2"></i>
                                                Usuarios
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="ti ti-chart-bar me-2"></i>
                                                Reportes
                                            </a>
                                            <a class="dropdown-item" href="<?php echo APP_URL; ?>/settings">
                                                <i class="ti ti-palette me-2"></i>
                                                Personalización
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <i class="ti ti-file-text"></i>
                                    </span>
                                    <span class="nav-link-title">
                                        Documentación
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
