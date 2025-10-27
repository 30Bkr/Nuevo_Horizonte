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

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de datos: `nuevo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `años`
--

CREATE TABLE IF NOT EXISTS `estados` (
    `id_estado` int(11) NOT NULL AUTO_INCREMENT,
    `estado` varchar(250) NOT NULL,
    `iso_3166-2` varchar(4) NOT NULL,
    PRIMARY KEY (`id_estado`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE IF NOT EXISTS `municipios` (
    `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
    `id_estado` int(11) NOT NULL,
    `municipio` varchar(100) NOT NULL,
    PRIMARY KEY (`id_municipio`),
    KEY `id_estado` (`id_estado`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE IF NOT EXISTS `parroquias` (
    `id_parroquia` int(11) NOT NULL AUTO_INCREMENT,
    `id_municipio` int(11) NOT NULL,
    `parroquia` varchar(250) NOT NULL,
    PRIMARY KEY (`id_parroquia`),
    KEY `id_municipio` (`id_municipio`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `direcciones` (
    `id_direccion` int(11) AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `id_estado` int(11) NOT NULL,
    `id_municipio` int(11) NOT NULL,
    `id_parroquia` int(11) NOT NULL,
    `direccion` varchar(200) NOT NULL,
    `calle` varchar(200) NOT NULL,
    `casa` varchar(200) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`),
    CONSTRAINT `direcciones_ibfk_2` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`),
    CONSTRAINT `direcciones_ibfk_3` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `roles` (
    `id_rol` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `nombre_rol` varchar(50) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `personas` (
    `id_persona` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_rol` int(11) NOT NULL,
    `nombres` varchar(50) NOT NULL,
    `apellidos` varchar(50) NOT NULL,
    `cedula` varchar(15) NOT NULL UNIQUE,
    `telefono` varchar(15) NOT NULL,
    `telefono_hab` varchar(15) DEFAULT NULL,
    `correo` varchar(50) NOT NULL UNIQUE,
    `lugar_nac` varchar(50) NOT NULL,
    `fecha_nac` date DEFAULT NULL,
    `sexo` varchar(15) DEFAULT NULL,
    `nacionalidad` varchar(10) NOT NULL,
    `id_direccion` int(11) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    CONSTRAINT `personas_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
    CONSTRAINT `personas_ibfk_2` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `usuarios` (
    `id_usuario` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `id_rol` int(11) NOT NULL,
    `nombre_usuario` varchar(50) NOT NULL,
    `usuario` varchar(8) DEFAULT NULL,
    `contraseña` varchar(255) NOT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` varchar(255) DEFAULT NULL,
    CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
    CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `permisos` (
    `id_permiso` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `nombre_url` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
    `url` text COLLATE utf8mb4_spanish_ci NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `roles_permisos` (
    `id_rol_permiso` int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_rol` int NOT NULL,
    `id_permiso` int NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    CONSTRAINT `roles_permisos_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
    CONSTRAINT `roles_permisos_ibfk_2` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `profesores` (
    `id_profesor` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `profesores_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `especialidades` (
    `id_especialidad` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `nombre_especialidad` varchar(50) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `profesor_especialidad` (
    `id_profesor_especialidad` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_profesor` int(11) NOT NULL,
    `id_especialidad` int(11) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `profesor_especialidad_ibfk_1` FOREIGN KEY (`id_profesor`) REFERENCES `profesores` (`id_profesor`),
    CONSTRAINT `profesor_especialidad_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `representantes` (
    `id_representante` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `profesion_oficio` varchar(50) NOT NULL,
    `ocupacion` varchar(50) NOT NULL,
    `lugar_trabajo` varchar(50) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `representantes_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `estudiantes` (
    `id_estudiante` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `enfermedades` (
    `id_enfermedad` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` varchar(50) NOT NULL,
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `estudiantes_enfermedades` (
    `id_estudiantes_enfermedades` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_estudiante` int(11) NOT NULL,
    `id_enfermedad` int(11) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `estudiantes_endermedades_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
    CONSTRAINT `estudiantes_endermedades_ibfk_2` FOREIGN KEY (`id_enfermedad`) REFERENCES `enfermedades` (`id_enfermedad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `estudiantes_representantes` (
    `id_estudiantes_representantes` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_estudiante` int(11) NOT NULL,
    `id_representante` int(11) NOT NULL,
    `parentesco` varchar(20) NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `estudiantes_representante_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
    CONSTRAINT `estudiantes_representante_ibfk_2` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `años` (
    `id_años` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `año` int(11) NOT NULL UNIQUE,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `grados` (
    `id_grados` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `grado` int(11) NOT NULL UNIQUE,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `secciones` (
    `id_seccion` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `nom_seccion` varchar(1) DEFAULT NULL,
    `turno` varchar(10) DEFAULT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `años_secciones` (
    `id_años_secciones` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_años` int(11) NOT NULL,
    `id_seccion` int(11) NOT NULL,
    `capacidad` int(11) DEFAULT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `estatus` TINYINT(4) DEFAULT 1,
    `actualizado` VARCHAR(255) DEFAULT NULL,
    CONSTRAINT `años_secciones_ibfk_1` FOREIGN KEY (`id_años`) REFERENCES `años` (`id_años`),
    CONSTRAINT `años_secciones_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `grados_secciones` (
    `id_grados_secciones` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_grados` int(11) NOT NULL,
    `id_seccion` int(11) NOT NULL,
    `capacidad` int(11) DEFAULT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `estatus` TINYINT(4) DEFAULT 1,
    `actualizado` VARCHAR(255) DEFAULT NULL,
    CONSTRAINT `grados_secciones_ibfk_1` FOREIGN KEY (`id_grados`) REFERENCES `grados` (`id_grados`),
    CONSTRAINT `grados_secciones_ibfk_2` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- CREATE TABLE `inscripcion_inicial` (
--     `id_inscripcion_inicial` int(11) NOT NULL,
--     `id_estudiantes_representantes` int(11) NOT NULL,
--     `id_grados_secciones` int(11) NOT NULL,
--     `id_periodo` int(11) NOT NULL,
--     `creacion` timestamp NULL DEFAULT current_timestamp(),
--     `actualizado` VARCHAR(255) DEFAULT NULL
-- ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- CREATE TABLE `inscripcion_media` (
--     `id_inscripcion_media` int(11) NOT NULL,
--     `id_estudiantes_representantes` int(11) NOT NULL,
--     `id_años_secciones` int(11) NOT NULL,
--     `id_periodo` int(11) NOT NULL,
--     `creacion` timestamp NULL DEFAULT current_timestamp(),
--     `actualizado` VARCHAR(255) DEFAULT NULL
-- ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `periodo` (
    `id_periodo` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `periodo` date DEFAULT NULL unique,
    `inicio` date DEFAULT NULL,
    `fin` date DEFAULT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `matricula` (
    `id_matricula` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `id_periodo` INT NOT NULL,
    `id_grados_secciones` INT,
    `id_años_secciones` INT,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `matricula_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodo` (`id_periodo`),
    CONSTRAINT `matricula_ibfk_2` FOREIGN KEY (`id_grados_secciones`) REFERENCES `grados_secciones` (`id_grados_secciones`),
    CONSTRAINT `matricula_ibfk_3` FOREIGN KEY (`id_años_secciones`) REFERENCES `años_secciones` (`id_años_secciones`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

CREATE TABLE `inscripcion` (
    `id_inscripcion` INT(11) PRIMARY KEY AUTO_INCREMENT,
    `id_estudiantes_representantes` INT NOT NULL,
    `id_matricula` INT NOT NULL,
    `fecha_inscripcion` DATE NOT NULL,
    `creacion` timestamp NULL DEFAULT current_timestamp(),
    `actualizado` VARCHAR(255) DEFAULT NULL,
    `estatus` TINYINT(4) DEFAULT 1,
    CONSTRAINT `inscripcion_ibfk_1` FOREIGN KEY (
        `id_estudiantes_representantes`
    ) REFERENCES `estudiantes_representantes` (
        `id_estudiantes_representantes`
    ),
    CONSTRAINT `inscripcion_ibfk_2` FOREIGN KEY (`id_matricula`) REFERENCES `matricula` (`id_matricula`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

INSERT INTO
    `estados` (
        `id_estado`,
        `estado`,
        `iso_3166-2`
    )
VALUES (1, 'Miranda', 'VE-M'),
    (2, 'La Guaira', 'VE-W'),
    (3, 'Distrito Capital', 'VE-A');

INSERT INTO
    `municipios` (`id_estado`, `municipio`)
VALUES (1, 'Acevedo'),
    (1, 'Andrés Bello'),
    (1, 'Baruta'),
    (1, 'Brión'),
    (1, 'Buroz'),
    (1, 'Carrizal'),
    (1, 'Chacao'),
    (1, 'Cristóbal Rojas'),
    (1, 'El Hatillo'),
    (1, 'Guaicaipuro'),
    (1, 'Independencia'),
    (1, 'Lander'),
    (1, 'Los Salias'),
    (1, 'Páez'),
    (1, 'Paz Castillo'),
    (1, 'Pedro Gual'),
    (1, 'Plaza'),
    (1, 'Simón Bolívar'),
    (1, 'Sucre'),
    (1, 'Urdaneta'),
    (1, 'Zamora'),
    (2, 'Vargas'),
    (3, 'Libertador');

INSERT INTO
    `parroquias` (`id_municipio`, `parroquia`)
VALUES (1, 'Aragüita'),
    (1, 'Arévalo González'),
    (1, 'Capaya'),
    (1, 'Caucagua'),
    (1, 'Panaquire'),
    (1, 'Ribas'),
    (1, 'El Café'),
    (1, 'Marizapa'),
    (2, 'Cumbo'),
    (2, 'San José de Barlovento'),
    (3, 'El Cafetal'),
    (3, 'Las Minas'),
    (
        3,
        'Nuestra Señora del Rosario'
    ),
    (4, 'Higuerote'),
    (4, 'Curiepe'),
    (4, 'Tacarigua de Brión'),
    (5, 'Mamporal'),
    (6, 'Carrizal'),
    (7, 'Chacao'),
    (8, 'Charallave'),
    (8, 'Las Brisas'),
    (9, 'El Hatillo'),
    (
        10,
        'Altagracia de la Montaña'
    ),
    (10, 'Cecilio Acosta'),
    (10, 'Los Teques'),
    (10, 'El Jarillo'),
    (10, 'San Pedro'),
    (10, 'Tácata'),
    (10, 'Paracotos'),
    (11, 'Cartanal'),
    (11, 'Santa Teresa del Tuy'),
    (12, 'La Democracia'),
    (12, 'Ocumare del Tuy'),
    (12, 'Santa Bárbara'),
    (
        13,
        'San Antonio de los Altos'
    ),
    (14, 'Río Chico'),
    (14, 'El Guapo'),
    (14, 'Tacarigua de la Laguna'),
    (14, 'Paparo'),
    (14, 'San Fernando del Guapo'),
    (15, 'Santa Lucía del Tuy'),
    (16, 'Cúpira'),
    (16, 'Machurucuto'),
    (17, 'Guarenas'),
    (18, 'San Antonio de Yare'),
    (18, 'San Francisco de Yare'),
    (19, 'Leoncio Martínez'),
    (19, 'Petare'),
    (19, 'Caucagüita'),
    (19, 'Filas de Mariche'),
    (19, 'La Dolorita'),
    (20, 'Cúa'),
    (20, 'Nueva Cúa'),
    (21, 'Guatire'),
    (21, 'Bolívar'),
    (22, 'Caraballeda'),
    (22, 'Carayaca'),
    (22, 'Carlos Soublette'),
    (22, 'Caruao Chuspa'),
    (22, 'Catia La Mar'),
    (22, 'El Junko'),
    (22, 'La Guaira'),
    (22, 'Macuto'),
    (22, 'Maiquetía'),
    (22, 'Naiguatá'),
    (22, 'Urimare'),
    (23, 'Altagracia'),
    (23, 'Antímano'),
    (23, 'Caricuao'),
    (23, 'Catedral'),
    (23, 'Coche'),
    (23, 'El Junquito'),
    (23, 'El Paraíso'),
    (23, 'El Recreo'),
    (23, 'El Valle'),
    (23, 'La Candelaria'),
    (23, 'La Pastora'),
    (23, 'La Vega'),
    (23, 'Macarao'),
    (23, 'San Agustín'),
    (23, 'San Bernardino'),
    (23, 'San José'),
    (23, 'San Juan'),
    (23, 'San Pedro'),
    (23, 'Santa Rosalía'),
    (23, 'Santa Teresa'),
    (23, 'Sucre (Catia)'),
    (23, '23 de enero');

--
-- Indices de la tabla `años_secciones`

ALTER TABLE `municipios`
ADD CONSTRAINT `municipios_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `parroquias`
ADD CONSTRAINT `parroquias_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;