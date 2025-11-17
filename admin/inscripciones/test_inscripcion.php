<?php
session_start();
header('Content-Type: application/json');

// Log para ver qué está llegando
error_log("DEBUG: Solicitud recibida - Método: " . $_SERVER['REQUEST_METHOD']);

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido. Se recibió: ' . $_SERVER['REQUEST_METHOD']);
  }

  // Verificar que lleguen los campos principales
  $camposEsperados = [
    'representante_existente',
    'primer_nombre_r',
    'primer_apellido_r',
    'cedula_r',
    'primer_nombre_e',
    'primer_apellido_e',
    'cedula_e'
  ];

  $camposFaltantes = [];
  foreach ($camposEsperados as $campo) {
    if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
      $camposFaltantes[] = $campo;
    }
  }

  if (!empty($camposFaltantes)) {
    throw new Exception('Campos faltantes: ' . implode(', ', $camposFaltantes));
  }

  // Respuesta exitosa con datos recibidos
  echo json_encode([
    'success' => true,
    'message' => '✅ Formulario recibido correctamente',
    'data_summary' => [
      'total_campos' => count($_POST),
      'representante' => [
        'nombre' => $_POST['primer_nombre_r'] . ' ' . $_POST['primer_apellido_r'],
        'cedula' => $_POST['cedula_r']
      ],
      'estudiante' => [
        'nombre' => $_POST['primer_nombre_e'] . ' ' . $_POST['primer_apellido_e'],
        'cedula' => $_POST['cedula_e']
      ],
      'representante_existente' => $_POST['representante_existente']
    ]
  ]);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => '❌ Error: ' . $e->getMessage(),
    'post_data' => $_POST // Para debug
  ]);
}
