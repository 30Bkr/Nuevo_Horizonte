<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // NUEVA VERSIÓN - usa id_nivel_seccion directamente
  if (isset($_POST['id_nivel_seccion']) && !empty($_POST['id_nivel_seccion'])) {
    // En la parte donde procesas la solicitud, cambia esto:
    if (isset($_POST['id_nivel_seccion']) && !empty($_POST['id_nivel_seccion'])) {
      // Método nuevo con id_nivel_seccion
      $id_nivel_seccion = $_POST['id_nivel_seccion'];
      $id_periodo = $_POST['id_periodo'];

      if (empty($id_periodo)) {
        throw new Exception("Parámetro requerido: id_periodo");
      }

      $conexion = new Conexion();
      $pdo = $conexion->conectar();
      $cuposController = new CuposController($pdo);

      // Verificar disponibilidad usando el nuevo método
      $disponibilidad = $cuposController->obtenerDisponibilidad($id_nivel_seccion, $id_periodo);

      echo json_encode($disponibilidad);
    }
  } else if (isset($_POST['id_nivel']) && isset($_POST['id_seccion'])) {
    // MÉTODO DE COMPATIBILIDAD - para código existente que aún usa id_nivel e id_seccion
    $required_params = ['id_nivel', 'id_seccion', 'id_periodo'];
    foreach ($required_params as $param) {
      if (empty($_POST[$param])) {
        throw new Exception("Parámetro requerido: {$param}");
      }
    }

    $id_nivel = $_POST['id_nivel'];
    $id_seccion = $_POST['id_seccion'];
    $id_periodo = $_POST['id_periodo'];

    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    $cuposController = new CuposController($pdo);

    // Verificar disponibilidad usando método de compatibilidad
    $disponibilidad = $cuposController->obtenerDisponibilidadPorSeparado($id_nivel, $id_seccion, $id_periodo);

    echo json_encode($disponibilidad);
  } else {
    throw new Exception('Parámetros insuficientes. Se requiere id_nivel_seccion o la combinación id_nivel e id_seccion');
  }
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage(),
    'disponible' => false
  ]);
}
