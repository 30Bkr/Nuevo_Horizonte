<?php
// global/protect.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/utils.php';
require_once '/xampp/htdocs/final/global/notifications.php';



// Función auxiliar para obtener URL relativa
function getCurrentRelativeUrlForProtection()
{
  $requestUri = $_SERVER['REQUEST_URI'];

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

// Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
  $_SESSION['mensaje'] = "Es necesario iniciar sesión";
  $_SESSION['icono'] = "error";

  if (!headers_sent()) {
    header('Location: ' . URL . '/login/index.php');
    exit();
  } else {
    echo '<script>window.location.href="' . URL . '/login/index.php"</script>';
    exit();
  }
}

// Verificar permisos para la página actual
$currentUrl = getCurrentRelativeUrlForProtection();
if (!PermissionManager::check($currentUrl)) {
  // $_SESSION['mensaje'] = "No tienes permiso para acceder a esta sección";
  // $_SESSION['icono'] = "error";
  // Usar Notification::set en lugar de $_SESSION directamente
  Notification::set("No tienes permiso para acceder a esta sección", "error");

  if (!headers_sent()) {
    header('Location: ' . URL . '/admin/index.php');
    exit();
  } else {
    echo '<script>window.location.href="' . URL . '/admin/index.php"</script>';
    exit();
  }
}
