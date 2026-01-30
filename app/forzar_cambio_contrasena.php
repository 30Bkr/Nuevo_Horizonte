<?php
session_start();
include_once('/xampp/htdocs/final/app/conexion.php');
include_once('/xampp/htdocs/final/app/users.php');
require_once '/xampp/htdocs/final/global/notifications.php';

// Verificar que sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
  Notification::set("Acceso denegado", "error");
  header('Location: /final/login/index.php');
  exit();
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  Notification::set("Método no permitido", "error");
  header('Location: /final/admin/roles_permisos/usuarios_list.php');
  exit();
}

// Validar datos
$userId = $_POST['user_id'] ?? '';

if (empty($userId)) {
  Notification::set("Usuario no especificado", "error");
  header('Location: /final/admin/roles_permisos/usuarios_list.php');
  exit();
}

// Marcar que requiere cambio de contraseña
$userModel = new Usuarios();
if ($userModel->marcarRequiereCambio($userId)) {
  Notification::set("Se ha forzado el cambio de contraseña. El usuario deberá cambiarla en el próximo inicio de sesión.", "success");
} else {
  Notification::set("Error al forzar el cambio de contraseña", "error");
}

header('Location: /final/admin/usuarios/usuarios_list.php');
exit();
