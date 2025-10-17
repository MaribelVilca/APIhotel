<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}

// Manejar eliminación
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    require_once __DIR__ . '/../controllers/TokenApiController.php';
    $tokenApiController = new TokenApiController();
    if ($tokenApiController->borrarToken($_GET['delete'])) {
        $mensaje = "Token eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = " Error al eliminar el token";
        $tipo_mensaje = "error";
    }
}

// Manejar cambio de estado
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    require_once __DIR__ . '/../controllers/TokenApiController.php';
    $tokenApiController = new TokenApiController();
    $token = $tokenApiController->obtenerToken($_GET['toggle']);
    $nuevoEstado = $token['estado'] ? 0 : 1;
    if ($tokenApiController->cambiarEstadoToken($_GET['toggle'], $nuevoEstado)) {
        $mensaje = " Estado del token actualizado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = " Error al actualizar el estado del token";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de tokens
require_once __DIR__ . '/../controllers/TokenApiController.php';
$tokenApiController = new TokenApiController();

// Búsqueda por nombre
$nombreBusqueda = isset($_GET['search']) ? trim($_GET['search']) : '';
$tokens = $nombreBusqueda ? $tokenApiController->buscarTokensPorNombre($nombreBusqueda) : $tokenApiController->listarTokens();
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

    /* Contenedor de la tabla */
    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Encabezado de la tabla */
    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background: rgba(102, 126, 234, 0.05);
        border-bottom: 1px solid #e2e8f0;
    }

    .table-header h3 {
        color: #2d3748;
        margin: 0;
    }

    /* Barra de búsqueda */
    .search-container {
        padding: 1.5rem;
        background: rgba(102, 126, 234, 0.05);
        border-bottom: 1px solid #e2e8f0;
    }

    .search-box {
        width: 100%;
        max-width: 400px;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 5px;
        font-size: 1rem;
    }

    /* Tabla */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table th {
        background-color: #f7fafc;
        font-weight: 600;
        color: #2d3748;
        cursor: pointer;
    }

    .sort-indicator {
        margin-left: 0.5rem;
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

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-small {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* Estado vacío */
    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #666;
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
    }

    /* Estado del token */
    .estado-activo {
        color: #48bb78;
        font-weight: bold;
    }

    .estado-inactivo {
        color: #f56565;
        font-weight: bold;
    }

    /* Numeración */
    .table td:nth-child(1) {
        font-weight: bold;
        color: #2d3748;
    }

    /* Token */
    .token {
        font-family: monospace;
        font-size: 0.875rem;
        word-break: break-all;
    }

    /* Toggle switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background-color: #48bb78;
    }

    input:checked + .toggle-slider:before {
        transform: translateX(26px);
    }
</style>

<div class="container fade-in">
    <!-- Mensajes -->
    <?php if (isset($mensaje)): ?>
    <div class="message <?php echo $tipo_mensaje; ?>">
        <?php echo $mensaje; ?>
    </div>
    <?php endif; ?>

    <div class="table-container">
        <div class="table-header">
            <h3> Gestión de Tokens API</h3>
            <a href="<?php echo BASE_URL; ?>views/token_form.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Generar Nuevo Token
            </a>
        </div>

        <!-- Barra de búsqueda -->
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" class="search-box" placeholder="🔍 Buscar por nombre de cliente..." value="<?php echo htmlspecialchars($nombreBusqueda); ?>">
            </form>
        </div>

        <?php if (empty($tokens)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-key"></i></div>
            <h3>No hay tokens registrados</h3>
            <p>Comienza generando tu primer token.</p>
            <a href="<?php echo BASE_URL; ?>views/token_form.php" class="btn" style="margin-top: 1rem;">
                <i class="fas fa-plus"></i> Generar Primer Token
            </a>
        </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table" id="tokensTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Token</th>
                        <th>Fecha de Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    foreach ($tokens as $token): ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo htmlspecialchars($token['razon_social']); ?></td>
                        <td class="token"><?php echo htmlspecialchars($token['token']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($token['fecha_registro'])); ?></td>
                        <td>
                            <label class="toggle-switch">
                                <input type="checkbox" <?php echo $token['estado'] ? 'checked' : ''; ?>
                                       onchange="cambiarEstado(<?php echo $token['id']; ?>, this.checked)">
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td>
                            <div class="table-actions">
                                <button onclick="confirmarEliminacion(<?php echo $token['id']; ?>, '<?php echo addslashes($token['token']); ?>')" class="btn btn-small btn-danger" title="Eliminar token">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmarEliminacion(id, token) {
        if (confirm(`¿Estás seguro de que deseas eliminar el token "${token.substring(0, 10)}..."?\n\nEsta acción no se puede deshacer.`)) {
            window.location.href = `<?php echo BASE_URL; ?>views/tokens_list.php?delete=${id}`;
        }
    }

    function cambiarEstado(id, nuevoEstado) {
        window.location.href = `<?php echo BASE_URL; ?>views/tokens_list.php?toggle=${id}`;
    }
</script>
