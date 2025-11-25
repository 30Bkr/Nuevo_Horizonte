<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_representante = $_POST['id_representante'];

    // 1. Obtener el período activo actual
    $sql_periodo_activo = "SELECT id_periodo, descripcion_periodo FROM periodos WHERE estatus = 1 LIMIT 1";
    $stmt_periodo = $pdo->prepare($sql_periodo_activo);
    $stmt_periodo->execute();
    $periodo_activo = $stmt_periodo->fetch(PDO::FETCH_ASSOC);

    if (!$periodo_activo) {
      throw new Exception('No hay un período académico activo');
    }

    $id_periodo_activo = $periodo_activo['id_periodo'];
    $desc_periodo_activo = $periodo_activo['descripcion_periodo'];

    // 2. Consulta principal simplificada y corregida
    $sql = "
            SELECT 
                e.id_estudiante,
                p.id_persona,
                p.id_direccion,
                p.cedula,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.fecha_nac,
                p.sexo,
                p.telefono,
                p.correo,
                par.parentesco,
                
                -- Información de inscripción en el PERÍODO ACTIVO
                i_actual.id_inscripcion as inscripcion_actual_id,
                i_actual.estatus as estatus_inscripcion_actual,
                per_actual.descripcion_periodo as periodo_actual_desc,
                
                -- Información de la última inscripción (cualquier período)
                ult_insc.nom_nivel as nombre_nivel,
                ult_insc.num_nivel,
                ult_insc.nom_seccion,
                ult_insc.descripcion_periodo as periodo_anterior_desc,
                
                -- Determinar estado
                CASE 
                    WHEN i_actual.id_inscripcion IS NOT NULL AND i_actual.estatus = 1 THEN 'Inscrito'
                    ELSE 'No inscrito'
                END as estado_inscripcion

            FROM estudiantes_representantes er
            INNER JOIN estudiantes e ON er.id_estudiante = e.id_estudiante
            INNER JOIN personas p ON e.id_persona = p.id_persona
            INNER JOIN parentesco par ON er.id_parentesco = par.id_parentesco
            
            -- LEFT JOIN para inscripción en período ACTIVO
            LEFT JOIN inscripciones i_actual ON e.id_estudiante = i_actual.id_estudiante 
                AND i_actual.id_periodo = ?
            LEFT JOIN periodos per_actual ON i_actual.id_periodo = per_actual.id_periodo
            
            -- LEFT JOIN para la ÚLTIMA inscripción del estudiante
            LEFT JOIN (
                SELECT 
                    i.id_estudiante,
                    n.nom_nivel,
                    n.num_nivel,
                    s.nom_seccion,
                    per.descripcion_periodo
                FROM inscripciones i
                INNER JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion
                INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                INNER JOIN periodos per ON i.id_periodo = per.id_periodo
                WHERE i.id_inscripcion IN (
                    SELECT MAX(i2.id_inscripcion)
                    FROM inscripciones i2
                    WHERE i2.id_estudiante = i.id_estudiante
                    AND i2.estatus = 1
                )
            ) ult_insc ON e.id_estudiante = ult_insc.id_estudiante
            
            WHERE er.id_representante = ? 
            AND er.estatus = 1 
            AND e.estatus = 1
            ORDER BY p.primer_nombre, p.primer_apellido
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_periodo_activo, $id_representante]);
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Log para debugging
    error_log("Período activo: " . $desc_periodo_activo . " (ID: " . $id_periodo_activo . ")");
    error_log("Estudiantes encontrados: " . count($estudiantes));

    echo json_encode([
      'success' => true,
      'estudiantes' => $estudiantes,
      'periodo_activo' => [
        'id' => $id_periodo_activo,
        'descripcion' => $desc_periodo_activo
      ],
      'total' => count($estudiantes)
    ]);
  }
} catch (PDOException $e) {
  error_log("Error en estudiantes_por_representante: " . $e->getMessage());
  echo json_encode([
    'success' => false,
    'message' => 'Error de base de datos: ' . $e->getMessage()
  ]);
} catch (Exception $e) {
  error_log("Error general: " . $e->getMessage());
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
