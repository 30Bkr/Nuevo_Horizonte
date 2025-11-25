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
}
