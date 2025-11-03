<?php
require_once(__DIR__ . '/../config.php');

class UsuarioModel {
    private $pdo;

    public function __construct() {
        // Inicializa la conexión PDO
        $conexion = new Conexion();
        $this->pdo = $conexion->getConexion();
    }

    /**
     * Obtiene el listado de todos los usuarios con su rol asociado.
     * Ya que no hay FK a 'personas', solo listamos los datos de 'usuarios' y 'roles'.
     */
    public function getListadoUsuarios() {
        try {
            $sql = "SELECT 
                        u.id_usuario, 
                        u.usuario, 
                        u.estatus, 
                        r.nom_rol
                    FROM usuarios u
                    JOIN roles r ON u.id_rol = r.id_rol
                    ORDER BY u.id_usuario DESC";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error al obtener el listado de usuarios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crea un nuevo usuario con una contraseña por defecto (123456).
     * @param array $datos_usuario Datos del usuario (usuario, id_rol)
     * @return bool Resultado de la operación
     */
    public function crearUsuario($datos_usuario) {
        
        try {
            $password_por_defecto = '12345678'; 
            $hashed_password = password_hash($password_por_defecto, PASSWORD_DEFAULT);

            // CORRECCIÓN: Cambiar 'password' por 'contrasena'
            $sql_usuario = "INSERT INTO usuarios (usuario, contrasena, id_rol, estatus) 
                            VALUES (:usuario, :password_hashed, :id_rol, 1)";
            $stmt_usuario = $this->pdo->prepare($sql_usuario);

            $stmt_usuario->execute([
                'usuario' => $datos_usuario['usuario'],
                'password_hashed' => $hashed_password, // El valor se llama :password_hashed, pero apunta a la columna 'contrasena'
                'id_rol' => $datos_usuario['id_rol']
            ]);

            return true;
            
        } catch (PDOException $e) {
            // No olvides quitar la línea de 'die()' que usaste para depurar.
            // Restablece el manejo de errores al log o a una alerta.
            error_log("Error PDO al crear usuario: " . $e->getMessage()); 
            return false;
        }
    }
    
    /**
     * Obtiene todos los roles disponibles para el formulario.
     */
    public function getRoles() {
        try {
            $sql = "SELECT id_rol, nom_rol FROM roles ORDER BY nom_rol ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Resetea la contraseña de un usuario a la contraseña por defecto (123456).
     */
    public function resetearPassword($id_usuario) {
        try {
            $password_por_defecto = '123456'; 
            $hashed_password = password_hash($password_por_defecto, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET contrasena = :contrasena WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            
            // Verificamos que la ejecución fue exitosa Y que al menos una fila fue afectada.
            if ($stmt->execute(['contrasena' => $hashed_password, 'id' => $id_usuario])) {
                return $stmt->rowCount() > 0;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al resetear contraseña: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cambia el estatus (Activo/Inactivo) de un usuario.
     */
    public function cambiarEstatus($id_usuario, $estatus) {
        try {
            $sql = "UPDATE usuarios SET estatus = :estatus WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            
            // Verificamos que la ejecución fue exitosa Y que al menos una fila fue afectada.
            if ($stmt->execute(['estatus' => $estatus, 'id' => $id_usuario])) {
                 return $stmt->rowCount() > 0;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al cambiar estatus: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtiene los datos completos de un usuario específico por su ID. (Para editar)
     */
    public function getUsuarioPorId($id_usuario) {
        try {
            $sql = "SELECT u.id_usuario, u.usuario, u.estatus, u.id_rol, r.nom_rol 
                    FROM usuarios u
                    JOIN roles r ON u.id_rol = r.id_rol
                    WHERE u.id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id_usuario]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener usuario por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza el rol y el estatus de un usuario existente. (Para editar)
     */
    public function actualizarUsuario($id_usuario, $id_rol, $estatus) {
        try {
            $sql = "UPDATE usuarios 
                    SET id_rol = :id_rol, estatus = :estatus
                    WHERE id_usuario = :id_usuario";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute([
                'id_rol' => $id_rol,
                'estatus' => $estatus,
                'id_usuario' => $id_usuario
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar usuario: " . $e->getMessage());
            return false;
        }
    }
}