<?php
class PeriodoController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  public function obtenerPeriodos()
  {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM periodos ORDER BY fecha_ini DESC");
      $stmt->execute();
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error al obtener periodos: " . $e->getMessage());
    }
  }

  public function obtenerPeriodoActivo()
  {
    try {
      $stmt = $this->conn->prepare("SELECT * FROM periodos WHERE estatus = 1 LIMIT 1");
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return null;
    }
  }

  /**
   * Activa un periodo y crea nueva versión en globales
   */
  public function activarPeriodo($idPeriodo, $id_usuario = null)
  {
    try {
      // Iniciar transacción
      $this->conn->beginTransaction();

      // 1. Desactivar todos los periodos
      $stmtDesactivar = $this->conn->prepare("UPDATE periodos SET estatus = 0");
      $stmtDesactivar->execute();

      // 2. Activar el periodo seleccionado
      $stmtActivar = $this->conn->prepare("UPDATE periodos SET estatus = 1, actualizacion = NOW() WHERE id_periodo = ?");
      $stmtActivar->execute([$idPeriodo]);

      // 3. Obtener datos del periodo que se va a activar
      $stmtPeriodo = $this->conn->prepare("SELECT descripcion_periodo FROM periodos WHERE id_periodo = ?");
      $stmtPeriodo->execute([$idPeriodo]);
      $periodo = $stmtPeriodo->fetch(PDO::FETCH_ASSOC);
      $nombrePeriodo = $periodo['descripcion_periodo'] ?? 'Periodo ' . $idPeriodo;

      // 4. Obtener la configuración global activa actual
      $stmtGlobal = $this->conn->prepare("
                SELECT * FROM globales 
                WHERE es_activo = 1 
                ORDER BY version DESC 
                LIMIT 1
            ");
      $stmtGlobal->execute();
      $globalActual = $stmtGlobal->fetch(PDO::FETCH_ASSOC);

      if (!$globalActual) {
        // Si no hay versión activa, buscar la última
        $stmtGlobal = $this->conn->prepare("SELECT * FROM globales ORDER BY version DESC LIMIT 1");
        $stmtGlobal->execute();
        $globalActual = $stmtGlobal->fetch(PDO::FETCH_ASSOC);
      }

      // 5. Desactivar versión actual de globales
      $stmtDesactivarGlobal = $this->conn->prepare("UPDATE globales SET es_activo = 0 WHERE es_activo = 1");
      $stmtDesactivarGlobal->execute();

      // 6. Obtener siguiente versión
      $stmtVersion = $this->conn->prepare("SELECT COALESCE(MAX(version), 0) + 1 as nueva_version FROM globales");
      $stmtVersion->execute();
      $nuevaVersion = $stmtVersion->fetchColumn();

      // 7. Insertar nueva versión con el nuevo periodo
      $stmtNuevaVersion = $this->conn->prepare("
                INSERT INTO globales (
                    edad_min, edad_max, nom_instituto, id_periodo,
                    nom_directora, ci_directora, direccion,
                    version, es_activo, id_usuario_modificacion, motivo_cambio, fecha_modificacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, NOW())
            ");

      $motivo = "Cambio de periodo académico activo a: " . $nombrePeriodo;

      $stmtNuevaVersion->execute([
        $globalActual['edad_min'] ?? 5,
        $globalActual['edad_max'] ?? 18,
        $globalActual['nom_instituto'] ?? 'Institución Educativa',
        $idPeriodo, // NUEVO PERIODO
        $globalActual['nom_directora'] ?? null,
        $globalActual['ci_directora'] ?? null,
        $globalActual['direccion'] ?? null,
        $nuevaVersion,
        $id_usuario ?? $globalActual['id_usuario_modificacion'] ?? 0,
        $motivo
      ]);

      $this->conn->commit();

      return [
        'success' => true,
        'message' => 'Periodo activado correctamente. Nueva versión creada (v' . $nuevaVersion . ')',
        'data' => [
          'id_periodo' => $idPeriodo,
          'periodo' => $nombrePeriodo,
          'version' => $nuevaVersion
        ]
      ];
    } catch (PDOException $e) {
      $this->conn->rollBack();
      return [
        'success' => false,
        'message' => 'Error al activar periodo: ' . $e->getMessage()
      ];
    }
  }

  public function crearPeriodo($descripcion, $fechaIni, $fechaFin)
  {
    try {
      // Validar que no haya superposición de fechas
      $stmtSuperposicion = $this->conn->prepare("
                SELECT COUNT(*) as superpuestos 
                FROM periodos 
                WHERE (fecha_ini BETWEEN ? AND ? OR fecha_fin BETWEEN ? AND ?)
                OR (? BETWEEN fecha_ini AND fecha_fin OR ? BETWEEN fecha_ini AND fecha_fin)
            ");
      $stmtSuperposicion->execute([$fechaIni, $fechaFin, $fechaIni, $fechaFin, $fechaIni, $fechaFin]);
      $superpuestos = $stmtSuperposicion->fetch(PDO::FETCH_ASSOC)['superpuestos'];

      if ($superpuestos > 0) {
        return [
          'success' => false,
          'message' => 'El nuevo periodo se superpone con periodos existentes'
        ];
      }

      // Validar que fecha_ini sea mayor a la fecha_fin del último periodo
      $stmtUltimoPeriodo = $this->conn->prepare("SELECT fecha_fin FROM periodos ORDER BY fecha_fin DESC LIMIT 1");
      $stmtUltimoPeriodo->execute();
      $ultimoPeriodo = $stmtUltimoPeriodo->fetch(PDO::FETCH_ASSOC);

      if ($ultimoPeriodo && $fechaIni <= $ultimoPeriodo['fecha_fin']) {
        return [
          'success' => false,
          'message' => 'La fecha de inicio debe ser posterior al final del último periodo (' . $ultimoPeriodo['fecha_fin'] . ')'
        ];
      }

      // Validar que fecha_ini sea menor que fecha_fin
      if ($fechaIni >= $fechaFin) {
        return [
          'success' => false,
          'message' => 'La fecha de inicio debe ser anterior a la fecha de fin'
        ];
      }

      // Crear el nuevo periodo (inactivo por defecto)
      $stmt = $this->conn->prepare("INSERT INTO periodos (descripcion_periodo, fecha_ini, fecha_fin, estatus) VALUES (?, ?, ?, 0)");
      $stmt->execute([$descripcion, $fechaIni, $fechaFin]);

      return [
        'success' => true,
        'message' => 'Periodo creado correctamente'
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        'message' => 'Error al crear periodo: ' . $e->getMessage()
      ];
    }
  }

  public function obtenerEstadisticasPeriodos()
  {
    try {
      // Total de periodos
      $stmtTotal = $this->conn->prepare("SELECT COUNT(*) as total FROM periodos");
      $stmtTotal->execute();
      $total = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

      // Periodos activos
      $stmtActivos = $this->conn->prepare("SELECT COUNT(*) as activos FROM periodos WHERE estatus = 1");
      $stmtActivos->execute();
      $activos = $stmtActivos->fetch(PDO::FETCH_ASSOC)['activos'];

      // Estudiantes en periodo activo
      $stmtEstudiantes = $this->conn->prepare("
                SELECT COUNT(*) as estudiantes 
                FROM inscripciones i 
                JOIN periodos p ON i.id_periodo = p.id_periodo 
                WHERE p.estatus = 1 AND i.estatus = 1
            ");
      $stmtEstudiantes->execute();
      $estudiantes = $stmtEstudiantes->fetch(PDO::FETCH_ASSOC)['estudiantes'];

      return [
        'total_periodos' => $total,
        'periodos_activos' => $activos,
        'estudiantes_activos' => $estudiantes
      ];
    } catch (PDOException $e) {
      return [
        'total_periodos' => 0,
        'periodos_activos' => 0,
        'estudiantes_activos' => 0
      ];
    }
  }

  /**
   * Obtiene la versión actual de globales para mostrar en la interfaz
   */
  public function obtenerVersionGlobalActual()
  {
    try {
      $stmt = $this->conn->prepare("
                SELECT version, fecha_modificacion 
                FROM globales 
                WHERE es_activo = 1 
                ORDER BY version DESC 
                LIMIT 1
            ");
      $stmt->execute();
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      return ['version' => 1, 'fecha_modificacion' => date('Y-m-d H:i:s')];
    }
  }
}
