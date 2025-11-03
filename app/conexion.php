<?php
class Conexion {
    private $host = "localhost";
    private $db = "nuevo_horizonte";
    private $user = "root"; // Cambia esto por tu usuario de BD
    private $pass = "";     // Cambia esto por tu contraseña de BD
    private $charset = "utf8mb4";
    private $pdo;

    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public function getConexion() {
        return $this->pdo;
    }
}
?>