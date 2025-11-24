<?php
class RepresentanteController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }


  // Crear nuevo representante
  public function crearRepresentante($id_persona, $datos)
  {
    try {
      $sql = "INSERT INTO representantes (id_persona, id_profesion, ocupacion, lugar_trabajo) 
                    VALUES (:id_persona, :id_profesion, :ocupacion, :lugar_trabajo)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_persona' => $id_persona,
        ':id_profesion' => $datos['id_profesion'],
        ':ocupacion' => $datos['ocupacion'],
        ':lugar_trabajo' => $datos['lugar_trabajo']
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear representante: " . $e->getMessage());
    }
  }

  // Validar representante por cédula
  public function validarRepresentante($cedula)
  {
    try {
      $sql = "SELECT 
                    r.id_representante, 
                    p.*, 
                    r.id_profesion, 
                    r.ocupacion, 
                    r.lugar_trabajo,
                    d.id_direccion,
                    d.id_parroquia,
                    d.direccion,
                    d.calle,
                    d.casa,
                    pr.id_parroquia,
                    pr.nom_parroquia,
                    pr.id_municipio,
                    m.nom_municipio,
                    m.id_estado,
                    e.nom_estado,
                    f.id_profesion AS profesion_id,
                    f.profesion
                FROM representantes r
                INNER JOIN personas p ON r.id_persona = p.id_persona
                INNER JOIN profesiones f ON r.id_profesion = f.id_profesion
                INNER JOIN direcciones d ON p.id_direccion = d.id_direccion
                INNER JOIN parroquias pr ON d.id_parroquia = pr.id_parroquia
                INNER JOIN municipios m ON pr.id_municipio = m.id_municipio
                INNER JOIN estados e ON m.id_estado = e.id_estado
                WHERE p.cedula = :cedula 
                    AND r.estatus = 1 
                    AND p.estatus = 1
                    AND d.estatus = 1";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':cedula' => $cedula]);

      $representante = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($representante) {
        return [
          'existe' => true,
          'tipo' => 'representante',
          'id_representante' => $representante['id_representante'],

          // Datos personales
          'primer_nombre' => $representante['primer_nombre'],
          'segundo_nombre' => $representante['segundo_nombre'],
          'primer_apellido' => $representante['primer_apellido'],
          'segundo_apellido' => $representante['segundo_apellido'],
          'cedula' => $representante['cedula'],
          'telefono' => $representante['telefono'],
          'telefono_hab' => $representante['telefono_hab'],
          'correo' => $representante['correo'],
          'fecha_nac' => $representante['fecha_nac'],
          'lugar_nac' => $representante['lugar_nac'],
          'sexo' => $representante['sexo'],
          'nacionalidad' => $representante['nacionalidad'],
          'profesion' => $representante['id_profesion'],
          'ocupacion' => $representante['ocupacion'],
          'lugar_trabajo' => $representante['lugar_trabajo'],

          // Datos de dirección
          'id_direccion' => $representante['id_direccion'],
          'id_parroquia' => $representante['id_parroquia'],
          'direccion' => $representante['direccion'],
          'calle' => $representante['calle'],
          'casa' => $representante['casa'],
          'id_municipio' => $representante['id_municipio'],
          'id_estado' => $representante['id_estado'],
          'nom_parroquia' => $representante['nom_parroquia'],
          'nom_municipio' => $representante['nom_municipio'],
          'nom_estado' => $representante['nom_estado'],

          'nombre_completo' => trim($representante['primer_nombre'] . ' ' .
            ($representante['segundo_nombre'] ? $representante['segundo_nombre'] . ' ' : '') .
            $representante['primer_apellido'] . ' ' .
            ($representante['segundo_apellido'] ? $representante['segundo_apellido'] : ''))
        ];
      }

      return ['existe' => false];
    } catch (PDOException $e) {
      throw new Exception("Error al validar representante: " . $e->getMessage());
    }
  }

  public function validarDocente($cedula)
  {
    try {
      $sql = "SELECT 
                    d.id_docente, 
                    p.*, 
                    d.id_profesion,
                    dir.id_direccion,
                    dir.id_parroquia,
                    dir.direccion,
                    dir.calle,
                    dir.casa,
                    pr.id_parroquia,
                    pr.nom_parroquia,
                    pr.id_municipio,
                    m.nom_municipio,
                    m.id_estado,
                    e.nom_estado,
                    f.id_profesion AS profesion_id,
                    f.profesion,
                    u.id_usuario,
                    u.usuario,
                    r.id_rol,
                    r.nom_rol
                FROM docentes d
                INNER JOIN personas p ON d.id_persona = p.id_persona
                INNER JOIN profesiones f ON d.id_profesion = f.id_profesion
                INNER JOIN direcciones dir ON p.id_direccion = dir.id_direccion
                INNER JOIN parroquias pr ON dir.id_parroquia = pr.id_parroquia
                INNER JOIN municipios m ON pr.id_municipio = m.id_municipio
                INNER JOIN estados e ON m.id_estado = e.id_estado
                LEFT JOIN usuarios u ON p.id_persona = u.id_persona
                LEFT JOIN roles r ON u.id_rol = r.id_rol
                WHERE p.cedula = :cedula 
                    AND d.estatus = 1 
                    AND p.estatus = 1
                    AND dir.estatus = 1";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':cedula' => $cedula]);

      $docente = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($docente) {
        return [
          'existe' => true,
          'tipo' => 'docente',
          'id_docente' => $docente['id_docente'],
          'id_persona' => $docente['id_persona'],
          'id_usuario' => $docente['id_usuario'],
          'id_rol' => $docente['id_rol'],

          // Datos personales
          'primer_nombre' => $docente['primer_nombre'],
          'segundo_nombre' => $docente['segundo_nombre'],
          'primer_apellido' => $docente['primer_apellido'],
          'segundo_apellido' => $docente['segundo_apellido'],
          'cedula' => $docente['cedula'],
          'telefono' => $docente['telefono'],
          'telefono_hab' => $docente['telefono_hab'],
          'correo' => $docente['correo'],
          'fecha_nac' => $docente['fecha_nac'],
          'lugar_nac' => $docente['lugar_nac'],
          'sexo' => $docente['sexo'],
          'nacionalidad' => $docente['nacionalidad'],
          'profesion' => $docente['id_profesion'],
          'nom_profesion' => $docente['profesion'],

          // Datos de dirección
          'id_direccion' => $docente['id_direccion'],
          'id_parroquia' => $docente['id_parroquia'],
          'direccion' => $docente['direccion'],
          'calle' => $docente['calle'],
          'casa' => $docente['casa'],
          'id_municipio' => $docente['id_municipio'],
          'id_estado' => $docente['id_estado'],
          'nom_parroquia' => $docente['nom_parroquia'],
          'nom_municipio' => $docente['nom_municipio'],
          'nom_estado' => $docente['nom_estado'],

          // Datos de usuario (si existe)
          'usuario' => $docente['usuario'],
          'nom_rol' => $docente['nom_rol'],

          'nombre_completo' => trim($docente['primer_nombre'] . ' ' .
            ($docente['segundo_nombre'] ? $docente['segundo_nombre'] . ' ' : '') .
            $docente['primer_apellido'] . ' ' .
            ($docente['segundo_apellido'] ? $docente['segundo_apellido'] : ''))
        ];
      }

      return ['existe' => false];
    } catch (PDOException $e) {
      throw new Exception("Error al validar docente: " . $e->getMessage());
    }
  }

  // Verificar si persona ya es representante
  public function esRepresentante($id_persona)
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM representantes WHERE id_persona = :id_persona AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_persona' => $id_persona]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar representante: " . $e->getMessage());
    }
  }

  // Crear relación estudiante-representante
  public function crearRelacionEstudianteRepresentante($id_estudiante, $id_representante, $parentesco)
  {
    try {
      $sql = "INSERT INTO estudiantes_representantes (id_estudiante, id_representante, id_parentesco) 
                    VALUES (:id_estudiante, :id_representante, :id_parentesco)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_estudiante' => $id_estudiante,
        ':id_representante' => $id_representante,
        ':id_parentesco' => $parentesco
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear relación estudiante-representante: " . $e->getMessage());
    }
  }

  // Cargando profesion de los representantes
  public function obtenerProfesiones()
  {
    try {
      $sql = "SELECT * FROM profesiones ORDER BY profesion";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estados: " . $e->getMessage());
    }
  }
  public function obtenerProfesionesById($profesionesID)
  {
    try {
      $sql = "SELECT * FROM profesiones WHERE id_profesion = :id_profesion AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_profesion' => $profesionesID]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener parroquias: " . $e->getMessage());
    }
  }

  public function contarEstudiantesPorId($idRepresentante)
  {
    try {
      $sql = "SELECT COUNT(*) as total_estudiantes 
                FROM estudiantes_representantes 
                WHERE id_representante = :id_representante 
                AND estatus = 1";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':id_representante', $idRepresentante, PDO::PARAM_INT);
      $stmt->execute();

      $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

      // Asegurarse de devolver un entero
      return (int)($resultado['total_estudiantes'] ?? 0);
    } catch (PDOException $e) {
      error_log("Error al contar estudiantes del representante: " . $e->getMessage());
      return 0;
    }
  }

  public function actualizarRepresentante($datos)
  {
    $sql = "UPDATE representantes SET 
            ocupacion = ?, 
            lugar_trabajo = ?, 
            id_profesion = ?,
            actualizacion = NOW()
            WHERE id_representante = ?";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      $datos['ocupacion'],
      $datos['lugar_trabajo'],
      $datos['id_profesion'],
      $datos['id_representante']
    ]);
  }
}
