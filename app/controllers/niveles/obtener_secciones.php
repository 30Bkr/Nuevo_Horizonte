<?php
session_start();
include_once("../../conexion.php");
include_once("niveles.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $nivelController = new NivelController($pdo);

  $id_nivel = $_POST['id_nivel'] ?? '';

  if (empty($id_nivel)) {
    echo json_encode([
      'success' => false,
      'message' => 'ID de nivel no proporcionado'
    ]);
    exit;
  }

  $secciones = $nivelController->obtenerSeccionesPorNivel($id_nivel);

  echo json_encode([
    'success' => true,
    'secciones' => $secciones
  ]);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
