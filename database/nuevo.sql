-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2025 a las 14:34:10
-- Versión del servidor: 11.7.2-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `nuevo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `años`
--

CREATE TABLE `años` (
  `id_año` int(11) NOT NULL,
  `año` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `años`
--

INSERT INTO `años` (`id_año`, `año`, `descripcion`, `creacion`) VALUES
(1, 9, 'Primer año', '2025-07-24 01:18:56'),
(2, 1, 'Hola', '2025-09-22 00:18:19'),
(3, 7, 'Encargada de brindar apoyo ', '2025-09-22 02:57:39'),
(4, 7, 'Encargada de brindar apoyo ', '2025-09-22 02:59:32'),
(5, 9, 'Es de prueba23', '2025-09-22 03:00:31'),
(6, 9, 'Es de prueba23', '2025-09-22 03:02:15'),
(7, 9, 'Es de prueba23', '2025-09-22 03:02:32'),
(8, 5, 'Quinto Año', '2025-09-22 03:03:17'),
(9, 3, 'Tercer Ano', '2025-09-22 13:03:33'),
(10, 9, 'Es de prueba', '2025-09-22 14:16:52'),
(11, 6, 'sexto ano', '2025-09-22 14:20:16'),
(12, 2, 'pruebaxxxx', '2025-09-22 14:29:00'),
(13, 2, 'pruebaxxxx', '2025-09-22 14:30:25'),
(14, 2, 'pruebaxxxx', '2025-09-22 14:30:39'),
(15, 10, 'Cuarto Grado', '2025-09-22 17:01:42'),
(16, 1, 'Es de prueba', '2025-09-22 23:30:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `año_seccion`
--

CREATE TABLE `año_seccion` (
  `id_año_seccion` int(11) NOT NULL,
  `id_año` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `año_seccion`
--

INSERT INTO `año_seccion` (`id_año_seccion`, `id_año`, `id_seccion`, `capacidad`, `creacion`) VALUES
(1, 1, 1, 32, '2025-07-24 01:20:35'),
(2, 7, 18, 45, '2025-09-22 03:02:32'),
(3, 8, 19, 1000, '2025-09-22 03:03:17'),
(4, 9, 20, 30, '2025-09-22 13:03:33'),
(5, 13, 23, 14, '2025-09-22 14:30:25'),
(6, 14, 24, 14, '2025-09-22 14:30:39'),
(7, 15, 25, 10, '2025-09-22 17:01:42'),
(8, 16, 26, 42, '2025-09-22 23:30:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `alergias` text DEFAULT NULL,
  `condiciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `id_persona`, `alergias`, `condiciones`) VALUES
(1, 1, 'Alergico a la rabia', 'Ninguna'),
(2, 27, 'Camilo', 'Camilo'),
(3, 28, 'Camilo', 'Camilo'),
(4, 30, 'Camilo', 'Camilo'),
(5, 34, 'Camilo', 'Camilo'),
(6, 36, 'Camilo', 'Camilo'),
(7, 38, 'Camilo', 'Camilo'),
(8, 40, 'car', 'car'),
(9, 42, 'car', 'car'),
(10, 45, 'car', 'car'),
(11, 47, 'car', 'car'),
(12, 49, 'car', 'car'),
(13, 51, 'car', 'car'),
(14, 53, 'car', 'car'),
(15, 55, 'car', 'car');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante_representante`
--

CREATE TABLE `estudiante_representante` (
  `id_estudiante_representante` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_representante` int(11) NOT NULL,
  `relacion` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `estudiante_representante`
--

INSERT INTO `estudiante_representante` (`id_estudiante_representante`, `id_estudiante`, `id_representante`, `relacion`) VALUES
(1, 1, 1, 'madre'),
(2, 13, 6, 'madree'),
(3, 14, 7, 'madree'),
(4, 15, 8, 'madree');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grados`
--

CREATE TABLE `grados` (
  `id_grado` int(11) NOT NULL,
  `grado` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `grados`
--

INSERT INTO `grados` (`id_grado`, `grado`, `descripcion`, `creacion`) VALUES
(1, 1, 'primer Grado', '2025-07-24 01:18:41'),
(2, NULL, NULL, '2025-09-21 23:23:28'),
(3, NULL, NULL, '2025-09-21 23:24:04'),
(4, 1, 'Primer Grado', '2025-09-21 23:27:11'),
(5, 3, 'Tercer Grado', '2025-09-21 23:29:43'),
(6, 6, 'Sexto grado', '2025-09-21 23:33:24'),
(7, 2, 'Segundo Grado', '2025-09-21 23:37:09'),
(8, 5, 'Quinto Grado', '2025-09-21 23:37:57'),
(9, 8, 'Quinto Año', '2025-09-21 23:47:52'),
(10, 9, 'prueba', '2025-09-22 00:09:04'),
(11, 8, 'mira', '2025-09-22 01:47:29'),
(12, 8, 'Es de prueba', '2025-09-22 14:13:37'),
(13, 9, 'Encargada de brindar apoyo ', '2025-09-22 14:16:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado_seccion`
--

CREATE TABLE `grado_seccion` (
  `id_grado_seccion` int(11) NOT NULL,
  `id_grado` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `grado_seccion`
--

INSERT INTO `grado_seccion` (`id_grado_seccion`, `id_grado`, `id_seccion`, `capacidad`, `creacion`) VALUES
(1, 1, 1, 2000, '2025-07-24 01:19:55'),
(2, 7, 7, 1500, '2025-09-21 23:37:09'),
(3, 8, 8, 30, '2025-09-21 23:37:57'),
(4, 9, 9, 30, '2025-09-21 23:47:52'),
(5, 10, 10, 100, '2025-09-22 00:09:04'),
(6, 11, 13, 100, '2025-09-22 01:47:29'),
(7, 12, 21, 20, '2025-09-22 14:13:37'),
(8, 13, 22, 500, '2025-09-22 14:16:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion_inicial`
--

CREATE TABLE `inscripcion_inicial` (
  `id_inscripcion_inicial` int(11) NOT NULL,
  `id_estudiante_representante` int(11) NOT NULL,
  `id_grado_seccion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `inscripcion_inicial`
--

INSERT INTO `inscripcion_inicial` (`id_inscripcion_inicial`, `id_estudiante_representante`, `id_grado_seccion`, `id_periodo`, `creacion`) VALUES
(2, 4, 7, 1, '2025-10-25 11:55:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripcion_media`
--

CREATE TABLE `inscripcion_media` (
  `id_inscripcion_media` int(11) NOT NULL,
  `id_estudiante_representante` int(11) NOT NULL,
  `id_año_seccion` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `inscripcion_media`
--

INSERT INTO `inscripcion_media` (`id_inscripcion_media`, `id_estudiante_representante`, `id_año_seccion`, `id_periodo`, `creacion`) VALUES
(1, 1, 1, 1, '2025-07-24 01:21:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo`
--

CREATE TABLE `periodo` (
  `id_periodo` int(11) NOT NULL,
  `periodo` date DEFAULT NULL,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `periodo`
--

INSERT INTO `periodo` (`id_periodo`, `periodo`, `inicio`, `fin`, `creacion`) VALUES
(1, '2025-07-23', '2025-07-01', '2025-07-31', '2025-07-24 01:21:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombres` varchar(50) NOT NULL,
  `apellidos` varchar(50) NOT NULL,
  `cedula` varchar(15) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `telefono_hab` varchar(15) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `lugar_nac` varchar(50) NOT NULL,
  `nacionalidad` varchar(10) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `sexo` varchar(15) DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `parroquia` varchar(50) NOT NULL,
  `calle` varchar(50) NOT NULL,
  `casa` varchar(50) NOT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp(),
  `actualizacion` timestamp NULL DEFAULT current_timestamp(),
  `inhabilitado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `id_rol`, `nombres`, `apellidos`, `cedula`, `telefono`, `telefono_hab`, `correo`, `lugar_nac`, `nacionalidad`, `fecha_nac`, `sexo`, `estado`, `parroquia`, `calle`, `casa`, `creacion`, `actualizacion`, `inhabilitado`) VALUES
(1, 2, 'Briant', 'Carrillo', '27318765', '04149105229', '02124845109', 'brianttd24.7@gmail.com', 'Caracas', 'Venezolano', '2015-07-14', NULL, 'Caracas', 'Santa teresa', 'Oeste 16', '15-g', '2025-07-24 01:03:39', '2025-07-24 01:03:39', NULL),
(4, 1, 'Maria', 'Sanchez', '9344828', '02121234567', '01212131541', 'alicias@gmail.com', 'caracas', 'venezolano', '2025-07-01', NULL, 'Caracas', 'Santa teresa', 'Oeste 16', '15-g', '2025-07-24 01:08:24', '2025-07-24 01:08:24', NULL),
(5, 3, 'Luis', 'Martinez', '99999999', '12345678912', '12345678123', 'prueba@gmail.com', 'caracas', 'Venezolano', '2025-07-08', NULL, 'Caracas', 'Santa teresa', 'Oeste 16', '15-g', '2025-07-24 01:13:37', '2025-07-24 01:13:37', NULL),
(6, 1, 'Pedro Abrahan', 'Ramirez Pascal', '12345678', '04140000000', '02120000000', 'pedro@gmail.com', 'El paraiso', 'venezolano', '1997-10-14', 'masculino', 'Caracas', 'santa teresa', '21312', '15', '2025-09-22 07:26:38', '2025-09-22 07:26:38', NULL),
(8, 1, 'Marino ', 'quijote', '87654321', '04140000000', '02120000000', 'marino@gmail.com', 'El paraiso', 'venezolano', '1999-05-22', 'masculino', 'Caracas', 'Santa teresa', '21', '4', '2025-09-22 07:29:02', '2025-09-22 07:29:02', NULL),
(9, 1, 'Iron', 'Man', '77777777', '04140000000', '02120000000', 'iron@gmail.com', 'Las Mercedes', 'venezolano', '1999-06-22', 'masculino', 'Miranda', 'Miranda', 'Miranda', '15', '2025-09-22 07:36:45', '2025-09-22 07:36:45', NULL),
(10, 1, 'HULK', 'Aplasta', '123456', '04120000000', '02120000000', 'hulk@gmail.com', 'Quinta Crezpo', 'venezolano', '1995-06-22', 'masculino', 'Caracas', 'San jugan', '24', '12', '2025-09-22 07:37:49', '2025-09-22 07:37:49', NULL),
(11, 1, 'Briant Alessandro', 'Carrillo Sanchez', '27318777', '04140000000', '02120000000', 'briant03@gmail.com', 'El paraiso', 'venezolano', '1998-11-30', 'masculino', 'Caracas', 'Santa rosalia', 'av. oeste 16', '15', '2025-09-22 08:13:23', '2025-09-22 08:13:23', NULL),
(12, 10, 'Alicia Maria', 'Sanchez Marquez', '98651345', '04140000000', '02120000000', 'alicia12@gmail.com', 'El paraiso', 'extranjero', '1998-11-11', 'masculino', 'Caracas', 'Santa rosalia', '21312', '15', '2025-09-22 08:38:10', '2025-09-22 08:38:10', NULL),
(15, 1, 'nuevo', 'prueba', '1342567', '04140000000', '02120000000', 'prueba@prueba.com', 'El paraiso', 'venezolano', '1998-11-30', 'masculino', 'Caracas', 'santa teresa', '21312', '15', '2025-09-22 23:17:49', '2025-09-22 23:17:49', NULL),
(21, 11, 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:18:14', '2025-10-25 11:18:14', NULL),
(23, 11, 'Camilo', 'Camilo', '1234567', 'Camilo', 'Camilo', 'Camilo2@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:23:37', '2025-10-25 11:23:37', NULL),
(25, 11, 'Camilo', 'Camilo', '12345671', 'Camilo', 'Camilo', 'Camilo21@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:25:21', '2025-10-25 11:25:21', NULL),
(26, 11, 'Camilo', 'Camilo', '1234', 'Camilo', 'Camilo', 'Camil21@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:30:56', '2025-10-25 11:30:56', NULL),
(27, 11, 'Camilo', 'Camilo', '12343', 'Camilo', 'Camilo', 'Camil211@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:32:36', '2025-10-25 11:32:36', NULL),
(28, 2, 'Camilo', 'Camilo', '123', 'Camilo', 'Camilo', 'Cal211@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:33:46', '2025-10-25 11:33:46', NULL),
(29, 11, 'Valeria', 'Valeria', '1234235', 'Valeria', 'Valeria', 'Valeria@gmail.com', 'Valeria', 'Valeria', '2025-10-07', 'Valeria', 'Valeria', 'Valeria', 'Valeria', 'Valeria', '2025-10-25 11:33:46', '2025-10-25 11:33:46', NULL),
(30, 2, 'Camilo', 'Camilo', '1231462', 'Camilo', 'Camilo', 'Cal2121@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:34:44', '2025-10-25 11:34:44', NULL),
(34, 2, 'Camilo', 'Camilo', '1231462911', 'Camilo', 'Camilo', 'Cal211121@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:35:27', '2025-10-25 11:35:27', NULL),
(35, 11, 'Valeria', 'Valeria', '1234228', 'Valeria', 'Valeria', 'Valer1ia@gmail.com', 'Valeria', 'Valeria', '2025-10-07', 'Valeria', 'Valeria', 'Valeria', 'Valeria', 'Valeria', '2025-10-25 11:35:27', '2025-10-25 11:35:27', NULL),
(36, 2, 'Camilo', 'Camilo', '196381', 'Camilo', 'Camilo', 'l211121@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:38:01', '2025-10-25 11:38:01', NULL),
(38, 2, 'Camilo', 'Camilo', '436335', 'Camilo', 'Camilo', 'l2k11121@gmail.com', 'Camilo', 'Camilo', '2025-10-08', 'Camilo', 'Camilo', 'Camilo', 'Camilo', 'Camilo', '2025-10-25 11:38:20', '2025-10-25 11:38:20', NULL),
(39, 11, 'Valeria', 'Valeria', '12349384', 'Valeria', 'Valeria', 'Valer231ian@gmail.com', 'Valeria', 'Valeria', '2025-10-07', 'Valeria', 'Valeria', 'Valeria', 'Valeria', 'Valeria', '2025-10-25 11:38:20', '2025-10-25 11:38:20', NULL),
(40, 2, 'camilo', 'camilo', '9684711', 'car', 'car', 'camilo875@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:40:00', '2025-10-25 11:40:00', NULL),
(41, 11, 'monica', 'monica', '9999888', 'hijo', 'hijo', '999888@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:40:00', '2025-10-25 11:40:00', NULL),
(42, 2, 'camilo', 'camilo', '96814711', 'car', 'car', 'camilo8175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:40:45', '2025-10-25 11:40:45', NULL),
(43, 11, 'monica', 'monica', '99998881', 'hijo', 'hijo', '9998881@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:40:45', '2025-10-25 11:40:45', NULL),
(45, 2, 'camilo', 'camilo', '968144', 'car', 'car', 'camil48175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:47:12', '2025-10-25 11:47:12', NULL),
(46, 11, 'monica', 'monica', '9999481', 'hijo', 'hijo', '9948881@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:47:12', '2025-10-25 11:47:12', NULL),
(47, 2, 'camilo', 'camilo', '9681445', 'car', 'car', 'cami2l48175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:49:48', '2025-10-25 11:49:48', NULL),
(48, 11, 'monica', 'monica', '9199481', 'hijo', 'hijo', '9148881@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:49:48', '2025-10-25 11:49:48', NULL),
(49, 2, 'camilo', 'camilo', '9681446', 'car', 'car', 'cami248175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:50:16', '2025-10-25 11:50:16', NULL),
(50, 11, 'monica', 'monica', '9199482', 'hijo', 'hijo', '9148882@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:50:16', '2025-10-25 11:50:16', NULL),
(51, 2, 'camilo', 'camilo', '9681436', 'car', 'car', 'cmi248175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:52:34', '2025-10-25 11:52:34', NULL),
(52, 11, 'monica', 'monica', '9399482', 'hijo', 'hijo', '918882@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:52:34', '2025-10-25 11:52:34', NULL),
(53, 2, 'camilo', 'camilo', '968146', 'car', 'car', 'cm248175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:55:12', '2025-10-25 11:55:12', NULL),
(54, 11, 'monica', 'monica', '939942', 'hijo', 'hijo', '91882@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:55:12', '2025-10-25 11:55:12', NULL),
(55, 2, 'camilo', 'camilo', '968176', 'car', 'car', 'cm48175@gmail.com', 'car', 'car', '1888-11-30', 'car', 'car ', 'car ', 'car', 'car', '2025-10-25 11:55:52', '2025-10-25 11:55:52', NULL),
(56, 11, 'monica', 'monica', '93992', 'hijo', 'hijo', '9182@gmail.com', 'ven', 'total', '1887-03-18', 'masculino', 'miranda', 'miranda', 'miranda', 'miranda', '2025-10-25 11:55:52', '2025-10-25 11:55:52', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_profesor` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `especialidad` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_profesor`, `id_persona`, `especialidad`) VALUES
(1, 15, 'ingles');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id_representante` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `ocupacion` varchar(50) NOT NULL,
  `lugar_trabajo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id_representante`, `id_persona`, `ocupacion`, `lugar_trabajo`) VALUES
(1, 4, 'Ama de casa', '15-g'),
(2, 43, 'total', 'todo'),
(3, 46, 'total', 'todo'),
(4, 48, 'total', 'todo'),
(5, 50, 'total', 'todo'),
(6, 52, 'total', 'todo'),
(7, 54, 'total', 'todo'),
(8, 56, 'total', 'todo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(1, 'ADMINISTRADOR', 'Tiene permiso a todas las tablas'),
(2, 'ALUMNO', 'Este rol es para alumnos'),
(3, 'PROFESOR', 'Este es un profesor'),
(4, 'SECRETARIA', 'Encargada de brindar apoyo '),
(10, 'COORDINADOR', 'Encargado de aprobar toda la informacion de los profesores'),
(11, 'REPRESENTANTE', 'Es de prueba'),
(12, 'borrar 2', 'Es de prueba intento 2'),
(13, 'Fortuna', 'La tenemos de prueba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id_seccion` int(11) NOT NULL,
  `nom_seccion` varchar(1) DEFAULT NULL,
  `turno` varchar(10) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_seccion`, `nom_seccion`, `turno`, `observacion`, `creacion`) VALUES
(1, 'A', 'diurno', 'Seccion A', '2025-07-24 01:19:31'),
(2, NULL, NULL, NULL, '2025-09-21 23:23:28'),
(3, NULL, NULL, NULL, '2025-09-21 23:24:04'),
(4, 'B', 'Nocturno', 'Grado B', '2025-09-21 23:27:11'),
(5, 'C', 'Diurno', 'SEccion c', '2025-09-21 23:29:43'),
(6, 'A', 'Diurno', 'Grado A', '2025-09-21 23:33:24'),
(7, 'A', 'Diurno', 'Seccion A', '2025-09-21 23:37:09'),
(8, 'E', 'Nocturno', 'Seccion E', '2025-09-21 23:37:57'),
(9, 'A', 'Diurno', 'Seccion A del año', '2025-09-21 23:47:52'),
(10, 'E', 'tarde', 'prueba', '2025-09-22 00:09:04'),
(11, 'A', 'Diurno', 'prueba', '2025-09-22 00:34:58'),
(12, 'A', 'Diurno', 'prueba', '2025-09-22 00:35:37'),
(13, 'x', 'Diurno', 'Esto es otra cosa', '2025-09-22 01:47:29'),
(14, 'x', 'Diurno', 'prueba', '2025-09-22 02:57:39'),
(15, 'x', 'Diurno', 'prueba', '2025-09-22 02:59:32'),
(16, 'y', 'Nocturno', 'Seccion D', '2025-09-22 03:00:31'),
(17, 'y', 'Nocturno', 'Seccion D', '2025-09-22 03:02:15'),
(18, 'Y', 'Diurno', 'Seccion D', '2025-09-22 03:02:32'),
(19, 'h', 'tarde', 'pruebisima', '2025-09-22 03:03:17'),
(20, 'F', 'Diurno', 'Seccion F', '2025-09-22 13:03:33'),
(21, 'p', 'Nocturno', 'Seccion A', '2025-09-22 14:13:37'),
(22, 'J', 'Diurno', 'observacion', '2025-09-22 14:16:24'),
(23, 'L', 'Diurno', 'obserxxxxx', '2025-09-22 14:30:25'),
(24, 'L', 'Diurno', 'obserxxxxx', '2025-09-22 14:30:39'),
(25, 'E', 'Diurno', 'Seccion E', '2025-09-22 17:01:42'),
(26, 'A', 'Nocturno', 'Seccion A', '2025-09-22 23:30:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `usuario` varchar(8) DEFAULT NULL,
  `contraseña` varchar(255) NOT NULL,
  `estado_cuenta` tinyint(4) DEFAULT NULL,
  `creacion` timestamp NULL DEFAULT current_timestamp(),
  `actualizacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_persona`, `id_rol`, `nombre_usuario`, `usuario`, `contraseña`, `estado_cuenta`, `creacion`, `actualizacion`) VALUES
(1, 1, 3, 'briant1234@gmail.com', '27318765', '12345', 0, '2025-09-07 21:17:21', '2025-09-07 21:17:21');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `años`
--
ALTER TABLE `años`
  ADD PRIMARY KEY (`id_año`);

--
-- Indices de la tabla `año_seccion`
--
ALTER TABLE `año_seccion`
  ADD PRIMARY KEY (`id_año_seccion`),
  ADD KEY `id_año` (`id_año`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `estudiante_representante`
--
ALTER TABLE `estudiante_representante`
  ADD PRIMARY KEY (`id_estudiante_representante`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_representante` (`id_representante`);

--
-- Indices de la tabla `grados`
--
ALTER TABLE `grados`
  ADD PRIMARY KEY (`id_grado`);

--
-- Indices de la tabla `grado_seccion`
--
ALTER TABLE `grado_seccion`
  ADD PRIMARY KEY (`id_grado_seccion`),
  ADD KEY `id_grado` (`id_grado`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `inscripcion_inicial`
--
ALTER TABLE `inscripcion_inicial`
  ADD PRIMARY KEY (`id_inscripcion_inicial`),
  ADD KEY `id_estudiante_representante` (`id_estudiante_representante`),
  ADD KEY `id_grado_seccion` (`id_grado_seccion`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `inscripcion_media`
--
ALTER TABLE `inscripcion_media`
  ADD PRIMARY KEY (`id_inscripcion_media`),
  ADD KEY `id_estudiante_representante` (`id_estudiante_representante`),
  ADD KEY `id_año_seccion` (`id_año_seccion`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `periodo`
--
ALTER TABLE `periodo`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_profesor`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id_representante`),
  ADD KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nombre_rol` (`nombre_rol`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones`
  ADD PRIMARY KEY (`id_seccion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD KEY `id_persona` (`id_persona`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `años`
--
ALTER TABLE `años`
  MODIFY `id_año` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `año_seccion`
--
ALTER TABLE `año_seccion`
  MODIFY `id_año_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `estudiante_representante`
--
ALTER TABLE `estudiante_representante`
  MODIFY `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `grados`
--
ALTER TABLE `grados`
  MODIFY `id_grado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `grado_seccion`
--
ALTER TABLE `grado_seccion`
  MODIFY `id_grado_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `inscripcion_inicial`
--
ALTER TABLE `inscripcion_inicial`
  MODIFY `id_inscripcion_inicial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inscripcion_media`
--
ALTER TABLE `inscripcion_media`
  MODIFY `id_inscripcion_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `periodo`
--
ALTER TABLE `periodo`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_profesor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `año_seccion`
--
ALTER TABLE `año_seccion`
  ADD CONSTRAINT `año_seccion_ibfk_1` FOREIGN KEY (`id_año`) REFERENCES `años` (`id_año`),
  ADD CONSTRAINT `año_seccion_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `estudiante_representante`
--
ALTER TABLE `estudiante_representante`
  ADD CONSTRAINT `estudiante_representante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `estudiante_representante_ibfk_2` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`);

--
-- Filtros para la tabla `grado_seccion`
--
ALTER TABLE `grado_seccion`
  ADD CONSTRAINT `grado_seccion_ibfk_1` FOREIGN KEY (`id_grado`) REFERENCES `grados` (`id_grado`),
  ADD CONSTRAINT `grado_seccion_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

--
-- Filtros para la tabla `inscripcion_inicial`
--
ALTER TABLE `inscripcion_inicial`
  ADD CONSTRAINT `inscripcion_inicial_ibfk_1` FOREIGN KEY (`id_estudiante_representante`) REFERENCES `estudiante_representante` (`id_estudiante_representante`),
  ADD CONSTRAINT `inscripcion_inicial_ibfk_2` FOREIGN KEY (`id_grado_seccion`) REFERENCES `grado_seccion` (`id_grado_seccion`),
  ADD CONSTRAINT `inscripcion_inicial_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `periodo` (`id_periodo`);

--
-- Filtros para la tabla `inscripcion_media`
--
ALTER TABLE `inscripcion_media`
  ADD CONSTRAINT `inscripcion_media_ibfk_1` FOREIGN KEY (`id_estudiante_representante`) REFERENCES `estudiante_representante` (`id_estudiante_representante`),
  ADD CONSTRAINT `inscripcion_media_ibfk_2` FOREIGN KEY (`id_año_seccion`) REFERENCES `año_seccion` (`id_año_seccion`),
  ADD CONSTRAINT `inscripcion_media_ibfk_3` FOREIGN KEY (`id_periodo`) REFERENCES `periodo` (`id_periodo`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `representantes_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
