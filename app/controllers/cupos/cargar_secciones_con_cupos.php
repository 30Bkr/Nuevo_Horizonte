<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  if (empty($_POST['id_nivel']) || empty($_POST['id_periodo'])) {
    throw new Exception('Parámetros requeridos: id_nivel, id_periodo');
  }

  $id_nivel = $_POST['id_nivel'];
  $id_periodo = $_POST['id_periodo'];

  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $cuposController = new CuposController($pdo);

  // Obtener secciones con información de cupos
  $resultado = $cuposController->obtenerDisponibilidadPorNivel($id_nivel, $id_periodo);

  echo json_encode($resultado);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage(),
    'secciones' => []
  ]);
}
