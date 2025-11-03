<?php
// config.php dentro de la carpeta /app

// Definir la ruta raíz del sistema de archivos (File System)
// __DIR__ es el directorio actual (/app). Subir un nivel (..) apunta a /nuevo_horizonte.
define('ROOT_PATH', __DIR__ . '/..');

// Incluir la conexión a la base de datos (Usando la nueva constante)
// ROOT_PATH . /app/conexion.php se resuelve a /nuevo_horizonte/app/conexion.php
require_once(ROOT_PATH . '/app/conexion.php');

// ** DEFINICIÓN DE BASE_URL (PARA REDIRECCIONES) **
// Obtiene el protocolo (http o https)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
// Obtiene el host (localhost)
$host = $_SERVER['HTTP_HOST'];
// Define el nombre de la carpeta de tu proyecto
$project_folder = 'nuevo_horizonte'; 

// URL base completa: http://localhost/nuevo_horizonte
define('BASE_URL', "{$protocol}://{$host}/{$project_folder}")
?>