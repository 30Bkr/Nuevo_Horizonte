<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'];

    // Consulta para validar representante por cédula
    $sql = "
            SELECT 
                r.id_representante,
                p.id_persona,
                p.cedula,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.telefono,
                p.telefono_hab,
                p.correo,
                p.fecha_nac,
                p.lugar_nac,
                p.sexo,
                p.nacionalidad,
                p.id_direccion,
                r.ocupacion,
                r.lugar_trabajo,
                r.id_profesion,
                d.direccion,
                d.calle,
                d.casa,
                d.id_parroquia,
                pa.id_municipio,
                m.id_estado
            FROM representantes r
            INNER JOIN personas p ON r.id_persona = p.id_persona
            INNER JOIN direcciones d ON p.id_direccion = d.id_direccion
            INNER JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
            INNER JOIN municipios m ON pa.id_municipio = m.id_municipio
            WHERE p.cedula = ? AND r.estatus = 1 AND p.estatus = 1
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cedula]);
    $representante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($representante) {
      // Contar estudiantes del representante
      $sql_estudiantes = "
                SELECT COUNT(*) as total_estudiantes 
                FROM estudiantes_representantes 
                WHERE id_representante = ? AND estatus = 1
            ";
      $stmt_estudiantes = $pdo->prepare($sql_estudiantes);
      $stmt_estudiantes->execute([$representante['id_representante']]);
      $total_estudiantes = $stmt_estudiantes->fetch(PDO::FETCH_ASSOC)['total_estudiantes'];

      $response = [
        'existe' => true,
        'id_representante' => $representante['id_representante'],
        'id_persona' => $representante['id_persona'],
        'cedula' => $representante['cedula'],
        'primer_nombre' => $representante['primer_nombre'],
        'segundo_nombre' => $representante['segundo_nombre'],
        'primer_apellido' => $representante['primer_apellido'],
        'segundo_apellido' => $representante['segundo_apellido'],
        'nombre_completo' => $representante['primer_nombre'] . ' ' .
          ($representante['segundo_nombre'] ? $representante['segundo_nombre'] . ' ' : '') .
          $representante['primer_apellido'] . ' ' .
          ($representante['segundo_apellido'] ? $representante['segundo_apellido'] : ''),
        'telefono' => $representante['telefono'],
        'telefono_hab' => $representante['telefono_hab'],
        'correo' => $representante['correo'],
        'fecha_nac' => $representante['fecha_nac'],
        'lugar_nac' => $representante['lugar_nac'],
        'sexo' => $representante['sexo'],
        'nacionalidad' => $representante['nacionalidad'],
        'id_direccion' => $representante['id_direccion'],
        'ocupacion' => $representante['ocupacion'],
        'lugar_trabajo' => $representante['lugar_trabajo'],
        'profesion' => $representante['id_profesion'],
        'direccion' => $representante['direccion'],
        'calle' => $representante['calle'],
        'casa' => $representante['casa'],
        'id_parroquia' => $representante['id_parroquia'],
        'id_municipio' => $representante['id_municipio'],
        'id_estado' => $representante['id_estado'],
        'total_estudiantes' => $total_estudiantes
      ];
    } else {
      $response = [
        'existe' => false,
        'message' => 'Representante no encontrado'
      ];
    }

    echo json_encode($response);
  }
} catch (PDOException $e) {
  echo json_encode([
    'existe' => false,
    'message' => 'Error: ' . $e->getMessage()
  ]);
}
