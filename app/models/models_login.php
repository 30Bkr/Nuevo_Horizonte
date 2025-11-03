<?php
require_once('../config.php');

class UsuarioModel {
    private $pdo;

    public function __construct() {
        $this->pdo = (new Conexion())->getConexion();
    }

    /**
     * Obtiene todos los roles activos para el formulario de selección.
     */
    public function getRolesActivos() {
        try {
            // No incluimos el rol "Administrador" (ID 1) para evitar creaciones accidentales
            $sql = "SELECT id_rol, nom_rol FROM roles WHERE estatus = 1 AND id_rol > 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error al obtener roles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verifica si un nombre de usuario ya existe.
     */
    public function existeUsuario($usuario) {
        try {
            $sql = "SELECT COUNT(*) FROM usuarios WHERE usuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$usuario]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error al verificar usuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Inserta un nuevo usuario con la contraseña predeterminada.
     */
    public function crearUsuario($usuario, $id_rol) {
        // La contraseña predeterminada es '12345678'
        $contrasena_default = '12345678';
        // ESENCIAL: Hasheamos la contraseña antes de guardarla
        $contrasena_hash = password_hash($contrasena_default, PASSWORD_DEFAULT);
        
        // El nuevo usuario se crea activo por defecto
        $estatus = 1; 

        try {
            // Nota: id_persona es una FK. Como aún no lo enlazamos, necesitamos un placeholder
            // Temporalmente, usaremos 1 para id_persona, asumiendo que ya hay un registro de persona
            // O, si la tabla `usuarios` permite NULL en `id_persona`, usamos NULL. 
            // Revisando tu DB, NO permite NULL. **DEBEMOS INSERTAR UNA PERSONA PRIMERO.**

            // RECOMENDACIÓN: Para que el sistema sea funcional AHORA, **insertemos una persona genérica**.
            // AÑADIRÉ EL CÓDIGO PARA INSERTAR UNA PERSONA BÁSICA PRIMERO.
            
            // 1. Insertar una persona (temporalmente con datos mínimos)
            // Esto es crucial porque la tabla `usuarios` tiene FK `id_persona` NOT NULL.
            // Para fines de prueba, **necesitas que la tabla `direcciones` tenga al menos 1 registro (id_direccion = 1)**
            // y que la tabla `parroquias`, `municipios`, `estados` tengan datos si la FK está activa.
            // ASUMO QUE YA TIENES UN REGISTRO EN LA TABLA `PERSONAS` CON ID = 1 para pruebas.
            
            // Si no tienes personas, este código NO funcionará. 
            // Para simplificar, asumiremos que insertamos un registro dummy en `personas`:
            
            // CÓDIGO PARA INSERTAR PERSONA DUMMY (Debe estar en una transacción):
            $this->pdo->beginTransaction();

            $sql_persona = "INSERT INTO personas (id_direccion, primer_nombre, primer_apellido, cedula, estatus) 
                            VALUES (?, ?, ?, ?, ?)";
            $nombre_persona = "Usuario_" . $usuario; // Nombre temporal para la persona
            $cedula_temp = time(); // Cédula única temporal
            
            // ATENCIÓN: Necesitas un id_direccion válido. Asumimos id_direccion = 1 existe.
            $stmt_p = $this->pdo->prepare($sql_persona);
            $stmt_p->execute([1, $nombre_persona, 'Sistema', $cedula_temp, 1]); 
            $id_persona = $this->pdo->lastInsertId();

            // 2. Insertar el usuario con la ID de la persona recién creada
            $sql_usuario = "INSERT INTO usuarios (id_persona, id_rol, usuario, contrasena, estatus) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt_u = $this->pdo->prepare($sql_usuario);
            $stmt_u->execute([$id_persona, $id_rol, $usuario, $contrasena_hash, $estatus]);
            
            $this->pdo->commit();
            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al crear usuario: " . $e->getMessage());
            return false;
        }
    }
}