<?php
/**
 * API Proxy para consulta de DNI y RUC
 * Evita problemas de CORS haciendo la petición desde el servidor
 */

header('Content-Type: application/json');

// Solo permitir peticiones GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Obtener el número de documento y tipo
$numero = $_GET['numero'] ?? $_GET['dni'] ?? ''; // Soportar ambos parámetros por compatibilidad
$tipo = $_GET['tipo'] ?? '';

// Validar número de documento
if (empty($numero)) {
    http_response_code(400);
    echo json_encode(['error' => 'Número de documento requerido']);
    exit;
}

// Determinar tipo si no se especificó (por longitud)
if (empty($tipo)) {
    $tipo = strlen($numero) === 8 ? 'DNI' : (strlen($numero) === 11 ? 'RUC' : '');
}

// Validar según el tipo
if ($tipo === 'DNI') {
    if (!preg_match('/^\d{8}$/', $numero)) {
        http_response_code(400);
        echo json_encode(['error' => 'DNI inválido. Debe tener 8 dígitos']);
        exit;
    }
    $apiUrl = "https://api.apis.net.pe/v1/dni?numero=" . $numero;
} elseif ($tipo === 'RUC') {
    if (!preg_match('/^\d{11}$/', $numero)) {
        http_response_code(400);
        echo json_encode(['error' => 'RUC inválido. Debe tener 11 dígitos']);
        exit;
    }
    $apiUrl = "https://api.apis.net.pe/v1/ruc?numero=" . $numero;
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Tipo de documento no válido']);
    exit;
}

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
