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

  public function obtenerEstados2()
  {
    try {
      $sql = "SELECT * FROM estados ORDER BY nom_estado";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estados: " . $e->getMessage());
    }
  }

  // Obtener todos los municipios para administración
  public function obtenerTodosLosMunicipios($id_estado = null)
  {
    try {
      $sql = "SELECT m.*, e.nom_estado 
              FROM municipios m
              INNER JOIN estados e ON m.id_estado = e.id_estado WHERE e.estatus = 1";

      if ($id_estado) {
        $sql .= " WHERE m.id_estado = :id_estado";
      }

      $sql .= " ORDER BY e.nom_estado, m.nom_municipio";

      $stmt = $this->pdo->prepare($sql);

      if ($id_estado) {
        $stmt->execute([':id_estado' => $id_estado]);
      } else {
        $stmt->execute();
      }

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener municipios: " . $e->getMessage());
    }
  }

  // Verificar si un municipio está en uso en direcciones activas
  public function municipioEnUso($id_municipio)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              WHERE m.id_municipio = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_municipio]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar uso del municipio: " . $e->getMessage());
    }
  }

  // Obtener conteo de usos de un municipio en direcciones activas
  public function obtenerConteoUsosMunicipio($id_municipio)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              WHERE m.id_municipio = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_municipio]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de usos del municipio: " . $e->getMessage());
    }
  }

  // Actualizar estado de un municipio
  public function actualizarMunicipio($id_municipio, $estatus)
  {
    try {
      $sql = "UPDATE municipios SET estatus = ?, actualizacion = NOW() WHERE id_municipio = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_municipio]);
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar municipio: " . $e->getMessage());
    }
  }

  // Obtener estadísticas de municipios
  public function obtenerEstadisticasMunicipios($id_estado = null)
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN m.estatus = 1 THEN 1 ELSE 0 END) as habilitados,
              SUM(CASE WHEN m.estatus = 0 THEN 1 ELSE 0 END) as inhabilitados
              FROM municipios m";

      if ($id_estado) {
        $sql .= " WHERE m.id_estado = :id_estado";
      }

      $stmt = $this->pdo->prepare($sql);

      if ($id_estado) {
        $stmt->execute([':id_estado' => $id_estado]);
      } else {
        $stmt->execute();
      }

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estadísticas de municipios: " . $e->getMessage());
    }
  }

  // Obtener conteo de municipios en uso
  public function obtenerConteoMunicipiosEnUso($id_estado = null)
  {
    try {
      $sql = "SELECT COUNT(DISTINCT m.id_municipio) as count
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio";

      if ($id_estado) {
        $sql .= " WHERE d.estatus = 1 AND m.id_estado = :id_estado";
      } else {
        $sql .= " WHERE d.estatus = 1";
      }

      $stmt = $this->pdo->prepare($sql);

      if ($id_estado) {
        $stmt->execute([':id_estado' => $id_estado]);
      } else {
        $stmt->execute();
      }

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de municipios en uso: " . $e->getMessage());
    }
  }
  public function obtenerTodasLasParroquias($id_municipio = null, $id_estado = null)
  {
    try {
      $sql = "SELECT p.*, m.nom_municipio, e.nom_estado 
              FROM parroquias p
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              INNER JOIN estados e ON m.id_estado = e.id_estado WHERE m.estatus = 1 AND e.estatus = 1 ";

      $conditions = [];
      $params = [];

      if ($id_municipio) {
        $conditions[] = "p.id_municipio = :id_municipio";
        $params[':id_municipio'] = $id_municipio;
      }

      if ($id_estado) {
        $conditions[] = "m.id_estado = :id_estado";
        $params[':id_estado'] = $id_estado;
      }

      if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
      }

      $sql .= " ORDER BY e.nom_estado, m.nom_municipio, p.nom_parroquia";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener parroquias: " . $e->getMessage());
    }
  }

  // Verificar si una parroquia está en uso en direcciones activas
  public function parroquiaEnUso($id_parroquia)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              WHERE d.id_parroquia = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_parroquia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'] > 0;
    } catch (PDOException $e) {
      throw new Exception("Error al verificar uso de la parroquia: " . $e->getMessage());
    }
  }

  // Obtener conteo de usos de una parroquia en direcciones activas
  public function obtenerConteoUsosParroquia($id_parroquia)
  {
    try {
      $sql = "SELECT COUNT(*) as count 
              FROM direcciones d
              WHERE d.id_parroquia = ? AND d.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_parroquia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de usos de la parroquia: " . $e->getMessage());
    }
  }

  // Actualizar estado de una parroquia
  public function actualizarParroquia($id_parroquia, $estatus)
  {
    try {
      $sql = "UPDATE parroquias SET estatus = ?, actualizacion = NOW() WHERE id_parroquia = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_parroquia]);
    } catch (PDOException $e) {
      throw new Exception("Error al actualizar parroquia: " . $e->getMessage());
    }
  }

  // Obtener estadísticas de parroquias
  public function obtenerEstadisticasParroquias($id_municipio = null, $id_estado = null)
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN p.estatus = 1 THEN 1 ELSE 0 END) as habilitados,
              SUM(CASE WHEN p.estatus = 0 THEN 1 ELSE 0 END) as inhabilitados
              FROM parroquias p
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio";

      $conditions = [];
      $params = [];

      if ($id_municipio) {
        $conditions[] = "p.id_municipio = :id_municipio";
        $params[':id_municipio'] = $id_municipio;
      }

      if ($id_estado) {
        $conditions[] = "m.id_estado = :id_estado";
        $params[':id_estado'] = $id_estado;
      }

      if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener estadísticas de parroquias: " . $e->getMessage());
    }
  }

  // Obtener conteo de parroquias en uso
  public function obtenerConteoParroquiasEnUso($id_municipio = null, $id_estado = null)
  {
    try {
      $sql = "SELECT COUNT(DISTINCT p.id_parroquia) as count
              FROM direcciones d
              INNER JOIN parroquias p ON d.id_parroquia = p.id_parroquia
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio";

      $conditions = ["d.estatus = 1"];
      $params = [];

      if ($id_municipio) {
        $conditions[] = "p.id_municipio = :id_municipio";
        $params[':id_municipio'] = $id_municipio;
      }

      if ($id_estado) {
        $conditions[] = "m.id_estado = :id_estado";
        $params[':id_estado'] = $id_estado;
      }

      $sql .= " WHERE " . implode(" AND ", $conditions);

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'];
    } catch (PDOException $e) {
      throw new Exception("Error al obtener conteo de parroquias en uso: " . $e->getMessage());
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
