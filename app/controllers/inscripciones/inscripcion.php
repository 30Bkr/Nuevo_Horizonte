<?php

class Inscripcion
{
  //datos del estudiante
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
  public $lugar_trabajo;
  public $id_rol;

  public $alergias;
  public $condiciones;

  //datos del representante.
  public $rol2;
  public $nombres2;
  public $apellidos2;
  public $cedula2;
  public $telefono2;
  public $telefono_hab2;
  public $correo2;
  public $lugar_nac2;
  public $sexo2;
  public $nacionalidad2;
  public $fecha_nac2;
  public $estado2;
  public $parroquia2;
  public $calle2;
  public $casa2;
  public $lugar_trabajo2;
  public $id_rol2;



  public $especialidad;
  public $ocupacion;




  //datos de la seccion

  //Datos para la inscripcion 

  public $id_estudiante_representante;
  public $id_grado_seccion;
  public $id_periodo;



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
