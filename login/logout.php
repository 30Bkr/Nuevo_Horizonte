<?php
// login/logout.php
session_start();

// Incluir utilidades
require_once '/xampp/htdocs/final/global/utils.php';

// Registrar actividad de logout
if (isset($_SESSION['usuario_email'])) {
  $logMessage = sprintf(
    "[%s] LOGOUT - Usuario: %s | IP: %s | Navegador: %s\n",
    date('Y-m-d H:i:s'),
    $_SESSION['usuario_email'],
    $_SERVER['REMOTE_ADDR'],
    $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido'
  );

  // Guardar en archivo local
  $logFile = '/xampp/htdocs/final/logs/accesos.log';
  file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
}

// Determinar si fue por timeout
$isTimeout = isset($_GET['timeout']) && $_GET['timeout'] === 'auto';

// Limpiar todas las variables de sesión
$_SESSION = array();

// Destruir cookie de sesión
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 86400,
    $params["path"],
    $params["domain"],
    $params["secure"] ?? false,
    $params["httponly"] ?? false
  );
}

// Destruir la sesión
session_destroy();

// Redirigir con mensaje apropiado
if ($isTimeout) {
  header('Location: ' . URL . '/login/index.php?timeout=1');
} else {
  header('Location: ' . URL . '/login/index.php?logout=1');
}
exit();
