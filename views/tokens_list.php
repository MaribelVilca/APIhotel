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
        $mensaje = "Error al eliminar el token";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de tokens
require_once __DIR__ . '/../controllers/TokenApiController.php';
$tokenApiController = new TokenApiController();
$tokens = $tokenApiController->listarTokens();

require_once __DIR__ . '/include/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>

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

    /* Tabla */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    table th, table td {
        padding: 0.8rem;
        text-align: left;
        border-bottom: 1px solid #e0e6ed;
    }

    table th {
        background: #f0f3f6;
        color: #2c3e50;
    }

    table tr:hover {
        background: #f9fafb;
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
        padding: 0.5rem 0.8rem;
        border-radius: 6px;
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

    .quick-actions a:hover, .quick-actions button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Estado */
    .estado-activo {
        color: #27ae60;
        font-weight: bold;
    }

    .estado-inactivo {
        color: #c0392b;
        font-weight: bold;
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

    /* Token */
    .token {
        font-family: monospace;
        font-size: 0.875rem;
        word-break: break-all;
    }
</style>

<div class="dashboard-container">
    <!-- Mensajes -->
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?php echo $tipo_mensaje; ?>">
            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3></i> Gestión de Tokens API</h3>
            <a href="<?php echo BASE_URL; ?>views/token_form.php" class="btn-success" style="padding: 0.5rem 1rem; border-radius: 6px;">
               </i> Generar Nuevo Token
            </a>
        </div>

        <?php if (empty($tokens)): ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-key"></i></div>
                <h3>No hay tokens registrados</h3>
                <p>Comienza generando tu primer token.</p>
                <a href="<?php echo BASE_URL; ?>views/token_form.php" class="btn-success" style="margin-top: 1rem; padding: 0.5rem 1rem; border-radius: 6px;">
                    </i> Generar Primer Token
                </a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table>
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
                        <?php $contador = 1; ?>
                        <?php foreach ($tokens as $token): ?>
                            <tr>
                                <td><?php echo $contador++; ?></td>
                                <td><?php echo htmlspecialchars($token['razon_social']); ?></td>
                                <td class="token"><?php echo htmlspecialchars($token['token']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($token['fecha_registro'])); ?></td>
                                <td class="<?php echo $token['estado'] ? 'estado-activo' : 'estado-inactivo'; ?>">
                                    <?php echo $token['estado'] ? 'Activo' : 'Inactivo'; ?>
                                </td>
                                <td>
                                    <div class="quick-actions">
                                        <a href="<?php echo BASE_URL; ?>views/token_form.php?edit=<?php echo $token['id']; ?>" class="btn-warning" title="Editar token">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmarEliminacion(<?php echo $token['id']; ?>, '<?php echo addslashes($token['token']); ?>')" class="btn-danger" title="Eliminar token">
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
</script>
