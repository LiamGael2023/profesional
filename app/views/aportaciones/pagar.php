<?php
$pageTitle = 'Registrar Pago';
require_once APP_PATH . '/views/layouts/header.php';
$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Registrar Pago de Aportación</h3>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Agremiado:</strong> <?php echo htmlspecialchars($aportacion['apellido_paterno'] . ' ' . $aportacion['apellido_materno'] . ', ' . $aportacion['nombres']); ?><br>
                                <strong>Colegiatura:</strong> <?php echo htmlspecialchars($aportacion['numero_colegiatura']); ?><br>
                                <strong>Período:</strong> <?php echo $meses[$aportacion['mes']] . ' ' . $aportacion['anio']; ?><br>
                                <strong>Monto:</strong> S/ <?php echo number_format($aportacion['monto'], 2); ?>
                            </div>

                            <form action="<?php echo APP_URL; ?>/aportaciones/procesarPago" method="POST">
                                <input type="hidden" name="id" value="<?php echo $aportacion['id']; ?>">
                                <input type="hidden" name="agremiado_id" value="<?php echo $aportacion['agremiado_id']; ?>">

                                <div class="mb-3">
                                    <label class="form-label">Fecha de Pago *</label>
                                    <input type="date" name="fecha_pago" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Método de Pago</label>
                                    <select name="metodo_pago" class="form-select">
                                        <option value="">Seleccione...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia Bancaria</option>
                                        <option value="Depósito">Depósito Bancario</option>
                                        <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                        <option value="Yape">Yape</option>
                                        <option value="Plin">Plin</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número de Operación/Transacción</label>
                                    <input type="text" name="numero_operacion" class="form-control" placeholder="Número de comprobante u operación">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-check"></i> Registrar Pago
                                    </button>
                                    <a href="<?php echo APP_URL; ?>/aportaciones?agremiado_id=<?php echo $aportacion['agremiado_id']; ?>" class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
