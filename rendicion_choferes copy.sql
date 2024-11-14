-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 14-11-2024 a las 18:27:21
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wol`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rendicion_choferes`
--

CREATE TABLE `rendicion_choferes` (
  `id` int(11) NOT NULL,
  `chofer_id` int(11) NOT NULL,
  `rela_vendedor` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `total_efectivo` decimal(10,2) DEFAULT 0.00,
  `total_transferencias` decimal(10,2) DEFAULT 0.00,
  `total_mercadopago` decimal(10,2) DEFAULT 0.00,
  `total_cheques` decimal(10,2) DEFAULT 0.00,
  `total_fiados` decimal(10,2) DEFAULT 0.00,
  `total_gastos` decimal(10,2) DEFAULT 0.00,
  `pago_secretario` decimal(10,2) DEFAULT 0.00,
  `total_general` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_menos_gastos` decimal(10,2) DEFAULT 0.00,
  `billetes_10000` int(11) DEFAULT 0,
  `billetes_2000` int(11) DEFAULT 0,
  `billetes_1000` int(11) DEFAULT 0,
  `billetes_500` int(11) DEFAULT 0,
  `billetes_200` int(11) DEFAULT 0,
  `billetes_100` int(11) DEFAULT 0,
  `billetes_50` int(11) DEFAULT 0,
  `billetes_20` int(11) DEFAULT 0,
  `billetes_10` int(11) DEFAULT 0,
  `total_mec_faltante` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_rechazos` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `rendicion_choferes`
--
ALTER TABLE `rendicion_choferes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chofer_id` (`chofer_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `rendicion_choferes`
--
ALTER TABLE `rendicion_choferes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `rendicion_choferes`
--
ALTER TABLE `rendicion_choferes`
  ADD CONSTRAINT `rendicion_choferes_ibfk_1` FOREIGN KEY (`chofer_id`) REFERENCES `choferes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
