<?php
$pageTitle = 'Editar Persona';
require_once APP_PATH . '/views/layouts/header.php';
?>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="page-wrapper">
    <div class="page-body">
        <div class="container-xl">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <a class="btn-close" data-bs-dismiss="alert"></a>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/personas/update" method="POST" enctype="multipart/form-data" id="formPersona">
                <input type="hidden" name="id" value="<?php echo $persona['id']; ?>">

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Datos Personales</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tipo Documento</label>
                                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                                            <option value="DNI" <?php echo $persona['tipo_documento'] == 'DNI' ? 'selected' : ''; ?>>DNI</option>
                                            <option value="RUC" <?php echo $persona['tipo_documento'] == 'RUC' ? 'selected' : ''; ?>>RUC</option>
                                            <option value="CE" <?php echo $persona['tipo_documento'] == 'CE' ? 'selected' : ''; ?>>CE</option>
                                            <option value="Pasaporte" <?php echo $persona['tipo_documento'] == 'Pasaporte' ? 'selected' : ''; ?>>Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <label class="form-label">Número de Documento *</label>
                                        <input type="text"
                                               name="numero_documento"
                                               id="numero_documento"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['numero_documento']); ?>"
                                               placeholder="Ingrese el número de documento"
                                               maxlength="20"
                                               required>
                                    </div>
                                </div>

                                <!-- Campos para RUC (Razón Social) -->
                                <div class="mb-3" id="campo_razon_social" style="display: none;">
                                    <label class="form-label">Razón Social *</label>
                                    <input type="text"
                                           name="razon_social"
                                           id="razon_social"
                                           class="form-control"
                                           value="<?php echo $persona['tipo_documento'] == 'RUC' ? htmlspecialchars($persona['nombres']) : ''; ?>"
                                           placeholder="Nombre o razón social de la empresa">
                                </div>

                                <!-- Campos para Persona Natural (DNI, CE, Pasaporte) -->
                                <div id="campos_persona_natural">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Apellido Paterno *</label>
                                            <input type="text"
                                                   name="apellido_paterno"
                                                   id="apellido_paterno"
                                                   class="form-control"
                                                   value="<?php echo htmlspecialchars($persona['apellido_paterno']); ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Apellido Materno</label>
                                            <input type="text"
                                                   name="apellido_materno"
                                                   id="apellido_materno"
                                                   class="form-control"
                                                   value="<?php echo htmlspecialchars($persona['apellido_materno']); ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nombres *</label>
                                        <input type="text"
                                               name="nombres"
                                               id="nombres"
                                               class="form-control"
                                               value="<?php echo $persona['tipo_documento'] != 'RUC' ? htmlspecialchars($persona['nombres']) : ''; ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha Nacimiento</label>
                                        <input type="date"
                                               name="fecha_nacimiento"
                                               id="fecha_nacimiento"
                                               class="form-control"
                                               value="<?php echo $persona['fecha_nacimiento']; ?>">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Género</label>
                                        <select name="genero" id="genero" class="form-select">
                                            <option value="Masculino" <?php echo $persona['genero'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Femenino" <?php echo $persona['genero'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado Civil</label>
                                        <select name="estado_civil" id="estado_civil" class="form-select">
                                            <option value="Soltero" <?php echo $persona['estado_civil'] == 'Soltero' ? 'selected' : ''; ?>>Soltero</option>
                                            <option value="Casado" <?php echo $persona['estado_civil'] == 'Casado' ? 'selected' : ''; ?>>Casado</option>
                                            <option value="Divorciado" <?php echo $persona['estado_civil'] == 'Divorciado' ? 'selected' : ''; ?>>Divorciado</option>
                                            <option value="Viudo" <?php echo $persona['estado_civil'] == 'Viudo' ? 'selected' : ''; ?>>Viudo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nacionalidad</label>
                                    <input type="text"
                                           name="nacionalidad"
                                           id="nacionalidad"
                                           class="form-control"
                                           value="<?php echo htmlspecialchars($persona['nacionalidad']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header"><h3 class="card-title">Información de Contacto</h3></div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control"
                                           value="<?php echo htmlspecialchars($persona['email']); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text"
                                               name="telefono"
                                               id="telefono"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['telefono']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Celular</label>
                                        <input type="text"
                                               name="celular"
                                               id="celular"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['celular']); ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text"
                                           name="direccion"
                                           id="direccion"
                                           class="form-control"
                                           value="<?php echo htmlspecialchars($persona['direccion']); ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Urbanización</label>
                                        <input type="text"
                                               name="urbanizacion"
                                               id="urbanizacion"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['urbanizacion'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Distrito</label>
                                        <input type="text"
                                               name="distrito"
                                               id="distrito"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['distrito']); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Provincia</label>
                                        <input type="text"
                                               name="provincia"
                                               id="provincia"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['provincia']); ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Departamento</label>
                                        <input type="text"
                                               name="departamento"
                                               id="departamento"
                                               class="form-control"
                                               value="<?php echo htmlspecialchars($persona['departamento']); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Foto</h3></div>
                            <div class="card-body text-center">
                                <?php if ($persona['foto']): ?>
                                    <img src="<?php echo APP_URL; ?>/images/personas/<?php echo $persona['foto']; ?>"
                                         alt="Foto" class="img-thumbnail mb-3" style="max-width: 200px;">
                                <?php else: ?>
                                    <div class="mb-3">
                                        <i class="ti ti-photo text-muted" style="font-size: 5rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="ti ti-device-floppy"></i> Guardar Cambios
                                </button>
                                <a href="<?php echo APP_URL; ?>/personas/view?id=<?php echo $persona['id']; ?>" class="btn btn-secondary w-100">
                                    <i class="ti ti-x"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Función para mostrar/ocultar campos según tipo de documento
function toggleCamposPorTipoDocumento() {
    const tipoDocumento = document.getElementById('tipo_documento');
    const campoRazonSocial = document.getElementById('campo_razon_social');
    const camposPersonaNatural = document.getElementById('campos_persona_natural');

    const inputRazonSocial = document.getElementById('razon_social');
    const inputApellidoPaterno = document.getElementById('apellido_paterno');
    const inputNombres = document.getElementById('nombres');

    // Verificar que todos los elementos existan
    if (!tipoDocumento || !campoRazonSocial || !camposPersonaNatural ||
        !inputRazonSocial || !inputApellidoPaterno || !inputNombres) {
        return;
    }

    if (tipoDocumento.value === 'RUC') {
        // Mostrar campo de Razón Social
        campoRazonSocial.style.display = 'block';
        camposPersonaNatural.style.display = 'none';

        // Ajustar validación required
        inputRazonSocial.required = true;
        inputApellidoPaterno.required = false;
        inputNombres.required = false;
    } else {
        // Mostrar campos de Persona Natural
        campoRazonSocial.style.display = 'none';
        camposPersonaNatural.style.display = 'block';

        // Ajustar validación required
        inputRazonSocial.required = false;
        inputApellidoPaterno.required = true;
        inputNombres.required = true;
    }
}

// Asegurar que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    // Ejecutar al cargar la página
    toggleCamposPorTipoDocumento();

    // Ejecutar al cambiar el tipo de documento
    document.getElementById('tipo_documento').addEventListener('change', toggleCamposPorTipoDocumento);
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
