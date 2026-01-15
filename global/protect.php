<?php
// global/protect.php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/utils.php';

// Función para obtener URL relativa
function getRelativeUrlForProtection()
{
  $requestUri = $_SERVER['REQUEST_URI'];
  $basePath = '/final/';

  // Extraer todo después de /final/
  $pos = strpos($requestUri, $basePath);
  if ($pos !== false) {
    $relativeUrl = substr($requestUri, $pos + strlen($basePath));

    // Remover parámetros GET
    $questionMarkPos = strpos($relativeUrl, '?');
    if ($questionMarkPos !== false) {
      $relativeUrl = substr($relativeUrl, 0, $questionMarkPos);
    }

    return $relativeUrl;
  }

  return $requestUri;
}

// URLs que NO requieren autenticación
$publicUrls = [
  'login/index.php',
  'login/login_controller.php'
];

$currentUrl = getRelativeUrlForProtection();

// DEBUG
error_log("=== PROTECT.PHP ===");
error_log("URL: $currentUrl");
error_log("Usuario ID: " . ($_SESSION['usuario_id'] ?? 'NO SESION'));

// 1. Si es URL pública, permitir acceso
if (in_array($currentUrl, $publicUrls)) {
  error_log("URL pública - SIN VERIFICACIÓN");
  return;
}

// 2. Verificar autenticación
if (!isset($_SESSION['usuario_id'])) {
  error_log("SIN AUTENTICACIÓN - Redirigiendo a login");
  $_SESSION['mensaje'] = "Debes iniciar sesión para continuar";
  $_SESSION['icono'] = "error";
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// 3. Verificar permisos
error_log("Verificando permisos para: $currentUrl");
if (!PermissionManager::check($currentUrl)) {
  error_log("SIN PERMISOS - Redirigiendo a dashboard");
  $_SESSION['mensaje'] = "No tienes permiso para acceder a esta sección";
  $_SESSION['icono'] = "error";

  // IMPORTANTE: Siempre redirigir al dashboard que SÍ es accesible
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

error_log("CON PERMISOS - Acceso permitido");
// Si pasa todas las verificaciones, continuar
