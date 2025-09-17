<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

// Verificar si ya hay sesión activa
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - API Docentes</title>
  <style>
   body {
  font-family: "Segoe UI", Roboto, sans-serif;
  background: linear-gradient(135deg, #eef2f3, #dfe6e9);
  margin: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  color: #333;
}

/* ===== LOGIN CARD ===== */
.auth-card {
  background: #ffffff;
  width: 100%;
  max-width: 380px;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.08);
  border: 1px solid #e0e6ed;
}

.auth-title {
  text-align: center;
  margin-bottom: 1.5rem;
  font-size: 1.6rem;
  font-weight: 600;
  color: #2d3436;
}

/* ALERTAS */
.alert {
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-size: 0.95rem;
}

.alert-success {
  background: #e9f7ef;
  color: #1e5631;
  border: 1px solid #c7e6d5;
}

.alert-error {
  background: #fdecea;
  color: #b23b3b;
  border: 1px solid #f5c6cb;
}

/* FORM */
.form-group {
  margin-bottom: 1.2rem;
}

.form-input {
  width: 100%;
  padding: 0.8rem;
  border: 1px solid #ccd1d9;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: #fafafa;
}

.form-input:focus {
  outline: none;
  border-color: #6c8ea4;
  box-shadow: 0 0 0 3px rgba(108,142,164,0.2);
  background: #fff;
}

.btn-submit {
  width: 100%;
  padding: 0.9rem;
  background: #6c8ea4;  /* azul grisáceo elegante */
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.1s;
}

.btn-submit:hover {
  background: #5a758a;
  transform: translateY(-2px);
}

.btn-submit:active {
  transform: translateY(0);
}

/* INFO EXTRA */
.test-info {
  margin-top: 1.5rem;
  font-size: 0.85rem;
  color: #666;
  text-align: center;
  line-height: 1.4;
}

  </style>
</head>
<body>
  <div class="auth-card">
    <h2 class="auth-title">Iniciar Sesión</h2>

    <!-- Mensajes -->
    <?php if (isset($_GET['logout']) && $_GET['logout'] == 1): ?>
      <div class="alert alert-success">
        Sesión cerrada exitosamente
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="alert alert-error">
         Usuario o contraseña incorrectos
      </div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>public/index.php?action=login" method="POST">
      <div class="form-group">
        <input type="text" name="username" class="form-input" placeholder="Usuario" value="" required>
      </div>
      <div class="form-group">
        <input type="password" name="password" class="form-input" placeholder="Contraseña" value="" required>
      </div>
      <button type="submit" class="btn-submit">Ingresar</button>
    </form>

    
  </div>
</body>
</html>