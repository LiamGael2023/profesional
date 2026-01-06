        </div>
    </div>

    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- JS de Tabler -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Script global para alertas -->
    <script>
        // Configuración global de SweetAlert2
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Función helper para confirmaciones
        function confirmarAccion(mensaje, titulo = '¿Está seguro?', textoConfirmar = 'Sí, continuar', textoCancelar = 'Cancelar') {
            return Swal.fire({
                title: titulo,
                html: mensaje,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#206bc4',
                cancelButtonColor: '#d63939',
                confirmButtonText: textoConfirmar,
                cancelButtonText: textoCancelar,
                reverseButtons: true
            });
        }

        // Mostrar mensajes de sesión automáticamente
        <?php if (isset($_SESSION['success'])): ?>
            Toast.fire({
                icon: 'success',
                title: '<?php echo addslashes($_SESSION['success']); ?>'
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo addslashes($_SESSION['error']); ?>',
                confirmButtonColor: '#206bc4'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['info'])): ?>
            Toast.fire({
                icon: 'info',
                title: '<?php echo addslashes($_SESSION['info']); ?>'
            });
            <?php unset($_SESSION['info']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['warning'])): ?>
            Toast.fire({
                icon: 'warning',
                title: '<?php echo addslashes($_SESSION['warning']); ?>'
            });
            <?php unset($_SESSION['warning']); ?>
        <?php endif; ?>

        // Configuración global de DataTables en español
        $.extend($.fn.dataTable.defaults, {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            responsive: true,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function() {
                // Aplicar estilos de Tabler al buscador
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        });

        // Función helper para eliminar con AJAX
        function eliminarConAjax(url, mensaje, onSuccess) {
            return $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: { _method: 'DELETE' }
            }).done(function(response) {
                if (response.success) {
                    Toast.fire({
                        icon: 'success',
                        title: response.message || 'Eliminado correctamente'
                    });
                    if (onSuccess) onSuccess(response);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'No se pudo eliminar el registro',
                        confirmButtonColor: '#206bc4'
                    });
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al procesar la solicitud',
                    confirmButtonColor: '#206bc4'
                });
            });
        }
    </script>
</body>
</html>
