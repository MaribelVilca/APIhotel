<?php
// api_handler.php (APIHOTEL)
header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TokenApiController.php';
require_once __DIR__ . '/controllers/HotelController.php';

// Obtener el token y la acción
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';

// Validar el token en APIHOTEL
if ($action === 'validarToken') {
    $tokenController = new TokenApiController();
    $tokenData = $tokenController->obtenerTokenPorToken($token);

    if (!$tokenData) {
        echo json_encode([
            'status' => false,
            'type' => 'error',
            'msg' => 'Token no encontrado en APIHOTEL.'
        ]);
        exit();
    }

    if ($tokenData['estado'] != 1) {
        echo json_encode([
            'status' => false,
            'type' => 'warning',
            'msg' => 'Token inactivo en APIHOTEL.'
        ]);
        exit();
    }

    echo json_encode([
        'status' => true,
        'type' => 'success',
        'msg' => 'Token válido en APIHOTEL.'
    ]);
    exit();
}

// Validar el token para otras acciones
$tokenController = new TokenApiController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no encontrado en APIHOTEL.'
    ]);
    exit();
}

if ($tokenData['estado'] != 1) {
    echo json_encode([
        'status' => false,
        'type' => 'warning',
        'msg' => 'Token inactivo en APIHOTEL.'
    ]);
    exit();
}

// Procesar la acción (ej: buscarHoteles)
switch ($action) {
    case 'buscarHoteles':
        $hotelController = new HotelController();
        $search = $_POST['search'] ?? '';
        $hoteles = $hotelController->buscarHoteles($search);
        foreach ($hoteles as &$hotel) {
            unset($hotel['email']); // Ocultar información sensible si es necesario
        }
        echo json_encode([
            'status' => true,
            'type' => 'success',
            'data' => $hoteles
        ]);
        break;
    default:
        echo json_encode([
            'status' => false,
            'type' => 'error',
            'msg' => 'Acción no válida.'
        ]);
}
?>
