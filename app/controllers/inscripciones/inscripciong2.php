<?php
// LIMPIAR ABSOLUTAMENTE TODO BUFFER ANTES DE COMENZAR
while (ob_get_level()) {
  ob_end_clean();
}

// Iniciar sesión SIN output
@session_start();

header('Content-Type: application/json');

// Desactivar todo output posible
ini_set('display_errors', 0);
error_reporting(E_ALL);

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/personas/personas.php");
include_once("/xampp/htdocs/final/app/controllers/estudiantes/estudiantes.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");
include_once("/xampp/htdocs/final/app/controllers/patologias/patologias.php");
include_once("/xampp/htdocs/final/app/controllers/discapacidades/discapacidades.php"); // NUEVO INCLUDE
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");
error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
  // Verificar que solo recibamos métodos POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  error_log("Iniciando procesamiento de REinscripción");

  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $pdo->beginTransaction();

  $ubicacionController = new UbicacionController($pdo);
  $personaController = new PersonaController($pdo);
  $estudianteController = new EstudianteController($pdo);
  $representanteController = new RepresentanteController($pdo);
  $inscripcionController = new InscripcionController($pdo);
  $cuposController = new CuposController($pdo);

  $camposRequeridos = [
    'primer_nombre_r',
    'primer_apellido_r',
    'cedula_r',
    'correo_r',
    'telefono_r',
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

  // Variables para control de datos existentes
  $alumno_VCP = $_POST['juntos'] ?? '0';
  $representante_existente = $_POST['representante_existente'] ?? '0';
  $estudiante_existente = $_POST['estudiante_existente'] ?? '0';
  $tipo_persona = $_POST['tipo_persona'] ?? 'representante';

  $id_representante = null;
  $id_estudiante = null;
  $direccionesRepre = null;
  error_log("=== INICIANDO PROCESAMIENTO REPRESENTANTE ===");
  error_log("representante_existente: " . $representante_existente);
  error_log("tipo_persona: " . $tipo_persona);
  error_log("POST id_direccion_repre: " . ($_POST['id_direccion_repre'] ?? 'NO ENVIADO'));

  // ========== PROCESAMIENTO DEL REPRESENTANTE ==========
  if ($representante_existente === '1') {

    // VERIFICAR SI ES DOCENTE O REPRESENTANTE EXISTENTE
    if ($tipo_persona === 'docente') {
      error_log("=== PROCESANDO DOCENTE EXISTENTE ===");

      $id_persona_docente = $_POST['id_representante_existente_esc'];
      error_log("id_persona_docente: " . $id_persona_docente);

      // Crear nueva dirección para el representante (docente)
      $datosDireccionRepresentante = [
        'id_parroquia' => $_POST['parroquia_r'],
        'direccion' => $_POST['direccion_r'],
        'calle' => $_POST['calle_r'] ?? '',
        'casa' => $_POST['casa_r'] ?? ''
      ];

      error_log("Creando dirección con datos: " . print_r($datosDireccionRepresentante, true));

      $id_direccion_representante = $ubicacionController->crearDireccion($datosDireccionRepresentante);

      if (!$id_direccion_representante) {
        throw new Exception('Error: No se pudo crear la dirección para el docente');
      }

      error_log("Dirección creada con ID: " . $id_direccion_representante);
      $direccionesRepre = $id_direccion_representante;

      // Actualizar la persona del docente
      $datosPersonaRepresentante = [
        'id_persona' => $id_persona_docente,
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

      error_log("Actualizando persona con id_direccion: " . $id_direccion_representante);
      $personaController->actualizarPersona($datosPersonaRepresentante);

      // Crear representante
      $datosRepresentante = [
        'id_profesion' => $_POST['profesion_r'] ?? '',
        'ocupacion' => $_POST['ocupacion_r'],
        'lugar_trabajo' => $_POST['lugar_trabajo_r'] ?? ''
      ];

      $id_representante = $representanteController->crearRepresentante($id_persona_docente, $datosRepresentante);
      error_log("Representante creado con ID: " . $id_representante);
    } else {
      error_log("=== PROCESANDO REPRESENTANTE EXISTENTE ===");

      $id_representante = $_POST['id_representante_existente'];
      $direccionesRepre = $_POST['id_direccion_repre'];

      error_log("id_representante: " . $id_representante);
      error_log("direccionesRepre: " . $direccionesRepre);

      if (empty($id_representante)) {
        throw new Exception('ID de representante existente no proporcionado');
      }

      if (empty($direccionesRepre)) {
        throw new Exception('ID de dirección del representante no proporcionado');
      }

      // Obtener ID de persona del representante
      $sql_get_persona_repre = "SELECT id_persona FROM representantes WHERE id_representante = ?";
      $stmt_repre = $pdo->prepare($sql_get_persona_repre);
      $stmt_repre->execute([$id_representante]);
      $repre_data = $stmt_repre->fetch(PDO::FETCH_ASSOC);

      if (!$repre_data) {
        throw new Exception('Representante no encontrado');
      }

      $id_persona_representante = $repre_data['id_persona'];
      error_log("id_persona_representante: " . $id_persona_representante);

      // Actualizar datos de la persona representante
      $datosPersonaRepresentante = [
        'id_persona' => $id_persona_representante,
        'id_direccion' => $direccionesRepre,
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

      error_log("Actualizando persona representante con id_direccion: " . $direccionesRepre);
      $personaController->actualizarPersona($datosPersonaRepresentante);

      // Actualizar datos del representante
      $datosRepresentante = [
        'id_representante' => $id_representante,
        'id_profesion' => $_POST['profesion_r'] ?? null,
        'ocupacion' => $_POST['ocupacion_r'],
        'lugar_trabajo' => $_POST['lugar_trabajo_r'] ?? ''
      ];

      error_log("Actualizando representante");
      $representanteController->actualizarRepresentante($datosRepresentante);

      // Actualizar dirección del representante
      $datosDireccionRepresentante = [
        'id_direccion' => $direccionesRepre,
        'id_parroquia' => $_POST['parroquia_r'],
        'direccion' => $_POST['direccion_r'],
        'calle' => $_POST['calle_r'] ?? '',
        'casa' => $_POST['casa_r'] ?? ''
      ];

      error_log("Actualizando dirección del representante");
      $ubicacionController->actualizarDireccion($datosDireccionRepresentante);
    }
  } else {
    error_log("=== CREANDO NUEVO REPRESENTANTE ===");

    // Crear dirección del representante
    $datosDireccionRepresentante = [
      'id_parroquia' => $_POST['parroquia_r'],
      'direccion' => $_POST['direccion_r'],
      'calle' => $_POST['calle_r'] ?? '',
      'casa' => $_POST['casa_r'] ?? ''
    ];

    error_log("Creando dirección con datos: " . print_r($datosDireccionRepresentante, true));
    $id_direccion_representante = $ubicacionController->crearDireccion($datosDireccionRepresentante);

    if (!$id_direccion_representante) {
      throw new Exception('Error: No se pudo crear la dirección para el nuevo representante');
    }

    error_log("Dirección creada con ID: " . $id_direccion_representante);
    $direccionesRepre = $id_direccion_representante;

    // Crear persona representante
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

    // Crear representante
    $datosRepresentante = [
      'id_profesion' => $_POST['profesion_r'] ?? '',
      'ocupacion' => $_POST['ocupacion_r'],
      'lugar_trabajo' => $_POST['lugar_trabajo_r'] ?? ''
    ];

    error_log("Creando representante");
    $id_representante = $representanteController->crearRepresentante($id_persona_representante, $datosRepresentante);
    error_log("Representante creado con ID: " . $id_representante);
  }

  // ========== PROCESAMIENTO DEL ESTUDIANTE ==========
  if ($estudiante_existente === '1') {
    // Estudiante existe - actualizar datos
    $id_estudiante = $_POST['id_estudiante_existente'];

    if (empty($id_estudiante)) {
      throw new Exception('ID de estudiante existente no proporcionado');
    }

    error_log("Actualizando estudiante existente ID: " . $id_estudiante);

    // Obtener ID de persona del estudiante
    $sql_get_persona_est = "SELECT id_persona FROM estudiantes WHERE id_estudiante = ?";
    $stmt_est = $pdo->prepare($sql_get_persona_est);
    $stmt_est->execute([$id_estudiante]);
    $est_data = $stmt_est->fetch(PDO::FETCH_ASSOC);

    if (!$est_data) {
      throw new Exception('Estudiante no encontrado');
    }

    $id_persona_estudiante = $est_data['id_persona'];

    // Procesar dirección del estudiante
    $direccion_alumno = null;
    if ($alumno_VCP === '0') {
      // Estudiante tiene dirección diferente
      $id_direccion_estudiante = $_POST['id_direccion_est'] ?? null;

      if ($id_direccion_estudiante) {
        // Actualizar dirección existente
        $datosDireccionEstudiante = [
          'id_direccion' => $id_direccion_estudiante,
          'id_parroquia' => $_POST['parroquia_e'],
          'direccion' => $_POST['direccion_e'],
          'calle' => $_POST['calle_e'] ?? '',
          'casa' => $_POST['casa_e'] ?? ''
        ];
        error_log("Actualizando dirección del estudiante");
        $ubicacionController->actualizarDireccion($datosDireccionEstudiante);
        $direccion_alumno = $id_direccion_estudiante;
      } else {
        // Crear nueva dirección
        $datosDireccionEstudiante = [
          'id_parroquia' => $_POST['parroquia_e'],
          'direccion' => $_POST['direccion_e'],
          'calle' => $_POST['calle_e'] ?? '',
          'casa' => $_POST['casa_e'] ?? ''
        ];
        error_log("Creando nueva dirección del estudiante");
        $direccion_alumno = $ubicacionController->crearDireccion($datosDireccionEstudiante);
      }
    } else {
      // Estudiante vive con representante
      $direccion_alumno = $direccionesRepre;
    }

    // Actualizar datos de la persona estudiante
    $datosPersonaEstudiante = [
      'id_persona' => $id_persona_estudiante,
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

    error_log("Actualizando persona estudiante");
    $personaController->actualizarPersona($datosPersonaEstudiante);

    // Actualizar patologías del estudiante
    error_log("Actualizando patologías del estudiante");

    // Primero eliminar patologías existentes
    $sql_delete_patologias = "DELETE FROM estudiantes_patologias WHERE id_estudiante = ?";
    $stmt_delete_pat = $pdo->prepare($sql_delete_patologias);
    $stmt_delete_pat->execute([$id_estudiante]);

    // Luego agregar las nuevas patologías seleccionadas
    if (isset($_POST['patologias']) && is_array($_POST['patologias'])) {
      error_log("Agregando " . count($_POST['patologias']) . " patologías");
      foreach ($_POST['patologias'] as $id_patologia) {
        // Solo procesar valores válidos (no vacíos y diferentes de "0")
        if (!empty($id_patologia) && $id_patologia != '0') {
          $estudianteController->agregarPatologia($id_estudiante, $id_patologia);
        }
      }
    }

    // ========== ACTUALIZAR DISCAPACIDADES DEL ESTUDIANTE ==========
    error_log("Actualizando discapacidades del estudiante");

    // Primero eliminar discapacidades existentes
    $sql_delete_discapacidades = "DELETE FROM estudiantes_discapacidades WHERE id_estudiante = ?";
    $stmt_delete_disc = $pdo->prepare($sql_delete_discapacidades);
    $stmt_delete_disc->execute([$id_estudiante]);

    // Luego agregar las nuevas discapacidades seleccionadas
    if (isset($_POST['discapacidades']) && is_array($_POST['discapacidades'])) {
      error_log("Agregando " . count($_POST['discapacidades']) . " discapacidades");
      foreach ($_POST['discapacidades'] as $id_discapacidad) {
        // Solo procesar valores válidos (no vacíos y diferentes de "0")
        if (!empty($id_discapacidad) && $id_discapacidad != '0') {
          $estudianteController->agregarDiscapacidad($id_estudiante, $id_discapacidad);
        }
      }
    }
  } else {
    // Estudiante no existe - crear nuevo
    error_log("Creando nuevo estudiante");

    // Procesar dirección del estudiante
    $direccion_alumno = null;
    if ($alumno_VCP === '0') {
      // Crear dirección para estudiante
      $datosDireccionEstudiante = [
        'id_parroquia' => $_POST['parroquia_e'],
        'direccion' => $_POST['direccion_e'],
        'calle' => $_POST['calle_e'] ?? '',
        'casa' => $_POST['casa_e'] ?? ''
      ];
      error_log("Creando dirección del estudiante");
      $direccion_alumno = $ubicacionController->crearDireccion($datosDireccionEstudiante);
    } else {
      // Usar dirección del representante
      $direccion_alumno = $direccionesRepre;
    }

    // Crear persona estudiante
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

    // Crear estudiante
    error_log("Creando estudiante");
    $id_estudiante = $estudianteController->crearEstudiante($id_persona_estudiante);
    error_log("Estudiante creado con ID: " . $id_estudiante);

    // Agregar patologías al estudiante
    if (isset($_POST['patologias']) && is_array($_POST['patologias'])) {
      error_log("Agregando patologías: " . count($_POST['patologias']));
      foreach ($_POST['patologias'] as $id_patologia) {
        // Solo procesar valores válidos (no vacíos y diferentes de "0")
        if (!empty($id_patologia) && $id_patologia != '0') {
          $estudianteController->agregarPatologia($id_estudiante, $id_patologia);
        }
      }
    }

    // ========== AGREGAR DISCAPACIDADES AL ESTUDIANTE ==========
    if (isset($_POST['discapacidades']) && is_array($_POST['discapacidades'])) {
      error_log("Agregando discapacidades: " . count($_POST['discapacidades']));
      foreach ($_POST['discapacidades'] as $id_discapacidad) {
        // Solo procesar valores válidos (no vacíos y diferentes de "0")
        if (!empty($id_discapacidad) && $id_discapacidad != '0') {
          $estudianteController->agregarDiscapacidad($id_estudiante, $id_discapacidad);
        }
      }
    }
  }

  // ========== RELACIÓN ESTUDIANTE-REPRESENTANTE ==========
  $parentesco = $_POST['parentesco'];

  if ($estudiante_existente === '1') {
    // Actualizar relación existente
    error_log("Actualizando relación estudiante-representante");

    // Verificar si ya existe una relación
    $sql_check_relacion = "SELECT id_estudiante_representante FROM estudiantes_representantes 
                              WHERE id_estudiante = ? AND id_representante = ?";
    $stmt_check = $pdo->prepare($sql_check_relacion);
    $stmt_check->execute([$id_estudiante, $id_representante]);
    $relacion_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($relacion_existente) {
      // Actualizar relación existente
      $sql_update_relacion = "UPDATE estudiantes_representantes SET id_parentesco = ? 
                                   WHERE id_estudiante = ? AND id_representante = ?";
      $stmt_update = $pdo->prepare($sql_update_relacion);
      $stmt_update->execute([$parentesco, $id_estudiante, $id_representante]);
    } else {
      // Crear nueva relación
      $representanteController->crearRelacionEstudianteRepresentante($id_estudiante, $id_representante, $parentesco);
    }
  } else {
    // Crear nueva relación
    error_log("Creando relación estudiante-representante");
    $representanteController->crearRelacionEstudianteRepresentante($id_estudiante, $id_representante, $parentesco);
  }


  // ========== VALIDACIÓN DE CUPOS ==========
  error_log("=== VALIDANDO DISPONIBILIDAD DE CUPOS ===");

  $id_nivel = $_POST['id_nivel'];
  $id_seccion = $_POST['id_seccion'];
  $id_periodo = $_POST['id_periodo'];

  $disponibilidad = $cuposController->obtenerDisponibilidadPorSeparado($id_nivel, $id_seccion, $id_periodo);

  if (!$disponibilidad['success']) {
    throw new Exception('Error al verificar disponibilidad: ' . $disponibilidad['message']);
  }

  if (!$disponibilidad['disponible']) {
    throw new Exception('NO HAY CUPOS DISPONIBLES: ' . $disponibilidad['mensaje']);
  }

  error_log("✅ Cupos disponibles: " . $disponibilidad['mensaje']);

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

  error_log("Creando REinscripción");
  $id_inscripcion = $inscripcionController->crearInscripcionConNivelSeccion(
    $id_estudiante,
    $_POST['id_periodo'],
    $_POST['id_nivel'],
    $_POST['id_seccion'],
    $_SESSION['id_usuario'] ?? 1,
    $_POST['observaciones'] ?? ''
  );
  error_log("REinscripción creada con ID: " . $id_inscripcion);

  // Confirmar transacción
  $pdo->commit();

  // Respuesta de éxito con el ID de inscripción
  error_log("Inscripción completada exitosamente");

  // ENVIAR RESPUESTA JSON - ESTO ES LO ÚNICO QUE DEBE SALIR
  echo json_encode([
    'success' => true,
    'message' => 'Inscripción realizada exitosamente',
    'id_inscripcion' => $id_inscripcion,
    'id_estudiante' => $id_estudiante,
    'id_representante' => $id_representante,
    'tipo_persona' => $tipo_persona
  ]);
} catch (Exception $e) {
  // Revertir transacción en caso de error
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
    error_log("Transacción revertida debido a error: " . $e->getMessage());
  }

  error_log("Error en inscripción: " . $e->getMessage());

  // ENVIAR ERROR EN JSON
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

  // LIMPIAR CUALQUIER BUFFER QUE HAYA QUEDADO
  while (ob_get_level()) {
    ob_end_clean();
  }

  // FORZAR SALIDA INMEDIATA
  exit;
}
