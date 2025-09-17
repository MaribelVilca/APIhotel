-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-09-2025 a las 06:08:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `api_hotel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoteles`
--

CREATE TABLE `hoteles` (
  `id_hotel` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `historia` text NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `precio_promedio` varchar(50) DEFAULT NULL,
  `servicios` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `hoteles`
--

INSERT INTO `hoteles` (`id_hotel`, `nombre`, `direccion`, `ubicacion`, `historia`, `telefono`, `email`, `precio_promedio`, `servicios`, `imagen`, `created_at`) VALUES
(1, 'Hotel Emperador', 'Jr. Ayacucho 321, Huanta, Ayacucho', 'https://maps.app.goo.gl/c1SAArMeB1ZDE9Pz6', 'El Hotel Emperador es uno de los más antiguos de Huanta. Fundado en los años 70, ha hospedado a viajeros de todo el Perú y destaca por su arquitectura tradicional.', '066-321456', 'contacto@hotelemperador.com', '40', 'WiFi gratis, Desayuno, Estacionamiento, Restaurante', 'https://example.com/img/imperador.jpg', '2025-09-09 02:15:26'),
(2, 'Hotel Huanta Real', 'Av. Mariscal Cáceres 456, Huanta, Ayacucho', 'https://maps.google.com/?q=-12.940000,-74.245000', 'El Huanta Real combina modernidad con tradición. Su diseño contemporáneo lo convierte en una de las opciones preferidas por turistas nacionales e internacionales.', '066-654321', 'reservas@huantareal.com', '150.00', 'Piscina, WiFi gratis, Bar, Estacionamiento', 'https://example.com/img/huanta_real.jpg', '2025-09-09 02:15:26'),
(3, 'Hostal Los Andes', 'Jr. Bolívar 178, Huanta, Ayacucho', 'https://maps.google.com/?q=-12.941200,-74.246500', 'El Hostal Los Andes ofrece un ambiente familiar y acogedor. Es conocido por su cercanía al centro histórico y su hospitalidad.', '066-222333', 'info@hostallosandes.com', '80.00', 'WiFi gratis, Desayuno continental', 'https://example.com/img/los_andes.jpg', '2025-09-09 02:15:26'),
(4, 'Hotel Colonial Huanta', 'Plaza de Armas 101, Huanta, Ayacucho', 'https://maps.google.com/?q=-12.942800,-74.247200', 'Ubicado en plena Plaza de Armas, el Hotel Colonial Huanta se destaca por su estilo republicano y por mantener vivas las tradiciones locales.', '066-777888', 'colonial@huanta.com', '110.00', 'WiFi gratis, Restaurante, Terraza con vista', 'https://example.com/img/colonial.jpg', '2025-09-09 02:15:26'),
(5, 'Hotel Turístico Ayacucho', 'Carretera Huanta - Ayacucho Km 1', 'https://maps.google.com/?q=-12.945000,-74.250000', 'Rodeado de naturaleza, el Hotel Turístico Ayacucho es ideal para quienes buscan descanso y tranquilidad, con hermosas vistas de los valles de Huanta.', '066-999000', 'reservas@turisticoayacucho.com', '1430.00', 'WiFi gratis, Piscina, Estacionamiento, Spa', 'https://example.com/img/turistico.jpg', '2025-09-09 02:15:26'),
(6, 'admin\' OR \'1\'=\'1', 'Av.jose olaya 215', 'https://maps.google.com/?q=-12.945000,-74.250000', 'dawdfafaw', '54325230', 'julianore79@gmail.com', '30.00', 'wifi', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDd_AEjFnkaM0ZJtW9UXVojPzeH2wYzNIwtQ&s', '2025-09-09 02:39:00'),
(7, 'AS', 'Luricocha', 'https://maps.google.com/?q=-12.945000,-74.250000', 'sad', '5727', 'julianore79@gmail.com', '3123.00', 'sd', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDd_AEjFnkaM0ZJtW9UXVojPzeH2wYzNIwtQ&s', '2025-09-09 02:48:21'),
(9, 'LAJS', 'Luricocha', 'https://maps.google.com/?q=-12.945000,-74.250000', 'a', '54325230', 'julianore79@gmail.com', '21.00', 'w', 'https://images-cdn.ubuy.co.in/65701a31ecc094123e253c0a-30-sheets-thin-mdf-wood-boards-for.jpg', '2025-09-09 02:49:03'),
(10, 'Polos', 'admin', 'https://maps.google.com/?q=-12.945000,-74.250000', 'w', '7741', 'julianore79@gmail.com', '33.00', '2', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDd_AEjFnkaM0ZJtW9UXVojPzeH2wYzNIwtQ&s', '2025-09-09 02:49:22'),
(11, 'MODULO I', 'Carretera Huanta - Ayacucho Km 1', 'https://maps.google.com/?q=-12.945000,-74.250000', 'ed', '54325230', 'admin@gmail.com', '33.00', '22', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTDd_AEjFnkaM0ZJtW9UXVojPzeH2wYzNIwtQ&s', '2025-09-09 02:49:44'),
(12, 'Polos', 'Av.jose olaya 215', 'https://maps.app.goo.gl/gYFvyd6R8xsDjuZTA', 'adfad', '272782', 'admin@gmail.com', '30', 'WiFi gratis', 'https://arcosac.com/wp-content/uploads/2022/10/TABMDF002001-AL-09-MDF-FIBROFACIL-MASISA-2-390x293.jpg', '2025-09-09 03:31:16'),
(13, 'GRAN HOTEL IMPERIAL HUANTA', 'Av.jose olaya 215', 'https://maps.app.goo.gl/k7PDrGtgNby59VXT7', 'ac', '54325230', 'majid@gmail.com', '30.00', 'Desayuno, Estacionamiento, Restaurante, Piscina', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQWB4SXmGDDHYNPKoLOCbvTwB_E0ZVC7UbWYg&s', '2025-09-09 03:45:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(120) NOT NULL,
  `rol` enum('admin') DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `nombre_completo`, `rol`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$a4qsOmIrUcXN4ptudcU57uOQ7li/aLuuuRedYHOb1YoBnoRQsWgPi', 'Administrador General', 'admin', '2025-09-09 02:15:26', '2025-09-09 02:15:26'),
(2, 'maribel', '$2y$10$Wx9Iy0F4ecCVCiSalYmqR.EWGAeKqVBmvjTje3bCGGrqj55XrlFui', 'Maribel', 'admin', '2025-09-09 02:41:22', '2025-09-09 02:41:22');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/APIHOTEL
│── /config
│    └── database.php            # conexión a la base de datos
│
│── /controllers
│    └── AuthController.php      # login (ya lo tienes)
│    └── HotelController.php     # CRUD + búsqueda de hoteles
│
│── /models
│    └── Usuario.php             # modelo usuarios
│    └── Hotel.php               # modelo hoteles
│
│── /views
│    ├── include/
│    │     ├── header.php
│    │     └── footer.php
│    ├── login.php               # vista login
│    ├── dashboard.php           # panel de admin
│    ├── hoteles_list.php        # listar hoteles
│    └── hotel_form.php          # formulario crear/editar hoteles
│
│── /public
│    ├── index.php               # enrutador frontal (endpoints API REST)
│    ├── css/
│    └── js/
│
└── index.php                    # redirige a /public/index.php
└── .htaccess                    # urls amigables

-- ================================
