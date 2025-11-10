<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class UbicacionController
{
  private $conn;

  public function __construct()
  {
    $conexion = new Conexion();
    $this->conn = $conexion->conectar();
  }

  /**
   * Obtener todos los estados
   */
  public function getEstados()
  {
    $query = "SELECT id_estado as id, nom_estado as nombre 
                  FROM estados 
                  WHERE estatus = 1 
                  ORDER BY nom_estado";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Obtener municipios por estado
   */
  public function getMunicipios($estado_id)
  {
    $query = "SELECT id_municipio as id, nom_municipio as nombre 
                  FROM municipios 
                  WHERE id_estado = :estado_id AND estatus = 1 
                  ORDER BY nom_municipio";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([':estado_id' => $estado_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Obtener parroquias por municipio
   */
  public function getParroquias($municipio_id)
  {
    $query = "SELECT id_parroquia as id, nom_parroquia as nombre 
                  FROM parroquias 
                  WHERE id_municipio = :municipio_id AND estatus = 1 
                  ORDER BY nom_parroquia";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([':municipio_id' => $municipio_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Obtener ubicación completa por ID de parroquia
   */
  public function getUbicacionCompleta($id_parroquia)
  {
    $query = "SELECT p.id_parroquia, p.nom_parroquia, 
                         m.id_municipio, m.nom_municipio,
                         e.id_estado, e.nom_estado
                  FROM parroquias p
                  JOIN municipios m ON p.id_municipio = m.id_municipio
                  JOIN estados e ON m.id_estado = e.id_estado
                  WHERE p.id_parroquia = :id_parroquia 
                  AND p.estatus = 1 AND m.estatus = 1 AND e.estatus = 1";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([':id_parroquia' => $id_parroquia]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function __destruct()
  {
    $this->conn = null;
  }
}
