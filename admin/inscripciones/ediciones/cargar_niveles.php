<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/NivelController.php");

header('Content-Type: application/json');

try {
  $nivelController = new NivelController();
  $niveles = $nivelController->getNiveles();

  echo json_encode([
    'success' => true,
    'niveles' => $niveles
  ]);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'error' => 'Error al cargar niveles: ' . $e->getMessage()
  ]);
}
