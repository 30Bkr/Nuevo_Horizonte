<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class PatologiaController
{
  private $conn;

  public function __construct()
  {
    $conexion = new Conexion();
    $this->conn = $conexion->conectar();
  }

  /**
   * Obtener todas las patologías activas
   */
  public function getPatologias()
  {
    $query = "SELECT id_patologia, nom_patologia 
                  FROM patologias 
                  WHERE estatus = 1 
                  ORDER BY nom_patologia";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
