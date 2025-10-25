<?php
include_once '/xampp/htdocs/final/app/conexion.php';
class Persona
{
  public $id_persona;
  public $id_rol;
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
  public $inhabilitado;
  public $especialidad;
  public $nombre_rol;

  public function mostrar()
  {
    try {
      $conexion = new Conexion();
      $objPersonas = $conexion->conectar();
      $sql = "SELECT * FROM personas WHERE id_rol != 2";
      $stmt = $objPersonas->prepare($sql);
      $stmt->execute();
      $listaPersona = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objPersona = new Persona();
        $objPersona->id_persona = $row->id_persona;
        $objPersona->id_rol = $row->id_rol;
        $objPersona->nombres = $row->nombres;
        $objPersona->apellidos = $row->apellidos;
        $objPersona->cedula = $row->cedula;
        $objPersona->telefono = $row->telefono;
        $objPersona->telefono_hab = $row->telefono_hab;
        $objPersona->correo = $row->correo;
        $objPersona->lugar_nac = $row->lugar_nac;
        $objPersona->sexo = $row->sexo;
        $objPersona->nacionalidad = $row->nacionalidad;
        $objPersona->fecha_nac = $row->fecha_nac;
        $objPersona->estado = $row->estado;
        $objPersona->parroquia = $row->parroquia;
        $objPersona->calle = $row->calle;
        $objPersona->casa = $row->casa;
        $objPersona->inhabilitado = $row->inhabilitado;
        $listaPersona[] = $objPersona;
      }
      return $listaPersona;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error en personas mostrarlas" . $th->getMessage() . $th->getLine();
    }
  }
  public function consultar($id)
  {
    try {
      $conexion = new Conexion();
      $objPersonas = $conexion->conectar();
      $sql = "SELECT * FROM profesores as pro
              INNER JOIN personas as per ON pro.id_persona = per.id_persona
              INNER JOIN roles as rol ON per.id_rol = rol.id_rol 
              WHERE pro.id_persona = $id";
      $stmt = $objPersonas->prepare($sql);
      $stmt->execute();
      $listaPersona = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objPersona = new Persona();
        $objPersona->id_persona = $row->id_persona;
        $objPersona->id_rol = $row->id_rol;
        $objPersona->nombres = $row->nombres;
        $objPersona->apellidos = $row->apellidos;
        $objPersona->cedula = $row->cedula;
        $objPersona->telefono = $row->telefono;
        $objPersona->telefono_hab = $row->telefono_hab;
        $objPersona->correo = $row->correo;
        $objPersona->lugar_nac = $row->lugar_nac;
        $objPersona->sexo = $row->sexo;
        $objPersona->nacionalidad = $row->nacionalidad;
        $objPersona->fecha_nac = $row->fecha_nac;
        $objPersona->estado = $row->estado;
        $objPersona->parroquia = $row->parroquia;
        $objPersona->calle = $row->calle;
        $objPersona->casa = $row->casa;
        $objPersona->especialidad = $row->especialidad;
        $objPersona->nombre_rol = $row->nombre_rol;
        $objPersona->inhabilitado = $row->inhabilitado;
        $listaPersona[] = $objPersona;
      }
      return $listaPersona;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error en personas mostrarlas" . $th->getMessage() . $th->getLine();
    }
  }
  public function actualizar($id)
  {
    try {

      $conexion = new Conexion();
      $actualizar = $conexion->conectar();
      $sql = "UPDATE personas 
            INNER JOIN profesores ON personas.id_persona = profesores.id_persona
            SET personas.id_rol = :id_rol, personas.nombres = :nombres, personas.apellidos = :apellidos,  personas.correo = :correo, personas.telefono = :telefono, personas.telefono_hab = :telefono_hab, personas.fecha_nac = :fecha_nac, personas.lugar_nac = :lugar_nac, personas.cedula = :cedula, personas.sexo = :sexo, personas.nacionalidad = :nacionalidad, personas.calle = :calle, personas.casa = :casa, personas.parroquia = :parroquia, personas.estado = :estado, profesores.especialidad = :especialidad
             WHERE personas.id_persona = $id";
      $stmt = $actualizar->prepare($sql);
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
      $stmt->bindParam(':especialidad', $this->especialidad);
      $stmt->execute();
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error en personas actualizar" . $th->getMessage() . $th->getLine();
    }
  }
  public function crearDocente()
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
