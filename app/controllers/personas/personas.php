<?php
class PersonaController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Crear nueva persona
  public function crearPersona($datos)
  {
    try {
      $sql = "INSERT INTO personas (
                id_direccion, primer_nombre, segundo_nombre, primer_apellido, 
                segundo_apellido, cedula, telefono, telefono_hab, correo, 
                lugar_nac, fecha_nac, sexo, nacionalidad
            ) VALUES (:id_direccion, :primer_nombre, :segundo_nombre, :primer_apellido, 
                     :segundo_apellido, :cedula, :telefono, :telefono_hab, :correo, 
                     :lugar_nac, :fecha_nac, :sexo, :nacionalidad)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_direccion' => $datos['id_direccion'],
        ':primer_nombre' => $datos['primer_nombre'],
        ':segundo_nombre' => $datos['segundo_nombre'],
        ':primer_apellido' => $datos['primer_apellido'],
        ':segundo_apellido' => $datos['segundo_apellido'],
        ':cedula' => $datos['cedula'],
        ':telefono' => $datos['telefono'],
        ':telefono_hab' => $datos['telefono_hab'],
        ':correo' => $datos['correo'],
        ':lugar_nac' => $datos['lugar_nac'],
        ':fecha_nac' => $datos['fecha_nac'],
        ':sexo' => $datos['sexo'],
        ':nacionalidad' => $datos['nacionalidad']
      ]);

      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("Error al crear persona: " . $e->getMessage());
    }
  }

  // Buscar persona por cédula
  public function buscarPorCedula($cedula)
  {
    try {
      $sql = "SELECT * FROM personas WHERE cedula = :cedula AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':cedula' => $cedula]);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al buscar persona: " . $e->getMessage());
    }
  }

  // Obtener persona por ID
  public function obtenerPorId($id_persona)
  {
    try {
      $sql = "SELECT * FROM personas WHERE id_persona = :id_persona AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_persona' => $id_persona]);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener persona: " . $e->getMessage());
    }
  }

  // Validar si cédula ya existe
  public function cedulaExiste($cedula)
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM personas WHERE cedula = :cedula AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':cedula' => $cedula]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al validar cédula: " . $e->getMessage());
    }
  }
}
