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
    require_once __DIR__ . '/../controllers/ClientApiController.php';
    $clientApiController = new ClientApiController();
    if ($clientApiController->borrarCliente($_GET['delete'])) {
        $mensaje = " Cliente eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = " Error al eliminar el cliente";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de clientes
require_once __DIR__ . '/../controllers/ClientApiController.php';
$clientApiController = new ClientApiController();
$clientes = $clientApiController->listarClientes();
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

    /* Estado del cliente */
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
            <h3> Gestión de Clientes API</h3>
            <a href="<?php echo BASE_URL; ?>views/cliente_form.php" class="btn btn-success">
                </i> Agregar Nuevo Cliente
            </a>
        </div>

        <?php if (empty($clientes)): ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-users"></i></div>
            <h3>No hay clientes registrados</h3>
            <p>Comienza agregando tu primer cliente al sistema.</p>
            <a href="<?php echo BASE_URL; ?>views/cliente_form.php" class="btn" style="margin-top: 1rem;">
                <i class="fas fa-plus"></i> Agregar Primer Cliente
            </a>
        </div>
        <?php else: ?>
        <div style="overflow-x: auto;">
            <table class="table" id="clientesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>RUC</th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $contador = 1;
                    foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo htmlspecialchars($cliente['ruc']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['razon_social']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['correo']); ?></td>
                        <td class="<?php echo $cliente['estado'] ? 'estado-activo' : 'estado-inactivo'; ?>">
                            <?php echo $cliente['estado'] ? 'Activo' : 'Inactivo'; ?>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="<?php echo BASE_URL; ?>views/cliente_form.php?edit=<?php echo $cliente['id']; ?>" class="btn btn-small btn-warning" title="Editar cliente">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmarEliminacion(<?php echo $cliente['id']; ?>, '<?php echo addslashes($cliente['razon_social']); ?>')" class="btn btn-small btn-danger" title="Eliminar cliente">
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
    function confirmarEliminacion(id, nombre) {
        if (confirm(`¿Estás seguro de que deseas eliminar al cliente "${nombre}"?\n\nEsta acción no se puede deshacer.`)) {
            window.location.href = `<?php echo BASE_URL; ?>views/clientes_list.php?delete=${id}`;
        }
    }
</script>
