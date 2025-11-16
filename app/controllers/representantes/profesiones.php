<?php
header('Content-Type: application/json');
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");

try {
  // Usar POST en lugar de GET
  if (!isset($_POST['profesionesId'])) {
    throw new Exception('ID de estado no proporcionado');
  }

  $profesionesId = filter_var($_POST['profesionesId'], FILTER_VALIDATE_INT);
  if (!$profesionesId) {
    throw new Exception('ID de estado inválido');
  }

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $ubicacionController = new RepresentanteController($pdo);
  $profesiones = $ubicacionController->obtenerProfesionesById($profesionesId);

  echo json_encode($profesiones);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['error' => $e->getMessage()]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
