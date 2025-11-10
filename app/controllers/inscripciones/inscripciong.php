<?php
include_once("/xampp/htdocs/final/app/controllers/inscripciones/InscripcionController.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    // Obtener datos JSON
    $input = file_get_contents('php://input');
    $datos = json_decode($input, true);

    if (!$datos) {
      throw new Exception('Datos de inscripción no válidos');
    }

    // Validar datos requeridos
    $errores = validarDatosInscripcion($datos);
    if (!empty($errores)) {
      echo json_encode([
        'success' => false,
        'error' => 'Datos incompletos: ' . implode(', ', $errores)
      ]);
      exit;
    }

    $inscripcionController = new InscripcionController();
    $resultado = $inscripcionController->procesarInscripcion($datos);

    echo json_encode($resultado);
  } catch (Exception $e) {
    error_log("Error en inscripciong.php: " . $e->getMessage());
    echo json_encode([
      'success' => false,
      'error' => 'Error al procesar la inscripción: ' . $e->getMessage()
    ]);
  }
} else {
  echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}

/**
 * Validar datos de inscripción
 */
function validarDatosInscripcion($datos)
{
  $errores = [];

  // Validar representante
  if (empty($datos['representante'])) {
    $errores[] = 'Datos del representante requeridos';
  } else {
    $rep = $datos['representante'];
    $campos_requeridos = ['primer_nombre', 'primer_apellido', 'cedula', 'correo', 'fecha_nac'];
    foreach ($campos_requeridos as $campo) {
      if (empty($rep[$campo])) {
        $errores[] = "Campo del representante: $campo";
      }
    }

    // Validar dirección
    if (empty($rep['direccion']['id_parroquia'])) {
      $errores[] = 'Parroquia del representante requerida';
    }
  }

  // Validar estudiantes
  if (empty($datos['estudiantes']) || !is_array($datos['estudiantes'])) {
    $errores[] = 'Al menos un estudiante requerido';
  } else {
    foreach ($datos['estudiantes'] as $index => $estudiante) {
      $num = $index + 1;
      $campos_requeridos = ['primer_nombre', 'primer_apellido', 'cedula', 'fecha_nac', 'sexo', 'nivel', 'seccion'];
      foreach ($campos_requeridos as $campo) {
        if (empty($estudiante[$campo])) {
          $errores[] = "Estudiante $num - campo: $campo";
        }
      }
    }
  }

  // Validar inscripción
  if (empty($datos['inscripcion']['periodo'])) {
    $errores[] = 'Periodo escolar requerido';
  }

  if (empty($datos['parentesco'])) {
    $errores[] = 'Parentesco requerido';
  }

  return $errores;
}
