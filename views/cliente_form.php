<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

require_once __DIR__ . '/../controllers/ClientApiController.php';
$clientApiController = new ClientApiController();

// Determinar si es edición o creación
$isEditing = isset($_GET['edit']) && is_numeric($_GET['edit']);
$cliente = null;
$pageTitle = $isEditing ? ' Editar Cliente' : ' Agregar Nuevo Cliente';

if ($isEditing) {
    $cliente = $clientApiController->obtenerCliente($_GET['edit']);
    if (!$cliente) {
        header('Location: ' . BASE_URL . 'views/clientes_list.php');
        exit();
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ruc = trim($_POST['ruc']);
    $razon_social = trim($_POST['razon_social']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $estado = $isEditing ? (isset($_POST['estado']) ? 1 : 0) : 1; // Estado activo por defecto al crear

    // Validaciones
    $errores = [];
    if (empty($ruc)) {
        $errores[] = "El campo RUC es obligatorio";
    }
    if (empty($razon_social)) {
        $errores[] = "El campo Razón Social es obligatorio";
    }
    if (!empty($correo) && !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato del correo electrónico no es válido";
    }

    if (empty($errores)) {
        if ($isEditing) {
            $resultado = $clientApiController->editarCliente($_GET['edit'], $ruc, $razon_social, $telefono, $correo, $estado);
            if ($resultado) {
                $mensaje = " Cliente actualizado exitosamente";
                $tipo_mensaje = "success";
                $cliente = $clientApiController->obtenerCliente($_GET['edit']);
            } else {
                $mensaje = " Error al actualizar el cliente";
                $tipo_mensaje = "error";
            }
        } else {
            $resultado = $clientApiController->crearCliente($ruc, $razon_social, $telefono, $correo);
            if ($resultado) {
                header('Location: ' . BASE_URL . 'views/clientes_list.php?created=1');
                exit();
            } else {
                $mensaje = " Error al crear el cliente";
                $tipo_mensaje = "error";
            }
        }
    }
}
require_once __DIR__ . '/include/header.php';
?>

<!-- Incluir Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


<style>
    /* Estilos globales */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Mensajes */
    .message {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 5px;
        text-align: center;
        font-weight: 500;
    }

    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Contenedor del formulario */
    .form-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 2rem;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .form-header h2 {
        color: #2d3748;
        margin: 0;
    }

    /* Formulario */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .form-section {
        margin-bottom: 1.5rem;
    }

    .form-section h4 {
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #2d3748;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
    }

    /* Botones */
    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-success {
        background-color: #48bb78;
        color: white;
    }

    .btn-warning {
        background-color: #fbbf24;
        color: white;
    }

    .btn-danger {
        background-color: #f56565;
        color: white;
    }

    .btn-cancel {
        background-color: #e2e8f0;
        color: #2d3748;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
    }

    .required-note {
        color: #666;
        font-size: 0.875rem;
    }
</style>

<div class="container fade-in">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="<?php echo BASE_URL; ?>views/dashboard.php"> Dashboard</a>
        <span>></span>
        <a href="<?php echo BASE_URL; ?>views/clientes_list.php"> Clientes</a>
        <span>></span>
        <span><?php echo $isEditing ? 'Editar' : 'Nuevo'; ?></span>
    </div>

    <!-- Mensajes -->
    <?php if (isset($mensaje)): ?>
    <div class="message <?php echo $tipo_mensaje; ?>">
        <?php echo $mensaje; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
    <div class="message error">
        <strong> Se encontraron los siguientes errores:</strong>
        <ul>
            <?php foreach ($errores as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="form-container">
        <div class="form-header">
            <h2><?php echo $pageTitle; ?></h2>
            <a href="<?php echo BASE_URL; ?>views/clientes_list.php" class="btn btn-cancel">
                ← Volver al listado
            </a>
        </div>

        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-section">
                    <h4> Información del Cliente</h4>
                    <div class="form-group">
                        <label for="ruc">RUC *</label>
                        <input type="text" id="ruc" name="ruc" class="form-control" value="<?php echo htmlspecialchars($cliente['ruc'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="razon_social">Razón Social *</label>
                        <input type="text" id="razon_social" name="razon_social" class="form-control" value="<?php echo htmlspecialchars($cliente['razon_social'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" class="form-control" value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($cliente['correo'] ?? ''); ?>">
                    </div>
                    <?php if ($isEditing): ?>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="estado" value="1" <?php echo isset($cliente['estado']) && $cliente['estado'] ? 'checked' : ''; ?>> Estado (Activo)
                        </label>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-actions">
                <div class="required-note">
                    <span style="color: #666; font-size: 0.875rem;">* Campos obligatorios</span>
                </div>
                <div class="action-buttons">
                    <a href="<?php echo BASE_URL; ?>views/clientes_list.php" class="btn btn-cancel">
                        Cancelar
                    </a>
                    <?php if ($isEditing): ?>
                    <button type="submit" class="btn btn-warning">
                         Actualizar Cliente
                    </button>
                    <?php else: ?>
                    <button type="submit" class="btn btn-success">
                         Crear Cliente
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

