<?php
class PatologiaController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
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
}
