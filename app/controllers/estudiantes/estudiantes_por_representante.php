<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_representante = $_POST['id_representante'];

    // Consulta para obtener los estudiantes del representante
    $sql = "
            SELECT 
                e.id_estudiante,
                p.cedula,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.fecha_nac,
                p.sexo,
                p.id_direccion,
                er.parentesco,
                n.num_nivel,
                n.nom_nivel as nombre_nivel,
                ns.id_nivel_seccion,
                CONCAT(n.nom_nivel, ' - ', s.nom_seccion) as nivel_seccion,
                i.estatus as estatus_inscripcion
            FROM estudiantes_representantes er
            INNER JOIN estudiantes e ON er.id_estudiante = e.id_estudiante
            INNER JOIN personas p ON e.id_persona = p.id_persona
            LEFT JOIN inscripciones i ON e.id_estudiante = i.id_estudiante 
                AND i.id_periodo = (SELECT MAX(id_periodo) FROM periodos WHERE estatus = 1)
            LEFT JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion
            LEFT JOIN niveles n ON ns.id_nivel = n.id_nivel
            LEFT JOIN secciones s ON ns.id_seccion = s.id_seccion
            WHERE er.id_representante = ? AND er.estatus = 1 AND e.estatus = 1
            ORDER BY p.primer_nombre, p.primer_apellido
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_representante]);
    $estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
      'success' => true,
      'estudiantes' => $estudiantes,
      'total' => count($estudiantes)
    ]);
  }
} catch (PDOException $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Error: ' . $e->getMessage()
  ]);
}
