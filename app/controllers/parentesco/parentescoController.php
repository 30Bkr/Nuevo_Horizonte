<?php
class ParentescoController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  // ========== MÉTODOS BÁSICOS ==========

  public function obtenerParentescos()
  {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM parentesco ORDER BY creacion DESC");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener parentescos: " . $e->getMessage());
    }
  }

  public function verificarParentescoExistente($nombre)
  {
    try {
      $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM parentesco WHERE parentesco = ?");
      $stmt->execute([trim($nombre)]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] > 0;
    } catch (PDOException $e) {
      return false;
    }
  }

  public function agregarParentesco($nombre)
  {
    try {
      // Primero verificamos si ya existe
      if ($this->verificarParentescoExistente($nombre)) {
        return [
          'success' => false,
          'message' => "No se puede agregar el parentesco \"$nombre\" porque ya se encuentra registrado.",
          'duplicate' => true
        ];
      }

      $stmt = $this->conn->prepare("INSERT INTO parentesco (parentesco) VALUES (?)");
      $stmt->execute([trim($nombre)]);
      return ['success' => true, 'message' => 'Parentesco agregado correctamente'];
    } catch (PDOException $e) {
      // Manejo específico para error de duplicado
      if ($e->getCode() == '23000' || strpos($e->getMessage(), '1062') !== false) {
        return [
          'success' => false,
          'message' => "No se puede agregar el parentesco \"$nombre\" porque ya se encuentra registrado.",
          'duplicate' => true
        ];
      }
      return ['success' => false, 'message' => 'Error al agregar parentesco: ' . $e->getMessage()];
    }
  }

  // ¡ESTE ES EL MÉTODO QUE FALTA!
  public function actualizarParentesco($id, $nombre, $estatus)
  {
    try {
      // Verificar si el nuevo nombre ya existe en otro registro
      $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM parentesco WHERE parentesco = ? AND id_parentesco != ?");
      $stmt->execute([trim($nombre), $id]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['total'] > 0) {
        return [
          'success' => false,
          'message' => "No se puede actualizar el parentesco a \"$nombre\" porque ya existe otro parentesco con ese nombre.",
          'duplicate' => true
        ];
      }

      $stmt = $this->conn->prepare("UPDATE parentesco SET parentesco = ?, estatus = ?, actualizacion = NOW() WHERE id_parentesco = ?");
      $stmt->execute([trim($nombre), $estatus, $id]);
      return ['success' => true, 'message' => 'Parentesco actualizado correctamente'];
    } catch (PDOException $e) {
      // Manejo específico para error de duplicado
      if ($e->getCode() == '23000' || strpos($e->getMessage(), '1062') !== false) {
        return [
          'success' => false,
          'message' => "No se puede actualizar el parentesco a \"$nombre\" porque ya existe otro parentesco con ese nombre.",
          'duplicate' => true
        ];
      }
      return ['success' => false, 'message' => 'Error al actualizar parentesco: ' . $e->getMessage()];
    }
  }

  // ========== MÉTODOS DE CONSULTA ==========

  public function contarUsosParentesco()
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM estudiantes_representantes WHERE id_parentesco IS NOT NULL";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      error_log("Error en contarUsosParentesco: " . $e->getMessage());
      return 0;
    }
  }

  public function obtenerTodosLosParentescos()
  {
    try {
      $sql = "SELECT * FROM parentesco ORDER BY creacion DESC";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerTodosLosParentescos: " . $e->getMessage());
      return [];
    }
  }

  public function parentescoEnUso($id_parentesco)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_representantes WHERE id_parentesco = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$id_parentesco]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] > 0;
    } catch (PDOException $e) {
      error_log("Error en parentescoEnUso: " . $e->getMessage());
      return false;
    }
  }

  public function obtenerConteoUsosParentesco($id_parentesco)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_representantes WHERE id_parentesco = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$id_parentesco]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] ?? 0;
    } catch (PDOException $e) {
      error_log("Error en obtenerConteoUsosParentesco: " . $e->getMessage());
      return 0;
    }
  }

  public function cambiarEstatusParentesco($id_parentesco, $estatus)
  {
    try {
      $sql = "UPDATE parentesco SET estatus = ?, actualizacion = NOW() WHERE id_parentesco = ?";
      $stmt = $this->conn->prepare($sql);
      return $stmt->execute([$estatus, $id_parentesco]);
    } catch (PDOException $e) {
      error_log("Error en cambiarEstatusParentesco: " . $e->getMessage());
      return false;
    }
  }

  public function obtenerEstadisticasParentescos()
  {
    try {
      $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as activas,
                    SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inactivas
                    FROM parentesco";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasParentescos: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }

  public function obtenerParentescosActivos()
  {
    try {
      $sql = "SELECT id_parentesco, parentesco FROM parentesco WHERE estatus = 1 ORDER BY parentesco";
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerParentescosActivos: " . $e->getMessage());
      return [];
    }
  }
}
