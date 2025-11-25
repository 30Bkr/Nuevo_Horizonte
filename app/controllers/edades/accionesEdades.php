<?php
session_start();
include_once("../../conexion.php");
include_once("edades.php");

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $edadesController = new EdadesController($pdo);
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'obtener_configuracion':
      $configuracion = $edadesController->obtenerConfiguracionEdades();
      $response = [
        'success' => true,
        'data' => $configuracion
      ];
      break;

    case 'actualizar':
      $edadMin = intval($_POST['edad_min'] ?? 0);
      $edadMax = intval($_POST['edad_max'] ?? 0);

      if ($edadMin <= 0 || $edadMax <= 0) {
        $response = [
          'success' => false,
          'message' => 'Las edades deben ser números positivos.'
        ];
      } else {
        $response = $edadesController->actualizarConfiguracionEdades($edadMin, $edadMax);
      }
      break;

    case 'obtener_estadisticas':
      $estadisticas = $edadesController->obtenerEstadisticasEdades();
      $response = [
        'success' => true,
        'data' => $estadisticas
      ];
      break;

    case 'obtener_fuera_rango':
      $estudiantes = $edadesController->obtenerEstudiantesFueraRango();
      $response = [
        'success' => true,
        'data' => $estudiantes
      ];
      break;

    default:
      $response = ['success' => false, 'message' => 'Acción no reconocida'];
      break;
  }
} catch (Exception $e) {
  $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
