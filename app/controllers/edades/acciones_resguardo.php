<?php
// accionesEdades.php
session_start();
include_once("../../conexion.php");
include_once("edades.php");

$conexion = new Conexion();
$pdo = $conexion->conectar();
$edadesController = new EdadesController($pdo);

$response = ['success' => false, 'message' => 'Acción no válida'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $id_usuario = $_SESSION['usuario_id'] ?? 0;

  switch ($action) {
    case 'actualizar':
      if (isset($_POST['edad_min']) && isset($_POST['edad_max'])) {
        $edad_min = intval($_POST['edad_min']);
        $edad_max = intval($_POST['edad_max']);

        $resultado = $edadesController->actualizarConfiguracionEdades($edad_min, $edad_max, $id_usuario);
        $response = $resultado;
      } else {
        $response['message'] = 'Parámetros incompletos';
      }
      break;

    case 'obtener_configuracion':
      $configuracion = $edadesController->obtenerConfiguracionEdades();
      $response = [
        'success' => true,
        'data' => [
          'edad_min' => $configuracion['edad_min'] ?? 5,
          'edad_max' => $configuracion['edad_max'] ?? 18,
          'version' => $configuracion['version'] ?? 1
        ]
      ];
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

    case 'obtener_info_modificacion':
      $info = $edadesController->obtenerInfoUltimaModificacion();
      $response = [
        'success' => true,
        'data' => $info ?: []
      ];
      break;
  }
}

header('Content-Type: application/json');
echo json_encode($response);
