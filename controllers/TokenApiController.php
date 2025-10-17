<?php
require_once __DIR__ . '/../models/TokenApi.php';
require_once __DIR__ . '/../models/ClientApi.php';

class TokenApiController
{
    private $tokenApiModel;
    private $clientApiModel;

    public function __construct()
    {
        $this->tokenApiModel = new TokenApi();
        $this->clientApiModel = new ClientApi();
    }

    public function getConexion()
    {
        return $this->tokenApiModel->getConexion();
    }

    // Listar todos los tokens
    public function listarTokens()
    {
        return $this->tokenApiModel->obtenerTokens();
    }

    // Obtener un token por ID
    public function obtenerToken($id)
    {
        return $this->tokenApiModel->obtenerTokenPorId($id);
    }

    // Obtener tokens por cliente
    public function obtenerTokensPorCliente($id_client_api)
    {
        return $this->tokenApiModel->obtenerTokensPorCliente($id_client_api);
    }

    // Crear un nuevo token
    public function crearToken($id_client_api)
    {
        $token = $this->tokenApiModel->generarToken($id_client_api);
        return $this->tokenApiModel->guardarToken($id_client_api, $token);
    }

    // Cambiar estado de un token
    public function cambiarEstadoToken($id, $nuevoEstado)
    {
        return $this->tokenApiModel->actualizarToken($id, $nuevoEstado);
    }

    // Eliminar un token
    public function borrarToken($id)
    {
        return $this->tokenApiModel->eliminarToken($id);
    }

    // Obtener clientes para el select
    public function obtenerClientes()
    {
        return $this->clientApiModel->obtenerClientes();
    }

    // Buscar tokens por nombre de cliente
    public function buscarTokensPorNombre($nombre)
    {
        $nombre = "%" . $nombre . "%";
        $stmt = $this->tokenApiModel->getConexion()->prepare("
            SELECT t.*, c.razon_social
            FROM tokens_api t
            JOIN client_api c ON t.id_client_api = c.id
            WHERE c.razon_social LIKE ?
        ");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tokens = [];
        while ($fila = $resultado->fetch_assoc()) {
            $tokens[] = $fila;
        }
        return $tokens;
    }
}
?>
