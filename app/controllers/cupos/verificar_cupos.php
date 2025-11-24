<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // Validar parámetros requeridos
  $required_params = ['id_nivel', 'id_seccion', 'id_periodo'];
  foreach ($required_params as $param) {
    if (empty($_POST[$param])) {
      throw new Exception("Parámetro requerido: {$param}");
    }
  }

  $id_nivel = $_POST['id_nivel'];
  $id_seccion = $_POST['id_seccion'];
  $id_periodo = $_POST['id_periodo'];

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $cuposController = new CuposController($pdo);

  // Verificar disponibilidad
  $disponibilidad = $cuposController->obtenerDisponibilidad($id_nivel, $id_seccion, $id_periodo);

  echo json_encode($disponibilidad);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage(),
    'disponible' => false
  ]);
}
