<?php

// Configuración de la base de datos
define('DB_HOST', 'localhost');      // Servidor (usualmente localhost)
define('DB_NAME', 'nuevo_horizonte'); // Nombre de tu base de datos
define('DB_USER', 'root');          // Usuario de la DB
define('DB_PASS', '');              // Contraseña de la DB
define('DB_CHARSET', 'utf8mb4');

class Conexion {
    
    protected $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
             $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (\PDOException $e) {
             throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Retorna la instancia de la conexión PDO.
     */
    public function getPdo() {
        return $this->pdo;
    }
}

// Instanciamos la conexión para que esté disponible
try {
    $conexion = new Conexion();
    $pdo = $conexion->getPdo(); // $pdo será tu variable de conexión global
} catch (\PDOException $e) {
    // Manejo de error de conexión
    echo "Error de conexión: " . $e->getMessage();
    exit;
}

?>