<?php
header('Content-Type: application/json');

// Incluir la conexión y la clase
require_once('../../conexion.php'); // Ajusta la ruta según tu estructura
require_once('globales.php');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  // Crear instancia del controlador
  $globalesController = new GlobalesController($pdo);

  // Obtener las edades
  $resultado = $globalesController->obtenerEdades();

  echo json_encode($resultado);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'error' => 'Error general: ' . $e->getMessage()
  ]);
}
