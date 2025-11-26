<?php
class DashboardController
{
  private $conn;

  public function __construct($conn)
  {
    $this->conn = $conn;
  }

  /**
   * Obtiene el periodo activo
   */
  private function obtenerPeriodoActivo()
  {
    try {
      $sql = "SELECT id_periodo, descripcion_periodo, fecha_ini, fecha_fin 
                    FROM periodos 
                    WHERE estatus = 1 
                    ORDER BY fecha_ini DESC 
                    LIMIT 1";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $periodo = $stmt->fetch(PDO::FETCH_ASSOC);

      return $periodo;
    } catch (PDOException $e) {
      throw new Exception('Error al obtener periodo activo: ' . $e->getMessage());
    }
  }

  /**
   * Obtiene estadísticas generales del sistema para el periodo activo
   */
  public function obtenerEstadisticasGenerales()
  {
    try {
      $periodo = $this->obtenerPeriodoActivo();

      if (!$periodo) {
        return [
          'success' => false,
          'message' => 'No hay periodo activo configurado'
        ];
      }

      // Total de estudiantes (únicos en el periodo activo)
      $sql_estudiantes = "SELECT COUNT(DISTINCT i.id_estudiante) as total 
                               FROM inscripciones i
                               WHERE i.estatus = 1 
                               AND i.id_periodo = ?";
      $stmt_estudiantes = $this->conn->prepare($sql_estudiantes);
      $stmt_estudiantes->execute([$periodo['id_periodo']]);
      $total_estudiantes = $stmt_estudiantes->fetch(PDO::FETCH_ASSOC)['total'];

      // Total de docentes
      $sql_docentes = "SELECT COUNT(*) as total FROM docentes WHERE estatus = 1";
      $stmt_docentes = $this->conn->prepare($sql_docentes);
      $stmt_docentes->execute();
      $total_docentes = $stmt_docentes->fetch(PDO::FETCH_ASSOC)['total'];

      // Total de representantes
      $sql_representantes = "SELECT COUNT(*) as total FROM representantes WHERE estatus = 1";
      $stmt_representantes = $this->conn->prepare($sql_representantes);
      $stmt_representantes->execute();
      $total_representantes = $stmt_representantes->fetch(PDO::FETCH_ASSOC)['total'];

      // Total de inscripciones en el periodo activo
      $sql_inscripciones_periodo = "SELECT COUNT(*) as total 
                                         FROM inscripciones 
                                         WHERE estatus = 1 
                                         AND id_periodo = ?";
      $stmt_inscripciones_periodo = $this->conn->prepare($sql_inscripciones_periodo);
      $stmt_inscripciones_periodo->execute([$periodo['id_periodo']]);
      $inscripciones_periodo = $stmt_inscripciones_periodo->fetch(PDO::FETCH_ASSOC)['total'];

      return [
        'success' => true,
        'data' => [
          'total_estudiantes' => $total_estudiantes,
          'total_docentes' => $total_docentes,
          'total_representantes' => $total_representantes,
          'inscripciones_periodo' => $inscripciones_periodo,
          'periodo_activo' => $periodo['descripcion_periodo']
        ]
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        'message' => 'Error al obtener estadísticas: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Obtiene inscripciones por mes del periodo activo
   */
  public function obtenerInscripcionesPorMes($mes = null, $anio = null)
  {
    try {
      $periodo = $this->obtenerPeriodoActivo();

      if (!$periodo) {
        return [
          'success' => false,
          'message' => 'No hay periodo activo configurado'
        ];
      }

      // Si no se especifica mes y año, usar el mes actual
      if ($mes === null || $anio === null) {
        $mes = date('n'); // Mes sin ceros iniciales (1-12)
        $anio = date('Y');
      }

      // Obtener inscripciones del mes específico en el periodo activo
      $sql = "
                SELECT 
                    DATE(fecha_inscripcion) as fecha,
                    COUNT(*) as cantidad,
                    DAY(fecha_inscripcion) as dia_mes
                FROM inscripciones 
                WHERE estatus = 1 
                AND id_periodo = ?
                AND MONTH(fecha_inscripcion) = ?
                AND YEAR(fecha_inscripcion) = ?
                GROUP BY DATE(fecha_inscripcion), DAY(fecha_inscripcion)
                ORDER BY fecha_inscripcion
            ";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$periodo['id_periodo'], $mes, $anio]);
      $inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Obtener número de días en el mes
      $dias_en_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

      // Nombres de los días de la semana en español
      $dias_semana = [
        'Monday' => 'Lun',
        'Tuesday' => 'Mar',
        'Wednesday' => 'Mié',
        'Thursday' => 'Jue',
        'Friday' => 'Vie',
        'Saturday' => 'Sáb',
        'Sunday' => 'Dom'
      ];

      $resultado = [];
      $total_inscripciones = 0;

      // Crear array con todos los días del mes
      for ($dia = 1; $dia <= $dias_en_mes; $dia++) {
        $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);
        $nombre_dia_ingles = date('l', strtotime($fecha));
        $nombre_dia_espanol = $dias_semana[$nombre_dia_ingles];

        // Buscar si hay inscripciones para esta fecha
        $inscripcion_dia = array_filter($inscripciones, function ($item) use ($fecha) {
          return $item['fecha'] == $fecha;
        });

        $cantidad = 0;
        if (!empty($inscripcion_dia)) {
          $inscripcion_dia = reset($inscripcion_dia);
          $cantidad = $inscripcion_dia['cantidad'];
          $total_inscripciones += $cantidad;
        }

        $resultado[] = [
          'fecha' => $fecha,
          'dia' => $dia,
          'dia_semana' => $nombre_dia_espanol,
          'cantidad' => $cantidad,
          'fecha_formateada' => date('d/m', strtotime($fecha))
        ];
      }

      // Nombres de los meses en español
      $meses_espanol = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
      ];

      return [
        'success' => true,
        'data' => [
          'inscripciones' => $resultado,
          'mes' => $mes,
          'mes_nombre' => $meses_espanol[$mes],
          'anio' => $anio,
          'total_mes' => $total_inscripciones,
          'dias_en_mes' => $dias_en_mes,
          'periodo_activo' => $periodo['descripcion_periodo']
        ]
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        'message' => 'Error al obtener inscripciones por mes: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Obtiene meses disponibles con inscripciones en el periodo activo
   */
  public function obtenerMesesDisponibles()
  {
    try {
      $periodo = $this->obtenerPeriodoActivo();

      if (!$periodo) {
        return [
          'success' => false,
          'message' => 'No hay periodo activo configurado'
        ];
      }

      $sql = "
                SELECT 
                    YEAR(fecha_inscripcion) as anio,
                    MONTH(fecha_inscripcion) as mes,
                    COUNT(*) as total_inscripciones
                FROM inscripciones 
                WHERE estatus = 1
                AND id_periodo = ?
                GROUP BY YEAR(fecha_inscripcion), MONTH(fecha_inscripcion)
                ORDER BY anio DESC, mes DESC
            ";

      $stmt = $this->conn->prepare($sql);
      $stmt->execute([$periodo['id_periodo']]);
      $meses = $stmt->fetchAll(PDO::FETCH_ASSOC);

      // Agregar nombres de meses
      $meses_espanol = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
      ];

      foreach ($meses as &$mes) {
        $mes['mes_nombre'] = $meses_espanol[$mes['mes']];
      }

      return [
        'success' => true,
        'meses' => $meses,
        'periodo_activo' => $periodo['descripcion_periodo']
      ];
    } catch (PDOException $e) {
      return [
        'success' => false,
        'message' => 'Error al obtener meses disponibles: ' . $e->getMessage()
      ];
    }
  }
}
