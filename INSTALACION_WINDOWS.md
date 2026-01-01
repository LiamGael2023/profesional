# Guía de Instalación para Windows (XAMPP/WAMP)

## Problema: Error 404

Si obtienes un error "Not Found" en `http://localhost/profesional`, sigue estos pasos:

## Solución 1: Verificar que mod_rewrite está habilitado

### Para XAMPP:

1. Abre `C:\xampp\apache\conf\httpd.conf`
2. Busca la línea: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Elimina el `#` al inicio para descomentarla:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Guarda el archivo y reinicia Apache

### Para WAMP:

1. Click en el icono de WAMP en la bandeja del sistema
2. Apache → Apache modules → rewrite_module (asegúrate que esté marcado)

## Solución 2: Configurar AllowOverride

1. Abre `C:\xampp\apache\conf\httpd.conf` (o el equivalente en WAMP)
2. Busca la sección `<Directory "C:/xampp/htdocs">` (ajusta la ruta según tu instalación)
3. Cambia `AllowOverride None` por `AllowOverride All`:

```apache
<Directory "C:/xampp/htdocs">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
</Directory>
```

4. Guarda y reinicia Apache

## Solución 3: Ubicación del proyecto

Asegúrate de que el proyecto esté en:
- **XAMPP**: `C:\xampp\htdocs\profesional\`
- **WAMP**: `C:\wamp64\www\profesional\`

La estructura debe ser:
```
htdocs/profesional/
├── .htaccess
├── app/
├── public/
└── ...
```

## Solución 4: Probar sin .htaccess

Si aún no funciona, prueba accediendo directamente:

```
http://localhost/profesional/public/test.php
```

Si esto funciona, el problema está en el .htaccess del directorio raíz.

### Alternativa - Usar solo el directorio public:

1. Accede a: `http://localhost/profesional/public/`
2. Para hacer login: `http://localhost/profesional/public/login`
3. Para dashboard: `http://localhost/profesional/public/dashboard`

O mejor aún, configura un Virtual Host (ver siguiente sección).

## Solución 5: Configurar Virtual Host (RECOMENDADO)

### Para XAMPP:

1. Edita `C:\xampp\apache\conf\extra\httpd-vhosts.conf`

2. Agrega al final:

```apache
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/profesional/public"
    ServerName profesional.local

    <Directory "C:/xampp/htdocs/profesional/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Edita `C:\Windows\System32\drivers\etc\hosts` (como Administrador)

4. Agrega esta línea:
```
127.0.0.1    profesional.local
```

5. Reinicia Apache

6. Accede a: `http://profesional.local`

## Configuración de MySQL (Puerto 3307)

El sistema ya está configurado para usar el puerto **3307** de MySQL.

### En phpMyAdmin:

1. Importa el archivo `database.sql`:
   - Abre phpMyAdmin: `http://localhost/phpmyadmin`
   - Click en "Importar"
   - Selecciona el archivo `database.sql`
   - Click en "Continuar"

### Verificar puerto de MySQL:

En `app/config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');  // ✓ Configurado
define('DB_USER', 'root');
define('DB_PASS', '');      // Ajusta si tienes contraseña
define('DB_NAME', 'mvc_login');
```

Si tu MySQL usa el puerto 3306 (default), cambia `3307` a `3306`.

## Verificación

1. Verifica que Apache esté corriendo (icono verde en XAMPP/WAMP)
2. Verifica que MySQL esté corriendo (icono verde en XAMPP/WAMP)
3. Accede a: `http://localhost/profesional/public/test.php`
4. Si ves información de PHP, ¡está funcionando!
5. Luego accede a: `http://localhost/profesional` o `http://profesional.local` (si configuraste VirtualHost)

## Credenciales de Prueba

- **Usuario**: admin
- **Contraseña**: admin123

## Troubleshooting

### Error de conexión a la base de datos

Verifica:
1. MySQL está corriendo
2. El puerto es correcto (3307 o 3306)
3. La base de datos `mvc_login` existe
4. El usuario `root` tiene permisos

### Sesión no funciona

En `php.ini` (`C:\xampp\php\php.ini`):
```ini
session.save_path = "C:/xampp/tmp"
```

Crea el directorio `tmp` si no existe.

### Errores de ruta

En Windows, asegúrate de usar barras diagonales `/` o dobles barras invertidas `\\` en las rutas de PHP.

## Soporte

Si sigues teniendo problemas:
1. Revisa los logs de Apache: `C:\xampp\apache\logs\error.log`
2. Habilita errores de PHP en `php.ini`:
   ```ini
   display_errors = On
   error_reporting = E_ALL
   ```
