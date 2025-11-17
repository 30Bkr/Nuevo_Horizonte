<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/Inscripcion2.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cedula = $_POST['cedula'] ?? '';

  if (empty($cedula)) {
    echo json_encode(['existe' => false, 'error' => 'Cédula requerida']);
    exit;
  }

  try {
    $inscripcionController = new InscripcionController();
    $resultado = $inscripcionController->validarRepresentante($cedula);

    echo json_encode($resultado);
  } catch (Exception $e) {
    echo json_encode([
      'existe' => false,
      'error' => 'Error en la validación: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode(['existe' => false, 'error' => 'Método no permitido']);
}
