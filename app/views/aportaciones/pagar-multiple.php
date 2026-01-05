<?php
$pageTitle = 'Registrar Pago Múltiple';
require_once APP_PATH . '/views/layouts/header.php';

$meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Registrar Pago de Múltiples Aportaciones</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo APP_URL; ?>/aportaciones/procesarPagoMultiple" method="POST">
                                <?php if ($agremiado_id): ?>
                                    <input type="hidden" name="agremiado_id" value="<?php echo $agremiado_id; ?>">
                                <?php endif; ?>

                                <!-- Lista de aportaciones seleccionadas -->
                                <div class="mb-4">
                                    <h4>Aportaciones a Pagar</h4>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Período</th>
                                                    <th>Agremiado</th>
                                                    <th class="text-end">Monto</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($aportaciones as $ap): ?>
                                                    <tr>
                                                        <td><?php echo $meses[$ap['mes']] . ' ' . $ap['anio']; ?></td>
                                                        <td>
                                                            <?php echo htmlspecialchars($ap['apellido_paterno'] . ' ' . $ap['apellido_materno'] . ', ' . $ap['nombres']); ?>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($ap['numero_colegiatura']); ?></small>
                                                        </td>
                                                        <td class="text-end">S/ <?php echo number_format($ap['monto'], 2); ?></td>
                                                    </tr>
                                                    <input type="hidden" name="aportaciones_ids[]" value="<?php echo $ap['id']; ?>">
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr class="fw-bold">
                                                    <td colspan="2" class="text-end">TOTAL:</td>
                                                    <td class="text-end">S/ <?php echo number_format($total, 2); ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <!-- Datos del pago -->
                                <div class="mb-3">
                                    <label for="fecha_pago" class="form-label">Fecha de Pago *</label>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago"
                                           value="<?php echo date('Y-m-d'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="metodo_pago" class="form-label">Método de Pago *</label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago" required>
                                        <option value="">Seleccione...</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Transferencia">Transferencia Bancaria</option>
                                        <option value="Depósito">Depósito Bancario</option>
                                        <option value="Tarjeta">Tarjeta de Crédito/Débito</option>
                                        <option value="Yape">Yape</option>
                                        <option value="Plin">Plin</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="numero_operacion" class="form-label">Número de Operación</label>
                                    <input type="text" class="form-control" id="numero_operacion" name="numero_operacion"
                                           placeholder="Ej: 123456789">
                                    <small class="form-hint">Número de comprobante, voucher o transacción (opcional)</small>
                                </div>

                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones"
                                              rows="3" placeholder="Notas adicionales (opcional)"></textarea>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="ti ti-check"></i> Confirmar Pago (<?php echo count($aportaciones); ?> aportaciones - S/ <?php echo number_format($total, 2); ?>)
                                    </button>
                                    <a href="<?php echo APP_URL; ?>/aportaciones<?php echo $agremiado_id ? '?agremiado_id=' . $agremiado_id : ''; ?>"
                                       class="btn btn-secondary">
                                        <i class="ti ti-x"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral con resumen -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Resumen</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted">Total de aportaciones</div>
                                <div class="h2"><?php echo count($aportaciones); ?></div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted">Monto Total</div>
                                <div class="h2 text-success">S/ <?php echo number_format($total, 2); ?></div>
                            </div>
                            <?php if (count($aportaciones) > 0 && isset($aportaciones[0])): ?>
                                <hr>
                                <div class="mb-2">
                                    <div class="text-muted">Agremiado</div>
                                    <div class="fw-bold">
                                        <?php echo htmlspecialchars($aportaciones[0]['apellido_paterno'] . ' ' . $aportaciones[0]['apellido_materno'] . ', ' . $aportaciones[0]['nombres']); ?>
                                    </div>
                                    <small class="text-muted"><?php echo htmlspecialchars($aportaciones[0]['numero_colegiatura']); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
