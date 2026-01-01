# Sistema de Login PHP MVC con Tabler.io

Sistema de autenticación completo desarrollado en PHP usando el patrón MVC (Modelo-Vista-Controlador) y la interfaz de usuario de Tabler.io.

## Características

✨ **Diseño moderno con Tabler.io**
- Interfaz de login responsiva (40% formulario, 60% imagen)
- Dashboard con header personalizado
- Perfil de usuario en la cabecera
- Menús de navegación interactivos

🔒 **Seguridad**
- Contraseñas hasheadas con `password_hash()`
- Protección contra SQL injection (PDO con prepared statements)
- Validación de sesiones con timeout
- Opción "Recordarme" con cookies seguras

🎯 **Arquitectura MVC**
- Separación clara de responsabilidades
- Código organizado y mantenible
- Fácil de extender y personalizar

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Apache con mod_rewrite habilitado
- Extensión PDO de PHP

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/profesional.git
cd profesional
```

### 2. Configurar la base de datos

Importa el archivo `database.sql` en tu servidor MySQL:

```bash
mysql -u root -p < database.sql
```

O desde phpMyAdmin, importa el archivo `database.sql`.

### 3. Configurar la aplicación

Edita el archivo `app/config/config.php` con tus credenciales de base de datos:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('DB_NAME', 'mvc_login');
define('APP_URL', 'http://localhost/profesional');
```

### 4. Configurar Apache

Asegúrate de que el módulo `mod_rewrite` esté habilitado:

```bash
sudo a2enmod rewrite
sudo service apache2 restart
```

Configura el `DocumentRoot` de Apache para que apunte al directorio raíz del proyecto (no a `/public`).

### 5. Permisos

Si estás en Linux, asegúrate de que los permisos sean correctos:

```bash
sudo chmod -R 755 /var/www/html/profesional
sudo chown -R www-data:www-data /var/www/html/profesional
```

## Estructura del Proyecto

```
profesional/
├── app/
│   ├── config/
│   │   ├── config.php          # Configuración general
│   │   └── Database.php        # Clase de conexión a BD
│   ├── controllers/
│   │   ├── AuthController.php  # Controlador de autenticación
│   │   └── DashboardController.php
│   ├── models/
│   │   └── User.php            # Modelo de usuario
│   └── views/
│       ├── auth/
│       │   └── login.php       # Vista de login
│       ├── dashboard/
│       │   └── index.php       # Vista de dashboard
│       └── layouts/
│           ├── header.php      # Header común
│           └── footer.php      # Footer común
├── public/
│   ├── css/                    # CSS personalizado
│   ├── js/                     # JavaScript personalizado
│   ├── images/                 # Imágenes
│   ├── .htaccess              # Configuración de URLs
│   └── index.php              # Punto de entrada
├── .htaccess                   # Redirección a /public
├── database.sql               # Script de base de datos
└── README.md
```

## Uso

### Acceder al sistema

1. Abre tu navegador y ve a: `http://localhost/profesional`
2. Serás redirigido automáticamente a la página de login

### Credenciales de prueba

**Usuario:** admin
**Contraseña:** admin123

**Usuario:** usuario1
**Contraseña:** admin123

### Rutas disponibles

- `/login` - Página de inicio de sesión
- `/dashboard` - Panel de control (requiere autenticación)
- `/logout` - Cerrar sesión

## Personalización

### Cambiar la imagen de login

Edita el archivo `app/views/auth/login.php` en la línea que contiene:

```html
<img src="https://images.unsplash.com/photo-..." alt="Login Background">
```

Reemplaza la URL con tu imagen preferida.

### Modificar colores y estilos

Tabler.io utiliza variables CSS que puedes personalizar. Crea un archivo CSS en `public/css/custom.css` y sobrescribe los estilos.

### Agregar nuevas rutas

Edita `public/index.php` y agrega nuevos casos en el switch del router:

```php
case 'mi-nueva-ruta':
    $miController = new MiController();
    $miController->index();
    break;
```

## Seguridad

### Cambiar el timeout de sesión

Edita `app/config/config.php`:

```php
define('SESSION_TIMEOUT', 3600); // En segundos (3600 = 1 hora)
```

### Regenerar hashes de contraseña

Para crear un nuevo hash de contraseña:

```php
echo password_hash('tu_contraseña', PASSWORD_DEFAULT);
```

## Tecnologías utilizadas

- **PHP** - Lenguaje de programación del lado del servidor
- **MySQL** - Sistema de gestión de bases de datos
- **Tabler.io** - Framework de UI basado en Bootstrap 5
- **PDO** - Extensión de PHP para acceso a bases de datos
- **Apache** - Servidor web

## Licencia

Este proyecto es de código abierto y está disponible bajo la licencia MIT.

## Soporte

Si encuentras algún problema o tienes preguntas, por favor abre un issue en GitHub.

---

Desarrollado con ❤️ usando PHP MVC y Tabler.io
