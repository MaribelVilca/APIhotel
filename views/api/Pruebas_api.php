<?php
require_once __DIR__ . '/../../controllers/TokenApiController.php';
$tokenController = new TokenApiController();
$tokens = $tokenController->listarTokens();

// Asumimos que tomas el primer token de la lista (puedes cambiar esto si necesitas otro)
$tokenActual = !empty($tokens) ? $tokens[0]['token'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Hotel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d6e9f9; /* azul pálido */
        }
        .card-Hotel {
            border-left: 4px solid #66c5ea;
            margin-bottom: 1rem;
        }
        .card-Hotel .card-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">API Hotel</h2>

        <!-- Configuración -->
        <div class="card shadow">
            <div class="card-header">
                <h5>Configuración</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="tokenInput" class="form-label">Token actual:</label>
                    <input type="text" class="form-control" id="tokenInput" value="<?php echo htmlspecialchars($tokenActual); ?>" readonly>
                    <small class="text-muted"></small>
                </div>

                <div class="mb-3">
                    <label for="codigoTokenInput" class="form-label">Código del Token:</label>
                    <input type="text" class="form-control" id="codigoTokenInput" placeholder=" 4de5174899a9d384e9f9a5136f8b0ecf-20251023-04">
                </div>

                <div class="mb-3">
                    <label for="searchInput" class="form-label">Buscar por nombre del hotel:</label>
                    <input type="text" class="form-control" id="searchInput" placeholder=" Hotel Emperador">
                </div>

                <button class="btn btn-primary w-100" onclick="buscarHotel()">Buscar</button>
            </div>
        </div>

        <div class="mt-4" id="resultadosContainer"></div>
    </div>

    <script>
        async function buscarHotel() {
            const nombre = document.getElementById('searchInput').value.trim();
            const tokenCodigo = document.getElementById('codigoTokenInput').value.trim();
            const tokenActual = document.getElementById('tokenInput').value.trim();

            if (!nombre && !tokenCodigo) {
                alert('Por favor, ingresa el nombre del hotel o el código del token.');
                return;
            }

            const tokenFinal = tokenCodigo || tokenActual;

            try {
                const response = await fetch(
                    `../../public/api.php?action=buscar_hoteles&token=${encodeURIComponent(tokenFinal)}&search=${encodeURIComponent(nombre)}`
                );
                const data = await response.json();

                if (!data.status) {
                    alert(data.msg || 'No se encontró información.');
                    return;
                }

                mostrarResultados(data.hotel);
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al buscar.');
            }
        }

        function mostrarResultados(hoteles) {
            const container = document.getElementById('resultadosContainer');
            container.innerHTML = '';

            if (!hoteles || hoteles.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No se encontraron hoteles.</div>';
                return;
            }

            hoteles.forEach(hotel => {
                const card = document.createElement('div');
                card.className = 'card card-Hotel shadow-sm';
                card.innerHTML = `
                    <div class="card-header">${hotel.nombre}</div>
                    <div class="card-body">
                        <p><strong>ID:</strong> ${hotel.id}</p>
                        <p><strong>Dirección:</strong> ${hotel.direccion || 'No especificada'}</p>
                        <p><strong>Ciudad:</strong> ${hotel.ciudad || 'No especificada'}</p>
                        <p><strong>Teléfono:</strong> ${hotel.telefono || 'No disponible'}</p>
                    </div>
                `;
                container.appendChild(card);
            });
        }
    </script>
</body>
</html>
