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
                                        <small class="form-hint">Para DNI: ingrese 8 dígitos y presione Buscar</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Apellido Paterno *</label>
                                        <input type="text"
                                               name="apellido_paterno"
                                               id="apellido_paterno"
                                               class="form-control"
                                               required>
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
                                           class="form-control"
                                           required>
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
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Distrito</label>
                                        <input type="text"
                                               name="distrito"
                                               id="distrito"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Provincia</label>
                                        <input type="text"
                                               name="provincia"
                                               id="provincia"
                                               class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-3">
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

    // Validar que sea DNI
    if (tipoDocumento !== 'DNI') {
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: 'La búsqueda automática solo está disponible para DNI peruano',
            confirmButtonText: 'Entendido'
        });
        return;
    }

    // Validar que tenga 8 dígitos
    if (numeroDocumento.length !== 8 || !/^\d+$/.test(numeroDocumento)) {
        Swal.fire({
            icon: 'warning',
            title: 'DNI Inválido',
            text: 'El DNI debe tener exactamente 8 dígitos',
            confirmButtonText: 'OK'
        });
        return;
    }

    // Mostrar loading
    btnBuscar.disabled = true;
    btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Buscando...';

    try {
        const response = await fetch(`https://api.apis.net.pe/v1/dni?numero=${numeroDocumento}`);

        if (!response.ok) {
            throw new Error('No se pudo conectar con el servicio');
        }

        const data = await response.json();

        // Verificar si se encontraron datos
        if (data && data.numeroDocumento) {
            // Rellenar los campos
            document.getElementById('apellido_paterno').value = data.apellidoPaterno || '';
            document.getElementById('apellido_materno').value = data.apellidoMaterno || '';
            document.getElementById('nombres').value = data.nombres || '';

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
            Swal.fire({
                icon: 'success',
                title: '¡Encontrado!',
                html: `
                    <div class="text-start">
                        <p><strong>Nombre:</strong> ${data.nombres}</p>
                        <p><strong>Apellidos:</strong> ${data.apellidoPaterno} ${data.apellidoMaterno}</p>
                        <p class="text-muted mb-0">Los datos han sido rellenados automáticamente</p>
                    </div>
                `,
                confirmButtonText: 'Continuar'
            });
        } else {
            // No se encontró
            Swal.fire({
                icon: 'warning',
                title: 'DNI No Encontrado',
                html: `
                    <p>No se encontraron datos para el DNI <strong>${numeroDocumento}</strong></p>
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
        console.error('Error al buscar DNI:', error);

        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            html: `
                <p>No se pudo conectar con el servicio de consulta de DNI</p>
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

// Validar que solo se ingresen números en DNI
document.getElementById('numero_documento').addEventListener('input', function(e) {
    const tipoDocumento = document.getElementById('tipo_documento').value;
    if (tipoDocumento === 'DNI') {
        this.value = this.value.replace(/\D/g, '').substring(0, 8);
    }
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
