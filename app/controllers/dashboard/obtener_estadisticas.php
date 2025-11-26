<?php
session_start();
header('Content-Type: application/json');

include_once("../../conexion.php");
include_once("dashboard.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $dashboardController = new DashboardController($pdo);

  $action = $_GET['action'] ?? '';

  switch ($action) {
    case 'estadisticas_generales':
      $resultado = $dashboardController->obtenerEstadisticasGenerales();
      break;

    case 'inscripciones_mes':
      $mes = $_GET['mes'] ?? null;
      $anio = $_GET['anio'] ?? null;
      $resultado = $dashboardController->obtenerInscripcionesPorMes($mes, $anio);
      break;

    case 'meses_disponibles':
      $resultado = $dashboardController->obtenerMesesDisponibles();
      break;

    default:
      $resultado = [
        'success' => false,
        'message' => 'Acción no válida'
      ];
      break;
  }

  echo json_encode($resultado);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
