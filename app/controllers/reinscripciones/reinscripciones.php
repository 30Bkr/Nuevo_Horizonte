<?php
// /final/app/controllers/inscripciones/ReinscripcionManager.php

class ReinscripcionController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  /**
   * Procesa la reinscripción de un estudiante
   */
  public function procesarReinscripcion($datos)
  {
    // Validar datos requeridos
    $this->validarDatosRequeridos($datos);

    // Validar representante y estudiante
    if ($datos['representante_existente'] != '1' || $datos['estudiante_existente'] != '1') {
      throw new Exception("Representante o estudiante no válido");
    }

    // Verificar período
    $periodo = $this->verificarPeriodo($datos['id_periodo']);

    // Verificar si ya está inscrito
    $this->verificarInscripcionExistente($datos['id_estudiante_existente'], $datos['id_periodo']);

    // Obtener nivel_seccion
    $id_nivel_seccion = $this->obtenerNivelSeccion($datos['id_nivel'], $datos['id_seccion']);

    // Realizar la reinscripción
    return $this->registrarReinscripcion(
      $datos['id_estudiante_existente'],
      $datos['id_periodo'],
      $id_nivel_seccion,
      $datos['observaciones'] ?? ''
    );
  }

  /**
   * Valida los datos requeridos
   */
  private function validarDatosRequeridos($datos)
  {
    $campos_requeridos = [
      'representante_existente',
      'id_representante_existente',
      'estudiante_existente',
      'id_estudiante_existente',
      'id_periodo',
      'id_nivel',
      'id_seccion'
    ];

    $campos_faltantes = [];
    foreach ($campos_requeridos as $campo) {
      if (!isset($datos[$campo]) || $datos[$campo] === '') {
        $campos_faltantes[] = $campo;
      }
    }

    if (!empty($campos_faltantes)) {
      throw new Exception('Campos requeridos faltantes: ' . implode(', ', $campos_faltantes));
    }
  }

  /**
   * Verifica que el período exista y esté activo
   */
  private function verificarPeriodo($id_periodo)
  {
    $sql = "SELECT id_periodo, descripcion_periodo, estatus FROM periodos WHERE id_periodo = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_periodo]);
    $periodo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$periodo) {
      throw new Exception("El período seleccionado no existe");
    }

    if ($periodo['estatus'] != 1) {
      throw new Exception("El período seleccionado no está activo: " . $periodo['descripcion_periodo']);
    }

    return $periodo;
  }

  /**
   * Verifica si el estudiante ya está inscrito en el período
   */
  private function verificarInscripcionExistente($id_estudiante, $id_periodo)
  {
    $sql = "
            SELECT i.id_inscripcion, p.descripcion_periodo 
            FROM inscripciones i 
            INNER JOIN periodos p ON i.id_periodo = p.id_periodo 
            WHERE i.id_estudiante = ? AND i.id_periodo = ? AND i.estatus = 1
        ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_estudiante, $id_periodo]);
    $inscripcion_existente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($inscripcion_existente) {
      throw new Exception("El estudiante ya está inscrito en el período: " . $inscripcion_existente['descripcion_periodo']);
    }
  }

  /**
   * Obtiene el ID de nivel_seccion
   */
  private function obtenerNivelSeccion($id_nivel, $id_seccion)
  {
    $sql = "
            SELECT id_nivel_seccion 
            FROM niveles_secciones 
            WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1
        ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_nivel, $id_seccion]);
    $nivel_seccion = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$nivel_seccion) {
      throw new Exception("No se encontró una sección activa para el nivel y sección seleccionados");
    }

    return $nivel_seccion['id_nivel_seccion'];
  }

  /**
   * Registra la reinscripción en la base de datos
   */
  private function registrarReinscripcion($id_estudiante, $id_periodo, $id_nivel_seccion, $observaciones)
  {
    $id_usuario = 1; // Por ahora hardcodeado

    $this->pdo->beginTransaction();

    try {
      // Insertar inscripción
      $sql = "
                INSERT INTO inscripciones (
                    id_estudiante, 
                    id_periodo, 
                    id_nivel_seccion, 
                    id_usuario, 
                    fecha_inscripcion, 
                    observaciones
                ) VALUES (?, ?, ?, ?, CURDATE(), ?)
            ";

      $stmt = $this->pdo->prepare($sql);
      $resultado = $stmt->execute([
        $id_estudiante,
        $id_periodo,
        $id_nivel_seccion,
        $id_usuario,
        $observaciones
      ]);

      if (!$resultado || $stmt->rowCount() === 0) {
        throw new Exception("No se pudo insertar la inscripción en la base de datos");
      }

      $id_inscripcion = $this->pdo->lastInsertId();

      // Obtener información del estudiante
      $estudiante_nombre = $this->obtenerNombreEstudiante($id_estudiante);

      $this->pdo->commit();

      return [
        'id_inscripcion' => $id_inscripcion,
        'estudiante_nombre' => $estudiante_nombre
      ];
    } catch (Exception $e) {
      $this->pdo->rollBack();
      throw new Exception("Error en la transacción: " . $e->getMessage());
    }
  }

  /**
   * Obtiene el nombre del estudiante
   */
  private function obtenerNombreEstudiante($id_estudiante)
  {
    $sql = "
            SELECT p.primer_nombre, p.primer_apellido 
            FROM estudiantes e 
            INNER JOIN personas p ON e.id_persona = p.id_persona 
            WHERE e.id_estudiante = ?
        ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id_estudiante]);
    $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

    return $estudiante ? $estudiante['primer_nombre'] . ' ' . $estudiante['primer_apellido'] : 'N/A';
  }
}
