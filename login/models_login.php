<?php
require_once('../app/conexion.php');

class LoginModel {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Conexion())->getConexion();
    }

    /**
     * Busca un usuario por su nombre de usuario.
     * SIMPLIFICADO: Solo consulta 'usuarios' y 'roles'.
     */
    public function getUsuarioByUsername($usuario) {
        try {
            // Query simplificada: quitamos el JOIN a 'personas'
            $sql = "SELECT u.id_usuario, u.usuario, u.contrasena, u.id_rol, u.estatus,
                           r.nom_rol
                    FROM usuarios u
                    INNER JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.usuario = ? AND u.estatus = 1"; // Solo usuarios activos

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario]);
            
            return $stmt->fetch();

        } catch (PDOException $e) {
            // Manejar el error
            error_log("Error en LoginModel::getUsuarioByUsername: " . $e->getMessage());
            return false;
        }
    }
}
?>