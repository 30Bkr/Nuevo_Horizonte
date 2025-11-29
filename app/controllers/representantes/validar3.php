<?php
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $representanteController = new RepresentanteController($pdo);

  $cedula = $_POST['cedula'] ?? '';

  if (empty($cedula)) {
    echo json_encode(['existe' => false, 'error' => 'Cédula no proporcionada']);
    exit;
  }

  // Buscar representante por cédula
  $representante = $representanteController->buscarPorCedula($cedula);

  if ($representante) {
    // Contar estudiantes del representante
    $estudiantes = $representanteController->obtenerEstudiantesPorRepresentante($representante['id_representante']);
    $total_estudiantes = count($estudiantes);

    echo json_encode([
      'existe' => true,
      'id_representante' => $representante['id_representante'],
      'id_direccion' => $representante['id_direccion'],
      'cedula' => $representante['cedula'],
      'primer_nombre' => $representante['primer_nombre'],
      'segundo_nombre' => $representante['segundo_nombre'],
      'primer_apellido' => $representante['primer_apellido'],
      'segundo_apellido' => $representante['segundo_apellido'],
      'correo' => $representante['correo'],
      'telefono' => $representante['telefono'],
      'telefono_hab' => $representante['telefono_hab'],
      'fecha_nac' => $representante['fecha_nac'],
      'lugar_nac' => $representante['lugar_nac'],
      'sexo' => $representante['sexo'],
      'nacionalidad' => $representante['nacionalidad'],
      'profesion' => $representante['id_profesion'],
      'ocupacion' => $representante['ocupacion'],
      'lugar_trabajo' => $representante['lugar_trabajo'],
      'id_estado' => $representante['id_estado'],
      'id_municipio' => $representante['id_municipio'],
      'id_parroquia' => $representante['id_parroquia'],
      'direccion' => $representante['direccion'],
      'calle' => $representante['calle'],
      'casa' => $representante['casa'],
      'nombre_completo' => $representante['primer_nombre'] . ' ' . ($representante['segundo_nombre'] ? $representante['segundo_nombre'] . ' ' : '') . $representante['primer_apellido'] . ' ' . ($representante['segundo_apellido'] ? $representante['segundo_apellido'] : ''),
      'total_estudiantes' => $total_estudiantes
    ]);
  } else {
    echo json_encode(['existe' => false]);
  }
} catch (Exception $e) {
  echo json_encode(['existe' => false, 'error' => $e->getMessage()]);
}
