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
  'municipios' => []
];

try {
  // Verificar método
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido. Usa POST.');
  }

  // Obtener y validar ID del estado
  $id_estado = $_POST['id_estado'] ?? '';

  if (empty($id_estado)) {
    throw new Exception('ID de estado requerido');
  }

  if (!is_numeric($id_estado)) {
    throw new Exception('ID de estado debe ser numérico');
  }

  // Conectar a la base de datos
  $database = new Conexion();
  $db = $database->conectar();

  if (!$db) {
    throw new Exception('Error de conexión a la base de datos');
  }

  // Obtener municipios
  $controller = new PersonasController($db);
  $municipios = $controller->obtenerMunicipiosPorEstado($id_estado);

  if ($municipios === false) {
    throw new Exception('Error al obtener municipios desde el controlador');
  }

  $response['success'] = true;
  $response['municipios'] = $municipios;
} catch (Exception $e) {
  $response['message'] = $e->getMessage();
  error_log("Error en obtener_municipios.php: " . $e->getMessage());
}

echo json_encode($response);
