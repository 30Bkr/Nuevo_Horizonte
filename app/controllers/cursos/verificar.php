<?php
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");

header('Content-Type: application/json');

// ✅ DEBUG INICIAL
error_log("=== INICIANDO verificar.php ===");
error_log("Datos POST: " . print_r($_POST, true));

try {
  $objCurso = new Cursos();

  // Validar campos
  if (empty($_POST['grado']) || empty($_POST['nom_seccion'])) {
    throw new Exception("Grado y sección son requeridos");
  }

  $grado = trim($_POST['grado']);
  $seccion = trim($_POST['nom_seccion']);

  // ✅ DEBUG: Valores que se van a verificar
  error_log("🔍 Verificando combinación: Grado='$grado', Sección='$seccion'");

  // Verificar si ya existe
  $existe = $objCurso->verificar($grado, $seccion);

  // ✅ DEBUG: Resultado de la verificación
  error_log("📊 Resultado de verificar('$grado', '$seccion'): " . ($existe ? 'TRUE (EXISTE)' : 'FALSE (NO EXISTE)'));

  if ($existe) {
    // Ya existe - NO podemos registrar
    $json_data = [
      'success' => false,
      'message' => "Ya existe un curso con el grado '$grado' y sección '$seccion'",
      'data' => [
        'existe' => true,
        'grado' => $grado,
        'nom_seccion' => $seccion
      ]
    ];
    error_log("❌ VERIFICACIÓN: NO se puede registrar - Ya existe");
  } else {
    // No existe - SÍ podemos registrar
    $json_data = [
      'success' => true,
      'message' => "No existe duplicado, puede proceder con el registro",
      'data' => [
        'existe' => false,
        'grado' => $grado,
        'nom_seccion' => $seccion
      ]
    ];
    error_log("✅ VERIFICACIÓN: SÍ se puede registrar - No existe duplicado");
  }
} catch (Exception $e) {
  error_log("❌ ERROR en verificar.php: " . $e->getMessage());
  $json_data = [
    'success' => false,
    'message' => 'Error: ' . $e->getMessage(),
    'data' => []
  ];
}

error_log("📨 Respuesta JSON: " . json_encode($json_data));
echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
exit;
?>

<!-- <?php
      include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
      include_once("/xampp/htdocs/final/global/utils.php");
      header('Content-Type: application/json');

      // try {
      //   //code...

      //   $objCurso = new Cursos();
      //   $camposRequeridos = ['grado', 'nom_seccion', 'turno', 'capacidad'];
      //   foreach ($camposRequeridos as $campo) {
      //     if (empty($_POST[$campo])) {
      //       throw new Exception("El campo $campo es requerido");
      //     }
      //   }

      //   $objCurso->grado          = $_POST['grado'];
      //   $objCurso->nom_seccion    = $_POST['nom_seccion'];
      //   $objCurso->turno          = $_POST['turno'];
      //   $objCurso->capacidad      = $_POST['capacidad'];
      //   $curso = $objCurso->verificar($_POST['grado'], $_POST['nom_seccion']);
      //   if (empty($curso)) {
      //     $json_data = [
      //       'success' => true,
      //       'message' => 'No existe registro duplicado, puede proceder con el registro',
      //       'data' => [
      //         'grado' => $objCurso->grado,
      //         'turno' => $objCurso->turno,
      //         'capacidad' => $objCurso->capacidad,
      //         'nom_seccion' => $objCurso->nom_seccion,
      //         'existe' => false
      //       ]
      //     ];
      //   } else {
      //     // SÍ existe - no podemos registrar
      //     $json_data = [
      //       'success' => false,
      //       'message' => 'Ya existe un registro con este grado y sección',
      //       'data' => [
      //         'grado' => $objCurso->grado,
      //         'turno' => $objCurso->turno,
      //         'capacidad' => $objCurso->capacidad,
      //         'nom_seccion' => $objCurso->nom_seccion,
      //         'existe' => true,
      //         'registro_existente' => $cursoExistente // Opcional: enviar datos del existente
      //       ]
      //     ];
      //   }
      // } catch (Exception $e) {
      //   $json_data = [
      //     'success' => false,
      //     'message' => 'Error: ' . $e->getMessage(),
      //     'data' => []
      //   ];
      // }

      // echo json_encode($json_data);

      try {
        $objCurso = new Cursos();

        // Validar campos
        if (empty($_POST['grado']) || empty($_POST['nom_seccion'])) {
          throw new Exception("Grado y sección son requeridos");
        }

        $grado = $_POST['grado'];
        $seccion = $_POST['nom_seccion'];

        // Verificar si ya existe (usando la versión optimizada)
        $existe = $objCurso->verificar($grado, $seccion);

        if ($existe) {
          // Ya existe - NO podemos registrar
          $json_data = [
            'success' => false,
            'message' => "Ya existe un curso con el grado '$grado' y sección '$seccion'",
            'data' => [
              'existe' => true,
              'grado' => $grado,
              'nom_seccion' => $seccion
            ]
          ];
        } else {
          // No existe - SÍ podemos registrar
          $json_data = [
            'success' => true,
            'message' => "No existe duplicado, puede proceder con el registro",
            'data' => [
              'existe' => false,
              'grado' => $grado,
              'nom_seccion' => $seccion
            ]
          ];
        }
      } catch (Exception $e) {
        $json_data = [
          'success' => false,
          'message' => 'Error: ' . $e->getMessage(),
          'data' => []
        ];
      }

      echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
      ?> -->