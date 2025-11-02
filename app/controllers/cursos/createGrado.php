<?php
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/global/utils.php");

$logFile = '/xampp/htdocs/final/debug.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Iniciando\n", FILE_APPEND);

// ✅ HEADERS JSON PRIMERO
header('Content-Type: application/json');

// ✅ DEBUG: Verificar si los archivos se incluyen
error_log("=== INICIANDO createGrado.php ===");
error_log("Datos POST recibidos: " . print_r($_POST, true));

try {
  // ✅ VALIDAR MÉTODO POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // ✅ VALIDAR CAMPOS REQUERIDOS
  $camposRequeridos = ['grado', 'nom_seccion', 'turno', 'capacidad'];
  foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
      throw new Exception("El campo $campo es requerido");
    }
  }

  // ✅ INSTANCIAR EL OBJETO
  error_log("Instanciando Cursos...");
  $objCurso = new Cursos();

  if (!$objCurso) {
    throw new Exception('No se pudo instanciar la clase Cursos');
  }

  // ✅ ASIGNAR VALORES
  $objCurso->grado       = (int) $_POST['grado'];
  $objCurso->nom_seccion = trim($_POST['nom_seccion']);
  $objCurso->turno       = trim($_POST['turno']);
  $objCurso->capacidad   = (int) $_POST['capacidad'];

  error_log("Valores asignados - Grado: {$objCurso->grado}, Sección: {$objCurso->nom_seccion}, Turno: {$objCurso->turno}, Capacidad: {$objCurso->capacidad}");

  // ✅ EJECUTAR CREACIÓN
  error_log("Llamando a crearGrado()...");
  $resultado = $objCurso->crearGrado();

  error_log("Resultado de crearGrado(): " . ($resultado ? $resultado : 'FALSE'));

  if ($resultado !== false && $resultado !== null) {
    $json_data = [
      'success' => true,
      'message' => 'Grado creado exitosamente',
      'data' => [
        'id_creado' => $resultado,
        'grado' => $objCurso->grado,
        'nom_seccion' => $objCurso->nom_seccion,
        'turno' => $objCurso->turno,
        'capacidad' => $objCurso->capacidad
      ]
    ];
    error_log("✅ ÉXITO: Grado creado con ID: " . $resultado);
  } else {
    throw new Exception('No se pudo crear el grado - método crearGrado() retornó false');
  }
} catch (Exception $e) {
  error_log("❌ ERROR en createGrado.php: " . $e->getMessage());
  $json_data = [
    'success' => false,
    'message' => 'Error: ' . $e->getMessage(),
    'data' => []
  ];
  http_response_code(400);
}

error_log("Respuesta JSON: " . json_encode($json_data));
echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
exit;
?>

<!-- <?php
      include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
      include_once("/xampp/htdocs/final/global/utils.php");

      // ✅ HEADERS JSON PRIMERO
      header('Content-Type: application/json');

      try {
        // ✅ VALIDAR MÉTODO POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
          throw new Exception('Método no permitido');
        }

        // ✅ VALIDAR CAMPOS REQUERIDOS
        $camposRequeridos = ['grado', 'nom_seccion', 'turno', 'capacidad'];
        foreach ($camposRequeridos as $campo) {
          if (empty($_POST[$campo])) {
            throw new Exception("El campo $campo es requerido");
          }
        }
        $objCurso = new Cursos();
        $objCurso->grado       = (int) $_POST['grado'];
        $objCurso->nom_seccion = $_POST['nom_seccion'];
        $objCurso->turno       = $_POST['turno'];
        $objCurso->capacidad   = $_POST['capacidad'];

        // ✅ USAR EL RESULTADO
        $resultado = $objCurso->crearGrado();

        if ($resultado !== false) {
          $json_data = [
            'success' => true,
            'message' => 'Grado creado exitosamente',
            'data' => [
              'id_creado' => $resultado,
              'grado' => $objCurso->grado,
              'nom_seccion' => $objCurso->nom_seccion,
              'turno' => $objCurso->turno,
              'capacidad' => $objCurso->capacidad
            ]
          ];
        } else {
          throw new Exception('No se pudo crear el grado (ver logs del servidor)');
        }
      } catch (Exception $e) {
        $json_data = [
          'success' => false,
          'message' => $e->getMessage(),
          'data' => []
        ];
      }

      echo json_encode($json_data, JSON_UNESCAPED_UNICODE);
      exit;
      ?> -->