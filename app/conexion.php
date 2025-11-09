<?php
class Conexion
{
  private $hostname = 'localhost';
  private $database = 'segunda';
  private $usuario = 'root';
  private $password = '5413528';
  private $conn;
  public function conectar()
  {
    $dsn = "mysql:host=$this->hostname;dbname=$this->database";
    try {
      $this->conn = new PDO($dsn, $this->usuario, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->conn;
    } catch (PDOException $err) {
      echo "Error en conexion a base de datos: " . $err->getMessage();
    }
  }
  public function desconectar()
  {
    $this->conn = null;
  }
}
