<?php
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/discapacidades/discapacidades.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    $discapacidadController = new DiscapacidadController($pdo);

    $id_estudiante = $_POST['id_estudiante'] ?? null;

    if ($id_estudiante) {
      $discapacidades = $discapacidadController->obtenerDiscapacidadesPorEstudiante($id_estudiante);

      echo json_encode([
        'success' => true,
        'discapacidades' => $discapacidades
      ]);
    } else {
      echo json_encode([
        'success' => false,
        'message' => 'ID de estudiante no proporcionado'
      ]);
    }
  } catch (Exception $e) {
    echo json_encode([
      'success' => false,
      'message' => 'Error: ' . $e->getMessage()
    ]);
  }
}
