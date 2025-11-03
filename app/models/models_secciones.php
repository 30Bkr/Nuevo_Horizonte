<?php
require_once(__DIR__ . '/../config.php'); 

class SeccionesModel {
    private $pdo;

    public function __construct() {
        global $pdo; 
        $this->pdo = $pdo;
    }
    
    public function getListadoSecciones() {
        $sql = "SELECT id_seccion, nom_seccion, turno, cupo, estatus 
                FROM secciones 
                ORDER BY nom_seccion ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $secciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($secciones as &$seccion) {
            $sql_niveles = "SELECT n.nom_nivel, n.num_nivel, n.tipo_nivel 
                            FROM niveles_secciones ns
                            JOIN niveles n ON ns.id_nivel = n.id_nivel
                            WHERE ns.id_seccion = :id_seccion";
            $stmt_niveles = $this->pdo->prepare($sql_niveles);
            $stmt_niveles->execute(['id_seccion' => $seccion['id_seccion']]);
            $seccion['niveles_asignados'] = $stmt_niveles->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($seccion);

        return $secciones;
    }

    public function crearSeccion($nombre, $turno, $cupo, $niveles_ids) {
        try {
            $this->pdo->beginTransaction();

            // 1. Crear la sección base
            $sql_seccion = "INSERT INTO secciones (nom_seccion, turno, cupo, estatus) 
                            VALUES (:nombre, :turno, :cupo, 1)";
            $stmt_seccion = $this->pdo->prepare($sql_seccion);
            $stmt_seccion->execute([
                'nombre' => $nombre,
                'turno' => $turno,
                'cupo' => $cupo
            ]);

            $id_seccion = $this->pdo->lastInsertId();

            // 2. Asignar la sección a los niveles seleccionados (niveles_secciones)
            if (!empty($niveles_ids) && is_array($niveles_ids)) {
                $sql_asignacion = "INSERT INTO niveles_secciones (id_nivel, id_seccion, capacidad) 
                                   VALUES (:id_nivel, :id_seccion, :cupo)";
                $stmt_asignacion = $this->pdo->prepare($sql_asignacion);

                foreach ($niveles_ids as $id_nivel) {
                    $stmt_asignacion->execute([
                        'id_nivel' => $id_nivel,
                        'id_seccion' => $id_seccion,
                        'cupo' => $cupo
                    ]);
                }
            }
            
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al crear sección: " . $e->getMessage());
            return false;
        }
    }
    
    public function actualizarEstatusSeccion($id_seccion, $estatus) {
        $sql = "UPDATE secciones SET estatus = :estatus, actualizacion = NOW() WHERE id_seccion = :id_seccion";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'estatus' => $estatus,
            'id_seccion' => $id_seccion
        ]);
    }
}