<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
  // Aca verificamos que solo recibamos metodos POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  error_log("Iniciando procesamiento de inscripción");

  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $pdo->beginTransaction();

  $ubicacionController = new UbicacionController($pdo);
  $personaController = new PersonaController($pdo);
  $estudianteController = new EstudianteController($pdo);
  $representanteController = new RepresentanteController($pdo);
  $inscripcionController = new InscripcionController($pdo);

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

  // Aca sabemos si viven en el mismo lugar.
  $alumno_VCP = $_POST['juntos'] ?? '0';
  //  Aca recibimos informacion sobre si el representante esta inscrito y si lo esta guardamos su id para la inscripcion.
  $representante_existente = $_POST['representante_existente'] ?? '0';
  $id_representante = null;
  $direccionesRepre = null;

  if ($representante_existente === '1') {
    //Como ya el representante existe solo nos interesa guardar su id, para introducirlo en la inscripcion y en tal caso en la tabla de representante.
    $id_representante = $_POST['id_representante_existente'];
    if (empty($id_representante)) {
      throw new Exception('ID de representante existente no proporcionado');
    }
    $direccionesRepre = $_POST['id_direccion_repre'];
  } else {
    //Como el representante no existe, procedemos a introducir los datos del representante en las tablas correspondientes (peronas, representantes y direcciones)
    //  Guardamos todos los datos recibidos del formulario para proceder a guardarlos en la tabla de direcciones primero.
    $datosDireccionRepresentante = [
      'id_parroquia' => $_POST['parroquia_r'],
      'direccion' => $_POST['direccion_r'],
      'calle' => $_POST['calle_r'] ?? '',
      'casa' => $_POST['casa_r'] ?? ''
    ];

    error_log("Creando dirección del representante");
    $id_direccion_representante = $ubicacionController->crearDireccion($datosDireccionRepresentante);
    error_log("Dirección creada con ID: " . $id_direccion_representante);
    $direccionesRepre = $id_direccion_representante;
    //  Aqui hacemos introducimos en la tabla de personas la identificacion del representante
    //  Aqui hacemos introducimos en la tabla de personas la identificacion del representante
    //  Aqui hacemos introducimos en la tabla de personas la identificacion del representante
    //  Aqui hacemos introducimos en la tabla de personas la identificacion del representante
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

    //  Aqui guardamos toda la informacion del representante en la tabla de representante luego de guardarlo en personas
    //  Aqui guardamos toda la informacion del representante en la tabla de representante luego de guardarlo en personas
    //  Aqui guardamos toda la informacion del representante en la tabla de representante luego de guardarlo en personas
    //  Aqui guardamos toda la informacion del representante en la tabla de representante luego de guardarlo en personas
    //  Aqui guardamos toda la informacion del representante en la tabla de representante luego de guardarlo en personas
    $datosRepresentante = [
      'profesion' => $_POST['profesion_r'] ?? '',
      'ocupacion' => $_POST['ocupacion_r'],
      'lugar_trabajo' => $_POST['lugar_trabajo_r'] ?? ''
    ];

    error_log("Creando representante");
    $id_representante = $representanteController->crearRepresentante($id_persona_representante, $datosRepresentante);
    error_log("Representante creado con ID: " . $id_representante);
  }
  //Aca validamos si el estudiante vive o no con su representante.
  if ($alumno_VCP === '0') {
    //  Agregando en la tabla de direccion la direccion que esta previamente en el representante
    $datosDireccionEstudiante = [
      'id_parroquia' => $_POST['parroquia_e'],
      'direccion' => $_POST['direccion_e'],
      'calle' => $_POST['calle_e'] ?? '',
      'casa' => $_POST['casa_e'] ?? ''
    ];

    error_log("Creando dirección del estudiante");
    $id_direccion_estudiante = $ubicacionController->crearDireccion($datosDireccionEstudiante);
    error_log("Dirección estudiante creada con ID: " . $id_direccion_estudiante);
  }

  $direccion_alumno = ($alumno_VCP === '0') ? $id_direccion_estudiante : $direccionesRepre;
  //  Aqui estamos ingresando en la tabla de personas la informacion del estudiante
  $datosPersonaEstudiante = [
    'id_direccion' => $direccion_alumno,
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

  //  CREAR ESTUDIANTE 
  error_log("Creando estudiante");
  $id_estudiante = $estudianteController->crearEstudiante($id_persona_estudiante);
  error_log("Estudiante creado con ID: " . $id_estudiante);

  //  AGREGAR PATOLOGÍAS AL ESTUDIANTE 
  if (isset($_POST['patologias']) && is_array($_POST['patologias'])) {
    error_log("Agregando patologías: " . count($_POST['patologias']));
    foreach ($_POST['patologias'] as $id_patologia) {
      $estudianteController->agregarPatologia($id_estudiante, $id_patologia);
    }
  }

  //  CREAR RELACIÓN ESTUDIANTE-REPRESENTANTE 
  $parentesco = $_POST['parentesco'];
  error_log("Creando relación estudiante-representante");
  $representanteController->crearRelacionEstudianteRepresentante($id_estudiante, $id_representante, $parentesco);

  //  CREAR INSCRIPCIÓN 
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
    'message' => 'Error en la inscripción: ' . $e->getMessage() . $e->getLine()
  ]);
} finally {
  // Cerrar conexión si existe
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
