<?php
// /final/app/controllers/cupos/verificar_cupos.php
include_once("/xampp/htdocs/final/app/conexion.php");
header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if (!isset($_POST['id_nivel_seccion']) || !isset($_POST['id_periodo'])) {
    echo json_encode([
      'success' => false,
      'message' => 'Faltan parámetros requeridos'
    ]);
    exit;
  }

  $id_nivel_seccion = $_POST['id_nivel_seccion'];
  $id_periodo = $_POST['id_periodo'];

  // Obtener capacidad de la sección
  $sql_capacidad = "SELECT capacidad FROM nivel_seccion WHERE id_nivel_seccion = :id_nivel_seccion";
  $stmt_capacidad = $pdo->prepare($sql_capacidad);
  $stmt_capacidad->execute([':id_nivel_seccion' => $id_nivel_seccion]);
  $capacidad = $stmt_capacidad->fetch(PDO::FETCH_ASSOC);

  if (!$capacidad) {
    echo json_encode([
      'success' => false,
      'message' => 'No se encontró la sección'
    ]);
    exit;
  }

  // Contar inscritos en esa sección para el período
  $sql_inscritos = "SELECT COUNT(*) as inscritos FROM inscripciones 
                      WHERE id_nivel_seccion = :id_nivel_seccion 
                      AND id_periodo = :id_periodo";
  $stmt_inscritos = $pdo->prepare($sql_inscritos);
  $stmt_inscritos->execute([
    ':id_nivel_seccion' => $id_nivel_seccion,
    ':id_periodo' => $id_periodo
  ]);
  $inscritos = $stmt_inscritos->fetch(PDO::FETCH_ASSOC);

  $disponible = ($capacidad['capacidad'] - $inscritos['inscritos']) > 0;
  $cupos_restantes = $capacidad['capacidad'] - $inscritos['inscritos'];

  echo json_encode([
    'success' => true,
    'disponible' => $disponible,
    'capacidad' => $capacidad['capacidad'],
    'inscritos' => $inscritos['inscritos'],
    'cupos_restantes' => $cupos_restantes,
    'mensaje' => $disponible ?
      "Hay {$cupos_restantes} cupo(s) disponible(s) de {$capacidad['capacidad']}" :
      "No hay cupos disponibles. Capacidad: {$capacidad['capacidad']}, Inscritos: {$inscritos['inscritos']}"
  ]);
} catch (PDOException $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Error en la base de datos: ' . $e->getMessage()
  ]);
}
