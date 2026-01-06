<?php
$pageTitle = 'Gestión de Agremiados';
require_once APP_PATH . '/views/layouts/header.php';
?>
<div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col"><h2 class="page-title">Gestión de Agremiados</h2></div>
                <div class="col-auto">
                    <a href="<?php echo APP_URL; ?>/personas" class="btn btn-primary">
                        <i class="ti ti-user-plus"></i> Afiliar Persona
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="table-responsive">
                    <table id="tablaAgremiados" class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Nº Colegiatura</th>
                                <th>Documento</th>
                                <th>Apellidos y Nombres</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agremiados as $a): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($a['numero_colegiatura']); ?></strong></td>
                                <td><?php echo htmlspecialchars($a['numero_documento']); ?></td>
                                <td><?php echo htmlspecialchars($a['apellido_paterno'] . ' ' . $a['apellido_materno'] . ', ' . $a['nombres']); ?></td>
                                <td><?php echo htmlspecialchars($a['especialidad']); ?></td>
                                <td>
                                    <?php
                                    $badgeClass = ['Activo' => 'success', 'Suspendido' => 'warning', 'Inhabilitado' => 'danger'][$a['estado']] ?? 'secondary';
                                    ?>
                                    <span class="badge bg-<?php echo $badgeClass; ?>"><?php echo $a['estado']; ?></span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="<?php echo APP_URL; ?>/agremiados/view?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-primary" title="Ver">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/agremiados/edit?id=<?php echo $a['id']; ?>" class="btn btn-sm btn-info" title="Editar">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger btn-delete-agremiado"
                                                data-id="<?php echo $a['id']; ?>"
                                                data-numero="<?php echo htmlspecialchars($a['numero_colegiatura']); ?>"
                                                data-nombre="<?php echo htmlspecialchars($a['apellido_paterno'] . ' ' . $a['apellido_materno'] . ', ' . $a['nombres']); ?>"
                                                title="Eliminar">
                                            <i class="ti ti-trash"></i>
                                        </button>
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
    const tabla = $('#tablaAgremiados').DataTable({
        order: [[0, 'asc']], // Ordenar por número de colegiatura
        columnDefs: [
            { orderable: false, targets: -1 } // Desactivar ordenamiento en columna de acciones
        ]
    });

    // Eliminar agremiado con AJAX
    $(document).on('click', '.btn-delete-agremiado', function() {
        const id = $(this).data('id');
        const numero = $(this).data('numero');
        const nombre = $(this).data('nombre');
        const btn = $(this);

        Swal.fire({
            title: '¿Eliminar agremiado?',
            html: `¿Está seguro de eliminar al agremiado <strong>${numero}</strong>?<br>
                   <strong>${nombre}</strong><br><br>
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
                    url: '<?php echo APP_URL; ?>/agremiados/deleteAjax',
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
                                title: response.message || 'Agremiado eliminado correctamente'
                            });
                        } else {
                            // Mostrar error
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'No se pudo eliminar el agremiado',
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
