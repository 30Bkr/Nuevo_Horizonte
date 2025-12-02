<?php
class ProfesionController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function obtenerProfesiones()
  {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM profesiones ORDER BY creacion DESC");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener profesiones: " . $e->getMessage());
    }
  }

  public function verificarProfesionExistente($nombre)
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM profesiones WHERE profesion = ?");
      $stmt->execute([trim($nombre)]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] > 0;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function agregarProfesion($nombre)
  {
    try {
      // Primero verificamos si ya existe
      if ($this->verificarProfesionExistente($nombre)) {
        return [
          'success' => false,
          'message' => "No se puede agregar la profesión \"$nombre\" porque ya se encuentra registrada.",
          'duplicate' => true
        ];
      }

      $stmt = $this->conn->prepare("INSERT INTO profesiones (profesion) VALUES (?)");
      $stmt->execute([trim($nombre)]);
      return ['success' => true, 'message' => 'Profesión agregada correctamente'];
    } catch (PDOException $e) {
      // Manejo específico para error de duplicado
      if ($e->getCode() == '23000' || strpos($e->getMessage(), '1062') !== false) {
        return [
          'success' => false,
          'message' => "No se puede agregar la profesión \"$nombre\" porque ya se encuentra registrada.",
          'duplicate' => true
        ];
      }
      return ['success' => false, 'message' => 'Error al agregar profesión: ' . $e->getMessage()];
    }
  }

  public function actualizarProfesion($id, $nombre, $estatus)
  {
    try {
      // Verificar si el nuevo nombre ya existe en otro registro
      $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM profesiones WHERE profesion = ? AND id_profesion != ?");
      $stmt->execute([trim($nombre), $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['total'] > 0) {
        return [
          'success' => false,
          'message' => "No se puede actualizar la profesión a \"$nombre\" porque ya existe otra profesión con ese nombre.",
          'duplicate' => true
        ];
      }

      $stmt = $this->conn->prepare("UPDATE profesiones SET profesion = ?, estatus = ?, actualizacion = NOW() WHERE id_profesion = ?");
      $stmt->execute([trim($nombre), $estatus, $id]);
      return ['success' => true, 'message' => 'Profesión actualizada correctamente'];
    } catch (PDOException $e) {
      // Manejo específico para error de duplicado
      if ($e->getCode() == '23000' || strpos($e->getMessage(), '1062') !== false) {
        return [
          'success' => false,
          'message' => "No se puede actualizar la profesión a \"$nombre\" porque ya existe otra profesión con ese nombre.",
          'duplicate' => true
        ];
      }
      return ['success' => false, 'message' => 'Error al actualizar profesión: ' . $e->getMessage()];
    }
  }

  public function contarUsosProfesion()
  {
    try {
      // Contar uso en representantes
      $stmtRep = $this->conn->prepare("SELECT COUNT(*) as total FROM representantes WHERE id_profesion IS NOT NULL");
      $stmtRep->execute();
      $repCount = $stmtRep->fetch(PDO::FETCH_ASSOC)['total'];

      // Contar uso en docentes
      $stmtDoc = $this->conn->prepare("SELECT COUNT(*) as total FROM docentes WHERE id_profesion IS NOT NULL");
      $stmtDoc->execute();
      $docCount = $stmtDoc->fetch(PDO::FETCH_ASSOC)['total'];

      return $repCount + $docCount;
    } catch (PDOException $e) {
      return 0;
    }
  }

  public function obtenerTodasLasProfesiones()
  {
    try {
      $sql = "SELECT * FROM profesiones ORDER BY creacion DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerTodasLasProfesiones: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Verifica si una profesión está en uso por representantes o docentes
   */
  public function profesionEnUso($id_profesion)
  {
    try {
      // Verificar uso en representantes
      $stmtRep = $this->conn->prepare("SELECT COUNT(*) as count FROM representantes WHERE id_profesion = ?");
      $stmtRep->execute([$id_profesion]);
      $repResult = $stmtRep->fetch(PDO::FETCH_ASSOC);

      // Verificar uso en docentes
      $stmtDoc = $this->conn->prepare("SELECT COUNT(*) as count FROM docentes WHERE id_profesion = ?");
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
      $stmtRep = $this->conn->prepare("SELECT COUNT(*) as count FROM representantes WHERE id_profesion = ?");
      $stmtRep->execute([$id_profesion]);
      $repResult = $stmtRep->fetch(PDO::FETCH_ASSOC);

      // Contar uso en docentes
      $stmtDoc = $this->conn->prepare("SELECT COUNT(*) as count FROM docentes WHERE id_profesion = ?");
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
      $stmt = $this->conn->prepare($sql);
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
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasProfesiones: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }
}
