        </div>
    </div>

    <!-- JS de Tabler -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
    </script>
</body>
</html>
