<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Hoteles</title>
    <!-- Estilos globales -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: #f4f6f8;
            color: #333;
        }
        header {
            background: #6c8ea4;
            color: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 {
            font-size: 1.4rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        nav {
            display: flex;
            gap: 15px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.3s ease;
        }
        nav a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-hotel"></i> API Hoteles 2025</h1>
        <nav>
            <a href="<?php echo BASE_URL; ?>views/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="<?php echo BASE_URL; ?>views/hoteles_list.php"><i class="fas fa-building"></i> Hoteles</a>
            <a href="#" onclick="logout(); return false;"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
        </nav>
    </header>

    <script>
        function logout() {
            if (confirm('¿Estás seguro de que deseas cerrar sesión?')) {
                window.location.href = '<?php echo BASE_URL; ?>logout.php';
            }
        }
    </script>
