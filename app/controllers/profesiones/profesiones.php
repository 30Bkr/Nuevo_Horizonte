<?php
class ProfesionController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  /**
   * Obtiene todas las profesiones activas
   */
  public function obtenerProfesionesActivas()
  {
    try {
      $sql = "SELECT * FROM profesiones WHERE estatus = 1 ORDER BY profesion";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerProfesionesActivas: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtiene una profesión por ID
   */
  public function obtenerProfesionPorId($id_profesion)
  {
    try {
      $sql = "SELECT * FROM profesiones WHERE id_profesion = ?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_profesion]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerProfesionPorId: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Crea una nueva profesión
   */
  public function crearProfesion($profesion)
  {
    try {
      $sql = "INSERT INTO profesiones (profesion) VALUES (?)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$profesion]);
      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error en crearProfesion: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Actualiza una profesión
   */
  public function actualizarProfesion($id_profesion, $profesion)
  {
    try {
      $sql = "UPDATE profesiones SET profesion = ?, actualizacion = NOW() WHERE id_profesion = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$profesion, $id_profesion]);
    } catch (PDOException $e) {
      error_log("Error en actualizarProfesion: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene todas las profesiones (incluyendo inactivas) para administración
   */
  public function obtenerTodasLasProfesiones()
  {
    try {
      $sql = "SELECT * FROM profesiones ORDER BY profesion";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerTodasLasProfesiones: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Cuenta las asignaciones de profesiones a representantes y docentes
   */
  public function contarAsignaciones()
  {
    try {
      // Contar uso en representantes
      $sqlRep = "SELECT COUNT(*) as total FROM representantes WHERE id_profesion IS NOT NULL";
      $stmtRep = $this->pdo->prepare($sqlRep);
      $stmtRep->execute();
      $repResult = $stmtRep->fetch(PDO::FETCH_ASSOC);

      // Contar uso en docentes
      $sqlDoc = "SELECT COUNT(*) as total FROM docentes WHERE id_profesion IS NOT NULL";
      $stmtDoc = $this->pdo->prepare($sqlDoc);
      $stmtDoc->execute();
      $docResult = $stmtDoc->fetch(PDO::FETCH_ASSOC);

      return ($repResult['total'] + $docResult['total']);
    } catch (PDOException $e) {
      error_log("Error en contarAsignaciones: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Verifica si una profesión está en uso por representantes o docentes
   */
  public function profesionEnUso($id_profesion)
  {
    try {
      // Verificar uso en representantes
      $sqlRep = "SELECT COUNT(*) as count FROM representantes WHERE id_profesion = ?";
      $stmtRep = $this->pdo->prepare($sqlRep);
      $stmtRep->execute([$id_profesion]);
      $repResult = $stmtRep->fetch(PDO::FETCH_ASSOC);

      // Verificar uso en docentes
      $sqlDoc = "SELECT COUNT(*) as count FROM docentes WHERE id_profesion = ?";
      $stmtDoc = $this->pdo->prepare($sqlDoc);
      $stmtDoc->execute([$id_profesion]);
      $docResult = $stmtDoc->fetch(PDO::FETCH_ASSOC);

      return ($repResult['count'] + $docResult['count']) > 0;
    } catch (PDOException $e) {
      error_log("Error en profesionEnUso: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene el conteo específico de usos de una profesión
   */
  public function obtenerConteoUsosProfesion($id_profesion)
  {
    try {
      // Contar uso en representantes
      $sqlRep = "SELECT COUNT(*) as count FROM representantes WHERE id_profesion = ?";
      $stmtRep = $this->pdo->prepare($sqlRep);
      $stmtRep->execute([$id_profesion]);
      $repResult = $stmtRep->fetch(PDO::FETCH_ASSOC);

      // Contar uso en docentes
      $sqlDoc = "SELECT COUNT(*) as count FROM docentes WHERE id_profesion = ?";
      $stmtDoc = $this->pdo->prepare($sqlDoc);
      $stmtDoc->execute([$id_profesion]);
      $docResult = $stmtDoc->fetch(PDO::FETCH_ASSOC);

      return $repResult['count'] + $docResult['count'];
    } catch (PDOException $e) {
      error_log("Error en obtenerConteoUsosProfesion: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Cambia el estatus de una profesión (activar/desactivar)
   */
  public function cambiarEstatusProfesion($id_profesion, $estatus)
  {
    try {
      $sql = "UPDATE profesiones SET estatus = ?, actualizacion = NOW() WHERE id_profesion = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_profesion]);
    } catch (PDOException $e) {
      error_log("Error en cambiarEstatusProfesion: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene estadísticas generales de profesiones
   */
  public function obtenerEstadisticasProfesiones()
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as activas,
              SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inactivas
              FROM profesiones";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasProfesiones: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }
}
