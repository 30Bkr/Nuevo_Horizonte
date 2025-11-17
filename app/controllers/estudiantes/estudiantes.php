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

      return true;
    } catch (PDOException $e) {
      throw new Exception("Error al agregar patología: " . $e->getMessage());
    }
  }
}
