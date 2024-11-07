-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2024 a las 19:20:06
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
-- Base de datos: `wolchuk`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devoluciones`
--

CREATE TABLE `devoluciones` (
  `idDevolucion` int(11) NOT NULL,
  `codBejerman` varchar(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `partida` varchar(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `codBarras` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idTipoDevolucion` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodevoluciones`
--

CREATE TABLE `tipodevoluciones` (
  `idDevolucion` int(100) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodevoluciones`
--

INSERT INTO `tipodevoluciones` (`idDevolucion`, `descripcion`) VALUES
(1, 'Chofer'),
(2, 'Preventa'),
(3, 'Local'),
(4, 'Administracion'),
(5, 'Gerencia'),
(6, 'Deposito'),
(7, 'Sistemas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuarios`
--

CREATE TABLE `tipousuarios` (
  `idTipoUsuario` int(100) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipousuarios`
--

INSERT INTO `tipousuarios` (`idTipoUsuario`, `descripcion`) VALUES
(1, 'Locales'),
(2, 'Preventa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(100) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nombreUsuario` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(15) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `idTipoUsuario` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `nombreUsuario`, `correo`, `contrasena`, `descripcion`, `idTipoUsuario`) VALUES
(1, 'lucas', 'casadei', 'lucaa', 'lucas@gmail.com', 'lucas', 'lukaaaaaaaaaaa', 1),
(2, '', '', '', 'brisa@gmail.com', 'brisa', '', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`idDevolucion`),
  ADD KEY `idTipoDevolucion` (`idTipoDevolucion`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idTipoDevolucion_2` (`idTipoDevolucion`);

--
-- Indices de la tabla `tipodevoluciones`
--
ALTER TABLE `tipodevoluciones`
  ADD PRIMARY KEY (`idDevolucion`);

--
-- Indices de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  ADD PRIMARY KEY (`idTipoUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `idTipoUsuario` (`idTipoUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipodevoluciones`
--
ALTER TABLE `tipodevoluciones`
  MODIFY `idDevolucion` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipousuarios`
--
ALTER TABLE `tipousuarios`
  MODIFY `idTipoUsuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `devoluciones_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `devoluciones_ibfk_3` FOREIGN KEY (`idTipoDevolucion`) REFERENCES `tipodevoluciones` (`idDevolucion`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idTipoUsuario`) REFERENCES `tipousuarios` (`idTipoUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
