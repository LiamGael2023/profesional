# Sistema de Aportaciones Mensuales

## Descripción General

El sistema de aportaciones mensuales permite generar y gestionar automáticamente las cuotas mensuales de los agremiados del colegio profesional.

## Características Principales

### 1. Generación Inteligente de Fechas

El sistema determina automáticamente desde qué fecha generar las aportaciones:

- **Agremiados con Traslado/Incorporación**: Usa la `fecha_traslado`
- **Agremiados Normales**: Usa la `fecha_colegiatura`

### 2. Estrategia Híbrida de Generación

El sistema implementa una estrategia híbrida eficiente:

- Genera aportaciones desde la fecha de inicio del agremiado
- Extiende hasta **3 meses en el futuro** desde la fecha actual
- Evita crear 50 años de aportaciones innecesarias
- Permite ajustar montos en el futuro según necesidad

**Ventajas:**
- ✅ Base de datos ligera y eficiente
- ✅ Flexibilidad para cambios de monto
- ✅ No genera datos innecesarios
- ✅ Siempre tiene 3 meses de proyección

### 3. Sistema de Estados

Las aportaciones tienen los siguientes estados:

- **Pendiente**: Aportación generada, esperando pago
- **Pagado**: Aportación ya pagada
- **Vencido**: Aportación pendiente después de la fecha de vencimiento
- **Exonerado**: Agremiado exonerado de pago

### 4. Actualización Automática de Estados

El sistema actualiza automáticamente:
- Marca como "Vencido" las aportaciones pendientes que superaron su fecha de vencimiento
- Se ejecuta cada vez que se genera el listado de aportaciones
- Se ejecuta al correr el script mensual automatizado

## Uso del Sistema

### Generación Manual desde la Interfaz Web

1. Ir a **Agremiados** → Ver detalles de un agremiado
2. En el panel lateral derecho, sección **"Aportaciones"**
3. Hacer click en **"Generar Aportaciones"**
4. El sistema creará todas las aportaciones desde la fecha correspondiente hasta 3 meses en el futuro

**Nota**: El sistema detecta aportaciones existentes y NO crea duplicados.

### Ver Aportaciones de un Agremiado

1. Desde la vista del agremiado, click en **"Ver Aportaciones"**
2. Se muestra la lista completa con filtros por:
   - Año
   - Estado (Pendiente, Pagado, Vencido, Exonerado)

### Registrar un Pago

1. En la lista de aportaciones, click en **"Registrar Pago"** (solo para estado Pendiente o Vencido)
2. Completar el formulario:
   - **Fecha de Pago** (por defecto: hoy)
   - **Método de Pago**: Efectivo, Transferencia, Depósito, Tarjeta, Yape, Plin
   - **Número de Operación**: Opcional, para referencia
   - **Observaciones**: Opcional
3. Click en **"Registrar Pago"**
4. La aportación cambiará su estado a "Pagado"

## Generación Automática Mensual

Para mantener el sistema actualizado, se debe ejecutar mensualmente un proceso que:
1. Genera aportaciones para los próximos 3 meses
2. Actualiza estados de aportaciones vencidas
3. Procesa todos los agremiados activos

### Opción 1: Ejecución Manual

**Desde CMD/PowerShell:**
```cmd
cd C:\xampp\htdocs\profesional
C:\xampp\php\php.exe public\cron\generar-aportaciones-mensual.php
```

**Usando el archivo .bat:**
1. Hacer doble click en `generar-aportaciones.bat`
2. Se ejecutará el proceso y mostrará el resultado
3. Presionar cualquier tecla para cerrar

### Opción 2: Programación Automática en Windows

Seguir la guía completa en: **[WINDOWS_TASK_SCHEDULER.md](WINDOWS_TASK_SCHEDULER.md)**

**Resumen rápido:**
1. Abrir Programador de tareas (Win + R → `taskschd.msc`)
2. Crear tarea nueva con:
   - Nombre: "Generar Aportaciones Mensuales"
   - Desencadenador: Mensual, día 1, 00:00
   - Acción: Ejecutar `C:\xampp\php\php.exe` con argumento al script
3. Guardar y la tarea se ejecutará automáticamente cada mes

## Estructura de la Base de Datos

```sql
CREATE TABLE aportaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agremiado_id INT NOT NULL,
    periodo VARCHAR(7) NOT NULL,           -- Formato: YYYY-MM
    anio INT NOT NULL,                     -- Año de la aportación
    mes INT NOT NULL,                      -- Mes de la aportación (1-12)
    monto DECIMAL(10,2) DEFAULT 0.00,      -- Monto a pagar
    estado ENUM('Pendiente', 'Pagado', 'Vencido', 'Exonerado'),
    fecha_vencimiento DATE,                -- Último día del mes
    fecha_pago DATE,                       -- Fecha en que se pagó
    metodo_pago VARCHAR(50),               -- Efectivo, Transferencia, etc.
    numero_operacion VARCHAR(100),         -- Número de comprobante
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT,

    FOREIGN KEY (agremiado_id) REFERENCES agremiados(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_periodo (agremiado_id, periodo)  -- Evita duplicados
);
```

## Archivos Importantes

```
profesional/
├── app/
│   ├── models/
│   │   └── Aportacion.php                  # Modelo de aportaciones
│   ├── controllers/
│   │   └── AportacionController.php        # Controlador de aportaciones
│   └── views/
│       └── aportaciones/
│           ├── index.php                   # Listado de aportaciones
│           └── pagar.php                   # Formulario de registro de pago
├── public/
│   └── cron/
│       └── generar-aportaciones-mensual.php  # Script automático
├── logs/
│   └── .gitkeep                            # Directorio para logs
├── generar-aportaciones.bat                # Ejecutable Windows
├── WINDOWS_TASK_SCHEDULER.md               # Guía de configuración
└── SISTEMA_APORTACIONES.md                 # Este archivo
```

## Preguntas Frecuentes

### ¿Qué pasa si ejecuto la generación múltiples veces?

No hay problema. El sistema tiene una restricción `UNIQUE` en la base de datos que previene la creación de aportaciones duplicadas para el mismo período.

### ¿Puedo cambiar el monto de las aportaciones?

Sí. Las aportaciones se generan con monto `0.00` por defecto. Puedes:
1. Actualizar manualmente los montos en la base de datos
2. Modificar el script para usar un monto específico
3. Crear una interfaz para configurar montos por período

### ¿Por qué solo 3 meses en el futuro?

Generar solo 3 meses permite:
- Mantener la base de datos ligera
- Flexibilidad para ajustar montos futuros
- Evitar datos innecesarios de períodos muy lejanos
- El script mensual extiende automáticamente la proyección

### ¿Qué pasa con agremiados que se dan de baja?

El sistema solo genera aportaciones para agremiados con estado "Activo". Si un agremiado cambia a estado "Retirado", "Inhabilitado" o "Suspendido", no se generarán nuevas aportaciones en las siguientes ejecuciones.

### ¿Cómo afectan los Traslados/Incorporaciones?

Los agremiados con tipo de incorporación "Traslado" o "Incorporación" y que tienen una `fecha_traslado` definida, generarán aportaciones desde esa fecha en lugar de la `fecha_colegiatura`. Esto evita cobrar por períodos anteriores al traslado.

## Mejoras Futuras

Posibles mejoras al sistema:

- [ ] Configuración de montos por año/período
- [ ] Reportes de ingresos por período
- [ ] Exportación a Excel de aportaciones
- [ ] Recordatorios automáticos por email
- [ ] Dashboard con estadísticas de pagos
- [ ] Recibos de pago en PDF
- [ ] Integración con pasarelas de pago online

## Soporte

Para más información sobre la configuración y uso del sistema, revisar:
- [WINDOWS_TASK_SCHEDULER.md](WINDOWS_TASK_SCHEDULER.md) - Configuración del programador de tareas
- Código fuente en `app/models/Aportacion.php` - Lógica de generación
- Código fuente en `app/controllers/AportacionController.php` - Controlador
