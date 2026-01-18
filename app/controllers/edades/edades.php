<?php
class EdadesController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  /**
   * Obtiene la configuración de edades activa
   */
  public function obtenerConfiguracionEdades()
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT * FROM globales 
                WHERE es_activo = 1 
                ORDER BY version DESC 
                LIMIT 1
            ");
      $stmt->execute();
      $configuracion = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$configuracion) {
        $stmt = $this->conn->prepare("
                    SELECT * FROM globales 
                    ORDER BY version DESC 
                    LIMIT 1
                ");
        $stmt->execute();
        $configuracion = $stmt->fetch(PDO::FETCH_ASSOC);
      }

      if (!$configuracion) {
        return [
          'edad_min' => 5,
          'edad_max' => 18,
          'id_periodo' => 1,
          'version' => 1,
          'es_activo' => 1
        ];
      }

      return $configuracion;
    } catch (PDOException $e) {
      throw new Exception("Error al obtener configuración de edades: " . $e->getMessage());
    }
  }

  /**
   * Actualiza la configuración de edades creando nueva versión
   */
  public function actualizarConfiguracionEdades($edadMin, $edadMax, $id_usuario)
  {
    try {
      // Validaciones
      if ($edadMin >= $edadMax) {
        return [
          'success' => false,
          'message' => 'La edad mínima debe ser menor que la edad máxima.'
        ];
      }

      if ($edadMin < 0 || $edadMax < 0) {
        return [
          'success' => false,
          'message' => 'Las edades no pueden ser números negativos.'
        ];
      }

      if ($edadMin > 25 || $edadMax > 25) {
        return [
          'success' => false,
          'message' => 'Las edades no pueden ser mayores a 25 años.'
        ];
      }

      // Iniciar transacción
      $this->conn->beginTransaction();

      // 1. Obtener configuración actual
      $configActual = $this->obtenerConfiguracionEdades();

      // 2. Desactivar versión actual
      $stmt = $this->conn->prepare("UPDATE globales SET es_activo = 0 WHERE es_activo = 1");
      $stmt->execute();

      // 3. Obtener siguiente versión
      $versionStmt = $this->conn->prepare("SELECT COALESCE(MAX(version), 0) + 1 as nueva_version FROM globales");
      $versionStmt->execute();
      $nuevaVersion = $versionStmt->fetchColumn();

      // 4. Insertar nueva versión
      $stmt = $this->conn->prepare("
                INSERT INTO globales (
                    edad_min, edad_max, nom_instituto, id_periodo,
                    nom_directora, ci_directora, direccion,
                    version, es_activo, id_usuario_modificacion, motivo_cambio, fecha_modificacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, NOW())
            ");

      $motivoCambio = "Ajuste de rango de edades: de {$configActual['edad_min']}-{$configActual['edad_max']} a $edadMin-$edadMax años";

      $stmt->execute([
        $edadMin,
        $edadMax,
        $configActual['nom_instituto'] ?? 'Institución Educativa',
        $configActual['id_periodo'] ?? 1,
        $configActual['nom_directora'] ?? null,
        $configActual['ci_directora'] ?? null,
        $configActual['direccion'] ?? null,
        $nuevaVersion,
        $id_usuario,
        $motivoCambio
      ]);

      $this->conn->commit();

      return [
        'success' => true,
        'message' => "✅ Rango de edades actualizado (Versión $nuevaVersion)",
        'data' => [
          'edad_min' => $edadMin,
          'edad_max' => $edadMax,
          'version' => $nuevaVersion
        ]
      ];
    } catch (PDOException $e) {
      $this->conn->rollBack();
      return [
        'success' => false,
        'message' => 'Error al actualizar edades: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Obtiene estadísticas de edades
   */
  public function obtenerEstadisticasEdades()
  {
    try {
      $config = $this->obtenerConfiguracionEdades();
      $id_periodo = $config['id_periodo'] ?? 1;

      $stmt = $this->conn->prepare("
                SELECT 
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - 
                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) as edad,
                    COUNT(*) as cantidad
                FROM estudiantes e 
                JOIN personas p ON e.id_persona = p.id_persona 
                JOIN inscripciones i ON e.id_estudiante = i.id_estudiante 
                WHERE i.id_periodo = ?
                AND i.estatus = 1
                AND e.estatus = 1
                GROUP BY edad
                ORDER BY edad
            ");
      $stmt->execute([$id_periodo]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  /**
   * Obtiene estudiantes fuera del rango
   */
  public function obtenerEstudiantesFueraRango()
  {
    try {
      $config = $this->obtenerConfiguracionEdades();
      $edad_min = $config['edad_min'] ?? 5;
      $edad_max = $config['edad_max'] ?? 18;
      $id_periodo = $config['id_periodo'] ?? 1;

      $stmt = $this->conn->prepare("
                SELECT 
                    p.primer_nombre,
                    p.primer_apellido,
                    p.fecha_nac,
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - 
                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) as edad,
                    n.nom_nivel,
                    s.nom_seccion
                FROM estudiantes e 
                JOIN personas p ON e.id_persona = p.id_persona 
                JOIN inscripciones i ON e.id_estudiante = i.id_estudiante 
                JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion
                JOIN niveles n ON ns.id_nivel = n.id_nivel
                JOIN secciones s ON ns.id_seccion = s.id_seccion
                WHERE i.id_periodo = ?
                AND i.estatus = 1
                AND e.estatus = 1
                AND (
                    YEAR(CURDATE()) - YEAR(p.fecha_nac) - 
                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) < ?
                    OR YEAR(CURDATE()) - YEAR(p.fecha_nac) - 
                    (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.fecha_nac, '%m%d')) > ?
                )
                ORDER BY edad
            ");
      $stmt->execute([$id_periodo, $edad_min, $edad_max]);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return [];
    }
  }

  /**
   * Obtiene información de la última modificación (MÉTODO NUEVO)
   */
  public function obtenerInfoUltimaModificacion()
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT 
                    g.version,
                    g.fecha_modificacion,
                    g.motivo_cambio,
                    u.usuario,
                    CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_usuario
                FROM globales g
                LEFT JOIN usuarios u ON g.id_usuario_modificacion = u.id_usuario
                LEFT JOIN personas p ON u.id_persona = p.id_persona
                WHERE g.es_activo = 1
                ORDER BY g.version DESC 
                LIMIT 1
            ");
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error en obtenerInfoUltimaModificacion: " . $e->getMessage());
      return null;
    }
  }
}
