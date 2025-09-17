<?php
// public/index.php - Versión mejorada
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión al principio
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

require_once __DIR__ . '/../controllers/HotelController.php';
// Función para redirigir de forma segura
function safe_redirect($url) {
    // Limpiar cualquier output buffer
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Usar tanto header como JavaScript por si las moscas
    header('Location: ' . $url);
    echo "<script>window.location.href = '$url';</script>";
    echo "<meta http-equiv='refresh' content='0;url=$url'>";
    exit();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

error_log("Public index - Action: $action, Method: " . $_SERVER['REQUEST_METHOD']);

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        error_log("Missing POST data");
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }
    
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        error_log("Empty credentials");
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }
    
    try {
        $authController = new AuthController();
        
        if ($authController->login($username, $password)) {
            error_log("Login successful - redirecting to dashboard");
            safe_redirect(BASE_URL . 'views/dashboard.php');
        } else {
            error_log("Login failed");
            safe_redirect(BASE_URL . 'views/login.php?error=1');
        }
        
    } catch (Exception $e) {
        error_log("Login exception: " . $e->getMessage());
        safe_redirect(BASE_URL . 'views/login.php?error=1');
    }
    
} else {
    // No es login, redirigir según el estado de autenticación
    $authController = new AuthController();
    
    if ($authController->isAuthenticated()) {
        error_log("User already authenticated - redirecting to dashboard");
        safe_redirect(BASE_URL . 'views/dashboard.php');
    } else {
        error_log("User not authenticated - redirecting to login");
        safe_redirect(BASE_URL . 'views/login.php');
    }
}


$hotelController = new HotelController();

// Manejo de solicitudes API
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'listar':
            header('Content-Type: application/json');
            echo json_encode($hotelController->listarHoteles());
            break;
        case 'obtener':
            if (isset($_GET['id'])) {
                header('Content-Type: application/json');
                echo json_encode($hotelController->obtenerHotel($_GET['id']));
            }
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'crear':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->crearHotel(
                $data['nombre'],
                $data['direccion'],
                $data['ubicacion'],
                $data['historia'],
                $data['telefono'],
                $data['email'],
                $data['precio_promedio'],
                $data['servicios'],
                $data['imagen']
            );
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'editar':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->editarHotel(
                $data['id_hotel'],
                $data['nombre'],
                $data['direccion'],
                $data['ubicacion'],
                $data['historia'],
                $data['telefono'],
                $data['email'],
                $data['precio_promedio'],
                $data['servicios'],
                $data['imagen']
            );
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'eliminar':
            $data = json_decode(file_get_contents('php://input'), true);
            $resultado = $hotelController->borrarHotel($data['id_hotel']);
            header('Content-Type: application/json');
            echo json_encode(['success' => $resultado]);
            break;
    }
}
?>