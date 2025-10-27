<?php
include_once '/xampp/htdocs/final/app/conexion.php';

class Persona
{
  public $rol;
  public $nombres;
  public $apellidos;
  public $cedula;
  public $telefono;
  public $telefono_hab;
  public $correo;
  public $lugar_nac;
  public $sexo;
  public $nacionalidad;
  public $fecha_nac;
  public $estado;
  public $parroquia;
  public $calle;
  public $casa;
  public $profesion;
  public $lugar_trabajo;
  public $id;

  //Muestra lista de los usuarios que estan en la base de datos
  public function index() {}
  //Muestra un formulario para crear un nuevo recurso
  public function create() {}
  //Guarda un nuevo recurso en la base de datos
  public function store() {}
  //Muestra un unico recursos especifico
  public function show() {}
  // Muestra el formulario para editar un unico recurso
  public function edit() {}
  // Actualiza un recurso especifico de la base de datos
  public function update() {}
  //Elemina un recurso de la tabla de datos
  public function destroy() {}
  //metodo

  public function registrar()
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();
    $sql =  "INSERT INTO persona(nombres, apellidos, correo, telefono, telefono_hab, fecha_nac, lugar_nac, cedula_esc,cedula,sexo,nacionalidad,edad,calle,casa,parroquia,profesion,lugar_trabajo,id_rol)
            VALUES (:nombres, :apellidos, :correo, :telefono, :telefono_hab, :fecha_nac, :lugar_nac, :cedula_esc, :decula, :sexo, :nacionalidad, :edad, :calle, :casa, :parroquia, :profesion, :lugar_trabajo, :id_rol);";
    $stmt = $objconexion->prepare($sql);

    $stmt->bindParam(':nombres', $this->nombres);
    $stmt->bindParam(':apellidos', $this->apellidos);
    $stmt->bindParam(':correo', $this->correo);
    $stmt->bindParam(':telefono', $this->telefono);
    $stmt->bindParam(':telefono_hab', $this->telefono_hab);
    $stmt->bindParam(':fecha_nac', $this->fecha_nac);
    $stmt->bindParam(':lugar_nac', $this->lugar_nac);
    $stmt->bindParam(':decula', $this->cedula);
    $stmt->bindParam(':sexo', $this->sexo);
    $stmt->bindParam(':nacionalidad', $this->nacionalidad);
    $stmt->bindParam(':calle', $this->calle);
    $stmt->bindParam(':casa', $this->casa);
    $stmt->bindParam(':parroquia', $this->parroquia);
    $stmt->bindParam(':profesion', $this->profesion);
    $stmt->bindParam(':lugar_trabajo', $this->lugar_trabajo);
    $stmt->bindParam(':id_rol', $this->rol);
    $stmt->execute();
    $stmt = null;
    $conexion->desconectar();
    echo 'Usuario registrado con exito';
  }
  public function listar()
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();
    $sql = "SELECT nombres, apellidos, correo, telefono, rol, id FROM usuario";
    $stmt = $objconexion->prepare($sql);
    $stmt->execute();
    $listarPersona = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      $objPersona = new Persona();
      $objPersona->nombres = $row->nombres;
      $objPersona->apellidos = $row->apellidos;
      $objPersona->correo = $row->correo;
      $objPersona->telefono = $row->telefono;
      $objPersona->rol = $row->rol;
      $objPersona->id = $row->id;
      $listarPersona[] = $objPersona;
    }
    return $listarPersona;
    $stmt = null;
    $conexion->desconectar();
  }
  public function eliminar($id)
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();

    $sql = "DELETE from usuario where id = '$id'";
    $stmt = $objconexion->prepare($sql);
    $stmt->execute();
    $stmt = null;
    $conexion->desconectar();
  }
  public function consultar($id)
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();

    $sql = "SELECT * FROM personas WHERE id_persona = $id";
    $stmt = $objconexion->prepare($sql);
    $stmt->execute();
    $datos = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      $objPersona = new Persona();
      $objPersona->nombres = $row->nombres;
      $objPersona->apellidos = $row->apellidos;
      $objPersona->correo = $row->correo;
      $objPersona->telefono = $row->telefono;
      $objPersona->telefono_hab = $row->telefono_hab;
      $objPersona->fecha_nac = $row->fecha_nac;
      $objPersona->cedula = $row->cedula;
      $objPersona->sexo = $row->sexo;
      $objPersona->nacionalidad = $row->nacionalidad;
      $objPersona->calle = $row->calle;
      $objPersona->casa = $row->casa;
      $objPersona->estado = $row->estado;
      $objPersona->parroquia = $row->parroquia;
      $datos[] = $objPersona;
    }
    return $datos;
    $stmt = null;
    $conexion->desconectar();
  }
  public function editar($id)
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();
    $sql = "UPDATE usuario SET nombres = :nombres, apellidos = :apellidos, correo = :correo, telefono = :telefono, telefono_hab = :telefono_hab, fecha_nac = :fecha_nac, lugar_nac = :lugar_nac, cedula = :cedula, cedula_esc = :cedula_esc, sexo = :sexo, nacionalidad = :nacionalidad, edad = :edad, calle = :calle, casa = :casa, parroquia = :parroquia, profesion = :profesion, lugar_trabajo = :lugar_trabajo, rol = :rol WHERE id = $id";
    $stmt = $objconexion->prepare($sql);
    $stmt->bindParam(':nombres', $this->nombres);
    $stmt->bindParam(':apellidos', $this->apellidos);
    $stmt->bindParam(':correo', $this->correo);
    $stmt->bindParam(':telefono', $this->telefono);
    $stmt->bindParam(':telefono_hab', $this->telefono_hab);
    $stmt->bindParam(':fecha_nac', $this->fecha_nac);
    $stmt->bindParam(':lugar_nac', $this->lugar_nac);
    $stmt->bindParam(':cedula', $this->cedula);
    $stmt->bindParam(':sexo', $this->sexo);
    $stmt->bindParam(':nacionalidad', $this->nacionalidad);
    $stmt->bindParam(':calle', $this->calle);
    $stmt->bindParam(':casa', $this->casa);
    $stmt->bindParam(':parroquia', $this->parroquia);
    $stmt->bindParam(':profesion', $this->profesion);
    $stmt->bindParam(':lugar_trabajo', $this->lugar_trabajo);
    $stmt->bindParam(':rol', $this->rol);

    $stmt->execute();
    $stmt = null;
    $conexion->desconectar();
  }
  public function rol()
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();

    $sql = "SELECT nombre FROM roles r INNER JOIN usuario u on r.id = u.rol";
    $stmt = $objconexion->prepare($sql);
    $stmt->execute();
    $listarRol = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      $objRol = new Persona();
      $objRol->rol = $row->nombre;
      $listarRol[] = $objRol;
    }
    return $listarRol;
    $stmt = null;
    $conexion->desconectar();
  }
  public function listarRol()
  {
    $conexion = new Conexion();
    $objconexion = $conexion->conectar();
    $sql = "SELECT id, nombre FROM roles";
    $stmt = $objconexion->prepare($sql);
    $stmt->execute();
    $listarRoles = array();
    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      $objPersona = new Persona();
      $objPersona->id = $row->id;
      $objPersona->nombres = $row->nombre;
      $objPersona->nombres = $row->nombre;
      $listarRoles[] = $objPersona;
    }
    return $listarRoles;
    $stmt = null;
    $conexion->desconectar();
  }

  public function inscribirPrimaria()
  {

    try {
      session_start();
      $conexion = new Conexion();
      $objPersonas = $conexion->conectar();
      $sql =  "INSERT INTO personas(id_rol, nombres, apellidos, correo, telefono, telefono_hab, fecha_nac, lugar_nac,cedula,sexo,nacionalidad,calle,casa,parroquia, estado)
            VALUES (:id_rol, :nombres, :apellidos, :correo, :telefono, :telefono_hab, :fecha_nac, :lugar_nac, :cedula, :sexo, :nacionalidad, :calle, :casa, :parroquia, :estado);";
      $stmt = $objPersonas->prepare($sql);

      $stmt->bindParam(':id_rol', $this->id_rol);
      $stmt->bindParam(':nombres', $this->nombres);
      $stmt->bindParam(':apellidos', $this->apellidos);
      $stmt->bindParam(':correo', $this->correo);
      $stmt->bindParam(':telefono', $this->telefono);
      $stmt->bindParam(':telefono_hab', $this->telefono_hab);
      $stmt->bindParam(':cedula', $this->cedula);
      $stmt->bindParam(':lugar_nac', $this->lugar_nac);
      $stmt->bindParam(':fecha_nac', $this->fecha_nac);
      $stmt->bindParam(':sexo', $this->sexo);
      $stmt->bindParam(':nacionalidad', $this->nacionalidad);
      $stmt->bindParam(':estado', $this->estado);
      $stmt->bindParam(':parroquia', $this->parroquia);
      $stmt->bindParam(':calle', $this->calle);
      $stmt->bindParam(':casa', $this->casa);

      $stmt->execute();
      $ultimaPersona = $objPersonas->lastInsertId();

      $sql2 = "INSERT INTO profesores (id_persona, especialidad) 
            VALUES (:id_persona, :especialidad);";
      $stmt2 = $objPersonas->prepare($sql2);
      $stmt2->bindParam(':id_persona', $ultimaPersona);
      $stmt2->bindParam(':especialidad', $this->especialidad);
      $stmt2->execute();

      $stmt = null;
      $stmt2 = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error en personas crear docente" . $th->getMessage() . $th->getLine();
      $_SESSION['mensaje'] = "Oh no, no se pudo hacer el registro. Comuniquese con el administrador";
      $_SESSION['icono'] = "error";
    }
  }
}
