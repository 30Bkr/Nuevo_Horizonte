<?php
class PatologiaController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function obtenerPatologiasPorEstudiante($id_estudiante)
  {
    try {
      $sql = "SELECT p.id_patologia, p.nom_patologia 
                FROM estudiantes_patologias ep 
                JOIN patologias p ON ep.id_patologia = p.id_patologia 
                WHERE ep.id_estudiante = ? AND ep.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estudiante]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error obteniendo patologías del estudiante: " . $e->getMessage());
      return [];
    }
  }

  public function obtenerPatologiasActivas()
  {
    try {
      $sql = "SELECT id_patologia, nom_patologia 
                    FROM patologias 
                    WHERE estatus = 1 
                    ORDER BY nom_patologia";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener patologías: " . $e->getMessage());
    }
  }

  public function obtenerPatologiaPorId($id_patologia)
  {
    try {
      $sql = "SELECT * FROM patologias WHERE id_patologia = :id_patologia AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_patologia' => $id_patologia]);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener patología: " . $e->getMessage());
    }
  }

  public function crearPatologia($nombre_patologia)
  {
    try {
      $sql = "INSERT INTO patologias (nom_patologia) VALUES (:nom_patologia)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':nom_patologia' => $nombre_patologia]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear patología: " . $e->getMessage());
    }
  }

  public function obtenerPatologias()
  {
    try {
      $stmt = $this->pdo->prepare("SELECT * FROM patologias ORDER BY creacion DESC");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener patologías: " . $e->getMessage());
    }
  }

  public function agregarPatologia($nombre)
  {
    try {
      $stmt = $this->pdo->prepare("INSERT INTO patologias (nom_patologia) VALUES (?)");
      $stmt->execute([trim($nombre)]);
      return ['success' => true, 'message' => 'Patología agregada correctamente'];
    } catch (PDOException $e) {
      return ['success' => false, 'message' => 'Error al agregar patología: ' . $e->getMessage()];
    }
  }

  public function actualizarPatologia($id, $nombre, $estatus)
  {
    try {
      $stmt = $this->pdo->prepare("UPDATE patologias SET nom_patologia = ?, estatus = ?, actualizacion = NOW() WHERE id_patologia = ?");
      $stmt->execute([trim($nombre), $estatus, $id]);
      return ['success' => true, 'message' => 'Patología actualizada correctamente'];
    } catch (PDOException $e) {
      return ['success' => false, 'message' => 'Error al actualizar patología: ' . $e->getMessage()];
    }
  }

  public function contarAsignacionesEstudiantes()
  {
    try {
      $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM estudiantes_patologias");
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    } catch (PDOException $e) {
      return 0;
    }
  }
  /**
   * Verifica si una patología está en uso por estudiantes
   */
  public function patologiaEnUso($id_patologia)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_patologias WHERE id_patologia = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_patologia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'] > 0;
    } catch (PDOException $e) {
      error_log("Error en patologiaEnUso: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene el conteo específico de usos de una patología
   */
  public function obtenerConteoUsosPatologia($id_patologia)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_patologias WHERE id_patologia = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_patologia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'];
    } catch (PDOException $e) {
      error_log("Error en obtenerConteoUsosPatologia: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Obtiene estadísticas generales de patologías
   */
  public function obtenerEstadisticasPatologias()
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as activas,
              SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inactivas
              FROM patologias";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasPatologias: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }
}
