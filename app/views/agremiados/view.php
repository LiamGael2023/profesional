<?php
$pageTitle = 'Ver Agremiado';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información del Agremiado</h3>
                            <div class="card-actions">
                                <a href="<?php echo APP_URL; ?>/agremiados/edit?id=<?php echo $agremiado['id']; ?>" class="btn btn-primary">
                                    <i class="ti ti-edit"></i> Editar
                                </a>
                                <a href="<?php echo APP_URL; ?>/agremiados" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Número Colegiatura:</label>
                                    <p class="fs-3 text-primary"><?php echo htmlspecialchars($agremiado['numero_colegiatura']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Estado:</label>
                                    <p>
                                        <?php
                                        $badgeClass = 'bg-success';
                                        if ($agremiado['estado'] == 'Suspendido') $badgeClass = 'bg-warning';
                                        if ($agremiado['estado'] == 'Inhabilitado') $badgeClass = 'bg-danger';
                                        if ($agremiado['estado'] == 'Retirado') $badgeClass = 'bg-secondary';
                                        ?>
                                        <span class="badge <?php echo $badgeClass; ?> fs-5"><?php echo htmlspecialchars($agremiado['estado']); ?></span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <h4>Datos de Incorporación</h4>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Fecha de Colegiatura:</label>
                                    <p><?php echo date('d/m/Y', strtotime($agremiado['fecha_colegiatura'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo de Incorporación:</label>
                                    <p>
                                        <?php
                                        $tipo = $agremiado['tipo_incorporacion'] ?? 'Normal';
                                        $badgeTipo = 'bg-info';
                                        if ($tipo == 'Traslado') $badgeTipo = 'bg-warning';
                                        if ($tipo == 'Incorporación') $badgeTipo = 'bg-primary';
                                        ?>
                                        <span class="badge <?php echo $badgeTipo; ?>"><?php echo htmlspecialchars($tipo); ?></span>
                                    </p>
                                </div>
                            </div>

                            <?php if (!empty($agremiado['fecha_traslado'])): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha de Traslado/Incorporación:</label>
                                <p><?php echo date('d/m/Y', strtotime($agremiado['fecha_traslado'])); ?></p>
                            </div>
                            <?php endif; ?>

                            <hr>

                            <h4>Datos Personales</h4>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Nombre Completo:</label>
                                    <p class="fs-5">
                                        <?php
                                        if ($agremiado['tipo_documento'] === 'RUC') {
                                            echo htmlspecialchars($agremiado['nombres']);
                                        } else {
                                            echo htmlspecialchars($agremiado['apellido_paterno'] . ' ' . $agremiado['apellido_materno'] . ', ' . $agremiado['nombres']);
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Tipo Documento:</label>
                                    <p><?php echo htmlspecialchars($agremiado['tipo_documento']); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Número Documento:</label>
                                    <p><?php echo htmlspecialchars($agremiado['numero_documento']); ?></p>
                                </div>
                            </div>

                            <hr>

                            <h4>Información Académica</h4>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Universidad:</label>
                                <p><?php echo $agremiado['universidad'] ? htmlspecialchars($agremiado['universidad']) : '-'; ?></p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Especialidad:</label>
                                    <p><?php echo $agremiado['especialidad'] ? htmlspecialchars($agremiado['especialidad']) : '-'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Año de Graduación:</label>
                                    <p><?php echo $agremiado['anio_graduacion'] ?? '-'; ?></p>
                                </div>
                            </div>

                            <hr>

                            <h4>Información de Contacto</h4>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email:</label>
                                <p><?php echo $agremiado['email'] ? htmlspecialchars($agremiado['email']) : '-'; ?></p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Teléfono:</label>
                                    <p><?php echo $agremiado['telefono'] ?? '-'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Celular:</label>
                                    <p><?php echo $agremiado['celular'] ?? '-'; ?></p>
                                </div>
                            </div>

                            <?php if ($agremiado['observaciones']): ?>
                            <hr>
                            <h4>Observaciones</h4>
                            <div class="mb-3">
                                <p><?php echo nl2br(htmlspecialchars($agremiado['observaciones'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Datos de Persona</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <?php if (!empty($agremiado['foto'])): ?>
                                    <img src="<?php echo APP_URL; ?>/images/personas/<?php echo $agremiado['foto']; ?>"
                                         alt="Foto" class="img-thumbnail" style="max-width: 200px;">
                                <?php else: ?>
                                    <i class="ti ti-user-circle text-muted" style="font-size: 6rem;"></i>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo APP_URL; ?>/personas/view?id=<?php echo $agremiado['persona_id']; ?>" class="btn btn-info w-100">
                                <i class="ti ti-eye"></i> Ver Detalles Completos
                            </a>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Aportaciones</h3>
                        </div>
                        <div class="card-body">
                            <a href="<?php echo APP_URL; ?>/aportaciones?agremiado_id=<?php echo $agremiado['id']; ?>" class="btn btn-info w-100 mb-2">
                                <i class="ti ti-list"></i> Ver Aportaciones
                            </a>
                            <button type="button" class="btn btn-primary w-100" id="btnGenerarAportaciones"
                                    data-url="<?php echo APP_URL; ?>/aportaciones/generar?agremiado_id=<?php echo $agremiado['id']; ?>"
                                    data-tipo-fecha="<?php echo (!empty($agremiado['fecha_traslado']) && ($agremiado['tipo_incorporacion'] == 'Traslado' || $agremiado['tipo_incorporacion'] == 'Incorporación')) ? 'traslado/incorporación' : 'colegiatura'; ?>"
                                    data-fecha="<?php echo (!empty($agremiado['fecha_traslado']) && ($agremiado['tipo_incorporacion'] == 'Traslado' || $agremiado['tipo_incorporacion'] == 'Incorporación')) ? date('d/m/Y', strtotime($agremiado['fecha_traslado'])) : date('d/m/Y', strtotime($agremiado['fecha_colegiatura'])); ?>">
                                <i class="ti ti-plus"></i> Generar Aportaciones
                            </button>
                            <small class="text-muted d-block mt-2">
                                Se generarán desde: <?php
                                if (!empty($agremiado['fecha_traslado']) && ($agremiado['tipo_incorporacion'] == 'Traslado' || $agremiado['tipo_incorporacion'] == 'Incorporación')) {
                                    echo date('d/m/Y', strtotime($agremiado['fecha_traslado'])) . ' (Traslado)';
                                } else {
                                    echo date('d/m/Y', strtotime($agremiado['fecha_colegiatura'])) . ' (Colegiatura)';
                                }
                                ?>
                            </small>
                        </div>
                    </div>

                    <?php if ($agremiado['estado'] == 'Inhabilitado' || $agremiado['estado'] == 'Suspendido'): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Estado de Habilitación</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($agremiado['fecha_inhabilitacion']): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha Inhabilitación:</label>
                                <p><?php echo date('d/m/Y', strtotime($agremiado['fecha_inhabilitacion'])); ?></p>
                            </div>
                            <?php endif; ?>

                            <?php if ($agremiado['motivo_inhabilitacion']): ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Motivo:</label>
                                <p><?php echo nl2br(htmlspecialchars($agremiado['motivo_inhabilitacion'])); ?></p>
                            </div>
                            <?php endif; ?>

                            <form action="<?php echo APP_URL; ?>/agremiados/habilitar" method="POST">
                                <input type="hidden" name="id" value="<?php echo $agremiado['id']; ?>">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="ti ti-check"></i> Habilitar Agremiado
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php elseif ($agremiado['estado'] == 'Activo'): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-danger text-white">
                            <h3 class="card-title">Acciones</h3>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo APP_URL; ?>/agremiados/inhabilitar" method="POST" onsubmit="return confirm('¿Está seguro de inhabilitar este agremiado?');">
                                <input type="hidden" name="id" value="<?php echo $agremiado['id']; ?>">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="ti ti-x"></i> Inhabilitar Agremiado
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="card mt-3">
                        <div class="card-body">
                            <small class="text-muted">
                                <strong>Registrado:</strong> <?php echo date('d/m/Y H:i', strtotime($agremiado['created_at'])); ?><br>
                                <strong>Última actualización:</strong> <?php echo date('d/m/Y H:i', strtotime($agremiado['updated_at'])); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerarAportaciones = document.getElementById('btnGenerarAportaciones');

    if (btnGenerarAportaciones) {
        btnGenerarAportaciones.addEventListener('click', function() {
            const url = this.dataset.url;
            const tipoFecha = this.dataset.tipoFecha;
            const fecha = this.dataset.fecha;

            Swal.fire({
                title: '¿Generar aportaciones?',
                html: `¿Desea generar las aportaciones mensuales desde la fecha de <strong>${tipoFecha}</strong>?<br><br>
                       <small class="text-muted">Fecha de inicio: <strong>${fecha}</strong><br>
                       Se generarán hasta el mes actual + 3 meses futuros</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-plus me-1"></i> Sí, generar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
