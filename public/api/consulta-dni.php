<?php
/**
 * API Proxy para consulta de DNI
 * Evita problemas de CORS haciendo la petición desde el servidor
 */

header('Content-Type: application/json');

// Solo permitir peticiones GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener el número de DNI
$dni = $_GET['dni'] ?? '';

// Validar DNI
if (empty($dni) || !preg_match('/^\d{8}$/', $dni)) {
    http_response_code(400);
    echo json_encode(['error' => 'DNI inválido. Debe tener 8 dígitos']);
    exit;
}

// URL de la API
$apiUrl = "https://api.apis.net.pe/v1/dni?numero=" . $dni;

// Inicializar cURL
$ch = curl_init();

// Configurar cURL
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Para desarrollo, en producción usar true
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout de 10 segundos
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
]);

// Ejecutar petición
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

// Verificar errores de cURL
if ($error) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al conectar con el servicio',
        'details' => $error
    ]);
    exit;
}

// Verificar código HTTP
if ($httpCode !== 200) {
    http_response_code($httpCode);
    echo json_encode([
        'error' => 'Error en la respuesta de la API',
        'http_code' => $httpCode
    ]);
    exit;
}

// Devolver la respuesta de la API
echo $response;
