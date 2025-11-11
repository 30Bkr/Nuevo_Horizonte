<?php
header('Content-Type: application/json');
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");

try {
  // Usar POST en lugar de GET
  if (!isset($_POST['estado_id'])) {
    throw new Exception('ID de estado no proporcionado');
  }

  $estado_id = filter_var($_POST['estado_id'], FILTER_VALIDATE_INT);
  if (!$estado_id) {
    throw new Exception('ID de estado inválido');
  }

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $ubicacionController = new UbicacionController($pdo);
  $municipios = $ubicacionController->obtenerMunicipiosPorEstado($estado_id);

  echo json_encode($municipios);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode(['error' => $e->getMessage()]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
