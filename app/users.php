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
  public $contrasena;
  public $estatus;
  public $cedula;
  public $cargo;
  public $correo;
  public $nombre;
  public $apellido;
  public function createUser()
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();

      // ANTES (SHA256):
      // $hashContrasena = hash('sha256', $this->contraseña);

      // AHORA (BCRYPT):
      $hashContrasena = password_hash($this->contraseña, PASSWORD_BCRYPT, ['cost' => 12]);

      $sql = "INSERT INTO usuarios (id_persona, id_rol, nombre_usuario, usuario, contraseña, estatus, contrasena_migrada)
                VALUES (:id_persona, :id_rol, :nombre_usuario, :usuario, :contrasena, :estatus, 1)"; // 1 = ya migrado

      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(":id_persona", $this->id_persona);
      $stmt->bindParam(":id_rol", $this->id_rol);
      $stmt->bindParam(":nombre_usuario", $this->nombre_usuario);
      $stmt->bindParam(":usuario", $this->usuario);
      $stmt->bindParam(":contrasena", $hashContrasena);
      $stmt->bindParam(":estatus", $this->estatus);

      if ($stmt->execute()) {
        return "Cuenta creada con éxito (contraseña BCRYPT)";
      } else {
        return "Error al crear la cuenta";
      }
    } catch (PDOException $e) {
      error_log("Error en createUser: " . $e->getMessage());
      return "Error en el sistema";
    }
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
      $objUsuarios->estatus = $row->estatus;
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

  public function consultar($usuario)
  {
    try {
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();

      // Usar prepared statements para seguridad
      $sql = "SELECT * FROM usuarios WHERE usuario = :usuario AND estatus = '1'";
      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(':usuario', $usuario);
      $stmt->execute();

      $listarUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Usuarios();
        $objUsuarios->id_usuario = $row->id_usuario;
        $objUsuarios->id_persona = $row->id_persona;
        $objUsuarios->id_rol = $row->id_rol;
        $objUsuarios->usuario = $row->usuario;
        $objUsuarios->contrasena = $row->contrasena;
        $objUsuarios->estatus = $row->estatus;
        $listarUsuarios[] = $objUsuarios;
      }
      return $listarUsuarios;
    } catch (PDOException $th) {
      error_log("Error en consultar usuario: " . $th->getMessage());
      return array();
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
              WHERE usu.usuario = '$aqui' AND usu.estatus = 1
      ";
      // $sql = "SELECT * FROM personas INNER JOIN usuarios on personas.id_persona = usuarios.id_persona WHERE usuarios.id_usuario = $aqui";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listarInfoUsuarios = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objUsuarios = new Usuarios();
        $objUsuarios->correo = $row->usuario;
        $objUsuarios->cargo = $row->nom_rol;
        $objUsuarios->nombre = $row->primer_nombre;
        $objUsuarios->apellido = $row->primer_apellido;
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
