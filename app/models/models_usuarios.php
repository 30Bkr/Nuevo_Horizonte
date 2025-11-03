<?php
// app/models/models_usuarios.php

// Asegúrate que la conexión se incluya
require_once(ROOT_PATH . '/app/conexion.php'); 

class UsuariosModel {
    private $pdo;

    public function __construct() {
        // CRÍTICO: Obtener la conexión global
        global $pdo; 
        $this->pdo = $pdo;
    }
    
    // Función de ejemplo para listado
    public function getListadoUsuarios() {
        // Consulta sin restricciones de rol
        $sql = "SELECT id_usuario, nombre, email, estatus FROM usuarios ORDER BY nombre ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Función de ejemplo para crear usuario
    public function crearUsuario(array $datos) {
        // Asegúrate de que los datos incluyan al menos nombre, email, password_hash
        // Simulación: Asume que el rol por defecto es 1 (Administrador, como dijiste)
        $sql = "INSERT INTO usuarios (nombre, email, password_hash, id_rol, estatus) 
                VALUES (:nombre, :email, :password, :id_rol, 1)";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->bindParam(':nombre', $datos['nombre']);
        $stmt->bindParam(':email', $datos['email']);
        // La contraseña debe venir hasheada
        $stmt->bindParam(':password', $datos['password_hash']); 
        
        // El id_rol debe venir del controlador o se establece aquí por defecto
        $default_rol = 1; 
        $stmt->bindParam(':id_rol', $default_rol, PDO::PARAM_INT);

        return $stmt->execute();
    }
    // ... otras funciones ...
}