-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-11-2024 a las 01:53:48
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
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE `stock` (
  `idStock` int(11) NOT NULL,
  `codBarras` varchar(50) NOT NULL,
  `descripcion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock`
--

INSERT INTO `stock` (`idStock`, `codBarras`, `descripcion`) VALUES
(1, '7798085681643', 'leche'),
(2, '44444', 'pan');

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
  `usuario` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `contrasena` varchar(200) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `idTipoUsuario` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `usuario`, `correo`, `contrasena`, `descripcion`, `idTipoUsuario`) VALUES
(6, '', '', 'fg', '', '$2y$10$Op.fNY2CZVPS03BF9ptOpe/ESg0yB7vp8ptW65oa5fi', '', 1),
(7, '', '', '236', '', '$2y$10$W1lOB/Nen5.yAp9qpR7Eb.4y8UrZSnwAhOhlO/AFiIk', '', 1),
(8, '', '', 'asd', '', '$2y$10$MQTRppC6uTNuRqZuvcTJm.6KfIk1Wxl2FNHJqI4M8QbIULHnLTRIa', '', 2),
(9, '', '', 'asd22', '', '$2y$10$rcg8POEqsjVrH/s0dorlvObdTTXDJS/wbHokeGthnirpwy.q7gFX.', '', 2),
(10, '', '', 'bri3411  ', '', '$2y$10$NVRz9zRH4aZixRAkKf1hX.EtkC0p3WaoMKIKt9ATYKLTQp1ApPeUm', '', 1),
(11, '', '', 'gdfh', '', '$2y$10$4RpqeKCN1YazkmhSSB5jq.uIUmaGhMd7l7.TxHryy9GoaBFU/u9SG', '', 2),
(12, '', '', 'asdwqeq', '', '$2y$10$6zLbMbQFO6c2e1YEZHJmT.pyumXD7yTbAGcG1Uqo.i34.jD1/02tS', '', 2),
(13, '', '', 'dasas', '', '$2y$10$UP3YRYYtl8RBEytGrPTgcugu8eU9A3x2ih8ouJrLY4GWg2wk8Xzui', '', 2),
(14, '', '', 'asddsd', '', '$2y$10$YbYb1OTrmYXKA2FKRzNP0urtBMG.yLfn1yEyYGS8WdQ.ej2HfZKee', '', 2),
(15, '', '', 'bri', '', '$2y$10$XBqkWJpUuMeUmXLo/Z21D.KeNtu6GrYCr5HToKINlSeKfNf1Ki5lC', '', 1),
(16, '', '', 'asd423', '', '$2y$10$a8PrTxvwg5hm0vooIbjIXuz/Pp64sFKOyhW3IaaIc4JTnKZd8Lu5C', '', 2);

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
-- Indices de la tabla `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`idStock`);

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
-- AUTO_INCREMENT de la tabla `stock`
--
ALTER TABLE `stock`
  MODIFY `idStock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `idUsuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
