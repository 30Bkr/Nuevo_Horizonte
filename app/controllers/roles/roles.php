<?php

include_once('/xampp/htdocs/final/app/conexion.php');
class Roles
{
  public $id_rol;
  public $nombre_rol;


  public function createRol()
  {
    try {
      session_start();
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "INSERT INTO roles (nombre_rol)
          VALUES (:nombre_rol)";
      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(":nombre_rol", $this->nombre_rol);
      $stmt->execute();
      $stmt = null;
      $conexion->desconectar();
      echo "Cuenta creada con exito";
      $_SESSION['mensaje'] = "Registro exitoso";
      $_SESSION['icono'] = 'success';
    } catch (\Throwable $th) {
      session_start();
      $_SESSION['mensaje'] = "Oh no, no se pudo hacer el registro. Comuniquese con el administrador";
      $_SESSION['icono'] = "error";
      echo "Error al crear un nuevo rol" . $th->getMessage();
    }
  }
  public function listar()
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "SELECT * FROM roles WHERE id_rol";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Roles();
        $objUsuarios->id_rol = $row->id_rol;
        $objUsuarios->nombre_rol = $row->nombre_rol;
        $listarUsuarios[] = $objUsuarios;
      }
      return $listarUsuarios;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al listar los roles" . $th->getMessage();
    }
  }
  public function listar2()
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "SELECT * FROM roles WHERE id_rol != 2 && id_rol != 11 && id_rol !=10 && id_rol !=8 && id_rol !=9";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Roles();
        $objUsuarios->id_rol = $row->id_rol;
        $objUsuarios->nombre_rol = $row->nombre_rol;
        $listarUsuarios[] = $objUsuarios;
      }
      return $listarUsuarios;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al listar los roles" . $th->getMessage();
    }
  }
  public function actualizarR($id)
  {
    try {
      $conexion = new Conexion();
      $objRol = $conexion->conectar();
      $sql = "UPDATE roles 
            SET nombre_rol = :nombre_rol
            WHERE id_rol = $id";
      $stmt = $objRol->prepare($sql);
      $stmt->bindParam('nombre_rol', $this->nombre_rol);
      $stmt->execute();
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al listar los roles" . $th->getMessage();
    }
  }
  public function consultar($aqui)
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "SELECT * FROM roles WHERE id_rol = '$aqui'";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Roles();

        $objUsuarios->id_rol = $row->id_rol;
        $objUsuarios->nombre_rol = $row->nombre_rol;


        $listarUsuarios[] = $objUsuarios;
      }
      return $listarUsuarios;
      $stmt = null;
      $conexion->desconectar();
    } catch (PDOEXception $th) {
      echo "Error al consultar informacion del rol" . $th->getMessage();
    }
  }
  // public function info($aqui)
  // {
  //   try {
  //     $conexion = new Conexion();
  //     $objConexion = $conexion->conectar();
  //     $sql = "SELECT * FROM personas INNER JOIN roles on personas.id_persona = roles.id_persona WHERE roles.id_usuario = $aqui";
  //     $stmt = $objConexion->prepare($sql);
  //     $stmt->setFetchMode(PDO::FETCH_ASSOC);
  //     $stmt->execute();
  //     $resultados_personas = $stmt->fetchAll();
  //     return $resultados_personas;
  //     $stmt = null;
  //     $conexion->desconectar();
  //   } catch (PDOEXception $th) {
  //     echo "Error al establecer conexion Acaa info:" . $th->getMessage();
  //   }
  // }
}
