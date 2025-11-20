<?php
// ELIMINAR cualquier espacio o salto de línea ANTES de esta línea
header('Content-Type: application/json');

// Incluir archivos - verificar que no tengan espacios al inicio/final
ob_start(); // Iniciar buffer de salida para capturar cualquier output no deseado
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");


try {
  // Verificar que sea POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // Validar ID
  if (!isset($_POST['id'])) {
    throw new Exception('ID de representante no proporcionado');
  }

  $id_representante = filter_var($_POST['id'], FILTER_VALIDATE_INT);
  if ($id_representante === false || $id_representante < 0) {
    throw new Exception('ID de representante inválido');
  }

  // Conectar y contar estudiantes
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $representanteController = new RepresentanteController($pdo);
  $cantidadDeAlumnos = $representanteController->contarEstudiantesPorId($id_representante);

  // Limpiar cualquier output que haya podido generarse
  ob_clean();

  // Enviar respuesta JSON
  echo json_encode([
    'success' => true,
    'total_estudiantes' => $cantidadDeAlumnos
  ]);
} catch (Exception $e) {
  // Limpiar buffer antes del error
  ob_clean();
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'error' => $e->getMessage()
  ]);
} finally {
  if (isset($conexion)) {
    $conexion->desconectar();
  }
  ob_end_flush(); // Limpiar y enviar buffer
}
