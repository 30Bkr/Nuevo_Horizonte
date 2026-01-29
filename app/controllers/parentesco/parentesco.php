<?php
class ParentescoController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function mostrarParentescos()
  {
    try {
      $sql = "SELECT * FROM parentesco ORDER BY parentesco";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estados: " . $e->getMessage());
    }
  }

  public function mostrarParentescosOn()
  {
    try {
      $sql = "SELECT * FROM parentesco WHERE estatus = 1 ORDER BY parentesco;";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estados: " . $e->getMessage());
    }
  }
}
