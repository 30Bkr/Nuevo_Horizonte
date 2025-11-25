<?php
class EdadesController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function obtenerConfiguracionEdades()
  {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM globales WHERE id_globales = 1");
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener configuración de edades: " . $e->getMessage());
    }
  }

  public function actualizarConfiguracionEdades($edadMin, $edadMax)
  {
    try {
      // Validar que las edades sean lógicas
      if ($edadMin >= $edadMax) {
        return [
          'success' => false,
          'message' => 'La edad mínima debe ser menor que la edad máxima.'
        ];
      }

      if ($edadMin < 0 || $edadMax < 0) {
        return [
          'success' => false,
          'message' => 'Las edades no pueden ser números negativos.'
        ];
      }

      if ($edadMin > 25 || $edadMax > 25) {
        return [
          'success' => false,
          'message' => 'Las edades no pueden ser mayores a 25 años para educación básica.'
        ];
      }

      $stmt = $this->conn->prepare("UPDATE globales SET edad_min = ?, edad_max = ? WHERE id_globales = 1");
      $stmt->execute([$edadMin, $edadMax]);

      return [
        'success' => true,
        'message' => 'Configuración de edades actualizada correctamente',
        'data' => [
          'edad_min' => $edadMin,
          'edad_max' => $edadMax
        ]
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        'message' => 'Error al actualizar configuración: ' . $e->getMessage()
      ];
    }
  }

  public function obtenerEstadisticasEdades()
  {
    try {
      // Obtener cantidad de estudiantes por edad en el período actual
      $stmt = $this->conn->prepare("
                SELECT 
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) as edad,
                    COUNT(*) as cantidad
                FROM estudiantes e 
                JOIN personas p ON e.id_persona = p.id_persona 
                JOIN inscripciones i ON e.id_estudiante = i.id_estudiante 
                WHERE i.id_periodo = (SELECT id_periodo FROM globales WHERE id_globales = 1)
                AND i.estatus = 1
                AND e.estatus = 1
                GROUP BY edad
                ORDER BY edad
            ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  public function obtenerEstudiantesFueraRango()
  {
    try {
      // Obtener estudiantes fuera del rango de edad actual
      $config = $this->obtenerConfiguracionEdades();

      $stmt = $this->conn->prepare("
                SELECT 
                    p.primer_nombre,
                    p.primer_apellido,
                    p.fecha_nac,
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) as edad,
                    n.nom_nivel,
                    s.nom_seccion
                FROM estudiantes e 
                JOIN personas p ON e.id_persona = p.id_persona 
                JOIN inscripciones i ON e.id_estudiante = i.id_estudiante 
                JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion
                JOIN niveles n ON ns.id_nivel = n.id_nivel
                JOIN secciones s ON ns.id_seccion = s.id_seccion
                WHERE i.id_periodo = (SELECT id_periodo FROM globales WHERE id_globales = 1)
                AND i.estatus = 1
                AND e.estatus = 1
                AND (
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) < ?
                    OR YEAR(CURDATE()) - YEAR(p.fecha_nac) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) > ?
                )
                ORDER BY edad
            ");
      $stmt->execute([$config['edad_min'], $config['edad_max']]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }
}
