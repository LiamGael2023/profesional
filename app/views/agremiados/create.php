<?php
$pageTitle = 'Nuevo Agremiado';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <form action="<?php echo APP_URL; ?>/agremiados/store" method="POST">
                <input type="hidden" name="persona_id" value="<?php echo $persona['id']; ?>">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Datos del Agremiado</h3></div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Persona:</strong> <?php echo htmlspecialchars($persona['apellido_paterno'] . ' ' . $persona['apellido_materno'] . ', ' . $persona['nombres']); ?><br>
                                    <strong>Documento:</strong> <?php echo htmlspecialchars($persona['numero_documento']); ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Número de Colegiatura *</label>
                                    <input type="text" name="numero_colegiatura" class="form-control" value="<?php echo $siguiente_numero; ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Colegiatura *</label>
                                    <input type="date" name="fecha_colegiatura" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Universidad</label>
                                    <input type="text" name="universidad" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Año de Graduación</label>
                                    <input type="number" name="anio_graduacion" class="form-control" min="1950" max="<?php echo date('Y'); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select name="estado" class="form-select">
                                        <option value="Activo">Activo</option>
                                        <option value="Suspendido">Suspendido</option>
                                        <option value="Inhabilitado">Inhabilitado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> Guardar</button>
                                <a href="<?php echo APP_URL; ?>/personas" class="btn btn-link">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
