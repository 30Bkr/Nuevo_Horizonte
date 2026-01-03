<?php
class PasswordHelper
{
  /**
   * Verificar contraseña con soporte para SHA256 y BCRYPT
   */
  public static function verify($password, $hash)
  {
    // 1. Intentar como BCRYPT primero (usuarios nuevos/migrados)
    if (password_verify($password, $hash)) {
      return true;
    }

    // 2. Verificar como SHA256 (usuarios antiguos)
    if (hash('sha256', $password) === $hash) {
      return true;
    }

    // 3. Contraseña incorrecta
    return false;
  }

  /**
   * Migrar contraseña SHA256 a BCRYPT
   */
  public static function migrateToBCRYPT($userId, $password, $currentHash)
  {
    try {
      // Verificar que realmente sea SHA256
      if (hash('sha256', $password) !== $currentHash) {
        return false;
      }

      $conexion = new Conexion();
      $objConexion = $conexion->conectar();

      // Crear nuevo hash BCRYPT
      $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

      // Actualizar en BD
      $sql = "UPDATE usuarios 
                    SET contrasena = :new_hash, 
                        contrasena_migrada = 1,
                        fecha_ultimo_cambio = NOW()
                    WHERE id_usuario = :id";

      $stmt = $objConexion->prepare($sql);
      $stmt->bindParam(':new_hash', $newHash);
      $stmt->bindParam(':id', $userId);

      return $stmt->execute();
    } catch (PDOException $e) {
      error_log("Error migrando contraseña: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Detectar tipo de hash
   */
  public static function getHashType($hash)
  {
    if (password_verify('dummy', $hash)) {
      return 'BCRYPT';
    } elseif (strlen($hash) === 64 && ctype_xdigit($hash)) {
      return 'SHA256';
    } else {
      return 'UNKNOWN';
    }
  }
}
