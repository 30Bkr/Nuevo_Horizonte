<?php
class PatologiaController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function obtenerPatologiasPorEstudiante($id_estudiante)
  {
    try {
      $sql = "SELECT p.id_patologia, p.nom_patologia 
                FROM estudiantes_patologias ep 
                JOIN patologias p ON ep.id_patologia = p.id_patologia 
                WHERE ep.id_estudiante = ? AND ep.estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estudiante]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error obteniendo patologías del estudiante: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtiene todas las patologías activas
   */
  public function obtenerPatologiasActivas()
  {
    try {
      $sql = "SELECT * FROM patologias WHERE estatus = 1 ORDER BY nom_patologia";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerPatologiasActivas: " . $e->getMessage());
      return [];
    }
  }

  public function obtenerPatologiasActivas2()
  {
    try {
      $sql = "SELECT * FROM patologias ORDER BY nom_patologia";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerPatologiasActivas: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Obtiene una patología por ID
   */
  public function obtenerPatologiaPorId($id_patologia)
  {
    try {
      $sql = "SELECT * FROM patologias WHERE id_patologia = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_patologia]);
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerPatologiaPorId: " . $e->getMessage());
      return null;
    }
  }

  /**
   * Crea una nueva patología
   */
  public function crearPatologia($nom_patologia)
  {
    try {
      $sql = "INSERT INTO patologias (nom_patologia) VALUES (?)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$nom_patologia]);
      return $this->pdo->lastInsertId();
    } catch (PDOException $e) {
      error_log("Error en crearPatologia: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Actualiza una patología
   */
  public function actualizarPatologia($id_patologia, $nom_patologia)
  {
    try {
      $sql = "UPDATE patologias SET nom_patologia = ?, actualizacion = NOW() WHERE id_patologia = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$nom_patologia, $id_patologia]);
    } catch (PDOException $e) {
      error_log("Error en actualizarPatologia: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Cambia el estatus de una patología (activar/desactivar)
   */
  public function cambiarEstatusPatologia($id_patologia, $estatus)
  {
    try {
      $sql = "UPDATE patologias SET estatus = ?, actualizacion = NOW() WHERE id_patologia = ?";
      $stmt = $this->pdo->prepare($sql);
      return $stmt->execute([$estatus, $id_patologia]);
    } catch (PDOException $e) {
      error_log("Error en cambiarEstatusPatologia: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene todas las patologías (incluyendo inactivas) para administración
   */
  public function obtenerTodasLasPatologias()
  {
    try {
      $sql = "SELECT * FROM patologias ORDER BY id_patologia ASC";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerTodasLasPatologias: " . $e->getMessage());
      return [];
    }
  }

  /**
   * Cuenta las asignaciones de patologías a estudiantes
   */
  public function contarAsignacionesEstudiantes()
  {
    try {
      $sql = "SELECT COUNT(*) as total FROM estudiantes_patologias WHERE estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'];
    } catch (PDOException $e) {
      error_log("Error en contarAsignacionesEstudiantes: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Verifica si una patología está en uso por estudiantes
   */
  public function patologiaEnUso($id_patologia)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_patologias WHERE id_patologia = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_patologia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'] > 0;
    } catch (PDOException $e) {
      error_log("Error en patologiaEnUso: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Obtiene el conteo específico de usos de una patología
   */
  public function obtenerConteoUsosPatologia($id_patologia)
  {
    try {
      $sql = "SELECT COUNT(*) as count FROM estudiantes_patologias WHERE id_patologia = ? AND estatus = 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_patologia]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['count'];
    } catch (PDOException $e) {
      error_log("Error en obtenerConteoUsosPatologia: " . $e->getMessage());
      return 0;
    }
  }

  /**
   * Agrega una nueva patología
   */
  public function agregarPatologia($nombre)
  {
    try {
      $id = $this->crearPatologia(trim($nombre));
      if ($id) {
        return ['success' => true, 'message' => 'Patología agregada correctamente', 'id' => $id];
      } else {
        return ['success' => false, 'message' => 'Error al agregar patología'];
      }
    } catch (PDOException $e) {
      error_log("Error en agregarPatologia: " . $e->getMessage());
      return ['success' => false, 'message' => 'Error al agregar patología: ' . $e->getMessage()];
    }
  }

  /**
   * Actualiza una patología (nombre y estatus)
   */
  public function actualizarPatologiaCompleta($id, $nombre, $estatus)
  {
    try {
      // Primero actualizar el nombre
      $nombreActualizado = $this->actualizarPatologia($id, trim($nombre));

      // Luego cambiar el estatus
      $estatusActualizado = $this->cambiarEstatusPatologia($id, $estatus);

      if ($nombreActualizado || $estatusActualizado) {
        return ['success' => true, 'message' => 'Patología actualizada correctamente'];
      } else {
        return ['success' => false, 'message' => 'Error al actualizar patología'];
      }
    } catch (PDOException $e) {
      error_log("Error en actualizarPatologiaCompleta: " . $e->getMessage());
      return ['success' => false, 'message' => 'Error al actualizar patología: ' . $e->getMessage()];
    }
  }

  /**
   * Obtiene estadísticas generales de patologías
   */
  public function obtenerEstadisticasPatologias()
  {
    try {
      $sql = "SELECT 
              COUNT(*) as total,
              SUM(CASE WHEN estatus = 1 THEN 1 ELSE 0 END) as activas,
              SUM(CASE WHEN estatus = 0 THEN 1 ELSE 0 END) as inactivas
              FROM patologias";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerEstadisticasPatologias: " . $e->getMessage());
      return ['total' => 0, 'activas' => 0, 'inactivas' => 0];
    }
  }

  /**
   * Método para compatibilidad
   */
  public function obtenerPatologias()
  {
    return $this->obtenerTodasLasPatologias();
  }
}
