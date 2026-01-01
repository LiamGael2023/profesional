<?php
// Script de verificación y diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Verificación del Sistema</h1>";
echo "<hr>";

// 1. Verificar configuración
echo "<h2>1. Configuración</h2>";
require_once dirname(__DIR__) . '/app/config/config.php';
echo "✓ DB_HOST: " . DB_HOST . "<br>";
echo "✓ DB_PORT: " . DB_PORT . "<br>";
echo "✓ DB_USER: " . DB_USER . "<br>";
echo "✓ DB_NAME: " . DB_NAME . "<br>";
echo "✓ APP_URL: " . APP_URL . "<br>";
echo "<hr>";

// 2. Verificar conexión a base de datos
echo "<h2>2. Conexión a Base de Datos</h2>";
try {
    require_once dirname(__DIR__) . '/app/config/Database.php';
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        echo "✓ <span style='color: green;'>Conexión exitosa a MySQL</span><br>";
    } else {
        echo "✗ <span style='color: red;'>Error: No se pudo conectar a MySQL</span><br>";
        die();
    }
} catch (Exception $e) {
    echo "✗ <span style='color: red;'>Error de conexión: " . $e->getMessage() . "</span><br>";
    die();
}
echo "<hr>";

// 3. Verificar si la base de datos existe
echo "<h2>3. Verificación de Base de Datos</h2>";
try {
    $stmt = $conn->query("SELECT DATABASE()");
    $dbname = $stmt->fetchColumn();
    echo "✓ Base de datos actual: <strong>" . $dbname . "</strong><br>";
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 4. Verificar si la tabla users existe
echo "<h2>4. Verificación de Tabla 'users'</h2>";
try {
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✓ <span style='color: green;'>La tabla 'users' existe</span><br>";

        // Contar usuarios
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✓ Total de usuarios en la tabla: <strong>" . $result['total'] . "</strong><br>";
    } else {
        echo "✗ <span style='color: red;'>La tabla 'users' NO existe</span><br>";
        echo "<p style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'>";
        echo "<strong>Solución:</strong> Necesitas importar el archivo database.sql<br>";
        echo "Usa phpMyAdmin o ejecuta: <code>mysql -u root -p -P 3307 mvc_login < database.sql</code>";
        echo "</p>";
        die();
    }
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 5. Listar usuarios
echo "<h2>5. Usuarios en la Base de Datos</h2>";
try {
    $stmt = $conn->query("SELECT id, username, email, full_name, is_active, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Username</th><th>Email</th><th>Nombre Completo</th><th>Activo</th><th>Creado</th>";
        echo "</tr>";

        foreach ($users as $user) {
            $activeColor = $user['is_active'] ? 'green' : 'red';
            $activeText = $user['is_active'] ? 'Sí' : 'No';
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td><strong>{$user['username']}</strong></td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['full_name']}</td>";
            echo "<td style='color: {$activeColor};'>{$activeText}</td>";
            echo "<td>{$user['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "✗ <span style='color: red;'>No hay usuarios en la base de datos</span><br>";
        echo "<p style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'>";
        echo "<strong>Solución:</strong> Ejecuta el siguiente SQL para crear usuarios de prueba:";
        echo "</p>";
    }
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 6. Probar hash de contraseña
echo "<h2>6. Prueba de Hash de Contraseña</h2>";
$testPassword = 'admin123';
$newHash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "Contraseña de prueba: <strong>{$testPassword}</strong><br>";
echo "Nuevo hash generado: <code style='font-size: 10px;'>{$newHash}</code><br>";

// Verificar hash actual en BD
try {
    $stmt = $conn->query("SELECT username, password FROM users WHERE username = 'admin'");
    $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminUser) {
        echo "<br>Hash actual del usuario 'admin' en BD: <br><code style='font-size: 10px;'>{$adminUser['password']}</code><br>";

        $verify = password_verify($testPassword, $adminUser['password']);
        if ($verify) {
            echo "<br>✓ <span style='color: green; font-weight: bold;'>El hash es correcto - La contraseña 'admin123' debería funcionar</span><br>";
        } else {
            echo "<br>✗ <span style='color: red; font-weight: bold;'>El hash NO coincide - La contraseña en la BD es incorrecta</span><br>";
            echo "<p style='background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545;'>";
            echo "<strong>Solución:</strong> Ejecuta este SQL para actualizar la contraseña:<br>";
            echo "<code>UPDATE users SET password = '{$newHash}' WHERE username = 'admin';</code>";
            echo "</p>";
        }
    } else {
        echo "<br>✗ El usuario 'admin' no existe en la base de datos<br>";
    }
} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// 7. Crear SQL para insertar usuarios
echo "<h2>7. SQL para Crear Usuarios (si no existen)</h2>";
echo "<p>Copia y ejecuta este SQL en phpMyAdmin si no tienes usuarios:</p>";
echo "<textarea style='width: 100%; height: 150px; font-family: monospace; padding: 10px;'>";
echo "-- Eliminar usuarios existentes (opcional)\n";
echo "DELETE FROM users;\n\n";
echo "-- Insertar usuarios de prueba\n";
echo "INSERT INTO users (username, email, password, full_name, is_active) VALUES\n";
echo "('admin', 'admin@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Administrador del Sistema', 1),\n";
echo "('usuario1', 'usuario1@example.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 'Usuario Demo', 1);\n";
echo "</textarea>";

echo "<hr>";
echo "<h2>8. Intentar Login</h2>";
echo "<p>Si todo lo anterior está correcto, intenta hacer login con:</p>";
echo "<ul>";
echo "<li><strong>Usuario:</strong> admin</li>";
echo "<li><strong>Contraseña:</strong> admin123</li>";
echo "</ul>";
echo "<a href='" . APP_URL . "/login' style='display: inline-block; background: #206bc4; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Ir al Login</a>";

echo "<hr>";
echo "<p style='color: #888; font-size: 12px;'>Puedes eliminar este archivo (verify.php) después de verificar que todo funciona.</p>";
?>
