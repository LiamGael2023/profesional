<?php
/**
 * Script para corregir las contraseñas de los usuarios
 * Este script actualiza las contraseñas de los usuarios de prueba
 *
 * Ejecuta este archivo una vez y luego elimínalo por seguridad
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Corrección de Contraseñas</h1>";
echo "<hr>";

// Cargar configuración
require_once dirname(__DIR__) . '/app/config/config.php';
require_once dirname(__DIR__) . '/app/config/Database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    if (!$conn) {
        die("<p style='color: red;'>Error: No se pudo conectar a la base de datos</p>");
    }

    echo "<p style='color: green;'>✓ Conectado a la base de datos</p>";

    // Definir las contraseñas
    $password = 'admin123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    echo "<p>Contraseña que se establecerá: <strong>admin123</strong></p>";
    echo "<p>Hash generado: <code style='font-size: 10px;'>{$hashedPassword}</code></p>";
    echo "<hr>";

    // Actualizar usuario admin
    echo "<h3>Actualizando usuario 'admin'...</h3>";
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE username = 'admin'");
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            echo "<p style='color: green;'>✓ Usuario 'admin' actualizado exitosamente</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Usuario 'admin' no encontrado</p>";
        }
    }

    // Actualizar usuario usuario1
    echo "<h3>Actualizando usuario 'usuario1'...</h3>";
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE username = 'usuario1'");
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        $rows = $stmt->rowCount();
        if ($rows > 0) {
            echo "<p style='color: green;'>✓ Usuario 'usuario1' actualizado exitosamente</p>";
        } else {
            echo "<p style='color: orange;'>⚠ Usuario 'usuario1' no encontrado</p>";
        }
    }

    echo "<hr>";
    echo "<h3>Verificación</h3>";

    // Verificar que los hashes funcionan
    $stmt = $conn->query("SELECT username, password FROM users WHERE username IN ('admin', 'usuario1')");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr style='background: #f0f0f0;'><th>Usuario</th><th>Hash Funciona</th></tr>";

    foreach ($users as $user) {
        $verify = password_verify($password, $user['password']);
        $status = $verify ? "<span style='color: green;'>✓ Correcto</span>" : "<span style='color: red;'>✗ Incorrecto</span>";
        echo "<tr>";
        echo "<td><strong>{$user['username']}</strong></td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<hr>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h2 style='color: #155724; margin-top: 0;'>✓ ¡Listo!</h2>";
    echo "<p style='color: #155724;'>Las contraseñas han sido actualizadas. Ahora puedes hacer login con:</p>";
    echo "<ul style='color: #155724;'>";
    echo "<li><strong>Usuario:</strong> admin</li>";
    echo "<li><strong>Contraseña:</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='" . APP_URL . "/login' style='display: inline-block; background: #206bc4; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>Ir al Login</a></p>";
    echo "</div>";

    echo "<hr>";
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px;'>";
    echo "<p style='color: #856404; margin: 0;'><strong>⚠ Importante:</strong> Por seguridad, elimina este archivo (fix-passwords.php) después de usarlo.</p>";
    echo "</div>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
