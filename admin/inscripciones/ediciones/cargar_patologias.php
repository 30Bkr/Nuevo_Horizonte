<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/PatologiaController.php");

header('Content-Type: application/json');

try {
  $patologiaController = new PatologiaController();
  $patologias = $patologiaController->getPatologias();

  echo json_encode([
    'success' => true,
    'patologias' => $patologias
  ]);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'error' => 'Error al cargar patologías: ' . $e->getMessage()
  ]);
}
