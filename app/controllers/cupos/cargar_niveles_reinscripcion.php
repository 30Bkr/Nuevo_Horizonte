<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  if (empty($_POST['id_estudiante'])) {
    throw new Exception('Parámetro requerido: id_estudiante');
  }

  $id_estudiante = $_POST['id_estudiante'];

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $cuposController = new CuposController($pdo);

  $resultado = $cuposController->obtenerNivelesReinscripcion($id_estudiante);

  echo json_encode($resultado);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage(),
    'niveles' => []
  ]);
}
