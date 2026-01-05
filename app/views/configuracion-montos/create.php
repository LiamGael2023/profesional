<?php
$pageTitle = 'Nueva Configuración de Monto';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Nueva Configuración de Monto</h3>
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

                            <form action="<?php echo APP_URL; ?>/configuracion-montos/store" method="POST">
                                <div class="mb-3">
                                    <label for="monto" class="form-label required">Monto (S/)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="monto" name="monto"
                                           placeholder="Ej: 28.00" required>
                                    <small class="form-hint">Monto de la aportación mensual en soles</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="periodo_inicio" class="form-label required">Período Inicio</label>
                                            <input type="month" class="form-control" id="periodo_inicio" name="periodo_inicio"
                                                   value="<?php echo date('Y-m'); ?>" required>
                                            <small class="form-hint">Desde qué mes aplicará este monto</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="periodo_fin" class="form-label">Período Fin (Opcional)</label>
                                            <input type="month" class="form-control" id="periodo_fin" name="periodo_fin">
                                            <small class="form-hint">Hasta qué mes. Dejar vacío para vigencia indefinida</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                              placeholder="Ej: Monto vigente desde diciembre 2025"></textarea>
                                    <small class="form-hint">Descripción opcional para identificar esta configuración</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i>
                                    <strong>Importante:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Si deja el <strong>Período Fin</strong> vacío, este monto estará vigente hasta que cree una nueva configuración.</li>
                                        <li>Al crear una configuración nueva sin período fin, las configuraciones anteriores se cerrarán automáticamente.</li>
                                        <li>El sistema aplicará el monto correspondiente según el período al generar las aportaciones.</li>
                                    </ul>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check"></i> Guardar Configuración
                                    </button>
                                    <a href="<?php echo APP_URL; ?>/configuracion-montos" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel de ayuda -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Guía Rápida</h3>
                        </div>
                        <div class="card-body">
                            <h4>¿Cómo funciona?</h4>
                            <p>El sistema busca el monto configurado para cada período al generar las aportaciones.</p>

                            <h4 class="mt-3">Ejemplo 1: Aumento de monto</h4>
                            <p class="small">Si el monto era S/ 25.00 y aumenta a S/ 30.00 desde marzo 2026:</p>
                            <ul class="small">
                                <li><strong>Monto:</strong> 30.00</li>
                                <li><strong>Inicio:</strong> 2026-03</li>
                                <li><strong>Fin:</strong> (vacío)</li>
                            </ul>

                            <h4 class="mt-3">Ejemplo 2: Monto temporal</h4>
                            <p class="small">Promoción especial solo por 3 meses:</p>
                            <ul class="small">
                                <li><strong>Monto:</strong> 20.00</li>
                                <li><strong>Inicio:</strong> 2026-01</li>
                                <li><strong>Fin:</strong> 2026-03</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
