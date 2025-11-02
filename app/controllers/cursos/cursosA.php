<?php
include_once('c:/xampp/htdocs/final/app/conexion.php');

class Cursos2
{
  public $id_año_seccion;
  public $id_grado_seccion;
  public $id_grado;
  public $id_año;
  public $id_seccion;
  public $grado;
  public $año;
  public $turno;
  public $nom_seccion;
  public $descripcion;
  public $observacion;
  public $descripcion3;
  public $capacidad;

  public function crearAño2()
  {
    try {
      // session_start();
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "INSERT INTO años (año)
              VALUES (?)";
      $stmt = $objConexion->prepare($sql);
      // $stmt->bindParam(':año', $this->año);
      // $stmt->bindParam(':descripcion', $this->descripcion);
      $stmt->execute([$this->año]);
      $ultimoAño = $objConexion->lastInsertId();

      echo "hoola";
      $sql2 = "INSERT INTO secciones (nom_seccion, turno)
              VALUES (?,?)";
      $stmt2 = $objConexion->prepare($sql2);
      // $stmt2->bindParam(':nom_seccion', $this->nom_seccion);
      // $stmt2->bindParam(':turno', $this->turno);
      // $stmt2->bindParam(':descripcion', $this->observacion);
      $stmt2->execute([$this->nom_seccion, $this->turno]);
      $ultimaSeccion = $objConexion->lastInsertId();

      $sql3 = "INSERT INTO años_secciones (id_años, id_seccion, capacidad)
              VALUES (?, ?, ?)";
      $stmt3 = $objConexion->prepare($sql3);
      // $stmt3->bindParam(":id_año", $ultimoAño);
      // $stmt3->bindParam(":id_seccion", $ultimaSeccion);
      // $stmt3->bindParam(":capacidad", $this->capacidad);
      $stmt3->execute([$ultimoAño, $ultimaSeccion, $this->capacidad]);
      $stmt = null;
      $stmt2 = null;
      $stmt3 = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error" . $th->getMessage() . $th->getLine();
    }
  }
}
