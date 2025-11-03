<?php
// En app/models/models_periodos.php

require_once(__DIR__ . '/../config.php'); // Asegúrate que esta ruta a config.php sea correcta

class PeriodosModel {
    private $pdo;

    public function __construct() {
        // ¡ESTO ES CRÍTICO Y CASI SIEMPRE FALTA!
        // Le dice a PHP que use la variable $pdo definida en config.php.
        global $pdo; 
        
        // Asignamos la conexión global a la variable interna del modelo.
        $this->pdo = $pdo; 
        
        // Si el valor de $this->pdo sigue siendo null aquí, es que config.php falló al conectar.
    }
    
    public function getListadoPeriodos() {
        $sql = "SELECT * FROM periodos ORDER BY fecha_inicio DESC";
        
        // Si $this->pdo es null, la siguiente línea falla (Fatal Error)
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ... el resto de tus funciones

    public function crearPeriodo($nombre, $inicio, $fin) {
        $sql = "INSERT INTO periodos (descripcion_periodo, fecha_ini, fecha_fin, estatus) 
                VALUES (:nombre, :inicio, :fin, 0)"; 
        $stmt = $this->pdo->prepare($sql);
        try {
            return $stmt->execute([
                'nombre' => $nombre,
                'inicio' => $inicio,
                'fin' => $fin
            ]);
        } catch (PDOException $e) {
            error_log("Error al crear periodo: " . $e->getMessage());
            return false;
        }
    }

    public function asignarPeriodo($id_periodo) {
        try {
            $this->pdo->beginTransaction();
            
            $sql_desactivar = "UPDATE periodos SET estatus = 0, asignacion_manual = 0 WHERE estatus = 1";
            $this->pdo->exec($sql_desactivar);
            
            $sql_activar = "UPDATE periodos SET estatus = 1, asignacion_manual = 1, actualizacion = NOW() WHERE id_periodo = :id_periodo";
            $stmt_activar = $this->pdo->prepare($sql_activar);
            $stmt_activar->execute(['id_periodo' => $id_periodo]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al asignar periodo: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPeriodoActivo() {
        $sql = "SELECT id_periodo, descripcion_periodo FROM periodos WHERE estatus = 1 LIMIT 1";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}