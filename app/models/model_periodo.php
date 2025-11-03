<?php
class ModelPeriodo {

    /**
     * Obtiene todos los periodos.
     */
    public function obtenerTodos($pdo) {
        try {
            $sql = "SELECT id_periodo, nombre_periodo, fecha_inicio, fecha_fin, estatus 
                    FROM periodos 
                    ORDER BY fecha_inicio DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ModelPeriodo::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea un nuevo periodo con estatus INACTIVO por defecto.
     */
    public function crearPeriodo($pdo, $nombre, $fechaInicio, $fechaFin) {
        try {
            // Estatus por defecto es INACTIVO
            $sql = "INSERT INTO periodos (nombre_periodo, fecha_inicio, fecha_fin) 
                    VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nombre, $fechaInicio, $fechaFin]);
        } catch (PDOException $e) {
            error_log("Error en ModelPeriodo::crearPeriodo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Resetea todos los periodos a INACTIVO.
     */
    private function resetearPeriodos($pdo) {
        $sql = "UPDATE periodos SET estatus = 'INACTIVO'";
        return $pdo->exec($sql);
    }

    /**
     * Asigna un periodo como 'ACTIVO' (el periodo actual del sistema).
     */
    public function asignarPeriodo($pdo, $id_periodo) {
        try {
            $pdo->beginTransaction();

            // 1. Desactivar todos los periodos existentes
            $this->resetearPeriodos($pdo);

            // 2. Activar el periodo seleccionado
            $sql = "UPDATE periodos SET estatus = 'ACTIVO', actualizacion = CURRENT_TIMESTAMP WHERE id_periodo = ?";
            $stmt = $pdo->prepare($sql);
            $activado = $stmt->execute([$id_periodo]);
            
            if ($activado) {
                $pdo->commit();
                return true;
            }

            $pdo->rollBack();
            return false;

        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Error en ModelPeriodo::asignarPeriodo: " . $e->getMessage());
            return false;
        }
    }
}
?>