<?php
session_start();
require_once '/xampp/htdocs/final/app/conexion.php';

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'] ?? 0;
    $id_nuevo_representante = $_POST['id_nuevo_representante'] ?? 0;
    $id_parentesco = $_POST['id_parentesco'] ?? 0;
    $id_usuario = $_SESSION['id_usuario'];

    if (!$id_estudiante || !$id_nuevo_representante || !$id_parentesco) {
      echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
      exit;
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    try {
      // 1. Obtener el representante actual
      $sql_actual = "SELECT id_representante FROM estudiantes_representantes 
                          WHERE id_estudiante = ? AND estatus = 1 LIMIT 1";
      $stmt_actual = $pdo->prepare($sql_actual);
      $stmt_actual->execute([$id_estudiante]);
      $actual = $stmt_actual->fetch(PDO::FETCH_ASSOC);

      $id_representante_actual = $actual['id_representante'] ?? 0;

      // 2. Desactivar la relación actual
      $sql_desactivar = "UPDATE estudiantes_representantes 
                              SET estatus = 0, actualizacion = NOW() 
                              WHERE id_estudiante = ? AND estatus = 1";
      $stmt_desactivar = $pdo->prepare($sql_desactivar);
      $stmt_desactivar->execute([$id_estudiante]);

      // 3. Crear nueva relación
      $sql_nueva = "INSERT INTO estudiantes_representantes 
                         (id_estudiante, id_representante, id_parentesco) 
                         VALUES (?, ?, ?)";
      $stmt_nueva = $pdo->prepare($sql_nueva);
      $stmt_nueva->execute([$id_estudiante, $id_nuevo_representante, $id_parentesco]);

      // 4. Registrar en historial (opcional - crea esta tabla si no existe)
      $sql_historial = "INSERT INTO historial_cambios_representante 
                             (id_estudiante, id_representante_anterior, id_representante_nuevo, 
                              id_usuario, fecha_cambio) 
                             VALUES (?, ?, ?, ?, NOW())";
      $stmt_historial = $pdo->prepare($sql_historial);
      $stmt_historial->execute([
        $id_estudiante,
        $id_representante_actual,
        $id_nuevo_representante,
        $id_usuario
      ]);

      $pdo->commit();

      echo json_encode([
        'success' => true,
        'message' => 'Representante cambiado exitosamente'
      ]);
    } catch (Exception $e) {
      $pdo->rollBack();
      throw $e;
    }
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
