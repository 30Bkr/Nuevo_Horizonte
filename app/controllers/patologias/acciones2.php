<?php
session_start();
include_once("../../conexion.php"); // Ruta corregida
include_once("patologias.php"); // Incluye el controlador

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $patologiaController = new PatologiaController($pdo);
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'agregar':
      $nombre = $_POST['nombre'] ?? '';
      if (empty($nombre)) {
        $response = ['success' => false, 'message' => 'El nombre de la patología es requerido'];
      } else {
        $response = $patologiaController->agregarPatologia($nombre);
      }
      break;

    case 'actualizar':
      $id = $_POST['id'] ?? '';
      $nombre = $_POST['nombre'] ?? '';
      $estatus = $_POST['estatus'] ?? 1;

      if (empty($id) || empty($nombre)) {
        $response = ['success' => false, 'message' => 'Datos incompletos'];
      } else {
        $response = $patologiaController->actualizarPatologia($id, $nombre, $estatus);
      }
      break;

    case 'obtener_todas':
      $patologias = $patologiaController->obtenerPatologias();
      $response = ['success' => true, 'data' => $patologias];
      break;

    default:
      $response = ['success' => false, 'message' => 'Acción no reconocida'];
      break;
  }
} catch (Exception $e) {
  $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
