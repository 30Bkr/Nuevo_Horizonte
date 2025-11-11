<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir las clases necesarias
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");

// Desactivar visualización de errores para producción, pero los capturaremos
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
  // Verificar que la solicitud sea POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // Log para debugging (puedes quitar esto después)
  error_log("Iniciando procesamiento de inscripción");

  // Conectar a la base de datos
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  // Iniciar transacción
  $pdo->beginTransaction();

  // Crear instancias de los controladores
  $ubicacionController = new UbicacionController($pdo);
  $personaController = new PersonaController($pdo);
  $estudianteController = new EstudianteController($pdo);
  $representanteController = new RepresentanteController($pdo);
  $inscripcionController = new InscripcionController($pdo);

  // ========== VALIDACIONES BÁSICAS ==========
  $camposRequeridos = [
    'primer_nombre_r',
    'primer_apellido_r',
    'cedula_r',
    'correo_r',
    'telefono_r',
    'telefono_hab_r',
    'fecha_nac_r',
    'lugar_nac_r',
    'sexo_r',
    'nacionalidad_r',
    'ocupacion_r',
    'parentesco',
    'estado_r',
    'municipio_r',
    'parroquia_r',
    'direccion_r',
    'primer_nombre_e',
    'primer_apellido_e',
    'cedula_e',
    'fecha_nac_e',
    'lugar_nac_e',
    'sexo_e',
    'nacionalidad_e',
    'id_periodo',
    'id_nivel',
    'id_seccion'
  ];

  foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
      throw new Exception("El campo $campo es requerido");
    }
  }

  // ========== PROCESAR REPRESENTANTE ==========
  $id_representante = null;
  $representante_existente = $_POST['representante_existente'] ?? '0';

  if ($representante_existente === '1') {
    // Usar representante existente
    $id_representante = $_POST['id_representante_existente'];
    if (empty($id_representante)) {
      throw new Exception('ID de representante existente no proporcionado');
    }
  } else {
    // ========== CREAR NUEVA DIRECCIÓN DEL REPRESENTANTE ==========
    $datosDireccionRepresentante = [
      'id_parroquia' => $_POST['parroquia_r'],
      'direccion' => $_POST['direccion_r'],
      'calle' => $_POST['calle_r'] ?? '',
      'casa' => $_POST['casa_r'] ?? ''
    ];

    error_log("Creando dirección del representante");
    $id_direccion_representante = $ubicacionController->crearDireccion($datosDireccionRepresentante);
    error_log("Dirección creada con ID: " . $id_direccion_representante);

    // ========== CREAR PERSONA REPRESENTANTE ==========
    $datosPersonaRepresentante = [
      'id_direccion' => $id_direccion_representante,
      'primer_nombre' => $_POST['primer_nombre_r'],
      'segundo_nombre' => $_POST['segundo_nombre_r'] ?? '',
      'primer_apellido' => $_POST['primer_apellido_r'],
      'segundo_apellido' => $_POST['segundo_apellido_r'] ?? '',
      'cedula' => $_POST['cedula_r'],
      'telefono' => $_POST['telefono_r'],
      'telefono_hab' => $_POST['telefono_hab_r'],
      'correo' => $_POST['correo_r'],
      'lugar_nac' => $_POST['lugar_nac_r'],
      'fecha_nac' => $_POST['fecha_nac_r'],
      'sexo' => $_POST['sexo_r'],
      'nacionalidad' => $_POST['nacionalidad_r']
    ];

    error_log("Creando persona representante");
    $id_persona_representante = $personaController->crearPersona($datosPersonaRepresentante);
    error_log("Persona representante creada con ID: " . $id_persona_representante);

    // ========== CREAR REPRESENTANTE ==========
    $datosRepresentante = [
      'profesion' => $_POST['profesion_r'] ?? '',
      'ocupacion' => $_POST['ocupacion_r'],
      'lugar_trabajo' => $_POST['lugar_trabajo_r'] ?? ''
    ];

    error_log("Creando representante");
    $id_representante = $representanteController->crearRepresentante($id_persona_representante, $datosRepresentante);
    error_log("Representante creado con ID: " . $id_representante);
  }

  // ========== CREAR DIRECCIÓN DEL ESTUDIANTE ==========
  $datosDireccionEstudiante = [
    'id_parroquia' => $_POST['parroquia_r'],
    'direccion' => $_POST['direccion_r'],
    'calle' => $_POST['calle_r'] ?? '',
    'casa' => $_POST['casa_r'] ?? ''
  ];

  error_log("Creando dirección del estudiante");
  $id_direccion_estudiante = $ubicacionController->crearDireccion($datosDireccionEstudiante);
  error_log("Dirección estudiante creada con ID: " . $id_direccion_estudiante);

  // ========== CREAR PERSONA ESTUDIANTE ==========
  $datosPersonaEstudiante = [
    'id_direccion' => $id_direccion_estudiante,
    'primer_nombre' => $_POST['primer_nombre_e'],
    'segundo_nombre' => $_POST['segundo_nombre_e'] ?? '',
    'primer_apellido' => $_POST['primer_apellido_e'],
    'segundo_apellido' => $_POST['segundo_apellido_e'] ?? '',
    'cedula' => $_POST['cedula_e'],
    'telefono' => $_POST['telefono_e'] ?? '',
    'telefono_hab' => $_POST['telefono_hab_r'],
    'correo' => $_POST['correo_e'] ?? '',
    'lugar_nac' => $_POST['lugar_nac_e'],
    'fecha_nac' => $_POST['fecha_nac_e'],
    'sexo' => $_POST['sexo_e'],
    'nacionalidad' => $_POST['nacionalidad_e']
  ];

  error_log("Creando persona estudiante");
  $id_persona_estudiante = $personaController->crearPersona($datosPersonaEstudiante);
  error_log("Persona estudiante creada con ID: " . $id_persona_estudiante);

  // ========== CREAR ESTUDIANTE ==========
  error_log("Creando estudiante");
  $id_estudiante = $estudianteController->crearEstudiante($id_persona_estudiante);
  error_log("Estudiante creado con ID: " . $id_estudiante);

  // ========== AGREGAR PATOLOGÍAS AL ESTUDIANTE ==========
  if (isset($_POST['patologias']) && is_array($_POST['patologias'])) {
    error_log("Agregando patologías: " . count($_POST['patologias']));
    foreach ($_POST['patologias'] as $id_patologia) {
      $estudianteController->agregarPatologia($id_estudiante, $id_patologia);
    }
  }

  // ========== CREAR RELACIÓN ESTUDIANTE-REPRESENTANTE ==========
  $parentesco = $_POST['parentesco'];
  error_log("Creando relación estudiante-representante");
  $representanteController->crearRelacionEstudianteRepresentante($id_estudiante, $id_representante, $parentesco);

  // ========== CREAR INSCRIPCIÓN ==========
  $datosInscripcion = [
    'id_estudiante' => $id_estudiante,
    'id_periodo' => $_POST['id_periodo'],
    'id_nivel' => $_POST['id_nivel'],
    'id_seccion' => $_POST['id_seccion'],
    'id_usuario' => $_SESSION['id_usuario'] ?? 1,
    'fecha_inscripcion' => date('Y-m-d'),
    'observaciones' => $_POST['observaciones'] ?? ''
  ];

  error_log("Creando inscripción");
  $id_inscripcion = $inscripcionController->crearInscripcion($datosInscripcion);
  error_log("Inscripción creada con ID: " . $id_inscripcion);

  // Confirmar transacción
  $pdo->commit();

  // Respuesta de éxito
  error_log("Inscripción completada exitosamente");
  echo json_encode([
    'success' => true,
    'message' => 'Inscripción realizada exitosamente',
    'id_inscripcion' => $id_inscripcion,
    'id_estudiante' => $id_estudiante
  ]);
} catch (Exception $e) {
  // Revertir transacción en caso de error
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
    error_log("Transacción revertida debido a error: " . $e->getMessage());
  }

  error_log("Error en inscripción: " . $e->getMessage());

  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'Error en la inscripción: ' . $e->getMessage()
  ]);
} finally {
  // Cerrar conexión si existe
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
