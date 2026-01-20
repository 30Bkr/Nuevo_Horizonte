<?php
session_start();
header('Content-Type: application/json');

include_once '/xampp/htdocs/final/app/conexion.php';

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

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe
      $sql = "SELECT COUNT(*) as count FROM discapacidades WHERE nom_discapacidad = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una discapacidad con ese nombre']);
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

      // Validar longitud mínima
      if (strlen($nombre) < 3) {
        echo json_encode(['success' => false, 'message' => 'El nombre debe tener al menos 3 caracteres']);
        exit;
      }

      // Verificar si ya existe (para otro registro)
      $sql = "SELECT COUNT(*) as count FROM discapacidades WHERE nom_discapacidad = ? AND id_discapacidad != ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$nombre, $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Ya existe otra discapacidad con ese nombre']);
        exit;
      }

      // ACTUALIZACIÓN IMPORTANTE: Permitir desactivar incluso si está en uso
      // Primero actualizar el nombre
      $nombre_actualizado = $discapacidadController->actualizarDiscapacidad($id, $nombre);

      // Luego cambiar el estatus (ahora siempre se permite cambiar el estatus)
      $estatus_actualizado = $discapacidadController->cambiarEstatusDiscapacidad($id, $estatus);

      if ($nombre_actualizado || $estatus_actualizado) {
        $mensaje = 'Discapacidad actualizada exitosamente';

        // Si se desactivó y está en uso, mostrar mensaje informativo
        if ($estatus == 0) {
          $en_uso = $discapacidadController->discapacidadEnUso($id);
          if ($en_uso) {
            $conteo = $discapacidadController->obtenerConteoUsosDiscapacidad($id);
            $mensaje = "Discapacidad desactivada exitosamente. NOTA: Está en uso por $conteo estudiante(s), pero no aparecerá en nuevos registros.";
          }
        }

        echo json_encode([
          'success' => true,
          'message' => $mensaje,
          'estatus' => $estatus
        ]);
      } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar discapacidad']);
      }
      break;

    case 'obtener_todas':
      $discapacidades = $discapacidadController->obtenerTodasLasDiscapacidades();

      // Agregar información de uso a cada discapacidad
      foreach ($discapacidades as &$discapacidad) {
        $discapacidad['en_uso'] = $discapacidadController->discapacidadEnUso($discapacidad['id_discapacidad']);
        $discapacidad['conteo_usos'] = $discapacidadController->obtenerConteoUsosDiscapacidad($discapacidad['id_discapacidad']);
      }

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
  // Log del error para debug
  error_log("Error en accionesDiscapacidades.php: " . $e->getMessage());

  echo json_encode([
    'success' => false,
    'message' => 'Error del servidor: ' . $e->getMessage()
  ]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
