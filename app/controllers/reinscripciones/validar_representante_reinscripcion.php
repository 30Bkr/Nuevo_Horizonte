<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $cedula = $_POST['cedula'] ?? '';

  if (empty($cedula)) {
    echo json_encode(['existe' => false, 'error' => 'Cédula requerida']);
    exit;
  }

  // Obtener información completa del representante
  $sql = "SELECT 
                r.id_representante,
                p.id_persona,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.cedula,
                p.telefono,
                p.telefono_hab,
                p.correo,
                p.lugar_nac,
                p.fecha_nac,
                p.sexo,
                p.nacionalidad,
                r.ocupacion,
                r.lugar_trabajo,
                r.id_profesion,  -- Asegúrate de incluir este campo
                d.id_direccion,
                d.direccion,
                d.calle,
                d.casa,
                d.id_parroquia,
                pa.id_municipio,
                m.id_estado,
                CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_completo
            FROM representantes r
            JOIN personas p ON r.id_persona = p.id_persona
            JOIN direcciones d ON p.id_direccion = d.id_direccion
            JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
            JOIN municipios m ON pa.id_municipio = m.id_municipio
            WHERE p.cedula = ? AND p.estatus = 1 AND r.estatus = 1";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$cedula]);
  $representante = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($representante) {
    // Obtener estudiantes asociados
    $sql_estudiantes = "SELECT COUNT(*) as total 
                           FROM estudiantes_representantes 
                           WHERE id_representante = ? AND estatus = 1";
    $stmt_estudiantes = $pdo->prepare($sql_estudiantes);
    $stmt_estudiantes->execute([$representante['id_representante']]);
    $total_estudiantes = $stmt_estudiantes->fetch(PDO::FETCH_ASSOC)['total'];

    $representante['total_estudiantes'] = $total_estudiantes;
    $representante['existe'] = true;

    echo json_encode($representante);
  } else {
    echo json_encode(['existe' => false]);
  }
} catch (PDOException $e) {
  echo json_encode(['existe' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
