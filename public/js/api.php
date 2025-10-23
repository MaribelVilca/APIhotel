<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/ApiHotelController.php';

// Capturar parámetros
$action = $_GET['action'] ?? '';
$token  = $_GET['token'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$codigo = $_GET['codigo'] ?? '';
$search = $_GET['search'] ?? ''; 
// Verificar acción
if ($action === 'buscar_hoteles') {
    // Validar token
    if (empty($token)) {
        echo json_encode(['status' => false, 'msg' => 'Falta el token.']);
        exit;
    }

    // Crear instancia del controlador correcto
    $apiController = new ApiHotelController();

    // Si hay código o nombre, realizar búsqueda
    if (!empty($nombre) || !empty($codigo) || !empty($search)) {
        $response = $apiController->buscarHotelesPorTokenYNombreOCodigo($token, $nombre ?: $search, $codigo);
        echo json_encode($response);
    } else {
        echo json_encode(['status' => false, 'msg' => 'Debes ingresar un nombre o código de hotel.']);
    }
} else {
    echo json_encode(['status' => false, 'msg' => 'Acción inválida o parámetros incorrectos.']);
}
?>
