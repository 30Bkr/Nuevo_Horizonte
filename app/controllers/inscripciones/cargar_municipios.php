<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/ubicacion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener datos del POST
  $input = file_get_contents('php://input');
  $datos = json_decode($input, true);

  $estado_id = $datos['estado_id'] ?? '';

  if (empty($estado_id)) {
    echo json_encode(['success' => false, 'error' => 'ID de estado requerido']);
    exit;
  }

  try {
    $ubicacionController = new UbicacionController();
    $municipios = $ubicacionController->getMunicipios($estado_id);

    echo json_encode([
      'success' => true,
      'municipios' => $municipios
    ]);
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'error' => 'Error al cargar municipios: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
