-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2024 a las 00:53:58
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
-- Estructura de tabla para la tabla `detalledevoluciones`
--

CREATE TABLE `detalledevoluciones` (
  `idDetalleDevolucion` int(11) NOT NULL,
  `fechaHora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalledevoluciones`
--

INSERT INTO `detalledevoluciones` (`idDetalleDevolucion`, `fechaHora`) VALUES
(5, '2024-11-09 18:17:48'),
(6, '2024-11-09 19:13:35'),
(7, '2024-11-09 19:23:18');

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
  `codBarras` varchar(100) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idTipoDevolucion` int(11) NOT NULL,
  `idDetalleDevolucion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `devoluciones`
--

INSERT INTO `devoluciones` (`idDevolucion`, `codBejerman`, `descripcion`, `partida`, `cantidad`, `codBarras`, `idUsuario`, `idTipoDevolucion`, `idDetalleDevolucion`) VALUES
(15, '', 'leche', '13', 50, '7798085681643', 17, 1, 5),
(16, '', 'leche', '586', 40, '7798085681643', 17, 1, 5),
(17, '', 'leche', '66', 66, '7798085681643', 17, 1, 6),
(18, '', 'leche', '45645', 55, '7798085681643', 17, 1, 6),
(19, '', 'leche', '534', 345345, '7798085681643', 17, 1, 6),
(20, '', 'leche', '53345', 345354, '7798085681643', 17, 1, 6),
(21, '', 'leche', '234', 234, '7798085681643', 17, 1, 6),
(22, '', 'leche', '34234', 4, '7798085681643', 17, 1, 6),
(23, '', 'leche', '4', 234, '7798085681643', 17, 1, 7),
(24, '', 'leche', '43', 3242, '7798085681643', 17, 1, 7),
(25, '', 'leche', '234', 43, '7798085681643', 17, 1, 7),
(26, '', 'leche', '2342', 23423, '7798085681643', 17, 1, 7),
(27, '', 'leche', '423', 23423, '7798085681643', 17, 1, 7),
(28, '', 'leche', '32443', 2423, '7798085681643', 17, 1, 7),
(29, '', 'leche', '423423', 3423, '7798085681643', 17, 1, 7),
(30, '', 'leche', '4234', 2423, '7798085681643', 17, 1, 7),
(31, '', 'leche', '23', 12, '7798085681643', 17, 1, 7),
(32, '', 'leche', '2131', 21312, '7798085681643', 17, 1, 7),
(33, '', 'leche', '23123', 1312, '7798085681643', 17, 1, 7),
(34, '', 'leche', '2313', 12312, '7798085681643', 17, 1, 7),
(35, '', 'leche', '234234', 234234, '7798085681643', 17, 1, 7),
(36, '', 'leche', '234234', 2342, '7798085681643', 17, 1, 7),
(37, '', 'leche', '4234', 423, '7798085681643', 17, 1, 7),
(38, '', 'leche', '43234', 23423, '7798085681643', 17, 1, 7),
(39, '', 'leche', '32423', 423, '7798085681643', 17, 1, 7);

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
(1, '7798085681643', 'leche');

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
(1, 'Locales'),
(2, 'Preventa');

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
(17, '', '', 'prueba', '', '$2y$10$zlNYP0T5CqwwzBlu2/uli.U0ql0vG7HZ3GuL0wUqyHgUQ7iNCHcBS', '', 1),
(18, '', '', 'prueba2', '', '$2y$10$v0qo.MeZObDctmwRJ.ime.QR2SiubZ5PYiPji0BQ29TLjwy/bzmdS', '', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `detalledevoluciones`
--
ALTER TABLE `detalledevoluciones`
  ADD PRIMARY KEY (`idDetalleDevolucion`);

--
-- Indices de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD PRIMARY KEY (`idDevolucion`),
  ADD KEY `idTipoDevolucion` (`idTipoDevolucion`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idTipoDevolucion_2` (`idTipoDevolucion`),
  ADD KEY `idDetalleDevolucion` (`idDetalleDevolucion`);

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
-- AUTO_INCREMENT de la tabla `detalledevoluciones`
--
ALTER TABLE `detalledevoluciones`
  MODIFY `idDetalleDevolucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  MODIFY `idDevolucion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `idUsuario` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `devoluciones`
--
ALTER TABLE `devoluciones`
  ADD CONSTRAINT `devoluciones_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`),
  ADD CONSTRAINT `devoluciones_ibfk_3` FOREIGN KEY (`idTipoDevolucion`) REFERENCES `tipodevoluciones` (`idDevolucion`),
  ADD CONSTRAINT `devoluciones_ibfk_4` FOREIGN KEY (`idDetalleDevolucion`) REFERENCES `detalledevoluciones` (`idDetalleDevolucion`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`idTipoUsuario`) REFERENCES `tipousuarios` (`idTipoUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
