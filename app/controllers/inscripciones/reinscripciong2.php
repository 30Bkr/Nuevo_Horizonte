<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $representante_existente = $_POST['representante_existente'];
    $id_representante_existente = $_POST['id_representante_existente'];
    $estudiante_existente = $_POST['estudiante_existente'];
    $id_estudiante_existente = $_POST['id_estudiante_existente'];
    $id_periodo = $_POST['id_periodo'];
    $id_nivel = $_POST['id_nivel'];
    $id_seccion = $_POST['id_seccion'];
    $observaciones = $_POST['observaciones'] ?? '';
    $parentesco = $_POST['parentesco'] ?? '';

    // Validaciones
    if ($representante_existente != '1' || $estudiante_existente != '1') {
      throw new Exception("Representante o estudiante no válido");
    }

    // Verificar que el estudiante no esté ya inscrito en el mismo período
    $sql_verificar_inscripcion = "
            SELECT id_inscripcion 
            FROM inscripciones 
            WHERE id_estudiante = ? AND id_periodo = ? AND estatus = 1
        ";
    $stmt_verificar = $pdo->prepare($sql_verificar_inscripcion);
    $stmt_verificar->execute([$id_estudiante_existente, $id_periodo]);
    $inscripcion_existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);

    if ($inscripcion_existente) {
      throw new Exception("El estudiante ya está inscrito en este período académico");
    }

    // Obtener el id_nivel_seccion correspondiente
    $sql_nivel_seccion = "
            SELECT id_nivel_seccion 
            FROM niveles_secciones 
            WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1
        ";
    $stmt_nivel_seccion = $pdo->prepare($sql_nivel_seccion);
    $stmt_nivel_seccion->execute([$id_nivel, $id_seccion]);
    $nivel_seccion = $stmt_nivel_seccion->fetch(PDO::FETCH_ASSOC);

    if (!$nivel_seccion) {
      throw new Exception("La combinación de nivel y sección no es válida");
    }

    $id_nivel_seccion = $nivel_seccion['id_nivel_seccion'];

    // Obtener el ID del usuario que realiza la inscripción
    $id_usuario = 1; // Ajusta según tu sistema de autenticación

    // Insertar la nueva inscripción
    $sql_inscripcion = "
            INSERT INTO inscripciones (
                id_estudiante, 
                id_periodo, 
                id_nivel_seccion, 
                id_usuario, 
                fecha_inscripcion, 
                observaciones
            ) VALUES (?, ?, ?, ?, CURDATE(), ?)
        ";

    $stmt_inscripcion = $pdo->prepare($sql_inscripcion);
    $stmt_inscripcion->execute([
      $id_estudiante_existente,
      $id_periodo,
      $id_nivel_seccion,
      $id_usuario,
      $observaciones
    ]);

    if ($stmt_inscripcion->rowCount() > 0) {
      echo json_encode([
        'success' => true,
        'message' => 'Reinscripción realizada exitosamente'
      ]);
    } else {
      throw new Exception("Error al realizar la reinscripción");
    }
  }
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Error en la Reinscripción: ' . $e->getMessage()
  ]);
}
