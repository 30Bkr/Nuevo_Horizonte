<?php
header('Content-Type: application/json');
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");

try {
  // Usar POST en lugar de GET
  if (!isset($_POST['municipio_id'])) {
    throw new Exception('ID de municipio no proporcionado');
  }

  $municipio_id = filter_var($_POST['municipio_id'], FILTER_VALIDATE_INT);
  if (!$municipio_id) {
    throw new Exception('ID de municipio inválido');
  }

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $ubicacionController = new UbicacionController($pdo);
  $parroquias = $ubicacionController->obtenerParroquiasPorMunicipio($municipio_id);

  echo json_encode($parroquias);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['error' => $e->getMessage()]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
