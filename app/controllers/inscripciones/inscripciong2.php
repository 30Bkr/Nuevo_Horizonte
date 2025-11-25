<?php
session_start();
header('Content-Type: application/json');

include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/inscripciones/inscripciones.php");
include_once("/xampp/htdocs/final/app/controllers/cupos/cupos.php");

error_reporting(E_ALL);
ini_set('display_errors', 0);

try {
  // Verificar que solo recibamos métodos POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  error_log("=== INICIANDO PROCESAMIENTO DE REINSCRIPCIÓN ===");

  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $pdo->beginTransaction();

  $inscripcionController = new InscripcionController($pdo);
  $cuposController = new CuposController($pdo);

  // Validar campos requeridos para reinscripción
  $camposRequeridos = [
    'id_estudiante_existente',
    'id_periodo',
    'id_nivel',
    'id_seccion'
  ];

  foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo])) {
      throw new Exception("El campo $campo es requerido");
    }
  }

  $id_estudiante = $_POST['id_estudiante_existente'];
  $id_periodo = $_POST['id_periodo'];
  $id_nivel = $_POST['id_nivel'];
  $id_seccion = $_POST['id_seccion'];
  $observaciones = $_POST['observaciones'] ?? '';

  error_log("Datos recibidos:");
  error_log("- ID Estudiante: " . $id_estudiante);
  error_log("- ID Periodo: " . $id_periodo);
  error_log("- ID Nivel: " . $id_nivel);
  error_log("- ID Sección: " . $id_seccion);

  // ========== VERIFICAR SI EL ESTUDIANTE YA ESTÁ INSCRITO EN EL PERIODO ==========
  error_log("=== VERIFICANDO INSCRIPCIÓN EXISTENTE ===");

  $sql_check_inscripcion = "
        SELECT id_inscripcion 
        FROM inscripciones 
        WHERE id_estudiante = ? AND id_periodo = ? AND estatus = 1
    ";
  $stmt_check = $pdo->prepare($sql_check_inscripcion);
  $stmt_check->execute([$id_estudiante, $id_periodo]);
  $inscripcion_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);

  if ($inscripcion_existente) {
    throw new Exception('El estudiante ya está inscrito en este período académico');
  }

  // ========== VERIFICAR QUE EL ESTUDIANTE EXISTA Y ESTÉ ACTIVO ==========
  error_log("=== VERIFICANDO ESTUDIANTE ===");

  $sql_check_estudiante = "
        SELECT e.id_estudiante, p.primer_nombre, p.primer_apellido
        FROM estudiantes e
        INNER JOIN personas p ON e.id_persona = p.id_persona
        WHERE e.id_estudiante = ? AND e.estatus = 1
    ";
  $stmt_estudiante = $pdo->prepare($sql_check_estudiante);
  $stmt_estudiante->execute([$id_estudiante]);
  $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);

  if (!$estudiante) {
    throw new Exception('Estudiante no encontrado o inactivo');
  }

  error_log("Estudiante encontrado: " . $estudiante['primer_nombre'] . " " . $estudiante['primer_apellido']);

  // ========== VALIDACIÓN DE CUPOS ==========
  error_log("=== VALIDANDO DISPONIBILIDAD DE CUPOS ===");

  $disponibilidad = $cuposController->obtenerDisponibilidad($id_nivel, $id_seccion, $id_periodo);

  if (!$disponibilidad['success']) {
    throw new Exception('Error al verificar disponibilidad: ' . $disponibilidad['message']);
  }

  if (!$disponibilidad['disponible']) {
    throw new Exception('NO HAY CUPOS DISPONIBLES: ' . $disponibilidad['mensaje']);
  }

  error_log("✅ Cupos disponibles: " . $disponibilidad['mensaje']);

  // ========== CREAR REINSCRIPCIÓN ==========
  error_log("=== CREANDO REINSCRIPCIÓN ===");

  $id_usuario = $_SESSION['id_usuario'] ?? 1;

  $datosInscripcion = [
    'id_estudiante' => $id_estudiante,
    'id_periodo' => $id_periodo,
    'id_nivel' => $id_nivel,
    'id_seccion' => $id_seccion,
    'id_usuario' => $id_usuario,
    'fecha_inscripcion' => date('Y-m-d'),
    'observaciones' => $observaciones
  ];

  error_log("Datos de reinscripción: " . print_r($datosInscripcion, true));

  $id_inscripcion = $inscripcionController->crearInscripcionConNivelSeccion(
    $id_estudiante,
    $id_periodo,
    $id_nivel,
    $id_seccion,
    $id_usuario,
    $observaciones
  );

  if (!$id_inscripcion) {
    throw new Exception('Error al crear la reinscripción');
  }

  error_log("Reinscripción creada con ID: " . $id_inscripcion);

  // Confirmar transacción
  $pdo->commit();

  // Respuesta de éxito
  error_log("=== REINSCRIPCIÓN COMPLETADA EXITOSAMENTE ===");

  echo json_encode([
    'success' => true,
    'message' => 'Reinscripción realizada exitosamente',
    'id_inscripcion' => $id_inscripcion,
    'id_estudiante' => $id_estudiante,
    'estudiante_nombre' => $estudiante['primer_nombre'] . ' ' . $estudiante['primer_apellido']
  ]);
} catch (Exception $e) {
  // Revertir transacción en caso de error
  if (isset($pdo) && $pdo->inTransaction()) {
    $pdo->rollBack();
    error_log("Transacción revertida debido a error: " . $e->getMessage());
  }

  error_log("Error en reinscripción: " . $e->getMessage());

  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => 'Error en la reinscripción: ' . $e->getMessage()
  ]);
} finally {
  // Cerrar conexión si existe
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
