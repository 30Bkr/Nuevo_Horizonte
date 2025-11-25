<?php
// app/models/Grado.php
class Grado {
    private $conn;
    private $table_name = "niveles_secciones";

    public $id_nivel_seccion;
    public $id_nivel;
    public $id_seccion;
    public $capacidad;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear nuevo grado/sección
    public function crear() {
        $query = "INSERT INTO " . $this->table_name . " 
                 (id_nivel, id_seccion, capacidad) 
                 VALUES (:id_nivel, :id_seccion, :capacidad)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpiar datos
        $this->id_nivel = htmlspecialchars(strip_tags($this->id_nivel));
        $this->id_seccion = htmlspecialchars(strip_tags($this->id_seccion));
        $this->capacidad = htmlspecialchars(strip_tags($this->capacidad));
        
        // Bind parameters
        $stmt->bindParam(":id_nivel", $this->id_nivel);
        $stmt->bindParam(":id_seccion", $this->id_seccion);
        $stmt->bindParam(":capacidad", $this->capacidad);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Listar todos los grados con conteo de alumnos (incluye inactivos)
    public function listarGradosConAlumnos() {
        $query = "SELECT 
                    ns.id_nivel_seccion,
                    n.nom_nivel as nombre_grado,
                    s.nom_seccion as seccion,
                    ns.capacidad,
                    ns.estatus,
                    COUNT(i.id_inscripcion) as total_alumnos
                  FROM " . $this->table_name . " ns
                  INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                  INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                  LEFT JOIN inscripciones i ON ns.id_nivel_seccion = i.id_nivel_seccion 
                    AND i.estatus = 1
                  WHERE n.estatus = 1 AND s.estatus = 1  -- Solo niveles y secciones activos
                  GROUP BY ns.id_nivel_seccion
                  ORDER BY n.num_nivel, s.nom_seccion";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Obtener todas las secciones disponibles
    public function obtenerSecciones() {
        $query = "SELECT * FROM secciones WHERE estatus = 1 ORDER BY nom_seccion";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener todos los niveles disponibles
    public function obtenerNiveles() {
        $query = "SELECT * FROM niveles WHERE estatus = 1 ORDER BY num_nivel";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Verificar si ya existe la combinación nivel-sección
    public function existeCombinacion($id_nivel, $id_seccion) {
        $query = "SELECT id_nivel_seccion FROM " . $this->table_name . " 
                  WHERE id_nivel = ? AND id_seccion = ? AND estatus = 1 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id_nivel);
        $stmt->bindParam(2, $id_seccion);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // Obtener grado por ID (incluye inactivos)
    public function obtenerPorId($id) {
        $query = "SELECT ns.*, n.nom_nivel, s.nom_seccion 
                  FROM " . $this->table_name . " ns
                  INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                  INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                  WHERE ns.id_nivel_seccion = ? 
                  LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_nivel_seccion = $row['id_nivel_seccion'];
            $this->id_nivel = $row['id_nivel'];
            $this->id_seccion = $row['id_seccion'];
            $this->capacidad = $row['capacidad'];
            return true;
        }
        return false;
    }

    // Actualizar grado
    public function actualizar() {
        // Validar que la capacidad no sea menor a los estudiantes registrados
        $query_check = "SELECT COUNT(*) as total_estudiantes 
                       FROM inscripciones 
                       WHERE id_nivel_seccion = ? AND estatus = 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(1, $this->id_nivel_seccion);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        $total_estudiantes = $result['total_estudiantes'];
        
        if ($this->capacidad < $total_estudiantes) {
            return false; // No se puede actualizar si la capacidad es menor a los estudiantes registrados
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET capacidad = :capacidad
                  WHERE id_nivel_seccion = :id_nivel_seccion";
        
        $stmt = $this->conn->prepare($query);
        
        $this->capacidad = htmlspecialchars(strip_tags($this->capacidad));
        $this->id_nivel_seccion = htmlspecialchars(strip_tags($this->id_nivel_seccion));
        
        $stmt->bindParam(":capacidad", $this->capacidad);
        $stmt->bindParam(":id_nivel_seccion", $this->id_nivel_seccion);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar grado (cambiar estado) - MANTENIDO POR COMPATIBILIDAD
    public function eliminar() {
        // Verificar si hay estudiantes inscritos
        $query_check = "SELECT COUNT(*) as total FROM inscripciones 
                       WHERE id_nivel_seccion = ? AND estatus = 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(1, $this->id_nivel_seccion);
        $stmt_check->execute();
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($row['total'] > 0) {
            return false; // No se puede eliminar si hay estudiantes
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET estatus = 0 
                  WHERE id_nivel_seccion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_nivel_seccion);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ========== NUEVOS MÉTODOS AGREGADOS ==========

    /**
     * Obtener información específica de un grado/sección por ID
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return array Información del grado/sección
     */
    public function obtenerGradoPorId($id_nivel_seccion) {
        $query = "SELECT ns.id_nivel_seccion, n.nom_nivel as nombre_grado, 
                         s.nom_seccion as seccion, ns.capacidad
                  FROM " . $this->table_name . " ns
                  INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                  INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                  WHERE ns.id_nivel_seccion = :id_nivel_seccion 
                  AND ns.estatus = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener lista de estudiantes inscritos en un grado/sección específico
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return PDOStatement Lista de estudiantes
     */
  public function obtenerEstudiantesPorGrado($id_nivel_seccion) {
    $query = "SELECT 
                p.cedula,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.sexo,
                p.fecha_nac,
                i.fecha_inscripcion,
                rp.primer_nombre as rep_primer_nombre,
                rp.segundo_nombre as rep_segundo_nombre,
                rp.primer_apellido as rep_primer_apellido,
                rp.segundo_apellido as rep_segundo_apellido,
                par.parentesco, 
                CONCAT(rp.primer_nombre, ' ', rp.primer_apellido) as representante_nombre,
                rp.cedula as rep_cedula
              FROM inscripciones i
              INNER JOIN estudiantes e ON i.id_estudiante = e.id_estudiante
              INNER JOIN personas p ON e.id_persona = p.id_persona
              LEFT JOIN estudiantes_representantes er ON e.id_estudiante = er.id_estudiante
              LEFT JOIN representantes r ON er.id_representante = r.id_representante
              LEFT JOIN personas rp ON r.id_persona = rp.id_persona
              LEFT JOIN parentesco par ON er.id_parentesco = par.id_parentesco 
              WHERE i.id_nivel_seccion = :id_nivel_seccion 
              AND i.estatus = 1 
              AND e.estatus = 1
              ORDER BY p.primer_apellido, p.primer_nombre";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
    $stmt->execute();
    
    return $stmt;
}

    /**
     * Obtener estadísticas detalladas de un grado/sección
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return array Estadísticas del grado
     */
    public function obtenerEstadisticasGrado($id_nivel_seccion) {
        $query = "SELECT 
                    ns.capacidad,
                    COUNT(i.id_inscripcion) as total_estudiantes,
                    (ns.capacidad - COUNT(i.id_inscripcion)) as cupos_disponibles,
                    ROUND((COUNT(i.id_inscripcion) / ns.capacidad) * 100, 2) as porcentaje_ocupacion,
                    COUNT(CASE WHEN p.sexo = 'Masculino' THEN 1 END) as estudiantes_masculinos,
                    COUNT(CASE WHEN p.sexo = 'Femenino' THEN 1 END) as estudiantes_femeninos
                  FROM " . $this->table_name . " ns
                  LEFT JOIN inscripciones i ON ns.id_nivel_seccion = i.id_nivel_seccion AND i.estatus = 1
                  LEFT JOIN estudiantes e ON i.id_estudiante = e.id_estudiante AND e.estatus = 1
                  LEFT JOIN personas p ON e.id_persona = p.id_persona
                  WHERE ns.id_nivel_seccion = :id_nivel_seccion 
                  AND ns.estatus = 1
                  GROUP BY ns.id_nivel_seccion";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verificar si un grado tiene estudiantes inscritos
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return bool True si tiene estudiantes, False si no
     */
    public function tieneEstudiantes($id_nivel_seccion) {
        $query = "SELECT COUNT(*) as total 
                  FROM inscripciones 
                  WHERE id_nivel_seccion = :id_nivel_seccion 
                  AND estatus = 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Obtener todos los grados disponibles para listas desplegables
     * @return PDOStatement Lista de grados
     */
    public function obtenerGradosParaSelect() {
        $query = "SELECT ns.id_nivel_seccion, 
                         CONCAT(n.nom_nivel, ' - ', s.nom_seccion) as nombre_completo
                  FROM " . $this->table_name . " ns
                  INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                  INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                  WHERE ns.estatus = 1
                  ORDER BY n.num_nivel, s.nom_seccion";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // ========== NUEVOS MÉTODOS PARA HABILITAR/INHABILITAR ==========

    /**
     * Obtener el estado actual de un grado
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return bool True si está activo, False si está inactivo
     */
    public function obtenerEstadoGrado($id_nivel_seccion) {
        $query = "SELECT estatus FROM " . $this->table_name . " 
                  WHERE id_nivel_seccion = :id_nivel_seccion";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['estatus'] == 1;
    }

    /**
     * Habilitar un grado/sección
     * @return bool True si se habilitó correctamente
     */
    public function habilitar() {
        $query = "UPDATE " . $this->table_name . " 
                  SET estatus = 1 
                  WHERE id_nivel_seccion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_nivel_seccion);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Inhabilitar un grado/sección
     * @return bool True si se inhabilitó correctamente
     */
    public function inhabilitar() {
        // Verificar si hay estudiantes inscritos activos
        $query_check = "SELECT COUNT(*) as total FROM inscripciones 
                       WHERE id_nivel_seccion = ? AND estatus = 1";
        $stmt_check = $this->conn->prepare($query_check);
        $stmt_check->bindParam(1, $this->id_nivel_seccion);
        $stmt_check->execute();
        $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if ($row['total'] > 0) {
            // No se puede inhabilitar si hay estudiantes activos
            return false;
        }
        
        $query = "UPDATE " . $this->table_name . " 
                  SET estatus = 0 
                  WHERE id_nivel_seccion = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_nivel_seccion);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Obtener información completa de un grado incluyendo estado
     * @param int $id_nivel_seccion ID del nivel_sección
     * @return array Información completa del grado
     */
    public function obtenerGradoCompletoPorId($id_nivel_seccion) {
        $query = "SELECT ns.id_nivel_seccion, n.nom_nivel as nombre_grado, 
                         s.nom_seccion as seccion, ns.capacidad, ns.estatus
                  FROM " . $this->table_name . " ns
                  INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
                  INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
                  WHERE ns.id_nivel_seccion = :id_nivel_seccion";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_nivel_seccion", $id_nivel_seccion);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cambiar el estado de un grado (habilitar/inhabilitar)
     * @param int $id_nivel_seccion ID del nivel_sección
     * @param bool $nuevo_estado True para habilitar, False para inhabilitar
     * @return bool True si se cambió el estado correctamente
     */
    public function cambiarEstado($id_nivel_seccion, $nuevo_estado) {
        $this->id_nivel_seccion = $id_nivel_seccion;
        
        if ($nuevo_estado) {
            return $this->habilitar();
        } else {
            return $this->inhabilitar();
        }
    }
}
?>