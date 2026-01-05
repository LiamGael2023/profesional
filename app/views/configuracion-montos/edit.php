<?php
$pageTitle = 'Editar Configuración de Monto';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Configuración de Monto</h3>
                    <div class="card-actions">
                        <a href="<?php echo APP_URL; ?>/configuracion-montos" class="btn btn-secondary">
                            <i class="ti ti-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <a class="btn-close" data-bs-dismiss="alert"></a>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo APP_URL; ?>/configuracion-montos/update" method="POST">
                        <input type="hidden" name="id" value="<?php echo $configuracion['id']; ?>">

                        <div class="mb-3">
                            <label for="monto" class="form-label required">Monto (S/)</label>
                            <input type="number" step="0.01" min="0" class="form-control" id="monto" name="monto"
                                   value="<?php echo htmlspecialchars($configuracion['monto']); ?>" required>
                            <small class="form-hint">Monto de la aportación mensual en soles</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="periodo_inicio" class="form-label required">Período Inicio</label>
                                    <input type="month" class="form-control" id="periodo_inicio" name="periodo_inicio"
                                           value="<?php echo htmlspecialchars($configuracion['periodo_inicio']); ?>" required>
                                    <small class="form-hint">Desde qué mes aplicará este monto</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="periodo_fin" class="form-label">Período Fin (Opcional)</label>
                                    <input type="month" class="form-control" id="periodo_fin" name="periodo_fin"
                                           value="<?php echo htmlspecialchars($configuracion['periodo_fin'] ?? ''); ?>">
                                    <small class="form-hint">Hasta qué mes. Dejar vacío para vigencia indefinida</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($configuracion['descripcion'] ?? ''); ?></textarea>
                            <small class="form-hint">Descripción opcional para identificar esta configuración</small>
                        </div>

                        <div class="alert alert-warning">
                            <i class="ti ti-alert-triangle"></i>
                            <strong>Atención:</strong> Los cambios en esta configuración NO afectarán las aportaciones ya generadas.
                            Solo se aplicarán a las nuevas aportaciones que se generen en el futuro.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check"></i> Actualizar Configuración
                            </button>
                            <a href="<?php echo APP_URL; ?>/configuracion-montos" class="btn btn-secondary">
                                <i class="ti ti-x"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
