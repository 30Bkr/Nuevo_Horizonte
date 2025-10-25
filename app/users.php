<?php
include_once '/xampp/htdocs/final/global/utils.php';
include_once '/xampp/htdocs/final/app/conexion.php';


class Usuarios
{
  public $id_usuario;
  public $id_persona;
  public $id_rol;
  public $nombre_usuario;
  public $usuario;
  public $contraseña;
  public $estado_cuenta;
  public $cedula;
  public $cargo;
  public $correo;
  public $nombre;
  public $apellido;

  public function createUser()
  {
    $conexion = new Conexion();
    $objConexion = $conexion->conectar();
    $sql = "INSERT INTO usuarios (id_persona, id_rol, nombre_usuario, usuario, contraseña, estado_cuenta)
            VALUES (:id_persona, :id_rol, :nombre_usuario, :usuario, :contraseña, :estado_cuenta)";
    $stmt = $objConexion->prepare($sql);
    $stmt->bindParam(":id_persona", $this->id_persona);
    $stmt->bindParam(":id_rol", $this->id_rol);
    $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
    $stmt->bindParam(":usuario", $this->usuario);
    $stmt->bindParam(":contraseña", $this->contraseña);
    $stmt->bindParam(":estado_cuenta", $this->estado_cuenta);
    $stmt->execute();
    $stmt = null;
    $conexion->desconectar();
    echo "Cuenta creada con exito";
  }
  public function listar()
  {
    $conexion = new Conexion();
    $objConexion = $conexion->conectar();
    $sql = "SELECT * FROM usuarios";
    $stmt = $objConexion->prepare($sql);
    $stmt->execute();
    $listarUsuarios = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      $objUsuarios = new Usuarios();
      $objUsuarios->id_persona = $row->id_persona;
      $objUsuarios->id_rol = $row->id_rol;
      $objUsuarios->nombre_usuario = $row->nombre_usuario;
      $objUsuarios->usuario = $row->usuario;
      $objUsuarios->contraseña = $row->contraseña;
      $objUsuarios->estado_cuenta = $row->estado_cuenta;
      $listarUsuarios[] = $objUsuarios;
    }
    return $listarUsuarios;
    $stmt = null;
    $conexion->desconectar();
  }
  public function listarAll()
  {
    $conexion = new Conexion();
    $objConexion = $conexion->conectar();
    $sql = "SELECT * FROM usuarios AS usu
              INNER JOIN roles as rol ON usu.id_rol = rol.id_rol 
              INNER JOIN personas as per ON usu.id_persona = per.id_persona ";
    $stmt = $objConexion->prepare($sql);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    $resultados_personas = $stmt->fetchAll();
    return $resultados_personas;
    $stmt = null;
    $conexion->desconectar();
  }

  public function consultar($aqui)
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      // echo "<pre>";
      // var_dump($aqui);
      // echo "</pre>";
      $sql = "SELECT * FROM usuarios WHERE nombre_usuario = '$aqui' AND estado_cuenta = 0";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Usuarios();
        $objUsuarios->id_usuario = $row->id_usuario;
        $objUsuarios->id_persona = $row->id_persona;
        $objUsuarios->id_rol = $row->id_rol;
        $objUsuarios->nombre_usuario = $row->nombre_usuario;
        $objUsuarios->usuario = $row->usuario;
        $objUsuarios->contraseña = $row->contraseña;
        $objUsuarios->estado_cuenta = $row->estado_cuenta;
        $listarUsuarios[] = $objUsuarios;
      }
      return $listarUsuarios;
      $stmt = null;
      $conexion->desconectar();
    } catch (PDOEXception $th) {
      echo "Error al establecer conexion Acaa:" . $th->getMessage();
    }
  }
  public function info($aqui)
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "SELECT * FROM usuarios AS usu
              INNER JOIN roles as rol ON usu.id_rol = rol.id_rol 
              INNER JOIN personas as per ON usu.id_persona = per.id_persona 
              WHERE usu.nombre_usuario = '$aqui' AND usu.estado_cuenta = 0
      ";
      // $sql = "SELECT * FROM personas INNER JOIN usuarios on personas.id_persona = usuarios.id_persona WHERE usuarios.id_usuario = $aqui";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarInfoUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Usuarios();
        $objUsuarios->correo = $row->nombre_usuario;
        $objUsuarios->cedula = $row->usuario;
        $objUsuarios->cargo = $row->nombre_rol;
        $objUsuarios->nombre = $row->nombres;
        $objUsuarios->apellido = $row->apellidos;
        $listarInfoUsuarios[] = $objUsuarios;
      }
      return $listarInfoUsuarios;
      $stmt = null;
      $conexion->desconectar();
      // $stmt = $objConexion->prepare($sql);
      // $stmt->setFetchMode(PDO::FETCH_ASSOC);
      // $stmt->execute();
      // $resultados_personas = $stmt->fetchAll();
      // return $resultados_personas;
      // $stmt = null;
      // $conexion->desconectar();
    } catch (PDOEXception $th) {
      echo "Error al establecer conexion Acaa info:" . $th->getMessage();
    }
  }
}
