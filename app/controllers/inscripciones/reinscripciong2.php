<?php
// Iniciar buffer inmediatamente
ob_start();

// Configurar para desarrollo (luego cambiar a 0 en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir conexión
include_once("/xampp/htdocs/final/app/conexion.php");

// Función para enviar respuesta JSON
function sendJsonResponse($success, $message, $additionalData = [])
{
  // Limpiar cualquier output previo
  if (ob_get_length()) ob_clean();

  header('Content-Type: application/json');
  $response = array_merge([
    'success' => $success,
    'message' => $message
  ], $additionalData);

  echo json_encode($response);
  exit;
}

try {
  // Verificar método
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Método no permitido');
  }

  // Log de datos recibidos (para depuración)
  error_log("Datos POST recibidos: " . print_r($_POST, true));

  // Validar campos requeridos
  $campos_requeridos = [
    'representante_existente',
    'id_representante_existente',
    'estudiante_existente',
    'id_estudiante_existente',
    'id_periodo',
    'id_nivel',
    'id_seccion'
  ];

  $campos_faltantes = [];
  foreach ($campos_requeridos as $campo) {
    if (!isset($_POST[$campo]) || $_POST[$campo] === '') {
      $campos_faltantes[] = $campo;
    }
  }

  if (!empty($campos_faltantes)) {
    sendJsonResponse(false, 'Campos requeridos faltantes: ' . implode(', ', $campos_faltantes));
  }

  // Obtener datos
  $representante_existente = $_POST['representante_existente'];
  $id_representante_existente = $_POST['id_representante_existente'];
  $estudiante_existente = $_POST['estudiante_existente'];
  $id_estudiante_existente = $_POST['id_estudiante_existente'];
  $id_periodo = $_POST['id_periodo'];
  $id_nivel = $_POST['id_nivel'];
  $id_seccion = $_POST['id_seccion'];
  $observaciones = $_POST['observaciones'] ?? '';
  $parentesco = $_POST['parentesco'] ?? '';

  // Validar valores
  if ($representante_existente != '1' || $estudiante_existente != '1') {
    sendJsonResponse(false, 'Representante o estudiante no válido');
  }

  // Conectar a la base de datos
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  // Verificar período
  $sql_periodo = "SELECT id_periodo, descripcion_periodo, estatus FROM periodos WHERE id_periodo = ?";
  $stmt_periodo = $pdo->prepare($sql_periodo);
  $stmt_periodo->execute([$id_periodo]);
  $periodo = $stmt_periodo->fetch(PDO::FETCH_ASSOC);

  if (!$periodo) {
    sendJsonResponse(false, "El período seleccionado no existe");
  }

  if ($periodo['estatus'] != 1) {
    sendJsonResponse(false, "El período seleccionado no está activo: " . $periodo['descripcion_periodo']);
  }

  // Verificar si ya está inscrito en este período
  $sql_verificar = "
        SELECT i.id_inscripcion, p.descripcion_periodo 
        FROM inscripciones i 
        INNER JOIN periodos p ON i.id_periodo = p.id_periodo 
        WHERE i.id_estudiante = ? AND i.id_periodo = ? AND i.estatus = 1
    ";
  $stmt_verificar = $pdo->prepare($sql_verificar);
  $stmt_verificar->execute([$id_estudiante_existente, $id_periodo]);
  $inscripcion_existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

  if ($inscripcion_existente) {
    sendJsonResponse(false, "El estudiante ya está inscrito en el período: " . $inscripcion_existente['descripcion_periodo']);
  }

  // Obtener nivel_seccion
  $sql_nivel_seccion = "
        SELECT id_nivel_seccion 
        FROM niveles_secciones 
        WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1
    ";
  $stmt_nivel_seccion = $pdo->prepare($sql_nivel_seccion);
  $stmt_nivel_seccion->execute([$id_nivel, $id_seccion]);
  $nivel_seccion = $stmt_nivel_seccion->fetch(PDO::FETCH_ASSOC);

  if (!$nivel_seccion) {
    sendJsonResponse(false, "No se encontró una sección activa para el nivel y sección seleccionados");
  }

  $id_nivel_seccion = $nivel_seccion['id_nivel_seccion'];

  // ID de usuario (por ahora hardcodeado)
  $id_usuario = 1;

  // INICIAR TRANSACCIÓN
  $pdo->beginTransaction();

  try {
    // Insertar inscripción
    $sql_insert = "
            INSERT INTO inscripciones (
                id_estudiante, 
                id_periodo, 
                id_nivel_seccion, 
                id_usuario, 
                fecha_inscripcion, 
                observaciones
            ) VALUES (?, ?, ?, ?, CURDATE(), ?)
        ";

    $stmt_insert = $pdo->prepare($sql_insert);
    $resultado = $stmt_insert->execute([
      $id_estudiante_existente,
      $id_periodo,
      $id_nivel_seccion,
      $id_usuario,
      $observaciones
    ]);

    if (!$resultado || $stmt_insert->rowCount() === 0) {
      throw new Exception("No se pudo insertar la inscripción en la base de datos");
    }

    $id_inscripcion = $pdo->lastInsertId();

    // Obtener información del estudiante para la respuesta
    $sql_estudiante = "
            SELECT p.primer_nombre, p.primer_apellido 
            FROM estudiantes e 
            INNER JOIN personas p ON e.id_persona = p.id_persona 
            WHERE e.id_estudiante = ?
        ";
    $stmt_estudiante = $pdo->prepare($sql_estudiante);
    $stmt_estudiante->execute([$id_estudiante_existente]);
    $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);

    // CONFIRMAR TRANSACCIÓN
    $pdo->commit();

    sendJsonResponse(true, 'Reinscripción realizada exitosamente', [
      'id_inscripcion' => $id_inscripcion,
      'estudiante_nombre' => $estudiante ? $estudiante['primer_nombre'] . ' ' . $estudiante['primer_apellido'] : 'N/A'
    ]);
  } catch (Exception $e) {
    $pdo->rollBack();
    throw new Exception("Error en la transacción: " . $e->getMessage());
  }
} catch (Exception $e) {
  // Manejar errores generales
  error_log("Error en reinscripción: " . $e->getMessage());
  sendJsonResponse(false, "Error en el proceso: " . $e->getMessage());
}

// Limpiar buffer final
ob_end_flush();
