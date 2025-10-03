<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}
require_once __DIR__ . '/../controllers/TokenApiController.php';
$tokenApiController = new TokenApiController();

// Determinar si es edición o creación
$isEditing = isset($_GET['edit']) && is_numeric($_GET['edit']);
$token = null;
$pageTitle = $isEditing ? '✏️ Editar Token' : '➕ Generar Nuevo Token';

// Obtener clientes
$clientes = $tokenApiController->obtenerClientes();

if ($isEditing) {
    $token = $tokenApiController->obtenerToken($_GET['edit']);
    if (!$token) {
        header('Location: ' . BASE_URL . 'views/tokens_list.php');
        exit();
    }
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_client_api = trim($_POST['id_client_api']);
    $estado = $isEditing ? (isset($_POST['estado']) ? 1 : 0) : 1;

    // Validaciones
    $errores = [];
    if (empty($id_client_api)) {
        $errores[] = "El campo Cliente es obligatorio";
    }

    if (empty($errores)) {
        if ($isEditing) {
            $resultado = $tokenApiController->editarToken($_GET['edit'], $estado);
            if ($resultado) {
                $mensaje = "Token actualizado exitosamente";
                $tipo_mensaje = "success";
                $token = $tokenApiController->obtenerToken($_GET['edit']);
            } else {
                $mensaje = "Error al actualizar el token";
                $tipo_mensaje = "error";
            }
        } else {
            $resultado = $tokenApiController->crearToken($id_client_api);
            if ($resultado) {
                header('Location: ' . BASE_URL . 'views/tokens_list.php?created=1');
                exit();
            } else {
                $mensaje = "Error al generar el token";
                $tipo_mensaje = "error";
            }
        }
    }
}

require_once __DIR__ . '/include/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: "Segoe UI", Roboto, sans-serif;
        background: #f4f6f8;
        color: #333;
        margin: 0;
        padding: 20px;
    }

    .dashboard-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Tarjetas */
    .card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .card h3 {
        font-size: 1.3rem;
        margin-bottom: 1rem;
        color: #34495e;
    }

    .card i {
        margin-right: 8px;
        color: #6c8ea4;
    }

    /* Mensajes */
    .mensaje {
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .mensaje.success {
        background: #e9f7ef;
        color: #1e5631;
        border: 1px solid #c7e6d5;
    }

    .mensaje.error {
        background: #fdecea;
        color: #b23b3b;
        border: 1px solid #f5c6cb;
    }

    /* Formulario */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #34495e;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #e0e6ed;
        border-radius: 6px;
        font-size: 1rem;
    }

    /* Botones */
    .quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .quick-actions a, .quick-actions button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        padding: 0.7rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-success {
        background: #6c8ea4;
        color: #fff;
    }

    .btn-warning {
        background: #fbbf24;
        color: #fff;
    }

    .btn-danger {
        background: #c0392b;
        color: #fff;
    }

    .btn-cancel {
        background: #ecf0f1;
        color: #34495e;
    }

    .quick-actions a:hover, .quick-actions button:hover {
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

    /* Token generado */
    .token-display {
        background: #f7fafc;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
        font-family: monospace;
        word-break: break-all;
    }
</style>

<div class="dashboard-container">
    <!-- Breadcrumb -->
    <div style="margin-bottom: 1rem; color: #6c8ea4;">
        <a href="<?php echo BASE_URL; ?>views/dashboard.php" style="color: #6c8ea4; text-decoration: none;">Dashboard</a>
        <span> > </span>
        <a href="<?php echo BASE_URL; ?>views/tokens_list.php" style="color: #6c8ea4; text-decoration: none;"> Tokens</a>
        <span> > </span>
        <span><?php echo $isEditing ? 'Editar' : 'Nuevo'; ?></span>
    </div>

    <!-- Mensajes -->
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?php echo $tipo_mensaje; ?>">
            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="mensaje error">
            <strong> Se encontraron los siguientes errores:</strong>
            <ul style="margin-top: 0.5rem; padding-left: 1.5rem;">
                <?php foreach ($errores as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <h3><i class="fas fa-key"></i> <?php echo $pageTitle; ?></h3>
        <form method="POST" action="">
            <div class="form-group">
                <label for="id_client_api">Cliente *</label>
                <select id="id_client_api" name="id_client_api" class="form-control" required>
                    <option value="">-- Seleccione un cliente --</option>
                    <?php foreach ($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['id']; ?>" <?php echo ($isEditing && $token['id_client_api'] == $cliente['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cliente['razon_social']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php if ($isEditing): ?>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="estado" value="1" <?php echo isset($token['estado']) && $token['estado'] ? 'checked' : ''; ?>> Estado (Activo)
                    </label>
                </div>

                <div class="form-group">
                    <label>Token Generado</label>
                    <div class="token-display">
                        <?php echo htmlspecialchars($token['token']); ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <div class="required-note">
                    <span>* Campos obligatorios</span>
                </div>
                <div class="quick-actions">
                    <a href="<?php echo BASE_URL; ?>views/tokens_list.php" class="btn-cancel">
                        Cancelar
                    </a>
                    <?php if ($isEditing): ?>
                        <button type="submit" class="btn-warning">
                            <i class="fas fa-save"></i> Actualizar Token
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn-success">
                            <i class="fas fa-plus-circle"></i> Generar Token
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>
