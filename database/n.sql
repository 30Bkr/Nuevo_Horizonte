-- Estructura de la base de datos `nuevo_horizonte`
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `nuevo_horizonte` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;

USE `nuevo_horizonte`;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `direcciones`
CREATE TABLE IF NOT EXISTS `direcciones` (
    `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
    `id_parroquia` int(11) NOT NULL,
    `direccion` varchar(255) NOT NULL,
    `calle` varchar(255) DEFAULT NULL,
    `casa` varchar(255) DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_direccion`),
    KEY `id_parroquia` (`id_parroquia`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `discapacidades`
CREATE TABLE IF NOT EXISTS `discapacidades` (
    `id_discapacidad` int(11) NOT NULL AUTO_INCREMENT,
    `nom_discapacidad` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_discapacidad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `docentes`
CREATE TABLE IF NOT EXISTS `docentes` (
    `id_docente` int(11) NOT NULL AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    `id_profesion` int(11) DEFAULT NULL,
    PRIMARY KEY (`id_docente`),
    UNIQUE KEY `id_persona` (`id_persona`),
    KEY `fk_docentes_profesiones` (`id_profesion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `docentes_especialidades`
CREATE TABLE IF NOT EXISTS `docentes_especialidades` (
    `id_docente_especialidad` int(11) NOT NULL AUTO_INCREMENT,
    `id_docente` int(11) NOT NULL,
    `id_especialidad` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_docente_especialidad`),
    UNIQUE KEY `uk_docente_especialidad` (
        `id_docente`,
        `id_especialidad`
    ),
    KEY `id_especialidad` (`id_especialidad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `especialidades`
CREATE TABLE IF NOT EXISTS `especialidades` (
    `id_especialidad` int(11) NOT NULL AUTO_INCREMENT,
    `nom_especialidad` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_especialidad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estados`
CREATE TABLE IF NOT EXISTS `estados` (
    `id_estado` int(11) NOT NULL AUTO_INCREMENT,
    `nom_estado` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_estado`),
    KEY `idx_estados_nombre` (`nom_estado`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiantes`
CREATE TABLE IF NOT EXISTS `estudiantes` (
    `id_estudiante` int(11) NOT NULL AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_estudiante`),
    UNIQUE KEY `id_persona` (`id_persona`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiantes_discapacidades`
CREATE TABLE IF NOT EXISTS `estudiantes_discapacidades` (
    `id_estudiante_discapacidad` int(11) NOT NULL AUTO_INCREMENT,
    `id_estudiante` int(11) NOT NULL,
    `id_discapacidad` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_estudiante_discapacidad`),
    UNIQUE KEY `uk_estudiante_discapacidad` (
        `id_estudiante`,
        `id_discapacidad`
    ),
    KEY `id_discapacidad` (`id_discapacidad`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiantes_patologias`
CREATE TABLE IF NOT EXISTS `estudiantes_patologias` (
    `id_estudiante_patologia` int(11) NOT NULL AUTO_INCREMENT,
    `id_estudiante` int(11) NOT NULL,
    `id_patologia` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_estudiante_patologia`),
    UNIQUE KEY `uk_estudiante_patologia` (
        `id_estudiante`,
        `id_patologia`
    ),
    KEY `id_patologia` (`id_patologia`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `estudiantes_representantes`
CREATE TABLE IF NOT EXISTS `estudiantes_representantes` (
    `id_estudiante_representante` int(11) NOT NULL AUTO_INCREMENT,
    `id_estudiante` int(11) NOT NULL,
    `id_representante` int(11) NOT NULL,
    `id_parentesco` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_estudiante_representante`),
    UNIQUE KEY `uk_estudiante_representante` (
        `id_estudiante`,
        `id_representante`
    ),
    KEY `id_representante` (`id_representante`),
    KEY `fk_est_rep_parentesco` (`id_parentesco`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `globales`
CREATE TABLE IF NOT EXISTS `globales` (
    `id_globales` int(11) NOT NULL AUTO_INCREMENT,
    `edad_min` int(11) NOT NULL,
    `edad_max` int(11) NOT NULL,
    `nom_instituto` varchar(50) NOT NULL,
    `id_periodo` int(11) NOT NULL,
    `nom_directora` varchar(100) DEFAULT NULL,
    `ci_directora` varchar(8) DEFAULT NULL,
    `direccion` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id_globales`),
    KEY `id_periodo` (`id_periodo`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `inscripciones`
CREATE TABLE IF NOT EXISTS `inscripciones` (
    `id_inscripcion` int(11) NOT NULL AUTO_INCREMENT,
    `id_estudiante` int(11) NOT NULL,
    `id_periodo` int(11) NOT NULL,
    `id_nivel_seccion` int(11) DEFAULT NULL,
    `id_usuario` int(11) NOT NULL,
    `fecha_inscripcion` date NOT NULL,
    `observaciones` text DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_inscripcion`),
    KEY `id_estudiante` (`id_estudiante`),
    KEY `id_periodo` (`id_periodo`),
    KEY `id_nivel_seccion` (`id_nivel_seccion`),
    KEY `id_usuario` (`id_usuario`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `municipios`
CREATE TABLE IF NOT EXISTS `municipios` (
    `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
    `id_estado` int(11) NOT NULL,
    `nom_municipio` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_municipio`),
    KEY `id_estado` (`id_estado`),
    KEY `idx_municipios_estado` (`id_estado`),
    KEY `idx_municipios_nombre` (`nom_municipio`),
    KEY `idx_municipios_estado_nombre` (`id_estado`, `nom_municipio`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `niveles`
CREATE TABLE IF NOT EXISTS `niveles` (
    `id_nivel` int(11) NOT NULL AUTO_INCREMENT,
    `num_nivel` int(11) NOT NULL,
    `nom_nivel` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_nivel`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `niveles_secciones`
CREATE TABLE IF NOT EXISTS `niveles_secciones` (
    `id_nivel_seccion` int(11) NOT NULL AUTO_INCREMENT,
    `id_nivel` int(11) NOT NULL,
    `id_seccion` int(11) NOT NULL,
    `capacidad` int(11) DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_nivel_seccion`),
    KEY `id_nivel` (`id_nivel`),
    KEY `id_seccion` (`id_seccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `parentesco`
CREATE TABLE IF NOT EXISTS `parentesco` (
    `id_parentesco` int(11) NOT NULL AUTO_INCREMENT,
    `parentesco` varchar(20) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_parentesco`),
    UNIQUE KEY `parentesco` (`parentesco`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `parroquias`
CREATE TABLE IF NOT EXISTS `parroquias` (
    `id_parroquia` int(11) NOT NULL AUTO_INCREMENT,
    `id_municipio` int(11) NOT NULL,
    `nom_parroquia` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_parroquia`),
    KEY `id_municipio` (`id_municipio`),
    KEY `idx_parroquias_municipio` (`id_municipio`),
    KEY `idx_parroquias_nombre` (`nom_parroquia`),
    KEY `idx_parroquias_municipio_nombre` (
        `id_municipio`,
        `nom_parroquia`
    )
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `patologias`
CREATE TABLE IF NOT EXISTS `patologias` (
    `id_patologia` int(11) NOT NULL AUTO_INCREMENT,
    `nom_patologia` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_patologia`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `periodos`
CREATE TABLE IF NOT EXISTS `periodos` (
    `id_periodo` int(11) NOT NULL AUTO_INCREMENT,
    `descripcion_periodo` varchar(255) NOT NULL,
    `fecha_ini` date NOT NULL,
    `fecha_fin` date NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_periodo`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `permisos`
CREATE TABLE IF NOT EXISTS `permisos` (
    `id_permiso` int(11) NOT NULL AUTO_INCREMENT,
    `nom_url` varchar(255) NOT NULL,
    `url` varchar(255) NOT NULL,
    `descripcion` text DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_permiso`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `personas`
CREATE TABLE IF NOT EXISTS `personas` (
    `id_persona` int(11) NOT NULL AUTO_INCREMENT,
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
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_persona`),
    UNIQUE KEY `cedula` (`cedula`),
    KEY `id_direccion` (`id_direccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `profesiones`
CREATE TABLE IF NOT EXISTS `profesiones` (
    `id_profesion` int(11) NOT NULL AUTO_INCREMENT,
    `profesion` varchar(25) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_profesion`),
    UNIQUE KEY `profesion` (`profesion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `representantes`
CREATE TABLE IF NOT EXISTS `representantes` (
    `id_representante` int(11) NOT NULL AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `ocupacion` varchar(255) DEFAULT NULL,
    `lugar_trabajo` varchar(255) DEFAULT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    `id_profesion` int(11) DEFAULT NULL,
    PRIMARY KEY (`id_representante`),
    UNIQUE KEY `id_persona` (`id_persona`),
    KEY `fk_representantes_profesiones` (`id_profesion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `roles`
CREATE TABLE IF NOT EXISTS `roles` (
    `id_rol` int(11) NOT NULL AUTO_INCREMENT,
    `nom_rol` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_rol`),
    UNIQUE KEY `nom_rol` (`nom_rol`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `roles_permisos`
CREATE TABLE IF NOT EXISTS `roles_permisos` (
    `id_rol_permiso` int(11) NOT NULL AUTO_INCREMENT,
    `id_rol` int(11) NOT NULL,
    `id_permiso` int(11) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_rol_permiso`),
    UNIQUE KEY `uk_rol_permiso` (`id_rol`, `id_permiso`),
    KEY `id_permiso` (`id_permiso`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `secciones`
CREATE TABLE IF NOT EXISTS `secciones` (
    `id_seccion` int(11) NOT NULL AUTO_INCREMENT,
    `nom_seccion` varchar(255) NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_seccion`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
    `id_persona` int(11) NOT NULL,
    `id_rol` int(11) NOT NULL,
    `usuario` varchar(255) NOT NULL,
    `contrasena` text NOT NULL,
    `creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `actualizacion` varchar(255) DEFAULT NULL,
    `estatus` tinyint(4) DEFAULT 1,
    PRIMARY KEY (`id_usuario`),
    UNIQUE KEY `id_persona` (`id_persona`),
    UNIQUE KEY `usuario` (`usuario`),
    KEY `id_rol` (`id_rol`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_spanish_ci;

-- --------------------------------------------------------
-- Restricciones para tablas volcadas
ALTER TABLE `direcciones`
ADD CONSTRAINT `fk_direcciones_parroquias` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquias` (`id_parroquia`);

ALTER TABLE `docentes`
ADD CONSTRAINT `fk_docentes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
ADD CONSTRAINT `fk_docentes_profesiones` FOREIGN KEY (`id_profesion`) REFERENCES `profesiones` (`id_profesion`);

ALTER TABLE `docentes_especialidades`
ADD CONSTRAINT `fk_docentes_especialidades_docentes` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
ADD CONSTRAINT `fk_docentes_especialidades_especialidades` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`);

ALTER TABLE `estudiantes`
ADD CONSTRAINT `fk_estudiantes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`);

ALTER TABLE `estudiantes_discapacidades`
ADD CONSTRAINT `fk_estudiantes_discapacidades_discapacidades` FOREIGN KEY (`id_discapacidad`) REFERENCES `discapacidades` (`id_discapacidad`),
ADD CONSTRAINT `fk_estudiantes_discapacidades_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

ALTER TABLE `estudiantes_patologias`
ADD CONSTRAINT `fk_estudiantes_patologias_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
ADD CONSTRAINT `fk_estudiantes_patologias_patologias` FOREIGN KEY (`id_patologia`) REFERENCES `patologias` (`id_patologia`);

ALTER TABLE `estudiantes_representantes`
ADD CONSTRAINT `fk_est_rep_parentesco` FOREIGN KEY (`id_parentesco`) REFERENCES `parentesco` (`id_parentesco`),
ADD CONSTRAINT `fk_estudiantes_representantes_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
ADD CONSTRAINT `fk_estudiantes_representantes_representantes` FOREIGN KEY (`id_representante`) REFERENCES `representantes` (`id_representante`);

ALTER TABLE `globales`
ADD CONSTRAINT `globales_ibfk_1` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`);

ALTER TABLE `inscripciones`
ADD CONSTRAINT `fk_inscripciones_estudiantes` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
ADD CONSTRAINT `fk_inscripciones_niveles_secciones` FOREIGN KEY (`id_nivel_seccion`) REFERENCES `niveles_secciones` (`id_nivel_seccion`),
ADD CONSTRAINT `fk_inscripciones_periodos` FOREIGN KEY (`id_periodo`) REFERENCES `periodos` (`id_periodo`),
ADD CONSTRAINT `fk_inscripciones_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

ALTER TABLE `municipios`
ADD CONSTRAINT `fk_municipios_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`);

ALTER TABLE `niveles_secciones`
ADD CONSTRAINT `fk_niveles_secciones_niveles` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id_nivel`),
ADD CONSTRAINT `fk_niveles_secciones_secciones` FOREIGN KEY (`id_seccion`) REFERENCES `secciones` (`id_seccion`);

ALTER TABLE `parroquias`
ADD CONSTRAINT `fk_parroquias_municipios` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`);

ALTER TABLE `personas`
ADD CONSTRAINT `fk_personas_direcciones` FOREIGN KEY (`id_direccion`) REFERENCES `direcciones` (`id_direccion`);

ALTER TABLE `representantes`
ADD CONSTRAINT `fk_representantes_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
ADD CONSTRAINT `fk_representantes_profesiones` FOREIGN KEY (`id_profesion`) REFERENCES `profesiones` (`id_profesion`);

ALTER TABLE `roles_permisos`
ADD CONSTRAINT `fk_roles_permisos_permisos` FOREIGN KEY (`id_permiso`) REFERENCES `permisos` (`id_permiso`),
ADD CONSTRAINT `fk_roles_permisos_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

ALTER TABLE `usuarios`
ADD CONSTRAINT `fk_usuarios_personas` FOREIGN KEY (`id_persona`) REFERENCES `personas` (`id_persona`),
ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

COMMIT;