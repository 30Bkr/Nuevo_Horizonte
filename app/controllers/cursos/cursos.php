<?php
include_once('/xampp/htdocs/final/app/conexion.php');

class Cursos
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

  public function mostrarGrados()
  {
    try {
      // session_start();
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      // $sql = "SELECT * FROM grado_seccion";
      $sqlPrueba = "SELECT * FROM grado_seccion AS gs
              INNER JOIN grados as g ON gs.id_grado = g.id_grado 
              INNER JOIN secciones as s ON gs.id_seccion = s.id_seccion";
      $stmt = $objConexion->prepare($sqlPrueba);
      $stmt->execute();
      $listaRoles = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->grado = $row->grado;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_grado_seccion = $row->id_grado_seccion;
        $listaRoles[] = $objCursos;
      }
      return $listaRoles;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      $_SESSION['mensaje'] = "Oh no, no se pudo mostrar las tablas del curso. Comuniquese con el administrador";
      $_SESSION['icono'] = "error";
      echo "Error al mostrar grados" . $th->getMessage();
    }
  }
  public function mostrarAños()
  {
    try {
      // session_start();
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "SELECT * FROM año_seccion AS ano
              INNER JOIN años as a ON ano.id_año = a.id_año
              INNER JOIN secciones as s ON ano.id_seccion = s.id_seccion";
      $stmt = $objConexion->prepare($sql);
      $stmt->execute();
      $listaRoles = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->año = $row->año;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_año_seccion = $row->id_año_seccion;
        $listaRoles[] = $objCursos;
      }
      return $listaRoles;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      $_SESSION['mensaje'] = "Oh no, no se pudo registrar el curso. Comuniquese con el administrador";
      $_SESSION['icono'] = "error";
      echo "Error al mostrar años" . $th->getMessage();
    }
  }
  public function consultarGS($id)
  {
    try {
      $conexion = new Conexion();
      $objCurso = $conexion->conectar();
      $sql = "SELECT * FROM grado_seccion as ans 
            INNER JOIN grados as a ON ans.id_grado = a.id_grado
            INNER JOIN secciones as s ON ans.id_seccion = s.id_seccion
            where ans.id_grado_seccion = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->execute();
      $listaCursos = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->grado = $row->grado;
        $objCursos->descripcion = $row->descripcion;
        $objCursos->observacion = $row->observacion;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_grado_seccion = $row->id_grado_seccion;
        $listaCursos[] = $objCursos;
      }
      return $listaCursos;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al mostrar buscar curso" . $th->getMessage() . $th->getLine();
    }
  }

  public function consultarAS($id)
  {
    try {
      $conexion = new Conexion();
      $objCurso = $conexion->conectar();
      $sql = "SELECT * FROM año_seccion as ans 
            INNER JOIN años as a ON ans.id_año = a.id_año
            INNER JOIN secciones as s ON ans.id_seccion = s.id_seccion
            where ans.id_año_seccion = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->execute();
      $listaCursos = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->año = $row->año;
        $objCursos->descripcion = $row->descripcion;
        $objCursos->observacion = $row->observacion;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_año_seccion = $row->id_año_seccion;
        $listaCursos[] = $objCursos;
      }
      return $listaCursos;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al mostrar buscar curso" . $th->getMessage() . $th->getLine();
    }
  }

  public function actualizarGS($id)
  {
    try {
      $conexion = new Conexion();
      $objCurso = $conexion->conectar();
      $sql = "UPDATE grado_seccion 
              INNER JOIN grados ON grado_seccion.id_grado = grados.id_grado
              INNER JOIN secciones ON grado_seccion.id_seccion = secciones.id_seccion
              SET secciones.nom_seccion = :nom_seccion, secciones.turno = :turno, secciones.observacion = :observacion, grados.grado = :grado, grados.descripcion = :descripcion, grado_seccion.capacidad = :capacidad
              WHERE grado_seccion.id_grado_seccion = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->bindParam(':nom_seccion', $this->nom_seccion);
      $stmt->bindParam(':turno', $this->turno);
      $stmt->bindParam(':observacion', $this->observacion);
      $stmt->bindParam(':grado', $this->grado);
      $stmt->bindParam(':descripcion', $this->descripcion);
      $stmt->bindParam(':capacidad', $this->capacidad);
      $stmt->execute();
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al actualizar gs curso" . $th->getMessage() . $th->getLine();
    }
  }
  public function actualizarAS($id)
  {
    try {
      $conexion = new Conexion();
      $objCurso = $conexion->conectar();
      $sql = "UPDATE año_seccion 
              INNER JOIN años ON año_seccion.id_año = años.id_año
              INNER JOIN secciones ON año_seccion.id_seccion = secciones.id_seccion
              SET secciones.nom_seccion = ?, secciones.turno = ?, secciones.observacion = ?, años.año = ?, años.descripcion = ?, año_seccion.capacidad = ?
              WHERE año_seccion.id_año_seccion = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->bindParam(':nom_seccion', $this->nom_seccion);
      $stmt->bindParam(':turno', $this->turno);
      $stmt->bindParam(':observacion', $this->observacion);
      $stmt->bindParam(':año', $this->año);
      $stmt->bindParam(':descripcion', $this->descripcion);
      $stmt->bindParam(':capacidad', $this->capacidad);
      $stmt->execute([$this->nom_seccion, $this->turno, $this->observacion, $this->año, $this->descripcion, $this->capacidad]);
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al actualizar as curso" . $th->getMessage() . $th->getLine();
    }
  }
  public function crearGrado()
  {
    try {
      session_start();
      $conexion = new Conexion();
      $objConexion = $conexion->conectar();
      $sql = "INSERT INTO grados (grado, descripcion)
              VALUES (:grado, :descripcion)";
      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(':grado', $this->grado);
      $stmt->bindParam(':descripcion', $this->descripcion);
      $stmt->execute();
      $ultimoGrado = $objConexion->lastInsertId();

      // echo "$ultimoGrado";
      $sql2 = "INSERT INTO secciones (nom_seccion, turno, observacion)
              VALUES (:nom_seccion, :turno, :observacion)";
      $stmt2 = $objConexion->prepare($sql2);
      $stmt2->bindParam(':nom_seccion', $this->nom_seccion);
      $stmt2->bindParam(':turno', $this->turno);
      $stmt2->bindParam(':observacion', $this->observacion);
      $stmt2->execute();
      $ultimaSeccion = $objConexion->lastInsertId();
      // echo "$ultimaSeccion";

      $sql3 = "INSERT INTO grado_seccion (id_grado, id_seccion, capacidad)
              VALUES (:id_grado, :id_seccion, :capacidad)";
      $stmt3 = $objConexion->prepare($sql3);
      $stmt3->bindParam(":id_grado", $ultimoGrado);
      $stmt3->bindParam(":id_seccion", $ultimaSeccion);
      $stmt3->bindParam(":capacidad", $this->capacidad);
      $stmt3->execute();

      $stmt = null;
      $stmt2 = null;
      $stmt3 = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      //throw $th;
    }
  }
}
