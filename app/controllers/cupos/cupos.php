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
}
