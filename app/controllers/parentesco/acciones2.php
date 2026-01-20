<?php
session_start();

// Buffer para evitar salidas no deseadas
ob_start();

// Incluir archivos necesarios
if (!file_exists("../../conexion.php")) {
  die(json_encode(['success' => false, 'message' => 'Error: No se encontró conexion.php']));
}

if (!file_exists("parentesco.php")) {
  die(json_encode(['success' => false, 'message' => 'Error: No se encontró parentesco.php']));
}

include_once("../../conexion.php");
include_once("parentesco.php");

// Configurar cabecera JSON
header('Content-Type: application/json; charset=utf-8');

// Verificar que se reciba una acción
if (!isset($_POST['action'])) {
  echo json_encode(['success' => false, 'message' => 'No se especificó acción']);
  exit;
}

try {
  // Crear conexión y controlador
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $controller = new ParentescoController($pdo);

  $action = $_POST['action'];
  $response = ['success' => false, 'message' => 'Acción no implementada'];

  // Procesar acciones
  switch ($action) {
    case 'agregar':
      $nombre = trim($_POST['nombre'] ?? '');
      if (empty($nombre)) {
        $response = ['success' => false, 'message' => 'El nombre del parentesco es requerido'];
      } else {
        $response = $controller->agregarParentesco($nombre);
      }
      break;

    case 'actualizar':
      $id = $_POST['id'] ?? 0;
      $nombre = trim($_POST['nombre'] ?? '');
      $estatus = $_POST['estatus'] ?? 1;

      if (empty($id) || empty($nombre)) {
        $response = ['success' => false, 'message' => 'Datos incompletos'];
      } else {
        $response = $controller->actualizarParentesco($id, $nombre, $estatus);
      }
      break;

    case 'obtener_todos':
      $parentescos = $controller->obtenerTodosLosParentescos();
      $response = ['success' => true, 'data' => $parentescos];
      break;

    case 'verificar_uso':
      $id = $_POST['id'] ?? 0;
      if (empty($id)) {
        $response = ['success' => false, 'message' => 'ID no proporcionado'];
      } else {
        $en_uso = $controller->parentescoEnUso($id);
        $conteo = $controller->obtenerConteoUsosParentesco($id);
        $response = [
          'success' => true,
          'en_uso' => $en_uso,
          'conteo' => $conteo
        ];
      }
      break;

    default:
      $response = ['success' => false, 'message' => 'Acción no reconocida: ' . $action];
      break;
  }
} catch (Exception $e) {
  $response = [
    'success' => false,
    'message' => 'Error interno: ' . $e->getMessage(),
    'trace' => $e->getTraceAsString()
  ];
}

// Limpiar buffer y enviar respuesta
ob_end_clean();
echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
