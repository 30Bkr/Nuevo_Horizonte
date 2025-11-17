-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-11-2025 a las 02:45:28
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
-- Base de datos: `neudelys`
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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
    `id_docente` int(11) NOT NULL,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_especialidades`
--

CREATE TABLE `docentes_especialidades` (
    `id_docente_especialidad` int(11) NOT NULL,
    `id_docente` int(11) NOT NULL,
    `id_especialidad` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
    `id_especialidad` int(11) NOT NULL,
    `nom_especialidad` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
    `id_estado` int(11) NOT NULL,
    `nom_estado` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
    `id_estudiante` int(11) NOT NULL,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_patologias`
--

CREATE TABLE `estudiantes_patologias` (
    `id_estudiante_patologia` int(11) NOT NULL,
    `id_estudiante` int(11) NOT NULL,
    `id_patologia` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

CREATE TABLE `municipios` (
    `id_municipio` int(11) NOT NULL,
    `id_estado` int(11) NOT NULL,
    `nom_municipio` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
    `id_nivel` int(11) NOT NULL,
    `num_nivel` int(11) NOT NULL,
    `nom_nivel` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquias`
--

CREATE TABLE `parroquias` (
    `id_parroquia` int(11) NOT NULL,
    `id_municipio` int(11) NOT NULL,
    `nom_parroquia` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patologias`
--

CREATE TABLE `patologias` (
    `id_patologia` int(11) NOT NULL,
    `nom_patologia` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
    `id_rol` int(11) NOT NULL,
    `nom_rol` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_permisos`
--

CREATE TABLE `roles_permisos` (
    `id_rol_permiso` int(11) NOT NULL,
    `id_rol` int(11) NOT NULL,
    `id_permiso` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones`
--

CREATE TABLE `secciones` (
    `id_seccion` int(11) NOT NULL,
    `nom_seccion` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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
    `actualizacion` varchar(255) NOT NULL,
    `estatus` tinyint(4) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

--

INSERT INTO
    `estados` (`nom_estado`)
VALUES ('Miranda'),
    ('La Guaira'),
    ('Distrito Capital');

INSERT INTO
    `municipios` (`id_estado`, `nom_municipio`)
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
    `parroquias` (
        `id_municipio`,
        `nom_parroquia`
    )
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
ALTER TABLE `niveles` ADD PRIMARY KEY (`id_nivel`);

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

ALTER TABLE `personas`
ADD CONSTRAINT `chk_personas_sexo_valido` CHECK (
    sexo IN ('Masculino', 'Femenino')
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;