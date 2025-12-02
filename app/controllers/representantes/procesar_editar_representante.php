<?php
session_start();

// Ajustar la ruta de conexión según tu estructura
// El error muestra que estás en: C:\xampp\htdocs\final\app\controllers\representantes\
// Y tratas de incluir: ../../app/conexion.php (que sería: C:\xampp\htdocs\final\app\conexion.php)

// Opción 1: Ruta absoluta (recomendada)
include_once($_SERVER['DOCUMENT_ROOT'] . '/final/app/conexion.php');

// Opción 2: O usar esta ruta relativa
// include_once(__DIR__ . '/../../app/conexion.php');

// Opción 3: O si tienes una estructura diferente, ajusta la ruta:
// include_once('/xampp/htdocs/final/app/conexion.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['error'] = "Método no permitido";
  header("Location: /final/admin/representantes/representantes_list.php");
  exit;
}

// Validar datos requeridos
$required_fields = [
  'id_representante',
  'id_persona',
  'primer_nombre',
  'primer_apellido',
  'cedula',
  'telefono',
  'correo',
  'lugar_nac',
  'fecha_nac',
  'sexo',
  'nacionalidad',
  'ocupacion',
  'profesion',
  'estado',
  'municipio',
  'parroquia',
  'direccion'
];

$missing_fields = [];
foreach ($required_fields as $field) {
  if (empty($_POST[$field])) {
    $missing_fields[] = $field;
  }
}

if (!empty($missing_fields)) {
  $_SESSION['error'] = "Faltan campos obligatorios: " . implode(', ', $missing_fields);
  header("Location: /final/admin/representantes/representante_editar.php?id=" . $_POST['id_representante']);
  exit;
}

try {
  $database = new Conexion();
  $db = $database->conectar();

  if (!$db) {
    throw new Exception("Error de conexión a la base de datos");
  }

  // Iniciar transacción
  $db->beginTransaction();

  // 1. Actualizar dirección (si existe id_direccion) o crear nueva
  $id_direccion = $_POST['id_direccion'] ?? 0;

  if (empty($id_direccion) || $id_direccion == 0) {
    // Crear nueva dirección
    $sql_direccion = "INSERT INTO direcciones 
                         (direccion, calle, casa, id_parroquia, estatus, creacion) 
                         VALUES (?, ?, ?, ?, 1, NOW())";
    $stmt_direccion = $db->prepare($sql_direccion);
    $stmt_direccion->execute([
      $_POST['direccion'],
      $_POST['calle'] ?? null,
      $_POST['casa'] ?? null,
      $_POST['parroquia']
    ]);
    $id_direccion = $db->lastInsertId();
  } else {
    // Actualizar dirección existente
    $sql_direccion = "UPDATE direcciones SET 
                         direccion = ?, calle = ?, casa = ?, id_parroquia = ?, 
                         actualizacion = NOW()
                         WHERE id_direccion = ?";
    $stmt_direccion = $db->prepare($sql_direccion);
    $stmt_direccion->execute([
      $_POST['direccion'],
      $_POST['calle'] ?? null,
      $_POST['casa'] ?? null,
      $_POST['parroquia'],
      $id_direccion
    ]);
  }

  // 2. Actualizar persona
  $sql_persona = "UPDATE personas SET 
                   primer_nombre = ?, segundo_nombre = ?, 
                   primer_apellido = ?, segundo_apellido = ?, 
                   cedula = ?, telefono = ?, telefono_hab = ?, 
                   correo = ?, lugar_nac = ?, fecha_nac = ?, 
                   sexo = ?, nacionalidad = ?, id_direccion = ?,
                   actualizacion = NOW()
                   WHERE id_persona = ?";

  $stmt_persona = $db->prepare($sql_persona);
  $stmt_persona->execute([
    $_POST['primer_nombre'],
    $_POST['segundo_nombre'] ?? null,
    $_POST['primer_apellido'],
    $_POST['segundo_apellido'] ?? null,
    $_POST['cedula'],
    $_POST['telefono'],
    $_POST['telefono_hab'] ?? null,
    $_POST['correo'],
    $_POST['lugar_nac'],
    $_POST['fecha_nac'],
    $_POST['sexo'],
    $_POST['nacionalidad'],
    $id_direccion,
    $_POST['id_persona']
  ]);

  // 3. Actualizar representante
  $sql_representante = "UPDATE representantes SET 
                         ocupacion = ?, lugar_trabajo = ?, id_profesion = ?,
                         actualizacion = NOW()
                         WHERE id_representante = ?";

  $stmt_representante = $db->prepare($sql_representante);
  $stmt_representante->execute([
    $_POST['ocupacion'],
    $_POST['lugar_trabajo'] ?? null,
    $_POST['profesion'],
    $_POST['id_representante']
  ]);

  // Confirmar transacción
  $db->commit();

  $_SESSION['success'] = "Representante actualizado exitosamente";

  // Redirigir a la lista de representantes
  header("Location: /final/admin/representantes/representantes_list.php");
  exit;
} catch (Exception $e) {
  // Rollback en caso de error
  if (isset($db) && $db->inTransaction()) {
    $db->rollBack();
  }

  $_SESSION['error'] = "Error al actualizar representante: " . $e->getMessage();
  header("Location: /final/admin/representantes/representante_editar.php?id=" . $_POST['id_representante']);
  exit;
}
