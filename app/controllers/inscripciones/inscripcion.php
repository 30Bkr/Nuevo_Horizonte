<?php

include_once('/xampp/htdocs/final/app/conexion.php');


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



  public $ocupacion;

  public $relacion;


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
      $alumno = $objPersonas->lastInsertId();

      $sql2 = "INSERT INTO estudiantes (id_persona,alergias,condiciones)
                VALUES (:id_persona,:alergias,:condiciones);";
      $stmt2 = $objPersonas->prepare($sql2);
      $stmt2->bindParam(':id_persona', $alumno);
      $stmt2->bindParam(':alergias', $this->alergias);
      $stmt2->bindParam(':condiciones', $this->condiciones);
      $stmt2->execute();

      $ultimoAlumno = $objPersonas->lastInsertId();

      $sql3 =  "INSERT INTO personas(id_rol, nombres, apellidos, correo, telefono, telefono_hab, fecha_nac, lugar_nac,cedula,sexo,nacionalidad,calle,casa,parroquia, estado)
            VALUES (:id_rol, :nombres, :apellidos, :correo, :telefono, :telefono_hab, :fecha_nac, :lugar_nac, :cedula, :sexo, :nacionalidad, :calle, :casa, :parroquia, :estado);";
      $stmt3 = $objPersonas->prepare($sql3);

      $stmt3->bindParam(':id_rol', $this->id_rol2);
      $stmt3->bindParam(':nombres', $this->nombres2);
      $stmt3->bindParam(':apellidos', $this->apellidos2);
      $stmt3->bindParam(':correo', $this->correo2);
      $stmt3->bindParam(':telefono', $this->telefono2);
      $stmt3->bindParam(':telefono_hab', $this->telefono_hab2);
      $stmt3->bindParam(':cedula', $this->cedula2);
      $stmt3->bindParam(':lugar_nac', $this->lugar_nac2);
      $stmt3->bindParam(':fecha_nac', $this->fecha_nac2);
      $stmt3->bindParam(':sexo', $this->sexo2);
      $stmt3->bindParam(':nacionalidad', $this->nacionalidad2);
      $stmt3->bindParam(':estado', $this->estado2);
      $stmt3->bindParam(':parroquia', $this->parroquia2);
      $stmt3->bindParam(':calle', $this->calle2);
      $stmt3->bindParam(':casa', $this->casa2);

      $stmt3->execute();
      $representante = $objPersonas->lastInsertId();

      $sql4 = "INSERT INTO representantes (id_persona,ocupacion,lugar_trabajo)
                VALUES (:id_persona,:ocupacion,:lugar_trabajo);";
      $stmt4 = $objPersonas->prepare($sql4);
      $stmt4->bindParam(':id_persona', $representante);
      $stmt4->bindParam(':ocupacion', $this->ocupacion);
      $stmt4->bindParam(':lugar_trabajo', $this->lugar_trabajo);
      $stmt4->execute();

      $ultimoRepresentante = $objPersonas->lastInsertId();

      echo "$ultimoRepresentante";
      echo "$ultimoAlumno";

      $sql5 = "INSERT INTO estudiante_representante (id_estudiante, id_representante, relacion)
                VALUES (:id_estudiante, :id_representante,:relacion);";
      $stmt5 = $objPersonas->prepare($sql5);
      $stmt5->bindParam(':id_estudiante', $ultimoAlumno);
      $stmt5->bindParam(':id_representante', $ultimoRepresentante);
      $stmt5->bindParam(':relacion', $this->relacion);
      $stmt5->execute();

      $id_estudiante_repre = $objPersonas->lastInsertId();

      $sql6 = "INSERT INTO inscripcion_inicial (id_estudiante_representante, id_grado_seccion, id_periodo)
                VALUES (:id_estudiante_representante, :id_grado_seccion, :id_periodo)";
      $ref  = 1;
      $stmt6 = $objPersonas->prepare($sql6);
      $stmt6->bindParam(':id_estudiante_representante', $id_estudiante_repre);
      $stmt6->bindParam(':id_grado_seccion', $this->id_grado_seccion);
      $stmt6->bindParam(':id_periodo', $ref);
      $stmt6->execute();
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
