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

$totalHoteles = count($hotelController->listarHoteles());
$hotelesRecientes = array_slice($hotelController->listarHoteles(), -5);

require_once __DIR__ . '/include/header.php';
?>
<style>
    body {
  font-family: "Segoe UI", Roboto, sans-serif;
  background: #f4f6f8;
  color: #333;
  margin: 0;
  padding: 20px;
}

h2 {
  font-size: 1.8rem;
  margin-bottom: 0.5rem;
  color: #2d3436;
}

p {
  margin: 0.3rem 0;
}

.dashboard-container {
  max-width: 1100px;
  margin: 0 auto;
}

/* === TARJETAS === */
.card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 6px 15px rgba(0,0,0,0.08);
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

/* === ACCIONES RÁPIDAS === */
.quick-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.quick-actions a {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  text-decoration: none;
  background: #6c8ea4;
  color: #fff;
  padding: 0.7rem 1rem;
  border-radius: 8px;
  font-weight: 500;
  transition: background 0.3s ease;
}

.quick-actions a:hover {
  background: #5a758a;
}

/* === TABLA === */
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

</style>
<div class="dashboard-container">
    <h2></i> ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['username']); ?>!</h2>
    <p>Sistema de Gestión de Hoteles</p>

    <div class="card">
        <h3></i> Estadísticas</h3>
        <p>Total de hoteles registrados: <strong><?php echo $totalHoteles; ?></strong></p>
    </div>

    <div class="card">
        <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
        <div class="quick-actions">
            <a href="<?php echo BASE_URL; ?>views/hoteles_list.php"></i> Ver Todos los Hoteles</a>
            <a href="<?php echo BASE_URL; ?>views/hotel_form.php"></i> Agregar Nuevo Hotel</a>
            <a href="<?= BASE_URL ?>views/usuarios_list.php"></i> Gestionar Usuarios</a>
            <a href="<?php echo BASE_URL; ?>views/clientes_list.php" class="btn btn-primary">
            <i class="fas fa-users"></i> Gestionar Clientes API
        </a>
        <a href="<?php echo BASE_URL; ?>views/tokens_list.php" class="btn btn-primary">
            </i> Gestionar Tokens API
        </a>
        <a href="<?php echo BASE_URL; ?>views/api/Pruebas_api.php" class="btn btn-primary">
            </i> Pruebas api
        </a>
          </div>
    </div>

    <div class="card">
        <h3></i> Información de la Sesión</h3>
        <p><strong>ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Rol:</strong> <?php echo strtoupper($_SESSION['rol'] ?? 'ADMIN'); ?></p>
    </div>

    <?php if (!empty($hotelesRecientes)): ?>
        <div class="card">
            <h3></i> Hoteles Registrados Recientemente</h3>
            <table>
                <thead>
                    <tr>
                        <th></i> Nombre</th>
                        <th></i> Dirección</th>
                        <th></i> Teléfono</th>
                        <th></i> Email</th>
                     </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($hotelesRecientes) as $hotel): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($hotel['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($hotel['email']); ?></td>
                         </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<?php require_once __DIR__ . '/include/footer.php'; ?>
