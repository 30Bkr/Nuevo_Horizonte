-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-01-2026 a las 22:32:10
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `globales`
--

CREATE TABLE `globales` (
    `id_globales` int(11) NOT NULL,
    `version` int(11) NOT NULL DEFAULT 1,
    `edad_min` int(11) NOT NULL,
    `edad_max` int(11) NOT NULL,
    `nom_instituto` varchar(50) NOT NULL,
    `id_periodo` int(11) NOT NULL,
    `nom_directora` varchar(100) DEFAULT NULL,
    `ci_directora` varchar(8) DEFAULT NULL,
    `direccion` varchar(255) DEFAULT NULL,
    `es_activo` tinyint(1) NOT NULL DEFAULT 1,
    `id_usuario_modificacion` int(11) DEFAULT NULL,
    `motivo_cambio` text DEFAULT NULL,
    `fecha_modificacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;

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
    `foto_representante` varchar(255) DEFAULT NULL,
    `foto_estudiante` varchar(255) DEFAULT NULL,
    `lugar_nac` varchar(255) DEFAULT NULL,
    `fecha_nac` date DEFAULT NULL,
    `sexo` varchar(10) DEFAULT NULL,
    `nacionalidad` varchar(255) DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
    `id_usuario` int(11) NOT NULL,
    `id_persona` int(11) NOT NULL,
    `id_rol` int(11) NOT NULL,
    `usuario` varchar(255) NOT NULL,
    `contrasena` varchar(255) DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    `contrasena_migrada` tinyint(1) DEFAULT 0,
    `requiere_cambio_contrasena` tinyint(1) DEFAULT 0,
    `fecha_ultimo_cambio` timestamp NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_backup_20251231`
--

CREATE TABLE `usuarios_backup_20251231` (
    `id_usuario` int(11) NOT NULL DEFAULT 0,
    `id_persona` int(11) NOT NULL,
    `id_rol` int(11) NOT NULL,
    `usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
    `contrasena` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_uca1400_ai_ci;

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
ADD KEY `id_periodo` (`id_periodo`),
ADD KEY `idx_globales_activo` (`es_activo`),
ADD KEY `idx_globales_version` (`version`),
ADD KEY `fk_globales_usuario` (`id_usuario_modificacion`);

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
ADD KEY `idx_municipios_estado_nombre` (`id_estado`, `nom_municipio`);

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
ADD KEY `id_municipio` (`id_municipio`),
ADD KEY `idx_parroquias_municipio` (`id_municipio`),
ADD KEY `idx_parroquias_nombre` (`nom_parroquia`),
ADD KEY `idx_parroquias_municipio_nombre` (
    `id_municipio`,
    `nom_parroquia`
);

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
MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `discapacidades`
--
ALTER TABLE `discapacidades`
MODIFY `id_discapacidad` int(11) NOT NULL AUTO_INCREMENT;

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
MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes_discapacidades`
--
ALTER TABLE `estudiantes_discapacidades`
MODIFY `id_estudiante_discapacidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes_patologias`
--
ALTER TABLE `estudiantes_patologias`
MODIFY `id_estudiante_patologia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estudiantes_representantes`
--
ALTER TABLE `estudiantes_representantes`
MODIFY `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `globales`
--
ALTER TABLE `globales`
MODIFY `id_globales` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
MODIFY `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `municipios`
--
ALTER TABLE `municipios`
MODIFY `id_municipio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
MODIFY `id_nivel` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `niveles_secciones`
--
ALTER TABLE `niveles_secciones`
MODIFY `id_nivel_seccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parentesco`
--
ALTER TABLE `parentesco`
MODIFY `id_parentesco` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parroquias`
--
ALTER TABLE `parroquias`
MODIFY `id_parroquia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `patologias`
--
ALTER TABLE `patologias`
MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
MODIFY `id_periodo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personas`
--
ALTER TABLE `personas`
MODIFY `id_persona` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesiones`
--
ALTER TABLE `profesiones`
MODIFY `id_profesion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `representantes`
--
ALTER TABLE `representantes`
MODIFY `id_representante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles_permisos`
--
ALTER TABLE `roles_permisos`
MODIFY `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `secciones`
--
ALTER TABLE `secciones`
MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

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
ADD CONSTRAINT `fk_globales_usuario` FOREIGN KEY (`id_usuario_modificacion`) REFERENCES `usuarios` (`id_usuario`),
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