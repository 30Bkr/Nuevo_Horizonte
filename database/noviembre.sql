-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 03:09:32
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
(1, 1, 'Av Principal de Petare', 'Av Principal', 'Casa 123', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 'Urbanización Caucagüita', 'Calle 2', 'Edificio A, Apt 4B', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 'Sector Baruta', 'Calle Los Samanes', 'Quinta María', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, 'Av Intercomunal El Valle', 'Av Principal', 'Casa 567', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 1, 'Urbanización Los Naranjos', 'Calle 5', 'Casa 89', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 2, 'Sector La Dolorita', 'Calle 7', 'Edificio B, Apt 2C', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 3, 'Urbanización Prados del Este', 'Av Ppal', 'Quinta Los Pinos', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `profesion` varchar(255) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Distrito Capital', '2025-11-10 06:03:55', NULL, 1);

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
(2, 2, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(4, 7, 4, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_representantes`
--

CREATE TABLE `estudiantes_representantes` (
  `id_estudiante_representante` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_representante` int(11) NOT NULL,
  `parentesco` varchar(20) NOT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_representantes`
--

INSERT INTO `estudiantes_representantes` (`id_estudiante_representante`, `id_estudiante`, `id_representante`, `parentesco`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 1, 'Madre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 2, 'Padre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 3, 'Madre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, 4, 'Padre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, 5, 'Padre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, 6, 'Madre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, 7, 'Padre', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(7, 7, 1, 1, 1, '2024-09-04', 'Rinitis alérgica, traer medicamento', '2025-11-10 06:17:18', '2025-11-10 02:17:18', 1);

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
(23, 3, 'Libertador', '2025-11-10 06:04:56', NULL, 1);

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
(2, 2, 'Segundo Grado', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(1, 1, 1, 25, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 1, 2, 25, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 2, 1, 25, '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(88, 23, '23 de enero', '2025-11-10 06:07:08', NULL, 1);

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
(1, 'Año Escolar 2024-2025', '2024-09-01', '2025-07-15', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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

INSERT INTO `personas` (`id_persona`, `id_direccion`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `cedula`, `telefono`, `telefono_hab`, `correo`, `lugar_nac`, `fecha_nac`, `sexo`, `nacionalidad`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 1, 'María', 'Gabriela', 'Pérez', 'González', '28987654', '04141234567', '02127788991', 'maria.perez@email.com', 'Caracas', '2015-03-15', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 2, 'Carlos', 'José', 'Rodríguez', 'López', '29012345', '04149876543', '02128877665', 'carlos.rodriguez@email.com', 'Caracas', '2016-07-22', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 3, 'Ana', 'Isabel', 'García', 'Mendoza', '29123456', '04148765432', '02129988776', 'ana.garcia@email.com', 'Caracas', '2015-11-08', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 4, 'Luis', 'Alberto', 'Martínez', 'Rojas', '29234567', '04147654321', '02126655443', 'luis.martinez@email.com', 'Caracas', '2016-01-30', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 5, 'Valentina', 'Sophia', 'Hernández', 'Silva', '29345678', '04146543210', '02125544332', 'valentina.hernandez@email.com', 'Caracas', '2015-09-14', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 6, 'Diego', 'Alejandro', 'Torres', 'Ramírez', '29456789', '04145432109', '02124433221', 'diego.torres@email.com', 'Caracas', '2016-04-05', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 7, 'Sofía', 'Camila', 'Díaz', 'Fernández', '29567890', '04144321098', '02123322110', 'sofia.diaz@email.com', 'Caracas', '2015-12-18', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(8, 1, 'Carmen', 'Elena', 'González', 'Pérez', '15678901', '04141234568', '02127788992', 'carmen.gonzalez@email.com', 'Caracas', '1980-05-20', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(9, 2, 'José', 'Luis', 'López', 'Rodríguez', '16789012', '04149876544', '02128877666', 'jose.lopez@email.com', 'Caracas', '1978-08-15', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(10, 3, 'Isabel', 'Carmen', 'Mendoza', 'García', '17890123', '04148765433', '02129988777', 'isabel.mendoza@email.com', 'Caracas', '1982-03-10', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(11, 4, 'Alberto', 'José', 'Rojas', 'Martínez', '18901234', '04147654322', '02126655444', 'alberto.rojas@email.com', 'Caracas', '1975-11-25', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(12, 5, 'Roberto', 'Carlos', 'Silva', 'Hernández', '19012345', '04146543211', '02125544333', 'roberto.silva@email.com', 'Caracas', '1979-07-30', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(13, 6, 'Patricia', 'Ana', 'Ramírez', 'Torres', '20123456', '04145432110', '02124433222', 'patricia.ramirez@email.com', 'Caracas', '1981-09-05', 'Femenino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(14, 7, 'Fernando', 'Luis', 'Fernández', 'Díaz', '21234567', '04144321099', '02123322111', 'fernando.fernandez@email.com', 'Caracas', '1977-12-12', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(15, 1, 'Admin', 'Sistema', 'Neudelys', 'School', '12345678', '04140000000', '02120000000', 'admin@neudelys.edu.ve', 'Caracas', '1990-01-01', 'Masculino', 'Venezolana', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `representantes`
--

CREATE TABLE `representantes` (
  `id_representante` int(11) NOT NULL,
  `id_persona` int(11) NOT NULL,
  `profesion` varchar(255) DEFAULT NULL,
  `ocupacion` varchar(255) DEFAULT NULL,
  `lugar_trabajo` varchar(255) DEFAULT NULL,
  `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `actualizacion` varchar(255) DEFAULT NULL,
  `estatus` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO `representantes` (`id_representante`, `id_persona`, `profesion`, `ocupacion`, `lugar_trabajo`, `creacion`, `actualizacion`, `estatus`) VALUES
(1, 8, 'Ingeniero', 'Ingeniero Civil', 'Constructora Nacional', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 9, 'Doctor', 'Médico', 'Hospital Central', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(3, 10, 'Licenciada', 'Contadora', 'Firma Contable', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(4, 11, 'Profesor', 'Docente', 'Universidad Central', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(5, 12, 'Arquitecto', 'Arquitecto', 'Estudio de Arquitectura', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(6, 13, 'Abogada', 'Abogada', 'Bufete Legal', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(7, 14, 'Ingeniero', 'Ingeniero de Sistemas', 'Empresa Tecnológica', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(1, 'Administrador', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(1, 'Sección A', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1),
(2, 'Sección B', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
(1, 15, 1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '2025-11-10 06:17:16', '2025-11-10 02:17:16', 1);

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
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `id_persona` (`id_persona`);

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
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `id_persona` (`id_persona`);

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
  ADD KEY `id_representante` (`id_representante`);

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
  ADD KEY `id_estado` (`id_estado`);

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
-- Indices de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`);

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
-- Indices de la tabla `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id_representante`),
  ADD UNIQUE KEY `id_persona` (`id_persona`);

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
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
  MODIFY `id_estudiante_patologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
  MODIFY `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
  MODIFY `id_nivel_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `patologias`
--
ALTER TABLE `patologias`
  MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
  MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
  MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `fk_docentes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

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
-- Filtros para la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
  ADD CONSTRAINT `fk_estudiantes_patologias_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `fk_estudiantes_patologias_patologias` FOREIGN KEY (`id_patologia`) REFERENCES `patologias` (`id_patologia`);

--
-- Filtros para la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
  ADD CONSTRAINT `fk_estudiantes_representantes_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `fk_estudiantes_representantes_representantes` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`);

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
  ADD CONSTRAINT `fk_representantes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

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
