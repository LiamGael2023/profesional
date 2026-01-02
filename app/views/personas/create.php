<?php
$pageTitle = 'Nueva Persona';
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

            <form action="<?php echo APP_URL; ?>/personas/store" method="POST" enctype="multipart/form-data" id="formPersona">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header"><h3 class="card-title">Datos Personales</h3></div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Tipo Documento</label>
                                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                                            <option value="DNI">DNI</option>
                                            <option value="RUC">RUC</option>
                                            <option value="CE">CE</option>
                                            <option value="Pasaporte">Pasaporte</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9 mb-3">
                                        <label class="form-label">Número de Documento *</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   name="numero_documento"
                                                   id="numero_documento"
                                                   class="form-control"
                                                   placeholder="Ingrese el número de documento"
                                                   maxlength="20"
                                                   required>
                                            <button type="button"
                                                    class="btn btn-primary"
                                                    id="btnBuscarDNI"
                                                    onclick="buscarDNI()">
                                                <i class="ti ti-search"></i> Buscar
                                            </button>
                                        </div>
                                        <small class="form-hint">Para DNI: ingrese 8 dígitos. Para RUC: ingrese 11 dígitos. Luego presione Buscar</small>
                                    </div>
                                </div>

                                <!-- Campos para RUC (Razón Social) -->
                                <div class="mb-3" id="campo_razon_social" style="display: none;">
                                    <label class="form-label">Razón Social *</label>
                                    <input type="text"
                                           name="razon_social"
                                           id="razon_social"
                                           class="form-control"
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
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Apellido Materno</label>
                                            <input type="text"
                                                   name="apellido_materno"
                                                   id="apellido_materno"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Nombres *</label>
                                        <input type="text"
                                               name="nombres"
                                               id="nombres"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Fecha Nacimiento</label>
                                        <input type="date"
                                               name="fecha_nacimiento"
                                               id="fecha_nacimiento"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Género</label>
                                        <select name="genero" id="genero" class="form-select">
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Estado Civil</label>
                                        <select name="estado_civil" id="estado_civil" class="form-select">
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
                                        <input type="email"
                                               name="email"
                                               id="email"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Celular</label>
                                        <input type="text"
                                               name="celular"
                                               id="celular"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text"
                                           name="direccion"
                                           id="direccion"
                                           class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Urbanización</label>
                                        <input type="text"
                                               name="urbanizacion"
                                               id="urbanizacion"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Distrito</label>
                                        <input type="text"
                                               name="distrito"
                                               id="distrito"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Provincia</label>
                                        <input type="text"
                                               name="provincia"
                                               id="provincia"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Departamento</label>
                                        <input type="text"
                                               name="departamento"
                                               id="departamento"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> Guardar
                                </button>
                                <a href="<?php echo APP_URL; ?>/personas" class="btn btn-link">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function buscarDNI() {
    const tipoDocumento = document.getElementById('tipo_documento').value;
    const numeroDocumento = document.getElementById('numero_documento').value;
    const btnBuscar = document.getElementById('btnBuscarDNI');

    // Validar que sea DNI o RUC
    if (tipoDocumento !== 'DNI' && tipoDocumento !== 'RUC') {
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: 'La búsqueda automática solo está disponible para DNI y RUC peruano',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Validar según el tipo de documento
    if (tipoDocumento === 'DNI') {
        if (numeroDocumento.length !== 8 || !/^\d+$/.test(numeroDocumento)) {
            Swal.fire({
                icon: 'warning',
                title: 'DNI Inválido',
                text: 'El DNI debe tener exactamente 8 dígitos',
                confirmButtonText: 'OK'
            });
            return;
        }
    } else if (tipoDocumento === 'RUC') {
        if (numeroDocumento.length !== 11 || !/^\d+$/.test(numeroDocumento)) {
            Swal.fire({
                icon: 'warning',
                title: 'RUC Inválido',
                text: 'El RUC debe tener exactamente 11 dígitos',
                confirmButtonText: 'OK'
            });
            return;
        }
    }

    // Mostrar loading
    btnBuscar.disabled = true;
    btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Buscando...';

    try {
        const response = await fetch(`<?php echo APP_URL; ?>/api/consulta-dni.php?numero=${numeroDocumento}&tipo=${tipoDocumento}`);

        console.log('Response status:', response.status);
        console.log('Response ok:', response.ok);

        // Leer el texto de la respuesta primero
        const responseText = await response.text();
        console.log('Response text:', responseText);

        // Intentar parsear como JSON
        let data;
        try {
            data = JSON.parse(responseText);
            console.log('Parsed data:', data);
        } catch (parseError) {
            console.error('Error al parsear JSON:', parseError);
            throw new Error('Respuesta inválida del servidor');
        }

        // Verificar si se encontraron datos
        // La API puede devolver el DNI tanto en 'numeroDocumento' como en 'dni'
        if (data && (data.numeroDocumento || data.dni) && (data.nombres || data.nombre)) {
            // Rellenar los campos (soportar múltiples formatos de respuesta)
            const nombres = data.nombres || data.nombre || '';
            const apellidoPaterno = data.apellidoPaterno || data.apellido_paterno || '';
            const apellidoMaterno = data.apellidoMaterno || data.apellido_materno || '';

            if (tipoDocumento === 'RUC') {
                // Para RUC, llenar directamente la razón social
                document.getElementById('razon_social').value = nombres;
            } else {
                // Para DNI y otros, llenar apellidos y nombres
                document.getElementById('apellido_paterno').value = apellidoPaterno;
                document.getElementById('apellido_materno').value = apellidoMaterno;
                document.getElementById('nombres').value = nombres;
            }

            // Rellenar dirección si está disponible
            if (data.direccion) {
                document.getElementById('direccion').value = data.direccion;
            }
            if (data.distrito) {
                document.getElementById('distrito').value = data.distrito;
            }
            if (data.provincia) {
                document.getElementById('provincia').value = data.provincia;
            }
            if (data.departamento) {
                document.getElementById('departamento').value = data.departamento;
            }

            // Mostrar éxito
            let infoHTML = `
                <div class="text-start">
                    <p><strong>${tipoDocumento}:</strong> ${numeroDocumento}</p>
            `;

            if (tipoDocumento === 'RUC') {
                infoHTML += `<p><strong>Razón Social:</strong> ${nombres}</p>`;
                if (data.estado) infoHTML += `<p><strong>Estado:</strong> ${data.estado}</p>`;
                if (data.condicion) infoHTML += `<p><strong>Condición:</strong> ${data.condicion}</p>`;
            } else {
                const nombreCompleto = apellidoPaterno + ' ' + apellidoMaterno + ' ' + nombres;
                infoHTML += `<p><strong>Nombre Completo:</strong> ${nombreCompleto.trim()}</p>`;
            }

            infoHTML += `
                    <p class="text-muted mb-0">Los datos han sido rellenados automáticamente</p>
                </div>
            `;

            Swal.fire({
                icon: 'success',
                title: '¡Encontrado!',
                html: infoHTML,
                confirmButtonText: 'Continuar'
            });
        } else {
            // No se encontró o respuesta vacía
            console.warn(`${tipoDocumento} no encontrado o respuesta vacía:`, data);
            Swal.fire({
                icon: 'warning',
                title: `${tipoDocumento} No Encontrado`,
                html: `
                    <p>No se encontraron datos para el ${tipoDocumento} <strong>${numeroDocumento}</strong></p>
                    <p class="text-muted">Puede continuar y registrar los datos manualmente</p>
                `,
                confirmButtonText: 'Registrar Manualmente',
                showCancelButton: true,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enfocar en el primer campo
                    document.getElementById('apellido_paterno').focus();
                }
            });
        }
    } catch (error) {
        console.error(`Error completo al buscar ${tipoDocumento}:`, error);

        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            html: `
                <p>No se pudo conectar con el servicio de consulta de ${tipoDocumento}</p>
                <p class="text-muted small">Error: ${error.message}</p>
                <p class="text-muted">Puede continuar y registrar los datos manualmente</p>
            `,
            confirmButtonText: 'Registrar Manualmente',
            showCancelButton: true,
            cancelButtonText: 'Reintentar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('apellido_paterno').focus();
            } else if (result.isDismissed) {
                buscarDNI(); // Reintentar
            }
        });
    } finally {
        // Restaurar botón
        btnBuscar.disabled = false;
        btnBuscar.innerHTML = '<i class="ti ti-search"></i> Buscar';
    }
}

// Permitir buscar con Enter en el campo de documento
document.getElementById('numero_documento').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        buscarDNI();
    }
});

// Validar que solo se ingresen números en DNI y RUC
document.getElementById('numero_documento').addEventListener('input', function(e) {
    const tipoDocumento = document.getElementById('tipo_documento').value;
    if (tipoDocumento === 'DNI') {
        this.value = this.value.replace(/\D/g, '').substring(0, 8);
    } else if (tipoDocumento === 'RUC') {
        this.value = this.value.replace(/\D/g, '').substring(0, 11);
    }
});

// Función para mostrar/ocultar campos según tipo de documento
function toggleCamposPorTipoDocumento() {
    const tipoDocumento = document.getElementById('tipo_documento').value;
    const campoRazonSocial = document.getElementById('campo_razon_social');
    const camposPersonaNatural = document.getElementById('campos_persona_natural');

    const inputRazonSocial = document.getElementById('razon_social');
    const inputApellidoPaterno = document.getElementById('apellido_paterno');
    const inputNombres = document.getElementById('nombres');

    if (tipoDocumento === 'RUC') {
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

// Función para limpiar todos los campos del formulario
function limpiarTodosLosCampos() {
    // Campos de identificación
    document.getElementById('numero_documento').value = '';

    // Campos de persona natural
    document.getElementById('apellido_paterno').value = '';
    document.getElementById('apellido_materno').value = '';
    document.getElementById('nombres').value = '';

    // Campo de razón social
    document.getElementById('razon_social').value = '';

    // Campos personales
    document.getElementById('fecha_nacimiento').value = '';

    // Campos de contacto
    document.getElementById('email').value = '';
    document.getElementById('telefono').value = '';
    document.getElementById('celular').value = '';

    // Campos de dirección
    document.getElementById('direccion').value = '';
    document.getElementById('urbanizacion').value = '';
    document.getElementById('distrito').value = '';
    document.getElementById('provincia').value = '';
    document.getElementById('departamento').value = '';
}

// Ejecutar al cargar la página
toggleCamposPorTipoDocumento();

// Ejecutar al cambiar el tipo de documento
document.getElementById('tipo_documento').addEventListener('change', function() {
    // Limpiar todos los campos cuando cambia el tipo de documento
    limpiarTodosLosCampos();
    // Mostrar/ocultar campos según el tipo
    toggleCamposPorTipoDocumento();
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
