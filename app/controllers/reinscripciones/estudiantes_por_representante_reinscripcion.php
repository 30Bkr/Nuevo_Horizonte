<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $id_representante = $_POST['id_representante'] ?? '';

  if (empty($id_representante)) {
    echo json_encode(['error' => 'ID de representante requerido']);
    exit;
  }

  // Obtener período activo
  $sql_periodo = "SELECT id_periodo FROM periodos WHERE estatus = 1 ORDER BY id_periodo DESC LIMIT 1";
  $stmt_periodo = $pdo->prepare($sql_periodo);
  $stmt_periodo->execute();
  $periodo_activo = $stmt_periodo->fetch(PDO::FETCH_ASSOC);
  $id_periodo_activo = $periodo_activo ? $periodo_activo['id_periodo'] : null;

  // Obtener estudiantes con información de inscripción
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
                er.id_parentesco,
                par.parentesco,
                d_est.direccion as direccion_est,
                d_est.calle as calle_est,
                d_est.casa as casa_est,
                d_est.id_parroquia as id_parroquia_est,
                pa_est.id_municipio as id_municipio_est,
                m_est.id_estado as id_estado_est,
                -- Información de la última inscripción
                (SELECT n.nom_nivel 
                 FROM inscripciones i 
                 JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion 
                 JOIN niveles n ON ns.id_nivel = n.id_nivel 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as nombre_nivel,
                (SELECT n.num_nivel 
                 FROM inscripciones i 
                 JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion 
                 JOIN niveles n ON ns.id_nivel = n.id_nivel 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as num_nivel,
                (SELECT p_ant.descripcion_periodo 
                 FROM inscripciones i 
                 JOIN periodos p_ant ON i.id_periodo = p_ant.id_periodo 
                 WHERE i.id_estudiante = e.id_estudiante 
                 ORDER BY i.id_inscripcion DESC LIMIT 1) as periodo_anterior_desc,
                -- Verificar si está inscrito en el período activo
                EXISTS (SELECT 1 FROM inscripciones 
                       WHERE id_estudiante = e.id_estudiante 
                       AND id_periodo = ? 
                       AND estatus = 1) as inscrito_periodo_activo
            FROM estudiantes_representantes er
            JOIN estudiantes e ON er.id_estudiante = e.id_estudiante
            JOIN personas p ON e.id_persona = p.id_persona
            JOIN parentesco par ON er.id_parentesco = par.id_parentesco
            JOIN direcciones d_est ON p.id_direccion = d_est.id_direccion
            JOIN parroquias pa_est ON d_est.id_parroquia = pa_est.id_parroquia
            JOIN municipios m_est ON pa_est.id_municipio = m_est.id_municipio
            WHERE er.id_representante = ? 
            AND er.estatus = 1 
            AND e.estatus = 1
            ORDER BY p.primer_nombre, p.primer_apellido";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([$id_periodo_activo, $id_representante]);
  $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Procesar datos para el frontend
  foreach ($estudiantes as &$estudiante) {
    $estudiante['estado_inscripcion'] = $estudiante['inscrito_periodo_activo'] ? 'Inscrito' : 'No inscrito';
    $estudiante['puede_reinscribir'] = !$estudiante['inscrito_periodo_activo'];
  }

  echo json_encode([
    'success' => true,
    'estudiantes' => $estudiantes,
    'id_periodo_activo' => $id_periodo_activo
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Error al cargar estudiantes']);
}
