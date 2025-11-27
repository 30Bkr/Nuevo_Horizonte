<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  if (empty($_POST['id_nivel'])) {
    throw new Exception('Parámetro requerido: id_nivel');
  }

  $id_nivel = $_POST['id_nivel'];

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $cuposController = new CuposController($pdo);

  $resultado = $cuposController->obtenerSeccionesPorNivel($id_nivel);

  echo json_encode($resultado);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage(),
    'secciones' => []
  ]);
}
