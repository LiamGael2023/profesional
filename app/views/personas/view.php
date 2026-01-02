<?php
$pageTitle = 'Ver Persona';
require_once APP_PATH . '/views/layouts/header.php';
?>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información Personal</h3>
                            <div class="card-actions">
                                <a href="<?php echo APP_URL; ?>/personas/edit?id=<?php echo $persona['id']; ?>" class="btn btn-primary">
                                    <i class="ti ti-edit"></i> Editar
                                </a>
                                <a href="<?php echo APP_URL; ?>/personas" class="btn btn-secondary">
                                    <i class="ti ti-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tipo Documento:</label>
                                    <p><?php echo htmlspecialchars($persona['tipo_documento']); ?></p>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Número Documento:</label>
                                    <p><?php echo htmlspecialchars($persona['numero_documento']); ?></p>
                                </div>
                            </div>

                            <?php if ($persona['tipo_documento'] === 'RUC'): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Razón Social:</label>
                                    <p><?php echo htmlspecialchars($persona['nombres']); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Apellido Paterno:</label>
                                        <p><?php echo htmlspecialchars($persona['apellido_paterno']); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Apellido Materno:</label>
                                        <p><?php echo htmlspecialchars($persona['apellido_materno']); ?></p>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nombres:</label>
                                    <p><?php echo htmlspecialchars($persona['nombres']); ?></p>
                                </div>
                            <?php endif; ?>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Fecha Nacimiento:</label>
                                    <p><?php echo $persona['fecha_nacimiento'] ? date('d/m/Y', strtotime($persona['fecha_nacimiento'])) : '-'; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Género:</label>
                                    <p><?php echo htmlspecialchars($persona['genero']); ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Estado Civil:</label>
                                    <p><?php echo htmlspecialchars($persona['estado_civil']); ?></p>
                                </div>
                            </div>

                            <hr>

                            <h4>Información de Contacto</h4>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p><?php echo $persona['email'] ? htmlspecialchars($persona['email']) : '-'; ?></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Teléfono:</label>
                                    <p><?php echo $persona['telefono'] ? htmlspecialchars($persona['telefono']) : '-'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Celular:</label>
                                    <p><?php echo $persona['celular'] ? htmlspecialchars($persona['celular']) : '-'; ?></p>
                                </div>
                            </div>

                            <hr>

                            <h4>Dirección</h4>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dirección:</label>
                                <p><?php echo $persona['direccion'] ? htmlspecialchars($persona['direccion']) : '-'; ?></p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Urbanización:</label>
                                    <p><?php echo !empty($persona['urbanizacion']) ? htmlspecialchars($persona['urbanizacion']) : '-'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Distrito:</label>
                                    <p><?php echo !empty($persona['distrito']) ? htmlspecialchars($persona['distrito']) : '-'; ?></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Provincia:</label>
                                    <p><?php echo $persona['provincia'] ? htmlspecialchars($persona['provincia']) : '-'; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Departamento:</label>
                                    <p><?php echo $persona['departamento'] ? htmlspecialchars($persona['departamento']) : '-'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <?php if ($agremiado): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h3 class="card-title">Estado de Agremiado</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Número Colegiatura:</label>
                                <p class="fs-3 text-success"><?php echo htmlspecialchars($agremiado['numero_colegiatura']); ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Estado:</label>
                                <p>
                                    <?php if ($agremiado['estado'] == 'habilitado'): ?>
                                        <span class="badge bg-success">Habilitado</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inhabilitado</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Fecha Afiliación:</label>
                                <p><?php echo date('d/m/Y', strtotime($agremiado['created_at'])); ?></p>
                            </div>
                            <a href="<?php echo APP_URL; ?>/agremiados/view?id=<?php echo $agremiado['id']; ?>" class="btn btn-success w-100">
                                <i class="ti ti-eye"></i> Ver Detalles Agremiado
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="ti ti-user-plus text-muted" style="font-size: 3rem;"></i>
                            <h3 class="mt-3">No es Agremiado</h3>
                            <p class="text-muted">Esta persona aún no ha sido afiliada al colegio profesional.</p>
                            <a href="<?php echo APP_URL; ?>/agremiados/create?persona_id=<?php echo $persona['id']; ?>" class="btn btn-primary">
                                <i class="ti ti-user-plus"></i> Afiliar Ahora
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
