<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TokenApiController.php';
require_once __DIR__ . '/../controllers/HotelController.php';

// Validar token
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';

if (empty($token)) {
    echo json_encode(['status' => false, 'msg' => 'Token no proporcionado.']);
    exit();
}

// Validar token en la base de datos
$tokenController = new TokenApiController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData || $tokenData['estado'] != 1) {
    echo json_encode(['status' => false, 'msg' => 'Token inválido o inactivo.']);
    exit();
}

// Procesar acción
$hotelController = new HotelController();
switch ($action) {
    case 'buscarHoteles':
        $search = $_POST['search'] ?? '';
        $hoteles = $hotelController->buscarHoteles($search);
        echo json_encode(['status' => true, 'data' => $hoteles]);
        break;
    default:
        echo json_encode(['status' => false, 'msg' => 'Acción no válida.']);
}
?>
