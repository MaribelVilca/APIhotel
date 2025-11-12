<?php
require_once __DIR__ . '/../models/Hotel.php';

class HotelController {
    private $hotelModel;

    public function __construct() {
        $this->hotelModel = new Hotel();
    }

    public function getConexion() {
        return $this->hotelModel->getConexion();
    }

    public function listarHoteles($limit = null, $offset = null) {
        if ($limit !== null && $offset !== null) {
            $query = "SELECT * FROM hoteles LIMIT $limit OFFSET $offset";
            $resultado = $this->hotelModel->getConexion()->query($query);
            $hoteles = [];
            while ($fila = $resultado->fetch_assoc()) {
                $hoteles[] = $fila;
            }
            return $hoteles;
        } else {
            return $this->hotelModel->obtenerHoteles();
        }
    }

    public function obtenerHotel($id) {
        return $this->hotelModel->obtenerHotelPorId($id);
    }

    public function crearHotel($nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen) {
        return $this->hotelModel->guardarHotel($nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen);
    }

    public function editarHotel($id, $nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen) {
        return $this->hotelModel->actualizarHotel($id, $nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen);
    }

    public function borrarHotel($id) {
        return $this->hotelModel->eliminarHotel($id);
    }

    public function contarHoteles() {
        $resultado = $this->hotelModel->getConexion()->query("SELECT COUNT(*) as total FROM hoteles");
        return $resultado->fetch_assoc()['total'];
    }
   public function buscarHoteles($search)
{
    $search = "%" . $this->getConexion()->real_escape_string($search) . "%";
    $query = "
        SELECT *
        FROM hoteles
        WHERE nombre LIKE ?
           OR servicios LIKE ?
           OR direccion LIKE ?
           OR ubicacion LIKE ?
        ORDER BY
            CASE
                WHEN nombre LIKE ? THEN 1
                WHEN servicios LIKE ? THEN 2
                WHEN direccion LIKE ? THEN 3
                ELSE 4
            END,
            nombre
    ";
    $stmt = $this->hotelModel->getConexion()->prepare($query);
    $stmt->bind_param("sssssss", $search, $search, $search, $search, $search, $search, $search);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $hoteles = [];
    while ($fila = $resultado->fetch_assoc()) {
        $hoteles[] = $fila;
    }
    return $hoteles;
}


}
?>
