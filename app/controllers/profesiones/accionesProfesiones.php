<?php
session_start();
header('Content-Type: application/json');

include_once '/xampp/htdocs/final/app/conexion.php';
include_once("profesiones.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $profesionController = new ProfesionController($pdo);

  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'agregar':
      $nombre = trim($_POST['nombre'] ?? '');

      if (empty($nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre de la profesión es requerido']);
        exit;
      }

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe
      $sql = "SELECT COUNT(*) as count FROM profesiones WHERE profesion = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una profesión con ese nombre']);
        exit;
      }

      $id = $profesionController->crearProfesion($nombre);
      if ($id) {
        echo json_encode(['success' => true, 'message' => 'Profesión creada exitosamente', 'id' => $id]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear profesión']);
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

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe (para otro registro)
      $sql = "SELECT COUNT(*) as count FROM profesiones WHERE profesion = ? AND id_profesion != ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre, $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otra profesión con ese nombre']);
        exit;
      }

      // Actualizar primero el nombre
      $nombre_actualizado = $profesionController->actualizarProfesion($id, $nombre);

      // Luego cambiar el estatus
      $estatus_actualizado = $profesionController->cambiarEstatusProfesion($id, $estatus);

      if ($nombre_actualizado || $estatus_actualizado) {
        $mensaje = 'Profesión actualizada exitosamente';

        // Si se desactivó y está en uso, mostrar mensaje informativo
        if ($estatus == 0) {
          $en_uso = $profesionController->profesionEnUso($id);
          if ($en_uso) {
            $conteo = $profesionController->obtenerConteoUsosProfesion($id);
            $mensaje = "Profesión desactivada exitosamente. NOTA: Está en uso por $conteo registro(s), pero no aparecerá en nuevos registros.";
          }
        }

        echo json_encode([
          'success' => true,
          'message' => $mensaje,
          'estatus' => $estatus
        ]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar profesión']);
      }
      break;

    case 'obtener_todas':
      $profesiones = $profesionController->obtenerTodasLasProfesiones();

      // Agregar información de uso a cada profesión
      foreach ($profesiones as &$profesion) {
        $profesion['en_uso'] = $profesionController->profesionEnUso($profesion['id_profesion']);
        $profesion['conteo_usos'] = $profesionController->obtenerConteoUsosProfesion($profesion['id_profesion']);
      }

      echo json_encode(['success' => true, 'data' => $profesiones]);
      break;

    case 'verificar_uso':
      $id = $_POST['id'] ?? '';
      if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID requerido']);
        exit;
      }

      $en_uso = $profesionController->profesionEnUso($id);
      $conteo = $profesionController->obtenerConteoUsosProfesion($id);

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

      $profesion = $profesionController->obtenerProfesionPorId($id);
      echo json_encode(['success' => true, 'data' => $profesion]);
      break;

    default:
      echo json_encode(['success' => false, 'message' => 'Acción no válida']);
      break;
  }
} catch (Exception $e) {
  error_log("Error en accionesProfesiones.php: " . $e->getMessage());

  echo json_encode([
    'success' => false,
    'message' => 'Error del servidor: ' . $e->getMessage()
  ]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
