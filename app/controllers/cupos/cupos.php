<?php
class CuposController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  /**
   * Obtiene la capacidad total y estudiantes inscritos para un id_nivel_seccion específico
   * NUEVA VERSIÓN - usa id_nivel_seccion directamente
   */

  public function obtenerTodosLosNiveles()
  {
    try {
      $sql = "
            SELECT 
                id_nivel,
                num_nivel,
                nom_nivel
            FROM niveles 
            WHERE estatus = 1
            ORDER BY num_nivel
        ";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
      $niveles = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return [
        'success' => true,
        'niveles' => $niveles
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerTodosLosNiveles: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al obtener niveles',
        'niveles' => []
      ];
    }
  }
  public function obtenerDisponibilidad($id_nivel_seccion, $id_periodo)
  {
    try {
      // Primero, obtener la capacidad del nivel_seccion
      $sql_nivel_seccion = "
                SELECT ns.id_nivel_seccion, ns.capacidad, 
                       n.nom_nivel, s.nom_seccion
                FROM niveles_secciones ns
                INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                WHERE ns.id_nivel_seccion = ? AND ns.estatus = 1
            ";
      $stmt_ns = $this->pdo->prepare($sql_nivel_seccion);
      $stmt_ns->execute([$id_nivel_seccion]);
      $nivel_seccion = $stmt_ns->fetch(PDO::FETCH_ASSOC);

      if (!$nivel_seccion) {
        return [
          'success' => false,
          'message' => 'No se encontró la sección especificada',
          'disponible' => false
        ];
      }

      $capacidad_total = $nivel_seccion['capacidad'];
      $nom_nivel = $nivel_seccion['nom_nivel'];
      $nom_seccion = $nivel_seccion['nom_seccion'];

      // Contar estudiantes inscritos en ese nivel-sección para el período
      $sql_inscritos = "
                SELECT COUNT(*) as total_inscritos
                FROM inscripciones i
                WHERE i.id_nivel_seccion = ? 
                AND i.id_periodo = ? 
                AND i.estatus = 1
            ";
      $stmt_ins = $this->pdo->prepare($sql_inscritos);
      $stmt_ins->execute([$id_nivel_seccion, $id_periodo]);
      $resultado = $stmt_ins->fetch(PDO::FETCH_ASSOC);

      $total_inscritos = $resultado['total_inscritos'];
      $cupos_disponibles = $capacidad_total - $total_inscritos;
      $disponible = $cupos_disponibles > 0;

      return [
        'success' => true,
        'disponible' => $disponible,
        'capacidad_total' => $capacidad_total,
        'total_inscritos' => $total_inscritos,
        'cupos_disponibles' => $cupos_disponibles,
        'id_nivel_seccion' => $id_nivel_seccion,
        'nom_nivel' => $nom_nivel,
        'nom_seccion' => $nom_seccion,
        'mensaje' => $disponible
          ? "✅ Hay {$cupos_disponibles} cupos disponibles de {$capacidad_total} en {$nom_nivel} - {$nom_seccion}"
          : "❌ No hay cupos disponibles en {$nom_nivel} - {$nom_seccion}. Capacidad: {$capacidad_total}, Inscritos: {$total_inscritos}"
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerDisponibilidad: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al verificar disponibilidad de cupos',
        'disponible' => false
      ];
    }
  }

  /**
   * MÉTODO COMPATIBILIDAD - Para mantener compatibilidad con código existente
   * que aún usa id_nivel e id_seccion por separado
   */
  public function obtenerDisponibilidadPorSeparado($id_nivel, $id_seccion, $id_periodo)
  {
    try {
      // Primero, obtener el id_nivel_seccion
      $sql_nivel_seccion = "
                SELECT id_nivel_seccion, capacidad 
                FROM niveles_secciones 
                WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1
            ";
      $stmt_ns = $this->pdo->prepare($sql_nivel_seccion);
      $stmt_ns->execute([$id_nivel, $id_seccion]);
      $nivel_seccion = $stmt_ns->fetch(PDO::FETCH_ASSOC);

      if (!$nivel_seccion) {
        return [
          'success' => false,
          'message' => 'No se encontró la combinación nivel-sección especificada',
          'disponible' => false
        ];
      }

      $id_nivel_seccion = $nivel_seccion['id_nivel_seccion'];

      // Usar el nuevo método con id_nivel_seccion
      return $this->obtenerDisponibilidad($id_nivel_seccion, $id_periodo);
    } catch (PDOException $e) {
      error_log("Error en obtenerDisponibilidadPorSeparado: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al verificar disponibilidad de cupos',
        'disponible' => false
      ];
    }
  }

  /**
   * Verifica si hay cupos disponibles (método rápido) - NUEVA VERSIÓN
   */
  public function hayCuposDisponibles($id_nivel_seccion, $id_periodo)
  {
    $disponibilidad = $this->obtenerDisponibilidad($id_nivel_seccion, $id_periodo);
    return $disponibilidad['success'] && $disponibilidad['disponible'];
  }

  /**
   * Verifica si hay cupos disponibles (método rápido) - VERSIÓN COMPATIBILIDAD
   */
  public function hayCuposDisponiblesPorSeparado($id_nivel, $id_seccion, $id_periodo)
  {
    $disponibilidad = $this->obtenerDisponibilidadPorSeparado($id_nivel, $id_seccion, $id_periodo);
    return $disponibilidad['success'] && $disponibilidad['disponible'];
  }

  /**
   * Obtiene información detallada de todas las secciones para un nivel
   * ACTUALIZADO para usar el nuevo sistema
   */
  public function obtenerDisponibilidadPorNivel($id_nivel, $id_periodo)
  {
    try {
      $sql = "
                SELECT 
                    ns.id_nivel_seccion,
                    n.nom_nivel,
                    s.nom_seccion,
                    ns.capacidad,
                    (SELECT COUNT(*) FROM inscripciones i 
                     WHERE i.id_nivel_seccion = ns.id_nivel_seccion 
                     AND i.id_periodo = ? AND i.estatus = 1) as inscritos,
                    (ns.capacidad - (SELECT COUNT(*) FROM inscripciones i 
                     WHERE i.id_nivel_seccion = ns.id_nivel_seccion 
                     AND i.id_periodo = ? AND i.estatus = 1)) as disponibles
                FROM niveles_secciones ns
                INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                WHERE ns.id_nivel = ? AND ns.estatus = 1
                ORDER BY s.nom_seccion
            ";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_periodo, $id_periodo, $id_nivel]);
      $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return [
        'success' => true,
        'secciones' => $secciones
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerDisponibilidadPorNivel: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al obtener disponibilidad por nivel'
      ];
    }
  }
  public function obtenerSeccionesPorNivel($id_nivel)
  {
    try {
      $sql = "
            SELECT 
                ns.id_nivel_seccion,
                s.id_seccion,
                s.nom_seccion,
                ns.capacidad,
                n.nom_nivel
            FROM niveles_secciones ns
            INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
            INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
            WHERE ns.id_nivel = ? AND ns.estatus = 1 AND s.estatus = 1
            ORDER BY s.nom_seccion
        ";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_nivel]);
      $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return [
        'success' => true,
        'secciones' => $secciones
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerSeccionesPorNivel: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al obtener secciones por nivel',
        'secciones' => []
      ];
    }
  }

  /**
   * Obtiene los niveles disponibles para reinscripción basados en el último nivel cursado
   * ORDEN: Primero todos los Grados, luego todos los Años, ordenados por número ordinal
   */
  public function obtenerNivelesReinscripcion($id_estudiante)
  {
    try {
      // 1. Obtener el último nivel cursado del estudiante
      $sql_ultimo_nivel = "
            SELECT 
                n.id_nivel,
                n.num_nivel,
                n.nom_nivel,
                i.id_inscripcion,
                i.fecha_inscripcion
            FROM inscripciones i
            INNER JOIN niveles_secciones ns ON i.id_nivel_seccion = ns.id_nivel_seccion
            INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
            WHERE i.id_estudiante = ? 
            AND i.estatus = 1
            ORDER BY i.fecha_inscripcion DESC, i.id_inscripcion DESC
            LIMIT 1
        ";

      $stmt_ultimo = $this->pdo->prepare($sql_ultimo_nivel);
      $stmt_ultimo->execute([$id_estudiante]);
      $ultimo_nivel = $stmt_ultimo->fetch(PDO::FETCH_ASSOC);

      if (!$ultimo_nivel) {
        return [
          'success' => false,
          'message' => 'No se encontró historial académico del estudiante',
          'niveles' => []
        ];
      }

      $ultimo_num_nivel = $ultimo_nivel['num_nivel'];
      $ultimo_nom_nivel = $ultimo_nivel['nom_nivel'];

      // 2. Obtener todos los niveles ordenados por tipo (Grado/Año) y luego por número ordinal
      $sql_todos_niveles = "
            SELECT 
                id_nivel,
                num_nivel,
                nom_nivel,
                -- Extraer el tipo de nivel (Grado/Año)
                CASE 
                    WHEN nom_nivel LIKE '%Grado%' THEN 1  -- Grados primero
                    WHEN nom_nivel LIKE '%Año%' THEN 2    -- Años después
                    ELSE 3                                -- Otros
                END as tipo_nivel
            FROM niveles 
            WHERE estatus = 1
            ORDER BY 
                tipo_nivel,  -- Primero Grados (1), luego Años (2)
                num_nivel    -- Luego por número ordinal
        ";

      $stmt_todos = $this->pdo->prepare($sql_todos_niveles);
      $stmt_todos->execute();
      $todos_niveles = $stmt_todos->fetchAll(PDO::FETCH_ASSOC);

      // 3. Encontrar la posición del último nivel en el array
      $posicion_ultimo = -1;
      foreach ($todos_niveles as $index => $nivel) {
        if ($nivel['id_nivel'] == $ultimo_nivel['id_nivel']) {
          $posicion_ultimo = $index;
          break;
        }
      }

      if ($posicion_ultimo === -1) {
        return [
          'success' => false,
          'message' => 'No se pudo determinar la posición del último nivel',
          'niveles' => []
        ];
      }

      // 4. Obtener los niveles disponibles (último + 2 siguientes)
      $niveles_disponibles = [];

      // Siempre incluir el último nivel cursado
      $niveles_disponibles[] = $todos_niveles[$posicion_ultimo];

      // Agregar los 2 niveles siguientes si existen
      for ($i = 1; $i <= 2; $i++) {
        $siguiente_pos = $posicion_ultimo + $i;
        if (isset($todos_niveles[$siguiente_pos])) {
          $niveles_disponibles[] = $todos_niveles[$siguiente_pos];
        }
      }

      return [
        'success' => true,
        'ultimo_nivel' => $ultimo_nivel,
        'niveles' => $niveles_disponibles,
        'mensaje' => "Último nivel cursado: {$ultimo_nom_nivel}",
        'debug' => [ // Para debugging
          'total_niveles' => count($todos_niveles),
          'posicion_ultimo' => $posicion_ultimo,
          'orden_niveles' => $todos_niveles
        ]
      ];
    } catch (PDOException $e) {
      error_log("Error en obtenerNivelesReinscripcion: " . $e->getMessage());
      return [
        'success' => false,
        'message' => 'Error al obtener niveles para reinscripción',
        'niveles' => []
      ];
    }
  }
}
