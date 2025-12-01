-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2025 a las 03:06:40
-- Versión del servidor: 11.7.2-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones`
--

INSERT INTO
    `direcciones` (
        `id_direccion`,
        `id_parroquia`,
        `direccion`,
        `calle`,
        `casa`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        'Av Principal de Petare',
        'Av Principal',
        'Casa 123',
        '2025-11-10 06:17:16',
        '2025-11-25 21:58:53',
        1
    ),
    (
        2,
        2,
        'Urbanización Caucagüita',
        'Calle 2',
        'Edificio A, Apt 4B',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        3,
        'Sector Baruta',
        'Calle Los Samanes',
        'Quinta María',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        4,
        'Av Intercomunal El Valle',
        'Av Principal',
        'Casa 567',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        1,
        'Urbanización Los Naranjos',
        'Calle 5',
        'Casa 89',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        6,
        2,
        'Sector La Dolorita',
        'Calle 7',
        'Edificio B, Apt 2C',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        7,
        3,
        'Urbanización Prados del Este',
        'Av Ppal',
        'Quinta Los Pinos',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        13,
        1,
        'Av Principal de Petare',
        'Av Principal',
        'Casa 123',
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        14,
        77,
        'Quinta Crespo',
        'av sur 4. oeste 16',
        'Res siena',
        '2025-11-11 19:30:17',
        '2025-11-20 16:05:39',
        1
    ),
    (
        15,
        77,
        'Quinta Crespo',
        'av sur 4. oeste 16',
        'Res siena',
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        16,
        1,
        'Av Principal de Petare',
        'Av Principal',
        'Casa 123',
        '2025-11-11 20:02:31',
        NULL,
        1
    ),
    (
        17,
        81,
        'Quinta Crespo',
        'av sur 4. oeste 16',
        'Res siena',
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        18,
        81,
        'Quinta Crespo',
        'av sur 4. oeste 16',
        'Res siena',
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        19,
        78,
        'Quinta Crespo',
        'Av 2',
        'Montalban 3',
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        20,
        78,
        'Quinta Crespo',
        'Av 2',
        'Montalban 3',
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        21,
        1,
        'Por definir',
        NULL,
        NULL,
        '2025-11-17 04:30:35',
        NULL,
        1
    ),
    (
        22,
        1,
        'Por definir',
        NULL,
        NULL,
        '2025-11-17 04:41:45',
        NULL,
        1
    ),
    (
        29,
        1,
        'K',
        'K',
        'K',
        '2025-11-20 03:45:22',
        '2025-11-20 00:15:06',
        1
    ),
    (
        30,
        69,
        'Quinta Crespo',
        'K',
        'Montalban 3',
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        31,
        67,
        'Quinta Crespo',
        'K',
        'Res siena',
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        32,
        64,
        'Quinta Crespo',
        'av sur 4',
        'res siena',
        '2025-11-20 21:54:05',
        NULL,
        1
    ),
    (
        33,
        61,
        'Quinta Crespo',
        'Nueva Granada',
        'Torre B',
        '2025-11-20 22:01:18',
        NULL,
        1
    ),
    (
        34,
        1,
        'Quinta Crespo',
        'av sur 4. oeste 16',
        'casa 16',
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        35,
        58,
        'Quinta Crespo',
        'avenida 4',
        'casa 16',
        '2025-11-24 03:28:17',
        '2025-11-28 01:08:48',
        1
    ),
    (
        36,
        57,
        'Quinta Crespo',
        'Nueva Granada',
        'Torre B',
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        37,
        88,
        'Av sucre',
        'Pabellon',
        '19',
        '2025-11-27 04:11:53',
        '2025-11-27 00:29:52',
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `discapacidades`
--

INSERT INTO
    `discapacidades` (
        `id_discapacidad`,
        `nom_discapacidad`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Discapacidad visual',
        '2025-11-25 10:00:00',
        NULL,
        1
    ),
    (
        2,
        'Discapacidad auditiva',
        '2025-11-25 10:00:00',
        NULL,
        1
    ),
    (
        3,
        'Discapacidad motora',
        '2025-11-25 10:00:00',
        NULL,
        1
    ),
    (
        4,
        'Discapacidad intelectual',
        '2025-11-25 10:00:00',
        NULL,
        1
    ),
    (
        5,
        'Trastorno del espectro autista',
        '2025-11-25 10:00:00',
        NULL,
        1
    ),
    (
        6,
        'Discapacidad múltiple',
        '2025-11-25 10:00:00',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO
    `docentes` (
        `id_docente`,
        `id_persona`,
        `creacion`,
        `actualizacion`,
        `estatus`,
        `id_profesion`
    )
VALUES (
        1,
        1,
        '2025-11-17 02:31:17',
        NULL,
        1,
        12
    ),
    (
        2,
        33,
        '2025-11-17 04:30:35',
        '2025-11-27 18:32:34',
        1,
        12
    ),
    (
        3,
        34,
        '2025-11-17 04:41:45',
        NULL,
        1,
        12
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO
    `estados` (
        `id_estado`,
        `nom_estado`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Miranda',
        '2025-11-10 06:03:55',
        NULL,
        1
    ),
    (
        2,
        'La Guaira',
        '2025-11-10 06:03:55',
        NULL,
        1
    ),
    (
        3,
        'Distrito Capital',
        '2025-11-10 06:03:55',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO
    `estudiantes` (
        `id_estudiante`,
        `id_persona`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        2,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        3,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        4,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        5,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        6,
        6,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        7,
        7,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        11,
        24,
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        12,
        26,
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        13,
        27,
        '2025-11-11 20:02:31',
        NULL,
        1
    ),
    (
        14,
        29,
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        15,
        31,
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        16,
        32,
        '2025-11-13 01:21:00',
        NULL,
        1
    ),
    (
        17,
        35,
        '2025-11-20 03:45:22',
        NULL,
        1
    ),
    (
        18,
        36,
        '2025-11-20 04:15:06',
        NULL,
        1
    ),
    (
        19,
        38,
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        20,
        39,
        '2025-11-20 20:05:39',
        NULL,
        1
    ),
    (
        21,
        41,
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        22,
        42,
        '2025-11-20 21:54:05',
        NULL,
        1
    ),
    (
        23,
        43,
        '2025-11-20 22:01:18',
        NULL,
        1
    ),
    (
        24,
        44,
        '2025-11-22 22:32:30',
        NULL,
        1
    ),
    (
        26,
        46,
        '2025-11-24 00:27:06',
        NULL,
        1
    ),
    (
        27,
        47,
        '2025-11-24 03:19:58',
        NULL,
        1
    ),
    (
        28,
        48,
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        29,
        50,
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        30,
        51,
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        31,
        52,
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        32,
        53,
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        33,
        54,
        '2025-11-24 05:59:02',
        NULL,
        1
    ),
    (
        36,
        57,
        '2025-11-24 15:32:51',
        NULL,
        1
    ),
    (
        37,
        58,
        '2025-11-24 15:34:01',
        NULL,
        1
    ),
    (
        38,
        59,
        '2025-11-26 00:48:30',
        NULL,
        1
    ),
    (
        39,
        60,
        '2025-11-26 01:58:53',
        NULL,
        1
    ),
    (
        40,
        62,
        '2025-11-27 04:11:53',
        NULL,
        1
    ),
    (
        41,
        63,
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        42,
        64,
        '2025-11-28 05:08:48',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_discapacidades`
--

INSERT INTO
    `estudiantes_discapacidades` (
        `id_estudiante_discapacidad`,
        `id_estudiante`,
        `id_discapacidad`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        41,
        4,
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        2,
        42,
        2,
        '2025-11-28 05:08:48',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_patologias`
--

INSERT INTO
    `estudiantes_patologias` (
        `id_estudiante_patologia`,
        `id_estudiante`,
        `id_patologia`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        3,
        2,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        5,
        3,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        7,
        4,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        11,
        4,
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        6,
        12,
        4,
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        7,
        14,
        2,
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        8,
        14,
        3,
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        9,
        15,
        2,
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        10,
        28,
        3,
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        11,
        29,
        2,
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        12,
        30,
        2,
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        13,
        30,
        1,
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        14,
        31,
        3,
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        15,
        31,
        2,
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        16,
        31,
        1,
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        17,
        32,
        4,
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        18,
        32,
        2,
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        21,
        37,
        2,
        '2025-11-24 15:34:01',
        NULL,
        1
    ),
    (
        22,
        39,
        3,
        '2025-11-26 01:58:53',
        NULL,
        1
    ),
    (
        23,
        40,
        1,
        '2025-11-27 04:11:53',
        NULL,
        1
    ),
    (
        24,
        41,
        2,
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        25,
        42,
        3,
        '2025-11-28 05:08:48',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `estudiantes_representantes`
--

INSERT INTO
    `estudiantes_representantes` (
        `id_estudiante_representante`,
        `id_estudiante`,
        `id_representante`,
        `id_parentesco`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        1,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        2,
        2,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        3,
        3,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        4,
        4,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        5,
        5,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        6,
        6,
        6,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        7,
        7,
        7,
        1,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        8,
        11,
        1,
        1,
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        9,
        12,
        11,
        1,
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        10,
        13,
        1,
        1,
        '2025-11-11 20:02:31',
        NULL,
        1
    ),
    (
        11,
        14,
        12,
        1,
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        12,
        15,
        13,
        1,
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        13,
        16,
        7,
        1,
        '2025-11-13 01:21:00',
        NULL,
        1
    ),
    (
        14,
        17,
        14,
        1,
        '2025-11-20 03:45:22',
        NULL,
        1
    ),
    (
        15,
        18,
        14,
        1,
        '2025-11-20 04:15:06',
        NULL,
        1
    ),
    (
        16,
        19,
        15,
        1,
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        17,
        20,
        11,
        1,
        '2025-11-20 20:05:39',
        NULL,
        1
    ),
    (
        18,
        21,
        16,
        1,
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        19,
        22,
        1,
        1,
        '2025-11-20 21:54:05',
        NULL,
        1
    ),
    (
        20,
        23,
        1,
        1,
        '2025-11-20 22:01:18',
        NULL,
        1
    ),
    (
        21,
        24,
        1,
        1,
        '2025-11-22 22:32:30',
        NULL,
        1
    ),
    (
        22,
        26,
        1,
        6,
        '2025-11-24 00:27:06',
        NULL,
        1
    ),
    (
        23,
        27,
        1,
        4,
        '2025-11-24 03:19:58',
        NULL,
        1
    ),
    (
        24,
        28,
        17,
        5,
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        25,
        29,
        18,
        1,
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        26,
        30,
        1,
        1,
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        27,
        31,
        1,
        3,
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        28,
        32,
        1,
        6,
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        29,
        33,
        1,
        4,
        '2025-11-24 05:59:02',
        NULL,
        1
    ),
    (
        32,
        36,
        1,
        3,
        '2025-11-24 15:32:51',
        NULL,
        1
    ),
    (
        33,
        37,
        1,
        2,
        '2025-11-24 15:34:01',
        NULL,
        1
    ),
    (
        34,
        38,
        1,
        3,
        '2025-11-26 00:48:30',
        NULL,
        1
    ),
    (
        35,
        39,
        1,
        6,
        '2025-11-26 01:58:53',
        NULL,
        1
    ),
    (
        36,
        40,
        19,
        2,
        '2025-11-27 04:11:53',
        NULL,
        1
    ),
    (
        37,
        41,
        19,
        4,
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        38,
        42,
        18,
        3,
        '2025-11-28 05:08:48',
        NULL,
        1
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `globales`
--

CREATE TABLE `globales` (
    `id_globales` int(11) NOT NULL,
    `edad_min` int(11) NOT NULL,
    `edad_max` int(11) NOT NULL,
    `nom_instituto` varchar(50) NOT NULL,
    `id_periodo` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;

--
-- Volcado de datos para la tabla `globales`
--

INSERT INTO
    `globales` (
        `id_globales`,
        `edad_min`,
        `edad_max`,
        `nom_instituto`,
        `id_periodo`
    )
VALUES (
        1,
        4,
        18,
        'Nuevo Horizonte',
        2
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO
    `inscripciones` (
        `id_inscripcion`,
        `id_estudiante`,
        `id_periodo`,
        `id_nivel_seccion`,
        `id_usuario`,
        `fecha_inscripcion`,
        `observaciones`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        1,
        1,
        1,
        '2024-09-01',
        'Estudiante nueva, con asma controlada',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        2,
        2,
        1,
        1,
        1,
        '2024-09-01',
        'Estudiante regular',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        3,
        3,
        1,
        1,
        1,
        '2024-09-02',
        'Alergia a lácteos, traer lunch especial',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        4,
        4,
        1,
        2,
        1,
        '2024-09-02',
        'Estudiante regular',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        5,
        5,
        1,
        2,
        1,
        '2024-09-03',
        'Alergia al polen, evitar áreas con flores',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        6,
        6,
        1,
        2,
        1,
        '2024-09-03',
        'Estudiante regular',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        7,
        7,
        1,
        1,
        1,
        '2024-09-04',
        'Rinitis alérgica, traer medicamento',
        '2025-11-10 06:17:18',
        '2025-11-10 02:17:18',
        1
    ),
    (
        8,
        11,
        1,
        3,
        1,
        '2025-11-11',
        '',
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        9,
        12,
        1,
        1,
        1,
        '2025-11-11',
        '',
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        10,
        13,
        1,
        1,
        1,
        '2025-11-11',
        '',
        '2025-11-11 20:02:31',
        NULL,
        1
    ),
    (
        11,
        14,
        1,
        2,
        1,
        '2025-11-11',
        '',
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        12,
        15,
        1,
        3,
        1,
        '2025-11-11',
        '',
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        13,
        16,
        1,
        1,
        1,
        '2025-11-13',
        '',
        '2025-11-13 01:21:00',
        NULL,
        1
    ),
    (
        14,
        17,
        1,
        1,
        1,
        '2025-11-20',
        '',
        '2025-11-20 03:45:22',
        NULL,
        1
    ),
    (
        15,
        18,
        1,
        2,
        1,
        '2025-11-20',
        '',
        '2025-11-20 04:15:06',
        NULL,
        1
    ),
    (
        16,
        19,
        1,
        1,
        1,
        '2025-11-20',
        '',
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        17,
        11,
        2,
        2,
        1,
        '2025-11-20',
        '',
        '2025-11-20 05:19:15',
        NULL,
        1
    ),
    (
        18,
        1,
        2,
        2,
        1,
        '2025-11-20',
        '',
        '2025-11-20 05:27:22',
        NULL,
        1
    ),
    (
        19,
        13,
        2,
        1,
        1,
        '2025-11-20',
        '',
        '2025-11-20 05:28:32',
        NULL,
        1
    ),
    (
        20,
        20,
        1,
        1,
        1,
        '2025-11-20',
        '',
        '2025-11-20 20:05:39',
        NULL,
        1
    ),
    (
        21,
        21,
        1,
        2,
        1,
        '2025-11-20',
        '',
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        22,
        14,
        2,
        3,
        1,
        '2025-11-20',
        '',
        '2025-11-20 21:37:57',
        NULL,
        1
    ),
    (
        23,
        22,
        1,
        2,
        1,
        '2025-11-20',
        '',
        '2025-11-20 21:54:05',
        NULL,
        1
    ),
    (
        24,
        23,
        1,
        1,
        1,
        '2025-11-20',
        '',
        '2025-11-20 22:01:18',
        NULL,
        1
    ),
    (
        25,
        24,
        1,
        2,
        1,
        '2025-11-22',
        '',
        '2025-11-22 22:32:30',
        NULL,
        1
    ),
    (
        26,
        26,
        1,
        1,
        1,
        '2025-11-24',
        '',
        '2025-11-24 00:27:06',
        NULL,
        1
    ),
    (
        27,
        27,
        1,
        1,
        1,
        '2025-11-24',
        '',
        '2025-11-24 03:19:58',
        NULL,
        1
    ),
    (
        28,
        28,
        1,
        2,
        1,
        '2025-11-24',
        '',
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        29,
        29,
        1,
        1,
        1,
        '2025-11-24',
        '',
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        30,
        30,
        1,
        2,
        1,
        '2025-11-24',
        '',
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        31,
        31,
        1,
        2,
        1,
        '2025-11-24',
        '',
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        32,
        32,
        2,
        2,
        1,
        '2025-11-24',
        '',
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        33,
        33,
        1,
        2,
        1,
        '2025-11-24',
        '',
        '2025-11-24 05:59:02',
        NULL,
        1
    ),
    (
        34,
        36,
        1,
        1,
        1,
        '2025-11-24',
        '',
        '2025-11-24 15:32:51',
        NULL,
        1
    ),
    (
        35,
        37,
        1,
        1,
        1,
        '2025-11-24',
        '',
        '2025-11-24 15:34:01',
        NULL,
        1
    ),
    (
        36,
        32,
        1,
        3,
        1,
        '2025-11-25',
        '',
        '2025-11-25 04:20:30',
        NULL,
        1
    ),
    (
        37,
        38,
        2,
        1,
        1,
        '2025-11-26',
        '',
        '2025-11-26 00:48:30',
        NULL,
        1
    ),
    (
        38,
        39,
        1,
        1,
        1,
        '2025-11-26',
        '',
        '2025-11-26 01:58:53',
        NULL,
        1
    ),
    (
        39,
        39,
        6,
        1,
        1,
        '2025-11-25',
        '',
        '2025-11-26 02:07:28',
        NULL,
        1
    ),
    (
        40,
        39,
        6,
        1,
        1,
        '2025-11-25',
        '',
        '2025-11-26 02:07:28',
        NULL,
        1
    ),
    (
        41,
        13,
        6,
        1,
        1,
        '2025-11-25',
        '',
        '2025-11-26 02:11:33',
        NULL,
        1
    ),
    (
        42,
        13,
        6,
        1,
        1,
        '2025-11-25',
        '',
        '2025-11-26 02:11:33',
        NULL,
        1
    ),
    (
        43,
        40,
        1,
        1,
        1,
        '2025-11-27',
        '',
        '2025-11-27 04:11:53',
        NULL,
        1
    ),
    (
        44,
        41,
        1,
        2,
        1,
        '2025-11-27',
        '',
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        45,
        42,
        1,
        3,
        1,
        '2025-11-28',
        '',
        '2025-11-28 05:08:48',
        NULL,
        1
    ),
    (
        46,
        42,
        2,
        1,
        1,
        '2025-11-28',
        '',
        '2025-11-29 01:52:32',
        NULL,
        1
    ),
    (
        47,
        29,
        2,
        3,
        1,
        '2025-11-28',
        '',
        '2025-11-29 02:02:13',
        NULL,
        1
    ),
    (
        48,
        29,
        2,
        3,
        1,
        '2025-11-28',
        '',
        '2025-11-29 02:02:13',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO
    `municipios` (
        `id_municipio`,
        `id_estado`,
        `nom_municipio`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        'Acevedo',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        2,
        1,
        'Andrés Bello',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        3,
        1,
        'Baruta',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        4,
        1,
        'Brión',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        5,
        1,
        'Buroz',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        6,
        1,
        'Carrizal',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        7,
        1,
        'Chacao',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        8,
        1,
        'Cristóbal Rojas',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        9,
        1,
        'El Hatillo',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        10,
        1,
        'Guaicaipuro',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        11,
        1,
        'Independencia',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        12,
        1,
        'Lander',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        13,
        1,
        'Los Salias',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        14,
        1,
        'Páez',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        15,
        1,
        'Paz Castillo',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        16,
        1,
        'Pedro Gual',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        17,
        1,
        'Plaza',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        18,
        1,
        'Simón Bolívar',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        19,
        1,
        'Sucre',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        20,
        1,
        'Urdaneta',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        21,
        1,
        'Zamora',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        22,
        2,
        'Vargas',
        '2025-11-10 06:04:56',
        NULL,
        1
    ),
    (
        23,
        3,
        'Libertador',
        '2025-11-10 06:04:56',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO
    `niveles` (
        `id_nivel`,
        `num_nivel`,
        `nom_nivel`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        'Primer Grado',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        2,
        'Segundo Grado',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        3,
        'Tercer Grado',
        '2025-11-27 03:27:28',
        NULL,
        1
    ),
    (
        4,
        4,
        'Cuarto Año',
        '2025-11-28 03:58:23',
        NULL,
        1
    ),
    (
        5,
        6,
        'Sexto Grado',
        '2025-11-28 03:59:13',
        NULL,
        1
    ),
    (
        6,
        1,
        'Primer Año',
        '2025-11-28 04:28:31',
        NULL,
        1
    ),
    (
        7,
        5,
        'Quinto Año',
        '2025-11-28 04:28:53',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `niveles_secciones`
--

INSERT INTO
    `niveles_secciones` (
        `id_nivel_seccion`,
        `id_nivel`,
        `id_seccion`,
        `capacidad`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        1,
        18,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        1,
        2,
        25,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        2,
        1,
        25,
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        5,
        4,
        17,
        '2025-11-28 03:59:26',
        NULL,
        1
    ),
    (
        5,
        4,
        3,
        37,
        '2025-11-28 03:59:41',
        NULL,
        1
    ),
    (
        6,
        6,
        3,
        30,
        '2025-11-28 04:28:42',
        NULL,
        1
    ),
    (
        7,
        7,
        4,
        30,
        '2025-11-28 04:29:05',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `parentesco`
--

INSERT INTO
    `parentesco` (
        `id_parentesco`,
        `parentesco`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Padre',
        '2025-11-23 23:38:42',
        NULL,
        1
    ),
    (
        2,
        'Madre',
        '2025-11-23 23:38:50',
        NULL,
        1
    ),
    (
        3,
        'Abuelo',
        '2025-11-23 23:38:59',
        NULL,
        1
    ),
    (
        4,
        'Abuela',
        '2025-11-23 23:39:06',
        NULL,
        1
    ),
    (
        5,
        'Tío',
        '2025-11-23 23:39:17',
        NULL,
        1
    ),
    (
        6,
        'Tía',
        '2025-11-23 23:39:24',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `parroquias`
--

INSERT INTO
    `parroquias` (
        `id_parroquia`,
        `id_municipio`,
        `nom_parroquia`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        'Aragüita',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        2,
        1,
        'Arévalo González',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        3,
        1,
        'Capaya',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        4,
        1,
        'Caucagua',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        5,
        1,
        'Panaquire',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        6,
        1,
        'Ribas',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        7,
        1,
        'El Café',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        8,
        1,
        'Marizapa',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        9,
        2,
        'Cumbo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        10,
        2,
        'San José de Barlovento',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        11,
        3,
        'El Cafetal',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        12,
        3,
        'Las Minas',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        13,
        3,
        'Nuestra Señora del Rosario',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        14,
        4,
        'Higuerote',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        15,
        4,
        'Curiepe',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        16,
        4,
        'Tacarigua de Brión',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        17,
        5,
        'Mamporal',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        18,
        6,
        'Carrizal',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        19,
        7,
        'Chacao',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        20,
        8,
        'Charallave',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        21,
        8,
        'Las Brisas',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        22,
        9,
        'El Hatillo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        23,
        10,
        'Altagracia de la Montaña',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        24,
        10,
        'Cecilio Acosta',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        25,
        10,
        'Los Teques',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        26,
        10,
        'El Jarillo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        27,
        10,
        'San Pedro',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        28,
        10,
        'Tácata',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        29,
        10,
        'Paracotos',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        30,
        11,
        'Cartanal',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        31,
        11,
        'Santa Teresa del Tuy',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        32,
        12,
        'La Democracia',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        33,
        12,
        'Ocumare del Tuy',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        34,
        12,
        'Santa Bárbara',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        35,
        13,
        'San Antonio de los Altos',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        36,
        14,
        'Río Chico',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        37,
        14,
        'El Guapo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        38,
        14,
        'Tacarigua de la Laguna',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        39,
        14,
        'Paparo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        40,
        14,
        'San Fernando del Guapo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        41,
        15,
        'Santa Lucía del Tuy',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        42,
        16,
        'Cúpira',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        43,
        16,
        'Machurucuto',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        44,
        17,
        'Guarenas',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        45,
        18,
        'San Antonio de Yare',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        46,
        18,
        'San Francisco de Yare',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        47,
        19,
        'Leoncio Martínez',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        48,
        19,
        'Petare',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        49,
        19,
        'Caucagüita',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        50,
        19,
        'Filas de Mariche',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        51,
        19,
        'La Dolorita',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        52,
        20,
        'Cúa',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        53,
        20,
        'Nueva Cúa',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        54,
        21,
        'Guatire',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        55,
        21,
        'Bolívar',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        56,
        22,
        'Caraballeda',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        57,
        22,
        'Carayaca',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        58,
        22,
        'Carlos Soublette',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        59,
        22,
        'Caruao Chuspa',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        60,
        22,
        'Catia La Mar',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        61,
        22,
        'El Junko',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        62,
        22,
        'La Guaira',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        63,
        22,
        'Macuto',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        64,
        22,
        'Maiquetía',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        65,
        22,
        'Naiguatá',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        66,
        22,
        'Urimare',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        67,
        23,
        'Altagracia',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        68,
        23,
        'Antímano',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        69,
        23,
        'Caricuao',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        70,
        23,
        'Catedral',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        71,
        23,
        'Coche',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        72,
        23,
        'El Junquito',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        73,
        23,
        'El Paraíso',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        74,
        23,
        'El Recreo',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        75,
        23,
        'El Valle',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        76,
        23,
        'La Candelaria',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        77,
        23,
        'La Pastora',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        78,
        23,
        'La Vega',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        79,
        23,
        'Macarao',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        80,
        23,
        'San Agustín',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        81,
        23,
        'San Bernardino',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        82,
        23,
        'San José',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        83,
        23,
        'San Juan',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        84,
        23,
        'San Pedro',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        85,
        23,
        'Santa Rosalía',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        86,
        23,
        'Santa Teresa',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        87,
        23,
        'Sucre (Catia)',
        '2025-11-10 06:07:08',
        NULL,
        1
    ),
    (
        88,
        23,
        '23 de enero',
        '2025-11-10 06:07:08',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `patologias`
--

INSERT INTO
    `patologias` (
        `id_patologia`,
        `nom_patologia`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Asma',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        'Alergia a lácteos',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        'Alergia al polen',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        'Rinitis alérgica',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        'Esquizofrenia',
        '2025-11-25 20:20:00',
        '2025-11-26 23:17:43',
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO
    `periodos` (
        `id_periodo`,
        `descripcion_periodo`,
        `fecha_ini`,
        `fecha_fin`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Año Escolar 2024-2025',
        '2024-09-01',
        '2025-07-15',
        '2025-11-10 06:17:16',
        '2025-11-26 19:18:43',
        0
    ),
    (
        2,
        'Año Escolar 2023-2024',
        '2023-09-01',
        '2024-07-15',
        '2025-11-20 05:11:53',
        '2025-11-28 21:48:50',
        1
    ),
    (
        6,
        'Año Escolar 2025-2026',
        '2025-09-15',
        '2026-07-15',
        '2025-11-26 02:02:37',
        '2025-11-25 22:03:33',
        0
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `personas`
--

INSERT INTO
    `personas` (
        `id_persona`,
        `id_direccion`,
        `primer_nombre`,
        `segundo_nombre`,
        `primer_apellido`,
        `segundo_apellido`,
        `cedula`,
        `telefono`,
        `telefono_hab`,
        `correo`,
        `lugar_nac`,
        `fecha_nac`,
        `sexo`,
        `nacionalidad`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        1,
        'María',
        'Gabriela',
        'Pérez',
        'González',
        '28987654',
        '04141234567',
        '02127788991',
        'maria.perez@email.com',
        'Caracas',
        '2015-03-15',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        2,
        'Carlos',
        'José',
        'Rodríguez',
        'López',
        '29012345',
        '04149876543',
        '02128877665',
        'carlos.rodriguez@email.com',
        'Caracas',
        '2016-07-22',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        3,
        'Ana',
        'Isabel',
        'García',
        'Mendoza',
        '29123456',
        '04148765432',
        '02129988776',
        'ana.garcia@email.com',
        'Caracas',
        '2015-11-08',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        4,
        4,
        'Luis',
        'Alberto',
        'Martínez',
        'Rojas',
        '29234567',
        '04147654321',
        '02126655443',
        'luis.martinez@email.com',
        'Caracas',
        '2016-01-30',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        5,
        5,
        'Valentina',
        'Sophia',
        'Hernández',
        'Silva',
        '29345678',
        '04146543210',
        '02125544332',
        'valentina.hernandez@email.com',
        'Caracas',
        '2015-09-14',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        6,
        6,
        'Diego',
        'Alejandro',
        'Torres',
        'Ramírez',
        '29456789',
        '04145432109',
        '02124433221',
        'diego.torres@email.com',
        'Caracas',
        '2016-04-05',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        7,
        7,
        'Sofía',
        'Camila',
        'Díaz',
        'Fernández',
        '29567890',
        '04144321098',
        '02123322110',
        'sofia.diaz@email.com',
        'Caracas',
        '2015-12-18',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        8,
        1,
        'Carmen',
        'Elena',
        'González',
        'Pérez',
        '15678901',
        '04141234568',
        '02127788992',
        'carmen.gonzalez@email.com',
        'Caracas',
        '1980-05-20',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-25 21:58:53',
        1
    ),
    (
        9,
        2,
        'José',
        'Luis',
        'López',
        'Rodríguez',
        '16789012',
        '04149876544',
        '02128877666',
        'jose.lopez@email.com',
        'Caracas',
        '1978-08-15',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        10,
        3,
        'Isabel',
        'Carmen',
        'Mendoza',
        'García',
        '17890123',
        '04148765433',
        '02129988777',
        'isabel.mendoza@email.com',
        'Caracas',
        '1982-03-10',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        11,
        4,
        'Alberto',
        'José',
        'Rojas',
        'Martínez',
        '18901234',
        '04147654322',
        '02126655444',
        'alberto.rojas@email.com',
        'Caracas',
        '1975-11-25',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        12,
        5,
        'Roberto',
        'Carlos',
        'Silva',
        'Hernández',
        '19012345',
        '04146543211',
        '02125544333',
        'roberto.silva@email.com',
        'Caracas',
        '1979-07-30',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        13,
        6,
        'Patricia',
        'Ana',
        'Ramírez',
        'Torres',
        '20123456',
        '04145432110',
        '02124433222',
        'patricia.ramirez@email.com',
        'Caracas',
        '1981-09-05',
        'Femenino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        14,
        7,
        'Fernando',
        'Luis',
        'Fernández',
        'Díaz',
        '21234567',
        '04144321099',
        '02123322111',
        'fernando.fernandez@email.com',
        'Caracas',
        '1977-12-12',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        15,
        1,
        'Admin',
        'Sistema',
        'Neudelys',
        'School',
        '12345678',
        '04140000000',
        '02120000000',
        'admin@neudelys.edu.ve',
        'Caracas',
        '1990-01-01',
        'Masculino',
        'Venezolano',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        24,
        13,
        'Briant',
        'briant',
        'Sanchez',
        'carrillo',
        '12344321',
        '04149105229',
        '02127788992',
        'briant@gmail.com',
        'El paraiso',
        '2025-10-28',
        'Masculino',
        'Venezolano',
        '2025-11-11 18:59:50',
        NULL,
        1
    ),
    (
        25,
        14,
        'Briant',
        'Alessandro',
        'Carrillo',
        'Sanchez',
        '27318765',
        '04149105229',
        '04149105229',
        'Briant1@gmail.com',
        'Caracas',
        '2025-11-04',
        'Masculino',
        'Venezolano',
        '2025-11-11 19:30:17',
        '2025-11-20 16:05:39',
        1
    ),
    (
        26,
        15,
        'Hugo',
        'massimo',
        'Carrillo',
        'Mendez',
        '32846139',
        '04149015229',
        '04149105229',
        'hugo@gmail.com',
        'El paraiso',
        '2025-10-26',
        'Masculino',
        'Venezolano',
        '2025-11-11 19:30:17',
        NULL,
        1
    ),
    (
        27,
        16,
        'Aharon',
        'Orlando',
        'Stojs',
        'Shein',
        '123456783',
        '04149015229',
        '02127788992',
        'aharon@gmail.com',
        'El paraiso',
        '1879-12-30',
        'Femenino',
        'Venezolano',
        '2025-11-11 20:02:31',
        NULL,
        1
    ),
    (
        28,
        17,
        'Maria',
        'Alicia',
        'Sanchez',
        'Devia',
        '9344828',
        '04149105229',
        '04149105229',
        'alicias@gmail.com',
        'El paraiso',
        '1998-11-30',
        'Femenino',
        'Venezolano',
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        29,
        18,
        'Pablo',
        'Hugo',
        'Chacon',
        'Mejias',
        '76544567',
        '04149105229',
        '04149105229',
        'pablo@gmail.com',
        'El paraiso',
        '2025-10-28',
        'Masculino',
        'Venezolano',
        '2025-11-11 22:16:43',
        NULL,
        1
    ),
    (
        30,
        19,
        'Meyly',
        'J',
        'Pinto',
        'G',
        '657483762',
        '04125079067',
        '04125079067',
        'meylypinto@gmail.com',
        'Caracas',
        '1971-12-14',
        'Femenino',
        'Venezolano',
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        31,
        20,
        'Jose',
        'Carlos',
        'Andrades',
        'Pinto',
        '34562738',
        '04149105229',
        '04125079067',
        'Jose@gmail.com',
        'El paraiso',
        '2025-01-11',
        'Masculino',
        'Venezolano',
        '2025-11-11 22:52:57',
        NULL,
        1
    ),
    (
        32,
        7,
        'Luny',
        'k',
        'Lopez',
        'm',
        '57481293',
        '04149105229',
        '02123322111',
        'luny@gmail.com',
        'El paraiso',
        '1199-11-30',
        'Masculino',
        'Venezolano',
        '2025-11-13 01:21:00',
        NULL,
        1
    ),
    (
        33,
        34,
        'Carlos',
        'Enrique',
        'Moreno',
        'Salazar',
        '27654322',
        '0416789542',
        '04149105229',
        'cmoreno@gmail.com',
        'Caracas',
        '1990-01-01',
        'Masculino',
        'Venezolano',
        '2025-11-17 04:30:35',
        '2025-11-23 23:22:17',
        1
    ),
    (
        34,
        29,
        'Roberto',
        'Andres',
        'Dela',
        'Salazar',
        '12333666',
        '000000000',
        '000000000',
        'rdela@gmail.com',
        'Caracas',
        '1990-01-01',
        'Masculino',
        'Venezolano',
        '2025-11-17 04:41:45',
        '2025-11-20 00:15:06',
        1
    ),
    (
        35,
        29,
        'K',
        'K',
        'K',
        'K',
        '20112333666',
        '04149105229',
        '000000000',
        'K@gmail.com',
        'El paraiso',
        '2020-12-22',
        'Masculino',
        'Venezolano',
        '2025-11-20 03:45:22',
        NULL,
        1
    ),
    (
        36,
        29,
        'S',
        's',
        's',
        's',
        '20212333666',
        '04149105229',
        '000000000',
        'S@gmail.com',
        'El paraiso',
        '2020-12-09',
        'Masculino',
        'Venezolano',
        '2025-11-20 04:15:06',
        NULL,
        1
    ),
    (
        37,
        30,
        'a',
        'a',
        'a',
        'a',
        '123151',
        '04149105229',
        '04149105229',
        'a@mgil.com',
        'Caracas',
        '2025-11-05',
        'Masculino',
        'Venezolano',
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        38,
        30,
        'A',
        'a',
        'a',
        'm',
        '201123151',
        '04149015229',
        '04149105229',
        'ak@gmial.com',
        'El paraisoe',
        '2020-12-02',
        'Femenino',
        'Venezolano',
        '2025-11-20 04:38:27',
        NULL,
        1
    ),
    (
        39,
        14,
        'Nn',
        'N',
        'N',
        'N',
        '20227318765',
        '04149105229',
        '04149105229',
        'N@GMAIL.COM',
        'El paraisoe',
        '2020-12-14',
        'Masculino',
        'Venezolano',
        '2025-11-20 20:05:39',
        NULL,
        1
    ),
    (
        40,
        31,
        'f',
        'F',
        'F',
        'F',
        '15521097',
        '04125079067',
        '04125079067',
        'F@GMAIL.COM',
        'Caracas',
        '2025-12-31',
        'Masculino',
        'Venezolano',
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        41,
        31,
        'g',
        'G',
        'G',
        'G',
        '20115521097',
        '04149105229',
        '04125079067',
        'GH@GMAIL.COM',
        'G',
        '2020-12-31',
        'Masculino',
        'Venezolano',
        '2025-11-20 20:09:12',
        NULL,
        1
    ),
    (
        42,
        32,
        'Z',
        'Z',
        'Z',
        'Z',
        '20415678901',
        '04149105229',
        '02127788992',
        'J@gmail.com',
        'El paraisoe',
        '2020-12-17',
        'Femenino',
        'Venezolano',
        '2025-11-20 21:54:05',
        NULL,
        1
    ),
    (
        43,
        33,
        'T',
        'briant',
        'T',
        'carrillo',
        '20515678901',
        '0412785942',
        '02127788992',
        't@GMAIL.COM',
        'El paraiso',
        '2020-12-16',
        'Masculino',
        'Venezolano',
        '2025-11-20 22:01:18',
        NULL,
        1
    ),
    (
        44,
        1,
        'elo',
        'eli',
        'as',
        'ta',
        '20615678901',
        '04149015229',
        '02127788992',
        'elo@gmail.com',
        'El paraisoe',
        '2020-12-09',
        'Masculino',
        'Venezolano',
        '2025-11-22 22:32:30',
        NULL,
        1
    ),
    (
        46,
        1,
        'z',
        'z',
        'z',
        'z',
        '20715678901',
        '04149105229',
        '02127788992',
        'z@gmail.com',
        'El paraiso',
        '2020-12-31',
        'Masculino',
        'Venezolano',
        '2025-11-24 00:27:06',
        NULL,
        1
    ),
    (
        47,
        1,
        'He',
        'Hi',
        'Ho',
        'Hu',
        '20815678901',
        '04149015229',
        '02127788992',
        'he@gmail.com',
        'El paraiso',
        '2020-12-14',
        'Masculino',
        'Venezolano',
        '2025-11-24 03:19:58',
        NULL,
        1
    ),
    (
        48,
        34,
        'Eje',
        'eji',
        'ojo',
        'uju',
        '20127654322',
        '04149015229',
        '04149105229',
        'eje@gmail.com',
        'El paraisoe',
        '2020-12-17',
        'Femenino',
        'Venezolano',
        '2025-11-24 03:22:17',
        NULL,
        1
    ),
    (
        49,
        35,
        'ti',
        'to',
        'tu',
        'te',
        '1234',
        '04149105229',
        '04125079067',
        'ti@gmail.com',
        'Caracas',
        '1993-12-30',
        'Masculino',
        'Venezolano',
        '2025-11-24 03:28:17',
        '2025-11-28 01:08:48',
        1
    ),
    (
        50,
        36,
        'ak',
        'ek',
        'ik',
        'ok',
        '1911234',
        '04149015229',
        '04125079067',
        'ak@gmail.com',
        'el',
        '2019-11-30',
        'Masculino',
        'Extranjero',
        '2025-11-24 03:28:17',
        NULL,
        1
    ),
    (
        51,
        1,
        'jey',
        'joy',
        'kim',
        'ba',
        '09915678901',
        '04149015229',
        '02127788992',
        'jey@gmail.com',
        'El paraisoe',
        '2009-12-31',
        'Masculino',
        'Venezolano',
        '2025-11-24 05:45:14',
        NULL,
        1
    ),
    (
        52,
        1,
        'Mo',
        'Ma',
        'Me',
        'Mi',
        '141015678901',
        '04149015229',
        '02127788992',
        'mo@gmail.com',
        'El paraisoe',
        '2014-11-30',
        'Masculino',
        'Venezolano',
        '2025-11-24 05:56:39',
        NULL,
        1
    ),
    (
        53,
        1,
        'La',
        'Le',
        'Li',
        'Lo',
        '201115678901',
        '04149105229',
        '02127788992',
        'briant@gmail.com',
        'El paraiso',
        '2020-12-07',
        'Masculino',
        'Venezolano',
        '2025-11-24 05:58:00',
        NULL,
        1
    ),
    (
        54,
        1,
        'Yu',
        'Ye',
        'Yi',
        'Yo',
        '071215678901',
        '04149105229',
        '02127788992',
        'yu@gmail.com',
        'El paraisoe',
        '2007-12-31',
        'Masculino',
        'Extranjero',
        '2025-11-24 05:59:02',
        NULL,
        1
    ),
    (
        57,
        1,
        'IO',
        'IO',
        'Io',
        'Io',
        '201315678901',
        '04149105229',
        '02127788992',
        'io@gmail.com',
        'El paraiso',
        '2020-12-30',
        'Masculino',
        'Venezolano',
        '2025-11-24 15:32:51',
        NULL,
        1
    ),
    (
        58,
        1,
        'Uo',
        'uo',
        'uo',
        'uo',
        '181415678901',
        '04149015229',
        '02127788992',
        'uo@gmail.com',
        'El paraisoe',
        '2018-11-30',
        'Masculino',
        'Extranjero',
        '2025-11-24 15:34:01',
        NULL,
        1
    ),
    (
        59,
        1,
        'gas',
        'ge',
        'ge',
        'ge',
        '201515678901',
        '04149015229',
        '02127788992',
        'gea@gmail.com',
        'El paraisoe',
        '2020-11-30',
        'Femenino',
        'Venezolano',
        '2025-11-26 00:48:30',
        NULL,
        1
    ),
    (
        60,
        1,
        'BEM',
        'BEM',
        'MAN',
        'MAN',
        '211615678901',
        '04149105229',
        '02127788992',
        'BEM@gmail.com',
        'El paraiso',
        '2021-12-01',
        'Femenino',
        'Venezolano',
        '2025-11-26 01:58:53',
        NULL,
        1
    ),
    (
        61,
        37,
        'Yaneivi',
        'Dayana',
        'Carrillo',
        'Sanchez',
        '20123432',
        '04141234567',
        '02121234567',
        'yaneivi@gmail.com',
        'Tachira',
        '1992-01-27',
        'Femenino',
        'Venezolano',
        '2025-11-27 04:11:53',
        '2025-11-27 00:29:52',
        1
    ),
    (
        62,
        37,
        'Maximo',
        'Garcia',
        'Carrillo',
        'Sanchez',
        '18120123432',
        '',
        '02121234567',
        '',
        'Loira',
        '2018-10-17',
        'Masculino',
        'Venezolano',
        '2025-11-27 04:11:53',
        NULL,
        1
    ),
    (
        63,
        37,
        'VI',
        'Ve',
        'Va',
        'Vo',
        '21220123432',
        '',
        '02121234567',
        '',
        'V',
        '2021-12-30',
        'Masculino',
        'Venezolano',
        '2025-11-27 04:29:52',
        NULL,
        1
    ),
    (
        64,
        35,
        'Chin',
        'Chan',
        'pu',
        'po',
        '1421234',
        '',
        '04125079067',
        '',
        'El paraisoe',
        '2014-02-19',
        'Masculino',
        'Venezolano',
        '2025-11-28 05:08:48',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `profesiones`
--

INSERT INTO
    `profesiones` (
        `id_profesion`,
        `profesion`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Abogado',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        2,
        'Arquitecto',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        3,
        'Artista',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        4,
        'Biólogo',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        5,
        'Chef o cocinero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        6,
        'Dentista',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        7,
        'Diseñador gráfico',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        8,
        'Doctor o médico',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        9,
        'Enfermero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        10,
        'Ingeniero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        11,
        'Juez',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        12,
        'Maestro o profesor',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        13,
        'Mecánico',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        14,
        'Periodista',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        15,
        'Psicólogo',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        16,
        'Veterinario',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        17,
        'Albañil',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        18,
        'Carnicero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        19,
        'Carpintero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        20,
        'Cerrajero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        21,
        'Chofer o conductor',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        22,
        'Electricista',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        23,
        'Peluquero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        24,
        'Pescador',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        25,
        'Plomero',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        26,
        'Sastre',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        27,
        'Ama de casa',
        '2025-11-13 02:09:21',
        NULL,
        1
    ),
    (
        28,
        'Contador',
        '2025-11-13 02:27:14',
        NULL,
        1
    ),
    (
        29,
        'Técnico superior',
        '2025-11-22 20:46:11',
        NULL,
        1
    ),
    (
        30,
        'Bachiller',
        '2025-11-22 20:46:32',
        '2025-11-25 16:25:47',
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `representantes`
--

INSERT INTO
    `representantes` (
        `id_representante`,
        `id_persona`,
        `ocupacion`,
        `lugar_trabajo`,
        `creacion`,
        `actualizacion`,
        `estatus`,
        `id_profesion`
    )
VALUES (
        1,
        8,
        'Ingeniero Civil',
        'Constructora Nacional',
        '2025-11-10 06:17:16',
        '2025-11-25 21:58:53',
        1,
        10
    ),
    (
        2,
        9,
        'Médico',
        'Hospital Central',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        8
    ),
    (
        3,
        10,
        'Contadora',
        'Firma Contable',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        28
    ),
    (
        4,
        11,
        'Docente',
        'Universidad Central',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        12
    ),
    (
        5,
        12,
        'Arquitecto',
        'Estudio de Arquitectura',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        2
    ),
    (
        6,
        13,
        'Abogada',
        'Bufete Legal',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        1
    ),
    (
        7,
        14,
        'Ingeniero de Sistemas',
        'Empresa Tecnológica',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1,
        10
    ),
    (
        11,
        25,
        'mecanico',
        'Mercedes',
        '2025-11-11 19:30:17',
        '2025-11-20 16:05:39',
        1,
        13
    ),
    (
        12,
        28,
        'mecanico',
        'Mercedes',
        '2025-11-11 22:16:43',
        NULL,
        1,
        13
    ),
    (
        13,
        30,
        'Ingeniero',
        'Bancamiga',
        '2025-11-11 22:52:57',
        NULL,
        1,
        10
    ),
    (
        14,
        34,
        'K',
        'K',
        '2025-11-20 03:45:22',
        '2025-11-20 00:15:06',
        1,
        12
    ),
    (
        15,
        37,
        'mecanico',
        'Mercedes',
        '2025-11-20 04:38:27',
        NULL,
        1,
        28
    ),
    (
        16,
        40,
        'F',
        'F',
        '2025-11-20 20:09:12',
        NULL,
        1,
        1
    ),
    (
        17,
        33,
        'Maestro',
        'Nuevo Horizonte',
        '2025-11-24 03:22:17',
        NULL,
        1,
        12
    ),
    (
        18,
        49,
        'mecanico',
        'Mercedes',
        '2025-11-24 03:28:17',
        '2025-11-28 01:08:48',
        1,
        17
    ),
    (
        19,
        61,
        'Odontologa',
        'Av baralt',
        '2025-11-27 04:11:53',
        '2025-11-27 00:29:52',
        1,
        6
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO
    `roles` (
        `id_rol`,
        `nom_rol`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Administrador',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        'Docente',
        '2025-11-17 02:52:59',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `secciones`
--

INSERT INTO
    `secciones` (
        `id_seccion`,
        `nom_seccion`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        'Sección A',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        'Sección B',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        3,
        'A',
        '2025-11-27 03:28:34',
        NULL,
        1
    ),
    (
        4,
        'D',
        '2025-11-27 03:32:51',
        NULL,
        1
    );

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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO
    `usuarios` (
        `id_usuario`,
        `id_persona`,
        `id_rol`,
        `usuario`,
        `contrasena`,
        `creacion`,
        `actualizacion`,
        `estatus`
    )
VALUES (
        1,
        15,
        1,
        'admin',
        '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9',
        '2025-11-10 06:17:16',
        '2025-11-10 02:17:16',
        1
    ),
    (
        2,
        33,
        2,
        'cmoreno',
        '7f42dcd0205e6f5d9fdb76a77098eda3b6a637e69f278c0715ea93b48726dab6',
        '2025-11-17 04:30:35',
        NULL,
        1
    ),
    (
        3,
        34,
        2,
        'rdela',
        '29aa5d0911f4fb6c3cb3b5f79b6e22f4555e9e71a4d63541ca8a5142c2521ac1',
        '2025-11-17 04:41:45',
        NULL,
        1
    );

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
ALTER TABLE `discapacidades` ADD PRIMARY KEY (`id_discapacidad`);

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
ADD UNIQUE KEY `uk_docente_especialidad` (
    `id_docente`,
    `id_especialidad`
),
ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades` ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados` ADD PRIMARY KEY (`id_estado`);

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
ADD UNIQUE KEY `uk_estudiante_discapacidad` (
    `id_estudiante`,
    `id_discapacidad`
),
ADD KEY `id_discapacidad` (`id_discapacidad`);

--
-- Indices de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
ADD PRIMARY KEY (`id_estudiante_patologia`),
ADD UNIQUE KEY `uk_estudiante_patologia` (
    `id_estudiante`,
    `id_patologia`
) USING BTREE,
ADD KEY `id_patologia` (`id_patologia`) USING BTREE;

--
-- Indices de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
ADD PRIMARY KEY (`id_estudiante_representante`),
ADD UNIQUE KEY `uk_estudiante_representante` (
    `id_estudiante`,
    `id_representante`
),
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
ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles` ADD PRIMARY KEY (`id_nivel`);

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
ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `patologias`
--
ALTER TABLE `patologias` ADD PRIMARY KEY (`id_patologia`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos` ADD PRIMARY KEY (`id_periodo`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos` ADD PRIMARY KEY (`id_permiso`);

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
ADD UNIQUE KEY `uk_rol_permiso` (`id_rol`, `id_permiso`),
ADD KEY `id_permiso` (`id_permiso`);

--
-- Indices de la tabla `secciones`
--
ALTER TABLE `secciones` ADD PRIMARY KEY (`id_seccion`);

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
MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 38;

--
-- AUTO_INCREMENT de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
MODIFY `id_discapacidad` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

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
MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 43;

--
-- AUTO_INCREMENT de la tabla `estudiantes_discapacidades`
--
ALTER TABLE `estudiantes_discapacidades`
MODIFY `id_estudiante_discapacidad` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
MODIFY `id_estudiante_patologia` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 26;

--
-- AUTO_INCREMENT de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
MODIFY `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 39;

--
-- AUTO_INCREMENT de la tabla `globales`
--
ALTER TABLE `globales`
MODIFY `id_globales` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 49;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 24;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

--
-- AUTO_INCREMENT de la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
MODIFY `id_nivel_seccion` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 8;

--
-- AUTO_INCREMENT de la tabla `parentesco`
--
ALTER TABLE `parentesco`
MODIFY `id_parentesco` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 89;

--
-- AUTO_INCREMENT de la tabla `patologias`
--
ALTER TABLE `patologias`
MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 6;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 65;

--
-- AUTO_INCREMENT de la tabla `profesiones`
--
ALTER TABLE `profesiones`
MODIFY `id_profesion` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 34;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 20;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;