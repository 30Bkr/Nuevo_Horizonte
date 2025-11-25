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

  public function activarPeriodo($idPeriodo)
  {
    try {
      // Iniciar transacción
      $this->conn->beginTransaction();

      // Primero, desactivar todos los periodos
      $stmtDesactivar = $this->conn->prepare("UPDATE periodos SET estatus = 0");
      $stmtDesactivar->execute();

      // Luego, activar el periodo seleccionado
      $stmtActivar = $this->conn->prepare("UPDATE periodos SET estatus = 1, actualizacion = NOW() WHERE id_periodo = ?");
      $stmtActivar->execute([$idPeriodo]);

      // Actualizar globales con el nuevo periodo activo
      $stmtGlobales = $this->conn->prepare("UPDATE globales SET id_periodo = ? WHERE id_globales = 1");
      $stmtGlobales->execute([$idPeriodo]);

      $this->conn->commit();

      return [
        'success' => true,
        'message' => 'Periodo activado correctamente'
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

  // public function generarPeriodosAutomaticos($fechaInicio, $aniosFuturos)
  // {
  //   try {
  //     $periodosCreados = [];
  //     $fechaActual = new DateTime($fechaInicio);

  //     // Obtener el último periodo para empezar desde ahí
  //     $stmtUltimo = $this->conn->prepare("SELECT fecha_fin FROM periodos ORDER BY fecha_fin DESC LIMIT 1");
  //     $stmtUltimo->execute();
  //     $ultimoPeriodo = $stmtUltimo->fetch(PDO::FETCH_ASSOC);

  //     if ($ultimoPeriodo) {
  //       $fechaActual = new DateTime($ultimoPeriodo['fecha_fin']);
  //       $fechaActual->modify('+1 day'); // Empezar al día siguiente del último periodo
  //     }

  //     for ($i = 1; $i <= $aniosFuturos; $i++) {
  //       $fechaIniPeriodo = clone $fechaActual;
  //       $fechaFinPeriodo = clone $fechaActual;
  //       $fechaFinPeriodo->modify('+10 months'); // Aproximadamente un año escolar

  //       $descripcion = 'Año Escolar ' . $fechaIniPeriodo->format('Y') . '-' . $fechaFinPeriodo->format('Y');

  //       $resultado = $this->crearPeriodo(
  //         $descripcion,
  //         $fechaIniPeriodo->format('Y-m-d'),
  //         $fechaFinPeriodo->format('Y-m-d')
  //       );

  //       if ($resultado['success']) {
  //         $periodosCreados[] = $descripcion;
  //       }

  //       // Preparar siguiente periodo (empezar después del fin del actual)
  //       $fechaActual = clone $fechaFinPeriodo;
  //       $fechaActual->modify('+2 months'); // Vacaciones entre periodos
  //     }

  //     return [
  //       'success' => true,
  //       'message' => 'Se crearon ' . count($periodosCreados) . ' periodos automáticamente',
  //       'periodos_creados' => $periodosCreados
  //     ];
  //   } catch (Exception $e) {
  //     return [
  //       'success' => false,
  //       'message' => 'Error al generar periodos automáticos: ' . $e->getMessage()
  //     ];
  //   }
  // }

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
}
