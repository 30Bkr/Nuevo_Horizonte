<?php
session_start();
header('Content-Type: application/json');

include_once '/xampp/htdocs/final/app/conexion.php';
include_once("patologias.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $patologiaController = new PatologiaController($pdo);

  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'agregar':
      $nombre = trim($_POST['nombre'] ?? '');

      if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la patología es requerido']);
        exit;
      }

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe
      $sql = "SELECT COUNT(*) as count FROM patologias WHERE nom_patologia = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una patología con ese nombre']);
        exit;
      }

      $result = $patologiaController->agregarPatologia($nombre);
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
      $sql = "SELECT COUNT(*) as count FROM patologias WHERE nom_patologia = ? AND id_patologia != ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre, $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otra patología con ese nombre']);
        exit;
      }

      // ACTUALIZACIÓN IMPORTANTE: Permitir desactivar incluso si está en uso
      $result = $patologiaController->actualizarPatologiaCompleta($id, $nombre, $estatus);

      // Si se desactivó y está en uso, agregar mensaje informativo
      if ($result['success'] && $estatus == 0) {
        $en_uso = $patologiaController->patologiaEnUso($id);
        if ($en_uso) {
          $conteo = $patologiaController->obtenerConteoUsosPatologia($id);
          $result['message'] = "Patología desactivada exitosamente. NOTA: Está en uso por $conteo estudiante(s), pero no aparecerá en nuevos registros.";
        }
      }

      echo json_encode($result);
      break;

    case 'obtener_todas':
      $patologias = $patologiaController->obtenerTodasLasPatologias();

      // Agregar información de uso a cada patología
      foreach ($patologias as &$patologia) {
        $patologia['en_uso'] = $patologiaController->patologiaEnUso($patologia['id_patologia']);
        $patologia['conteo_usos'] = $patologiaController->obtenerConteoUsosPatologia($patologia['id_patologia']);
      }

      echo json_encode(['success' => true, 'data' => $patologias]);
      break;

    case 'obtener_por_id':
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
      }

      $patologia = $patologiaController->obtenerPatologiaPorId($id);
      echo json_encode(['success' => true, 'data' => $patologia]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Acción no válida']);
      break;
  }
} catch (Exception $e) {
  // Log del error para debug
  error_log("Error en accionesPatologias.php: " . $e->getMessage());

  echo json_encode([
    'success' => false,
    'message' => 'Error del servidor: ' . $e->getMessage()
  ]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
