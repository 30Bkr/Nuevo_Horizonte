<?php
class InscripcionController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }
  /**
   * Obtiene períodos académicos que están actualmente activos (fecha actual entre fecha_ini y fecha_fin)
   */
  public function obtenerPeriodosVigentes()
  {
    try {
      $fecha_actual = date('Y-m-d');

      $sql = "
            SELECT 
                id_periodo,
                descripcion_periodo,
                fecha_ini,
                fecha_fin,
                estatus
            FROM periodos 
            WHERE estatus = 1
            AND '2025-12-07' BETWEEN fecha_ini AND fecha_fin
            ORDER BY fecha_ini DESC
        ";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$fecha_actual]);
      $periodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return [
        'success' => true,
        'periodos' => $periodos,
        'fecha_actual' => $fecha_actual
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerPeriodosVigentes: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al obtener períodos vigentes',
        'periodos' => []
      ];
    }
  }
  //crear inscripcion nueva
  // En InscripcionController
  public function crearInscripcionConNivelSeccion($id_estudiante, $id_periodo, $id_nivel, $id_seccion, $id_usuario, $observaciones = '')
  {
    try {
      // Primero obtener el id_nivel_seccion
      $sql_nivel_seccion = "SELECT id_nivel_seccion FROM niveles_secciones 
                             WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql_nivel_seccion);
      $stmt->execute([$id_nivel, $id_seccion]);
      $nivel_seccion = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$nivel_seccion) {
        throw new Exception('No se encontró la combinación nivel-sección');
      }

      $id_nivel_seccion = $nivel_seccion['id_nivel_seccion'];

      // Crear la inscripción
      $sql = "INSERT INTO inscripciones 
                (id_estudiante, id_periodo, id_nivel_seccion, id_usuario, fecha_inscripcion, observaciones) 
                VALUES (?, ?, ?, ?, ?, ?)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        $id_estudiante,
        $id_periodo,
        $id_nivel_seccion,
        $id_usuario,
        date('Y-m-d'),
        $observaciones
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error en crearInscripcionConNivelSeccion: " . $e->getMessage());
      throw new Exception('Error al crear la inscripción: ' . $e->getMessage());
    }
  }

  // Crear nueva inscripción
  public function crearInscripcion($datos)
  {
    try {
      // Primero necesitamos obtener el id_nivel_seccion basado en id_nivel e id_seccion
      $sqlNivelSeccion = "SELECT id_nivel_seccion FROM niveles_secciones 
                               WHERE id_nivel = :id_nivel AND id_seccion = :id_seccion 
                               AND estatus = 1 LIMIT 1";
      $stmt = $this->pdo->prepare($sqlNivelSeccion);
      $stmt->execute([
        ':id_nivel' => $datos['id_nivel'],
        ':id_seccion' => $datos['id_seccion']
      ]);

      $nivelSeccion = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$nivelSeccion) {
        throw new Exception("No se encontró la combinación de nivel y sección especificada");
      }

      $id_nivel_seccion = $nivelSeccion['id_nivel_seccion'];

      // Crear la inscripción
      $sql = "INSERT INTO inscripciones (
                id_estudiante, id_periodo, id_nivel_seccion, id_usuario, 
                fecha_inscripcion, observaciones
            ) VALUES (
                :id_estudiante, :id_periodo, :id_nivel_seccion, :id_usuario,
                :fecha_inscripcion, :observaciones
            )";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_estudiante' => $datos['id_estudiante'],
        ':id_periodo' => $datos['id_periodo'],
        ':id_nivel_seccion' => $id_nivel_seccion,
        ':id_usuario' => $datos['id_usuario'],
        ':fecha_inscripcion' => $datos['fecha_inscripcion'],
        ':observaciones' => $datos['observaciones']
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear inscripción: " . $e->getMessage());
    }
  }

  // Verificar si estudiante ya está inscrito en el período
  public function estudianteInscrito($id_estudiante, $id_periodo)
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM inscripciones 
                    WHERE id_estudiante = :id_estudiante 
                    AND id_periodo = :id_periodo 
                    AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_estudiante' => $id_estudiante,
        ':id_periodo' => $id_periodo
      ]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar inscripción: " . $e->getMessage());
    }
  }
  public function obtenerPeriodosActivos()
  {
    try {
      $sql = "SELECT id_periodo, descripcion_periodo, fecha_ini, fecha_fin, estatus 
                    FROM periodos 
                    WHERE estatus = 1 
                    ORDER BY fecha_ini DESC";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error al obtener períodos: " . $e->getMessage());
      return [];
    }
  }
}
