# Guía de Implementación - DataTables y AJAX

Esta guía documenta cómo implementar DataTables con búsqueda, paginación y operaciones AJAX en las tablas del sistema.

## 1. DataTables Ya Configurado

DataTables está configurado globalmente en `app/views/layouts/footer.php` con:
- ✅ Traducción al español automática
- ✅ Responsive
- ✅ Paginación (10, 25, 50, Todos)
- ✅ Búsqueda integrada
- ✅ Estilos de Tabler aplicados
- ✅ Función helper `eliminarConAjax()` para operaciones AJAX

## 2. Implementar DataTables en una Tabla

### En la vista PHP:

```html
<!-- Agregar ID a la tabla -->
<table class="table table-vcenter" id="tablaPersonas">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Nombre Completo</th>
            <th>Email</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($personas as $p): ?>
            <tr id="fila-<?php echo $p['id']; ?>">
                <td><?php echo $p['numero_documento']; ?></td>
                <td><?php echo $p['nombres']; ?></td>
                <td><?php echo $p['email']; ?></td>
                <td>
                    <button class="btn btn-sm btn-danger btn-delete-ajax"
                            data-id="<?php echo $p['id']; ?>"
                            data-url="<?php echo APP_URL; ?>/personas/deleteAjax?id=<?php echo $p['id']; ?>"
                            data-nombre="<?php echo htmlspecialchars($p['nombres']); ?>">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$(document).ready(function() {
    // Inicializar DataTable
    var table = $('#tablaPersonas').DataTable({
        order: [[0, 'desc']], // Ordenar por primera columna descendente
        columnDefs: [
            { orderable: false, targets: -1 } // Desactivar ordenamiento en columna de acciones
        ]
    });

    // Eliminar con AJAX
    $('#tablaPersonas').on('click', '.btn-delete-ajax', function() {
        const btn = $(this);
        const id = btn.data('id');
        const url = btn.data('url');
        const nombre = btn.data('nombre');

        Swal.fire({
            title: '¿Eliminar persona?',
            html: `¿Está seguro de eliminar a <strong>${nombre}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d63939',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Eliminar fila de DataTable sin recargar página
                            table.row($('#fila-' + id)).remove().draw();

                            Toast.fire({
                                icon: 'success',
                                title: response.message || 'Eliminado correctamente'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonColor: '#206bc4'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo eliminar el registro',
                            confirmButtonColor: '#206bc4'
                        });
                    }
                });
            }
        });
    });
});
</script>
```

## 3. Endpoint AJAX en el Controlador

### Agregar método deleteAjax en PersonController.php:

```php
// Eliminar persona via AJAX
public function deleteAjax() {
    $this->auth->requireAuth();

    // Configurar respuesta JSON
    header('Content-Type: application/json');

    $id = $_GET['id'] ?? null;

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'ID no especificado']);
        exit();
    }

    try {
        $personModel = new Person($this->db);

        if ($personModel->delete($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Persona eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo eliminar la persona'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
    exit();
}
```

## 4. Configuración Opcional de DataTables

### Personalizar por tabla:

```javascript
$('#miTabla').DataTable({
    pageLength: 25,  // Mostrar 25 registros por defecto
    order: [[1, 'asc']],  // Ordenar por columna 2, ascendente
    columnDefs: [
        { orderable: false, targets: [0, -1] },  // Desactivar orden en primera y última
        { searchable: false, targets: [3] }  // Excluir de búsqueda
    ],
    language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "No hay registros",
        zeroRecords: "No se encontraron coincidencias",
        paginate: {
            first: "Primero",
            last: "Último",
            next: "Siguiente",
            previous: "Anterior"
        }
    }
});
```

## 5. Tablas a Implementar

### Prioridad Alta:
1. ✅ **Personas** (`app/views/personas/index.php`)
   - Buscador por documento, nombre, email
   - Eliminar AJAX

2. ✅ **Agremiados** (`app/views/agremiados/index.php`)
   - Buscador por número colegiatura, nombre
   - Eliminar AJAX

3. ✅ **Configuración de Montos** (`app/views/configuracion-montos/index.php`)
   - Eliminar AJAX (ya implementado con SweetAlert)

### Prioridad Media:
4. **Aportaciones** (`app/views/aportaciones/index.php`)
   - Búsqueda por período, estado
   - Nota: Ya tiene checkboxes para pago múltiple

## 6. Ventajas Implementadas

✅ **Búsqueda instantánea** - Busca en todas las columnas
✅ **Paginación automática** - 10, 25, 50 o Todos los registros
✅ **Ordenamiento** - Click en encabezados para ordenar
✅ **Responsive** - Se adapta a móviles
✅ **En español** - Traducción automática
✅ **Sin recargar** - AJAX para eliminar
✅ **SweetAlert integrado** - Confirmaciones elegantes
✅ **Toast notifications** - Feedback visual

## 7. Notas Importantes

- DataTables carga automáticamente en todas las páginas
- jQuery 3.7.1 está disponible globalmente
- La función `eliminarConAjax(url, mensaje, callback)` está disponible
- Los estilos de Tabler se aplican automáticamente
- Compatible con responsive tables de Tabler

## 8. Troubleshooting

**Tabla no se inicializa:**
- Verificar que el ID sea único
- Comprobar que jQuery esté cargado antes del script
- Revisar consola de JavaScript

**Búsqueda no funciona:**
- Asegurarse que las columnas tengan contenido
- Verificar que `searchable: true` en columnDefs

**AJAX falla:**
- Verificar URL del endpoint
- Comprobar que el método devuelva JSON válido
- Revisar Network tab en DevTools
