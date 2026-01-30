<?php
session_start();

// Definir constantes
include_once '/xampp/htdocs/final/global/utils.php';

// Incluir archivos necesarios
include_once '/xampp/htdocs/final/app/conexion.php';
include_once '/xampp/htdocs/final/app/users.php';
include_once '/xampp/htdocs/final/app/password_validator.php';
include_once '/xampp/htdocs/final/app/password_helper.php';
require_once '/xampp/htdocs/final/global/notifications.php';

// Verificar que sea administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol_id'] != 1) {
  Notification::set("Acceso denegado", "error");
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  Notification::set("Método no permitido", "error");
  header('Location: ' . URL . '/admin/usuarios/usuarios_list.php');
  exit();
}

// Obtener datos del formulario
$userId = $_POST['user_id'] ?? '';
$newPassword = trim($_POST['nueva_contrasena'] ?? '');
$confirmPassword = trim($_POST['confirmar_contrasena'] ?? '');
$forzarCambio = isset($_POST['forzar_cambio']) && $_POST['forzar_cambio'] == '1';

// Validar datos básicos
if (empty($userId) || empty($newPassword) || empty($confirmPassword)) {
  Notification::set("Todos los campos son obligatorios", "error");
  header('Location: ' . URL . '/admin/usuarios/usuarios_list.php');
  exit();
}

// Validar que las contraseñas coincidan
if ($newPassword !== $confirmPassword) {
  Notification::set("Las contraseñas no coinciden", "error");
  header('Location: ' . URL . '/admin/usuarios/usuarios_list.php');
  exit();
}

// Validar nueva contraseña
$validator = new PasswordValidator();
$validationResult = $validator->validate($newPassword);
if (!$validationResult['valid']) {
  Notification::set($validationResult['errors'][0], "error");
  header('Location: ' . URL . '/admin/usuarios/usuarios_list.php');
  exit();
}

// Instanciar usuario y cambiar contraseña
$userModel = new Usuarios();

// Verificar que el usuario existe
$usuarioData = $userModel->getById($userId);
if (!$usuarioData) {
  Notification::set("Usuario no encontrado en el sistema", "error");
  header('Location: ' . URL . '/admin/usuarios/usuarios_list.php');
  exit();
}

// Cambiar contraseña usando el mismo método que funciona
if ($userModel->cambiarContrasena($userId, $newPassword)) {

  // Si se solicitó forzar cambio, marcar la bandera
  if ($forzarCambio) {
    $userModel->marcarRequiereCambio($userId);
    Notification::set("Contraseña cambiada exitosamente. El usuario deberá cambiarla en el próximo inicio de sesión.", "success");
  } else {
    Notification::set("Contraseña cambiada exitosamente", "success");
  }
} else {
  Notification::set("Error al cambiar la contraseña", "error");
}

header('Location: ' . URL . '/admin/roles_permisos/usuarios_list.php');
exit();
