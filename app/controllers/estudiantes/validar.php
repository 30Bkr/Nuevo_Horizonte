<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cedula = $_POST['cedula'] ?? '';

    if (empty($cedula)) {
      echo json_encode(['existe' => false, 'error' => 'Cédula requerida']);
      exit;
    }

    // Consulta para obtener datos del estudiante
    $sql = "SELECT 
                    e.id_estudiante,
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
                    d.id_direccion,
                    d.id_parroquia,
                    pa.id_municipio,
                    m.id_estado,
                    d.direccion,
                    d.calle,
                    d.casa
                FROM estudiantes e
                INNER JOIN personas p ON e.id_persona = p.id_persona
                INNER JOIN direcciones d ON p.id_direccion = d.id_direccion
                INNER JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
                INNER JOIN municipios m ON pa.id_municipio = m.id_municipio
                WHERE p.cedula = ? AND e.estatus = 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cedula]);
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($estudiante) {
      // Obtener patologías del estudiante
      $sqlPatologias = "SELECT pat.id_patologia 
                             FROM estudiantes_patologias ep
                             INNER JOIN patologias pat ON ep.id_patologia = pat.id_patologia
                             WHERE ep.id_estudiante = ? AND ep.estatus = 1";
      $stmtPat = $pdo->prepare($sqlPatologias);
      $stmtPat->execute([$estudiante['id_estudiante']]);
      $patologias = $stmtPat->fetchAll(PDO::FETCH_COLUMN);

      echo json_encode([
        'existe' => true,
        'id_estudiante' => $estudiante['id_estudiante'],
        'id_persona' => $estudiante['id_persona'],
        'id_direccion' => $estudiante['id_direccion'],
        'primer_nombre' => $estudiante['primer_nombre'],
        'segundo_nombre' => $estudiante['segundo_nombre'],
        'primer_apellido' => $estudiante['primer_apellido'],
        'segundo_apellido' => $estudiante['segundo_apellido'],
        'cedula' => $estudiante['cedula'],
        'telefono' => $estudiante['telefono'],
        'telefono_hab' => $estudiante['telefono_hab'],
        'correo' => $estudiante['correo'],
        'lugar_nac' => $estudiante['lugar_nac'],
        'fecha_nac' => $estudiante['fecha_nac'],
        'sexo' => $estudiante['sexo'],
        'nacionalidad' => $estudiante['nacionalidad'],
        'id_estado' => $estudiante['id_estado'],
        'id_municipio' => $estudiante['id_municipio'],
        'id_parroquia' => $estudiante['id_parroquia'],
        'direccion' => $estudiante['direccion'],
        'calle' => $estudiante['calle'],
        'casa' => $estudiante['casa'],
        'patologias' => $patologias,
        'nombre_completo' => $estudiante['primer_nombre'] . ' ' .
          ($estudiante['segundo_nombre'] ? $estudiante['segundo_nombre'] . ' ' : '') .
          $estudiante['primer_apellido'] . ' ' .
          ($estudiante['segundo_apellido'] ? $estudiante['segundo_apellido'] : '')
      ]);
    } else {
      echo json_encode(['existe' => false]);
    }
  }
} catch (PDOException $e) {
  echo json_encode(['existe' => false, 'error' => 'Error de base de datos: ' . $e->getMessage()]);
}
