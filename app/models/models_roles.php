<?php
// Incluir la configuración global que contiene ROOT_PATH y ya incluye conexion.php 

class RolModel {
    private $pdo;

    public function __construct() {
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    /**
     * Obtiene el listado de todos los roles (ID y Nombre).
     * Esta función es usada por la vista de creación/edición de usuarios.
     * @return array Lista de roles.
     */
    public function getListadoRoles() {
        try {
            // Asumiendo que la tabla de roles se llama 'roles'
            $sql = "SELECT id_rol, nom_rol FROM roles ORDER BY nom_rol ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener listado de roles: " . $e->getMessage());
            // En caso de error, devuelve un array vacío
            return [];
        }
    }
    
    /**
     * Obtiene los datos de un rol específico por su ID.
     */
    public function getRolPorId($id_rol) {
        try {
            $sql = "SELECT id_rol, nom_rol, descripcion FROM roles WHERE id_rol = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id_rol]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener rol por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo rol.
     */
    public function crearRol($nom_rol, $descripcion) {
        try {
            $sql = "INSERT INTO roles (nom_rol, descripcion) VALUES (:nombre, :descripcion)";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute([
                'nombre' => $nom_rol,
                'descripcion' => $descripcion
            ]);
        } catch (PDOException $e) {
            error_log("Error al crear rol: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Actualiza un rol existente.
     */
    public function actualizarRol($id_rol, $nom_rol, $descripcion) {
        try {
            $sql = "UPDATE roles 
                    SET nom_rol = :nombre, descripcion = :descripcion
                    WHERE id_rol = :id";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute([
                'nombre' => $nom_rol,
                'descripcion' => $descripcion,
                'id' => $id_rol
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar rol: " . $e->getMessage());
            return false;
        }
    }

    public function cambiarEstatusRol($id_rol, $estatus) {
        try {
            // Reemplaza la lógica de eliminación con una actualización de estatus
            $sql = "UPDATE roles SET estatus = :estatus WHERE id_rol = :id";
            $stmt = $this->pdo->prepare($sql);
            
            if ($stmt->execute(['estatus' => $estatus, 'id' => $id_rol])) {
                 return $stmt->rowCount() > 0; // Solo retorna TRUE si la fila fue actualizada
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al cambiar estatus del rol: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina un rol.
     */
    /* public function eliminarRol($id_rol) {
        try {
            $sql = "DELETE FROM roles WHERE id_rol = :id";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute(['id' => $id_rol]);
        } catch (PDOException $e) {
            error_log("Error al eliminar rol: " . $e->getMessage());
            return false; // Fallará si hay restricción de clave foránea (usuarios asociados)
        }
    } */
}