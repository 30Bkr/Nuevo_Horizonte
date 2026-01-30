<?php
class DiscapacidadController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function obtenerDiscapacidadesPorEstudiante($id_estudiante)
  {
    try {
      $sql = "SELECT d.id_discapacidad, d.nom_discapacidad 
                FROM estudiantes_discapacidades ed 
                JOIN discapacidades d ON ed.id_discapacidad = d.id_discapacidad 
                WHERE ed.id_estudiante = ? AND ed.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estudiante]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error obteniendo discapacidades del estudiante: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtiene todas las discapacidades activas
   */
  public function obtenerDiscapacidadesActivas()
  {
    try {
      $sql = "SELECT * FROM discapacidades WHERE estatus = 1 ORDER BY nom_discapacidad";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerDiscapacidadesActivas: " . $e->getMessage());
      return [];
    }
  }
  public function obtenerDiscapacidadesActivas2()
  {
    try {
      $sql = "SELECT * FROM discapacidades ORDER BY nom_discapacidad";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerDiscapacidadesActivas: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtiene una discapacidad por ID
   */
  public function obtenerDiscapacidadPorId($id_discapacidad)
  {
    try {
      $sql = "SELECT * FROM discapacidades WHERE id_discapacidad = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_discapacidad]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerDiscapacidadPorId: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Crea una nueva discapacidad
   */
  public function crearDiscapacidad($nom_discapacidad)
  {
    try {
      $sql = "INSERT INTO discapacidades (nom_discapacidad) VALUES (?)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$nom_discapacidad]);
      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error en crearDiscapacidad: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Actualiza una discapacidad
   */
  public function actualizarDiscapacidad($id_discapacidad, $nom_discapacidad)
  {
    try {
      $sql = "UPDATE discapacidades SET nom_discapacidad = ?, actualizacion = NOW() WHERE id_discapacidad = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$nom_discapacidad, $id_discapacidad]);
    } catch (PDOException $e) {
      error_log("Error en actualizarDiscapacidad: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Elimina (desactiva) una discapacidad
   */
  public function eliminarDiscapacidad($id_discapacidad)
  {
    try {
      $sql = "UPDATE discapacidades SET estatus = 0, actualizacion = NOW() WHERE id_discapacidad = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$id_discapacidad]);
    } catch (PDOException $e) {
      error_log("Error en eliminarDiscapacidad: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene todas las discapacidades (incluyendo inactivas) para administración
   */
  public function obtenerTodasLasDiscapacidades()
  {
    try {
      $sql = "SELECT * FROM discapacidades ORDER BY id_discapacidad ASC";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerTodasLasDiscapacidades: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Cuenta las asignaciones de discapacidades a estudiantes
   */
  public function contarAsignacionesEstudiantes()
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM estudiantes_discapacidades WHERE estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    } catch (PDOException $e) {
      error_log("Error en contarAsignacionesEstudiantes: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Verifica si una discapacidad está en uso por estudiantes
   */
  public function discapacidadEnUso($id_discapacidad)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_discapacidades WHERE id_discapacidad = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_discapacidad]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'] > 0;
    } catch (PDOException $e) {
      error_log("Error en discapacidadEnUso: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene el conteo específico de usos de una discapacidad
   */
  public function obtenerConteoUsosDiscapacidad($id_discapacidad)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_discapacidades WHERE id_discapacidad = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_discapacidad]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'];
    } catch (PDOException $e) {
      error_log("Error en obtenerConteoUsosDiscapacidad: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Cambia el estatus de una discapacidad (activar/desactivar)
   */
  public function cambiarEstatusDiscapacidad($id_discapacidad, $estatus)
  {
    try {
      $sql = "UPDATE discapacidades SET estatus = ?, actualizacion = NOW() WHERE id_discapacidad = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_discapacidad]);
    } catch (PDOException $e) {
      error_log("Error en cambiarEstatusDiscapacidad: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene estadísticas generales de discapacidades
   */
  public function obtenerEstadisticasDiscapacidades()
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as activas,
              SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inactivas
              FROM discapacidades";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasDiscapacidades: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }
}
