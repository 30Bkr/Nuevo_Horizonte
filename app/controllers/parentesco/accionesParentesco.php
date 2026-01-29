<?php
// admin/configuraciones/configuracion/accionesParentesco.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Establecer cabeceras JSON primero
header('Content-Type: application/json; charset=utf-8');

// Limpiar buffer de salida
while (ob_get_level()) {
  ob_end_clean();
}

include_once '/xampp/htdocs/final/app/conexion.php';
include_once("parentescoController.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $parentescoController = new ParentescoController($pdo);

  $action = $_POST['action'] ?? '';

  if (empty($action)) {
    throw new Exception('Acción no especificada');
  }

  switch ($action) {
    case 'agregar':
      $nombre = trim($_POST['nombre'] ?? '');

      if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre del parentesco es requerido']);
        exit;
      }

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe
      $sql = "SELECT COUNT(*) as count FROM parentesco WHERE parentesco = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode([
          'success' => false,
          'message' => 'Ya existe un parentesco con ese nombre',
          'duplicate' => true
        ]);
        exit;
      }

      $result = $parentescoController->agregarParentesco($nombre);
      echo json_encode($result);
      break;

    case 'actualizar':
      $id = $_POST['id'] ?? '';
      $nombre = trim($_POST['nombre'] ?? '');
      $estatus = $_POST['estatus'] ?? 1;

      if (empty($id) || empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
      }

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe (para otro registro)
      $sql = "SELECT COUNT(*) as count FROM parentesco WHERE parentesco = ? AND id_parentesco != ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre, $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode([
          'success' => false,
          'message' => 'Ya existe otro parentesco con ese nombre',
          'duplicate' => true
        ]);
        exit;
      }

      // ACTUALIZACIÓN IMPORTANTE: Permitir desactivar incluso si está en uso
      $result = $parentescoController->actualizarParentesco($id, $nombre, $estatus);

      // Si se desactivó y está en uso, agregar mensaje informativo
      if ($result['success'] && $estatus == 0) {
        $en_uso = $parentescoController->parentescoEnUso($id);
        if ($en_uso) {
          $conteo = $parentescoController->obtenerConteoUsosParentesco($id);
          $result['message'] = "Parentesco desactivado exitosamente. NOTA: Está en uso en $conteo relación(es), pero no aparecerá en nuevos registros.";
        }
      }

      echo json_encode($result);
      break;

    case 'obtener_todos':
      $parentescos = $parentescoController->obtenerTodosLosParentescos();

      // Agregar información de uso a cada parentesco
      foreach ($parentescos as &$parentesco) {
        $parentesco['en_uso'] = $parentescoController->parentescoEnUso($parentesco['id_parentesco']);
        $parentesco['conteo_usos'] = $parentescoController->obtenerConteoUsosParentesco($parentesco['id_parentesco']);
      }

      echo json_encode(['success' => true, 'data' => $parentescos]);
      break;

    case 'verificar_uso':
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
        exit;
      }

      $en_uso = $parentescoController->parentescoEnUso($id);
      $conteo = $parentescoController->obtenerConteoUsosParentesco($id);
      echo json_encode([
        'success' => true,
        'en_uso' => $en_uso,
        'conteo' => $conteo
      ]);
      break;

    case 'obtener_por_id':
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
      }

      $sql = "SELECT * FROM parentesco WHERE id_parentesco = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$id]);
      $parentesco = $stmt->fetch(PDO::FETCH_ASSOC);
      echo json_encode(['success' => true, 'data' => $parentesco]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Acción no válida']);
      break;
  }
} catch (Exception $e) {
  // Log del error para debug
  error_log("Error en accionesParentesco.php: " . $e->getMessage());

  echo json_encode([
    'success' => false,
    'message' => 'Error del servidor: ' . $e->getMessage()
  ]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
