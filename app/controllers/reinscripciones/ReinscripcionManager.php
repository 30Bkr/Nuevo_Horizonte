<?php
// /final/app/controllers/inscripciones/ReinscripcionManager.php

class ReinscripcionManager
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

    // Iniciar transacción
    $this->pdo->beginTransaction();

    try {
      // 1. Actualizar datos del representante
      $this->actualizarDatosRepresentante($datos);

      // 2. Actualizar datos del estudiante
      $this->actualizarDatosEstudiante($datos);

      // 3. Actualizar dirección del representante
      if (isset($datos['id_direccion_repre'])) {
        $this->actualizarDireccionRepresentante($datos);
      }

      // 4. Manejar dirección del estudiante según si vive con el representante
      $this->manejarDireccionEstudiante($datos);

      // 5. Actualizar patologías y discapacidades del estudiante
      $this->actualizarDatosSaludEstudiante($datos);

      // 6. Realizar la reinscripción
      $resultado_inscripcion = $this->registrarReinscripcion(
        $datos['id_estudiante_existente'],
        $datos['id_periodo'],
        $id_nivel_seccion,
        $datos['observaciones'] ?? ''
      );

      $this->pdo->commit();

      return $resultado_inscripcion;
    } catch (Exception $e) {
      $this->pdo->rollBack();
      throw new Exception("Error en la transacción: " . $e->getMessage());
    }
  }

  /**
   * Maneja la dirección del estudiante según si vive con el representante
   */
  private function manejarDireccionEstudiante($datos)
  {
    $juntos = $datos['juntos'] ?? 'si';
    $id_estudiante = $datos['id_estudiante_existente'];

    if ($juntos === 'si') {
      // Si vive con el representante, usar la misma dirección
      $this->usarDireccionRepresentante($id_estudiante, $datos['id_direccion_repre']);
    } else {
      // Si NO vive con el representante, actualizar su dirección independiente
      $this->actualizarDireccionEstudianteIndependiente($datos);
    }
  }

  /**
   * Asigna la dirección del representante al estudiante
   */
  private function usarDireccionRepresentante($id_estudiante, $id_direccion_representante)
  {
    // Obtener id_persona del estudiante
    $sql_persona = "SELECT id_persona FROM estudiantes WHERE id_estudiante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$id_estudiante]);
    $estudiante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
      throw new Exception("No se encontró el estudiante");
    }

    // Actualizar la persona del estudiante para que use la dirección del representante
    $sql_update_persona = "
            UPDATE personas 
            SET id_direccion = ?, actualizacion = NOW()
            WHERE id_persona = ?
        ";

    $stmt_persona = $this->pdo->prepare($sql_update_persona);
    $stmt_persona->execute([
      $id_direccion_representante,
      $estudiante['id_persona']
    ]);
  }

  /**
   * Actualiza la dirección independiente del estudiante
   */
  private function actualizarDireccionEstudianteIndependiente($datos)
  {
    // Verificar si tenemos todos los datos necesarios
    if (empty($datos['id_direccion_est']) || empty($datos['parroquia_e'])) {
      throw new Exception("Faltan datos de dirección del estudiante");
    }

    $sql_update_direccion = "
            UPDATE direcciones SET
                id_parroquia = ?,
                direccion = ?,
                calle = ?,
                casa = ?,
                actualizacion = NOW()
            WHERE id_direccion = ?
        ";

    $stmt_direccion = $this->pdo->prepare($sql_update_direccion);
    $stmt_direccion->execute([
      $datos['parroquia_e'] ?? null,
      $datos['direccion_e'] ?? '',
      $datos['calle_e'] ?? '',
      $datos['casa_e'] ?? '',
      $datos['id_direccion_est']
    ]);
  }

  // ... (los demás métodos se mantienen igual hasta actualizarDatosSaludEstudiante) ...

  /**
   * Actualiza las patologías y discapacidades del estudiante
   */
  private function actualizarDatosSaludEstudiante($datos)
  {
    $id_estudiante = $datos['id_estudiante_existente'];

    // 1. Eliminar patologías existentes
    $sql_delete_patologias = "DELETE FROM estudiantes_patologias WHERE id_estudiante = ?";
    $stmt_delete_pat = $this->pdo->prepare($sql_delete_patologias);
    $stmt_delete_pat->execute([$id_estudiante]);

    // 2. Insertar nuevas patologías
    if (isset($datos['patologias']) && is_array($datos['patologias'])) {
      foreach ($datos['patologias'] as $id_patologia) {
        if (!empty($id_patologia) && $id_patologia != '0') {
          $sql_insert_patologia = "
                        INSERT INTO estudiantes_patologias (id_estudiante, id_patologia) 
                        VALUES (?, ?)
                    ";
          $stmt_pat = $this->pdo->prepare($sql_insert_patologia);
          $stmt_pat->execute([$id_estudiante, $id_patologia]);
        }
      }
    }

    // 3. Eliminar discapacidades existentes
    $sql_delete_discapacidades = "DELETE FROM estudiantes_discapacidades WHERE id_estudiante = ?";
    $stmt_delete_disc = $this->pdo->prepare($sql_delete_discapacidades);
    $stmt_delete_disc->execute([$id_estudiante]);

    // 4. Insertar nuevas discapacidades
    if (isset($datos['discapacidades']) && is_array($datos['discapacidades'])) {
      foreach ($datos['discapacidades'] as $id_discapacidad) {
        if (!empty($id_discapacidad) && $id_discapacidad != '0') {
          $sql_insert_discapacidad = "
                        INSERT INTO estudiantes_discapacidades (id_estudiante, id_discapacidad) 
                        VALUES (?, ?)
                    ";
          $stmt_disc = $this->pdo->prepare($sql_insert_discapacidad);
          $stmt_disc->execute([$id_estudiante, $id_discapacidad]);
        }
      }
    }
  }

  /**
   * Registra la reinscripción en la base de datos
   */
  private function registrarReinscripcion($id_estudiante, $id_periodo, $id_nivel_seccion, $observaciones)
  {
    $id_usuario = 1; // Por ahora hardcodeado

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

    return [
      'id_inscripcion' => $id_inscripcion,
      'estudiante_nombre' => $estudiante_nombre
    ];
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

  // ... (los métodos de validación se mantienen igual) ...

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
      'id_seccion',
      'juntos' // Ahora es requerido
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

    // Validación adicional si NO viven juntos
    if ($datos['juntos'] === 'no') {
      $campos_direccion_estudiante = [
        'id_direccion_est',
        'parroquia_e',
        'direccion_e'
      ];

      $campos_faltantes_direccion = [];
      foreach ($campos_direccion_estudiante as $campo) {
        if (!isset($datos[$campo]) || $datos[$campo] === '') {
          $campos_faltantes_direccion[] = $campo;
        }
      }

      if (!empty($campos_faltantes_direccion)) {
        throw new Exception('Cuando el estudiante no vive con el representante, se requieren: ' . implode(', ', $campos_faltantes_direccion));
      }
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
   * Actualiza los datos del representante
   */
  private function actualizarDatosRepresentante($datos)
  {
    // Obtener id_persona del representante
    $sql_persona = "SELECT id_persona FROM representantes WHERE id_representante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$datos['id_representante_existente']]);
    $representante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if (!$representante) {
      throw new Exception("No se encontró el representante");
    }

    $id_persona_representante = $representante['id_persona'];

    // Actualizar datos de la persona (representante)
    $sql_update_persona = "
            UPDATE personas SET
                primer_nombre = ?,
                segundo_nombre = ?,
                primer_apellido = ?,
                segundo_apellido = ?,
                correo = ?,
                telefono = ?,
                telefono_hab = ?,
                fecha_nac = ?,
                lugar_nac = ?,
                sexo = ?,
                nacionalidad = ?,
                actualizacion = NOW()
            WHERE id_persona = ?
        ";

    $stmt_persona = $this->pdo->prepare($sql_update_persona);
    $stmt_persona->execute([
      $datos['primer_nombre_r'] ?? '',
      $datos['segundo_nombre_r'] ?? '',
      $datos['primer_apellido_r'] ?? '',
      $datos['segundo_apellido_r'] ?? '',
      $datos['correo_r'] ?? '',
      $datos['telefono_r'] ?? '',
      $datos['telefono_hab_r'] ?? '',
      $datos['fecha_nac_r'] ?? '',
      $datos['lugar_nac_r'] ?? '',
      $datos['sexo_r'] ?? '',
      $datos['nacionalidad_r'] ?? '',
      $id_persona_representante
    ]);

    // Actualizar datos del representante (ocupación, profesión, etc.)
    $sql_update_representante = "
            UPDATE representantes SET
                ocupacion = ?,
                lugar_trabajo = ?,
                id_profesion = ?,
                actualizacion = NOW()
            WHERE id_representante = ?
        ";

    $stmt_representante = $this->pdo->prepare($sql_update_representante);
    $stmt_representante->execute([
      $datos['ocupacion_r'] ?? '',
      $datos['lugar_trabajo_r'] ?? '',
      $datos['profesion_r'] ?? '',
      $datos['id_representante_existente']
    ]);
  }

  /**
   * Actualiza los datos del estudiante
   */
  private function actualizarDatosEstudiante($datos)
  {
    // Obtener id_persona del estudiante
    $sql_persona = "SELECT id_persona FROM estudiantes WHERE id_estudiante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$datos['id_estudiante_existente']]);
    $estudiante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
      throw new Exception("No se encontró el estudiante");
    }

    $id_persona_estudiante = $estudiante['id_persona'];

    // Actualizar datos de la persona (estudiante)
    $sql_update_persona = "
            UPDATE personas SET
                primer_nombre = ?,
                segundo_nombre = ?,
                primer_apellido = ?,
                segundo_apellido = ?,
                cedula = ?,
                correo = ?,
                telefono = ?,
                fecha_nac = ?,
                lugar_nac = ?,
                sexo = ?,
                nacionalidad = ?,
                actualizacion = NOW()
            WHERE id_persona = ?
        ";

    $stmt_persona = $this->pdo->prepare($sql_update_persona);
    $stmt_persona->execute([
      $datos['primer_nombre_e'] ?? '',
      $datos['segundo_nombre_e'] ?? '',
      $datos['primer_apellido_e'] ?? '',
      $datos['segundo_apellido_e'] ?? '',
      $datos['cedula_e'] ?? '',
      $datos['correo_e'] ?? '',
      $datos['telefono_e'] ?? '',
      $datos['fecha_nac_e'] ?? '',
      $datos['lugar_nac_e'] ?? '',
      $datos['sexo_e'] ?? '',
      $datos['nacionalidad_e'] ?? '',
      $id_persona_estudiante
    ]);
  }

  /**
   * Actualiza la dirección del representante
   */
  private function actualizarDireccionRepresentante($datos)
  {
    $sql_update_direccion = "
            UPDATE direcciones SET
                id_parroquia = ?,
                direccion = ?,
                calle = ?,
                casa = ?,
                actualizacion = NOW()
            WHERE id_direccion = ?
        ";

    $stmt_direccion = $this->pdo->prepare($sql_update_direccion);
    $stmt_direccion->execute([
      $datos['parroquia_r'] ?? null,
      $datos['direccion_r'] ?? '',
      $datos['calle_r'] ?? '',
      $datos['casa_r'] ?? '',
      $datos['id_direccion_repre']
    ]);
  }
}
