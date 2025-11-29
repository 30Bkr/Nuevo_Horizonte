<?php
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $estudianteController = new EstudianteController($pdo);

  $id_representante = $_POST['id_representante'] ?? '';

  if (empty($id_representante)) {
    echo json_encode(['estudiantes' => []]);
    exit;
  }

  $estudiantes = $estudianteController->obtenerEstudiantesPorRepresentante($id_representante);

  echo json_encode(['estudiantes' => $estudiantes]);
} catch (Exception $e) {
  echo json_encode(['estudiantes' => [], 'error' => $e->getMessage()]);
}
