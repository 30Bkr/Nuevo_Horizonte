<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/SeccionController.php");

header('Content-Type: application/json');

try {
  $seccionController = new SeccionController();
  $secciones = $seccionController->getSecciones();

  echo json_encode([
    'success' => true,
    'secciones' => $secciones
  ]);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'error' => 'Error al cargar secciones: ' . $e->getMessage()
  ]);
}
