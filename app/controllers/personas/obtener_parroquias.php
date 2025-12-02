<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/personas/personas2.php';

$response = [
  'success' => false,
  'message' => '',
  'parroquias' => []
];

try {
  // Verificar método
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido. Usa POST.');
  }

  // Obtener y validar ID del municipio
  $id_municipio = $_POST['id_municipio'] ?? '';

  if (empty($id_municipio)) {
    throw new Exception('ID de municipio requerido');
  }

  if (!is_numeric($id_municipio)) {
    throw new Exception('ID de municipio debe ser numérico');
  }

  // Conectar a la base de datos
  $database = new Conexion();
  $db = $database->conectar();

  if (!$db) {
    throw new Exception('Error de conexión a la base de datos');
  }

  // Obtener parroquias
  $controller = new PersonasController($db);
  $parroquias = $controller->obtenerParroquiasPorMunicipio($id_municipio);

  if ($parroquias === false) {
    throw new Exception('Error al obtener parroquias desde el controlador');
  }

  $response['success'] = true;
  $response['parroquias'] = $parroquias;
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
  error_log("Error en obtener_parroquias.php: " . $e->getMessage());
}

echo json_encode($response);
