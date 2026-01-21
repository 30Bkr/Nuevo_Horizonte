<?php
// global/check_permissions.php

class PermissionManager
{
  private static $pdo = null;

  /**
   * Inicializar conexión
   */
  private static function initDB()
  {
    if (self::$pdo === null) {
      require_once '/xampp/htdocs/final/app/conexion.php';
      $conexion = new Conexion();
      self::$pdo = $conexion->conectar();
    }
    return self::$pdo;
  }

  /**
   * Verificar si el usuario actual tiene permiso para la URL
   */
  public static function check($url = null)
  {
    // Si no hay sesión activa, denegar
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (!isset($_SESSION['usuario_id'])) {
      return false;
    }

    // Si no se pasa URL, usar la actual
    if ($url === null) {
      $url = self::getCurrentRelativeUrl();
    }

    // DEBUG: Ver qué está pasando
    error_log("=== CHECK PERMISSIONS ===");
    error_log("URL a verificar: " . $url);
    error_log("Usuario ID: " . ($_SESSION['usuario_id'] ?? 'NO'));
    error_log("Usuario Rol: " . ($_SESSION['usuario_rol'] ?? 'NO'));
    error_log("Usuario Rol ID: " . ($_SESSION['usuario_rol_id'] ?? 'NO'));

    // URLs que son accesibles para TODOS los usuarios autenticados
    $publicForAllAuthenticated = [
      'admin/index.php',
      'login/logout.php',
      'admin/perfil.php' // Si tienes página de perfil
    ];

    // Si la URL está en la lista pública, permitir acceso
    if (in_array($url, $publicForAllAuthenticated)) {
      error_log("URL pública - PERMITIDO");
      return true;
    }

    // Administrador tiene acceso a todo
    if (self::isAdmin()) {
      error_log("Es admin - PERMITIDO");
      return true;
    }

    // Verificar permiso en BD para otras URLs
    // CORRECCIÓN: Usar el rol_id de la sesión
    $rolId = $_SESSION['usuario_rol_id'] ?? 0;
    error_log("Verificando permisos para rol ID: " . $rolId);

    return self::hasPermission($rolId, $url);
  }

  /**
   * Verificar permiso para una URL específica
   */
  public static function hasPermission($roleId, $url)
  {
    $pdo = self::initDB();

    try {
      // DEBUG: Para ver qué se está verificando
      error_log("hasPermission - Rol ID: $roleId, URL: $url");

      // Primero intentar con la URL completa
      $sql = "SELECT COUNT(*) as tiene_permiso
                FROM roles_permisos rp
                INNER JOIN permisos p ON p.id_permiso = rp.id_permiso
                WHERE rp.id_rol = :rol_id
                  AND rp.estatus = 1
                  AND p.estatus = 1
                  AND (p.url = :url OR p.nom_url = :url)";

      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':rol_id', $roleId, PDO::PARAM_INT);
      $stmt->bindParam(':url', $url);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result['tiene_permiso'] > 0) {
        error_log("PERMITIDO - URL exacta encontrada");
        return true;
      }

      // Si no encuentra, intentar con coincidencia parcial
      $sqlPartial = "SELECT COUNT(*) as tiene_permiso
                       FROM roles_permisos rp
                       INNER JOIN permisos p ON p.id_permiso = rp.id_permiso
                       WHERE rp.id_rol = :rol_id
                         AND rp.estatus = 1
                         AND p.estatus = 1
                         AND :url LIKE CONCAT('%', p.url, '%')";

      $stmtPartial = $pdo->prepare($sqlPartial);
      $stmtPartial->bindParam(':rol_id', $roleId, PDO::PARAM_INT);
      $stmtPartial->bindParam(':url', $url);
      $stmtPartial->execute();

      $resultPartial = $stmtPartial->fetch(PDO::FETCH_ASSOC);

      $tienePermiso = $resultPartial['tiene_permiso'] > 0;
      if ($tienePermiso) {
        error_log("PERMITIDO - Coincidencia parcial encontrada");
      } else {
        error_log("DENEGADO - No se encontró permiso");
      }

      return $tienePermiso;
    } catch (PDOException $e) {
      error_log("ERROR en hasPermission: " . $e->getMessage());
      return false;
    }
  }

  /**
   * Verificar si puede ver un elemento del menú
   */
  public static function canView($menuUrl)
  {
    // Administrador ve todo
    if (self::isAdmin()) {
      return true;
    }

    if (!isset($_SESSION['usuario_id'])) {
      return false;
    }

    return self::hasPermission($_SESSION['usuario_rol_id'], $menuUrl);
  }
  public static function canViewAny(array $urls)
  {
    // Validar que $urls sea un array
    if (!is_array($urls) || empty($urls)) {
      return false;
    }

    $urlBloqueadaAdmin = 'views/grados/grados_list_solo_lectura.php';

    // Si alguna de las URLs es la bloqueada para admin
    if (in_array($urlBloqueadaAdmin, $urls)) {
      // Admin NUNCA puede ver esta página
      if (self::isAdmin()) {
        return false;
      }
    }

    // Si es admin, puede ver todo
    if (self::isAdmin()) {
      return true;
    }

    // Si no hay usuario o rol, no puede ver nada
    if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_rol_id'])) {
      return false;
    }

    $rolId = $_SESSION['usuario_rol_id'];

    // Verificar si tiene permiso para al menos una de las URLs
    foreach ($urls as $url) {
      if (self::hasPermission($rolId, $url)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Es administrador?
   */
  public static function isAdmin()
  {
    return isset($_SESSION['usuario_rol']) &&
      $_SESSION['usuario_rol'] === 'Administrador';
  }

  /**
   * Es docente?
   */
  public static function isTeacher()
  {
    return isset($_SESSION['usuario_rol']) &&
      $_SESSION['usuario_rol'] === 'Docente';
  }

  /**
   * Obtener URL relativa actual
   */
  private static function getCurrentRelativeUrl()
  {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];

    // Extraer la parte después de /final/
    $basePath = '/final/';
    $pos = strpos($requestUri, $basePath);

    if ($pos !== false) {
      $relativeUrl = substr($requestUri, $pos + strlen($basePath));

      // Limpiar parámetros GET para comparación
      $questionMarkPos = strpos($relativeUrl, '?');
      if ($questionMarkPos !== false) {
        $relativeUrl = substr($relativeUrl, 0, $questionMarkPos);
      }

      return $relativeUrl;
    }

    return $requestUri;
  }

  /**
   * Forzar verificación y redirigir si no tiene permiso
   */
  public static function requirePermission($redirectUrl = null)
  {
    if (!self::check()) {
      $_SESSION['mensaje'] = "No tienes permiso para acceder a esta sección";
      $_SESSION['icono'] = "error";

      $redirect = $redirectUrl ?? URL . '/admin/index.php';
      header('Location: ' . $redirect);
      exit();
    }
  }

  /**
   * Obtener todos los permisos del usuario actual
   */
  public static function getUserPermissions()
  {
    if (!isset($_SESSION['usuario_id'])) {
      return [];
    }

    $pdo = self::initDB();
    $roleId = $_SESSION['usuario_id'];

    try {
      $sql = "SELECT p.*
                    FROM permisos p
                    INNER JOIN roles_permisos rp ON rp.id_permiso = p.id_permiso
                    WHERE rp.id_rol = :rol_id
                      AND rp.estatus = 1
                      AND p.estatus = 1
                    ORDER BY p.id_permiso";

      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':rol_id', $roleId);
      $stmt->execute();

      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Error obteniendo permisos: " . $e->getMessage());
      return [];
    }
  }
}
