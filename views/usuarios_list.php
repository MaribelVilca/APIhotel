<?php
// views/usuarios_list.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';
require_once __DIR__ . '/include/header.php';

$usuarioController = new UsuarioController();
$usuarios = $usuarioController->listarUsuarios();

if (isset($_GET['delete'])) {
    $usuarioController->eliminar($_GET['delete']);
    header('Location: ' . BASE_URL . 'views/usuarios_list.php');
    exit();
}
?>

<style>
    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
    }

    .table-container {
        overflow-x: auto;
        padding: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
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

    .badge {
        padding: 0.35rem 0.6rem;
        border-radius: 5px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .role-badge {
        background: #667eea;
        color: white;
    }
</style>

<div class="container fade-in">
    <div class="dashboard-container">
        <div class="list-header">
            <h2 style="color: #2d3748; margin: 0;">
                <i class="fas fa-users"></i> Lista de Usuarios
            </h2>
            <a href="<?= BASE_URL ?>views/usuario_form.php" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Agregar Usuario
            </a>
        </div>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Usuario</th>
                        <th><i class="fas fa-id-card"></i> Nombre Completo</th>
                        <th><i class="fas fa-user-tag"></i> Rol</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= $usuario['id_usuario'] ?></td>
                            <td><?= $usuario['username'] ?></td>
                            <td><?= $usuario['nombre_completo'] ?></td>
                            <td>
                                <span class="badge role-badge">
                                    <i class="fas fa-shield-alt"></i> <?= ucfirst($usuario['rol']) ?>
                                </span>
                            </td>
                            <td class="table-actions">
                                <a href="<?= BASE_URL ?>views/usuario_form.php?edit=<?= $usuario['id_usuario'] ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= BASE_URL ?>views/usuarios_list.php?delete=<?= $usuario['id_usuario'] ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
