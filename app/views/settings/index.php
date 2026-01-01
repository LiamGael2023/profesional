<?php
$pageTitle = 'Personalización';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Configuración
                    </div>
                    <h2 class="page-title">
                        Personalización del Sistema
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

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-alert-circle alert-icon"></i>
                        </div>
                        <div class="ms-2">
                            <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                            ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/settings/update" method="POST" enctype="multipart/form-data">
                <div class="row row-cards">
                    <!-- Configuración General -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="ti ti-settings me-2"></i>
                                    Configuración General
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Nombre de la Aplicación</label>
                                    <input type="text"
                                           name="app_name"
                                           class="form-control"
                                           value="<?php echo htmlspecialchars($settings['app_name'] ?? 'MVC Login System'); ?>"
                                           placeholder="Nombre de tu aplicación">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Color del Header</label>
                                    <div class="row g-2">
                                        <div class="col-auto">
                                            <input type="color"
                                                   name="header_bg_color"
                                                   id="header_bg_color"
                                                   class="form-control form-control-color"
                                                   value="<?php echo $settings['header_bg_color'] ?? '#206bc4'; ?>"
                                                   title="Seleccionar color">
                                        </div>
                                        <div class="col">
                                            <input type="text"
                                                   id="header_bg_color_text"
                                                   class="form-control"
                                                   value="<?php echo $settings['header_bg_color'] ?? '#206bc4'; ?>"
                                                   readonly>
                                        </div>
                                    </div>
                                    <small class="form-hint">
                                        El sistema ajustará automáticamente el color del texto (blanco o negro) según el color de fondo seleccionado.
                                    </small>
                                </div>

                                <!-- Vista previa del color -->
                                <div class="mb-3">
                                    <label class="form-label">Vista Previa</label>
                                    <div id="color-preview" class="p-3 rounded" style="background-color: <?php echo $settings['header_bg_color'] ?? '#206bc4'; ?>;">
                                        <span id="preview-text" style="font-weight: bold;">Texto del Header</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Desktop -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="ti ti-photo me-2"></i>
                                    Logo Desktop (Rectangular)
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($settings['logo_desktop'])): ?>
                                    <div class="mb-3 text-center">
                                        <img src="<?php echo APP_URL; ?>/public/images/<?php echo htmlspecialchars($settings['logo_desktop']); ?>"
                                             alt="Logo Desktop"
                                             class="img-fluid"
                                             style="max-height: 100px; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                        <div class="mt-2">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="deleteLogo('logo_desktop')">
                                                <i class="ti ti-trash"></i>
                                                Eliminar Logo
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <?php echo !empty($settings['logo_desktop']) ? 'Cambiar Logo Desktop' : 'Subir Logo Desktop'; ?>
                                    </label>
                                    <input type="file"
                                           name="logo_desktop"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="form-hint">
                                        Formatos: JPG, PNG, GIF, WEBP, SVG. Tamaño máx: 2MB. Recomendado: Imagen rectangular.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Mobile -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="ti ti-device-mobile me-2"></i>
                                    Logo Mobile (Cuadrado)
                                </h3>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($settings['logo_mobile'])): ?>
                                    <div class="mb-3 text-center">
                                        <img src="<?php echo APP_URL; ?>/public/images/<?php echo htmlspecialchars($settings['logo_mobile']); ?>"
                                             alt="Logo Mobile"
                                             class="img-fluid"
                                             style="max-height: 80px; max-width: 80px; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                        <div class="mt-2">
                                            <button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="deleteLogo('logo_mobile')">
                                                <i class="ti ti-trash"></i>
                                                Eliminar Logo
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <?php echo !empty($settings['logo_mobile']) ? 'Cambiar Logo Mobile' : 'Subir Logo Mobile'; ?>
                                    </label>
                                    <input type="file"
                                           name="logo_mobile"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="form-hint">
                                        Formatos: JPG, PNG, GIF, WEBP, SVG. Tamaño máx: 2MB. Recomendado: Imagen cuadrada.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón guardar -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="<?php echo APP_URL; ?>/dashboard" class="btn btn-link">
                                            <i class="ti ti-arrow-left me-2"></i>
                                            Volver al Dashboard
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy me-2"></i>
                                            Guardar Cambios
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Formulario oculto para eliminar logos -->
<form id="delete-logo-form" method="POST" action="<?php echo APP_URL; ?>/settings/deleteLogo" style="display: none;">
    <input type="hidden" name="logo_type" id="delete-logo-type">
</form>

<script>
// Sincronizar color picker con input de texto
const colorInput = document.getElementById('header_bg_color');
const colorTextInput = document.getElementById('header_bg_color_text');
const colorPreview = document.getElementById('color-preview');
const previewText = document.getElementById('preview-text');

colorInput.addEventListener('input', function() {
    const color = this.value;
    colorTextInput.value = color;
    updateColorPreview(color);
});

function updateColorPreview(color) {
    colorPreview.style.backgroundColor = color;

    // Calcular si el color es oscuro
    const rgb = hexToRgb(color);
    const luminance = (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;

    // Ajustar color del texto
    previewText.style.color = luminance < 0.5 ? '#ffffff' : '#000000';
}

function hexToRgb(hex) {
    const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

function deleteLogo(logoType) {
    if (confirm('¿Estás seguro de que quieres eliminar este logo?')) {
        document.getElementById('delete-logo-type').value = logoType;
        document.getElementById('delete-logo-form').submit();
    }
}

// Inicializar vista previa
updateColorPreview(colorInput.value);
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
