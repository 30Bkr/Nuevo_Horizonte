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
  // Obtener todos los estados (incluyendo inhabilitados) para administración
  public function obtenerTodosLosEstados()
  {
    try {
      $sql = "SELECT * FROM estados ORDER BY nom_estado";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener todos los estados: " . $e->getMessage());
    }
  }
  // Verificar si un estado está en uso en direcciones activas
  public function estadoEnUso($id_estado)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              INNER JOIN estados e ON m.id_estado = e.id_estado
              WHERE e.id_estado = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estado]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar uso del estado: " . $e->getMessage());
    }
  }

  // Obtener conteo de usos de un estado en direcciones activas
  public function obtenerConteoUsosEstado($id_estado)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              INNER JOIN estados e ON m.id_estado = e.id_estado
              WHERE e.id_estado = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estado]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de usos: " . $e->getMessage());
    }
  }

  // Actualizar estado de un estado (habilitar/inhabilitar)
  public function actualizarEstado($id_estado, $estatus)
  {
    try {
      $sql = "UPDATE estados SET estatus = ?, actualizacion = NOW() WHERE id_estado = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_estado]);
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar estado: " . $e->getMessage());
    }
  }

  // Obtener estadísticas de estados
  public function obtenerEstadisticasEstados()
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as habilitados,
              SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inhabilitados
              FROM estados";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estadísticas: " . $e->getMessage());
    }
  }
  // Obtener conteo de estados en uso
  public function obtenerConteoEstadosEnUso()
  {
    try {
      $sql = "SELECT COUNT(DISTINCT e.id_estado) as count
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              INNER JOIN estados e ON m.id_estado = e.id_estado
              WHERE d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de estados en uso: " . $e->getMessage());
    }
  }
}
