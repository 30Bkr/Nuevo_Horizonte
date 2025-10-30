<?php
include_once('/xampp/htdocs/final/app/conexion.php');

class Cursos
{
  public $id_años_secciones;
  public $id_grados_secciones;
  public $id_grados;
  public $id_años;
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
      $sqlPrueba = "SELECT * FROM grados_secciones AS gs
              INNER JOIN grados as g ON gs.id_grados = g.id_grados 
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
        $objCursos->id_grados_secciones = $row->id_grados_secciones;
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
      $sql = "SELECT * FROM años_secciones AS ano
              INNER JOIN años as a ON ano.id_años = a.id_años
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
        $objCursos->id_años_secciones = $row->id_años_secciones;
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
      $sql = "SELECT * FROM grados_secciones as ans 
            INNER JOIN grados as a ON ans.id_grados = a.id_grados
            INNER JOIN secciones as s ON ans.id_seccion = s.id_seccion
            where ans.id_grados_secciones = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->execute();
      $listaCursos = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->grado = $row->grado;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_grados_secciones = $row->id_grados_secciones;
        $listaCursos[] = $objCursos;
      }
      return $listaCursos;
      $stmt = null;
      $conexion->desconectar();
    } catch (\Throwable $th) {
      echo "Error al mostrar buscar curso consultaGs" . $th->getMessage() . $th->getLine();
    }
  }

  public function consultarAS($id)
  {
    try {
      $conexion = new Conexion();
      $objCurso = $conexion->conectar();
      $sql = "SELECT * FROM años_secciones as ans 
            INNER JOIN años as a ON ans.id_años = a.id_años
            INNER JOIN secciones as s ON ans.id_seccion = s.id_seccion
            where ans.id_años_secciones = $id";
      $stmt = $objCurso->prepare($sql);
      $stmt->execute();
      $listaCursos = array();
      while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
        $objCursos = new Cursos();
        $objCursos->año = $row->año;
        $objCursos->nom_seccion = $row->nom_seccion;
        $objCursos->capacidad = $row->capacidad;
        $objCursos->turno = $row->turno;
        $objCursos->id_años_secciones = $row->id_años_secciones;
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
      $sql = "UPDATE grados_secciones 
              INNER JOIN grados ON grados_secciones.id_grado = grados.id_grado
              INNER JOIN secciones ON grados_secciones.id_seccion = secciones.id_seccion
              SET secciones.nom_seccion = :nom_seccion, secciones.turno = :turno, secciones.observacion = :observacion, grados.grado = :grado, grados.descripcion = :descripcion, grados_secciones.capacidad = :capacidad
              WHERE grados_secciones.id_grados_secciones = $id";
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
      $sql = "UPDATE años_secciones 
              INNER JOIN años ON años_secciones.id_años = años.id_año
              INNER JOIN secciones ON años_secciones.id_seccion = secciones.id_seccion
              SET secciones.nom_seccion = ?, secciones.turno = ?, secciones.observacion = ?, años.año = ?, años.descripcion = ?, años_secciones.capacidad = ?
              WHERE años_secciones.id_años_secciones = $id";
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
      $sql = "INSERT INTO grados (grado)
              VALUES (:grado)";
      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(':grado', $this->grado);
      $stmt->execute();
      $ultimoGrado = $objConexion->lastInsertId();

      // echo "$ultimoGrado";
      $sql2 = "INSERT INTO secciones (nom_seccion, turno)
              VALUES (:nom_seccion, :turno)";
      $stmt2 = $objConexion->prepare($sql2);
      $stmt2->bindParam(':nom_seccion', $this->nom_seccion);
      $stmt2->bindParam(':turno', $this->turno);
      $stmt2->execute();
      $ultimaSeccion = $objConexion->lastInsertId();
      // echo "$ultimaSeccion";

      $sql3 = "INSERT INTO grados_secciones (id_grados, id_seccion, capacidad)
              VALUES (:id_grados, :id_seccion, :capacidad)";
      $stmt3 = $objConexion->prepare($sql3);
      $stmt3->bindParam(":id_grados", $ultimoGrado);
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
