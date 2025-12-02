<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/profesiones/profesiones.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $profesionController = new ProfesionController($pdo);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
      case 'agregar':
        $nombre = $_POST['nombre'] ?? '';
        if (empty($nombre)) {
          echo json_encode(['success' => false, 'message' => 'El nombre de la profesión es requerido']);
          exit;
        }
        $result = $profesionController->agregarProfesion($nombre);
        echo json_encode($result);
        break;

      case 'actualizar':
        $id = $_POST['id'] ?? 0;
        $nombre = $_POST['nombre'] ?? '';
        $estatus = $_POST['estatus'] ?? 1;

        if (empty($nombre) || $id <= 0) {
          echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
          exit;
        }

        $result = $profesionController->actualizarProfesion($id, $nombre, $estatus);
        echo json_encode($result);
        break;

      case 'obtener_todas':
        $profesiones = $profesionController->obtenerTodasLasProfesiones();
        echo json_encode(['success' => true, 'data' => $profesiones]);
        break;

      case 'verificar_uso':
        $id = $_POST['id'] ?? 0;
        if ($id <= 0) {
          echo json_encode(['success' => false, 'en_uso' => false, 'conteo' => 0]);
          exit;
        }
        $en_uso = $profesionController->profesionEnUso($id);
        $conteo = $profesionController->obtenerConteoUsosProfesion($id);
        echo json_encode(['success' => true, 'en_uso' => $en_uso, 'conteo' => $conteo]);
        break;

      default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
    }
  } else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
