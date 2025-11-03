<?php
// app/config.php

// Define el directorio raíz del proyecto para inclusiones seguras
define('ROOT_PATH', dirname(__DIR__)); 

// URL base para redirecciones y enlaces (ajusta 'nuevo_horizonte' si es diferente)
define('BASE_URL', 'http://localhost/nuevo_horizonte'); 

// Incluir la conexión a la base de datos (conexion.php)
require_once(ROOT_PATH . '/app/conexion.php');

// ... otras configuraciones que puedas tener
?>