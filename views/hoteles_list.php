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
    require_once __DIR__ . '/../controllers/HotelController.php';
    $hotelController = new HotelController();
    if ($hotelController->borrarHotel($_GET['delete'])) {
        $mensaje = "✅ Hotel eliminado exitosamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "❌ Error al eliminar el hotel";
        $tipo_mensaje = "error";
    }
}

// Obtener lista de hoteles
require_once __DIR__ . '/../controllers/HotelController.php';
$hotelController = new HotelController();

// Configuración de paginación
$hotelesPorPagina = 10;
$totalHoteles = $hotelController->contarHoteles();
$totalPaginas = ceil($totalHoteles / $hotelesPorPagina);
$paginaActual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($paginaActual - 1) * $hotelesPorPagina;

// Filtros de búsqueda
$filtroNombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$filtroServicio = isset($_GET['servicio']) ? trim($_GET['servicio']) : '';

// Obtener todos los hoteles para aplicar los filtros
$todosLosHoteles = $hotelController->listarHoteles();

// Aplicar filtros
$hotelesFiltrados = [];
foreach ($todosLosHoteles as $hotel) {
    $coincideNombre = empty($filtroNombre) || stripos($hotel['nombre'], $filtroNombre) !== false;
    $coincideServicio = empty($filtroServicio) || stripos($hotel['servicios'], $filtroServicio) !== false;
    if ($coincideNombre && $coincideServicio) {
        $hotelesFiltrados[] = $hotel;
    }
}

// Calcular paginación para los hoteles filtrados
$totalHotelesFiltrados = count($hotelesFiltrados);
$totalPaginasFiltradas = ceil($totalHotelesFiltrados / $hotelesPorPagina);

// Obtener los hoteles de la página actual después de aplicar los filtros
$hotelesPaginados = array_slice($hotelesFiltrados, $offset, $hotelesPorPagina);

require_once __DIR__ . '/include/header.php';
?>

<style>
    .contenedor {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 1rem;
    }

    .mensaje {
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .mensaje.success { background: #e9f7ef; color: #1e5631; border: 1px solid #c7e6d5; }
    .mensaje.error { background: #fdecea; color: #b23b3b; border: 1px solid #f5c6cb; }

    h3 {
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .acciones {
        margin: 1rem 0;
    }
    .acciones a {
        background: #6c8ea4;
        color: #fff;
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.3s;
    }
    .acciones a:hover { background: #5a758a; }

    form.filtros {
        margin: 1.5rem 0;
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        align-items: flex-end;
    }
    form.filtros label {
        display: block;
        font-weight: 500;
        margin-bottom: 4px;
    }
    form.filtros input {
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    form.filtros button {
        background: #27ae60;
        color: #fff;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    form.filtros a {
        background: #c0392b;
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
    }

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
        border-bottom: 1px solid #e0e6ed;
        text-align: left;
        font-size: 0.95rem;
    }
    table th {
        background: #f0f3f6;
        color: #2c3e50;
    }
    table tr:hover { background: #f9fafb; }

    .acciones-tabla a {
        margin-right: 8px;
        text-decoration: none;
        color: #2980b9;
        font-weight: 500;
    }
    .acciones-tabla a.eliminar { color: #c0392b; }

    .paginacion {
        margin-top: 1.5rem;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .paginacion a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 4px;
        background: #ecf0f1;
        color: #2c3e50;
        font-weight: 500;
    }
    .paginacion a:hover { background: #bdc3c7; }
    .paginacion a.activa {
        background: #6c8ea4;
        color: #fff;
    }
</style>

<div class="contenedor">
    <?php if (isset($mensaje)): ?>
        <div class="mensaje <?= $tipo_mensaje; ?>">
            <i class="fas <?= $tipo_mensaje == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <div class="acciones">
        <h3><i class="fas fa-building"></i> Gestión de Hoteles</h3>
        <a href="<?php echo BASE_URL; ?>views/hotel_form.php"><i class="fas fa-plus-circle"></i> Agregar Nuevo Hotel</a>
    </div>

    <!-- Filtros de búsqueda -->
    <form method="GET" action="<?php echo BASE_URL; ?>views/hoteles_list.php" class="filtros">
        <input type="hidden" name="pagina" value="1">
        <div>
            <label for="nombre"><i class="fas fa-search"></i> Buscar por nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($filtroNombre); ?>" placeholder="Ingrese nombre del hotel">
        </div>
        <div>
            <label for="servicio"><i class="fas fa-concierge-bell"></i> Buscar por servicio:</label>
            <input type="text" id="servicio" name="servicio" value="<?php echo htmlspecialchars($filtroServicio); ?>" placeholder="Ingrese servicio">
        </div>
        <button type="submit"><i class="fas fa-filter"></i> Buscar</button>
        <a href="<?php echo BASE_URL; ?>views/hoteles_list.php"><i class="fas fa-times"></i> Limpiar</a>
    </form>

    <?php if (empty($hotelesPaginados)): ?>
        <div class="mensaje error">
            <i class="fas fa-info-circle"></i> No se encontraron hoteles.
            <a href="<?php echo BASE_URL; ?>views/hotel_form.php" style="margin-left:10px; color:#27ae60;">
                <i class="fas fa-plus-circle"></i> Agregar Primer Hotel
            </a>
        </div>
    <?php else: ?>
        <table id="hotelesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th><i class="fas fa-hotel"></i> Nombre</th>
                    <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                    <th><i class="fas fa-map"></i> Ubicación</th>
                    <th><i class="fas fa-book"></i> Historia</th>
                    <th><i class="fas fa-phone"></i> Teléfono</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-dollar-sign"></i> Precio Promedio</th>
                    <th><i class="fas fa-concierge-bell"></i> Servicios</th>
                    <th><i class="fas fa-image"></i> Imagen</th>
                    <th><i class="fas fa-cogs"></i> Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $contador = $offset + 1; ?>
                <?php foreach ($hotelesPaginados as $hotel): ?>
                    <tr>
                        <td><?php echo $contador++; ?></td>
                        <td><?php echo htmlspecialchars($hotel['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['direccion']); ?></td>
                        <td>
                            <?php if (!empty($hotel['ubicacion'])): ?>
                                <a href="<?php echo htmlspecialchars($hotel['ubicacion']); ?>" target="_blank">
                                    <i class="fas fa-map-pin"></i> Ver en mapa
                                </a>
                            <?php else: ?>
                                Sin ubicación
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars(substr($hotel['historia'], 0, 50)) . (strlen($hotel['historia']) > 50 ? '...' : ''); ?></td>
                        <td><?php echo htmlspecialchars($hotel['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['email']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['precio_promedio']); ?></td>
                        <td><?php echo htmlspecialchars($hotel['servicios']); ?></td>
                        <td>
                            <?php if (!empty($hotel['imagen'])): ?>
                                <img src="<?php echo htmlspecialchars($hotel['imagen']); ?>" alt="Imagen del hotel" width="100">
                            <?php else: ?>
                                Sin imagen
                            <?php endif; ?>
                        </td>
                        <td class="acciones-tabla">
                            <a href="<?php echo BASE_URL; ?>views/hotel_form.php?edit=<?php echo $hotel['id_hotel']; ?>">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="#" class="eliminar" onclick="confirmarEliminacion(<?php echo $hotel['id_hotel']; ?>, '<?php echo addslashes($hotel['nombre']); ?>', <?php echo $paginaActual; ?>, '<?php echo urlencode($filtroNombre); ?>', '<?php echo urlencode($filtroServicio); ?>')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="paginacion">
            <?php if ($paginaActual > 1): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $paginaActual - 1; ?><?php echo (!empty($filtroNombre) || !empty($filtroServicio)) ? '&nombre=' . urlencode($filtroNombre) . '&servicio=' . urlencode($filtroServicio) : ''; ?>">
                    <i class="fas fa-arrow-left"></i> Anterior
                </a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginasFiltradas; $i++): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $i; ?><?php echo (!empty($filtroNombre) || !empty($filtroServicio)) ? '&nombre=' . urlencode($filtroNombre) . '&servicio=' . urlencode($filtroServicio) : ''; ?>" class="<?= $i === $paginaActual ? 'activa' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginasFiltradas): ?>
                <a href="<?php echo BASE_URL; ?>views/hoteles_list.php?pagina=<?php echo $paginaActual + 1; ?><?php echo (!empty($filtroNombre) || !empty($filtroServicio)) ? '&nombre=' . urlencode($filtroNombre) . '&servicio=' . urlencode($filtroServicio) : ''; ?>">
                    Siguiente <i class="fas fa-arrow-right"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmarEliminacion(id, nombre, pagina, filtroNombre, filtroServicio) {
        if (confirm(`¿Estás seguro de que deseas eliminar el hotel "${nombre}"?`)) {
            window.location.href = `<?php echo BASE_URL; ?>views/hoteles_list.php?delete=${id}&pagina=${pagina}&nombre=${filtroNombre}&servicio=${filtroServicio}`;
        }
    }
</script>

<?php require_once __DIR__ . '/include/footer.php'; ?>
