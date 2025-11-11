<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class NivelController
{
  private $conn;

  public function __construct()
  {
    $conexion = new Conexion();
    $this->conn = $conexion->conectar();
  }

  /**
   * Obtener todos los niveles activos
   */
  public function getNiveles()
  {
    $query = "SELECT id_nivel, num_nivel, nom_nivel 
                  FROM niveles 
                  WHERE estatus = 1 
                  ORDER BY num_nivel";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
