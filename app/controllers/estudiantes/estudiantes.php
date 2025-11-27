<?php
class EstudianteController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Crear nuevo estudiante
  public function crearEstudiante($id_persona)
  {
    try {
      $sql = "INSERT INTO estudiantes (id_persona) VALUES (:id_persona)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_persona' => $id_persona]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear estudiante: " . $e->getMessage());
    }
  }

  // Verificar si persona ya es estudiante
  public function esEstudiante($id_persona)
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM estudiantes WHERE id_persona = :id_persona AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_persona' => $id_persona]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar estudiante: " . $e->getMessage());
    }
  }

  // Obtener estudiante por ID de persona
  public function obtenerPorPersona($id_persona)
  {
    try {
      $sql = "SELECT e.*, p.* 
                    FROM estudiantes e 
                    INNER JOIN personas p ON e.id_persona = p.id_persona 
                    WHERE e.id_persona = :id_persona AND e.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_persona' => $id_persona]);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estudiante: " . $e->getMessage());
    }
  }

  // Agregar patología a estudiante
  public function agregarPatologia($id_estudiante, $id_patologia)
  {
    try {
      $sql = "INSERT INTO estudiantes_patologias (id_estudiante, id_patologia) 
                    VALUES (:id_estudiante, :id_patologia)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_estudiante' => $id_estudiante,
        ':id_patologia' => $id_patologia
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al agregar patología: " . $e->getMessage());
    }
  }
  /**
   * Agrega una discapacidad a un estudiante
   */
  public function agregarDiscapacidad($id_estudiante, $id_discapacidad)
  {
    try {
      $sql = "INSERT INTO estudiantes_discapacidades (id_estudiante, id_discapacidad) VALUES (?, ?)";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$id_estudiante, $id_discapacidad]);
    } catch (PDOException $e) {
      error_log("Error en agregarDiscapacidad: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene las discapacidades de un estudiante
   */
  public function obtenerDiscapacidadesEstudiante($id_estudiante)
  {
    try {
      $sql = "SELECT d.* FROM discapacidades d
                INNER JOIN estudiantes_discapacidades ed ON d.id_discapacidad = ed.id_discapacidad
                WHERE ed.id_estudiante = ? AND ed.estatus = 1 AND d.estatus = 1
                ORDER BY d.nom_discapacidad";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estudiante]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerDiscapacidadesEstudiante: " . $e->getMessage());
      return [];
    }
  }
  /**
   * Elimina una discapacidad de un estudiante
   */
  public function eliminarDiscapacidad($id_estudiante, $id_discapacidad)
  {
    try {
      $sql = "UPDATE estudiantes_discapacidades SET estatus = 0, actualizacion = NOW() 
                WHERE id_estudiante = ? AND id_discapacidad = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$id_estudiante, $id_discapacidad]);
    } catch (PDOException $e) {
      error_log("Error en eliminarDiscapacidad: " . $e->getMessage());
      return false;
    }
  }
}
