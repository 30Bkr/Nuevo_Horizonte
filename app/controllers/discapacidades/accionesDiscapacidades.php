<?php
session_start();
header('Content-Type: application/json');

include_once("../../../conexion.php");
include_once("discapacidades.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $discapacidadController = new DiscapacidadController($pdo);

  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'agregar':
      $nombre = trim($_POST['nombre'] ?? '');

      if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la discapacidad es requerido']);
        exit;
      }

      $id = $discapacidadController->crearDiscapacidad($nombre);
      if ($id) {
        echo json_encode(['success' => true, 'message' => 'Discapacidad creada exitosamente', 'id' => $id]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear discapacidad']);
      }
      break;

    case 'actualizar':
      $id = $_POST['id'] ?? '';
      $nombre = trim($_POST['nombre'] ?? '');
      $estatus = $_POST['estatus'] ?? 1;

      if (empty($id) || empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
      }

      $success = $discapacidadController->cambiarEstatusDiscapacidad($id, $estatus);
      if ($success) {
        // También actualizamos el nombre si es necesario
        $discapacidadController->actualizarDiscapacidad($id, $nombre);
        echo json_encode(['success' => true, 'message' => 'Discapacidad actualizada exitosamente']);
      } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar discapacidad']);
      }
      break;

    case 'obtener_todas':
      $discapacidades = $discapacidadController->obtenerTodasLasDiscapacidades();
      echo json_encode(['success' => true, 'data' => $discapacidades]);
      break;

    case 'obtener_por_id':
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
      }

      $discapacidad = $discapacidadController->obtenerDiscapacidadPorId($id);
      echo json_encode(['success' => true, 'data' => $discapacidad]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Acción no válida']);
      break;
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
