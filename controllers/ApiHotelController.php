<?php
require_once __DIR__ . '/../models/TokenApi.php';
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../models/ClientApi.php';

class ApiHotelController
{
    private $tokenApiModel;
    private $hotelModel;
    private $clientApiModel;

    public function __construct()
    {
        $this->tokenApiModel = new TokenApi();
        $this->hotelModel = new Hotel();
        $this->clientApiModel = new ClientApi();
    }

    /**
     * Busca hoteles por token válido y nombre o código.
     */
    public function buscarHotelesPorTokenYNombreOCodigo($token, $nombre = '', $codigo = '')
    {
        // Validar formato del token
        $token_arr = explode("-", $token);
        if (count($token_arr) < 3) {
            return ['status' => false, 'msg' => 'Token inválido.'];
        }

        //  Verificar que el token exista y esté activo
        $conexion = $this->tokenApiModel->getConexion();
        $stmt = $conexion->prepare("
            SELECT t.*, c.estado AS cliente_estado
            FROM tokens_api t
            JOIN client_api c ON t.id_client_api = c.id
            WHERE t.token = ? AND t.estado = 1 AND c.estado = 1
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $token_data = $resultado->fetch_assoc();

        if (!$token_data) {
            return ['status' => false, 'msg' => 'Token no válido o cliente inactivo.'];
        }

        // Preparar la búsqueda en la tabla de hoteles
        $conexion = $this->hotelModel->getConexion();

        $query = "
            SELECT id, codigo, nombre, direccion, ciudad
            FROM hoteles
            WHERE 1=1
        ";

        $params = [];
        $types = "";

        if (!empty($nombre)) {
            $query .= " AND nombre LIKE ?";
            $params[] = "%" . $nombre . "%";
            $types .= "s";
        }

        if (!empty($codigo)) {
            $query .= " AND codigo LIKE ?";
            $params[] = "%" . $codigo . "%";
            $types .= "s";
        }

        $stmt = $conexion->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $resultado = $stmt->get_result();

        $hoteles = [];
        while ($fila = $resultado->fetch_assoc()) {
            unset($fila['correo'], $fila['telefono']); // Por seguridad
            $hoteles[] = $fila;
        }

        // 4️ Formatear la respuesta
        return [
            'status' => true,
            'msg' => 'Búsqueda completada correctamente.',
            'cliente' => $token_data['id_client_api'],
            'hotel' => $hoteles
        ];
    }
}
?>
