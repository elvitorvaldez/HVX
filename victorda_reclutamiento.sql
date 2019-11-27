-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-11-2019 a las 14:36:13
-- Versión del servidor: 10.1.43-MariaDB-cll-lve
-- Versión de PHP: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `victorda_reclutamiento`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Areas`
--

CREATE TABLE `Areas` (
  `clave` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Areas`
--

INSERT INTO `Areas` (`clave`, `nombre`) VALUES
(1, 'Acabados'),
(2, 'Compras'),
(3, 'Contabilidad'),
(4, 'Dirección General'),
(5, 'Diseño Gráfico'),
(6, 'Ensamble'),
(7, 'Finanzas'),
(8, 'Girecco'),
(9, 'Informática y Sistemas'),
(10, 'Ingeniería y Desarrollo de Nuevos Productos'),
(11, 'Mantenimiento de equipos'),
(12, 'Marketing Digital'),
(13, 'Nómina'),
(14, 'Recursos Humanos'),
(15, 'Seguridad e Higiene'),
(16, 'Servicios Generales'),
(17, 'Ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Candidatos`
--

CREATE TABLE `Candidatos` (
  `idCandidato` int(11) NOT NULL COMMENT 'Id de tabla candidatos',
  `Nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre completo del candidato',
  `PuestoDeseado` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Puesto al que aspira el candidato',
  `TelefonoLocal` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Telefono local del candidato',
  `Celular` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Telefono celular del candidato',
  `Escolaridad` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Escolaridad del candidato',
  `CV` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ruta del CV',
  `status` int(11) NOT NULL COMMENT 'estatus de la solicitud'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Candidatos`
--

INSERT INTO `Candidatos` (`idCandidato`, `Nombre`, `PuestoDeseado`, `TelefonoLocal`, `Celular`, `Escolaridad`, `CV`, `status`) VALUES
(1, 'Víctor David Valdez Guerrero', 'Desarrollador Php', '5576653987', '5576653987', 'Ingeniería de Desarrollo de Sofware', 'CV Desarrolladot PHP Sr y Project Manager Jr.pdf', 5),
(2, 'Isaias Morales Rodriguez', 'Cobrador', '0146113136', 'aaaaa', 'Preparatoria', 'DPRN3_U2_A1_JOMR.pdf', 5),
(4, 'Víctor David Valdez Guerrero', 'Desarrollador Php', '65896445', '5576653987', 'Ingeniería en Sistemas Computacionales', 'CV Desarrollador PHP y PM Jr - Victor Valdez.pdf', 5),
(5, '1', '1', '12345678', '3222222222', 'ing', '2019-2Carta_Compromiso_PT.pdf', 6),
(6, 'david guerrero', 'Analista', '55789654', '5576653987', 'IDS', 'VICTOR DAVID VALDEZ GUERRERO - DS-DPT2.pdf', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Roles`
--

CREATE TABLE `Roles` (
  `id` int(11) NOT NULL COMMENT 'id de rol',
  `Rol` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Roles`
--

INSERT INTO `Roles` (`id`, `Rol`) VALUES
(1, 'Administrador'),
(2, 'Gerente RH'),
(3, 'Reclutador'),
(4, 'Cliente Interno Jefe'),
(5, 'Cliente Interno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Status`
--

CREATE TABLE `Status` (
  `idStatus` int(11) NOT NULL COMMENT 'Id de status',
  `Descripcion` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre del status'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Status`
--

INSERT INTO `Status` (`idStatus`, `Descripcion`) VALUES
(1, 'Pendiente de aceptación'),
(2, 'Aceptada'),
(3, 'Rechazada'),
(4, 'En proceso de reclutamiento'),
(5, 'Candidato aceptado'),
(6, 'Candidato rechazado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(11) NOT NULL COMMENT 'id de usuario',
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nombre de usuario',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nick de usuario',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contraseña',
  `rol` int(10) NOT NULL COMMENT 'rol de usuario',
  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'correo electrónico del usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `username`, `password`, `rol`, `correo`) VALUES
(1, 'Víctor Valdez', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'davidgro1982@nube.unadmexico.mx'),
(2, 'Victor Valdez Gte RH', 'jeferh', '8f6a8ab35acd02395c069787d80d9848', 2, 'davidgro1982@nube.unadmexico.mx'),
(3, 'Victor Valdez Reclutador', 'reclutador', '0bda58827419367fe34c8f89205f4753', 3, 'davidgro1982@nube.unadmexico.mx'),
(4, 'Victor Valdez Jefe CI', 'jefeci', '2cb9854f9ea184838c62dce3ff331a4b', 4, 'davidgro1982@nube.unadmexico.mx'),
(5, 'Victor Valdez CI', 'clienteinterno', '21232411939a3f847223925945f21607', 5, 'davidgro1982@nube.unadmexico.mx'),
(6, 'david valdez', 'david.valdez', 'c57c58880963453d793be52ab13ae1ed', 1, 'davidgro1982@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Vacantes`
--

CREATE TABLE `Vacantes` (
  `Id` int(11) NOT NULL COMMENT 'id de vacante',
  `Departamento` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Departamento que solicita la vacante',
  `Puesto` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre del Puesto solicitado',
  `Descripcion` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Descripción del Puesto solicitado',
  `Escolaridad` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Escolaridad requerida',
  `Experiencia` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Experiencia requerida',
  `Requisitos` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Requisitos de la vacante',
  `Conocimientos` varchar(1000) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Conocimientos previos',
  `TipoPuesto` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Temporalidad de la vacante',
  `Titulado` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Se requiere que el candidato esté titulado?',
  `correoSolicitante` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Correo del solicitante de la vacante',
  `Status` int(11) NOT NULL COMMENT 'Status de solicitud',
  `motivoRechazo` varchar(800) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Si se rechaza la vacante, se especifica la razón'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `Vacantes`
--

INSERT INTO `Vacantes` (`Id`, `Departamento`, `Puesto`, `Descripcion`, `Escolaridad`, `Experiencia`, `Requisitos`, `Conocimientos`, `TipoPuesto`, `Titulado`, `correoSolicitante`, `Status`, `motivoRechazo`) VALUES
(1, 'Informática y Sistemas', 'Desarrollador Php', 'Diseñar, construir y probar sistemas de información con distintas arquitecturas, utilizando metodologías establecidas y con apego a las especificaciones de calidad definidas.\r\nDar mantenimiento correctivo y evolutivo a aplicativos existentes.', 'Ingeniería en sistemas o afín', 'De 1 a 2 años', 'PHP (ver. 7.x), consciente de cómo funciona PHP internamente', 'Patrones de diseño.\r\nCRUD, ORM, SQL Injection.\r\nProgramación orientada a objetos\r\nManejo de estructura de datos\r\nLinux y servidores de aplicaciones/web (Apache)\r\nSQL\r\nConocimiento de Laravel 5.2 en adelante\r\nImplementación de aplicaciones usando el patrón MVC\r\nImplementación de pruebas unitarias con PHPUnit\r\nUso de Blade\r\nManejo de Eloquent\r\nManejo de Composer\r\nUso de manejadores de datos como MySQL, MariaDB o PostgreSQL\r\nExperiencia con control de versiones (preferentemente Git)', 'Tiempo completo', 'No necesariamente', 'davidgro1982@nube.unadmexico.mx', 2, NULL),
(2, 'Acabados', '1', '2', '3', 'Recién egresado', '4', '5', 'Becario', 'Si', 'davidgro1982@nube.unadmexico.mx', 2, NULL),
(3, 'Informática y Sistemas', 'Analista', 'analisis de sistemas', 'Ingenieria sistemaso afin', 'Hasta 1 año', 'manjo UML', 'UML, analisis prograacion', 'Tiempo completo', 'No necesariamente', 'davidgro1982@nube.unadmexico.mx', 2, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Areas`
--
ALTER TABLE `Areas`
  ADD PRIMARY KEY (`clave`);

--
-- Indices de la tabla `Candidatos`
--
ALTER TABLE `Candidatos`
  ADD PRIMARY KEY (`idCandidato`);

--
-- Indices de la tabla `Roles`
--
ALTER TABLE `Roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`idStatus`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Indices de la tabla `Vacantes`
--
ALTER TABLE `Vacantes`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Areas`
--
ALTER TABLE `Areas`
  MODIFY `clave` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `Candidatos`
--
ALTER TABLE `Candidatos`
  MODIFY `idCandidato` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de tabla candidatos', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `Roles`
--
ALTER TABLE `Roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de rol', AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `Status`
--
ALTER TABLE `Status`
  MODIFY `idStatus` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id de status', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de usuario', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `Vacantes`
--
ALTER TABLE `Vacantes`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id de vacante', AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
