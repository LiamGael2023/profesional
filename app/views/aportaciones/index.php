<?php
$pageTitle = 'Aportaciones';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['info'])): ?>
                <div class="alert alert-info alert-dismissible">
                    <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <?php if ($agremiado): ?>
                            Aportaciones - <?php echo htmlspecialchars($agremiado['apellido_paterno'] . ' ' . $agremiado['apellido_materno'] . ', ' . $agremiado['nombres']); ?>
                            <span class="badge bg-primary"><?php echo htmlspecialchars($agremiado['numero_colegiatura']); ?></span>
                        <?php else: ?>
                            Todas las Aportaciones
                        <?php endif; ?>
                    </h3>
                    <div class="card-actions">
                        <a href="<?php echo APP_URL; ?>/aportaciones/actualizarMontos<?php echo $agremiado ? '?agremiado_id=' . $agremiado['id'] : ''; ?>"
                           class="btn btn-warning me-2"
                           onclick="return confirm('¿Está seguro de actualizar los montos de las aportaciones pendientes según la configuración de montos?\n\nEsto actualizará TODAS las aportaciones pendientes y vencidas<?php echo $agremiado ? ' de este agremiado' : ''; ?> con los montos configurados.');"
                           title="Actualizar montos según configuración">
                            <i class="ti ti-refresh"></i> Actualizar Montos
                        </a>
                        <?php if ($agremiado): ?>
                            <a href="<?php echo APP_URL; ?>/agremiados/view?id=<?php echo $agremiado['id']; ?>" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Volver a Agremiado
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <div class="d-flex">
                            <div>
                                <i class="ti ti-info-circle me-2"></i>
                            </div>
                            <div>
                                <h4 class="alert-title">Actualización de Montos</h4>
                                <div class="text-muted">
                                    Si cambió los montos en la <a href="<?php echo APP_URL; ?>/configuracion-montos" class="alert-link">Configuración de Montos</a>,
                                    use el botón <strong>"Actualizar Montos"</strong> para aplicar los nuevos montos a las aportaciones pendientes y vencidas.
                                    Las aportaciones ya pagadas NO se modificarán.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form method="GET" class="row mb-3">
                        <?php if ($agremiado): ?>
                            <input type="hidden" name="agremiado_id" value="<?php echo $agremiado['id']; ?>">
                        <?php endif; ?>
                        <div class="col-md-3">
                            <label class="form-label">Año</label>
                            <select name="anio" class="form-select">
                                <?php
                                $anioActual = date('Y');
                                $anioSeleccionado = $_GET['anio'] ?? $anioActual;
                                for ($i = $anioActual; $i >= ($anioActual - 10); $i--):
                                ?>
                                    <option value="<?php echo $i; ?>" <?php echo $anioSeleccionado == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="">Todos</option>
                                <option value="Pendiente" <?php echo ($_GET['estado'] ?? '') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="Pagado" <?php echo ($_GET['estado'] ?? '') == 'Pagado' ? 'selected' : ''; ?>>Pagado</option>
                                <option value="Vencido" <?php echo ($_GET['estado'] ?? '') == 'Vencido' ? 'selected' : ''; ?>>Vencido</option>
                                <option value="Exonerado" <?php echo ($_GET['estado'] ?? '') == 'Exonerado' ? 'selected' : ''; ?>>Exonerado</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter"></i> Filtrar
                            </button>
                        </div>
                    </form>

                    <!-- Formulario de pago múltiple -->
                    <form id="formPagoMultiple" action="<?php echo APP_URL; ?>/aportaciones/pagarMultiple" method="POST">
                        <?php if ($agremiado): ?>
                            <input type="hidden" name="agremiado_id" value="<?php echo $agremiado['id']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <button type="button" id="btnPagarSeleccionadas" class="btn btn-success" disabled>
                                <i class="ti ti-cash"></i> Pagar Seleccionadas (<span id="contadorSeleccionadas">0</span>)
                            </button>
                            <button type="button" id="btnSeleccionarTodas" class="btn btn-outline-primary">
                                <i class="ti ti-checkbox"></i> Seleccionar todas pendientes
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="checkTodas" class="form-check-input">
                                        </th>
                                        <th>Período</th>
                                        <?php if (!$agremiado): ?>
                                            <th>Agremiado</th>
                                        <?php endif; ?>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Vencimiento</th>
                                        <th>Fecha Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($aportaciones)): ?>
                                        <tr>
                                            <td colspan="<?php echo $agremiado ? '7' : '8'; ?>" class="text-center text-muted">
                                                No hay aportaciones registradas
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php
                                        $meses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                        foreach ($aportaciones as $ap):
                                            $badgeClass = 'bg-secondary';
                                            if ($ap['estado'] == 'Pagado') $badgeClass = 'bg-success';
                                            if ($ap['estado'] == 'Pendiente') $badgeClass = 'bg-warning';
                                            if ($ap['estado'] == 'Vencido') $badgeClass = 'bg-danger';
                                            if ($ap['estado'] == 'Exonerado') $badgeClass = 'bg-info';

                                            $puedePagar = ($ap['estado'] == 'Pendiente' || $ap['estado'] == 'Vencido');
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if ($puedePagar): ?>
                                                    <input type="checkbox" name="aportaciones[]" value="<?php echo $ap['id']; ?>"
                                                           class="form-check-input checkbox-aportacion"
                                                           data-monto="<?php echo $ap['monto']; ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $meses[$ap['mes']] . ' ' . $ap['anio']; ?></td>
                                            <?php if (!$agremiado): ?>
                                                <td>
                                                    <?php echo htmlspecialchars($ap['apellido_paterno'] . ' ' . $ap['apellido_materno'] . ', ' . $ap['nombres']); ?>
                                                    <br><small class="text-muted"><?php echo htmlspecialchars($ap['numero_colegiatura']); ?></small>
                                                </td>
                                            <?php endif; ?>
                                            <td>S/ <?php echo number_format($ap['monto'], 2); ?></td>
                                            <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $ap['estado']; ?></span></td>
                                            <td><?php echo $ap['fecha_vencimiento'] ? date('d/m/Y', strtotime($ap['fecha_vencimiento'])) : '-'; ?></td>
                                            <td><?php echo $ap['fecha_pago'] ? date('d/m/Y', strtotime($ap['fecha_pago'])) : '-'; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const checkboxes = document.querySelectorAll('.checkbox-aportacion');
                        const btnPagar = document.getElementById('btnPagarSeleccionadas');
                        const contador = document.getElementById('contadorSeleccionadas');
                        const checkTodas = document.getElementById('checkTodas');
                        const btnSeleccionarTodas = document.getElementById('btnSeleccionarTodas');

                        function actualizarContador() {
                            const seleccionadas = document.querySelectorAll('.checkbox-aportacion:checked');
                            const count = seleccionadas.length;
                            contador.textContent = count;
                            btnPagar.disabled = count === 0;

                            // Calcular total
                            let total = 0;
                            seleccionadas.forEach(cb => {
                                total += parseFloat(cb.dataset.monto || 0);
                            });

                            if (count > 0) {
                                contador.textContent = count + ' - S/ ' + total.toFixed(2);
                            } else {
                                contador.textContent = '0';
                            }
                        }

                        checkboxes.forEach(cb => {
                            cb.addEventListener('change', actualizarContador);
                        });

                        checkTodas.addEventListener('change', function() {
                            checkboxes.forEach(cb => {
                                cb.checked = this.checked;
                            });
                            actualizarContador();
                        });

                        btnSeleccionarTodas.addEventListener('click', function() {
                            checkboxes.forEach(cb => {
                                cb.checked = true;
                            });
                            checkTodas.checked = true;
                            actualizarContador();
                        });

                        btnPagar.addEventListener('click', function() {
                            const seleccionadas = document.querySelectorAll('.checkbox-aportacion:checked');
                            if (seleccionadas.length === 0) {
                                alert('Seleccione al menos una aportación para pagar');
                                return;
                            }

                            // Mostrar modal de confirmación con formulario
                            if (confirm('¿Desea registrar el pago de ' + seleccionadas.length + ' aportación(es)?')) {
                                document.getElementById('formPagoMultiple').submit();
                            }
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
