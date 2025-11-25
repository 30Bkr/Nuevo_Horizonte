<?php
class NivelController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // Obtener todos los niveles activos
  public function obtenerNiveles()
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT id_nivel, num_nivel, nom_nivel 
                FROM niveles 
                WHERE estatus = 1 
                ORDER BY num_nivel ASC
            ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener niveles: " . $e->getMessage());
    }
  }

  // Obtener secciones disponibles para un nivel específico
  public function obtenerSeccionesPorNivel($id_nivel)
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT 
                    ns.id_nivel_seccion,
                    s.id_seccion,
                    s.nom_seccion,
                    ns.capacidad,
                    COALESCE(COUNT(i.id_inscripcion), 0) as inscritos,
                    (ns.capacidad - COALESCE(COUNT(i.id_inscripcion), 0)) as cupos_disponibles
                FROM niveles_secciones ns
                INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                LEFT JOIN inscripciones i ON ns.id_nivel_seccion = i.id_nivel_seccion 
                    AND i.estatus = 1
                    AND i.id_periodo = (
                        SELECT id_periodo FROM periodos WHERE estatus = 1 LIMIT 1
                    )
                WHERE ns.id_nivel = ? 
                    AND ns.estatus = 1 
                    AND s.estatus = 1
                GROUP BY ns.id_nivel_seccion, s.id_seccion, s.nom_seccion, ns.capacidad
                HAVING cupos_disponibles > 0
                ORDER BY s.nom_seccion ASC
            ");
      $stmt->execute([$id_nivel]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener secciones: " . $e->getMessage());
    }
  }

  // Obtener todas las secciones (para uso general)
  public function obtenerTodasLasSecciones()
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT id_seccion, nom_seccion 
                FROM secciones 
                WHERE estatus = 1 
                ORDER BY nom_seccion ASC
            ");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener secciones: " . $e->getMessage());
    }
  }
}
