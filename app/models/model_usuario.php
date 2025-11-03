<?php

// El nombre de la clase ahora coincide con el archivo
class ModelUsuario {

    /**
     * Obtiene un usuario por su nombre de usuario (columna 'usuario')
     * Incluye un JOIN con roles para traer el nombre del rol.
     */
    public function obtenerUsuarioPorUsuario($pdo, $usuario) {
        try {
            $sql = "SELECT u.*, r.nom_rol 
                    FROM usuarios u
                    JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.usuario = ?";
                    
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario]);
            
            return $stmt->fetch(); 

        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::obtenerUsuarioPorUsuario: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene todos los usuarios con su rol
     */
    public function obtenerTodos($pdo) {
         try {
            $sql = "SELECT u.id_usuario, u.nom_usuario, u.usuario, r.nom_rol, u.estatus, u.creacion, u.actualizacion 
                    FROM usuarios u 
                    JOIN roles r ON u.id_rol = r.id_rol
                    ORDER BY u.id_usuario DESC";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verifica si un 'usuario' (cedula) ya existe
     */
    public function verificarUsuarioExiste($pdo, $usuario) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::verificarUsuarioExiste: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea un nuevo usuario
     */
    public function crearUsuario($pdo, $nombre, $usuario, $idRol, $contrasenaHash) {
        try {
            $sql = "INSERT INTO usuarios (nom_usuario, usuario, id_rol, contrasena, estatus, creacion, actualizacion) 
                    VALUES (?, ?, ?, ?, 1, CURRENT_TIMESTAMP, '')"; // Estatus 1 = Activo
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nombre, $usuario, $idRol, $contrasenaHash]);
        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::crearUsuario: " . $e->getMessage());
            return false;
        }
    }
    
    public function cambiarEstatusUsuario($pdo, $id_usuario, $nuevoEstatus) {
        try {
            $sql = "UPDATE usuarios SET estatus = ?, actualizacion = CURRENT_TIMESTAMP WHERE id_usuario = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$nuevoEstatus, $id_usuario]);
        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::cambiarEstatusUsuario: " . $e->getMessage());
            return false;
        }
    }

    public function resetContrasenaUsuario($pdo, $id_usuario, $contrasenaHash) {
        try {
            $sql = "UPDATE usuarios SET contrasena = ?, actualizacion = CURRENT_TIMESTAMP WHERE id_usuario = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$contrasenaHash, $id_usuario]);
        } catch (PDOException $e) {
            error_log("Error en ModelUsuario::resetContrasenaUsuario: " . $e->getMessage());
            return false;
        }
    }

    // (Aquí irán las funciones de Actualizar)

}
?>