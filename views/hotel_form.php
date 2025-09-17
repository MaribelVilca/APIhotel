<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

require_once __DIR__ . '/../controllers/HotelController.php';
$hotelController = new HotelController();

// Determinar si es edición o creación
$isEditing = isset($_GET['edit']) && is_numeric($_GET['edit']);
$hotel = null;
$pageTitle = $isEditing ? 'Editar Hotel' : 'Agregar Nuevo Hotel';

if ($isEditing) {
    $hotel = $hotelController->obtenerHotel($_GET['edit']);
    if (!$hotel) {
        header('Location: ' . BASE_URL . 'views/hoteles_list.php');
        exit();
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $ubicacion = trim($_POST['ubicacion']);
    $historia = trim($_POST['historia']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $precio_promedio = isset($_POST['precio_promedio_custom']) ? trim($_POST['precio_promedio_custom']) : trim($_POST['precio_promedio_select']);
    $servicios = isset($_POST['servicios']) ? implode(", ", $_POST['servicios']) : '';
    $nuevos_servicios = trim($_POST['nuevos_servicios']);

    if (!empty($nuevos_servicios)) {
        $servicios .= ($servicios ? ", " : "") . $nuevos_servicios;
    }
    $imagen = trim($_POST['imagen']);

    // Validaciones
    $errores = [];
    if (empty($nombre)) $errores[] = "El campo Nombre es obligatorio";
    if (empty($direccion)) $errores[] = "El campo Dirección es obligatorio";
    if (empty($ubicacion)) {
        $errores[] = "El campo Ubicación es obligatorio";
    } elseif (strpos($ubicacion, 'https://maps.app.goo.gl/') !== 0) {
        $errores[] = "La ubicación debe ser un enlace válido de Google Maps";
    }
    if (empty($historia)) $errores[] = "El campo Historia es obligatorio";
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido";
    }

    if (empty($errores)) {
        if ($isEditing) {
            $resultado = $hotelController->editarHotel(
                $_GET['edit'],
                $nombre, $direccion, $ubicacion,
                $historia, $telefono, $email,
                $precio_promedio, $servicios, $imagen
            );
            $mensaje = $resultado ? "✅ Hotel actualizado exitosamente" : "❌ Error al actualizar el hotel";
            $tipo_mensaje = $resultado ? "success" : "error";
            $hotel = $hotelController->obtenerHotel($_GET['edit']);
        } else {
            $resultado = $hotelController->crearHotel(
                $nombre, $direccion, $ubicacion,
                $historia, $telefono, $email,
                $precio_promedio, $servicios, $imagen
            );
            if ($resultado) {
                header('Location: ' . BASE_URL . 'views/hoteles_list.php?created=1');
                exit();
            } else {
                $mensaje = "❌ Error al crear el hotel";
                $tipo_mensaje = "error";
            }
        }
    }
}

// Lista de servicios comunes
$serviciosComunes = ["WiFi gratis", "Desayuno", "Estacionamiento", "Restaurante", "Piscina", "Bar", "Spa", "Terraza con vista", "Aire acondicionado"];

require_once __DIR__ . '/include/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    .form-container {
        max-width: 800px;
        margin: 30px auto;
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #374151;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        font-weight: bold;
        display: block;
        margin-bottom: 6px;
        color: #333;
    }
    input[type="text"],
    input[type="email"],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        outline: none;
    }
    textarea {
        min-height: 100px;
    }
    .alert-success {
        background: #d1fae5;
        border-left: 5px solid #10b981;
        padding: 10px;
        margin-bottom: 15px;
        color: #065f46;
        border-radius: 6px;
    }
    .alert-error {
        background: #fee2e2;
        border-left: 5px solid #ef4444;
        padding: 10px;
        margin-bottom: 15px;
        color: #991b1b;
        border-radius: 6px;
    }
    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-primary {
        background: #3b82f6;
        color: #fff;
    }
    .btn-primary:hover {
        background: #2563eb;
    }
    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }
    .btn-secondary:hover {
        background: #d1d5db;
    }
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .checkbox-group label {
        font-weight: normal;
    }
</style>

<div class="form-container">
    <h2><i class="fas fa-hotel"></i> <?php echo $pageTitle; ?></h2>
    <a href="<?php echo BASE_URL; ?>views/hoteles_list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>

    <?php if (isset($mensaje)): ?>
        <div class="alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert-error">
            <strong>⚠ Se encontraron errores:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="nombre"><i class="fas fa-signature"></i> Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($hotel['nombre'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="direccion"><i class="fas fa-map-marker-alt"></i> Dirección *</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($hotel['direccion'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="ubicacion"><i class="fas fa-map"></i> Google Maps *</label>
            <input type="text" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($hotel['ubicacion'] ?? ''); ?>" required placeholder="https://maps.app.goo.gl/...">
        </div>

        <div class="form-group">
            <label for="historia"><i class="fas fa-book-open"></i> Historia *</label>
            <textarea id="historia" name="historia" required><?php echo htmlspecialchars($hotel['historia'] ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label for="telefono"><i class="fas fa-phone"></i> Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($hotel['telefono'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($hotel['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="precio_promedio"><i class="fas fa-dollar-sign"></i> Precio Promedio</label>
            <select id="precio_promedio_select" name="precio_promedio_select" onchange="toggleCustomPrice()">
                <option value="">Selecciona un rango</option>
                <?php 
                $rangos = ["30 - 80","40 - 100","80 - 150","150 - 250"];
                foreach ($rangos as $r) {
                    echo "<option value='$r' ".((isset($hotel['precio_promedio']) && $hotel['precio_promedio']===$r) ? "selected" : "").">$r</option>";
                }
                ?>
                <option value="custom" <?php echo (!in_array($hotel['precio_promedio'] ?? '', $rangos) && !empty($hotel['precio_promedio'])) ? 'selected' : ''; ?>>Personalizado</option>
            </select>
            <input type="text" id="precio_promedio_custom" name="precio_promedio_custom" value="<?php echo (!in_array($hotel['precio_promedio'] ?? '', $rangos) && !empty($hotel['precio_promedio'])) ? htmlspecialchars($hotel['precio_promedio']) : ''; ?>" placeholder="Ej: 50 - 120" style="display: none; margin-top: 10px;">
        </div>

        <div class="form-group">
            <label><i class="fas fa-concierge-bell"></i> Servicios</label>
            <div class="checkbox-group">
                <?php foreach ($serviciosComunes as $servicio): ?>
                    <div>
                        <input type="checkbox" id="servicio_<?php echo strtolower(str_replace(' ', '_', $servicio)); ?>" name="servicios[]" value="<?php echo $servicio; ?>" <?php echo ($hotel && strpos($hotel['servicios'], $servicio) !== false) ? 'checked' : ''; ?>>
                        <label for="servicio_<?php echo strtolower(str_replace(' ', '_', $servicio)); ?>"><?php echo $servicio; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="text" id="nuevos_servicios" name="nuevos_servicios" placeholder="Otros (ej: Gimnasio, Room service)">
        </div>

        <div class="form-group">
            <label for="imagen"><i class="fas fa-image"></i> URL de la Imagen</label>
            <input type="text" id="imagen" name="imagen" value="<?php echo htmlspecialchars($hotel['imagen'] ?? ''); ?>">
        </div>

        <div class="form-actions">
            <a href="<?php echo BASE_URL; ?>views/hoteles_list.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancelar</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $isEditing ? 'Actualizar Hotel' : 'Crear Hotel'; ?></button>
        </div>
    </form>
</div>

<script>
    function toggleCustomPrice() {
        var select = document.getElementById('precio_promedio_select');
        var customInput = document.getElementById('precio_promedio_custom');
        if (select.value === 'custom') {
            customInput.style.display = 'block';
            customInput.required = true;
        } else {
            customInput.style.display = 'none';
            customInput.required = false;
            customInput.value = select.value;
        }
    }
    window.onload = toggleCustomPrice;
</script>

<?php require_once __DIR__ . '/include/footer.php'; ?>
