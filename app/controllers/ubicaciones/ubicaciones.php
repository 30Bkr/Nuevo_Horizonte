<?php
class UbicacionController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Obtener todos los estados
  public function obtenerEstados()
  {
    try {
      $sql = "SELECT * FROM estados WHERE estatus = 1 ORDER BY nom_estado";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estados: " . $e->getMessage());
    }
  }

  // Obtener municipios por estado
  public function obtenerMunicipiosPorEstado($id_estado)
  {
    try {
      $sql = "SELECT * FROM municipios WHERE id_estado = :id_estado AND estatus = 1 ORDER BY nom_municipio";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_estado' => $id_estado]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener municipios: " . $e->getMessage());
    }
  }

  // Obtener parroquias por municipio
  public function obtenerParroquiasPorMunicipio($id_municipio)
  {
    try {
      $sql = "SELECT * FROM parroquias WHERE id_municipio = :id_municipio AND estatus = 1 ORDER BY nom_parroquia";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([':id_municipio' => $id_municipio]);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener parroquias: " . $e->getMessage());
    }
  }

  // Crear nueva dirección
  // public function crearDireccion($datos)
  // {
  //   try {
  //     $sql = "INSERT INTO direcciones (id_parroquia, direccion, calle, casa) 
  //                   VALUES (:id_parroquia, :direccion, :calle, :casa)";
  //     $stmt = $this->pdo->prepare($sql);
  //     $stmt->execute([
  //       ':id_parroquia' => $datos['id_parroquia'],
  //       ':direccion' => $datos['direccion'],
  //       ':calle' => $datos['calle'],
  //       ':casa' => $datos['casa']
  //     ]);

  //     return $this->pdo->lastInsertId();
  //   } catch (PDOException $e) {
  //     throw new Exception("Error al crear dirección: " . $e->getMessage());
  //   }
  // }
  public function crearDireccion($datosDireccion)
  {
    try {
      $sql = "INSERT INTO direcciones (id_parroquia, direccion, calle, casa) 
                VALUES (:id_parroquia, :direccion, :calle, :casa)";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([
        ':id_parroquia' => $datosDireccion['id_parroquia'],
        ':direccion' => $datosDireccion['direccion'],
        ':calle' => $datosDireccion['calle'] ?? '',
        ':casa' => $datosDireccion['casa'] ?? ''
      ]);

      $id_direccion = $this->pdo->lastInsertId();
      error_log("Dirección creada exitosamente con ID: " . $id_direccion);

      return $id_direccion;
    } catch (PDOException $e) {
      error_log("Error al crear dirección: " . $e->getMessage());
      throw new Exception("Error al crear dirección: " . $e->getMessage());
    }
  }

  public function actualizarDireccion($datos)
  {
    $sql = "UPDATE direcciones SET 
            id_parroquia = ?, 
            direccion = ?, 
            calle = ?, 
            casa = ?,
            actualizacion = NOW()
            WHERE id_direccion = ?";

    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
      $datos['id_parroquia'],
      $datos['direccion'],
      $datos['calle'],
      $datos['casa'],
      $datos['id_direccion']
    ]);
  }
}
