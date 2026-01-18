<?php
session_start();
include_once("../../conexion.php");
include_once("periodos.php");

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Acción no válida'];

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $periodoController = new PeriodoController($pdo);
  $action = $_POST['action'] ?? '';

  switch ($action) {
    case 'obtener_todos':
      $periodos = $periodoController->obtenerPeriodos();
      $periodoActivo = $periodoController->obtenerPeriodoActivo();
      $estadisticas = $periodoController->obtenerEstadisticasPeriodos();

      $response = [
        'success' => true,
        'data' => [
          'periodos' => $periodos,
          'periodo_activo' => $periodoActivo,
          'estadisticas' => $estadisticas
        ]
      ];
      break;

    case 'activar':
      $idPeriodo = $_POST['id_periodo'] ?? '';
      if (empty($idPeriodo)) {
        $response = ['success' => false, 'message' => 'ID de periodo no válido'];
      } else {
        $response = $periodoController->activarPeriodo($idPeriodo);
      }
      break;

    case 'crear':
      $descripcion = $_POST['descripcion'] ?? '';
      $fechaIni = $_POST['fecha_ini'] ?? '';
      $fechaFin = $_POST['fecha_fin'] ?? '';

      if (empty($descripcion) || empty($fechaIni) || empty($fechaFin)) {
        $response = ['success' => false, 'message' => 'Todos los campos son requeridos'];
      } else {
        $response = $periodoController->crearPeriodo($descripcion, $fechaIni, $fechaFin);
      }
      break;

    default:
      $response = ['success' => false, 'message' => 'Acción no reconocida'];
      break;
  }
} catch (Exception $e) {
  $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
