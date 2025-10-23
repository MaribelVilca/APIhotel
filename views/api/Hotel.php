<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../controllers/ApiHotelController.php';

$ApiHotelController = new ApiHotelController();
$method = $_SERVER['REQUEST_METHOD'];
$token = isset($_SERVER['HTTP_AUTHORIZATION']) ? trim($_SERVER['HTTP_AUTHORIZATION']) : '';

try {
    if (empty($token)) {
        throw new Exception('Token no proporcionado');
    }

    switch ($method) {
        case 'GET':
            if (isset($_GET['nombre'])) {
                $nombre = $_GET['nombre'];
                $response = $hotelApiController->obtenerHotelPorNombreParaApi($token, $nombre);
            } else {
                $response = $hotelApiController->obtenerHotelParaApi($token);
            }
            echo json_encode($response);
            break;
        default:
            echo json_encode(['status' => false, 'msg' => 'Método no soportado']);
            break;
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => false, 'msg' => $e->getMessage()]);
}
?>
