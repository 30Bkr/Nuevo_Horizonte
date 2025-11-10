<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/ubicacion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Obtener datos del POST
  $input = file_get_contents('php://input');
  $datos = json_decode($input, true);

  $municipio_id = $datos['municipio_id'] ?? '';

  if (empty($municipio_id)) {
    echo json_encode(['success' => false, 'error' => 'ID de municipio requerido']);
    exit;
  }

  try {
    $ubicacionController = new UbicacionController();
    $parroquias = $ubicacionController->getParroquias($municipio_id);

    echo json_encode([
      'success' => true,
      'parroquias' => $parroquias
    ]);
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'error' => 'Error al cargar parroquias: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
