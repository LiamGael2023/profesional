<?php
$pageTitle = 'Gestión de Personas';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Gestión de Personas</h2>
                </div>
                <div class="col-auto">
                    <a href="<?php echo APP_URL; ?>/personas/create" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Nueva Persona
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <!-- Listado -->
            <div class="card">
                <div class="table-responsive">
                    <table id="tablaPersonas" class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Apellidos y Nombres</th>
                                <th>Email</th>
                                <th>Celular</th>
                                <th>Estado</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personas as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p['numero_documento']); ?></td>
                                <td>
                                    <?php
                                    // Si es RUC, mostrar solo razón social (nombres), si es persona mostrar apellidos y nombres
                                    if ($p['tipo_documento'] === 'RUC') {
                                        echo htmlspecialchars($p['nombres']);
                                    } else {
                                        echo htmlspecialchars($p['apellido_paterno'] . ' ' . $p['apellido_materno'] . ', ' . $p['nombres']);
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($p['email']); ?></td>
                                <td><?php echo htmlspecialchars($p['celular']); ?></td>
                                <td><span class="badge bg-success">Activo</span></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="<?php echo APP_URL; ?>/personas/view?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-primary" title="Ver"><i class="ti ti-eye"></i></a>
                                        <a href="<?php echo APP_URL; ?>/personas/edit?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info" title="Editar"><i class="ti ti-edit"></i></a>
                                        <?php if (!in_array($p['id'], $personasAgremiadas)): ?>
                                            <button type="button" class="btn btn-sm btn-danger btn-delete-persona"
                                                    data-id="<?php echo $p['id']; ?>"
                                                    data-nombre="<?php echo htmlspecialchars($p['tipo_documento'] === 'RUC' ? $p['nombres'] : $p['apellido_paterno'] . ' ' . $p['apellido_materno'] . ', ' . $p['nombres']); ?>"
                                                    title="Eliminar">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                            <a href="<?php echo APP_URL; ?>/agremiados/create?persona_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success" title="Afiliar"><i class="ti ti-user-plus"></i> Afiliar</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DataTable
    const tabla = $('#tablaPersonas').DataTable({
        order: [[1, 'asc']], // Ordenar por apellidos y nombres
        columnDefs: [
            { orderable: false, targets: -1 } // Desactivar ordenamiento en columna de acciones
        ]
    });

    // Eliminar persona con AJAX
    $(document).on('click', '.btn-delete-persona', function() {
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const btn = $(this);

        Swal.fire({
            title: '¿Eliminar persona?',
            html: `¿Está seguro de eliminar a <strong>${nombre}</strong>?<br><br>
                   <small class="text-muted">Esta acción no se puede deshacer.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar petición AJAX
                $.ajax({
                    url: '<?php echo APP_URL; ?>/personas/deleteAjax',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            // Eliminar fila de DataTable
                            tabla.row(btn.closest('tr')).remove().draw();

                            // Mostrar mensaje de éxito
                            Toast.fire({
                                icon: 'success',
                                title: response.message || 'Persona eliminada correctamente'
                            });
                        } else {
                            // Mostrar error
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'No se pudo eliminar la persona',
                                icon: 'error',
                                confirmButtonColor: '#206bc4'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un error al procesar la solicitud',
                            icon: 'error',
                            confirmButtonColor: '#206bc4'
                        });
                    }
                });
            }
        });
    });
});
</script>

<?php require_once APP_PATH . '/views/layouts/footer.php'; ?>
