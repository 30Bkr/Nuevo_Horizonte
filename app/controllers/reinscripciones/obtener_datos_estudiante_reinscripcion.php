<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $id_estudiante = $_POST['id_estudiante'] ?? '';

  if (empty($id_estudiante)) {
    echo json_encode(['error' => 'ID de estudiante requerido']);
    exit;
  }

  // Obtener datos completos del estudiante
  $sql = "SELECT 
                e.id_estudiante,
                p.id_persona,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.cedula,
                p.telefono,
                p.correo,
                p.lugar_nac,
                p.fecha_nac,
                p.sexo,
                p.nacionalidad,
                p.id_direccion as id_direccion_est,
                d_est.direccion as direccion_est,
                d_est.calle as calle_est,
                d_est.casa as casa_est,
                d_est.id_parroquia as id_parroquia_est,
                pa_est.id_municipio as id_municipio_est,
                m_est.id_estado as id_estado_est,
                -- Última inscripción
                (SELECT n.id_nivel 
                 FROM inscripciones i 
                 JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion 
                 JOIN niveles n ON ns.id_nivel = n.id_nivel 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as id_nivel_anterior,
                (SELECT n.num_nivel 
                 FROM inscripciones i 
                 JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion 
                 JOIN niveles n ON ns.id_nivel = n.id_nivel 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as num_nivel_anterior,
                (SELECT n.nom_nivel 
                 FROM inscripciones i 
                 JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion 
                 JOIN niveles n ON ns.id_nivel = n.id_nivel 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as nom_nivel_anterior
            FROM estudiantes e
            JOIN personas p ON e.id_persona = p.id_persona
            JOIN direcciones d_est ON p.id_direccion = d_est.id_direccion
            JOIN parroquias pa_est ON d_est.id_parroquia = pa_est.id_parroquia
            JOIN municipios m_est ON pa_est.id_municipio = m_est.id_municipio
            WHERE e.id_estudiante = ? 
            AND e.estatus = 1";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id_estudiante]);
  $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($estudiante) {
    // Obtener patologías
    $sql_patologias = "SELECT ep.id_patologia, pat.nom_patologia
                          FROM estudiantes_patologias ep
                          JOIN patologias pat ON ep.id_patologia = pat.id_patologia
                          WHERE ep.id_estudiante = ? AND ep.estatus = 1";
    $stmt_patologias = $pdo->prepare($sql_patologias);
    $stmt_patologias->execute([$id_estudiante]);
    $estudiante['patologias'] = $stmt_patologias->fetchAll(PDO::FETCH_ASSOC);

    // Obtener discapacidades
    $sql_discapacidades = "SELECT ed.id_discapacidad, disc.nom_discapacidad
                              FROM estudiantes_discapacidades ed
                              JOIN discapacidades disc ON ed.id_discapacidad = disc.id_discapacidad
                              WHERE ed.id_estudiante = ? AND ed.estatus = 1";
    $stmt_discapacidades = $pdo->prepare($sql_discapacidades);
    $stmt_discapacidades->execute([$id_estudiante]);
    $estudiante['discapacidades'] = $stmt_discapacidades->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'estudiante' => $estudiante]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Estudiante no encontrado']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Error al cargar datos del estudiante']);
}
