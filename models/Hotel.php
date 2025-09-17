<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';

class Hotel {
    private $conexion;

    public function __construct() {
        $this->conexion = conectarDB();
    }

    public function getConexion() {
        return $this->conexion;
    }

    public function obtenerHoteles() {
        $query = "SELECT * FROM hoteles";
        $resultado = $this->conexion->query($query);
        $hoteles = [];
        while ($fila = $resultado->fetch_assoc()) {
            $hoteles[] = $fila;
        }
        return $hoteles;
    }

    public function obtenerHotelPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM hoteles WHERE id_hotel = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function guardarHotel($nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen) {
        $stmt = $this->conexion->prepare("INSERT INTO hoteles (nombre, direccion, ubicacion, historia, telefono, email, precio_promedio, servicios, imagen) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssdss", $nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        return false;
    }

    public function actualizarHotel($id, $nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen) {
        $stmt = $this->conexion->prepare("UPDATE hoteles SET nombre=?, direccion=?, ubicacion=?, historia=?, telefono=?, email=?, precio_promedio=?, servicios=?, imagen=? WHERE id_hotel=?");
        $stmt->bind_param("ssssssdssi", $nombre, $direccion, $ubicacion, $historia, $telefono, $email, $precio_promedio, $servicios, $imagen, $id);
        return $stmt->execute();
    }

    public function eliminarHotel($id) {
        $stmt = $this->conexion->prepare("DELETE FROM hoteles WHERE id_hotel=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
