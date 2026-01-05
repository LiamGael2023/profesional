# Configurar Tarea Programada en Windows para Aportaciones

Este documento explica cómo configurar una tarea programada en Windows para generar automáticamente las aportaciones mensuales de todos los agremiados activos.

## Requisitos Previos

- XAMPP o WAMP instalado
- PHP funcionando en el servidor local
- Base de datos configurada y funcionando

## Pasos para Configurar la Tarea Programada

### 1. Abrir el Programador de Tareas (Task Scheduler)

**Opción A - Mediante Ejecutar:**
1. Presionar `Win + R` en el teclado
2. Escribir: `taskschd.msc`
3. Presionar `Enter`

**Opción B - Mediante Búsqueda:**
1. Presionar el botón de Windows
2. Buscar "Programador de tareas" o "Task Scheduler"
3. Hacer click en la aplicación

### 2. Crear una Nueva Tarea

1. En la ventana del Programador de tareas, hacer click derecho en **"Biblioteca del Programador de tareas"**
2. Seleccionar **"Crear tarea..."** (no "Crear tarea básica")

### 3. Configuración de la Pestaña "General"

Configurar los siguientes campos:

- **Nombre:** `Generar Aportaciones Mensuales`
- **Descripción:** `Genera automáticamente las aportaciones de los próximos 3 meses para todos los agremiados activos del sistema`
- **Opciones de seguridad:**
  - ☑ Ejecutar solo cuando el usuario haya iniciado sesión
  - ☑ Ejecutar con los privilegios más altos
- **Configurar para:** Windows 10 / Windows 11 (según tu sistema)

### 4. Configuración de la Pestaña "Desencadenadores"

1. Hacer click en el botón **"Nuevo..."**
2. Configurar:
   - **Iniciar la tarea:** Según una programación
   - **Configuración:**
     - Seleccionar: ☑ **Mensual**
     - **Día:** 1 (primer día del mes)
     - **Meses:** ☑ Todos los meses (seleccionar todos)
   - **Hora:** `00:00:00` (medianoche)
   - **Configuración avanzada:**
     - ☑ Habilitado
3. Hacer click en **"Aceptar"**

### 5. Configuración de la Pestaña "Acciones"

1. Hacer click en el botón **"Nuevo..."**
2. Configurar:
   - **Acción:** Iniciar un programa
   - **Programa o script:** `C:\xampp\php\php.exe`
     - *(Ajustar la ruta si XAMPP está instalado en otra ubicación)*
     - *(Para WAMP usar: `C:\wamp64\bin\php\phpX.X.XX\php.exe`)*
   - **Agregar argumentos (opcional):**
     ```
     C:\xampp\htdocs\profesional\public\cron\generar-aportaciones-mensual.php
     ```
     - *(Ajustar la ruta según la ubicación de tu proyecto)*
   - **Iniciar en (opcional):** `C:\xampp\htdocs\profesional`
3. Hacer click en **"Aceptar"**

### 6. Configuración de la Pestaña "Condiciones"

Configurar según tus necesidades:
- ☐ Iniciar la tarea solo si el equipo está conectado a la corriente alterna
- ☐ Iniciar la tarea solo si la siguiente conexión de red está disponible

### 7. Configuración de la Pestaña "Configuración"

Recomendaciones:
- ☑ Permitir que la tarea se ejecute a petición
- ☑ Ejecutar la tarea lo antes posible después de un inicio programado perdido
- ☑ Si la tarea no se ejecuta, volver a intentar cada: `10 minutos`
- ☑ Intentar volver a ejecutar hasta: `3 veces`
- ☑ Detener la tarea si se ejecuta más de: `1 hora`

### 8. Finalizar

1. Hacer click en **"Aceptar"** para guardar la tarea
2. Si se solicita, ingresar la contraseña de tu usuario de Windows
3. La tarea quedará programada y se ejecutará el primer día de cada mes a medianoche

---

## Ejecución Manual de la Tarea

Para probar la tarea o ejecutarla manualmente antes de esperar la programación:

### Opción 1: Desde el Programador de Tareas

1. En el Programador de tareas, buscar la tarea **"Generar Aportaciones Mensuales"**
2. Hacer click derecho sobre la tarea
3. Seleccionar **"Ejecutar"**
4. Verificar en el sistema que se crearon las aportaciones

### Opción 2: Desde la Línea de Comandos

Abrir **CMD** o **PowerShell** como Administrador y ejecutar:

```cmd
cd C:\xampp\htdocs\profesional
C:\xampp\php\php.exe public\cron\generar-aportaciones-mensual.php
```

*(Ajustar las rutas según tu configuración)*

---

## Verificar el Historial de Ejecución

1. En el Programador de tareas, seleccionar la tarea **"Generar Aportaciones Mensuales"**
2. En el panel inferior, hacer click en la pestaña **"Historial"**
3. Revisar las ejecuciones anteriores y verificar que se completaron exitosamente

---

## Solución de Problemas

### La tarea no se ejecuta

**Verificar:**
1. Que el servicio "Programador de tareas" esté ejecutándose
2. Que las rutas a PHP y al script sean correctas
3. Que el usuario tenga permisos para ejecutar PHP
4. Revisar el historial de la tarea para ver mensajes de error

### Error al ejecutar PHP

**Verificar:**
1. Que XAMPP esté iniciado (Apache y MySQL)
2. Que la base de datos esté accesible
3. Ejecutar el script manualmente desde CMD para ver errores:
   ```cmd
   C:\xampp\php\php.exe C:\xampp\htdocs\profesional\public\cron\generar-aportaciones-mensual.php
   ```

### El script no encuentra la base de datos

**Verificar:**
1. Que MySQL esté ejecutándose en el puerto correcto (3307)
2. Que el archivo `config.php` tenga la configuración correcta
3. Que el usuario de la base de datos tenga permisos

---

## Registro de Actividad (Log)

El script muestra información detallada al ejecutarse. Para guardar esta información en un archivo de log:

### Modificar la acción de la tarea:

En la pestaña **"Acciones"**, editar y cambiar los argumentos a:

```cmd
C:\xampp\htdocs\profesional\public\cron\generar-aportaciones-mensual.php > C:\xampp\htdocs\profesional\logs\aportaciones.log 2>&1
```

Esto guardará la salida del script en un archivo `aportaciones.log` dentro de la carpeta `logs`.

**Nota:** Crear la carpeta `logs` si no existe:
```cmd
mkdir C:\xampp\htdocs\profesional\logs
```

---

## Notas Importantes

1. **Asegurarse de que XAMPP se inicie automáticamente con Windows** si se desea que la tarea funcione sin intervención manual
2. **El script genera aportaciones hasta 3 meses en el futuro**, por lo que ejecutarlo mensualmente es suficiente
3. **No genera aportaciones duplicadas**, el sistema detecta períodos ya existentes
4. **Solo procesa agremiados con estado "Activo"**
5. **Actualiza automáticamente el estado de aportaciones vencidas** antes de generar nuevas

---

## Alternativa: Archivo BAT para Ejecución Manual

Si prefieres ejecutar el proceso manualmente, puedes crear un archivo `.bat`:

### Crear archivo `generar-aportaciones.bat`:

```batch
@echo off
echo ========================================
echo Generacion de Aportaciones Mensuales
echo ========================================
echo.

cd C:\xampp\htdocs\profesional
C:\xampp\php\php.exe public\cron\generar-aportaciones-mensual.php

echo.
echo Presiona cualquier tecla para salir...
pause > nul
```

Guardar este archivo en `C:\xampp\htdocs\profesional\` y ejecutarlo con doble click cuando se necesite.

---

## Soporte

Para más información sobre el Programador de tareas de Windows, consultar:
- [Documentación oficial de Microsoft](https://docs.microsoft.com/es-es/windows/win32/taskschd/task-scheduler-start-page)
