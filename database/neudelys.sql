-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-01-2026 a las 17:27:18
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
-- Base de datos: `nuevo_horizonte`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones`
--

CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL,
  `id_parroquia` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `calle` varchar(255) DEFAULT NULL,
  `casa` varchar(255) DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO `direcciones` (`id_direccion`, `id_parroquia`, `direccion`, `calle`, `casa`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'Av Principal de Petare', 'Av Principal', 'Casa 123', '2025-11-10 06:17:16', '2025-11-23 20:27:06', 1),
(2, 2, 'Urbanización Caucagüita', 'Calle 2', 'Edificio A, Apt 4B', '2025-11-10 06:17:16', '2025-11-27 01:35:47', 1),
(3, 3, 'Sector Baruta', 'Calle Los Samanes', 'Quinta María', '2025-11-10 06:17:16', '2025-11-25 20:53:36', 1),
(4, 4, 'AV INTERCOMUNAL EL VALLE', 'AV PRINCIPAL', 'CASA 80', '2025-11-10 06:17:16', '2025-12-02 01:04:35', 1),
(5, 1, 'Urbanización Los Naranjos', 'Calle 5', 'Casa 89', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 2, 'Sector La Dolorita', 'Calle 7', 'Edificio B, Apt 2C', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 3, 'Urbanización Prados del Este', 'Av Ppal', 'Quinta Los Pinos', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(13, 1, 'Av Principal de Petare', 'Av Principal', 'Casa 123', '2025-11-11 18:59:50', NULL, 1),
(14, 77, 'Quinta Crespo', 'av sur 4. oeste 16', 'Res siena', '2025-11-11 19:30:17', '2025-11-20 16:05:39', 1),
(15, 77, 'Quinta Crespo', 'av sur 4. oeste 16', 'Res siena', '2025-11-11 19:30:17', NULL, 1),
(16, 1, 'Av Principal de Petare', 'Av Principal', 'Casa 123', '2025-11-11 20:02:31', NULL, 1),
(17, 81, 'Quinta Crespo', 'av sur 4. oeste 16', 'Res siena', '2025-11-11 22:16:43', NULL, 1),
(18, 81, 'Quinta Crespo', 'av sur 4. oeste 16', 'Res siena', '2025-11-11 22:16:43', NULL, 1),
(19, 78, 'Quinta Crespo', 'Av 2', 'Montalban 3', '2025-11-11 22:52:57', NULL, 1),
(20, 78, 'Quinta Crespo', 'Av 2', 'Montalban 3', '2025-11-11 22:52:57', NULL, 1),
(21, 1, 'Por definir', NULL, NULL, '2025-11-17 04:30:35', NULL, 1),
(22, 1, 'Por definir', NULL, NULL, '2025-11-17 04:41:45', NULL, 1),
(29, 1, 'LOS SAUCES', 'LOS NARANJOS', '33F', '2025-11-20 03:45:22', '2025-12-02 01:25:19', 1),
(30, 69, 'Quinta Crespo', 'K', 'Montalban 3', '2025-11-20 04:38:27', '2025-12-01 19:26:24', 1),
(31, 67, 'Quinta Crespo', 'K', 'Res siena', '2025-11-20 20:09:12', NULL, 1),
(32, 64, 'Quinta Crespo', 'av sur 4', 'res siena', '2025-11-20 21:54:05', NULL, 1),
(33, 61, 'Quinta Crespo', 'Nueva Granada', 'Torre B', '2025-11-20 22:01:18', NULL, 1),
(34, 73, 'EL VALLE', 'CALLE 3', '34', '2025-11-24 23:38:25', '2025-12-02 01:21:12', 1),
(35, 68, 'Av. Real 4', '', '', '2025-11-25 00:44:20', NULL, 1),
(36, 85, 'Av. Principal del Cementerio', 'La Vereda', '', '2025-11-28 04:37:12', NULL, 1),
(37, 85, 'AV PRINCIPAL EL CEMENTERIO', 'LOS ALPES', 'SN', '2025-11-28 23:32:52', '2025-12-02 17:15:59', 1),
(38, 68, 'AV PRINCIPAL ANTIMANO', 'LOS JABILLOS', '', '2025-11-30 00:55:12', NULL, 1),
(39, 87, 'Barrio Nuevo Horizonte', 'La Parada', '56', '2025-11-30 14:45:26', '2025-12-07 18:10:29', 1),
(41, 87, 'BARRIO NUEVO HORIZONTE', 'LA PARADA', '23', '2025-12-01 21:27:51', '2025-12-01 20:04:30', 1),
(43, 87, 'GATO NEGRO', 'CATIA', '3', '2025-12-02 17:09:44', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacidades`
--

CREATE TABLE `discapacidades` (
  `id_discapacidad` int(11) NOT NULL,
  `nom_discapacidad` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `discapacidades`
--

INSERT INTO `discapacidades` (`id_discapacidad`, `nom_discapacidad`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Discapacidad visual', '2025-11-25 10:00:00', NULL, 1),
(2, 'Discapacidad auditiva', '2025-11-25 10:00:00', NULL, 1),
(3, 'Discapacidad motora', '2025-11-25 10:00:00', NULL, 1),
(4, 'Discapacidad intelectual', '2025-11-25 10:00:00', NULL, 1),
(5, 'Trastorno del espectro autista', '2025-11-25 10:00:00', NULL, 1),
(6, 'Discapacidad múltiple', '2025-11-25 10:00:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1,
  `id_profesion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_docente`, `id_persona`, `creacion`, `actualizacion`, `estatus`, `id_profesion`) VALUES
(1, 1, '2025-11-17 02:31:17', '2025-11-30 20:05:52', 0, 12),
(2, 33, '2025-11-17 04:30:35', '2025-11-27 18:31:14', 0, 12),
(3, 34, '2025-11-17 04:41:45', '2025-12-02 01:25:19', 1, 12),
(4, 47, '2025-11-24 23:38:25', '2025-12-02 01:21:12', 1, 12),
(5, 48, '2025-11-25 00:44:20', NULL, 1, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_especialidades`
--

CREATE TABLE `docentes_especialidades` (
  `id_docente_especialidad` int(11) NOT NULL,
  `id_docente` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nom_especialidad` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id_estado` int(11) NOT NULL,
  `nom_estado` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estado`, `nom_estado`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Miranda', '2025-11-10 06:03:55', NULL, 1),
(2, 'La Guaira', '2025-11-10 06:03:55', NULL, 1),
(3, 'Distrito Capital', '2025-11-10 06:03:55', NULL, 1),
(4, 'Amazonas', '2025-11-30 21:02:45', NULL, 1),
(5, 'Anzoátegui', '2025-11-30 21:02:45', NULL, 1),
(6, 'Apure', '2025-11-30 21:02:45', NULL, 1),
(7, 'Aragua', '2025-11-30 21:02:45', NULL, 1),
(8, 'Barinas', '2025-11-30 21:02:45', NULL, 1),
(9, 'Bolívar', '2025-11-30 21:02:45', NULL, 1),
(10, 'Carabobo', '2025-11-30 21:02:45', NULL, 1),
(11, 'Cojedes', '2025-11-30 21:02:45', NULL, 1),
(12, 'Delta Amacuro', '2025-11-30 21:02:45', NULL, 1),
(13, 'Falcón', '2025-11-30 21:02:45', NULL, 1),
(14, 'Guárico', '2025-11-30 21:02:45', NULL, 1),
(15, 'Lara', '2025-11-30 21:02:45', NULL, 1),
(16, 'Mérida', '2025-11-30 21:02:45', NULL, 1),
(17, 'Monagas', '2025-11-30 21:02:45', NULL, 1),
(18, 'Nueva Esparta', '2025-11-30 21:02:45', NULL, 1),
(19, 'Portuguesa', '2025-11-30 21:02:45', NULL, 1),
(20, 'Sucre', '2025-11-30 21:02:45', NULL, 1),
(21, 'Táchira', '2025-11-30 21:02:45', NULL, 1),
(22, 'Trujillo', '2025-11-30 21:02:45', NULL, 1),
(23, 'Yaracuy', '2025-11-30 21:02:45', '2025-12-02 17:54:02', 0),
(24, 'Zulia', '2025-11-30 21:02:45', NULL, 1),
(25, 'Dependencias Federales', '2025-11-30 21:02:45', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `id_persona`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, '2025-11-10 06:17:16', '2025-11-27 22:53:58', 1),
(3, 3, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(11, 24, '2025-11-11 18:59:50', NULL, 1),
(12, 26, '2025-11-11 19:30:17', NULL, 1),
(13, 27, '2025-11-11 20:02:31', NULL, 1),
(14, 29, '2025-11-11 22:16:43', NULL, 1),
(15, 31, '2025-11-11 22:52:57', NULL, 1),
(16, 32, '2025-11-13 01:21:00', NULL, 1),
(17, 35, '2025-11-20 03:45:22', NULL, 1),
(18, 36, '2025-11-20 04:15:06', NULL, 1),
(19, 38, '2025-11-20 04:38:27', NULL, 1),
(20, 39, '2025-11-20 20:05:39', NULL, 1),
(21, 41, '2025-11-20 20:09:12', NULL, 1),
(22, 42, '2025-11-20 21:54:05', NULL, 1),
(23, 43, '2025-11-20 22:01:18', NULL, 1),
(24, 44, '2025-11-22 22:32:30', '2025-12-07 15:23:20', 0),
(26, 46, '2025-11-24 00:27:06', NULL, 1),
(27, 49, '2025-11-26 00:53:36', NULL, 1),
(28, 50, '2025-11-27 05:35:47', NULL, 1),
(29, 52, '2025-11-28 04:37:12', NULL, 1),
(30, 54, '2025-11-28 23:32:52', NULL, 1),
(32, 58, '2025-11-29 00:49:29', NULL, 1),
(34, 62, '2025-11-29 01:26:47', NULL, 1),
(35, 64, '2025-11-29 01:31:17', NULL, 1),
(36, 67, '2025-11-29 02:06:22', NULL, 1),
(37, 69, '2025-11-29 02:36:49', NULL, 1),
(39, 73, '2025-11-29 03:01:30', NULL, 1),
(40, 75, '2025-11-29 03:13:49', NULL, 1),
(41, 77, '2025-11-29 03:25:04', NULL, 1),
(42, 79, '2025-11-29 03:45:48', NULL, 1),
(43, 81, '2025-11-29 03:53:17', NULL, 1),
(44, 83, '2025-11-29 15:06:58', NULL, 1),
(46, 87, '2025-11-29 16:39:28', NULL, 1),
(48, 91, '2025-11-29 16:54:26', NULL, 1),
(49, 93, '2025-11-30 01:39:09', NULL, 1),
(50, 95, '2025-11-30 01:55:13', NULL, 1),
(51, 98, '2025-11-30 14:45:26', NULL, 1),
(52, 100, '2025-12-01 04:46:04', NULL, 1),
(53, 102, '2025-12-01 06:00:26', NULL, 1),
(54, 104, '2025-12-01 06:40:04', NULL, 1),
(55, 107, '2025-12-01 21:27:51', NULL, 1),
(56, 109, '2025-12-01 22:08:02', NULL, 1),
(57, 111, '2025-12-02 05:36:27', NULL, 1),
(58, 113, '2025-12-02 14:41:06', NULL, 1),
(59, 115, '2025-12-02 17:09:44', NULL, 1),
(60, 117, '2025-12-02 20:53:30', NULL, 1),
(61, 119, '2025-12-02 21:15:59', NULL, 1),
(62, 121, '2025-12-02 21:40:49', NULL, 1),
(63, 123, '2025-12-03 01:23:16', NULL, 1),
(64, 125, '2025-12-07 22:04:29', NULL, 1),
(65, 127, '2025-12-07 22:10:29', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_discapacidades`
--

CREATE TABLE `estudiantes_discapacidades` (
  `id_estudiante_discapacidad` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_discapacidad` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_patologias`
--

CREATE TABLE `estudiantes_patologias` (
  `id_estudiante_patologia` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_patologia` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_patologias`
--

INSERT INTO `estudiantes_patologias` (`id_estudiante_patologia`, `id_estudiante`, `id_patologia`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 3, 2, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 5, 3, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 7, 4, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 11, 4, '2025-11-11 18:59:50', NULL, 1),
(6, 12, 4, '2025-11-11 19:30:17', NULL, 1),
(7, 14, 2, '2025-11-11 22:16:43', NULL, 1),
(8, 14, 3, '2025-11-11 22:16:43', NULL, 1),
(9, 15, 2, '2025-11-11 22:52:57', NULL, 1),
(10, 28, 1, '2025-11-27 05:35:47', NULL, 1),
(11, 29, 4, '2025-11-28 04:37:12', NULL, 1),
(12, 58, 1, '2025-12-02 14:41:06', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_representantes`
--

CREATE TABLE `estudiantes_representantes` (
  `id_estudiante_representante` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_representante` int(11) NOT NULL,
  `id_parentesco` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_representantes`
--

INSERT INTO `estudiantes_representantes` (`id_estudiante_representante`, `id_estudiante`, `id_representante`, `id_parentesco`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 2, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 3, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, 4, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, 5, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, 6, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, 7, 1, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(8, 11, 1, 1, '2025-11-11 18:59:50', NULL, 1),
(9, 12, 11, 1, '2025-11-11 19:30:17', NULL, 1),
(10, 13, 1, 1, '2025-11-11 20:02:31', NULL, 1),
(11, 14, 12, 1, '2025-11-11 22:16:43', NULL, 1),
(12, 15, 13, 1, '2025-11-11 22:52:57', NULL, 1),
(13, 16, 7, 1, '2025-11-13 01:21:00', NULL, 1),
(14, 17, 14, 1, '2025-11-20 03:45:22', NULL, 1),
(15, 18, 14, 1, '2025-11-20 04:15:06', NULL, 1),
(16, 19, 15, 1, '2025-11-20 04:38:27', NULL, 1),
(17, 20, 11, 1, '2025-11-20 20:05:39', NULL, 1),
(18, 21, 16, 1, '2025-11-20 20:09:12', NULL, 1),
(19, 22, 1, 1, '2025-11-20 21:54:05', NULL, 1),
(20, 23, 1, 1, '2025-11-20 22:01:18', NULL, 1),
(21, 24, 1, 1, '2025-11-22 22:32:30', NULL, 1),
(22, 26, 1, 6, '2025-11-24 00:27:06', NULL, 1),
(23, 27, 3, 2, '2025-11-26 00:53:36', NULL, 1),
(24, 28, 2, 1, '2025-11-27 05:35:47', NULL, 1),
(25, 29, 17, 1, '2025-11-28 04:37:12', NULL, 1),
(26, 30, 18, 2, '2025-11-28 23:32:52', NULL, 1),
(28, 32, 18, 2, '2025-11-29 00:49:29', NULL, 1),
(30, 34, 18, 2, '2025-11-29 01:26:47', NULL, 1),
(31, 35, 18, 2, '2025-11-29 01:31:17', NULL, 1),
(32, 36, 18, 2, '2025-11-29 02:06:22', NULL, 1),
(33, 37, 18, 2, '2025-11-29 02:36:49', NULL, 1),
(35, 39, 18, 2, '2025-11-29 03:01:30', '2025-12-01 19:51:36', 1),
(36, 40, 18, 2, '2025-11-29 03:13:49', NULL, 1),
(37, 41, 18, 2, '2025-11-29 03:25:04', '2025-12-01 19:54:27', 1),
(38, 42, 18, 2, '2025-11-29 03:45:48', '2025-11-29 20:55:12', 1),
(39, 43, 18, 2, '2025-11-29 03:53:17', NULL, 1),
(40, 44, 18, 6, '2025-11-29 15:06:58', NULL, 1),
(42, 46, 18, 6, '2025-11-29 16:39:28', NULL, 1),
(44, 48, 18, 6, '2025-11-29 16:54:26', NULL, 1),
(45, 49, 18, 2, '2025-11-30 01:39:09', NULL, 1),
(46, 50, 18, 2, '2025-11-30 01:55:13', '2025-12-01 19:52:29', 1),
(47, 51, 19, 2, '2025-11-30 14:45:26', NULL, 1),
(48, 52, 18, 6, '2025-12-01 04:46:04', NULL, 1),
(49, 53, 18, 2, '2025-12-01 06:00:26', NULL, 1),
(50, 54, 19, 3, '2025-12-01 06:40:04', '2025-12-02 01:26:18', 1),
(51, 55, 20, 2, '2025-12-01 21:27:51', NULL, 1),
(52, 56, 20, 2, '2025-12-01 22:08:02', '2025-12-01 20:04:30', 1),
(53, 57, 19, 6, '2025-12-02 05:36:27', NULL, 1),
(54, 58, 18, 6, '2025-12-02 14:41:06', NULL, 1),
(55, 59, 18, 6, '2025-12-02 17:09:44', NULL, 1),
(56, 60, 18, 6, '2025-12-02 20:53:30', NULL, 1),
(57, 61, 18, 6, '2025-12-02 21:15:59', NULL, 1),
(58, 62, 19, 6, '2025-12-02 21:40:49', NULL, 1),
(59, 63, 19, 6, '2025-12-03 01:23:16', NULL, 1),
(60, 64, 19, 2, '2025-12-07 22:04:29', NULL, 1),
(61, 65, 19, 2, '2025-12-07 22:10:29', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `globales`
--

CREATE TABLE `globales` (
  `id_globales` int(11) NOT NULL,
  `edad_min` int(11) NOT NULL,
  `edad_max` int(11) NOT NULL,
  `nom_instituto` varchar(50) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `nom_directora` varchar(100) DEFAULT NULL,
  `ci_directora` varchar(8) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `globales`
--

INSERT INTO `globales` (`id_globales`, `edad_min`, `edad_max`, `nom_instituto`, `id_periodo`, `nom_directora`, `ci_directora`, `direccion`) VALUES
(1, 5, 19, 'Nuevo Horizonte', 3, 'Mariday Castaño', '13088634', 'Distrito Capital, Parroquia Sucre, Catia, Gramoven - Barrio Nuevo Horizonte, Calle Principal La Parada, Edificio U.E.N “Nuevo Horizonte”');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_inscripcion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_periodo` int(11) NOT NULL,
  `id_nivel_seccion` int(11) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `observaciones` text DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id_inscripcion`, `id_estudiante`, `id_periodo`, `id_nivel_seccion`, `id_usuario`, `fecha_inscripcion`, `observaciones`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, 1, 1, '2024-09-01', 'Estudiante nueva, con asma controlada', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(2, 2, 1, 1, 1, '2024-09-01', 'Estudiante regular', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(3, 3, 1, 1, 1, '2024-09-02', 'Alergia a lácteos, traer lunch especial', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(4, 4, 1, 2, 1, '2024-09-02', 'Estudiante regular', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(5, 5, 1, 2, 1, '2024-09-03', 'Alergia al polen, evitar áreas con flores', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(6, 6, 1, 2, 1, '2024-09-03', 'Estudiante regular', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(7, 7, 1, 1, 1, '2024-09-04', 'Rinitis alérgica, traer medicamento', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1),
(8, 11, 1, 3, 1, '2025-11-11', '', '2025-11-11 18:59:50', NULL, 1),
(9, 12, 1, 1, 1, '2025-11-11', '', '2025-11-11 19:30:17', NULL, 1),
(10, 13, 1, 1, 1, '2025-11-11', '', '2025-11-11 20:02:31', NULL, 1),
(11, 14, 1, 2, 1, '2025-11-11', '', '2025-11-11 22:16:43', NULL, 1),
(12, 15, 1, 3, 1, '2025-11-11', '', '2025-11-11 22:52:57', NULL, 1),
(13, 16, 1, 1, 1, '2025-11-13', '', '2025-11-13 01:21:00', NULL, 1),
(14, 17, 1, 1, 1, '2025-11-20', '', '2025-11-20 03:45:22', NULL, 1),
(15, 18, 1, 2, 1, '2025-11-20', '', '2025-11-20 04:15:06', NULL, 1),
(16, 19, 1, 1, 1, '2025-11-20', '', '2025-11-20 04:38:27', NULL, 1),
(17, 11, 2, 2, 1, '2025-11-20', '', '2025-11-20 05:19:15', NULL, 1),
(18, 1, 2, 2, 1, '2025-11-20', '', '2025-11-20 05:27:22', NULL, 1),
(19, 13, 2, 1, 1, '2025-11-20', '', '2025-11-20 05:28:32', NULL, 1),
(20, 20, 1, 1, 1, '2025-11-20', '', '2025-11-20 20:05:39', NULL, 1),
(21, 21, 1, 2, 1, '2025-11-20', '', '2025-11-20 20:09:12', NULL, 1),
(22, 14, 2, 3, 1, '2025-11-20', '', '2025-11-20 21:37:57', NULL, 1),
(23, 22, 1, 2, 1, '2025-11-20', '', '2025-11-20 21:54:05', NULL, 1),
(24, 23, 1, 1, 1, '2025-11-20', '', '2025-11-20 22:01:18', NULL, 1),
(25, 24, 1, 2, 1, '2025-11-22', '', '2025-11-22 22:32:30', NULL, 1),
(26, 26, 1, 1, 1, '2025-11-24', '', '2025-11-24 00:27:06', NULL, 1),
(27, 27, 2, 1, 1, '2025-11-26', '', '2025-11-26 00:53:36', NULL, 1),
(28, 27, 1, 3, 1, '2025-11-25', '', '2025-11-26 00:54:27', NULL, 1),
(29, 28, 2, 1, 1, '2025-11-27', '', '2025-11-27 05:35:47', NULL, 1),
(30, 28, 1, 3, 1, '2025-11-27', '', '2025-11-27 05:39:30', NULL, 1),
(31, 28, 1, 3, 1, '2025-11-27', '', '2025-11-27 05:39:30', NULL, 1),
(32, 29, 2, 1, 1, '2025-11-28', '', '2025-11-28 04:37:12', NULL, 1),
(33, 29, 1, 3, 1, '2025-11-28', '', '2025-11-28 04:43:15', NULL, 1),
(34, 30, 2, 3, 1, '2025-11-29', '', '2025-11-28 23:32:52', NULL, 1),
(36, 32, 2, 3, 1, '2025-11-29', '', '2025-11-29 00:49:29', NULL, 1),
(38, 34, 2, 1, 1, '2025-11-29', '', '2025-11-29 01:26:47', NULL, 1),
(39, 35, 2, 1, 1, '2025-11-29', '', '2025-11-29 01:31:17', NULL, 1),
(41, 37, 2, 1, 1, '2025-11-29', '', '2025-11-29 02:36:49', NULL, 1),
(43, 39, 1, 1, 1, '2025-11-29', '', '2025-11-29 03:01:30', NULL, 1),
(44, 40, 1, 3, 1, '2025-11-29', '', '2025-11-29 03:13:49', NULL, 1),
(45, 41, 2, 1, 1, '2025-11-29', '', '2025-11-29 03:25:04', NULL, 1),
(46, 42, 2, 3, 1, '2025-11-29', '', '2025-11-29 03:45:48', NULL, 1),
(47, 43, 2, 3, 1, '2025-11-29', '', '2025-11-29 03:53:17', NULL, 1),
(48, 44, 1, 3, 1, '2025-11-29', '', '2025-11-29 15:06:58', NULL, 1),
(50, 46, 2, 3, 1, '2025-11-29', '', '2025-11-29 16:39:28', NULL, 1),
(52, 48, 2, 3, 1, '2025-11-29', '', '2025-11-29 16:54:26', NULL, 1),
(53, 49, 2, 1, 1, '2025-11-30', '', '2025-11-30 01:39:09', NULL, 1),
(54, 50, 2, 1, 1, '2025-11-30', '', '2025-11-30 01:55:13', NULL, 1),
(55, 39, 2, 3, 1, '2025-11-29', '', '2025-11-30 02:15:29', NULL, 1),
(56, 40, 2, 3, 1, '2025-11-29', '', '2025-11-30 02:22:23', NULL, 1),
(57, 51, 1, 21, 1, '2025-11-30', '', '2025-11-30 14:45:26', NULL, 1),
(58, 51, 3, 22, 1, '2025-11-30', '', '2025-11-30 15:46:35', NULL, 1),
(59, 44, 3, 9, 1, '2025-11-30', '', '2025-11-30 16:50:32', NULL, 1),
(60, 48, 3, 9, 1, '2025-11-30', '', '2025-11-30 17:34:37', NULL, 1),
(61, 50, 3, 3, 1, '2025-11-30', '', '2025-11-30 17:35:42', NULL, 1),
(62, 49, 3, 9, 1, '2025-11-30', '', '2025-11-30 17:52:23', NULL, 1),
(63, 42, 3, 13, 1, '2025-11-30', '', '2025-11-30 17:53:59', NULL, 1),
(64, 35, 3, 6, 1, '2025-11-30', '', '2025-11-30 18:00:35', NULL, 1),
(65, 39, 3, 9, 1, '2025-11-30', '', '2025-11-30 18:31:11', NULL, 1),
(66, 52, 3, 17, 1, '2025-12-01', '', '2025-12-01 04:46:04', NULL, 1),
(67, 53, 3, 21, 1, '2025-12-01', '', '2025-12-01 06:00:26', NULL, 1),
(68, 54, 3, 17, 1, '2025-12-01', '', '2025-12-01 06:40:04', NULL, 1),
(69, 41, 3, 3, 1, '2025-12-01', '', '2025-12-01 06:46:09', NULL, 1),
(70, 55, 3, 21, 1, '2025-12-01', '', '2025-12-01 21:27:51', NULL, 1),
(71, 56, 1, 9, 1, '2025-12-01', '', '2025-12-01 22:08:02', NULL, 1),
(72, 56, 3, 13, 1, '2025-12-01', '', '2025-12-01 22:10:38', NULL, 1),
(73, 37, 3, 1, 1, '2025-12-01', '', '2025-12-02 00:50:03', NULL, 1),
(74, 57, 1, 13, 1, '2025-12-02', '', '2025-12-02 05:36:27', NULL, 1),
(75, 57, 3, 17, 1, '2025-12-02', '', '2025-12-02 05:39:22', NULL, 1),
(76, 58, 3, 1, 1, '2025-12-02', '', '2025-12-02 14:41:06', NULL, 1),
(77, 59, 3, 17, 1, '2025-12-02', '', '2025-12-02 17:09:44', NULL, 1),
(78, 32, 3, 13, 1, '2025-12-02', '', '2025-12-02 17:11:04', NULL, 1),
(79, 60, 3, 9, 1, '2025-12-02', '', '2025-12-02 20:53:30', NULL, 1),
(80, 30, 3, 9, 1, '2025-12-02', '', '2025-12-02 20:55:16', NULL, 1),
(81, 61, 3, 17, 1, '2025-12-02', '', '2025-12-02 21:15:59', NULL, 1),
(82, 46, 3, 14, 1, '2025-12-02', '', '2025-12-02 21:23:10', NULL, 1),
(83, 34, 3, 3, 1, '2025-12-02', '', '2025-12-02 21:31:38', NULL, 1),
(84, 40, 3, 3, 1, '2025-12-02', '', '2025-12-02 21:32:27', NULL, 1),
(85, 62, 1, 13, 1, '2025-12-02', '', '2025-12-02 21:40:49', NULL, 1),
(86, 62, 3, 17, 1, '2025-12-02', '', '2025-12-02 21:45:12', NULL, 1),
(87, 63, 2, 13, 1, '2025-12-03', '', '2025-12-03 01:23:16', NULL, 1),
(88, 63, 3, 17, 1, '2025-12-02', '', '2025-12-03 01:28:25', NULL, 1),
(89, 64, 1, 23, 1, '2025-12-07', '', '2025-12-07 22:04:29', NULL, 1),
(90, 65, 3, 24, 1, '2025-12-07', '', '2025-12-07 22:10:29', NULL, 1),
(91, 64, 3, 24, 1, '2025-12-07', '', '2025-12-08 00:18:01', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `nom_municipio` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `id_estado`, `nom_municipio`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'Acevedo', '2025-11-10 06:04:56', NULL, 1),
(2, 1, 'Andrés Bello', '2025-11-10 06:04:56', NULL, 1),
(3, 1, 'Baruta', '2025-11-10 06:04:56', NULL, 1),
(4, 1, 'Brión', '2025-11-10 06:04:56', NULL, 1),
(5, 1, 'Buroz', '2025-11-10 06:04:56', NULL, 1),
(6, 1, 'Carrizal', '2025-11-10 06:04:56', NULL, 1),
(7, 1, 'Chacao', '2025-11-10 06:04:56', NULL, 1),
(8, 1, 'Cristóbal Rojas', '2025-11-10 06:04:56', NULL, 1),
(9, 1, 'El Hatillo', '2025-11-10 06:04:56', NULL, 1),
(10, 1, 'Guaicaipuro', '2025-11-10 06:04:56', NULL, 1),
(11, 1, 'Independencia', '2025-11-10 06:04:56', NULL, 1),
(12, 1, 'Lander', '2025-11-10 06:04:56', NULL, 1),
(13, 1, 'Los Salias', '2025-11-10 06:04:56', NULL, 1),
(14, 1, 'Páez', '2025-11-10 06:04:56', NULL, 1),
(15, 1, 'Paz Castillo', '2025-11-10 06:04:56', NULL, 1),
(16, 1, 'Pedro Gual', '2025-11-10 06:04:56', NULL, 1),
(17, 1, 'Plaza', '2025-11-10 06:04:56', NULL, 1),
(18, 1, 'Simón Bolívar', '2025-11-10 06:04:56', NULL, 1),
(19, 1, 'Sucre', '2025-11-10 06:04:56', NULL, 1),
(20, 1, 'Urdaneta', '2025-11-10 06:04:56', NULL, 1),
(21, 1, 'Zamora', '2025-11-10 06:04:56', NULL, 1),
(22, 2, 'Vargas', '2025-11-10 06:04:56', NULL, 1),
(23, 3, 'Libertador', '2025-11-10 06:04:56', NULL, 1),
(24, 4, 'Alto Orinoco', '2025-11-30 21:07:20', NULL, 1),
(25, 4, 'Atabapo', '2025-11-30 21:07:20', NULL, 1),
(26, 4, 'Atures', '2025-11-30 21:07:20', NULL, 1),
(27, 4, 'Autana', '2025-11-30 21:07:20', NULL, 1),
(28, 4, 'Manapiare', '2025-11-30 21:07:20', NULL, 1),
(29, 4, 'Maroa', '2025-11-30 21:07:20', NULL, 1),
(30, 4, 'Río Negro', '2025-11-30 21:07:20', NULL, 1),
(31, 5, 'Anaco', '2025-11-30 21:07:20', NULL, 1),
(32, 5, 'Aragua', '2025-11-30 21:07:20', NULL, 1),
(33, 5, 'Manuel Ezequiel Bruzual', '2025-11-30 21:07:20', NULL, 1),
(34, 5, 'Diego Bautista Urbaneja', '2025-11-30 21:07:20', NULL, 1),
(35, 5, 'Fernando Peñalver', '2025-11-30 21:07:20', NULL, 1),
(36, 5, 'Francisco Del Carmen Carvajal', '2025-11-30 21:07:20', NULL, 1),
(37, 5, 'General Sir Arthur McGregor', '2025-11-30 21:07:20', NULL, 1),
(38, 5, 'Guanta', '2025-11-30 21:07:20', NULL, 1),
(39, 5, 'Independencia', '2025-11-30 21:07:20', NULL, 1),
(40, 5, 'José Gregorio Monagas', '2025-11-30 21:07:20', NULL, 1),
(41, 5, 'Juan Antonio Sotillo', '2025-11-30 21:07:20', NULL, 1),
(42, 5, 'Juan Manuel Cajigal', '2025-11-30 21:07:20', NULL, 1),
(43, 5, 'Libertad', '2025-11-30 21:07:20', NULL, 1),
(44, 5, 'Francisco de Miranda', '2025-11-30 21:07:20', NULL, 1),
(45, 5, 'Pedro María Freites', '2025-11-30 21:07:20', NULL, 1),
(46, 5, 'Píritu', '2025-11-30 21:07:20', NULL, 1),
(47, 5, 'San José de Guanipa', '2025-11-30 21:07:20', NULL, 1),
(48, 5, 'San Juan de Capistrano', '2025-11-30 21:07:20', NULL, 1),
(49, 5, 'Santa Ana', '2025-11-30 21:07:20', NULL, 1),
(50, 5, 'Simón Bolívar', '2025-11-30 21:07:20', NULL, 1),
(51, 5, 'Simón Rodríguez', '2025-11-30 21:07:20', NULL, 1),
(52, 6, 'Achaguas', '2025-11-30 21:07:20', NULL, 1),
(53, 6, 'Biruaca', '2025-11-30 21:07:20', NULL, 1),
(54, 6, 'Muñóz', '2025-11-30 21:07:20', NULL, 1),
(55, 6, 'Páez', '2025-11-30 21:07:20', NULL, 1),
(56, 6, 'Pedro Camejo', '2025-11-30 21:07:20', NULL, 1),
(57, 6, 'Rómulo Gallegos', '2025-11-30 21:07:20', NULL, 1),
(58, 6, 'San Fernando', '2025-11-30 21:07:20', NULL, 1),
(59, 7, 'Atanasio Girardot', '2025-11-30 21:07:20', NULL, 1),
(60, 7, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(61, 7, 'Camatagua', '2025-11-30 21:07:20', NULL, 1),
(62, 7, 'Francisco Linares Alcántara', '2025-11-30 21:07:20', NULL, 1),
(63, 7, 'José Ángel Lamas', '2025-11-30 21:07:20', NULL, 1),
(64, 7, 'José Félix Ribas', '2025-11-30 21:07:20', NULL, 1),
(65, 7, 'José Rafael Revenga', '2025-11-30 21:07:20', NULL, 1),
(66, 7, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(67, 7, 'Mario Briceño Iragorry', '2025-11-30 21:07:20', NULL, 1),
(68, 7, 'Ocumare de la Costa de Oro', '2025-11-30 21:07:20', NULL, 1),
(69, 7, 'San Casimiro', '2025-11-30 21:07:20', NULL, 1),
(70, 7, 'San Sebastián', '2025-11-30 21:07:20', NULL, 1),
(71, 7, 'Santiago Mariño', '2025-11-30 21:07:20', NULL, 1),
(72, 7, 'Santos Michelena', '2025-11-30 21:07:20', NULL, 1),
(73, 7, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(74, 7, 'Tovar', '2025-11-30 21:07:20', NULL, 1),
(75, 7, 'Urdaneta', '2025-11-30 21:07:20', NULL, 1),
(76, 7, 'Zamora', '2025-11-30 21:07:20', NULL, 1),
(77, 8, 'Alberto Arvelo Torrealba', '2025-11-30 21:07:20', NULL, 1),
(78, 8, 'Andrés Eloy Blanco', '2025-11-30 21:07:20', NULL, 1),
(79, 8, 'Antonio José de Sucre', '2025-11-30 21:07:20', NULL, 1),
(80, 8, 'Arismendi', '2025-11-30 21:07:20', NULL, 1),
(81, 8, 'Barinas', '2025-11-30 21:07:20', NULL, 1),
(82, 8, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(83, 8, 'Cruz Paredes', '2025-11-30 21:07:20', NULL, 1),
(84, 8, 'Ezequiel Zamora', '2025-11-30 21:07:20', NULL, 1),
(85, 8, 'Obispos', '2025-11-30 21:07:20', NULL, 1),
(86, 8, 'Pedraza', '2025-11-30 21:07:20', NULL, 1),
(87, 8, 'Rojas', '2025-11-30 21:07:20', NULL, 1),
(88, 8, 'Sosa', '2025-11-30 21:07:20', NULL, 1),
(89, 9, 'Caroní', '2025-11-30 21:07:20', NULL, 1),
(90, 9, 'Cedeño', '2025-11-30 21:07:20', NULL, 1),
(91, 9, 'El Callao', '2025-11-30 21:07:20', NULL, 1),
(92, 9, 'Gran Sabana', '2025-11-30 21:07:20', NULL, 1),
(93, 9, 'Heres', '2025-11-30 21:07:20', NULL, 1),
(94, 9, 'Piar', '2025-11-30 21:07:20', NULL, 1),
(95, 9, 'Angostura (Raúl Leoni)', '2025-11-30 21:07:20', NULL, 1),
(96, 9, 'Roscio', '2025-11-30 21:07:20', NULL, 1),
(97, 9, 'Sifontes', '2025-11-30 21:07:20', NULL, 1),
(98, 9, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(99, 9, 'Padre Pedro Chien', '2025-11-30 21:07:20', NULL, 1),
(100, 10, 'Bejuma', '2025-11-30 21:07:20', NULL, 1),
(101, 10, 'Carlos Arvelo', '2025-11-30 21:07:20', NULL, 1),
(102, 10, 'Diego Ibarra', '2025-11-30 21:07:20', NULL, 1),
(103, 10, 'Guacara', '2025-11-30 21:07:20', NULL, 1),
(104, 10, 'Juan José Mora', '2025-11-30 21:07:20', NULL, 1),
(105, 10, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(106, 10, 'Los Guayos', '2025-11-30 21:07:20', NULL, 1),
(107, 10, 'Miranda', '2025-11-30 21:07:20', NULL, 1),
(108, 10, 'Montalbán', '2025-11-30 21:07:20', NULL, 1),
(109, 10, 'Naguanagua', '2025-11-30 21:07:20', NULL, 1),
(110, 10, 'Puerto Cabello', '2025-11-30 21:07:20', NULL, 1),
(111, 10, 'San Diego', '2025-11-30 21:07:20', NULL, 1),
(112, 10, 'San Joaquín', '2025-11-30 21:07:20', NULL, 1),
(113, 10, 'Valencia', '2025-11-30 21:07:20', NULL, 1),
(114, 11, 'Anzoátegui', '2025-11-30 21:07:20', NULL, 1),
(115, 11, 'Tinaquillo', '2025-11-30 21:07:20', NULL, 1),
(116, 11, 'Girardot', '2025-11-30 21:07:20', NULL, 1),
(117, 11, 'Lima Blanco', '2025-11-30 21:07:20', NULL, 1),
(118, 11, 'Pao de San Juan Bautista', '2025-11-30 21:07:20', NULL, 1),
(119, 11, 'Ricaurte', '2025-11-30 21:07:20', NULL, 1),
(120, 11, 'Rómulo Gallegos', '2025-11-30 21:07:20', NULL, 1),
(121, 11, 'San Carlos', '2025-11-30 21:07:20', NULL, 1),
(122, 11, 'Tinaco', '2025-11-30 21:07:20', NULL, 1),
(123, 12, 'Antonio Díaz', '2025-11-30 21:07:20', NULL, 1),
(124, 12, 'Casacoima', '2025-11-30 21:07:20', NULL, 1),
(125, 12, 'Pedernales', '2025-11-30 21:07:20', NULL, 1),
(126, 12, 'Tucupita', '2025-11-30 21:07:20', NULL, 1),
(127, 13, 'Acosta', '2025-11-30 21:07:20', NULL, 1),
(128, 13, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(129, 13, 'Buchivacoa', '2025-11-30 21:07:20', NULL, 1),
(130, 13, 'Cacique Manaure', '2025-11-30 21:07:20', NULL, 1),
(131, 13, 'Carirubana', '2025-11-30 21:07:20', NULL, 1),
(132, 13, 'Colina', '2025-11-30 21:07:20', NULL, 1),
(133, 13, 'Dabajuro', '2025-11-30 21:07:20', NULL, 1),
(134, 13, 'Democracia', '2025-11-30 21:07:20', NULL, 1),
(135, 13, 'Falcón', '2025-11-30 21:07:20', NULL, 1),
(136, 13, 'Federación', '2025-11-30 21:07:20', NULL, 1),
(137, 13, 'Jacura', '2025-11-30 21:07:20', NULL, 1),
(138, 13, 'José Laurencio Silva', '2025-11-30 21:07:20', NULL, 1),
(139, 13, 'Los Taques', '2025-11-30 21:07:20', NULL, 1),
(140, 13, 'Mauroa', '2025-11-30 21:07:20', NULL, 1),
(141, 13, 'Miranda', '2025-11-30 21:07:20', NULL, 1),
(142, 13, 'Monseñor Iturriza', '2025-11-30 21:07:20', NULL, 1),
(143, 13, 'Palmasola', '2025-11-30 21:07:20', NULL, 1),
(144, 13, 'Petit', '2025-11-30 21:07:20', NULL, 1),
(145, 13, 'Píritu', '2025-11-30 21:07:20', NULL, 1),
(146, 13, 'San Francisco', '2025-11-30 21:07:20', NULL, 1),
(147, 13, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(148, 13, 'Tocópero', '2025-11-30 21:07:20', NULL, 1),
(149, 13, 'Unión', '2025-11-30 21:07:20', NULL, 1),
(150, 13, 'Urumaco', '2025-11-30 21:07:20', NULL, 1),
(151, 13, 'Zamora', '2025-11-30 21:07:20', NULL, 1),
(152, 14, 'Camaguán', '2025-11-30 21:07:20', NULL, 1),
(153, 14, 'Chaguaramas', '2025-11-30 21:07:20', NULL, 1),
(154, 14, 'El Socorro', '2025-11-30 21:07:20', NULL, 1),
(155, 14, 'José Félix Ribas', '2025-11-30 21:07:20', NULL, 1),
(156, 14, 'José Tadeo Monagas', '2025-11-30 21:07:20', NULL, 1),
(157, 14, 'Juan Germán Roscio', '2025-11-30 21:07:20', NULL, 1),
(158, 14, 'Julián Mellado', '2025-11-30 21:07:20', NULL, 1),
(159, 14, 'Las Mercedes', '2025-11-30 21:07:20', NULL, 1),
(160, 14, 'Leonardo Infante', '2025-11-30 21:07:20', NULL, 1),
(161, 14, 'Pedro Zaraza', '2025-11-30 21:07:20', NULL, 1),
(162, 14, 'Ortíz', '2025-11-30 21:07:20', NULL, 1),
(163, 14, 'San Gerónimo de Guayabal', '2025-11-30 21:07:20', NULL, 1),
(164, 14, 'San José de Guaribe', '2025-11-30 21:07:20', NULL, 1),
(165, 14, 'Santa María de Ipire', '2025-11-30 21:07:20', NULL, 1),
(166, 14, 'Sebastián Francisco de Miranda', '2025-11-30 21:07:20', NULL, 1),
(167, 15, 'Andrés Eloy Blanco', '2025-11-30 21:07:20', NULL, 1),
(168, 15, 'Crespo', '2025-11-30 21:07:20', NULL, 1),
(169, 15, 'Iribarren', '2025-11-30 21:07:20', NULL, 1),
(170, 15, 'Jiménez', '2025-11-30 21:07:20', NULL, 1),
(171, 15, 'Morán', '2025-11-30 21:07:20', NULL, 1),
(172, 15, 'Palavecino', '2025-11-30 21:07:20', NULL, 1),
(173, 15, 'Simón Planas', '2025-11-30 21:07:20', NULL, 1),
(174, 15, 'Torres', '2025-11-30 21:07:20', NULL, 1),
(175, 15, 'Urdaneta', '2025-11-30 21:07:20', NULL, 1),
(176, 16, 'Alberto Adriani', '2025-11-30 21:07:20', NULL, 1),
(177, 16, 'Andrés Bello', '2025-11-30 21:07:20', NULL, 1),
(178, 16, 'Antonio Pinto Salinas', '2025-11-30 21:07:20', NULL, 1),
(179, 16, 'Aricagua', '2025-11-30 21:07:20', NULL, 1),
(180, 16, 'Arzobispo Chacón', '2025-11-30 21:07:20', NULL, 1),
(181, 16, 'Campo Elías', '2025-11-30 21:07:20', NULL, 1),
(182, 16, 'Caracciolo Parra Olmedo', '2025-11-30 21:07:20', NULL, 1),
(183, 16, 'Cardenal Quintero', '2025-11-30 21:07:20', NULL, 1),
(184, 16, 'Guaraque', '2025-11-30 21:07:20', NULL, 1),
(185, 16, 'Julio César Salas', '2025-11-30 21:07:20', NULL, 1),
(186, 16, 'Justo Briceño', '2025-11-30 21:07:20', NULL, 1),
(187, 16, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(188, 16, 'Miranda', '2025-11-30 21:07:20', NULL, 1),
(189, 16, 'Obispo Ramos de Lora', '2025-11-30 21:07:20', NULL, 1),
(190, 16, 'Padre Noguera', '2025-11-30 21:07:20', NULL, 1),
(191, 16, 'Pueblo Llano', '2025-11-30 21:07:20', NULL, 1),
(192, 16, 'Rangel', '2025-11-30 21:07:20', NULL, 1),
(193, 16, 'Rivas Dávila', '2025-11-30 21:07:20', NULL, 1),
(194, 16, 'Santos Marquina', '2025-11-30 21:07:20', NULL, 1),
(195, 16, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(196, 16, 'Tovar', '2025-11-30 21:07:20', NULL, 1),
(197, 16, 'Tulio Febres Cordero', '2025-11-30 21:07:20', NULL, 1),
(198, 16, 'Zea', '2025-11-30 21:07:20', NULL, 1),
(199, 17, 'Acosta', '2025-11-30 21:07:20', NULL, 1),
(200, 17, 'Aguasay', '2025-11-30 21:07:20', NULL, 1),
(201, 17, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(202, 17, 'Caripe', '2025-11-30 21:07:20', NULL, 1),
(203, 17, 'Cedeño', '2025-11-30 21:07:20', NULL, 1),
(204, 17, 'Ezequiel Zamora', '2025-11-30 21:07:20', NULL, 1),
(205, 17, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(206, 17, 'Maturín', '2025-11-30 21:07:20', NULL, 1),
(207, 17, 'Piar', '2025-11-30 21:07:20', NULL, 1),
(208, 17, 'Punceres', '2025-11-30 21:07:20', NULL, 1),
(209, 17, 'Santa Bárbara', '2025-11-30 21:07:20', NULL, 1),
(210, 17, 'Sotillo', '2025-11-30 21:07:20', NULL, 1),
(211, 17, 'Uracoa', '2025-11-30 21:07:20', NULL, 1),
(212, 18, 'Antolín del Campo', '2025-11-30 21:07:20', NULL, 1),
(213, 18, 'Arismendi', '2025-11-30 21:07:20', NULL, 1),
(214, 18, 'García', '2025-11-30 21:07:20', NULL, 1),
(215, 18, 'Gómez', '2025-11-30 21:07:20', NULL, 1),
(216, 18, 'Maneiro', '2025-11-30 21:07:20', NULL, 1),
(217, 18, 'Marcano', '2025-11-30 21:07:20', NULL, 1),
(218, 18, 'Mariño', '2025-11-30 21:07:20', NULL, 1),
(219, 18, 'Península de Macanao', '2025-11-30 21:07:20', NULL, 1),
(220, 18, 'Tubores', '2025-11-30 21:07:20', NULL, 1),
(221, 18, 'Villalba', '2025-11-30 21:07:20', NULL, 1),
(222, 18, 'Díaz', '2025-11-30 21:07:20', NULL, 1),
(223, 19, 'Agua Blanca', '2025-11-30 21:07:20', NULL, 1),
(224, 19, 'Araure', '2025-11-30 21:07:20', NULL, 1),
(225, 19, 'Esteller', '2025-11-30 21:07:20', NULL, 1),
(226, 19, 'Guanare', '2025-11-30 21:07:20', NULL, 1),
(227, 19, 'Guanarito', '2025-11-30 21:07:20', NULL, 1),
(228, 19, 'Monseñor José Vicente de Unda', '2025-11-30 21:07:20', NULL, 1),
(229, 19, 'Ospino', '2025-11-30 21:07:20', NULL, 1),
(230, 19, 'Páez', '2025-11-30 21:07:20', NULL, 1),
(231, 19, 'Papelón', '2025-11-30 21:07:20', NULL, 1),
(232, 19, 'San Genaro de Boconoíto', '2025-11-30 21:07:20', NULL, 1),
(233, 19, 'San Rafael de Onoto', '2025-11-30 21:07:20', NULL, 1),
(234, 19, 'Santa Rosalía', '2025-11-30 21:07:20', NULL, 1),
(235, 19, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(236, 19, 'Turén', '2025-11-30 21:07:20', NULL, 1),
(237, 20, 'Andrés Eloy Blanco', '2025-11-30 21:07:20', NULL, 1),
(238, 20, 'Andrés Mata', '2025-11-30 21:07:20', NULL, 1),
(239, 20, 'Arismendi', '2025-11-30 21:07:20', NULL, 1),
(240, 20, 'Benítez', '2025-11-30 21:07:20', NULL, 1),
(241, 20, 'Bermúdez', '2025-11-30 21:07:20', NULL, 1),
(242, 20, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(243, 20, 'Cajigal', '2025-11-30 21:07:20', NULL, 1),
(244, 20, 'Cruz Salmerón Acosta', '2025-11-30 21:07:20', NULL, 1),
(245, 20, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(246, 20, 'Mariño', '2025-11-30 21:07:20', NULL, 1),
(247, 20, 'Mejía', '2025-11-30 21:07:20', NULL, 1),
(248, 20, 'Montes', '2025-11-30 21:07:20', NULL, 1),
(249, 20, 'Ribero', '2025-11-30 21:07:20', NULL, 1),
(250, 20, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(251, 20, 'Valdéz', '2025-11-30 21:07:20', NULL, 1),
(252, 21, 'Andrés Bello', '2025-11-30 21:07:20', NULL, 1),
(253, 21, 'Antonio Rómulo Costa', '2025-11-30 21:07:20', NULL, 1),
(254, 21, 'Ayacucho', '2025-11-30 21:07:20', NULL, 1),
(255, 21, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(256, 21, 'Cárdenas', '2025-11-30 21:07:20', NULL, 1),
(257, 21, 'Córdoba', '2025-11-30 21:07:20', NULL, 1),
(258, 21, 'Fernández Feo', '2025-11-30 21:07:20', NULL, 1),
(259, 21, 'Francisco de Miranda', '2025-11-30 21:07:20', NULL, 1),
(260, 21, 'García de Hevia', '2025-11-30 21:07:20', NULL, 1),
(261, 21, 'Guásimos', '2025-11-30 21:07:20', NULL, 1),
(262, 21, 'Independencia', '2025-11-30 21:07:20', NULL, 1),
(263, 21, 'Jáuregui', '2025-11-30 21:07:20', NULL, 1),
(264, 21, 'José María Vargas', '2025-11-30 21:07:20', NULL, 1),
(265, 21, 'Junín', '2025-11-30 21:07:20', NULL, 1),
(266, 21, 'Libertad', '2025-11-30 21:07:20', NULL, 1),
(267, 21, 'Libertador', '2025-11-30 21:07:20', NULL, 1),
(268, 21, 'Lobatera', '2025-11-30 21:07:20', NULL, 1),
(269, 21, 'Michelena', '2025-11-30 21:07:20', NULL, 1),
(270, 21, 'Panamericano', '2025-11-30 21:07:20', NULL, 1),
(271, 21, 'Pedro María Ureña', '2025-11-30 21:07:20', NULL, 1),
(272, 21, 'Rafael Urdaneta', '2025-11-30 21:07:20', NULL, 1),
(273, 21, 'Samuel Darío Maldonado', '2025-11-30 21:07:20', NULL, 1),
(274, 21, 'San Cristóbal', '2025-11-30 21:07:20', NULL, 1),
(275, 21, 'Seboruco', '2025-11-30 21:07:20', NULL, 1),
(276, 21, 'Simón Rodríguez', '2025-11-30 21:07:20', NULL, 1),
(277, 21, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(278, 21, 'Torbes', '2025-11-30 21:07:20', NULL, 1),
(279, 21, 'Uribante', '2025-11-30 21:07:20', NULL, 1),
(280, 21, 'San Judas Tadeo', '2025-11-30 21:07:20', NULL, 1),
(281, 22, 'Andrés Bello', '2025-11-30 21:07:20', NULL, 1),
(282, 22, 'Boconó', '2025-11-30 21:07:20', NULL, 1),
(283, 22, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(284, 22, 'Candelaria', '2025-11-30 21:07:20', NULL, 1),
(285, 22, 'Carache', '2025-11-30 21:07:20', NULL, 1),
(286, 22, 'Escuque', '2025-11-30 21:07:20', NULL, 1),
(287, 22, 'José Felipe Márquez Cañizalez', '2025-11-30 21:07:20', NULL, 1),
(288, 22, 'Juan Vicente Campos Elías', '2025-11-30 21:07:20', NULL, 1),
(289, 22, 'La Ceiba', '2025-11-30 21:07:20', NULL, 1),
(290, 22, 'Miranda', '2025-11-30 21:07:20', NULL, 1),
(291, 22, 'Monte Carmelo', '2025-11-30 21:07:20', NULL, 1),
(292, 22, 'Motatán', '2025-11-30 21:07:20', NULL, 1),
(293, 22, 'Pampán', '2025-11-30 21:07:20', NULL, 1),
(294, 22, 'Pampanito', '2025-11-30 21:07:20', NULL, 1),
(295, 22, 'Rafael Rangel', '2025-11-30 21:07:20', NULL, 1),
(296, 22, 'San Rafael de Carvajal', '2025-11-30 21:07:20', NULL, 1),
(297, 22, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(298, 22, 'Trujillo', '2025-11-30 21:07:20', NULL, 1),
(299, 22, 'Urdaneta', '2025-11-30 21:07:20', NULL, 1),
(300, 22, 'Valera', '2025-11-30 21:07:20', NULL, 1),
(301, 23, 'Arístides Bastidas', '2025-11-30 21:07:20', NULL, 1),
(302, 23, 'Bolívar', '2025-11-30 21:07:20', NULL, 1),
(303, 23, 'Bruzual', '2025-11-30 21:07:20', NULL, 1),
(304, 23, 'Cocorote', '2025-11-30 21:07:20', NULL, 1),
(305, 23, 'Independencia', '2025-11-30 21:07:20', NULL, 1),
(306, 23, 'José Antonio Páez', '2025-11-30 21:07:20', NULL, 1),
(307, 23, 'La Trinidad', '2025-11-30 21:07:20', NULL, 1),
(308, 23, 'Manuel Monge', '2025-11-30 21:07:20', NULL, 1),
(309, 23, 'Nirgua', '2025-11-30 21:07:20', NULL, 1),
(310, 23, 'Peña', '2025-11-30 21:07:20', NULL, 1),
(311, 23, 'San Felipe', '2025-11-30 21:07:20', NULL, 1),
(312, 23, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(313, 23, 'Urachiche', '2025-11-30 21:07:20', NULL, 1),
(314, 23, 'José Joaquín Veroes', '2025-11-30 21:07:20', NULL, 1),
(315, 24, 'Almirante Padilla', '2025-11-30 21:07:20', NULL, 1),
(316, 24, 'Baralt', '2025-11-30 21:07:20', NULL, 1),
(317, 24, 'Cabimas', '2025-11-30 21:07:20', NULL, 1),
(318, 24, 'Catatumbo', '2025-11-30 21:07:20', NULL, 1),
(319, 24, 'Colón', '2025-11-30 21:07:20', NULL, 1),
(320, 24, 'Francisco Javier Pulgar', '2025-11-30 21:07:20', NULL, 1),
(321, 24, 'Páez', '2025-11-30 21:07:20', NULL, 1),
(322, 24, 'Jesús Enrique Losada', '2025-11-30 21:07:20', NULL, 1),
(323, 24, 'Jesús María Semprún', '2025-11-30 21:07:20', NULL, 1),
(324, 24, 'La Cañada de Urdaneta', '2025-11-30 21:07:20', NULL, 1),
(325, 24, 'Lagunillas', '2025-11-30 21:07:20', NULL, 1),
(326, 24, 'Machiques de Perijá', '2025-11-30 21:07:20', NULL, 1),
(327, 24, 'Mara', '2025-11-30 21:07:20', NULL, 1),
(328, 24, 'Maracaibo', '2025-11-30 21:07:20', NULL, 1),
(329, 24, 'Miranda', '2025-11-30 21:07:20', NULL, 1),
(330, 24, 'Rosario de Perijá', '2025-11-30 21:07:20', NULL, 1),
(331, 24, 'San Francisco', '2025-11-30 21:07:20', NULL, 1),
(332, 24, 'Santa Rita', '2025-11-30 21:07:20', NULL, 1),
(333, 24, 'Simón Bolívar', '2025-11-30 21:07:20', NULL, 1),
(334, 24, 'Sucre', '2025-11-30 21:07:20', NULL, 1),
(335, 24, 'Valmore Rodríguez', '2025-11-30 21:07:20', NULL, 1),
(336, 25, 'Libertador', '2025-11-30 21:07:20', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id_nivel` int(11) NOT NULL,
  `num_nivel` int(11) NOT NULL,
  `nom_nivel` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id_nivel`, `num_nivel`, `nom_nivel`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'Primer Grado', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 'Segundo Grado', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 'Tercer Grado', '2025-11-30 02:40:57', NULL, 1),
(4, 4, 'Cuarto Grado', '2025-11-30 02:40:57', NULL, 1),
(5, 5, 'Quinto Grado', '2025-11-30 02:40:57', NULL, 1),
(6, 6, 'Sexto Grado', '2025-11-30 02:40:57', NULL, 1),
(7, 1, 'Primer Año', '2025-11-30 02:45:02', NULL, 1),
(8, 2, 'Segundo Año', '2025-11-30 02:45:02', NULL, 1),
(9, 3, 'Tercer Año', '2025-11-30 02:45:02', NULL, 1),
(10, 4, 'Cuarto Año', '2025-11-30 02:45:02', NULL, 1),
(11, 5, 'Quinto Año', '2025-11-30 02:45:02', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles_secciones`
--

CREATE TABLE `niveles_secciones` (
  `id_nivel_seccion` int(11) NOT NULL,
  `id_nivel` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `niveles_secciones`
--

INSERT INTO `niveles_secciones` (`id_nivel_seccion`, `id_nivel`, `id_seccion`, `capacidad`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, 20, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 1, 2, 25, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 2, 1, 25, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 1, 3, 30, '2025-11-26 23:49:33', NULL, 1),
(5, 1, 4, 37, '2025-11-30 14:30:58', NULL, 1),
(6, 2, 2, 37, '2025-11-30 14:31:18', NULL, 1),
(7, 2, 3, 37, '2025-11-30 14:31:35', NULL, 1),
(8, 2, 4, 37, '2025-11-30 14:31:47', NULL, 1),
(9, 3, 1, 37, '2025-11-30 14:32:04', NULL, 1),
(10, 3, 2, 37, '2025-11-30 14:32:25', NULL, 1),
(11, 3, 3, 37, '2025-11-30 14:32:44', NULL, 1),
(12, 3, 4, 37, '2025-11-30 14:32:54', NULL, 1),
(13, 4, 1, 37, '2025-11-30 14:33:19', NULL, 1),
(14, 4, 2, 37, '2025-11-30 14:34:47', NULL, 1),
(15, 4, 3, 37, '2025-11-30 14:35:03', NULL, 1),
(16, 4, 4, 37, '2025-11-30 14:35:16', NULL, 1),
(17, 5, 1, 37, '2025-11-30 14:35:26', NULL, 1),
(18, 5, 2, 37, '2025-11-30 14:35:36', NULL, 1),
(19, 5, 3, 37, '2025-11-30 14:35:45', NULL, 1),
(20, 5, 4, 37, '2025-11-30 14:35:54', NULL, 1),
(21, 10, 1, 37, '2025-11-30 14:36:25', NULL, 1),
(22, 11, 1, 37, '2025-11-30 14:36:34', NULL, 1),
(23, 6, 1, 37, '2025-12-07 21:58:54', NULL, 1),
(24, 7, 1, 37, '2025-12-07 22:02:23', NULL, 1),
(25, 7, 2, 37, '2025-12-08 00:35:23', NULL, 1),
(26, 8, 1, 37, '2025-12-08 00:40:11', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parentesco`
--

CREATE TABLE `parentesco` (
  `id_parentesco` int(11) NOT NULL,
  `parentesco` varchar(20) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `parentesco`
--

INSERT INTO `parentesco` (`id_parentesco`, `parentesco`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Padre', '2025-11-23 23:38:42', NULL, 1),
(2, 'Madre', '2025-11-23 23:38:50', NULL, 1),
(3, 'Abuelo', '2025-11-23 23:38:59', NULL, 1),
(4, 'Abuela', '2025-11-23 23:39:06', NULL, 1),
(5, 'Tío', '2025-11-23 23:39:17', NULL, 1),
(6, 'Tía', '2025-11-23 23:39:24', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` int(11) NOT NULL,
  `id_municipio` int(11) NOT NULL,
  `nom_parroquia` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `id_municipio`, `nom_parroquia`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'Aragüita', '2025-11-10 06:07:08', NULL, 1),
(2, 1, 'Arévalo González', '2025-11-10 06:07:08', NULL, 1),
(3, 1, 'Capaya', '2025-11-10 06:07:08', NULL, 1),
(4, 1, 'Caucagua', '2025-11-10 06:07:08', NULL, 1),
(5, 1, 'Panaquire', '2025-11-10 06:07:08', NULL, 1),
(6, 1, 'Ribas', '2025-11-10 06:07:08', NULL, 1),
(7, 1, 'El Café', '2025-11-10 06:07:08', NULL, 1),
(8, 1, 'Marizapa', '2025-11-10 06:07:08', NULL, 1),
(9, 2, 'Cumbo', '2025-11-10 06:07:08', NULL, 1),
(10, 2, 'San José de Barlovento', '2025-11-10 06:07:08', NULL, 1),
(11, 3, 'El Cafetal', '2025-11-10 06:07:08', NULL, 1),
(12, 3, 'Las Minas', '2025-11-10 06:07:08', NULL, 1),
(13, 3, 'Nuestra Señora del Rosario', '2025-11-10 06:07:08', NULL, 1),
(14, 4, 'Higuerote', '2025-11-10 06:07:08', NULL, 1),
(15, 4, 'Curiepe', '2025-11-10 06:07:08', NULL, 1),
(16, 4, 'Tacarigua de Brión', '2025-11-10 06:07:08', NULL, 1),
(17, 5, 'Mamporal', '2025-11-10 06:07:08', NULL, 1),
(18, 6, 'Carrizal', '2025-11-10 06:07:08', NULL, 1),
(19, 7, 'Chacao', '2025-11-10 06:07:08', NULL, 1),
(20, 8, 'Charallave', '2025-11-10 06:07:08', NULL, 1),
(21, 8, 'Las Brisas', '2025-11-10 06:07:08', NULL, 1),
(22, 9, 'El Hatillo', '2025-11-10 06:07:08', NULL, 1),
(23, 10, 'Altagracia de la Montaña', '2025-11-10 06:07:08', NULL, 1),
(24, 10, 'Cecilio Acosta', '2025-11-10 06:07:08', NULL, 1),
(25, 10, 'Los Teques', '2025-11-10 06:07:08', NULL, 1),
(26, 10, 'El Jarillo', '2025-11-10 06:07:08', NULL, 1),
(27, 10, 'San Pedro', '2025-11-10 06:07:08', NULL, 1),
(28, 10, 'Tácata', '2025-11-10 06:07:08', NULL, 1),
(29, 10, 'Paracotos', '2025-11-10 06:07:08', NULL, 1),
(30, 11, 'Cartanal', '2025-11-10 06:07:08', NULL, 1),
(31, 11, 'Santa Teresa del Tuy', '2025-11-10 06:07:08', NULL, 1),
(32, 12, 'La Democracia', '2025-11-10 06:07:08', NULL, 1),
(33, 12, 'Ocumare del Tuy', '2025-11-10 06:07:08', NULL, 1),
(34, 12, 'Santa Bárbara', '2025-11-10 06:07:08', NULL, 1),
(35, 13, 'San Antonio de los Altos', '2025-11-10 06:07:08', NULL, 1),
(36, 14, 'Río Chico', '2025-11-10 06:07:08', NULL, 1),
(37, 14, 'El Guapo', '2025-11-10 06:07:08', NULL, 1),
(38, 14, 'Tacarigua de la Laguna', '2025-11-10 06:07:08', NULL, 1),
(39, 14, 'Paparo', '2025-11-10 06:07:08', NULL, 1),
(40, 14, 'San Fernando del Guapo', '2025-11-10 06:07:08', NULL, 1),
(41, 15, 'Santa Lucía del Tuy', '2025-11-10 06:07:08', NULL, 1),
(42, 16, 'Cúpira', '2025-11-10 06:07:08', NULL, 1),
(43, 16, 'Machurucuto', '2025-11-10 06:07:08', NULL, 1),
(44, 17, 'Guarenas', '2025-11-10 06:07:08', NULL, 1),
(45, 18, 'San Antonio de Yare', '2025-11-10 06:07:08', NULL, 1),
(46, 18, 'San Francisco de Yare', '2025-11-10 06:07:08', NULL, 1),
(47, 19, 'Leoncio Martínez', '2025-11-10 06:07:08', NULL, 1),
(48, 19, 'Petare', '2025-11-10 06:07:08', NULL, 1),
(49, 19, 'Caucagüita', '2025-11-10 06:07:08', NULL, 1),
(50, 19, 'Filas de Mariche', '2025-11-10 06:07:08', NULL, 1),
(51, 19, 'La Dolorita', '2025-11-10 06:07:08', NULL, 1),
(52, 20, 'Cúa', '2025-11-10 06:07:08', NULL, 1),
(53, 20, 'Nueva Cúa', '2025-11-10 06:07:08', NULL, 1),
(54, 21, 'Guatire', '2025-11-10 06:07:08', NULL, 1),
(55, 21, 'Bolívar', '2025-11-10 06:07:08', NULL, 1),
(56, 22, 'Caraballeda', '2025-11-10 06:07:08', NULL, 1),
(57, 22, 'Carayaca', '2025-11-10 06:07:08', NULL, 1),
(58, 22, 'Carlos Soublette', '2025-11-10 06:07:08', NULL, 1),
(59, 22, 'Caruao Chuspa', '2025-11-10 06:07:08', NULL, 1),
(60, 22, 'Catia La Mar', '2025-11-10 06:07:08', NULL, 1),
(61, 22, 'El Junko', '2025-11-10 06:07:08', NULL, 1),
(62, 22, 'La Guaira', '2025-11-10 06:07:08', NULL, 1),
(63, 22, 'Macuto', '2025-11-10 06:07:08', NULL, 1),
(64, 22, 'Maiquetía', '2025-11-10 06:07:08', NULL, 1),
(65, 22, 'Naiguatá', '2025-11-10 06:07:08', NULL, 1),
(66, 22, 'Urimare', '2025-11-10 06:07:08', NULL, 1),
(67, 23, 'Altagracia', '2025-11-10 06:07:08', NULL, 1),
(68, 23, 'Antímano', '2025-11-10 06:07:08', NULL, 1),
(69, 23, 'Caricuao', '2025-11-10 06:07:08', NULL, 1),
(70, 23, 'Catedral', '2025-11-10 06:07:08', NULL, 1),
(71, 23, 'Coche', '2025-11-10 06:07:08', NULL, 1),
(72, 23, 'El Junquito', '2025-11-10 06:07:08', NULL, 1),
(73, 23, 'El Paraíso', '2025-11-10 06:07:08', NULL, 1),
(74, 23, 'El Recreo', '2025-11-10 06:07:08', NULL, 1),
(75, 23, 'El Valle', '2025-11-10 06:07:08', NULL, 1),
(76, 23, 'La Candelaria', '2025-11-10 06:07:08', NULL, 1),
(77, 23, 'La Pastora', '2025-11-10 06:07:08', NULL, 1),
(78, 23, 'La Vega', '2025-11-10 06:07:08', NULL, 1),
(79, 23, 'Macarao', '2025-11-10 06:07:08', NULL, 1),
(80, 23, 'San Agustín', '2025-11-10 06:07:08', NULL, 1),
(81, 23, 'San Bernardino', '2025-11-10 06:07:08', NULL, 1),
(82, 23, 'San José', '2025-11-10 06:07:08', NULL, 1),
(83, 23, 'San Juan', '2025-11-10 06:07:08', NULL, 1),
(84, 23, 'San Pedro', '2025-11-10 06:07:08', NULL, 1),
(85, 23, 'Santa Rosalía', '2025-11-10 06:07:08', NULL, 1),
(86, 23, 'Santa Teresa', '2025-11-10 06:07:08', NULL, 1),
(87, 23, 'Sucre (Catia)', '2025-11-10 06:07:08', NULL, 1),
(88, 23, '23 de enero', '2025-11-10 06:07:08', NULL, 1),
(89, 24, 'Alto Orinoco', '2025-11-30 21:10:14', NULL, 1),
(90, 24, 'Huachamacare Acanaña', '2025-11-30 21:10:14', NULL, 1),
(91, 24, 'Marawaka Toky Shamanaña', '2025-11-30 21:10:14', NULL, 1),
(92, 24, 'Mavaka Mavaka', '2025-11-30 21:10:14', NULL, 1),
(93, 24, 'Sierra Parima Parimabé', '2025-11-30 21:10:14', NULL, 1),
(94, 25, 'Ucata Laja Lisa', '2025-11-30 21:10:14', NULL, 1),
(95, 25, 'Yapacana Macuruco', '2025-11-30 21:10:14', NULL, 1),
(96, 25, 'Caname Guarinuma', '2025-11-30 21:10:14', NULL, 1),
(97, 26, 'Fernando Girón Tovar', '2025-11-30 21:10:14', NULL, 1),
(98, 26, 'Luis Alberto Gómez', '2025-11-30 21:10:14', NULL, 1),
(99, 26, 'Pahueña Limón de Parhueña', '2025-11-30 21:10:14', NULL, 1),
(100, 26, 'Platanillal Platanillal', '2025-11-30 21:10:14', NULL, 1),
(101, 27, 'Samariapo', '2025-11-30 21:10:14', NULL, 1),
(102, 27, 'Sipapo', '2025-11-30 21:10:14', NULL, 1),
(103, 27, 'Munduapo', '2025-11-30 21:10:14', NULL, 1),
(104, 27, 'Guayapo', '2025-11-30 21:10:14', NULL, 1),
(105, 28, 'Alto Ventuari', '2025-11-30 21:10:14', NULL, 1),
(106, 28, 'Medio Ventuari', '2025-11-30 21:10:14', NULL, 1),
(107, 28, 'Bajo Ventuari', '2025-11-30 21:10:14', NULL, 1),
(108, 29, 'Victorino', '2025-11-30 21:10:14', NULL, 1),
(109, 29, 'Comunidad', '2025-11-30 21:10:14', NULL, 1),
(110, 30, 'Casiquiare', '2025-11-30 21:10:14', NULL, 1),
(111, 30, 'Cocuy', '2025-11-30 21:10:14', NULL, 1),
(112, 30, 'San Carlos de Río Negro', '2025-11-30 21:10:14', NULL, 1),
(113, 30, 'Solano', '2025-11-30 21:10:14', NULL, 1),
(114, 31, 'Anaco', '2025-11-30 21:10:14', NULL, 1),
(115, 31, 'San Joaquín', '2025-11-30 21:10:14', NULL, 1),
(116, 32, 'Cachipo', '2025-11-30 21:10:14', NULL, 1),
(117, 32, 'Aragua de Barcelona', '2025-11-30 21:10:14', NULL, 1),
(118, 34, 'Lechería', '2025-11-30 21:10:14', NULL, 1),
(119, 34, 'El Morro', '2025-11-30 21:10:14', NULL, 1),
(120, 35, 'Puerto Píritu', '2025-11-30 21:10:14', NULL, 1),
(121, 35, 'San Miguel', '2025-11-30 21:10:14', NULL, 1),
(122, 35, 'Sucre', '2025-11-30 21:10:14', NULL, 1),
(123, 36, 'Valle de Guanape', '2025-11-30 21:10:14', NULL, 1),
(124, 36, 'Santa Bárbara', '2025-11-30 21:10:14', NULL, 1),
(125, 37, 'El Chaparro', '2025-11-30 21:10:14', NULL, 1),
(126, 37, 'Tomás Alfaro', '2025-11-30 21:10:14', NULL, 1),
(127, 37, 'Calatrava', '2025-11-30 21:10:14', NULL, 1),
(128, 38, 'Guanta', '2025-11-30 21:10:14', NULL, 1),
(129, 38, 'Chorrerón', '2025-11-30 21:10:14', NULL, 1),
(130, 39, 'Mamo', '2025-11-30 21:10:14', NULL, 1),
(131, 39, 'Soledad', '2025-11-30 21:10:14', NULL, 1),
(132, 40, 'Mapire', '2025-11-30 21:10:14', NULL, 1),
(133, 40, 'Piar', '2025-11-30 21:10:14', NULL, 1),
(134, 40, 'Santa Clara', '2025-11-30 21:10:14', NULL, 1),
(135, 40, 'San Diego de Cabrutica', '2025-11-30 21:10:14', NULL, 1),
(136, 40, 'Uverito', '2025-11-30 21:10:14', NULL, 1),
(137, 40, 'Zuata', '2025-11-30 21:10:14', NULL, 1),
(138, 41, 'Puerto La Cruz', '2025-11-30 21:10:14', NULL, 1),
(139, 41, 'Pozuelos', '2025-11-30 21:10:14', NULL, 1),
(140, 42, 'Onoto', '2025-11-30 21:10:14', NULL, 1),
(141, 42, 'San Pablo', '2025-11-30 21:10:14', NULL, 1),
(142, 43, 'San Mateo', '2025-11-30 21:10:14', NULL, 1),
(143, 43, 'El Carito', '2025-11-30 21:10:14', NULL, 1),
(144, 43, 'Santa Inés', '2025-11-30 21:10:14', NULL, 1),
(145, 43, 'La Romereña', '2025-11-30 21:10:14', NULL, 1),
(146, 44, 'Atapirire', '2025-11-30 21:10:14', NULL, 1),
(147, 44, 'Boca del Pao', '2025-11-30 21:10:14', NULL, 1),
(148, 44, 'El Pao', '2025-11-30 21:10:14', NULL, 1),
(149, 44, 'Pariaguán', '2025-11-30 21:10:14', NULL, 1),
(150, 45, 'Cantaura', '2025-11-30 21:10:14', NULL, 1),
(151, 45, 'Libertador', '2025-11-30 21:10:14', NULL, 1),
(152, 45, 'Santa Rosa', '2025-11-30 21:10:14', NULL, 1),
(153, 45, 'Urica', '2025-11-30 21:10:14', NULL, 1),
(154, 46, 'Píritu', '2025-11-30 21:10:14', NULL, 1),
(155, 46, 'San Francisco', '2025-11-30 21:10:14', NULL, 1),
(156, 47, 'San José de Guanipa', '2025-11-30 21:10:14', NULL, 1),
(157, 48, 'Boca de Uchire', '2025-11-30 21:10:14', NULL, 1),
(158, 48, 'Boca de Chávez', '2025-11-30 21:10:14', NULL, 1),
(159, 49, 'Pueblo Nuevo', '2025-11-30 21:10:14', NULL, 1),
(160, 49, 'Santa Ana', '2025-11-30 21:10:14', NULL, 1),
(161, 50, 'Bergantín', '2025-11-30 21:10:14', NULL, 1),
(162, 50, 'Caigua', '2025-11-30 21:10:14', NULL, 1),
(163, 50, 'El Carmen', '2025-11-30 21:10:14', NULL, 1),
(164, 50, 'El Pilar', '2025-11-30 21:10:14', NULL, 1),
(165, 50, 'Naricual', '2025-11-30 21:10:14', NULL, 1),
(166, 50, 'San Cristóbal', '2025-11-30 21:10:14', NULL, 1),
(167, 51, 'Edmundo Barrios', '2025-11-30 21:10:14', NULL, 1),
(168, 51, 'Miguel Otero Silva', '2025-11-30 21:10:14', NULL, 1),
(169, 52, 'Achaguas', '2025-11-30 21:10:14', NULL, 1),
(170, 52, 'Apurito', '2025-11-30 21:10:14', NULL, 1),
(171, 52, 'El Yagual', '2025-11-30 21:10:14', NULL, 1),
(172, 52, 'Guachara', '2025-11-30 21:10:14', NULL, 1),
(173, 52, 'Mucuritas', '2025-11-30 21:10:14', NULL, 1),
(174, 52, 'Queseras del medio', '2025-11-30 21:10:14', NULL, 1),
(175, 53, 'Biruaca', '2025-11-30 21:10:14', NULL, 1),
(176, 54, 'Bruzual', '2025-11-30 21:10:14', NULL, 1),
(177, 54, 'Mantecal', '2025-11-30 21:10:14', NULL, 1),
(178, 54, 'Quintero', '2025-11-30 21:10:14', NULL, 1),
(179, 54, 'Rincón Hondo', '2025-11-30 21:10:14', NULL, 1),
(180, 54, 'San Vicente', '2025-11-30 21:10:14', NULL, 1),
(181, 55, 'Guasdualito', '2025-11-30 21:10:14', NULL, 1),
(182, 55, 'Aramendi', '2025-11-30 21:10:14', NULL, 1),
(183, 55, 'El Amparo', '2025-11-30 21:10:14', NULL, 1),
(184, 55, 'San Camilo', '2025-11-30 21:10:14', NULL, 1),
(185, 55, 'Urdaneta', '2025-11-30 21:10:14', NULL, 1),
(186, 56, 'San Juan de Payara', '2025-11-30 21:10:14', NULL, 1),
(187, 56, 'Codazzi', '2025-11-30 21:10:14', NULL, 1),
(188, 56, 'Cunaviche', '2025-11-30 21:10:14', NULL, 1),
(189, 57, 'Elorza', '2025-11-30 21:10:14', NULL, 1),
(190, 57, 'La Trinidad', '2025-11-30 21:10:14', NULL, 1),
(191, 58, 'San Fernando', '2025-11-30 21:10:14', NULL, 1),
(192, 58, 'El Recreo', '2025-11-30 21:10:14', NULL, 1),
(193, 58, 'Peñalver', '2025-11-30 21:10:14', NULL, 1),
(194, 58, 'San Rafael de Atamaica', '2025-11-30 21:10:14', NULL, 1),
(195, 59, 'Pedro José Ovalles', '2025-11-30 21:10:14', NULL, 1),
(196, 59, 'Joaquín Crespo', '2025-11-30 21:10:14', NULL, 1),
(197, 59, 'José Casanova Godoy', '2025-11-30 21:10:14', NULL, 1),
(198, 59, 'Madre María de San José', '2025-11-30 21:10:14', NULL, 1),
(199, 59, 'Andrés Eloy Blanco', '2025-11-30 21:10:14', NULL, 1),
(200, 59, 'Los Tacarigua', '2025-11-30 21:10:14', NULL, 1),
(201, 59, 'Las Delicias', '2025-11-30 21:10:14', NULL, 1),
(202, 59, 'Choroní', '2025-11-30 21:10:14', NULL, 1),
(203, 60, 'Bolívar', '2025-11-30 21:10:14', NULL, 1),
(204, 61, 'Camatagua', '2025-11-30 21:10:14', NULL, 1),
(205, 61, 'Carmen de Cura', '2025-11-30 21:10:14', NULL, 1),
(206, 62, 'Santa Rita', '2025-11-30 21:10:14', NULL, 1),
(207, 62, 'Francisco de Miranda', '2025-11-30 21:10:14', NULL, 1),
(208, 62, 'Moseñor Feliciano González', '2025-11-30 21:10:14', NULL, 1),
(209, 63, 'Santa Cruz', '2025-11-30 21:10:14', NULL, 1),
(210, 64, 'José Félix Ribas', '2025-11-30 21:10:14', NULL, 1),
(211, 64, 'Castor Nieves Ríos', '2025-11-30 21:10:14', NULL, 1),
(212, 64, 'Las Guacamayas', '2025-11-30 21:10:14', NULL, 1),
(213, 64, 'Pao de Zárate', '2025-11-30 21:10:14', NULL, 1),
(214, 64, 'Zuata', '2025-11-30 21:10:14', NULL, 1),
(215, 65, 'José Rafael Revenga', '2025-11-30 21:10:14', NULL, 1),
(216, 66, 'Palo Negro', '2025-11-30 21:10:14', NULL, 1),
(217, 66, 'San Martín de Porres', '2025-11-30 21:10:14', NULL, 1),
(218, 67, 'El Limón', '2025-11-30 21:10:14', NULL, 1),
(219, 67, 'Caña de Azúcar', '2025-11-30 21:10:14', NULL, 1),
(220, 68, 'Ocumare de la Costa', '2025-11-30 21:10:14', NULL, 1),
(221, 69, 'San Casimiro', '2025-11-30 21:10:14', NULL, 1),
(222, 69, 'Güiripa', '2025-11-30 21:10:14', NULL, 1),
(223, 69, 'Ollas de Caramacate', '2025-11-30 21:10:14', NULL, 1),
(224, 69, 'Valle Morín', '2025-11-30 21:10:14', NULL, 1),
(225, 70, 'San Sebastián', '2025-11-30 21:10:14', NULL, 1),
(226, 71, 'Turmero', '2025-11-30 21:10:14', NULL, 1),
(227, 71, 'Arevalo Aponte', '2025-11-30 21:10:14', NULL, 1),
(228, 71, 'Chuao', '2025-11-30 21:10:14', NULL, 1),
(229, 71, 'Samán de Güere', '2025-11-30 21:10:14', NULL, 1),
(230, 71, 'Alfredo Pacheco Miranda', '2025-11-30 21:10:14', NULL, 1),
(231, 72, 'Santos Michelena', '2025-11-30 21:10:14', NULL, 1),
(232, 72, 'Tiara', '2025-11-30 21:10:14', NULL, 1),
(233, 73, 'Cagua', '2025-11-30 21:10:14', NULL, 1),
(234, 73, 'Bella Vista', '2025-11-30 21:10:14', NULL, 1),
(235, 74, 'Tovar', '2025-11-30 21:10:14', NULL, 1),
(236, 75, 'Urdaneta', '2025-11-30 21:10:14', NULL, 1),
(237, 75, 'Las Peñitas', '2025-11-30 21:10:14', NULL, 1),
(238, 75, 'San Francisco de Cara', '2025-11-30 21:10:14', NULL, 1),
(239, 75, 'Taguay', '2025-11-30 21:10:14', NULL, 1),
(240, 76, 'Zamora', '2025-11-30 21:10:14', NULL, 1),
(241, 76, 'Magdaleno', '2025-11-30 21:10:14', NULL, 1),
(242, 76, 'San Francisco de Asís', '2025-11-30 21:10:14', NULL, 1),
(243, 76, 'Valles de Tucutunemo', '2025-11-30 21:10:14', NULL, 1),
(244, 76, 'Augusto Mijares', '2025-11-30 21:10:14', NULL, 1),
(245, 77, 'Sabaneta', '2025-11-30 21:10:14', NULL, 1),
(246, 77, 'Juan Antonio Rodríguez Domínguez', '2025-11-30 21:10:14', NULL, 1),
(247, 78, 'El Cantón', '2025-11-30 21:10:14', NULL, 1),
(248, 78, 'Santa Cruz de Guacas', '2025-11-30 21:10:14', NULL, 1),
(249, 78, 'Puerto Vivas', '2025-11-30 21:10:14', NULL, 1),
(250, 79, 'Ticoporo', '2025-11-30 21:10:14', NULL, 1),
(251, 79, 'Nicolás Pulido', '2025-11-30 21:10:14', NULL, 1),
(252, 79, 'Andrés Bello', '2025-11-30 21:10:14', NULL, 1),
(253, 80, 'Arismendi', '2025-11-30 21:10:14', NULL, 1),
(254, 80, 'Guadarrama', '2025-11-30 21:10:14', NULL, 1),
(255, 80, 'La Unión', '2025-11-30 21:10:14', NULL, 1),
(256, 80, 'San Antonio', '2025-11-30 21:10:14', NULL, 1),
(257, 81, 'Barinas', '2025-11-30 21:10:14', NULL, 1),
(258, 81, 'Alberto Arvelo Larriva', '2025-11-30 21:10:14', NULL, 1),
(259, 81, 'San Silvestre', '2025-11-30 21:10:14', NULL, 1),
(260, 81, 'Santa Inés', '2025-11-30 21:10:14', NULL, 1),
(261, 81, 'Santa Lucía', '2025-11-30 21:10:14', NULL, 1),
(262, 81, 'Torumos', '2025-11-30 21:10:14', NULL, 1),
(263, 81, 'El Carmen', '2025-11-30 21:10:14', NULL, 1),
(264, 81, 'Rómulo Betancourt', '2025-11-30 21:10:14', NULL, 1),
(265, 81, 'Corazón de Jesús', '2025-11-30 21:10:14', NULL, 1),
(266, 81, 'Ramón Ignacio Méndez', '2025-11-30 21:10:14', NULL, 1),
(267, 81, 'Alto Barinas', '2025-11-30 21:10:14', NULL, 1),
(268, 81, 'Manuel Palacio Fajardo', '2025-11-30 21:10:14', NULL, 1),
(269, 81, 'Juan Antonio Rodríguez Domínguez', '2025-11-30 21:10:14', NULL, 1),
(270, 81, 'Dominga Ortiz de Páez', '2025-11-30 21:10:14', NULL, 1),
(271, 82, 'Barinitas', '2025-11-30 21:10:14', NULL, 1),
(272, 82, 'Altamira de Cáceres', '2025-11-30 21:10:14', NULL, 1),
(273, 82, 'Calderas', '2025-11-30 21:10:14', NULL, 1),
(274, 83, 'Barrancas', '2025-11-30 21:10:14', NULL, 1),
(275, 83, 'El Socorro', '2025-11-30 21:10:14', NULL, 1),
(276, 83, 'Mazparrito', '2025-11-30 21:10:14', NULL, 1),
(277, 84, 'Santa Bárbara', '2025-11-30 21:10:14', NULL, 1),
(278, 84, 'Pedro Briceño Méndez', '2025-11-30 21:10:14', NULL, 1),
(279, 84, 'Ramón Ignacio Méndez', '2025-11-30 21:10:14', NULL, 1),
(280, 84, 'José Ignacio del Pumar', '2025-11-30 21:10:14', NULL, 1),
(281, 85, 'Obispos', '2025-11-30 21:10:14', NULL, 1),
(282, 85, 'Guasimitos', '2025-11-30 21:10:14', NULL, 1),
(283, 85, 'El Real', '2025-11-30 21:10:14', NULL, 1),
(284, 85, 'La Luz', '2025-11-30 21:10:14', NULL, 1),
(285, 86, 'Ciudad Bolívia', '2025-11-30 21:10:14', NULL, 1),
(286, 86, 'José Ignacio Briceño', '2025-11-30 21:10:14', NULL, 1),
(287, 86, 'José Félix Ribas', '2025-11-30 21:10:14', NULL, 1),
(288, 86, 'Páez', '2025-11-30 21:10:14', NULL, 1),
(289, 87, 'Libertad', '2025-11-30 21:10:14', NULL, 1),
(290, 87, 'Dolores', '2025-11-30 21:10:14', NULL, 1),
(291, 87, 'Santa Rosa', '2025-11-30 21:10:14', NULL, 1),
(292, 87, 'Palacio Fajardo', '2025-11-30 21:10:14', NULL, 1),
(293, 88, 'Ciudad de Nutrias', '2025-11-30 21:10:14', NULL, 1),
(294, 88, 'El Regalo', '2025-11-30 21:10:14', NULL, 1),
(295, 88, 'Puerto Nutrias', '2025-11-30 21:10:14', NULL, 1),
(296, 88, 'Santa Catalina', '2025-11-30 21:10:14', NULL, 1),
(297, 89, 'Cachamay', '2025-11-30 21:10:14', NULL, 1),
(298, 89, 'Chirica', '2025-11-30 21:10:14', NULL, 1),
(299, 89, 'Dalla Costa', '2025-11-30 21:10:14', NULL, 1),
(300, 89, 'Once de Abril', '2025-11-30 21:10:14', NULL, 1),
(301, 89, 'Simón Bolívar', '2025-11-30 21:10:14', NULL, 1),
(302, 89, 'Unare', '2025-11-30 21:10:14', NULL, 1),
(303, 89, 'Universidad', '2025-11-30 21:10:14', NULL, 1),
(304, 89, 'Vista al Sol', '2025-11-30 21:10:14', NULL, 1),
(305, 89, 'Pozo Verde', '2025-11-30 21:10:14', NULL, 1),
(306, 89, 'Yocoima', '2025-11-30 21:10:14', NULL, 1),
(307, 89, '5 de Julio', '2025-11-30 21:10:14', NULL, 1),
(308, 90, 'Cedeño', '2025-11-30 21:10:14', NULL, 1),
(309, 90, 'Altagracia', '2025-11-30 21:10:14', NULL, 1),
(310, 90, 'Ascensión Farreras', '2025-11-30 21:10:14', NULL, 1),
(311, 90, 'Guaniamo', '2025-11-30 21:10:14', NULL, 1),
(312, 90, 'La Urbana', '2025-11-30 21:10:14', NULL, 1),
(313, 90, 'Pijiguaos', '2025-11-30 21:10:14', NULL, 1),
(314, 91, 'El Callao', '2025-11-30 21:10:14', NULL, 1),
(315, 92, 'Gran Sabana', '2025-11-30 21:10:14', NULL, 1),
(316, 92, 'Ikabarú', '2025-11-30 21:10:14', NULL, 1),
(317, 93, 'Catedral', '2025-11-30 21:10:14', NULL, 1),
(318, 93, 'Zea', '2025-11-30 21:10:14', NULL, 1),
(319, 93, 'Orinoco', '2025-11-30 21:10:14', NULL, 1),
(320, 93, 'José Antonio Páez', '2025-11-30 21:10:14', NULL, 1),
(321, 93, 'Marhuanta', '2025-11-30 21:10:14', NULL, 1),
(322, 93, 'Agua Salada', '2025-11-30 21:10:14', NULL, 1),
(323, 93, 'Vista Hermosa', '2025-11-30 21:10:14', NULL, 1),
(324, 93, 'La Sabanita', '2025-11-30 21:10:14', NULL, 1),
(325, 93, 'Panapana', '2025-11-30 21:10:14', NULL, 1),
(326, 94, 'Andrés Eloy Blanco', '2025-11-30 21:10:14', NULL, 1),
(327, 94, 'Pedro Cova', '2025-11-30 21:10:14', NULL, 1),
(328, 95, 'Raúl Leoni', '2025-11-30 21:10:14', NULL, 1),
(329, 95, 'Barceloneta', '2025-11-30 21:10:14', NULL, 1),
(330, 95, 'Santa Bárbara', '2025-11-30 21:10:14', NULL, 1),
(331, 95, 'San Francisco', '2025-11-30 21:10:14', NULL, 1),
(332, 96, 'Roscio', '2025-11-30 21:10:14', NULL, 1),
(333, 96, 'Salóm', '2025-11-30 21:10:14', NULL, 1),
(334, 97, 'Sifontes', '2025-11-30 21:10:14', NULL, 1),
(335, 97, 'Dalla Costa', '2025-11-30 21:10:14', NULL, 1),
(336, 97, 'San Isidro', '2025-11-30 21:10:14', NULL, 1),
(337, 98, 'Sucre', '2025-11-30 21:10:14', NULL, 1),
(338, 98, 'Aripao', '2025-11-30 21:10:14', NULL, 1),
(339, 98, 'Guarataro', '2025-11-30 21:10:14', NULL, 1),
(340, 98, 'Las Majadas', '2025-11-30 21:10:14', NULL, 1),
(341, 98, 'Moitaco', '2025-11-30 21:10:14', NULL, 1),
(342, 99, 'Padre Pedro Chien', '2025-11-30 21:10:14', NULL, 1),
(343, 99, 'Río Grande', '2025-11-30 21:10:14', NULL, 1),
(344, 100, 'Bejuma', '2025-11-30 21:11:17', NULL, 1),
(345, 100, 'Canoabo', '2025-11-30 21:11:17', NULL, 1),
(346, 100, 'Simón Bolívar', '2025-11-30 21:11:17', NULL, 1),
(347, 101, 'Güigüe', '2025-11-30 21:11:17', NULL, 1),
(348, 101, 'Carabobo', '2025-11-30 21:11:17', NULL, 1),
(349, 101, 'Tacarigua', '2025-11-30 21:11:17', NULL, 1),
(350, 102, 'Mariara', '2025-11-30 21:11:17', NULL, 1),
(351, 102, 'Aguas Calientes', '2025-11-30 21:11:17', NULL, 1),
(352, 103, 'Ciudad Alianza', '2025-11-30 21:11:17', NULL, 1),
(353, 103, 'Guacara', '2025-11-30 21:11:17', NULL, 1),
(354, 103, 'Yagua', '2025-11-30 21:11:17', NULL, 1),
(355, 104, 'Morón', '2025-11-30 21:11:17', NULL, 1),
(356, 104, 'Yagua', '2025-11-30 21:11:17', NULL, 1),
(357, 105, 'Tocuyito', '2025-11-30 21:11:17', NULL, 1),
(358, 105, 'Independencia', '2025-11-30 21:11:17', NULL, 1),
(359, 106, 'Los Guayos', '2025-11-30 21:11:17', NULL, 1),
(360, 107, 'Miranda', '2025-11-30 21:11:17', NULL, 1),
(361, 108, 'Montalbán', '2025-11-30 21:11:17', NULL, 1),
(362, 109, 'Naguanagua', '2025-11-30 21:11:17', NULL, 1),
(363, 110, 'Bartolomé Salóm', '2025-11-30 21:11:17', NULL, 1),
(364, 110, 'Democracia', '2025-11-30 21:11:17', NULL, 1),
(365, 110, 'Fraternidad', '2025-11-30 21:11:17', NULL, 1),
(366, 110, 'Goaigoaza', '2025-11-30 21:11:17', NULL, 1),
(367, 110, 'Juan José Flores', '2025-11-30 21:11:17', NULL, 1),
(368, 110, 'Unión', '2025-11-30 21:11:17', NULL, 1),
(369, 110, 'Borburata', '2025-11-30 21:11:17', NULL, 1),
(370, 110, 'Patanemo', '2025-11-30 21:11:17', NULL, 1),
(371, 111, 'San Diego', '2025-11-30 21:11:17', NULL, 1),
(372, 112, 'San Joaquín', '2025-11-30 21:11:17', NULL, 1),
(373, 113, 'Candelaria', '2025-11-30 21:11:17', NULL, 1),
(374, 113, 'Catedral', '2025-11-30 21:11:17', NULL, 1),
(375, 113, 'El Socorro', '2025-11-30 21:11:17', NULL, 1),
(376, 113, 'Miguel Peña', '2025-11-30 21:11:17', NULL, 1),
(377, 113, 'Rafael Urdaneta', '2025-11-30 21:11:17', NULL, 1),
(378, 113, 'San Blas', '2025-11-30 21:11:17', NULL, 1),
(379, 113, 'San José', '2025-11-30 21:11:17', NULL, 1),
(380, 113, 'Santa Rosa', '2025-11-30 21:11:17', NULL, 1),
(381, 113, 'Negro Primero', '2025-11-30 21:11:17', NULL, 1),
(382, 114, 'Cojedes', '2025-11-30 21:11:17', NULL, 1),
(383, 114, 'Juan de Mata Suárez', '2025-11-30 21:11:17', NULL, 1),
(384, 115, 'Tinaquillo', '2025-11-30 21:11:17', NULL, 1),
(385, 116, 'El Baúl', '2025-11-30 21:11:17', NULL, 1),
(386, 116, 'Sucre', '2025-11-30 21:11:17', NULL, 1),
(387, 117, 'La Aguadita', '2025-11-30 21:11:17', NULL, 1),
(388, 117, 'Macapo', '2025-11-30 21:11:17', NULL, 1),
(389, 118, 'El Pao', '2025-11-30 21:11:17', NULL, 1),
(390, 119, 'El Amparo', '2025-11-30 21:11:17', NULL, 1),
(391, 119, 'Libertad de Cojedes', '2025-11-30 21:11:17', NULL, 1),
(392, 120, 'Rómulo Gallegos', '2025-11-30 21:11:17', NULL, 1),
(393, 121, 'San Carlos de Austria', '2025-11-30 21:11:17', NULL, 1),
(394, 121, 'Juan Ángel Bravo', '2025-11-30 21:11:17', NULL, 1),
(395, 121, 'Manuel Manrique', '2025-11-30 21:11:17', NULL, 1),
(396, 122, 'General en Jefe José Laurencio Silva', '2025-11-30 21:11:17', NULL, 1),
(397, 123, 'Curiapo', '2025-11-30 21:11:17', NULL, 1),
(398, 123, 'Almirante Luis Brión', '2025-11-30 21:11:17', NULL, 1),
(399, 123, 'Francisco Aniceto Lugo', '2025-11-30 21:11:17', NULL, 1),
(400, 123, 'Manuel Renaud', '2025-11-30 21:11:17', NULL, 1),
(401, 123, 'Padre Barral', '2025-11-30 21:11:17', NULL, 1),
(402, 123, 'Santos de Abelgas', '2025-11-30 21:11:17', NULL, 1),
(403, 124, 'Imataca', '2025-11-30 21:11:17', NULL, 1),
(404, 124, 'Cinco de Julio', '2025-11-30 21:11:17', NULL, 1),
(405, 124, 'Juan Bautista Arismendi', '2025-11-30 21:11:17', NULL, 1),
(406, 124, 'Manuel Piar', '2025-11-30 21:11:17', NULL, 1),
(407, 124, 'Rómulo Gallegos', '2025-11-30 21:11:17', NULL, 1),
(408, 125, 'Pedernales', '2025-11-30 21:11:17', NULL, 1),
(409, 125, 'Luis Beltrán Prieto Figueroa', '2025-11-30 21:11:17', NULL, 1),
(410, 126, 'San José (Delta Amacuro)', '2025-11-30 21:11:17', NULL, 1),
(411, 126, 'José Vidal Marcano', '2025-11-30 21:11:17', NULL, 1),
(412, 126, 'Juan Millán', '2025-11-30 21:11:17', NULL, 1),
(413, 126, 'Leonardo Ruíz Pineda', '2025-11-30 21:11:17', NULL, 1),
(414, 126, 'Mariscal Antonio José de Sucre', '2025-11-30 21:11:17', NULL, 1),
(415, 126, 'Monseñor Argimiro García', '2025-11-30 21:11:17', NULL, 1),
(416, 126, 'San Rafael (Delta Amacuro)', '2025-11-30 21:11:17', NULL, 1),
(417, 126, 'Virgen del Valle', '2025-11-30 21:11:17', NULL, 1),
(418, 127, 'Capadare', '2025-11-30 21:11:17', NULL, 1),
(419, 127, 'La Pastora', '2025-11-30 21:11:17', NULL, 1),
(420, 127, 'Libertador', '2025-11-30 21:11:17', NULL, 1),
(421, 127, 'San Juan de los Cayos', '2025-11-30 21:11:17', NULL, 1),
(422, 128, 'Aracua', '2025-11-30 21:11:17', NULL, 1),
(423, 128, 'La Peña', '2025-11-30 21:11:17', NULL, 1),
(424, 128, 'San Luis', '2025-11-30 21:11:17', NULL, 1),
(425, 129, 'Bariro', '2025-11-30 21:11:17', NULL, 1),
(426, 129, 'Borojó', '2025-11-30 21:11:17', NULL, 1),
(427, 129, 'Capatárida', '2025-11-30 21:11:17', NULL, 1),
(428, 129, 'Guajiro', '2025-11-30 21:11:17', NULL, 1),
(429, 129, 'Seque', '2025-11-30 21:11:17', NULL, 1),
(430, 129, 'Zazárida', '2025-11-30 21:11:17', NULL, 1),
(431, 129, 'Valle de Eroa', '2025-11-30 21:11:17', NULL, 1),
(432, 130, 'Cacique Manaure', '2025-11-30 21:11:17', NULL, 1),
(433, 131, 'Norte', '2025-11-30 21:11:17', NULL, 1),
(434, 131, 'Carirubana', '2025-11-30 21:11:17', NULL, 1),
(435, 131, 'Santa Ana', '2025-11-30 21:11:17', NULL, 1),
(436, 131, 'Urbana Punta Cardón', '2025-11-30 21:11:17', NULL, 1),
(437, 132, 'La Vela de Coro', '2025-11-30 21:11:17', NULL, 1),
(438, 132, 'Acurigua', '2025-11-30 21:11:17', NULL, 1),
(439, 132, 'Guaibacoa', '2025-11-30 21:11:17', NULL, 1),
(440, 132, 'Las Calderas', '2025-11-30 21:11:17', NULL, 1),
(441, 132, 'Macoruca', '2025-11-30 21:11:17', NULL, 1),
(442, 133, 'Dabajuro', '2025-11-30 21:11:17', NULL, 1),
(443, 134, 'Agua Clara', '2025-11-30 21:11:17', NULL, 1),
(444, 134, 'Avaria', '2025-11-30 21:11:17', NULL, 1),
(445, 134, 'Pedregal', '2025-11-30 21:11:17', NULL, 1),
(446, 134, 'Piedra Grande', '2025-11-30 21:11:17', NULL, 1),
(447, 134, 'Purureche', '2025-11-30 21:11:17', NULL, 1),
(448, 135, 'Adaure', '2025-11-30 21:11:17', NULL, 1),
(449, 135, 'Adícora', '2025-11-30 21:11:17', NULL, 1),
(450, 135, 'Baraived', '2025-11-30 21:11:17', NULL, 1),
(451, 135, 'Buena Vista', '2025-11-30 21:11:17', NULL, 1),
(452, 135, 'Jadacaquiva', '2025-11-30 21:11:17', NULL, 1),
(453, 135, 'El Vínculo', '2025-11-30 21:11:17', NULL, 1),
(454, 135, 'El Hato', '2025-11-30 21:11:17', NULL, 1),
(455, 135, 'Moruy', '2025-11-30 21:11:17', NULL, 1),
(456, 135, 'Pueblo Nuevo', '2025-11-30 21:11:17', NULL, 1),
(457, 136, 'Agua Larga', '2025-11-30 21:11:17', NULL, 1),
(458, 136, 'El Paují', '2025-11-30 21:11:17', NULL, 1),
(459, 136, 'Independencia', '2025-11-30 21:11:17', NULL, 1),
(460, 136, 'Mapararí', '2025-11-30 21:11:17', NULL, 1),
(461, 137, 'Agua Linda', '2025-11-30 21:11:17', NULL, 1),
(462, 137, 'Araurima', '2025-11-30 21:11:17', NULL, 1),
(463, 137, 'Jacura', '2025-11-30 21:11:17', NULL, 1),
(464, 138, 'Tucacas', '2025-11-30 21:11:17', NULL, 1),
(465, 138, 'Boca de Aroa', '2025-11-30 21:11:17', NULL, 1),
(466, 139, 'Los Taques', '2025-11-30 21:11:17', NULL, 1),
(467, 139, 'Judibana', '2025-11-30 21:11:17', NULL, 1),
(468, 140, 'Mene de Mauroa', '2025-11-30 21:11:17', NULL, 1),
(469, 140, 'San Félix', '2025-11-30 21:11:17', NULL, 1),
(470, 140, 'Casigua', '2025-11-30 21:11:17', NULL, 1),
(471, 141, 'Guzmán Guillermo', '2025-11-30 21:11:17', NULL, 1),
(472, 141, 'Mitare', '2025-11-30 21:11:17', NULL, 1),
(473, 141, 'Río Seco', '2025-11-30 21:11:17', NULL, 1),
(474, 141, 'Sabaneta', '2025-11-30 21:11:17', NULL, 1),
(475, 141, 'San Antonio', '2025-11-30 21:11:17', NULL, 1),
(476, 141, 'San Gabriel', '2025-11-30 21:11:17', NULL, 1),
(477, 141, 'Santa Ana', '2025-11-30 21:11:17', NULL, 1),
(478, 142, 'Boca del Tocuyo', '2025-11-30 21:11:17', NULL, 1),
(479, 142, 'Chichiriviche', '2025-11-30 21:11:17', NULL, 1),
(480, 142, 'Tocuyo de la Costa', '2025-11-30 21:11:17', NULL, 1),
(481, 143, 'Palmasola', '2025-11-30 21:11:17', NULL, 1),
(482, 144, 'Cabure', '2025-11-30 21:11:17', NULL, 1),
(483, 144, 'Colina', '2025-11-30 21:11:17', NULL, 1),
(484, 144, 'Curimagua', '2025-11-30 21:11:17', NULL, 1),
(485, 145, 'San José de la Costa', '2025-11-30 21:11:17', NULL, 1),
(486, 145, 'Píritu', '2025-11-30 21:11:17', NULL, 1),
(487, 146, 'San Francisco', '2025-11-30 21:11:17', NULL, 1),
(488, 147, 'Sucre', '2025-11-30 21:11:17', NULL, 1),
(489, 147, 'Pecaya', '2025-11-30 21:11:17', NULL, 1),
(490, 148, 'Tocópero', '2025-11-30 21:11:17', NULL, 1),
(491, 149, 'El Charal', '2025-11-30 21:11:17', NULL, 1),
(492, 149, 'Las Vegas del Tuy', '2025-11-30 21:11:17', NULL, 1),
(493, 149, 'Santa Cruz de Bucaral', '2025-11-30 21:11:17', NULL, 1),
(494, 150, 'Bruzual', '2025-11-30 21:11:17', NULL, 1),
(495, 150, 'Urumaco', '2025-11-30 21:11:17', NULL, 1),
(496, 151, 'Puerto Cumarebo', '2025-11-30 21:11:17', NULL, 1),
(497, 151, 'La Ciénaga', '2025-11-30 21:11:17', NULL, 1),
(498, 151, 'La Soledad', '2025-11-30 21:11:17', NULL, 1),
(499, 151, 'Pueblo Cumarebo', '2025-11-30 21:11:17', NULL, 1),
(500, 151, 'Zazárida', '2025-11-30 21:11:17', NULL, 1),
(501, 152, 'Camaguán', '2025-11-30 21:11:40', NULL, 1),
(502, 152, 'Puerto Miranda', '2025-11-30 21:11:40', NULL, 1),
(503, 152, 'Uverito', '2025-11-30 21:11:40', NULL, 1),
(504, 153, 'Chaguaramas', '2025-11-30 21:11:40', NULL, 1),
(505, 154, 'El Socorro', '2025-11-30 21:11:40', NULL, 1),
(506, 155, 'Tucupido', '2025-11-30 21:11:40', NULL, 1),
(507, 155, 'San Rafael de Laya', '2025-11-30 21:11:40', NULL, 1),
(508, 156, 'Altagracia de Orituco', '2025-11-30 21:11:40', NULL, 1),
(509, 156, 'San Rafael de Orituco', '2025-11-30 21:11:40', NULL, 1),
(510, 156, 'San Francisco Javier de Lezama', '2025-11-30 21:11:40', NULL, 1),
(511, 156, 'Paso Real de Macaira', '2025-11-30 21:11:40', NULL, 1),
(512, 156, 'Carlos Soublette', '2025-11-30 21:11:40', NULL, 1),
(513, 156, 'San Francisco de Macaira', '2025-11-30 21:11:40', NULL, 1),
(514, 156, 'Libertad de Orituco', '2025-11-30 21:11:40', NULL, 1),
(515, 157, 'Cantaclaro', '2025-11-30 21:11:40', NULL, 1),
(516, 157, 'San Juan de los Morros', '2025-11-30 21:11:40', NULL, 1),
(517, 157, 'Parapara', '2025-11-30 21:11:40', NULL, 1),
(518, 158, 'El Sombrero', '2025-11-30 21:11:40', NULL, 1),
(519, 158, 'Sosa', '2025-11-30 21:11:40', NULL, 1),
(520, 159, 'Las Mercedes', '2025-11-30 21:11:40', NULL, 1),
(521, 159, 'Cabruta', '2025-11-30 21:11:40', NULL, 1),
(522, 159, 'Santa Rita de Manapire', '2025-11-30 21:11:40', NULL, 1),
(523, 160, 'Valle de la Pascua', '2025-11-30 21:11:40', NULL, 1),
(524, 160, 'Espino', '2025-11-30 21:11:40', NULL, 1),
(525, 161, 'San José de Unare', '2025-11-30 21:11:40', NULL, 1),
(526, 161, 'Zaraza', '2025-11-30 21:11:40', NULL, 1),
(527, 162, 'San José de Tiznados', '2025-11-30 21:11:40', NULL, 1),
(528, 162, 'San Francisco de Tiznados', '2025-11-30 21:11:40', NULL, 1),
(529, 162, 'San Lorenzo de Tiznados', '2025-11-30 21:11:40', NULL, 1),
(530, 162, 'Ortíz', '2025-11-30 21:11:40', NULL, 1),
(531, 163, 'Guayabal', '2025-11-30 21:11:40', NULL, 1),
(532, 163, 'Cazorla', '2025-11-30 21:11:40', NULL, 1),
(533, 164, 'San José de Guaribe', '2025-11-30 21:11:40', NULL, 1),
(534, 164, 'Uveral', '2025-11-30 21:11:40', NULL, 1),
(535, 165, 'Santa María de Ipire', '2025-11-30 21:11:40', NULL, 1),
(536, 165, 'Altamira', '2025-11-30 21:11:40', NULL, 1),
(537, 166, 'El Calvario', '2025-11-30 21:11:40', NULL, 1),
(538, 166, 'El Rastro', '2025-11-30 21:11:40', NULL, 1),
(539, 166, 'Guardatinajas', '2025-11-30 21:11:40', NULL, 1),
(540, 166, 'Capital Urbana Calabozo', '2025-11-30 21:11:40', NULL, 1),
(541, 167, 'Quebrada Honda de Guache', '2025-11-30 21:11:40', NULL, 1),
(542, 167, 'Pío Tamayo', '2025-11-30 21:11:40', NULL, 1),
(543, 167, 'Yacambú', '2025-11-30 21:11:40', NULL, 1),
(544, 168, 'Fréitez', '2025-11-30 21:11:40', NULL, 1),
(545, 168, 'José María Blanco', '2025-11-30 21:11:40', NULL, 1),
(546, 169, 'Catedral', '2025-11-30 21:11:40', NULL, 1),
(547, 169, 'Concepción', '2025-11-30 21:11:40', NULL, 1),
(548, 169, 'El Cují', '2025-11-30 21:11:40', NULL, 1),
(549, 169, 'Juan de Villegas', '2025-11-30 21:11:40', NULL, 1),
(550, 169, 'Santa Rosa', '2025-11-30 21:11:40', NULL, 1),
(551, 169, 'Tamaca', '2025-11-30 21:11:40', NULL, 1),
(552, 169, 'Unión', '2025-11-30 21:11:40', NULL, 1),
(553, 169, 'Aguedo Felipe Alvarado', '2025-11-30 21:11:40', NULL, 1),
(554, 169, 'Buena Vista', '2025-11-30 21:11:40', NULL, 1),
(555, 169, 'Juárez', '2025-11-30 21:11:40', NULL, 1),
(556, 170, 'Juan Bautista Rodríguez', '2025-11-30 21:11:40', NULL, 1),
(557, 170, 'Cuara', '2025-11-30 21:11:40', NULL, 1),
(558, 170, 'Diego de Lozada', '2025-11-30 21:11:40', NULL, 1),
(559, 170, 'Paraíso de San José', '2025-11-30 21:11:40', NULL, 1),
(560, 170, 'San Miguel', '2025-11-30 21:11:40', NULL, 1),
(561, 170, 'Tintorero', '2025-11-30 21:11:40', NULL, 1),
(562, 170, 'José Bernardo Dorante', '2025-11-30 21:11:40', NULL, 1),
(563, 170, 'Coronel Mariano Peraza', '2025-11-30 21:11:40', NULL, 1),
(564, 171, 'Bolívar', '2025-11-30 21:11:40', NULL, 1),
(565, 171, 'Anzoátegui', '2025-11-30 21:11:40', NULL, 1),
(566, 171, 'Guarico', '2025-11-30 21:11:40', NULL, 1),
(567, 171, 'Hilario Luna y Luna', '2025-11-30 21:11:40', NULL, 1),
(568, 171, 'Humocaro Alto', '2025-11-30 21:11:40', NULL, 1),
(569, 171, 'Humocaro Bajo', '2025-11-30 21:11:40', NULL, 1),
(570, 171, 'La Candelaria', '2025-11-30 21:11:40', NULL, 1),
(571, 171, 'Morán', '2025-11-30 21:11:40', NULL, 1),
(572, 172, 'Cabudare', '2025-11-30 21:11:40', NULL, 1),
(573, 172, 'José Gregorio Bastidas', '2025-11-30 21:11:40', NULL, 1),
(574, 172, 'Agua Viva', '2025-11-30 21:11:40', NULL, 1),
(575, 173, 'Sarare', '2025-11-30 21:11:40', NULL, 1),
(576, 173, 'Buría', '2025-11-30 21:11:40', NULL, 1),
(577, 173, 'Gustavo Vegas León', '2025-11-30 21:11:40', NULL, 1),
(578, 174, 'Trinidad Samuel', '2025-11-30 21:11:40', NULL, 1),
(579, 174, 'Antonio Díaz', '2025-11-30 21:11:40', NULL, 1),
(580, 174, 'Camacaro', '2025-11-30 21:11:40', NULL, 1),
(581, 174, 'Castañeda', '2025-11-30 21:11:40', NULL, 1),
(582, 174, 'Cecilio Zubillaga', '2025-11-30 21:11:40', NULL, 1),
(583, 174, 'Chiquinquirá', '2025-11-30 21:11:40', NULL, 1),
(584, 174, 'El Blanco', '2025-11-30 21:11:40', NULL, 1),
(585, 174, 'Espinoza de los Monteros', '2025-11-30 21:11:40', NULL, 1),
(586, 174, 'Lara', '2025-11-30 21:11:40', NULL, 1),
(587, 174, 'Las Mercedes', '2025-11-30 21:11:40', NULL, 1),
(588, 174, 'Manuel Morillo', '2025-11-30 21:11:40', NULL, 1),
(589, 174, 'Montaña Verde', '2025-11-30 21:11:40', NULL, 1),
(590, 174, 'Montes de Oca', '2025-11-30 21:11:40', NULL, 1),
(591, 174, 'Torres', '2025-11-30 21:11:40', NULL, 1),
(592, 174, 'Heriberto Arroyo', '2025-11-30 21:11:40', NULL, 1),
(593, 174, 'Reyes Vargas', '2025-11-30 21:11:40', NULL, 1),
(594, 174, 'Altagracia', '2025-11-30 21:11:40', NULL, 1),
(595, 175, 'Siquisique', '2025-11-30 21:11:40', NULL, 1),
(596, 175, 'Moroturo', '2025-11-30 21:11:40', NULL, 1),
(597, 175, 'San Miguel', '2025-11-30 21:11:40', NULL, 1),
(598, 175, 'Xaguas', '2025-11-30 21:11:40', NULL, 1),
(599, 176, 'Presidente Betancourt', '2025-11-30 21:12:07', NULL, 1),
(600, 176, 'Presidente Páez', '2025-11-30 21:12:07', NULL, 1),
(601, 176, 'Presidente Rómulo Gallegos', '2025-11-30 21:12:07', NULL, 1),
(602, 176, 'Gabriel Picón González', '2025-11-30 21:12:07', NULL, 1),
(603, 176, 'Héctor Amable Mora', '2025-11-30 21:12:07', NULL, 1),
(604, 176, 'José Nucete Sardi', '2025-11-30 21:12:07', NULL, 1),
(605, 176, 'Pulido Méndez', '2025-11-30 21:12:07', NULL, 1),
(606, 177, 'La Azulita', '2025-11-30 21:12:07', NULL, 1),
(607, 178, 'Santa Cruz de Mora', '2025-11-30 21:12:07', NULL, 1),
(608, 178, 'Mesa Bolívar', '2025-11-30 21:12:07', NULL, 1),
(609, 178, 'Mesa de Las Palmas', '2025-11-30 21:12:07', NULL, 1),
(610, 179, 'Aricagua', '2025-11-30 21:12:07', NULL, 1),
(611, 179, 'San Antonio', '2025-11-30 21:12:07', NULL, 1),
(612, 180, 'Canagua', '2025-11-30 21:12:07', NULL, 1),
(613, 180, 'Capurí', '2025-11-30 21:12:07', NULL, 1),
(614, 180, 'Chacantá', '2025-11-30 21:12:07', NULL, 1),
(615, 180, 'El Molino', '2025-11-30 21:12:07', NULL, 1),
(616, 180, 'Guaimaral', '2025-11-30 21:12:07', NULL, 1),
(617, 180, 'Mucutuy', '2025-11-30 21:12:07', NULL, 1),
(618, 180, 'Mucuchachí', '2025-11-30 21:12:07', NULL, 1),
(619, 181, 'Fernández Peña', '2025-11-30 21:12:07', NULL, 1),
(620, 181, 'Matriz', '2025-11-30 21:12:07', NULL, 1),
(621, 181, 'Montalbán', '2025-11-30 21:12:07', NULL, 1),
(622, 181, 'Acequias', '2025-11-30 21:12:07', NULL, 1),
(623, 181, 'Jají', '2025-11-30 21:12:07', NULL, 1),
(624, 181, 'La Mesa', '2025-11-30 21:12:07', NULL, 1),
(625, 181, 'San José del Sur', '2025-11-30 21:12:07', NULL, 1),
(626, 182, 'Tucaní', '2025-11-30 21:12:07', NULL, 1),
(627, 182, 'Florencio Ramírez', '2025-11-30 21:12:07', NULL, 1),
(628, 183, 'Santo Domingo', '2025-11-30 21:12:07', NULL, 1),
(629, 183, 'Las Piedras', '2025-11-30 21:12:07', NULL, 1),
(630, 184, 'Guaraque', '2025-11-30 21:12:07', NULL, 1),
(631, 184, 'Mesa de Quintero', '2025-11-30 21:12:07', NULL, 1),
(632, 184, 'Río Negro', '2025-11-30 21:12:07', NULL, 1),
(633, 185, 'Arapuey', '2025-11-30 21:12:07', NULL, 1),
(634, 185, 'Palmira', '2025-11-30 21:12:07', NULL, 1),
(635, 186, 'San Cristóbal de Torondoy', '2025-11-30 21:12:07', NULL, 1),
(636, 186, 'Torondoy', '2025-11-30 21:12:07', NULL, 1),
(637, 187, 'Antonio Spinetti Dini', '2025-11-30 21:12:07', NULL, 1),
(638, 187, 'Arias', '2025-11-30 21:12:07', NULL, 1),
(639, 187, 'Caracciolo Parra Pérez', '2025-11-30 21:12:07', NULL, 1),
(640, 187, 'Domingo Peña', '2025-11-30 21:12:07', NULL, 1),
(641, 187, 'El Llano', '2025-11-30 21:12:07', NULL, 1),
(642, 187, 'Gonzalo Picón Febres', '2025-11-30 21:12:07', NULL, 1),
(643, 187, 'Jacinto Plaza', '2025-11-30 21:12:07', NULL, 1),
(644, 187, 'Juan Rodríguez Suárez', '2025-11-30 21:12:07', NULL, 1),
(645, 187, 'Lasso de la Vega', '2025-11-30 21:12:07', NULL, 1),
(646, 187, 'Mariano Picón Salas', '2025-11-30 21:12:07', NULL, 1),
(647, 187, 'Milla', '2025-11-30 21:12:07', NULL, 1),
(648, 187, 'Osuna Rodríguez', '2025-11-30 21:12:07', NULL, 1),
(649, 187, 'Sagrario', '2025-11-30 21:12:07', NULL, 1),
(650, 187, 'El Morro', '2025-11-30 21:12:07', NULL, 1),
(651, 187, 'Los Nevados', '2025-11-30 21:12:07', NULL, 1),
(652, 188, 'Andrés Eloy Blanco', '2025-11-30 21:12:07', NULL, 1),
(653, 188, 'La Venta', '2025-11-30 21:12:07', NULL, 1),
(654, 188, 'Piñango', '2025-11-30 21:12:07', NULL, 1),
(655, 188, 'Timotes', '2025-11-30 21:12:07', NULL, 1),
(656, 189, 'Eloy Paredes', '2025-11-30 21:12:07', NULL, 1),
(657, 189, 'San Rafael de Alcázar', '2025-11-30 21:12:07', NULL, 1),
(658, 189, 'Santa Elena de Arenales', '2025-11-30 21:12:07', NULL, 1),
(659, 190, 'Santa María de Caparo', '2025-11-30 21:12:07', NULL, 1),
(660, 191, 'Pueblo Llano', '2025-11-30 21:12:07', NULL, 1),
(661, 192, 'Cacute', '2025-11-30 21:12:07', NULL, 1),
(662, 192, 'La Toma', '2025-11-30 21:12:07', NULL, 1),
(663, 192, 'Mucuchíes', '2025-11-30 21:12:07', NULL, 1),
(664, 192, 'Mucurubá', '2025-11-30 21:12:07', NULL, 1),
(665, 192, 'San Rafael', '2025-11-30 21:12:07', NULL, 1),
(666, 193, 'Gerónimo Maldonado', '2025-11-30 21:12:07', NULL, 1),
(667, 193, 'Bailadores', '2025-11-30 21:12:07', NULL, 1),
(668, 194, 'Tabay', '2025-11-30 21:12:07', NULL, 1),
(669, 195, 'Chiguará', '2025-11-30 21:12:07', NULL, 1),
(670, 195, 'Estánquez', '2025-11-30 21:12:07', NULL, 1),
(671, 195, 'Lagunillas', '2025-11-30 21:12:07', NULL, 1),
(672, 195, 'La Trampa', '2025-11-30 21:12:07', NULL, 1),
(673, 195, 'Pueblo Nuevo del Sur', '2025-11-30 21:12:07', NULL, 1),
(674, 195, 'San Juan', '2025-11-30 21:12:07', NULL, 1),
(675, 196, 'El Amparo', '2025-11-30 21:12:07', NULL, 1),
(676, 196, 'El Llano', '2025-11-30 21:12:07', NULL, 1),
(677, 196, 'San Francisco', '2025-11-30 21:12:07', NULL, 1),
(678, 196, 'Tovar', '2025-11-30 21:12:07', NULL, 1),
(679, 197, 'Independencia', '2025-11-30 21:12:07', NULL, 1),
(680, 197, 'María de la Concepción Palacios Blanco', '2025-11-30 21:12:07', NULL, 1),
(681, 197, 'Nueva Bolivia', '2025-11-30 21:12:07', NULL, 1),
(682, 197, 'Santa Apolonia', '2025-11-30 21:12:07', NULL, 1),
(683, 198, 'Caño El Tigre', '2025-11-30 21:12:07', NULL, 1),
(684, 198, 'Zea', '2025-11-30 21:12:07', NULL, 1),
(685, 199, 'San Antonio de Maturín', '2025-11-30 21:12:39', NULL, 1),
(686, 199, 'San Francisco de Maturín', '2025-11-30 21:12:39', NULL, 1),
(687, 200, 'Aguasay', '2025-11-30 21:12:39', NULL, 1),
(688, 201, 'Caripito', '2025-11-30 21:12:39', NULL, 1),
(689, 202, 'El Guácharo', '2025-11-30 21:12:39', NULL, 1),
(690, 202, 'La Guanota', '2025-11-30 21:12:39', NULL, 1),
(691, 202, 'Sabana de Piedra', '2025-11-30 21:12:39', NULL, 1),
(692, 202, 'San Agustín', '2025-11-30 21:12:39', NULL, 1),
(693, 202, 'Teresen', '2025-11-30 21:12:39', NULL, 1),
(694, 202, 'Caripe', '2025-11-30 21:12:39', NULL, 1),
(695, 203, 'Areo', '2025-11-30 21:12:39', NULL, 1),
(696, 203, 'Capital Cedeño', '2025-11-30 21:12:39', NULL, 1),
(697, 203, 'San Félix de Cantalicio', '2025-11-30 21:12:39', NULL, 1),
(698, 203, 'Viento Fresco', '2025-11-30 21:12:39', NULL, 1),
(699, 204, 'El Tejero', '2025-11-30 21:12:39', NULL, 1),
(700, 204, 'Punta de Mata', '2025-11-30 21:12:39', NULL, 1),
(701, 205, 'Chaguaramas', '2025-11-30 21:12:39', NULL, 1),
(702, 205, 'Las Alhuacas', '2025-11-30 21:12:39', NULL, 1),
(703, 205, 'Tabasca', '2025-11-30 21:12:39', NULL, 1),
(704, 205, 'Temblador', '2025-11-30 21:12:39', NULL, 1),
(705, 206, 'Alto de los Godos', '2025-11-30 21:12:39', NULL, 1),
(706, 206, 'Boquerón', '2025-11-30 21:12:39', NULL, 1),
(707, 206, 'Las Cocuizas', '2025-11-30 21:12:39', NULL, 1),
(708, 206, 'La Cruz', '2025-11-30 21:12:39', NULL, 1),
(709, 206, 'San Simón', '2025-11-30 21:12:39', NULL, 1),
(710, 206, 'El Corozo', '2025-11-30 21:12:39', NULL, 1),
(711, 206, 'El Furrial', '2025-11-30 21:12:39', NULL, 1),
(712, 206, 'Jusepín', '2025-11-30 21:12:39', NULL, 1),
(713, 206, 'La Pica', '2025-11-30 21:12:39', NULL, 1),
(714, 206, 'San Vicente', '2025-11-30 21:12:39', NULL, 1),
(715, 207, 'Aparicio', '2025-11-30 21:12:39', NULL, 1),
(716, 207, 'Aragua de Maturín', '2025-11-30 21:12:39', NULL, 1),
(717, 207, 'Chaguamal', '2025-11-30 21:12:39', NULL, 1),
(718, 207, 'El Pinto', '2025-11-30 21:12:39', NULL, 1),
(719, 207, 'Guanaguana', '2025-11-30 21:12:39', NULL, 1),
(720, 207, 'La Toscana', '2025-11-30 21:12:39', NULL, 1),
(721, 207, 'Taguaya', '2025-11-30 21:12:39', NULL, 1),
(722, 208, 'Cachipo', '2025-11-30 21:12:39', NULL, 1),
(723, 208, 'Quiriquire', '2025-11-30 21:12:39', NULL, 1),
(724, 209, 'Santa Bárbara', '2025-11-30 21:12:39', NULL, 1),
(725, 210, 'Barrancas', '2025-11-30 21:12:39', NULL, 1),
(726, 210, 'Los Barrancos de Fajardo', '2025-11-30 21:12:39', NULL, 1),
(727, 211, 'Uracoa', '2025-11-30 21:12:39', NULL, 1),
(728, 212, 'Antolín del Campo', '2025-11-30 21:12:39', NULL, 1),
(729, 213, 'Arismendi', '2025-11-30 21:12:39', NULL, 1),
(730, 214, 'García', '2025-11-30 21:12:39', NULL, 1),
(731, 214, 'Francisco Fajardo', '2025-11-30 21:12:39', NULL, 1),
(732, 215, 'Bolívar', '2025-11-30 21:12:39', NULL, 1),
(733, 215, 'Guevara', '2025-11-30 21:12:39', NULL, 1),
(734, 215, 'Matasiete', '2025-11-30 21:12:39', NULL, 1),
(735, 215, 'Santa Ana', '2025-11-30 21:12:39', NULL, 1),
(736, 215, 'Sucre', '2025-11-30 21:12:39', NULL, 1),
(737, 216, 'Aguirre', '2025-11-30 21:12:39', NULL, 1),
(738, 216, 'Maneiro', '2025-11-30 21:12:39', NULL, 1),
(739, 217, 'Adrián', '2025-11-30 21:12:39', NULL, 1),
(740, 217, 'Juan Griego', '2025-11-30 21:12:39', NULL, 1),
(741, 217, 'Yaguaraparo', '2025-11-30 21:12:39', NULL, 1),
(742, 218, 'Porlamar', '2025-11-30 21:12:39', NULL, 1),
(743, 219, 'San Francisco de Macanao', '2025-11-30 21:12:39', NULL, 1),
(744, 219, 'Boca de Río', '2025-11-30 21:12:39', NULL, 1),
(745, 220, 'Tubores', '2025-11-30 21:12:39', NULL, 1),
(746, 220, 'Los Baleales', '2025-11-30 21:12:39', NULL, 1),
(747, 221, 'Vicente Fuentes', '2025-11-30 21:12:39', NULL, 1),
(748, 221, 'Villalba', '2025-11-30 21:12:39', NULL, 1),
(749, 222, 'San Juan Bautista', '2025-11-30 21:12:39', NULL, 1),
(750, 222, 'Zabala', '2025-11-30 21:12:39', NULL, 1),
(751, 223, 'Capital Araure', '2025-11-30 21:12:39', NULL, 1),
(752, 223, 'Río Acarigua', '2025-11-30 21:12:39', NULL, 1),
(753, 224, 'Capital Araure', '2025-11-30 21:12:39', NULL, 1),
(754, 224, 'Río Acarigua', '2025-11-30 21:12:39', NULL, 1),
(755, 225, 'Capital Esteller', '2025-11-30 21:12:39', NULL, 1),
(756, 225, 'Uveral', '2025-11-30 21:12:39', NULL, 1),
(757, 226, 'Guanare', '2025-11-30 21:12:39', NULL, 1),
(758, 226, 'Córdoba', '2025-11-30 21:12:39', NULL, 1),
(759, 226, 'San José de la Montaña', '2025-11-30 21:12:39', NULL, 1),
(760, 226, 'San Juan de Guanaguanare', '2025-11-30 21:12:39', NULL, 1),
(761, 226, 'Virgen de la Coromoto', '2025-11-30 21:12:39', NULL, 1),
(762, 227, 'Guanarito', '2025-11-30 21:12:39', NULL, 1),
(763, 227, 'Trinidad de la Capilla', '2025-11-30 21:12:39', NULL, 1),
(764, 227, 'Divina Pastora', '2025-11-30 21:12:39', NULL, 1),
(765, 228, 'Monseñor José Vicente de Unda', '2025-11-30 21:12:39', NULL, 1),
(766, 228, 'Peña Blanca', '2025-11-30 21:12:39', NULL, 1),
(767, 229, 'Capital Ospino', '2025-11-30 21:12:39', NULL, 1),
(768, 229, 'Aparición', '2025-11-30 21:12:39', NULL, 1),
(769, 229, 'La Estación', '2025-11-30 21:12:39', NULL, 1),
(770, 230, 'Páez', '2025-11-30 21:12:39', NULL, 1),
(771, 230, 'Payara', '2025-11-30 21:12:39', NULL, 1),
(772, 230, 'Pimpinela', '2025-11-30 21:12:39', NULL, 1),
(773, 230, 'Ramón Peraza', '2025-11-30 21:12:39', NULL, 1),
(774, 231, 'Papelón', '2025-11-30 21:12:39', NULL, 1),
(775, 231, 'Caño Delgadito', '2025-11-30 21:12:39', NULL, 1),
(776, 232, 'San Genaro de Boconoíto', '2025-11-30 21:12:39', NULL, 1),
(777, 232, 'Antolín Tovar', '2025-11-30 21:12:39', NULL, 1),
(778, 233, 'San Rafael de Onoto', '2025-11-30 21:12:39', NULL, 1),
(779, 233, 'Santa Fe', '2025-11-30 21:12:39', NULL, 1),
(780, 233, 'Thermo Morles', '2025-11-30 21:12:39', NULL, 1),
(781, 234, 'Santa Rosalía', '2025-11-30 21:12:39', NULL, 1),
(782, 234, 'Florida', '2025-11-30 21:12:39', NULL, 1),
(783, 235, 'Sucre', '2025-11-30 21:12:39', NULL, 1),
(784, 235, 'Concepción', '2025-11-30 21:12:39', NULL, 1),
(785, 235, 'San Rafael de Palo Alzado', '2025-11-30 21:12:39', NULL, 1),
(786, 235, 'Uvencio Antonio Velásquez', '2025-11-30 21:12:39', NULL, 1),
(787, 235, 'San José de Saguaz', '2025-11-30 21:12:39', NULL, 1),
(788, 235, 'Villa Rosa', '2025-11-30 21:12:39', NULL, 1),
(789, 236, 'Turén', '2025-11-30 21:12:39', NULL, 1),
(790, 236, 'Canelones', '2025-11-30 21:12:39', NULL, 1),
(791, 236, 'Santa Cruz', '2025-11-30 21:12:39', NULL, 1),
(792, 236, 'San Isidro Labrador', '2025-11-30 21:12:39', NULL, 1),
(793, 237, 'Mariño', '2025-11-30 21:13:11', NULL, 1),
(794, 237, 'Rómulo Gallegos', '2025-11-30 21:13:11', NULL, 1),
(795, 238, 'San José de Aerocuar', '2025-11-30 21:13:11', NULL, 1),
(796, 238, 'Tavera Acosta', '2025-11-30 21:13:11', NULL, 1),
(797, 239, 'Río Caribe', '2025-11-30 21:13:11', NULL, 1),
(798, 239, 'Antonio José de Sucre', '2025-11-30 21:13:11', NULL, 1),
(799, 239, 'El Morro de Puerto Santo', '2025-11-30 21:13:11', NULL, 1),
(800, 239, 'Puerto Santo', '2025-11-30 21:13:11', NULL, 1),
(801, 239, 'San Juan de las Galdonas', '2025-11-30 21:13:11', NULL, 1),
(802, 240, 'El Pilar', '2025-11-30 21:13:11', NULL, 1),
(803, 240, 'El Rincón', '2025-11-30 21:13:11', NULL, 1),
(804, 240, 'General Francisco Antonio Váquez', '2025-11-30 21:13:11', NULL, 1),
(805, 240, 'Guaraúnos', '2025-11-30 21:13:11', NULL, 1),
(806, 240, 'Tunapuicito', '2025-11-30 21:13:11', NULL, 1),
(807, 240, 'Unión', '2025-11-30 21:13:11', NULL, 1),
(808, 241, 'Santa Catalina', '2025-11-30 21:13:11', NULL, 1),
(809, 241, 'Santa Rosa', '2025-11-30 21:13:11', NULL, 1),
(810, 241, 'Santa Teresa', '2025-11-30 21:13:11', NULL, 1),
(811, 241, 'Bolívar', '2025-11-30 21:13:11', NULL, 1),
(812, 241, 'Maracapana', '2025-11-30 21:13:11', NULL, 1),
(813, 242, 'Libertad', '2025-11-30 21:13:11', NULL, 1),
(814, 242, 'El Paujil', '2025-11-30 21:13:11', NULL, 1),
(815, 242, 'Yaguaraparo', '2025-11-30 21:13:11', NULL, 1),
(816, 243, 'Cruz Salmerón Acosta', '2025-11-30 21:13:11', NULL, 1),
(817, 243, 'Chacopata', '2025-11-30 21:13:11', NULL, 1),
(818, 243, 'Manicuare', '2025-11-30 21:13:11', NULL, 1),
(819, 244, 'Tunapuy', '2025-11-30 21:13:11', NULL, 1),
(820, 244, 'Campo Elías', '2025-11-30 21:13:11', NULL, 1),
(821, 245, 'Irapa', '2025-11-30 21:13:11', NULL, 1),
(822, 245, 'Campo Claro', '2025-11-30 21:13:11', NULL, 1),
(823, 245, 'Maraval', '2025-11-30 21:13:11', NULL, 1),
(824, 245, 'San Antonio de Irapa', '2025-11-30 21:13:11', NULL, 1),
(825, 245, 'Soro', '2025-11-30 21:13:11', NULL, 1),
(826, 246, 'Mejía', '2025-11-30 21:13:11', NULL, 1),
(827, 247, 'Cumanacoa', '2025-11-30 21:13:11', NULL, 1),
(828, 247, 'Arenas', '2025-11-30 21:13:11', NULL, 1),
(829, 247, 'Aricagua', '2025-11-30 21:13:11', NULL, 1),
(830, 247, 'Cogollar', '2025-11-30 21:13:11', NULL, 1),
(831, 247, 'San Fernando', '2025-11-30 21:13:11', NULL, 1),
(832, 247, 'San Lorenzo', '2025-11-30 21:13:11', NULL, 1),
(833, 248, 'Villa Frontado (Muelle de Cariaco)', '2025-11-30 21:13:11', NULL, 1),
(834, 248, 'Catuaro', '2025-11-30 21:13:11', NULL, 1),
(835, 248, 'Rendón', '2025-11-30 21:13:11', NULL, 1),
(836, 248, 'San Cruz', '2025-11-30 21:13:11', NULL, 1),
(837, 248, 'Santa María', '2025-11-30 21:13:11', NULL, 1),
(838, 249, 'Altagracia', '2025-11-30 21:13:11', NULL, 1),
(839, 249, 'Santa Inés', '2025-11-30 21:13:11', NULL, 1),
(840, 249, 'Valentín Valiente', '2025-11-30 21:13:11', NULL, 1),
(841, 249, 'Ayacucho', '2025-11-30 21:13:11', NULL, 1),
(842, 249, 'San Juan', '2025-11-30 21:13:11', NULL, 1),
(843, 249, 'Raúl Leoni', '2025-11-30 21:13:11', NULL, 1),
(844, 249, 'Gran Mariscal', '2025-11-30 21:13:11', NULL, 1),
(845, 250, 'Cristóbal Colón', '2025-11-30 21:13:11', NULL, 1),
(846, 250, 'Bideau', '2025-11-30 21:13:11', NULL, 1),
(847, 250, 'Punta de Piedras', '2025-11-30 21:13:11', NULL, 1),
(848, 250, 'Güiria', '2025-11-30 21:13:11', NULL, 1),
(849, 251, 'Cristóbal Colón', '2025-11-30 21:13:11', NULL, 1),
(850, 251, 'Bideau', '2025-11-30 21:13:11', NULL, 1),
(851, 251, 'Punta de Piedras', '2025-11-30 21:13:11', NULL, 1),
(852, 251, 'Güiria', '2025-11-30 21:13:11', NULL, 1),
(853, 252, 'Andrés Bello', '2025-11-30 21:13:11', NULL, 1),
(854, 253, 'Antonio Rómulo Costa', '2025-11-30 21:13:11', NULL, 1),
(855, 254, 'Ayacucho', '2025-11-30 21:13:11', NULL, 1),
(856, 254, 'Rivas Berti', '2025-11-30 21:13:11', NULL, 1),
(857, 254, 'San Pedro del Río', '2025-11-30 21:13:11', NULL, 1),
(858, 255, 'Bolívar', '2025-11-30 21:13:11', NULL, 1),
(859, 255, 'Palotal', '2025-11-30 21:13:11', NULL, 1),
(860, 255, 'General Juan Vicente Gómez', '2025-11-30 21:13:11', NULL, 1),
(861, 255, 'Isaías Medina Angarita', '2025-11-30 21:13:11', NULL, 1),
(862, 256, 'Cárdenas', '2025-11-30 21:13:11', NULL, 1),
(863, 256, 'Amenodoro Ángel Lamus', '2025-11-30 21:13:11', NULL, 1),
(864, 256, 'La Florida', '2025-11-30 21:13:11', NULL, 1),
(865, 257, 'Córdoba', '2025-11-30 21:13:11', NULL, 1),
(866, 258, 'Fernández Feo', '2025-11-30 21:13:11', NULL, 1),
(867, 258, 'Alberto Adriani', '2025-11-30 21:13:11', NULL, 1),
(868, 258, 'Santo Domingo', '2025-11-30 21:13:11', NULL, 1),
(869, 259, 'Francisco de Miranda', '2025-11-30 21:13:11', NULL, 1),
(870, 260, 'García de Hevia', '2025-11-30 21:13:11', NULL, 1),
(871, 260, 'Boca de Grita', '2025-11-30 21:13:11', NULL, 1),
(872, 260, 'José Antonio Páez', '2025-11-30 21:13:11', NULL, 1);
INSERT INTO `parroquias` (`id_parroquia`, `id_municipio`, `nom_parroquia`, `creacion`, `actualizacion`, `estatus`) VALUES
(873, 261, 'Guásimos', '2025-11-30 21:13:11', NULL, 1),
(874, 262, 'Independencia', '2025-11-30 21:13:11', NULL, 1),
(875, 262, 'Juan Germán Roscio', '2025-11-30 21:13:11', NULL, 1),
(876, 262, 'Román Cárdenas', '2025-11-30 21:13:11', NULL, 1),
(877, 263, 'Jáuregui', '2025-11-30 21:13:11', NULL, 1),
(878, 263, 'Emilio Constantino Guerrero', '2025-11-30 21:13:11', NULL, 1),
(879, 263, 'Monseñor Miguel Antonio Salas', '2025-11-30 21:13:11', NULL, 1),
(880, 264, 'José María Vargas', '2025-11-30 21:13:11', NULL, 1),
(881, 265, 'Junín', '2025-11-30 21:13:11', NULL, 1),
(882, 265, 'La Petrólea', '2025-11-30 21:13:11', NULL, 1),
(883, 265, 'Quinimarí', '2025-11-30 21:13:11', NULL, 1),
(884, 265, 'Bramón', '2025-11-30 21:13:11', NULL, 1),
(885, 266, 'Libertad', '2025-11-30 21:13:11', NULL, 1),
(886, 266, 'Cipriano Castro', '2025-11-30 21:13:11', NULL, 1),
(887, 266, 'Manuel Felipe Rugeles', '2025-11-30 21:13:11', NULL, 1),
(888, 267, 'Libertador', '2025-11-30 21:13:11', NULL, 1),
(889, 267, 'Doradas', '2025-11-30 21:13:11', NULL, 1),
(890, 267, 'Emeterio Ochoa', '2025-11-30 21:13:11', NULL, 1),
(891, 267, 'San Joaquín de Navay', '2025-11-30 21:13:11', NULL, 1),
(892, 268, 'Lobatera', '2025-11-30 21:13:11', NULL, 1),
(893, 268, 'Constitución', '2025-11-30 21:13:11', NULL, 1),
(894, 269, 'Michelena', '2025-11-30 21:13:11', NULL, 1),
(895, 270, 'Panamericano', '2025-11-30 21:13:11', NULL, 1),
(896, 270, 'La Palmita', '2025-11-30 21:13:11', NULL, 1),
(897, 271, 'Pedro María Ureña', '2025-11-30 21:13:11', NULL, 1),
(898, 271, 'Nueva Arcadia', '2025-11-30 21:13:11', NULL, 1),
(899, 272, 'Delicias', '2025-11-30 21:13:11', NULL, 1),
(900, 272, 'Pecaya', '2025-11-30 21:13:11', NULL, 1),
(901, 273, 'Samuel Darío Maldonado', '2025-11-30 21:13:11', NULL, 1),
(902, 273, 'Boconó', '2025-11-30 21:13:11', NULL, 1),
(903, 273, 'Hernández', '2025-11-30 21:13:11', NULL, 1),
(904, 274, 'La Concordia', '2025-11-30 21:13:11', NULL, 1),
(905, 274, 'San Juan Bautista', '2025-11-30 21:13:11', NULL, 1),
(906, 274, 'Pedro María Morantes', '2025-11-30 21:13:11', NULL, 1),
(907, 274, 'San Sebastián', '2025-11-30 21:13:11', NULL, 1),
(908, 274, 'Dr. Francisco Romero Lobo', '2025-11-30 21:13:11', NULL, 1),
(909, 275, 'Seboruco', '2025-11-30 21:13:11', NULL, 1),
(910, 276, 'Simón Rodríguez', '2025-11-30 21:13:11', NULL, 1),
(911, 277, 'Sucre', '2025-11-30 21:13:11', NULL, 1),
(912, 277, 'Eleazar López Contreras', '2025-11-30 21:13:11', NULL, 1),
(913, 277, 'San Pablo', '2025-11-30 21:13:11', NULL, 1),
(914, 278, 'Torbes', '2025-11-30 21:13:11', NULL, 1),
(915, 279, 'Uribante', '2025-11-30 21:13:11', NULL, 1),
(916, 279, 'Cárdenas', '2025-11-30 21:13:11', NULL, 1),
(917, 279, 'Juan Pablo Peñalosa', '2025-11-30 21:13:11', NULL, 1),
(918, 279, 'Potosí', '2025-11-30 21:13:11', NULL, 1),
(919, 280, 'San Judas Tadeo', '2025-11-30 21:13:11', NULL, 1),
(920, 281, 'Araguaney', '2025-11-30 21:13:55', NULL, 1),
(921, 281, 'El Jaguito', '2025-11-30 21:13:55', NULL, 1),
(922, 281, 'La Esperanza', '2025-11-30 21:13:55', NULL, 1),
(923, 281, 'Santa Isabel', '2025-11-30 21:13:55', NULL, 1),
(924, 282, 'Boconó', '2025-11-30 21:13:55', NULL, 1),
(925, 282, 'El Carmen', '2025-11-30 21:13:55', NULL, 1),
(926, 282, 'Mosquey', '2025-11-30 21:13:55', NULL, 1),
(927, 282, 'Ayacucho', '2025-11-30 21:13:55', NULL, 1),
(928, 282, 'Burbusay', '2025-11-30 21:13:55', NULL, 1),
(929, 282, 'General Ribas', '2025-11-30 21:13:55', NULL, 1),
(930, 282, 'Guaramacal', '2025-11-30 21:13:55', NULL, 1),
(931, 282, 'Vega de Guaramacal', '2025-11-30 21:13:55', NULL, 1),
(932, 282, 'Monseñor Jáuregui', '2025-11-30 21:13:55', NULL, 1),
(933, 282, 'Rafael Rangel', '2025-11-30 21:13:55', NULL, 1),
(934, 282, 'San Miguel', '2025-11-30 21:13:55', NULL, 1),
(935, 282, 'San José', '2025-11-30 21:13:55', NULL, 1),
(936, 283, 'Sabana Grande', '2025-11-30 21:13:55', NULL, 1),
(937, 283, 'Cheregüé', '2025-11-30 21:13:55', NULL, 1),
(938, 283, 'Granados', '2025-11-30 21:13:55', NULL, 1),
(939, 284, 'Arnoldo Gabaldón', '2025-11-30 21:13:55', NULL, 1),
(940, 284, 'Bolivia', '2025-11-30 21:13:55', NULL, 1),
(941, 284, 'Carrillo', '2025-11-30 21:13:55', NULL, 1),
(942, 284, 'Cegarra', '2025-11-30 21:13:55', NULL, 1),
(943, 284, 'Chejendé', '2025-11-30 21:13:55', NULL, 1),
(944, 284, 'Manuel Salvador Ulloa', '2025-11-30 21:13:55', NULL, 1),
(945, 284, 'San José', '2025-11-30 21:13:55', NULL, 1),
(946, 285, 'Carache', '2025-11-30 21:13:55', NULL, 1),
(947, 285, 'La Concepción', '2025-11-30 21:13:55', NULL, 1),
(948, 285, 'Cuicas', '2025-11-30 21:13:55', NULL, 1),
(949, 285, 'Panamericana', '2025-11-30 21:13:55', NULL, 1),
(950, 285, 'Santa Cruz', '2025-11-30 21:13:55', NULL, 1),
(951, 286, 'Escuque', '2025-11-30 21:13:55', NULL, 1),
(952, 286, 'La Unión', '2025-11-30 21:13:55', NULL, 1),
(953, 286, 'Santa Rita', '2025-11-30 21:13:55', NULL, 1),
(954, 286, 'Sabana Libre', '2025-11-30 21:13:55', NULL, 1),
(955, 287, 'El Socorro', '2025-11-30 21:13:55', NULL, 1),
(956, 287, 'Los Caprichos', '2025-11-30 21:13:55', NULL, 1),
(957, 287, 'Antonio José de Sucre', '2025-11-30 21:13:55', NULL, 1),
(958, 288, 'Campo Elías', '2025-11-30 21:13:55', NULL, 1),
(959, 288, 'Arnoldo Gabaldón', '2025-11-30 21:13:55', NULL, 1),
(960, 289, 'Santa Apolonia', '2025-11-30 21:13:55', NULL, 1),
(961, 289, 'El Progreso', '2025-11-30 21:13:55', NULL, 1),
(962, 289, 'La Ceiba', '2025-11-30 21:13:55', NULL, 1),
(963, 289, 'Tres de Febrero', '2025-11-30 21:13:55', NULL, 1),
(964, 290, 'El Dividive', '2025-11-30 21:13:55', NULL, 1),
(965, 290, 'Agua Santa', '2025-11-30 21:13:55', NULL, 1),
(966, 290, 'Agua Caliente', '2025-11-30 21:13:55', NULL, 1),
(967, 290, 'El Cenizo', '2025-11-30 21:13:55', NULL, 1),
(968, 290, 'Valerita', '2025-11-30 21:13:55', NULL, 1),
(969, 291, 'Monte Carmelo', '2025-11-30 21:13:55', NULL, 1),
(970, 291, 'Buena Vista', '2025-11-30 21:13:55', NULL, 1),
(971, 291, 'Santa María del Horcón', '2025-11-30 21:13:55', NULL, 1),
(972, 292, 'Motatán', '2025-11-30 21:13:55', NULL, 1),
(973, 292, 'El Baño', '2025-11-30 21:13:55', NULL, 1),
(974, 292, 'Jalisco', '2025-11-30 21:13:55', NULL, 1),
(975, 293, 'Pampán', '2025-11-30 21:13:55', NULL, 1),
(976, 293, 'Flor de Patria', '2025-11-30 21:13:55', NULL, 1),
(977, 293, 'La Paz', '2025-11-30 21:13:55', NULL, 1),
(978, 293, 'Santa Ana', '2025-11-30 21:13:55', NULL, 1),
(979, 294, 'Pampanito', '2025-11-30 21:13:55', NULL, 1),
(980, 294, 'La Concepción', '2025-11-30 21:13:55', NULL, 1),
(981, 294, 'Pampanito II', '2025-11-30 21:13:55', NULL, 1),
(982, 295, 'Betijoque', '2025-11-30 21:13:55', NULL, 1),
(983, 295, 'José Gregorio Hernández', '2025-11-30 21:13:55', NULL, 1),
(984, 295, 'La Pueblita', '2025-11-30 21:13:55', NULL, 1),
(985, 295, 'Los Cedros', '2025-11-30 21:13:55', NULL, 1),
(986, 296, 'Carvajal', '2025-11-30 21:13:55', NULL, 1),
(987, 296, 'Campo Alegre', '2025-11-30 21:13:55', NULL, 1),
(988, 296, 'Antonio Nicolás Briceño', '2025-11-30 21:13:55', NULL, 1),
(989, 296, 'José Leonardo Suárez', '2025-11-30 21:13:55', NULL, 1),
(990, 297, 'Sabana de Mendoza', '2025-11-30 21:13:55', NULL, 1),
(991, 297, 'Junín', '2025-11-30 21:13:55', NULL, 1),
(992, 297, 'Valmore Rodríguez', '2025-11-30 21:13:55', NULL, 1),
(993, 297, 'El Paraíso', '2025-11-30 21:13:55', NULL, 1),
(994, 298, 'Andrés Linares', '2025-11-30 21:13:55', NULL, 1),
(995, 298, 'Chiquinquirá', '2025-11-30 21:13:55', NULL, 1),
(996, 298, 'Cristóbal Mendoza', '2025-11-30 21:13:55', NULL, 1),
(997, 298, 'Cruz Carrillo', '2025-11-30 21:13:55', NULL, 1),
(998, 298, 'Matriz', '2025-11-30 21:13:55', NULL, 1),
(999, 298, 'Monseñor Carrillo', '2025-11-30 21:13:55', NULL, 1),
(1000, 298, 'Tres Esquinas', '2025-11-30 21:13:55', NULL, 1),
(1001, 299, 'Cabimbú', '2025-11-30 21:13:55', NULL, 1),
(1002, 299, 'Jajó', '2025-11-30 21:13:55', NULL, 1),
(1003, 299, 'La Mesa de Esnujaque', '2025-11-30 21:13:55', NULL, 1),
(1004, 299, 'Santiago', '2025-11-30 21:13:55', NULL, 1),
(1005, 299, 'Tuñame', '2025-11-30 21:13:55', NULL, 1),
(1006, 299, 'La Quebrada', '2025-11-30 21:13:55', NULL, 1),
(1007, 300, 'Juan Ignacio Montilla', '2025-11-30 21:13:55', NULL, 1),
(1008, 300, 'La Beatriz', '2025-11-30 21:13:55', NULL, 1),
(1009, 300, 'La Puerta', '2025-11-30 21:13:55', NULL, 1),
(1010, 300, 'Mendoza del Valle de Momboy', '2025-11-30 21:13:55', NULL, 1),
(1011, 300, 'Mercedes Díaz', '2025-11-30 21:13:55', NULL, 1),
(1012, 300, 'San Luis', '2025-11-30 21:13:55', NULL, 1),
(1013, 301, 'Arístides Bastidas', '2025-11-30 21:13:55', NULL, 1),
(1014, 302, 'Bolívar', '2025-11-30 21:13:55', NULL, 1),
(1015, 303, 'Chivacoa', '2025-11-30 21:13:55', NULL, 1),
(1016, 303, 'Campo Elías', '2025-11-30 21:13:55', NULL, 1),
(1017, 304, 'Cocorote', '2025-11-30 21:13:55', NULL, 1),
(1018, 305, 'Independencia', '2025-11-30 21:13:55', NULL, 1),
(1019, 306, 'José Antonio Páez', '2025-11-30 21:13:55', NULL, 1),
(1020, 307, 'La Trinidad', '2025-11-30 21:13:55', NULL, 1),
(1021, 308, 'Manuel Monge', '2025-11-30 21:13:55', NULL, 1),
(1022, 309, 'Salóm', '2025-11-30 21:13:55', NULL, 1),
(1023, 309, 'Temerla', '2025-11-30 21:13:55', NULL, 1),
(1024, 309, 'Nirgua', '2025-11-30 21:13:55', NULL, 1),
(1025, 310, 'San Andrés', '2025-11-30 21:13:55', NULL, 1),
(1026, 310, 'Yaritagua', '2025-11-30 21:13:55', NULL, 1),
(1027, 311, 'San Javier', '2025-11-30 21:13:55', NULL, 1),
(1028, 311, 'Albarico', '2025-11-30 21:13:55', NULL, 1),
(1029, 311, 'San Felipe', '2025-11-30 21:13:55', NULL, 1),
(1030, 312, 'Sucre', '2025-11-30 21:13:55', NULL, 1),
(1031, 313, 'Urachiche', '2025-11-30 21:13:55', NULL, 1),
(1032, 314, 'El Guayabo', '2025-11-30 21:13:55', NULL, 1),
(1033, 314, 'Farriar', '2025-11-30 21:13:55', NULL, 1),
(1034, 315, 'Isla de Toas', '2025-11-30 21:13:55', NULL, 1),
(1035, 315, 'Monagas', '2025-11-30 21:13:55', NULL, 1),
(1036, 316, 'San Timoteo', '2025-11-30 21:13:55', NULL, 1),
(1037, 316, 'General Urdaneta', '2025-11-30 21:13:55', NULL, 1),
(1038, 316, 'Libertador', '2025-11-30 21:13:55', NULL, 1),
(1039, 316, 'Marcelino Briceño', '2025-11-30 21:13:55', NULL, 1),
(1040, 316, 'Pueblo Nuevo', '2025-11-30 21:13:55', NULL, 1),
(1041, 316, 'Manuel Guanipa Matos', '2025-11-30 21:13:55', NULL, 1),
(1042, 317, 'Ambrosio', '2025-11-30 21:13:55', NULL, 1),
(1043, 317, 'Carmen Herrera', '2025-11-30 21:13:55', NULL, 1),
(1044, 317, 'La Rosa', '2025-11-30 21:13:55', NULL, 1),
(1045, 317, 'Germán Ríos Linares', '2025-11-30 21:13:55', NULL, 1),
(1046, 317, 'San Benito', '2025-11-30 21:13:55', NULL, 1),
(1047, 317, 'Rómulo Betancourt', '2025-11-30 21:13:55', NULL, 1),
(1048, 317, 'Jorge Hernández', '2025-11-30 21:13:55', NULL, 1),
(1049, 317, 'Punta Gorda', '2025-11-30 21:13:55', NULL, 1),
(1050, 317, 'Arístides Calvani', '2025-11-30 21:13:55', NULL, 1),
(1051, 318, 'Encontrados', '2025-11-30 21:13:55', NULL, 1),
(1052, 318, 'Udón Pérez', '2025-11-30 21:13:55', NULL, 1),
(1053, 319, 'Moralito', '2025-11-30 21:13:55', NULL, 1),
(1054, 319, 'San Carlos del Zulia', '2025-11-30 21:13:55', NULL, 1),
(1055, 319, 'Santa Cruz del Zulia', '2025-11-30 21:13:55', NULL, 1),
(1056, 319, 'Santa Bárbara', '2025-11-30 21:13:55', NULL, 1),
(1057, 319, 'Urribarrí', '2025-11-30 21:13:55', NULL, 1),
(1058, 320, 'Carlos Quevedo', '2025-11-30 21:13:55', NULL, 1),
(1059, 320, 'Francisco Javier Pulgar', '2025-11-30 21:13:55', NULL, 1),
(1060, 320, 'Simón Rodríguez', '2025-11-30 21:13:55', NULL, 1),
(1061, 320, 'Guamo-Gavilanes', '2025-11-30 21:13:55', NULL, 1),
(1062, 322, 'La Concepción', '2025-11-30 21:13:55', NULL, 1),
(1063, 322, 'San José', '2025-11-30 21:13:55', NULL, 1),
(1064, 322, 'Mariano Parra León', '2025-11-30 21:13:55', NULL, 1),
(1065, 322, 'José Ramón Yépez', '2025-11-30 21:13:55', NULL, 1),
(1066, 323, 'Jesús María Semprún', '2025-11-30 21:13:55', NULL, 1),
(1067, 323, 'Barí', '2025-11-30 21:13:55', NULL, 1),
(1068, 324, 'Concepción', '2025-11-30 21:13:55', NULL, 1),
(1069, 324, 'Andrés Bello', '2025-11-30 21:13:55', NULL, 1),
(1070, 324, 'Chiquinquirá', '2025-11-30 21:13:55', NULL, 1),
(1071, 324, 'El Carmelo', '2025-11-30 21:13:55', NULL, 1),
(1072, 324, 'Potreritos', '2025-11-30 21:13:55', NULL, 1),
(1073, 325, 'Libertad', '2025-11-30 21:13:55', NULL, 1),
(1074, 325, 'Alonso de Ojeda', '2025-11-30 21:13:55', NULL, 1),
(1075, 325, 'Venezuela', '2025-11-30 21:13:55', NULL, 1),
(1076, 325, 'Eleazar López Contreras', '2025-11-30 21:13:55', NULL, 1),
(1077, 325, 'Campo Lara', '2025-11-30 21:13:55', NULL, 1),
(1078, 326, 'Bartolomé de las Casas', '2025-11-30 21:13:55', NULL, 1),
(1079, 326, 'Libertad', '2025-11-30 21:13:55', NULL, 1),
(1080, 326, 'Río Negro', '2025-11-30 21:13:55', NULL, 1),
(1081, 326, 'San José de Perijá', '2025-11-30 21:13:55', NULL, 1),
(1082, 327, 'San Rafael', '2025-11-30 21:13:55', NULL, 1),
(1083, 327, 'La Sierrita', '2025-11-30 21:13:55', NULL, 1),
(1084, 327, 'Las Parcelas', '2025-11-30 21:13:55', NULL, 1),
(1085, 327, 'Luis de Vicente', '2025-11-30 21:13:55', NULL, 1),
(1086, 327, 'Monseñor Marcos Sergio Godoy', '2025-11-30 21:13:55', NULL, 1),
(1087, 327, 'Ricaurte', '2025-11-30 21:13:55', NULL, 1),
(1088, 327, 'Tamare', '2025-11-30 21:13:55', NULL, 1),
(1089, 328, 'Antonio Borjas Romero', '2025-11-30 21:13:55', NULL, 1),
(1090, 328, 'Bolívar', '2025-11-30 21:13:55', NULL, 1),
(1091, 328, 'Cacique Mara', '2025-11-30 21:13:55', NULL, 1),
(1092, 328, 'Carracciolo Parra Pérez', '2025-11-30 21:13:55', NULL, 1),
(1093, 328, 'Cecilio Acosta', '2025-11-30 21:13:55', NULL, 1),
(1094, 328, 'Cristo de Aranza', '2025-11-30 21:13:55', NULL, 1),
(1095, 328, 'Coquivacoa', '2025-11-30 21:13:55', NULL, 1),
(1096, 328, 'Chiquinquirá', '2025-11-30 21:13:55', NULL, 1),
(1097, 328, 'Francisco Eugenio Bustamante', '2025-11-30 21:13:55', NULL, 1),
(1098, 328, 'Idelfonzo Vásquez', '2025-11-30 21:13:55', NULL, 1),
(1099, 328, 'Juana de Ávila', '2025-11-30 21:13:55', NULL, 1),
(1100, 328, 'Luis Hurtado Higuera', '2025-11-30 21:13:55', NULL, 1),
(1101, 328, 'Manuel Dagnino', '2025-11-30 21:13:55', NULL, 1),
(1102, 328, 'Olegario Villalobos', '2025-11-30 21:13:55', NULL, 1),
(1103, 328, 'Raúl Leoni', '2025-11-30 21:13:55', NULL, 1),
(1104, 328, 'Santa Lucía', '2025-11-30 21:13:55', NULL, 1),
(1105, 328, 'Venancio Pulgar', '2025-11-30 21:13:55', NULL, 1),
(1106, 328, 'San Isidro', '2025-11-30 21:13:55', NULL, 1),
(1107, 329, 'Altagracia', '2025-11-30 21:13:55', NULL, 1),
(1108, 329, 'Faría', '2025-11-30 21:13:55', NULL, 1),
(1109, 329, 'Ana María Campos', '2025-11-30 21:13:55', NULL, 1),
(1110, 329, 'San Antonio', '2025-11-30 21:13:55', NULL, 1),
(1111, 329, 'San José', '2025-11-30 21:13:55', NULL, 1),
(1112, 330, 'Donaldo García', '2025-11-30 21:13:55', NULL, 1),
(1113, 330, 'El Rosario', '2025-11-30 21:13:55', NULL, 1),
(1114, 330, 'Sixto Zambrano', '2025-11-30 21:13:55', NULL, 1),
(1115, 331, 'San Francisco', '2025-11-30 21:13:55', NULL, 1),
(1116, 331, 'El Bajo', '2025-11-30 21:13:55', NULL, 1),
(1117, 331, 'Domitila Flores', '2025-11-30 21:13:55', NULL, 1),
(1118, 331, 'Francisco Ochoa', '2025-11-30 21:13:55', NULL, 1),
(1119, 331, 'Los Cortijos', '2025-11-30 21:13:55', NULL, 1),
(1120, 331, 'Marcial Hernández', '2025-11-30 21:13:55', NULL, 1),
(1121, 332, 'Santa Rita', '2025-11-30 21:13:55', NULL, 1),
(1122, 332, 'El Mene', '2025-11-30 21:13:55', NULL, 1),
(1123, 332, 'Pedro Lucas Urribarrí', '2025-11-30 21:13:55', NULL, 1),
(1124, 332, 'José Cenobio Urribarrí', '2025-11-30 21:13:55', NULL, 1),
(1125, 333, 'Rafael Maria Baralt', '2025-11-30 21:13:55', NULL, 1),
(1126, 333, 'Manuel Manrique', '2025-11-30 21:13:55', NULL, 1),
(1127, 333, 'Rafael Urdaneta', '2025-11-30 21:13:55', NULL, 1),
(1128, 334, 'Bobures', '2025-11-30 21:13:55', NULL, 1),
(1129, 334, 'Gibraltar', '2025-11-30 21:13:55', NULL, 1),
(1130, 334, 'Heras', '2025-11-30 21:13:55', NULL, 1),
(1131, 334, 'Monseñor Arturo Álvarez', '2025-11-30 21:13:55', NULL, 1),
(1132, 334, 'Rómulo Gallegos', '2025-11-30 21:13:55', NULL, 1),
(1133, 334, 'El Batey', '2025-11-30 21:13:55', NULL, 1),
(1134, 335, 'Rafael Urdaneta', '2025-11-30 21:13:55', NULL, 1),
(1135, 335, 'La Victoria', '2025-11-30 21:13:55', NULL, 1),
(1136, 335, 'Raúl Cuenca', '2025-11-30 21:13:55', NULL, 1),
(1137, 336, 'Archipiélago Los Roques', '2025-11-30 21:13:55', NULL, 1),
(1138, 336, 'Archipiélago Los Monjes', '2025-11-30 21:13:55', NULL, 1),
(1139, 336, 'Isla La Tortuga y Cayos adyacentes', '2025-11-30 21:13:55', NULL, 1),
(1140, 336, 'Isla La Sola', '2025-11-30 21:13:55', NULL, 1),
(1141, 336, 'Islas Los Testigos', '2025-11-30 21:13:55', NULL, 1),
(1142, 336, 'Islas Los Frailes', '2025-11-30 21:13:55', NULL, 1),
(1143, 336, 'Isla La Orchila', '2025-11-30 21:13:55', NULL, 1),
(1144, 336, 'Archipiélago Las Aves', '2025-11-30 21:13:55', NULL, 1),
(1145, 336, 'Isla de Aves', '2025-11-30 21:13:55', NULL, 1),
(1146, 336, 'Isla La Blanquilla', '2025-11-30 21:13:55', NULL, 1),
(1147, 336, 'Isla de Patos', '2025-11-30 21:13:55', NULL, 1),
(1148, 336, 'Islas Los Hermanos', '2025-11-30 21:13:55', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patologias`
--

CREATE TABLE `patologias` (
  `id_patologia` int(11) NOT NULL,
  `nom_patologia` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `patologias`
--

INSERT INTO `patologias` (`id_patologia`, `nom_patologia`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Asma', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 'Alergia a lácteos', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 'Alergia al polen', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 'Rinitis alérgica', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `id_periodo` int(11) NOT NULL,
  `descripcion_periodo` varchar(255) NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`id_periodo`, `descripcion_periodo`, `fecha_ini`, `fecha_fin`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Año Escolar 2024-2025', '2024-09-01', '2025-07-15', '2025-11-10 06:17:16', '2025-12-07 17:55:55', 0),
(2, 'Año Escolar 2023-2024', '2023-09-01', '2024-07-15', '2025-11-20 05:11:53', '2025-12-02 17:47:20', 0),
(3, 'Año Escolar 2025-2026', '2025-09-15', '2026-07-15', '2025-11-30 14:41:56', '2025-12-07 18:05:02', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permiso` int(11) NOT NULL,
  `nom_url` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permiso`, `nom_url`, `url`, `descripcion`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'dashboard', 'admin/index.php', 'Acceso al panel principal', '2026-01-01 01:08:14', NULL, 1),
(2, 'configuraciones', 'admin/configuraciones/index.php', 'Acceso al módulo de configuraciones', '2026-01-01 01:08:14', NULL, 1),
(3, 'docentes_list', 'views/docentes/docentes_list.php', 'Ver listado de docentes', '2026-01-01 01:08:14', NULL, 1),
(4, 'estudiantes_list', 'admin/estudiantes/estudiantes_list.php', 'Ver listado de estudiantes', '2026-01-01 01:08:14', NULL, 1),
(5, 'inscripciones', 'admin/inscripciones/indexf2.php', 'Realizar inscripciones', '2026-01-01 01:08:14', NULL, 1),
(6, 'reinscripciones', 'admin/reinscripciones/reinscripcion2.php', 'Realizar reinscripciones', '2026-01-01 01:08:14', NULL, 1),
(7, 'niveles_list', 'views/grados/grados_list_solo_lectura.php', 'Ver niveles y grados', '2026-01-01 01:08:14', NULL, 1),
(8, 'representantes_list', 'admin/representantes/representantes_list.php', 'Ver listado de representantes', '2026-01-01 01:08:14', NULL, 1),
(9, 'roles_permisos', 'admin/roles_permisos/index.php', 'Acceso al módulo de roles y permisos', '2026-01-07 01:56:27', NULL, 1),
(10, 'roles_permisos_guardar', 'admin/roles_permisos/guardar_permisos.php', 'Guardar permisos de roles', '2026-01-07 01:56:27', NULL, 1),
(11, 'roles_permisos_rol', 'admin/roles_permisos/guardar_rol.php', 'Guardar/editar roles', '2026-01-07 01:56:27', NULL, 1),
(12, 'institucion_config', 'admin/configuraciones/configuracion/institucion.php', 'Configuración de información institucional', '2026-01-15 16:24:23', NULL, 1),
(13, 'periodos_config', 'admin/configuraciones/configuracion/periodos.php', 'Configuración de períodos académicos', '2026-01-15 16:24:23', NULL, 1),
(14, 'edades_config', 'admin/configuraciones/configuracion/edades.php', 'Configuración de rangos de edades', '2026-01-15 16:24:23', NULL, 1),
(15, 'discapacidades_config', 'admin/configuraciones/configuracion/discapacidades.php', 'Gestión de discapacidades', '2026-01-15 16:24:23', NULL, 1),
(16, 'grados_editar', 'views/grados/grados_list.php', 'Editar niveles y grados (no solo lectura)', '2026-01-15 16:24:23', NULL, 1),
(17, 'profesiones_config', 'admin/configuraciones/configuracion/profesiones.php', 'Gestión de profesiones', '2026-01-15 16:24:23', NULL, 1),
(18, 'ubicacion_config', 'admin/configuraciones/configuracion/ubicacion.php', 'Configuración de ubicación geográfica', '2026-01-15 16:24:23', NULL, 1),
(19, 'patologias_config', 'admin/configuraciones/configuracion/patologias.php', 'Gestión de patologías médicas', '2026-01-15 16:24:23', NULL, 1),
(20, 'parentesco_config', 'admin/configuraciones/configuracion/parentesco.php', 'Gestión de tipos de parentesco', '2026-01-15 16:24:23', NULL, 1),
(21, 'docentes_dashboard', 'admin-docentes/index.php', 'Panel principal para docentes', '2026-01-15 16:24:23', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personas`
--

CREATE TABLE `personas` (
  `id_persona` int(11) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  `primer_nombre` varchar(255) NOT NULL,
  `segundo_nombre` varchar(255) DEFAULT NULL,
  `primer_apellido` varchar(255) NOT NULL,
  `segundo_apellido` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `telefono_hab` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `foto_representante` varchar(255) DEFAULT NULL,
  `foto_estudiante` varchar(255) DEFAULT NULL,
  `lugar_nac` varchar(255) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `nacionalidad` varchar(255) DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO `personas` (`id_persona`, `id_direccion`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `cedula`, `telefono`, `telefono_hab`, `correo`, `foto_representante`, `foto_estudiante`, `lugar_nac`, `fecha_nac`, `sexo`, `nacionalidad`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'María', 'Gabriela', 'Pérez', 'González', '28987654', '04141234567', '02127788991', 'maria.perez@email.com', NULL, NULL, 'Caracas', '2015-03-15', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 'Carlos', 'José', 'Rodríguez', 'López', '29012345', '04149876543', '02128877665', 'carlos.rodriguez@email.com', NULL, NULL, 'Caracas', '2016-07-22', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 'Ana', 'Isabel', 'García', 'Mendoza', '29123456', '04148765432', '02129988776', 'ana.garcia@email.com', NULL, NULL, 'Caracas', '2015-11-08', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, 'Luis', 'Alberto', 'Martínez', 'Rojas', '29234567', '04147654321', '02126655443', 'luis.martinez@email.com', NULL, NULL, 'Caracas', '2016-01-30', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, 'Valentina', 'Sophia', 'Hernández', 'Silva', '29345678', '04146543210', '02125544332', 'valentina.hernandez@email.com', NULL, NULL, 'Caracas', '2015-09-14', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, 'Diego', 'Alejandro', 'Torres', 'Ramírez', '29456789', '04145432109', '02124433221', 'diego.torres@email.com', NULL, NULL, 'Caracas', '2016-04-05', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, 'Sofía', 'Camila', 'Díaz', 'Fernández', '29567890', '04144321098', '02123322110', 'sofia.diaz@email.com', NULL, NULL, 'Caracas', '2015-12-18', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(8, 1, 'Carmen', 'Elena', 'González', 'Pérez', '15678901', '04141234568', '02127788992', 'carmen.gonzalez@email.com', NULL, NULL, 'Caracas', '1980-05-20', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-23 20:27:06', 1),
(9, 2, 'José', 'Luis', 'López', 'Rodríguez', '16789012', '04149876544', '02128877666', 'jose.lopez@email.com', NULL, NULL, 'Caracas', '1978-08-15', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-27 01:35:47', 1),
(10, 3, 'Isabel', 'Carmen', 'Mendoza', 'García', '17890123', '04148765433', '02129988777', 'isabel.mendoza@email.com', NULL, NULL, 'Caracas', '1982-03-10', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-25 20:53:36', 1),
(11, 4, 'ALBERTO', 'JOSÉ', 'ROJAS', 'MARTÍNEZ', '18901234', '04147654322', '02126655444', 'alberto.rojas@email.com', NULL, NULL, 'CARACAS', '1975-11-25', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-12-02 01:04:35', 1),
(12, 5, 'Roberto', 'Carlos', 'Silva', 'Hernández', '19012345', '04146543211', '02125544333', 'roberto.silva@email.com', NULL, NULL, 'Caracas', '1979-07-30', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(13, 6, 'Patricia', 'Ana', 'Ramírez', 'Torres', '20123456', '04145432110', '02124433222', 'patricia.ramirez@email.com', NULL, NULL, 'Caracas', '1981-09-05', 'Femenino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(14, 7, 'Fernando', 'Luis', 'Fernández', 'Díaz', '21234567', '04144321099', '02123322111', 'fernando.fernandez@email.com', NULL, NULL, 'Caracas', '1977-12-12', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(15, 1, 'Admin', 'Sistema', 'Neudelys', 'School', '12345678', '04140000000', '02120000000', 'admin@neudelys.edu.ve', NULL, NULL, 'Caracas', '1990-01-01', 'Masculino', 'Venezolano', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(24, 13, 'Briant', 'briant', 'Sanchez', 'carrillo', '12344321', '04149105229', '02127788992', 'briant@gmail.com', NULL, NULL, 'El paraiso', '2025-10-28', 'Masculino', 'Venezolano', '2025-11-11 18:59:50', NULL, 1),
(25, 14, 'Briant', 'Alessandro', 'Carrillo', 'Sanchez', '27318765', '04149105229', '04149105229', 'Briant1@gmail.com', NULL, NULL, 'Caracas', '2025-11-04', 'Masculino', 'Venezolano', '2025-11-11 19:30:17', '2025-11-20 16:05:39', 1),
(26, 15, 'Hugo', 'massimo', 'Carrillo', 'Mendez', '32846139', '04149015229', '04149105229', 'hugo@gmail.com', NULL, NULL, 'El paraiso', '2025-10-26', 'Masculino', 'Venezolano', '2025-11-11 19:30:17', NULL, 1),
(27, 16, 'Aharon', 'Orlando', 'Stojs', 'Shein', '123456783', '04149015229', '02127788992', 'aharon@gmail.com', NULL, NULL, 'El paraiso', '1879-12-30', 'Femenino', 'Venezolano', '2025-11-11 20:02:31', NULL, 1),
(28, 17, 'Maria', 'Alicia', 'Sanchez', 'Devia', '9344828', '04149105229', '04149105229', 'alicias@gmail.com', NULL, NULL, 'El paraiso', '1998-11-30', 'Femenino', 'Venezolano', '2025-11-11 22:16:43', NULL, 1),
(29, 18, 'Pablo', 'Hugo', 'Chacon', 'Mejias', '76544567', '04149105229', '04149105229', 'pablo@gmail.com', NULL, NULL, 'El paraiso', '2025-10-28', 'Masculino', 'Venezolano', '2025-11-11 22:16:43', NULL, 1),
(30, 19, 'Meyly', 'J', 'Pinto', 'G', '657483762', '04125079067', '04125079067', 'meylypinto@gmail.com', NULL, NULL, 'Caracas', '1971-12-14', 'Femenino', 'Venezolano', '2025-11-11 22:52:57', NULL, 1),
(31, 20, 'Jose', 'Carlos', 'Andrades', 'Pinto', '34562738', '04149105229', '04125079067', 'Jose@gmail.com', NULL, NULL, 'El paraiso', '2025-01-11', 'Masculino', 'Venezolano', '2025-11-11 22:52:57', NULL, 1),
(32, 7, 'Luny', 'k', 'Lopez', 'm', '57481293', '04149105229', '02123322111', 'luny@gmail.com', NULL, NULL, 'El paraiso', '1199-11-30', 'Masculino', 'Venezolano', '2025-11-13 01:21:00', NULL, 1),
(33, 21, 'Carlos', 'Enrique', 'Moreno', 'Salazar', '27654322', '0416789542', '', 'cmoreno@gmail.com', NULL, NULL, 'Caracas', '1990-01-01', 'Masculino', 'Venezolano', '2025-11-17 04:30:35', NULL, 1),
(34, 29, 'Roberto', 'Andres', 'Dela', 'Salazar', '12333666', '0412345678', '000000000', 'rdela@gmail.com', NULL, NULL, 'Caracas', '1990-01-01', 'Masculino', 'Venezolano', '2025-11-17 04:41:45', '2025-12-02 01:25:19', 1),
(35, 29, 'K', 'K', 'K', 'K', '20112333666', '04149105229', '000000000', 'K@gmail.com', NULL, NULL, 'El paraiso', '2020-12-22', 'Masculino', 'Venezolano', '2025-11-20 03:45:22', NULL, 1),
(36, 29, 'S', 's', 's', 's', '20212333666', '04149105229', '000000000', 'S@gmail.com', NULL, NULL, 'El paraiso', '2020-12-09', 'Masculino', 'Venezolano', '2025-11-20 04:15:06', NULL, 1),
(37, 30, 'A', 'A', 'A', 'A', '123151', '04149105229', '04149105229', 'a@mgil.com', NULL, NULL, 'CARACAS', '2025-11-05', 'MASCULINO', 'VENEZOLANO', '2025-11-20 04:38:27', '2025-12-01 19:26:24', 1),
(38, 30, 'A', 'a', 'a', 'm', '201123151', '04149015229', '04149105229', 'ak@gmial.com', NULL, NULL, 'El paraisoe', '2020-12-02', 'Femenino', 'Venezolano', '2025-11-20 04:38:27', NULL, 1),
(39, 14, 'Nn', 'N', 'N', 'N', '20227318765', '04149105229', '04149105229', 'N@GMAIL.COM', NULL, NULL, 'El paraisoe', '2020-12-14', 'Masculino', 'Venezolano', '2025-11-20 20:05:39', NULL, 1),
(40, 31, 'f', 'F', 'F', 'F', '15521097', '04125079067', '04125079067', 'F@GMAIL.COM', NULL, NULL, 'Caracas', '2025-12-31', 'Masculino', 'Venezolano', '2025-11-20 20:09:12', NULL, 1),
(41, 31, 'g', 'G', 'G', 'G', '20115521097', '04149105229', '04125079067', 'GH@GMAIL.COM', NULL, NULL, 'G', '2020-12-31', 'Masculino', 'Venezolano', '2025-11-20 20:09:12', NULL, 1),
(42, 32, 'Z', 'Z', 'Z', 'Z', '20415678901', '04149105229', '02127788992', 'J@gmail.com', NULL, NULL, 'El paraisoe', '2020-12-17', 'Femenino', 'Venezolano', '2025-11-20 21:54:05', NULL, 1),
(43, 33, 'T', 'briant', 'T', 'carrillo', '20515678901', '0412785942', '02127788992', 't@GMAIL.COM', NULL, NULL, 'El paraiso', '2020-12-16', 'Masculino', 'Venezolano', '2025-11-20 22:01:18', NULL, 1),
(44, 1, 'elo', 'eli', 'as', 'ta', '20615678901', '04149015229', '02127788992', 'elo@gmail.com', NULL, NULL, 'El paraisoe', '2020-12-09', 'Masculino', 'Venezolano', '2025-11-22 22:32:30', NULL, 1),
(46, 1, 'z', 'z', 'z', 'z', '20715678901', '04149105229', '02127788992', 'z@gmail.com', NULL, NULL, 'El paraiso', '2020-12-31', 'Masculino', 'Venezolano', '2025-11-24 00:27:06', NULL, 1),
(47, 34, 'Andres', 'Eloy', 'Blanco', '', '13544321', '0412654324', '', 'aeloy@gmail.com', NULL, NULL, 'Caracas', '1975-11-15', 'Masculino', 'Venezolano', '2025-11-24 23:38:25', '2025-12-02 01:21:12', 1),
(48, 35, 'Ronald', '', 'Delgado', '', '12467895', '0412543267', '', '', NULL, NULL, 'Caracas', '1977-09-15', 'Masculino', 'Venezolano', '2025-11-25 00:44:20', NULL, 1),
(49, 3, 'Adriana', 'Carolina', 'Rodríguez', 'Mendoza', '19217890123', '', '02129988777', '', NULL, NULL, 'Caracas', '2019-09-10', 'Femenino', 'Venezolano', '2025-11-26 00:53:36', NULL, 1),
(50, 2, 'Joheiric', 'Alexa', 'Lopez', 'Carrera', '19216789012', '', '02128877666', '', NULL, NULL, 'Caracas', '2019-12-10', 'Femenino', 'Venezolano', '2025-11-27 05:35:47', NULL, 1),
(51, 36, 'Juan', 'Luis', 'Guerra', 'Guerra', '27365944', '04142236676', '', 'jguerra@gmail.com', NULL, NULL, 'San Félix', '1980-12-30', 'Masculino', 'Venezolano', '2025-11-28 04:37:12', NULL, 1),
(52, 36, 'Andres', 'Angel', 'Guerra', '', '16127365944', '', '', 'a@guerra.com', NULL, NULL, 'Caracas', '2016-07-08', 'Masculino', 'Venezolano', '2025-11-28 04:37:12', NULL, 1),
(53, 37, 'TRINIDAD', 'SAMALIA', 'CABRERA', 'LATAN', '23638934', '0412634546', '', 'tsamalia@gmail.com', '/final/uploads/fotos/representante_23638934_1766952448.jpg', NULL, 'SAN FÉLIX', '1993-07-04', 'Femenino', 'Venezolano', '2025-11-28 23:32:52', '2025-12-02 17:32:27', 1),
(54, 37, 'Santiago', '', 'Pacheco', 'Cabrera', '17123638934', '', '', '', NULL, NULL, 'Puerto Ordaz', '2017-01-28', 'Masculino', 'Venezolano', '2025-11-28 23:32:52', '2025-12-02 16:55:16', 1),
(58, 37, 'Sophia', 'Valentina', 'Pacheco', 'Cabrera', '35765487', '', '', '', NULL, NULL, 'Puerto Ordaz', '2014-12-28', 'Femenino', 'Venezolano', '2025-11-29 00:49:29', '2025-12-02 13:11:04', 1),
(62, 37, 'Caro', '', 'Pacheco', '', '16323638934', '', '', '', NULL, NULL, 'Puerto Ordaz', '2016-08-20', 'Femenino', 'Venezolano', '2025-11-29 01:26:47', '2025-12-02 17:31:38', 1),
(64, 37, 'aslan', '', 'Pacheco', '', '15423638934', '', '', '', NULL, NULL, 'Caracas', '2015-07-13', 'Femenino', 'Venezolano', '2025-11-29 01:31:17', '2025-11-30 14:00:35', 1),
(67, 37, 'Juana', '', 'Pacheco', '', '20523638934', '', '', '', NULL, NULL, 'Caracas', '2020-09-15', 'Femenino', 'Venezolano', '2025-11-29 02:06:22', NULL, 1),
(69, 37, 'Carolina', '', 'Lopez', '', '20623638934', '', '', '', NULL, NULL, 'Caracas', '2020-07-12', 'Femenino', 'Venezolano', '2025-11-29 02:36:49', '2025-12-01 20:50:03', 1),
(73, 37, 'DANIEL', '', 'LOPEZ', '', '18723638934', '', '', '', NULL, NULL, 'CARACAS', '2018-11-10', 'MASCULINO', 'VENEZOLANO', '2025-11-29 03:01:30', '2025-12-01 19:51:36', 1),
(75, 37, 'daniela', '', 'lopez', '', '19823638934', '', '', '', NULL, NULL, 'Caracas', '2019-06-11', 'Femenino', 'Venezolano', '2025-11-29 03:13:49', '2025-12-02 17:32:27', 1),
(77, 37, 'VENCIDA', '', 'LOPEZ', '', '18923638934', '', '', '', NULL, NULL, 'CARACAS', '2018-11-10', 'FEMENINO', 'VENEZOLANO', '2025-11-29 03:25:04', '2025-12-01 19:54:27', 1),
(79, 38, 'CAMILA', '', 'LOPEZ', '', '201023638934', '', '', '', NULL, '/final/uploads/fotos/estudiante_201023638934_1766951733.jpg', 'CARACAS', '2020-06-08', 'Femenino', 'Venezolano', '2025-11-29 03:45:48', '2025-11-30 13:53:59', 1),
(81, 37, 'Vencidita', '', 'Lopez', '', '201123638934', '', '', '', NULL, NULL, 'Caracas', '2020-04-13', 'Femenino', 'Venezolano', '2025-11-29 03:53:17', NULL, 1),
(83, 37, 'Maria', '', 'Ramírez', '', '201223638934', '', '', '', NULL, NULL, 'Caracas', '2020-03-15', 'Femenino', 'Venezolano', '2025-11-29 15:06:58', '2025-11-30 12:50:32', 1),
(87, 37, 'Carlos', '', 'Lopez', '', '191323638934', '', '', '', NULL, NULL, 'Caracas', '2019-02-10', 'Masculino', 'Venezolano', '2025-11-29 16:39:28', '2025-12-02 17:23:10', 1),
(91, 37, 'Pedro', '', 'Perez', '', '191423638934', '', '', '', NULL, NULL, 'Caracas', '2019-03-13', 'Masculino', 'Venezolano', '2025-11-29 16:54:26', '2025-11-30 13:34:37', 1),
(93, 37, 'Petra', '', 'Perez', '', '191523638934', '', '', '', NULL, NULL, 'Caracas', '2019-12-20', 'Femenino', 'Venezolano', '2025-11-30 01:39:09', '2025-11-30 13:52:23', 1),
(95, 37, 'MARTHA', '', 'LOPEZ', '', '191623638934', '', '', '', NULL, NULL, 'CARACAS', '2019-03-20', 'FEMENINO', 'VENEZOLANO', '2025-11-30 01:55:13', '2025-12-01 19:52:29', 1),
(97, 39, 'NEULYS', '', 'CABRERA', '', '15683394', '0412345678', '', 'ncabrera@gmail.com', NULL, NULL, 'San Félix', '1980-06-16', 'Femenino', 'Venezolano', '2025-11-30 14:45:26', '2025-12-07 20:18:01', 1),
(98, 39, 'Gerardo', 'José', 'Rimac', 'Cabrera', '33765434', '', '', '', NULL, NULL, 'Caracas', '2009-03-15', 'Masculino', 'Venezolano', '2025-11-30 14:45:26', '2025-11-30 11:46:35', 1),
(100, 37, 'MAGDALENA', '', 'SALAZAR', '', '191723638934', '', '', '', NULL, NULL, 'CARACAS', '2019-04-15', 'Femenino', 'Venezolano', '2025-12-01 04:46:04', NULL, 1),
(102, 37, 'MARIA', '', 'PQCHECO', '', '21345678', '', '', '', NULL, NULL, 'CARACAS', '2009-12-10', 'Femenino', 'Venezolano', '2025-12-01 06:00:26', NULL, 1),
(104, 39, 'DANIEL ', 'DAVID', 'CAMPOS', '', '32133233', '', '', 'dcampos38@gmail.com', NULL, NULL, 'CARACAS', '2007-10-15', 'MASCULINO', 'VENEZOLANO', '2025-12-01 06:40:04', '2025-12-02 01:26:18', 1),
(106, 41, 'KARINA', '', 'GARCIA', '', '12683394', '0412546789', '', 'kgarcia@gmail.com', NULL, NULL, 'CARACAS', '1980-09-13', 'Femenino', 'Venezolano', '2025-12-01 21:27:51', '2025-12-01 20:04:30', 1),
(107, 41, 'ANTHONELLA', 'DANIELA', 'PEREZ', 'GARCIA', '33245678', '', '', '', NULL, NULL, 'CARACAS', '2009-03-05', 'Femenino', 'Venezolano', '2025-12-01 21:27:51', NULL, 1),
(109, 41, 'ANDRES', 'ANASTACIO', 'LOPEZ', 'GARCIA', '16212683394', '', '', '', NULL, NULL, 'CARACAS', '2016-12-10', 'MASCULINO', 'VENEZOLANO', '2025-12-01 22:08:02', '2025-12-01 20:04:30', 1),
(111, 39, 'CHRISTIAN', 'JOSÉ', 'RODRIGUEZ', 'SALAZAR', '16315683394', '', '', '', NULL, NULL, 'CARACAS', '2016-11-17', 'Masculino', 'Venezolano', '2025-12-02 05:36:27', '2025-12-02 01:39:22', 1),
(113, 37, 'MARIA', '', 'RODRIGUEZ', '', '181923638934', '', '', '', NULL, NULL, 'CARACAS', '2018-06-30', 'Femenino', 'Venezolano', '2025-12-02 14:41:06', NULL, 1),
(115, 43, 'JUAN', '', 'CABRERA', '', '152023638934', '', '', '', NULL, NULL, 'CARACAS', '2015-02-11', 'Masculino', 'Venezolano', '2025-12-02 17:09:44', NULL, 1),
(117, 37, 'CARLA', 'ANDREINA', 'FERNANDEZ', 'GONZALEZ', '172123638934', '', '', '', NULL, NULL, 'CARACAS', '2017-07-14', 'Femenino', 'Venezolano', '2025-12-02 20:53:30', NULL, 1),
(119, 37, 'ANA', '', 'PRIMERA', '', '152223638934', '', '', '', NULL, NULL, 'CARACAS', '2015-07-31', 'Femenino', 'Venezolano', '2025-12-02 21:15:59', NULL, 1),
(121, 39, 'CARLA', 'MARIA', 'GONZALEZ', 'PEREZ', '16415683394', '', '', '', NULL, NULL, 'CARACAS', '2016-07-15', 'Femenino', 'Venezolano', '2025-12-02 21:40:49', '2025-12-02 17:45:12', 1),
(123, 39, 'ANA', '', 'CAMPOS', '', '16515683394', '', '', '', NULL, NULL, 'CARACAS', '2016-02-04', 'Femenino', 'Venezolano', '2025-12-03 01:23:16', '2025-12-02 21:28:25', 1),
(125, 39, 'EUGENIA', 'MARIA', 'RIMAC', 'CABRERA', '35456789', '', '', '', NULL, NULL, 'CARACAS', '2013-03-14', 'Femenino', 'Venezolano', '2025-12-07 22:04:29', '2025-12-07 20:18:01', 1),
(127, 39, 'DAVID', 'EDUARDO', 'RIMAC', 'CABRERA', '37654354', '0414235678', '', 'deduardo@gmail.com', NULL, NULL, 'CARACAS', '2013-06-06', 'Masculino', 'Venezolano', '2025-12-07 22:10:29', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesiones`
--

CREATE TABLE `profesiones` (
  `id_profesion` int(11) NOT NULL,
  `profesion` varchar(25) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `profesiones`
--

INSERT INTO `profesiones` (`id_profesion`, `profesion`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Abogado', '2025-11-13 02:09:21', NULL, 1),
(2, 'Arquitecto', '2025-11-13 02:09:21', NULL, 1),
(3, 'Artista', '2025-11-13 02:09:21', NULL, 1),
(4, 'Biólogo', '2025-11-13 02:09:21', NULL, 1),
(5, 'Chef o cocinero', '2025-11-13 02:09:21', NULL, 1),
(6, 'Dentista', '2025-11-13 02:09:21', NULL, 1),
(7, 'Diseñador gráfico', '2025-11-13 02:09:21', NULL, 1),
(8, 'Doctor o médico', '2025-11-13 02:09:21', NULL, 1),
(9, 'Enfermero', '2025-11-13 02:09:21', NULL, 1),
(10, 'Ingeniero', '2025-11-13 02:09:21', NULL, 1),
(11, 'Juez', '2025-11-13 02:09:21', NULL, 1),
(12, 'Maestro o profesor', '2025-11-13 02:09:21', NULL, 1),
(13, 'Mecánico', '2025-11-13 02:09:21', NULL, 1),
(14, 'Periodista', '2025-11-13 02:09:21', NULL, 1),
(15, 'Psicólogo', '2025-11-13 02:09:21', NULL, 1),
(16, 'Veterinario', '2025-11-13 02:09:21', NULL, 1),
(17, 'Albañil', '2025-11-13 02:09:21', NULL, 1),
(18, 'Carnicero', '2025-11-13 02:09:21', NULL, 1),
(19, 'Carpintero', '2025-11-13 02:09:21', NULL, 1),
(20, 'Cerrajero', '2025-11-13 02:09:21', NULL, 1),
(21, 'Chofer o conductor', '2025-11-13 02:09:21', NULL, 1),
(22, 'Electricista', '2025-11-13 02:09:21', NULL, 1),
(23, 'Peluquero', '2025-11-13 02:09:21', NULL, 1),
(24, 'Pescador', '2025-11-13 02:09:21', NULL, 1),
(25, 'Plomero', '2025-11-13 02:09:21', NULL, 1),
(26, 'Sastre', '2025-11-13 02:09:21', NULL, 1),
(27, 'Ama de casa', '2025-11-13 02:09:21', NULL, 1),
(28, 'Contador', '2025-11-13 02:27:14', NULL, 1),
(29, 'Técnico superior', '2025-11-22 20:46:11', NULL, 1),
(30, 'Bachiller', '2025-11-22 20:46:32', NULL, 1),
(31, 'Administrador', '2025-12-01 21:22:54', NULL, 1),
(32, 'Otra', '2025-12-01 21:22:54', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id_representante` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `ocupacion` varchar(255) DEFAULT NULL,
  `lugar_trabajo` varchar(255) DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1,
  `id_profesion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id_representante`, `id_persona`, `ocupacion`, `lugar_trabajo`, `creacion`, `actualizacion`, `estatus`, `id_profesion`) VALUES
(1, 8, 'Ingeniero Civil', 'Constructora Nacional', '2025-11-10 06:17:16', '2025-11-23 20:27:06', 1, 10),
(2, 9, 'Médico', 'Hospital Central', '2025-11-10 06:17:16', '2025-11-27 01:35:47', 1, 8),
(3, 10, 'Contadora', 'Firma Contable', '2025-11-10 06:17:16', '2025-11-25 20:53:36', 1, 28),
(4, 11, 'DOCENTE', 'UNIVERSIDAD CENTRAL', '2025-11-10 06:17:16', '2025-12-02 01:04:35', 1, 12),
(5, 12, 'Arquitecto', 'Estudio de Arquitectura', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1, 2),
(6, 13, 'Abogada', 'Bufete Legal', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1, 1),
(7, 14, 'Ingeniero de Sistemas', 'Empresa Tecnológica', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1, 10),
(11, 25, 'mecanico', 'Mercedes', '2025-11-11 19:30:17', '2025-11-20 16:05:39', 1, 13),
(12, 28, 'mecanico', 'Mercedes', '2025-11-11 22:16:43', NULL, 1, 13),
(13, 30, 'Ingeniero', 'Bancamiga', '2025-11-11 22:52:57', NULL, 1, 10),
(14, 34, 'K', 'K', '2025-11-20 03:45:22', '2025-11-20 00:15:06', 1, 12),
(15, 37, 'BARBERO', 'SABANA GRANDE', '2025-11-20 04:38:27', '2025-12-01 19:27:35', 1, 28),
(16, 40, 'F', 'F', '2025-11-20 20:09:12', NULL, 1, 1),
(17, 51, 'Consultor', 'ABAE', '2025-11-28 04:37:12', '2025-11-29 23:34:58', 1, 1),
(18, 53, 'AMA DE CASA', '', '2025-11-28 23:32:52', '2025-12-02 17:32:27', 1, 29),
(19, 97, 'AMA DE CASA', '', '2025-11-30 14:45:26', '2025-12-07 20:18:01', 1, 30),
(20, 106, 'ABOGADA', 'TRIBUNAL SUPREMO DE JUSTICIA', '2025-12-01 21:27:51', '2025-12-01 20:04:30', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nom_rol` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nom_rol`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'Administrador', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 'Docente', '2025-11-17 02:52:59', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
  `id_rol_permiso` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_permiso` int(11) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles_permisos`
--

INSERT INTO `roles_permisos` (`id_rol_permiso`, `id_rol`, `id_permiso`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, '2026-01-01 01:14:24', NULL, 1),
(2, 1, 2, '2026-01-01 01:14:24', NULL, 1),
(3, 1, 3, '2026-01-01 01:14:24', NULL, 1),
(4, 1, 4, '2026-01-01 01:14:24', NULL, 1),
(5, 1, 5, '2026-01-01 01:14:24', NULL, 1),
(6, 1, 6, '2026-01-01 01:14:24', NULL, 1),
(7, 1, 7, '2026-01-01 01:14:24', NULL, 1),
(8, 1, 8, '2026-01-01 01:14:24', NULL, 1),
(16, 2, 1, '2026-01-01 01:19:07', NULL, 1),
(17, 2, 4, '2026-01-01 01:19:07', NULL, 1),
(18, 2, 7, '2026-01-01 01:19:07', NULL, 1),
(19, 1, 9, '2026-01-07 01:56:27', NULL, 1),
(20, 1, 10, '2026-01-07 01:56:27', NULL, 1),
(21, 1, 11, '2026-01-07 01:56:27', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
  `id_seccion` int(11) NOT NULL,
  `nom_seccion` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO `secciones` (`id_seccion`, `nom_seccion`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 'A', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 'B', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 'C', '2025-11-26 23:49:17', NULL, 1),
(4, 'D', '2025-11-30 02:54:10', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `contrasena` text NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_persona`, `id_rol`, `usuario`, `contrasena`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 15, 1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 33, 2, 'cmoreno', '7f42dcd0205e6f5d9fdb76a77098eda3b6a637e69f278c0715ea93b48726dab6', '2025-11-17 04:30:35', NULL, 1),
(3, 34, 2, 'rdela', '29aa5d0911f4fb6c3cb3b5f79b6e22f4555e9e71a4d63541ca8a5142c2521ac1', '2025-11-17 04:41:45', '2025-12-02 01:25:19', 1),
(4, 47, 2, '13544321', '9309a090b467e184588cf3611e00e3b78c106239f8b52bcf308b6bd1260a71ea', '2025-11-24 23:38:25', '2025-12-02 01:21:12', 1),
(5, 48, 2, '12467895', '026875080e88f1c7673dc6155b908c997348223178e903bbb7c5ced9dee74115', '2025-11-25 00:44:20', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_parroquia` (`id_parroquia`);

--
-- Indices de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
  ADD PRIMARY KEY (`id_discapacidad`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD KEY `fk_docentes_profesiones` (`id_profesion`);

--
-- Indices de la tabla `docentes_especialidades`
--
ALTER TABLE `docentes_especialidades`
  ADD PRIMARY KEY (`id_docente_especialidad`),
  ADD UNIQUE KEY `uk_docente_especialidad` (`id_docente`,`id_especialidad`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`),
  ADD KEY `idx_estados_nombre` (`nom_estado`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `id_persona` (`id_persona`);

--
-- Indices de la tabla `estudiantes_discapacidades`
--
ALTER TABLE `estudiantes_discapacidades`
  ADD PRIMARY KEY (`id_estudiante_discapacidad`),
  ADD UNIQUE KEY `uk_estudiante_discapacidad` (`id_estudiante`,`id_discapacidad`),
  ADD KEY `id_discapacidad` (`id_discapacidad`);

--
-- Indices de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
  ADD PRIMARY KEY (`id_estudiante_patologia`),
  ADD UNIQUE KEY `uk_estudiante_patologia` (`id_estudiante`,`id_patologia`) USING BTREE,
  ADD KEY `id_patologia` (`id_patologia`) USING BTREE;

--
-- Indices de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
  ADD PRIMARY KEY (`id_estudiante_representante`),
  ADD UNIQUE KEY `uk_estudiante_representante` (`id_estudiante`,`id_representante`),
  ADD KEY `id_representante` (`id_representante`),
  ADD KEY `fk_est_rep_parentesco` (`id_parentesco`);

--
-- Indices de la tabla `globales`
--
ALTER TABLE `globales`
  ADD PRIMARY KEY (`id_globales`),
  ADD KEY `id_periodo` (`id_periodo`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_inscripcion`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_periodo` (`id_periodo`),
  ADD KEY `id_nivel_seccion` (`id_nivel_seccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `idx_municipios_estado` (`id_estado`),
  ADD KEY `idx_municipios_nombre` (`nom_municipio`),
  ADD KEY `idx_municipios_estado_nombre` (`id_estado`,`nom_municipio`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id_nivel`);

--
-- Indices de la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
  ADD PRIMARY KEY (`id_nivel_seccion`),
  ADD KEY `id_nivel` (`id_nivel`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `parentesco`
--
ALTER TABLE `parentesco`
  ADD PRIMARY KEY (`id_parentesco`),
  ADD UNIQUE KEY `parentesco` (`parentesco`);

--
-- Indices de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`),
  ADD KEY `idx_parroquias_municipio` (`id_municipio`),
  ADD KEY `idx_parroquias_nombre` (`nom_parroquia`),
  ADD KEY `idx_parroquias_municipio_nombre` (`id_municipio`,`nom_parroquia`);

--
-- Indices de la tabla `patologias`
--
ALTER TABLE `patologias`
  ADD PRIMARY KEY (`id_patologia`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id_persona`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `id_direccion` (`id_direccion`);

--
-- Indices de la tabla `profesiones`
--
ALTER TABLE `profesiones`
  ADD PRIMARY KEY (`id_profesion`),
  ADD UNIQUE KEY `profesion` (`profesion`);

--
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id_representante`),
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD KEY `fk_representantes_profesiones` (`id_profesion`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `nom_rol` (`nom_rol`);

--
-- Indices de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD PRIMARY KEY (`id_rol_permiso`),
  ADD UNIQUE KEY `uk_rol_permiso` (`id_rol`,`id_permiso`),
  ADD KEY `id_permiso` (`id_permiso`);

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
  ADD UNIQUE KEY `id_persona` (`id_persona`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
  MODIFY `id_discapacidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `docentes_especialidades`
--
ALTER TABLE `docentes_especialidades`
  MODIFY `id_docente_especialidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `estudiantes_discapacidades`
--
ALTER TABLE `estudiantes_discapacidades`
  MODIFY `id_estudiante_discapacidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
  MODIFY `id_estudiante_patologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
  MODIFY `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `globales`
--
ALTER TABLE `globales`
  MODIFY `id_globales` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
  MODIFY `id_nivel_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de la tabla `parentesco`
--
ALTER TABLE `parentesco`
  MODIFY `id_parentesco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1149;

--
-- AUTO_INCREMENT de la tabla `patologias`
--
ALTER TABLE `patologias`
  MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `profesiones`
--
ALTER TABLE `profesiones`
  MODIFY `id_profesion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD CONSTRAINT `fk_direcciones_parroquias` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`);

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `fk_docentes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `fk_docentes_profesiones` FOREIGN KEY (`id_profesion`) REFERENCES `profesiones` (`id_profesion`);

--
-- Filtros para la tabla `docentes_especialidades`
--
ALTER TABLE `docentes_especialidades`
  ADD CONSTRAINT `fk_docentes_especialidades_docentes` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  ADD CONSTRAINT `fk_docentes_especialidades_especialidades` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `fk_estudiantes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

--
-- Filtros para la tabla `estudiantes_discapacidades`
--
ALTER TABLE `estudiantes_discapacidades`
  ADD CONSTRAINT `fk_estudiantes_discapacidades_discapacidades` FOREIGN KEY (`id_discapacidad`) REFERENCES `discapacidades` (`id_discapacidad`),
  ADD CONSTRAINT `fk_estudiantes_discapacidades_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
  ADD CONSTRAINT `fk_estudiantes_patologias_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `fk_estudiantes_patologias_patologias` FOREIGN KEY (`id_patologia`) REFERENCES `patologias` (`id_patologia`);

--
-- Filtros para la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
  ADD CONSTRAINT `fk_est_rep_parentesco` FOREIGN KEY (`id_parentesco`) REFERENCES `parentesco` (`id_parentesco`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_estudiantes_representantes_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `fk_estudiantes_representantes_representantes` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`);

--
-- Filtros para la tabla `globales`
--
ALTER TABLE `globales`
  ADD CONSTRAINT `globales_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `fk_inscripciones_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `fk_inscripciones_niveles_secciones` FOREIGN KEY (`id_nivel_seccion`) REFERENCES `niveles_secciones` (`id_nivel_seccion`),
  ADD CONSTRAINT `fk_inscripciones_periodos` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
  ADD CONSTRAINT `fk_inscripciones_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `fk_municipios_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`);

--
-- Filtros para la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
  ADD CONSTRAINT `fk_niveles_secciones_niveles` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`),
  ADD CONSTRAINT `fk_niveles_secciones_secciones` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

--
-- Filtros para la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD CONSTRAINT `fk_parroquias_municipios` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`);

--
-- Filtros para la tabla `personas`
--
ALTER TABLE `personas`
  ADD CONSTRAINT `fk_personas_direcciones` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`);

--
-- Filtros para la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `fk_representantes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `fk_representantes_profesiones` FOREIGN KEY (`id_profesion`) REFERENCES `profesiones` (`id_profesion`);

--
-- Filtros para la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  ADD CONSTRAINT `fk_roles_permisos_permisos` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`),
  ADD CONSTRAINT `fk_roles_permisos_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
