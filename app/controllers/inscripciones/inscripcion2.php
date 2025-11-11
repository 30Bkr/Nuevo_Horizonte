<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class InscripcionController
{
  private $conn;

  public function __construct()
  {
    $conexion = new Conexion();
    $this->conn = $conexion->conectar();
  }

  /**
   * Validar si un representante existe por cédula
   */
  // public function validarRepresentante($cedula)
  // {
  //   try {
  //     $query = "SELECT p.id_persona, p.primer_nombre, p.segundo_nombre, 
  //                            p.primer_apellido, p.segundo_apellido, r.id_representante
  //                     FROM personas p 
  //                     LEFT JOIN representantes r ON p.id_persona = r.id_persona
  //                     WHERE p.cedula = :cedula AND p.estatus = 1";

  //     $stmt = $this->conn->prepare($query);
  //     $stmt->bindParam(':cedula', $cedula);
  //     $stmt->execute();

  //     if ($stmt->rowCount() > 0) {
  //       $persona = $stmt->fetch(PDO::FETCH_ASSOC);
  //       $nombre_completo = $this->formatearNombreCompleto($persona);

  //       return [
  //         'existe' => true,
  //         'id_persona' => $persona['id_persona'],
  //         'id_representante' => $persona['id_representante'],
  //         'nombre_completo' => $nombre_completo,
  //         'cedula' => $cedula
  //       ];
  //     }
  //     return ['existe' => false];
  //   } catch (PDOException $e) {
  //     error_log("Error validando representante: " . $e->getMessage());
  //     return ['existe' => false, 'error' => $e->getMessage()];
  //   }
  // }
  /**
   * Validar si un representante existe por cédula - VERSIÓN MEJORADA
   */
  public function validarRepresentante($cedula)
  {
    try {
      $query = "SELECT 
                    p.id_persona, 
                    p.primer_nombre, 
                    p.segundo_nombre, 
                    p.primer_apellido, 
                    p.segundo_apellido, 
                    p.cedula,
                    p.telefono,
                    p.telefono_hab,
                    p.correo,
                    p.lugar_nac,
                    p.fecha_nac,
                    p.sexo,
                    p.nacionalidad,
                    r.id_representante,
                    r.profesion,
                    r.ocupacion,
                    r.lugar_trabajo,
                    d.id_direccion,
                    d.direccion,
                    d.calle,
                    d.casa,
                    d.id_parroquia,
                    par.nom_parroquia,
                    mun.id_municipio,
                    mun.nom_municipio,
                    est.id_estado,
                    est.nom_estado
                  FROM personas p 
                  LEFT JOIN representantes r ON p.id_persona = r.id_persona
                  LEFT JOIN direcciones d ON p.id_direccion = d.id_direccion
                  LEFT JOIN parroquias par ON d.id_parroquia = par.id_parroquia
                  LEFT JOIN municipios mun ON par.id_municipio = mun.id_municipio
                  LEFT JOIN estados est ON mun.id_estado = est.id_estado
                  WHERE p.cedula = :cedula AND p.estatus = 1";

      $stmt = $this->conn->prepare($query);
      $stmt->bindParam(':cedula', $cedula);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $persona = $stmt->fetch(PDO::FETCH_ASSOC);
        $nombre_completo = $this->formatearNombreCompleto($persona);

        return [
          'existe' => true,
          'id_persona' => $persona['id_persona'],
          'id_representante' => $persona['id_representante'],
          'nombre_completo' => $nombre_completo,
          'cedula' => $cedula,
          'datos_completos' => $persona // Enviamos todos los datos
        ];
      }

      return ['existe' => false];
    } catch (PDOException $e) {
      error_log("Error validando representante: " . $e->getMessage());
      return ['existe' => false, 'error' => $e->getMessage()];
    }
  }
  /**
   * Procesar inscripción completa
   */
  public function procesarInscripcion($datos)
  {
    try {
      $this->conn->beginTransaction();

      // 1. Registrar o obtener representante
      $id_representante = $this->registrarRepresentante($datos['representante']);

      // 2. Registrar estudiantes
      $estudiantes_registrados = [];
      foreach ($datos['estudiantes'] as $estudiante) {
        $id_estudiante = $this->registrarEstudiante($estudiante, $id_representante);
        $estudiantes_registrados[] = $id_estudiante;

        // 3. Relacionar estudiante con representante
        $this->relacionarEstudianteRepresentante($id_estudiante, $id_representante, $datos['parentesco']);

        // 4. Registrar inscripción
        $this->registrarInscripcion($id_estudiante, $datos['inscripcion'], $estudiante);
      }

      $this->conn->commit();

      return [
        'success' => true,
        'message' => 'Inscripción completada exitosamente',
        'id_representante' => $id_representante,
        'estudiantes_registrados' => $estudiantes_registrados
      ];
    } catch (Exception $e) {
      $this->conn->rollBack();
      error_log("Error en procesarInscripcion: " . $e->getMessage());
      return [
        'success' => false,
        'error' => 'Error al procesar la inscripción: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Registrar o obtener representante
   */
  private function registrarRepresentante($datosRepresentante)
  {
    // Verificar si el representante ya existe
    if (!empty($datosRepresentante['id_representante'])) {
      return $datosRepresentante['id_representante'];
    }

    // Registrar nueva dirección
    $id_direccion = $this->registrarDireccion($datosRepresentante['direccion']);

    // Registrar nueva persona
    $id_persona = $this->registrarPersona($datosRepresentante, $id_direccion);

    // Registrar representante
    return $this->registrarDatosRepresentante($id_persona, $datosRepresentante);
  }

  /**
   * Registrar dirección
   */
  private function registrarDireccion($datosDireccion)
  {
    $query = "INSERT INTO direcciones (id_parroquia, direccion, calle, casa, actualizacion, estatus) 
                  VALUES (:id_parroquia, :direccion, :calle, :casa, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':id_parroquia' => $datosDireccion['id_parroquia'],
      ':direccion' => $datosDireccion['direccion'],
      ':calle' => $datosDireccion['calle'],
      ':casa' => $datosDireccion['casa']
    ]);

    return $this->conn->lastInsertId();
  }

  /**
   * Registrar persona
   */
  private function registrarPersona($datosPersona, $id_direccion)
  {
    $query = "INSERT INTO personas (id_direccion, primer_nombre, segundo_nombre, 
                                       primer_apellido, segundo_apellido, cedula, telefono, 
                                       telefono_hab, correo, lugar_nac, fecha_nac, sexo, 
                                       nacionalidad, actualizacion, estatus) 
                  VALUES (:id_direccion, :primer_nombre, :segundo_nombre, :primer_apellido, 
                         :segundo_apellido, :cedula, :telefono, :telefono_hab, :correo, 
                         :lugar_nac, :fecha_nac, :sexo, :nacionalidad, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':id_direccion' => $id_direccion,
      ':primer_nombre' => $datosPersona['primer_nombre'],
      ':segundo_nombre' => $datosPersona['segundo_nombre'] ?? null,
      ':primer_apellido' => $datosPersona['primer_apellido'],
      ':segundo_apellido' => $datosPersona['segundo_apellido'] ?? null,
      ':cedula' => $datosPersona['cedula'],
      ':telefono' => $datosPersona['telefono'],
      ':telefono_hab' => $datosPersona['telefono_hab'],
      ':correo' => $datosPersona['correo'],
      ':lugar_nac' => $datosPersona['lugar_nac'],
      ':fecha_nac' => $datosPersona['fecha_nac'],
      ':sexo' => $datosPersona['sexo'],
      ':nacionalidad' => $datosPersona['nacionalidad']
    ]);

    return $this->conn->lastInsertId();
  }

  /**
   * Registrar datos específicos del representante
   */
  private function registrarDatosRepresentante($id_persona, $datosRepresentante)
  {
    $query = "INSERT INTO representantes (id_persona, profesion, ocupacion, lugar_trabajo, actualizacion, estatus) 
                  VALUES (:id_persona, :profesion, :ocupacion, :lugar_trabajo, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':id_persona' => $id_persona,
      ':profesion' => $datosRepresentante['profesion'],
      ':ocupacion' => $datosRepresentante['ocupacion'],
      ':lugar_trabajo' => $datosRepresentante['lugar_trabajo']
    ]);

    return $this->conn->lastInsertId();
  }

  /**
   * Registrar estudiante
   */
  private function registrarEstudiante($datosEstudiante, $id_representante)
  {
    // Registrar dirección del estudiante (usar misma del representante por ahora)
    $id_direccion = $this->obtenerDireccionRepresentante($id_representante);

    // Registrar persona del estudiante
    $id_persona = $this->registrarPersona($datosEstudiante, $id_direccion);

    // Registrar estudiante
    $query = "INSERT INTO estudiantes (id_persona, actualizacion, estatus) 
                  VALUES (:id_persona, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([':id_persona' => $id_persona]);

    $id_estudiante = $this->conn->lastInsertId();

    // Registrar patologías si existen
    if (!empty($datosEstudiante['patologias'])) {
      $this->registrarPatologias($id_estudiante, $datosEstudiante['patologias']);
    }

    return $id_estudiante;
  }

  /**
   * Obtener dirección del representante
   */
  private function obtenerDireccionRepresentante($id_representante)
  {
    $query = "SELECT p.id_direccion 
                  FROM representantes r 
                  JOIN personas p ON r.id_persona = p.id_persona 
                  WHERE r.id_representante = :id_representante";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([':id_representante' => $id_representante]);

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row['id_direccion'];
    }

    throw new Exception("No se pudo obtener la dirección del representante");
  }

  /**
   * Relacionar estudiante con representante
   */
  private function relacionarEstudianteRepresentante($id_estudiante, $id_representante, $parentesco)
  {
    $query = "INSERT INTO estudiantes_representantes (id_estudiante, id_representante, parentesco, actualizacion, estatus) 
                  VALUES (:id_estudiante, :id_representante, :parentesco, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':id_estudiante' => $id_estudiante,
      ':id_representante' => $id_representante,
      ':parentesco' => $parentesco
    ]);
  }

  /**
   * Registrar inscripción
   */
  private function registrarInscripcion($id_estudiante, $datosInscripcion, $datosEstudiante)
  {
    // Obtener id_nivel_seccion basado en nivel y sección
    $id_nivel_seccion = $this->obtenerNivelSeccion($datosEstudiante['nivel'], $datosEstudiante['seccion']);

    $query = "INSERT INTO inscripciones (id_estudiante, id_periodo, id_nivel_seccion, id_usuario, 
                                           fecha_inscripcion, observaciones, actualizacion, estatus) 
                  VALUES (:id_estudiante, :id_periodo, :id_nivel_seccion, :id_usuario, 
                         :fecha_inscripcion, :observaciones, NOW(), 1)";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':id_estudiante' => $id_estudiante,
      ':id_periodo' => $datosInscripcion['periodo'],
      ':id_nivel_seccion' => $id_nivel_seccion,
      ':id_usuario' => $datosInscripcion['id_usuario'],
      ':fecha_inscripcion' => $datosInscripcion['fecha_inscripcion'],
      ':observaciones' => $datosInscripcion['observaciones'] ?? null
    ]);
  }

  /**
   * Obtener id_nivel_seccion
   */
  private function obtenerNivelSeccion($nivel, $seccion)
  {
    $query = "SELECT ns.id_nivel_seccion 
                  FROM niveles_secciones ns
                  JOIN niveles n ON ns.id_nivel = n.id_nivel
                  JOIN secciones s ON ns.id_seccion = s.id_seccion
                  WHERE n.num_nivel = :nivel AND s.nom_seccion = :seccion AND ns.estatus = 1";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
      ':nivel' => $nivel,
      ':seccion' => $seccion
    ]);

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      return $row['id_nivel_seccion'];
    }

    throw new Exception("No se encontró la combinación nivel-sección especificada");
  }

  /**
   * Registrar patologías del estudiante
   */
  private function registrarPatologias($id_estudiante, $patologiasTexto)
  {
    if (!empty(trim($patologiasTexto))) {
      // Buscar si ya existe la patología
      $query = "SELECT id_patologia FROM patologias WHERE nom_patologia = :patologia AND estatus = 1";
      $stmt = $this->conn->prepare($query);
      $stmt->execute([':patologia' => $patologiasTexto]);

      if ($stmt->rowCount() > 0) {
        $patologia = $stmt->fetch(PDO::FETCH_ASSOC);
        $id_patologia = $patologia['id_patologia'];
      } else {
        // Crear nueva patología
        $query = "INSERT INTO patologias (nom_patologia, actualizacion, estatus) 
                          VALUES (:patologia, NOW(), 1)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':patologia' => $patologiasTexto]);
        $id_patologia = $this->conn->lastInsertId();
      }

      // Relacionar patología con estudiante
      $query_rel = "INSERT INTO estudiantes_patologias (id_estudiante, id_patologia, actualizacion, estatus) 
                          VALUES (:id_estudiante, :id_patologia, NOW(), 1)";

      $stmt_rel = $this->conn->prepare($query_rel);
      $stmt_rel->execute([
        ':id_estudiante' => $id_estudiante,
        ':id_patologia' => $id_patologia
      ]);
    }
  }

  /**
   * Formatear nombre completo
   */
  private function formatearNombreCompleto($persona)
  {
    return trim($persona['primer_nombre'] . ' ' .
      ($persona['segundo_nombre'] ? $persona['segundo_nombre'] . ' ' : '') .
      $persona['primer_apellido'] . ' ' .
      ($persona['segundo_apellido'] ? $persona['segundo_apellido'] : ''));
  }

  /**
   * Destructor para cerrar conexión
   */
  public function __destruct()
  {
    $this->conn = null;
  }
}
