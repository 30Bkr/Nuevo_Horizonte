<?php
require_once(__DIR__ . '/../config.php'); 

class NivelesModel {
    private $pdo;

    public function __construct() {
        global $pdo; 
        $this->pdo = $pdo;
    }
    
    public function getListadoNiveles() {
        $sql = "SELECT id_nivel, nom_nivel, num_nivel, tipo_nivel, estatus 
                FROM niveles 
                ORDER BY tipo_nivel DESC, num_nivel ASC"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearNivel($nombre, $numero, $tipo_nivel, $estatus) {
        if (!in_array($tipo_nivel, ['grado', 'año'])) {
            return false; 
        }

        $sql = "INSERT INTO niveles (nom_nivel, num_nivel, tipo_nivel, estatus) 
                VALUES (:nombre, :numero, :tipo_nivel, :estatus)";
        $stmt = $this->pdo->prepare($sql);
        try {
            return $stmt->execute([
                'nombre' => $nombre,
                'numero' => $numero,
                'tipo_nivel' => $tipo_nivel, 
                'estatus' => $estatus
            ]);
        } catch (PDOException $e) {
            error_log("Error al crear nivel: " . $e->getMessage());
            return false; 
        }
    }

    public function actualizarEstatusNivel($id_nivel, $estatus) {
        $sql = "UPDATE niveles SET estatus = :estatus, actualizacion = NOW() WHERE id_nivel = :id_nivel";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'estatus' => $estatus,
            'id_nivel' => $id_nivel
        ]);
    }
    
    public function getNivelesActivos() {
        $sql = "SELECT id_nivel, nom_nivel, num_nivel, tipo_nivel FROM niveles WHERE estatus = 1 ORDER BY num_nivel ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}