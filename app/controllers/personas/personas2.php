<?php
class PersonasController
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  /**
   * Obtener representante por ID - Versión mejorada
   */
  public function obtenerRepresentantePorId($id_representante)
  {
    try {
      // Primero obtener el representante
      $sqlRepresentante = "SELECT 
                    r.id_representante,
                    r.ocupacion,
                    r.lugar_trabajo,
                    r.id_profesion,
                    r.id_persona
                FROM representantes r
                WHERE r.id_representante = :id_representante
                AND r.estatus = 1";

      $stmt = $this->db->prepare($sqlRepresentante);
      $stmt->bindParam(':id_representante', $id_representante, PDO::PARAM_INT);
      $stmt->execute();
      $representante = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$representante) {
        return false;
      }

      // Luego obtener los datos de la persona
      $sqlPersona = "SELECT 
                    p.id_persona,
                    p.id_direccion,
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
                    p.nacionalidad
                FROM personas p
                WHERE p.id_persona = :id_persona
                AND p.estatus = 1";

      $stmtPersona = $this->db->prepare($sqlPersona);
      $stmtPersona->bindParam(':id_persona', $representante['id_persona'], PDO::PARAM_INT);
      $stmtPersona->execute();
      $persona = $stmtPersona->fetch(PDO::FETCH_ASSOC);

      // Obtener datos de dirección
      $sqlDireccion = "SELECT 
                    d.id_direccion,
                    d.id_parroquia,
                    d.direccion,
                    d.calle,
                    d.casa
                FROM direcciones d
                WHERE d.id_direccion = :id_direccion
                AND d.estatus = 1";

      $stmtDireccion = $this->db->prepare($sqlDireccion);
      $stmtDireccion->bindParam(':id_direccion', $persona['id_direccion'], PDO::PARAM_INT);
      $stmtDireccion->execute();
      $direccion = $stmtDireccion->fetch(PDO::FETCH_ASSOC);

      // Obtener datos de parroquia, municipio y estado
      $sqlUbicacion = "SELECT 
                    pa.id_parroquia,
                    pa.nom_parroquia,
                    pa.id_municipio,
                    m.id_municipio,
                    m.nom_municipio,
                    m.id_estado,
                    e.id_estado,
                    e.nom_estado
                FROM parroquias pa
                INNER JOIN municipios m ON pa.id_municipio = m.id_municipio
                INNER JOIN estados e ON m.id_estado = e.id_estado
                WHERE pa.id_parroquia = :id_parroquia
                AND pa.estatus = 1
                AND m.estatus = 1
                AND e.estatus = 1";

      $stmtUbicacion = $this->db->prepare($sqlUbicacion);
      $stmtUbicacion->bindParam(':id_parroquia', $direccion['id_parroquia'], PDO::PARAM_INT);
      $stmtUbicacion->execute();
      $ubicacion = $stmtUbicacion->fetch(PDO::FETCH_ASSOC);

      // DEPURACIÓN: Mostrar lo que se está obteniendo
      error_log("Datos obtenidos para representante ID {$id_representante}:");
      error_log("Representante: " . print_r($representante, true));
      error_log("Persona: " . print_r($persona, true));
      error_log("Dirección: " . print_r($direccion, true));
      error_log("Ubicación: " . print_r($ubicacion, true));

      // Combinar todos los datos SIN sobrescribir claves
      $datosCompletos = array_merge(
        $representante,
        $persona,
        $direccion,
        $ubicacion
      );

      error_log("Datos combinados: " . print_r($datosCompletos, true));

      return $datosCompletos;
    } catch (PDOException $e) {
      error_log("Error al obtener representante: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Actualizar representante - Versión simplificada
   */
  public function actualizarRepresentante($id_representante, $datos)
  {
    try {
      $this->db->beginTransaction();

      // 1. Obtener información actual
      $representante = $this->obtenerRepresentantePorId($id_representante);
      if (!$representante) {
        throw new Exception("Representante no encontrado");
      }

      // 2. Validar datos requeridos
      $camposRequeridos = [
        'nacionalidad',
        'cedula',
        'primer_nombre',
        'primer_apellido',
        'sexo',
        'lugar_nac',
        'telefono',
        'correo',
        'id_profesion',
        'ocupacion',
        'id_estado',
        'id_municipio',
        'id_parroquia',
        'direccion'
      ];

      foreach ($camposRequeridos as $campo) {
        if (!isset($datos[$campo]) || empty(trim($datos[$campo]))) {
          throw new Exception("El campo '{$campo}' es requerido");
        }
      }

      // 3. Actualizar dirección
      $sqlDireccion = "UPDATE direcciones SET
                id_parroquia = :id_parroquia,
                direccion = :direccion,
                calle = :calle,
                casa = :casa,
                actualizacion = NOW()
                WHERE id_direccion = :id_direccion";

      $stmtDireccion = $this->db->prepare($sqlDireccion);
      $stmtDireccion->execute([
        ':id_parroquia' => $datos['id_parroquia'],
        ':direccion' => $datos['direccion'],
        ':calle' => $datos['calle'] ?? null,
        ':casa' => $datos['casa'] ?? null,
        ':id_direccion' => $representante['id_direccion']
      ]);

      // 4. Actualizar persona
      $sqlPersona = "UPDATE personas SET
                primer_nombre = :primer_nombre,
                segundo_nombre = :segundo_nombre,
                primer_apellido = :primer_apellido,
                segundo_apellido = :segundo_apellido,
                cedula = :cedula,
                telefono = :telefono,
                telefono_hab = :telefono_hab,
                correo = :correo,
                lugar_nac = :lugar_nac,
                fecha_nac = :fecha_nac,
                sexo = :sexo,
                nacionalidad = :nacionalidad,
                actualizacion = NOW()
                WHERE id_persona = :id_persona";

      $stmtPersona = $this->db->prepare($sqlPersona);
      $stmtPersona->execute([
        ':primer_nombre' => strtoupper($datos['primer_nombre']),
        ':segundo_nombre' => !empty($datos['segundo_nombre']) ? strtoupper($datos['segundo_nombre']) : null,
        ':primer_apellido' => strtoupper($datos['primer_apellido']),
        ':segundo_apellido' => !empty($datos['segundo_apellido']) ? strtoupper($datos['segundo_apellido']) : null,
        ':cedula' => $datos['cedula'],
        ':telefono' => $datos['telefono'],
        ':telefono_hab' => !empty($datos['telefono_hab']) ? $datos['telefono_hab'] : null,
        ':correo' => $datos['correo'],
        ':lugar_nac' => strtoupper($datos['lugar_nac']),
        ':fecha_nac' => !empty($datos['fecha_nac']) ? $datos['fecha_nac'] : null,
        ':sexo' => $datos['sexo'],
        ':nacionalidad' => $datos['nacionalidad'],
        ':id_persona' => $representante['id_persona']
      ]);

      // 5. Actualizar representante
      $sqlRepresentante = "UPDATE representantes SET
                ocupacion = :ocupacion,
                lugar_trabajo = :lugar_trabajo,
                id_profesion = :id_profesion,
                actualizacion = NOW()
                WHERE id_representante = :id_representante";

      $stmtRepresentante = $this->db->prepare($sqlRepresentante);
      $stmtRepresentante->execute([
        ':ocupacion' => !empty($datos['ocupacion']) ? strtoupper($datos['ocupacion']) : null,
        ':lugar_trabajo' => !empty($datos['lugar_trabajo']) ? strtoupper($datos['lugar_trabajo']) : null,
        ':id_profesion' => $datos['id_profesion'],
        ':id_representante' => $id_representante
      ]);

      $this->db->commit();

      return [
        'success' => true,
        'message' => 'Representante actualizado exitosamente'
      ];
    } catch (PDOException $e) {
      if ($this->db->inTransaction()) {
        $this->db->rollBack();
      }

      error_log("Error al actualizar representante: " . $e->getMessage());

      return [
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
      ];
    } catch (Exception $e) {
      if ($this->db->inTransaction()) {
        $this->db->rollBack();
      }

      error_log("Error al actualizar representante: " . $e->getMessage());

      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  /**
   * Obtener todos los estados
   */
  public function obtenerEstados()
  {
    try {
      $sql = "SELECT id_estado, nom_estado FROM estados WHERE estatus = 1 ORDER BY nom_estado";
      $stmt = $this->db->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error al obtener estados: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtener municipios por estado
   */
  public function obtenerMunicipiosPorEstado($id_estado)
  {
    try {
      $sql = "SELECT id_municipio, nom_municipio 
                    FROM municipios 
                    WHERE id_estado = :id_estado 
                    AND estatus = 1 
                    ORDER BY nom_municipio";

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id_estado', $id_estado, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error al obtener municipios: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtener parroquias por municipio
   */
  public function obtenerParroquiasPorMunicipio($id_municipio)
  {
    try {
      $sql = "SELECT id_parroquia, nom_parroquia 
                    FROM parroquias 
                    WHERE id_municipio = :id_municipio 
                    AND estatus = 1 
                    ORDER BY nom_parroquia";

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':id_municipio', $id_municipio, PDO::PARAM_INT);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error al obtener parroquias: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtener todas las profesiones
   */
  public function obtenerProfesiones()
  {
    try {
      $sql = "SELECT id_profesion, profesion FROM profesiones WHERE estatus = 1 ORDER BY profesion";
      $stmt = $this->db->query($sql);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error al obtener profesiones: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Validar cédula única
   */
  public function validarCedulaUnica($cedula, $id_persona_actual = null)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
                    FROM personas 
                    WHERE cedula = :cedula 
                    AND estatus = 1";

      if ($id_persona_actual) {
        $sql .= " AND id_persona != :id_persona_actual";
      }

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':cedula', $cedula, PDO::PARAM_STR);

      if ($id_persona_actual) {
        $stmt->bindParam(':id_persona_actual', $id_persona_actual, PDO::PARAM_INT);
      }

      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] == 0;
    } catch (PDOException $e) {
      error_log("Error al validar cédula: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Validar correo único
   */
  public function validarCorreoUnico($correo, $id_persona_actual = null)
  {
    try {
      if (empty($correo)) {
        return true; // Correo vacío no necesita validación de unicidad
      }

      $sql = "SELECT COUNT(*) as count 
                    FROM personas 
                    WHERE correo = :correo 
                    AND estatus = 1";

      if ($id_persona_actual) {
        $sql .= " AND id_persona != :id_persona_actual";
      }

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);

      if ($id_persona_actual) {
        $stmt->bindParam(':id_persona_actual', $id_persona_actual, PDO::PARAM_INT);
      }

      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] == 0;
    } catch (PDOException $e) {
      error_log("Error al validar correo: " . $e->getMessage());
      return false;
    }
  }
}
