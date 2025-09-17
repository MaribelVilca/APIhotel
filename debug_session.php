<?php
// debug_session.php - Coloca este archivo en la raíz /APIDOCENTES/
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 DEBUG SESIONES Y REDIRECCIONES</h2>";
echo "<hr>";

// 1. Verificar información de sesión
echo "<h3>1. Estado de la Sesión:</h3>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Status: " . session_status() . " (1=disabled, 2=active, 3=none)<br>";

if (isset($_SESSION['user_id'])) {
    echo " Sesión activa encontrada:<br>";
    echo "- User ID: " . $_SESSION['user_id'] . "<br>";
    echo "- Username: " . ($_SESSION['username'] ?? 'No definido') . "<br>";
    echo "- Nombre completo: " . ($_SESSION['nombre_completo'] ?? 'No definido') . "<br>";
    echo "- Rol: " . ($_SESSION['rol'] ?? 'No definido') . "<br>";
} else {
    echo " No hay sesión activa<br>";
}

echo "<h4>Todas las variables de sesión:</h4>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// 2. Probar el proceso de login manualmente
echo "<h3>2. Prueba Manual del Login:</h3>";
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AuthController.php';

try {
    $authController = new AuthController();
    echo "AuthController creado exitosamente<br>";
    
    // Intentar login
    $login_result = $authController->login('admin', 'admin123');
    echo "Resultado del login: " . ($login_result ? 'EXITOSO' : 'FALLIDO') . "<br>";
    
    if ($login_result) {
        echo "<h4>Sesión después del login:</h4>";
        echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    }
    
} catch (Exception $e) {
    echo " Error en AuthController: " . $e->getMessage() . "<br>";
}

// 3. Verificar URLs y rutas
echo "<h3>3. Verificación de URLs:</h3>";
echo "BASE_URL definida: " . (defined('BASE_URL') ? BASE_URL : 'NO DEFINIDA') . "<br>";
echo "URL del dashboard: " . BASE_URL . 'views/dashboard.php<br>';

// Verificar si el archivo dashboard existe
$dashboard_path = __DIR__ . '/views/dashboard.php';
echo "Dashboard existe: " . (file_exists($dashboard_path) ? ' SÍ' : 'NO') . "<br>";
echo "Ruta completa: " . $dashboard_path . "<br>";

// 4. Simular redirección
echo "<h3>4. Simulación de Redirección:</h3>";
if (isset($_SESSION['user_id'])) {
    echo " Sesión válida - Redirección debería funcionar<br>";
    echo "<a href='" . BASE_URL . "views/dashboard.php'>🔗 Ir al Dashboard manualmente</a><br>";
} else {
    echo "Sin sesión - Necesitas hacer login primero<br>";
}

// 5. Verificar headers y output
echo "<h3>5. Headers y Output:</h3>";
if (headers_sent($file, $line)) {
    echo "⚠️ Headers ya enviados en archivo: $file línea: $line<br>";
    echo "Esto puede impedir las redirecciones<br>";
} else {
    echo " Headers no enviados - Redirecciones deberían funcionar<br>";
}

// 6. Revisar configuración PHP
echo "<h3>6. Configuración PHP:</h3>";
echo "output_buffering: " . (ini_get('output_buffering') ? 'ON' : 'OFF') . "<br>";
echo "session.use_cookies: " . (ini_get('session.use_cookies') ? 'ON' : 'OFF') . "<br>";

echo "<hr>";
echo "<h3>🧪 Formulario de Prueba:</h3>";
?>

<form method="POST" action="">
    <input type="hidden" name="test_login" value="1">
    <button type="submit">🧪 Probar Login Directo</button>
</form>

<?php
if (isset($_POST['test_login'])) {
    echo "<h4>Resultado de prueba de login:</h4>";
    
    // Limpiar cualquier output previo
    if (ob_get_level()) {
        ob_clean();
    }
    
    try {
        $authController = new AuthController();
        if ($authController->login('admin', 'admin123')) {
            echo " Login exitoso - Intentando redirección...<br>";
            echo "<script>console.log('Redirigiendo...'); window.location.href = '" . BASE_URL . "views/dashboard.php';</script>";
            echo "<meta http-equiv='refresh' content='2;url=" . BASE_URL . "views/dashboard.php'>";
            echo "<p>Si no se redirige automáticamente, <a href='" . BASE_URL . "views/dashboard.php'>haz clic aquí</a></p>";
        } else {
            echo " Login falló<br>";
        }
    } catch (Exception $e) {
        echo " Error: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";
echo "<h3>🔧 Soluciones Sugeridas:</h3>";
echo "1. Si headers ya están enviados, hay output antes del header() en algún archivo<br>";
echo "2. Verificar que dashboard.php existe y es accesible<br>";
echo "3. Revisar que no hay espacios/caracteres antes de <?php en los archivos<br>";
echo "4. Probar con JavaScript redirect si header() no funciona<br>";
?>