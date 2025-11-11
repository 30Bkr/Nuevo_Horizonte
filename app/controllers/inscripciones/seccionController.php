<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class SeccionController
{
  private $conn;

  public function __construct()
  {
    $conexion = new Conexion();
    $this->conn = $conexion->conectar();
  }

  /**
   * Obtener todas las secciones activas
   */
  public function getSecciones()
  {
    $query = "SELECT id_seccion, nom_seccion 
                  FROM secciones 
                  WHERE estatus = 1 
                  ORDER BY nom_seccion";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
