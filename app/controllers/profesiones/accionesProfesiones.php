<?php
session_start();
include_once("../../conexion.php");
include_once("profesiones.php");

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $profesionController = new ProfesionController($pdo);
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'agregar':
      $nombre = $_POST['nombre'] ?? '';
      if (empty($nombre)) {
        $response = ['success' => false, 'message' => 'El nombre de la profesión es requerido'];
      } else {
        $response = $profesionController->agregarProfesion($nombre);
      }
      break;

    case 'actualizar':
      $id = $_POST['id'] ?? '';
      $nombre = $_POST['nombre'] ?? '';
      $estatus = $_POST['estatus'] ?? 1;

      if (empty($id) || empty($nombre)) {
        $response = ['success' => false, 'message' => 'Datos incompletos'];
      } else {
        $response = $profesionController->actualizarProfesion($id, $nombre, $estatus);
      }
      break;

    case 'obtener_todas':
      $profesiones = $profesionController->obtenerProfesiones();
      $response = ['success' => true, 'data' => $profesiones];
      break;

    default:
      $response = ['success' => false, 'message' => 'Acción no reconocida'];
      break;
  }
} catch (Exception $e) {
  $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
