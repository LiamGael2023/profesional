<?php
$pageTitle = 'Nueva Persona';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <form action="<?php echo APP_URL; ?>/personas/store" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Datos Personales</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tipo Documento</label>
                                        <select name="tipo_documento" class="form-select" required>
                                            <option value="DNI">DNI</option>
                                            <option value="CE">CE</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <label class="form-label">Número de Documento *</label>
                                        <input type="text" name="numero_documento" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido Paterno *</label>
                                        <input type="text" name="apellido_paterno" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido Materno</label>
                                        <input type="text" name="apellido_materno" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nombres *</label>
                                    <input type="text" name="nombres" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha Nacimiento</label>
                                        <input type="date" name="fecha_nacimiento" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Género</label>
                                        <select name="genero" class="form-select">
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado Civil</label>
                                        <select name="estado_civil" class="form-select">
                                            <option value="Soltero">Soltero</option>
                                            <option value="Casado">Casado</option>
                                            <option value="Divorciado">Divorciado</option>
                                            <option value="Viudo">Viudo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Celular</label>
                                        <input type="text" name="celular" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" name="direccion" class="form-control">
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
