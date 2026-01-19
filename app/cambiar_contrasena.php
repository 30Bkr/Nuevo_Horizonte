<?php
session_start();

// Definir constantes
include_once '/xampp/htdocs/final/global/utils.php';

// Incluir archivos necesarios
include_once '/xampp/htdocs/final/app/conexion.php';
include_once '/xampp/htdocs/final/app/users.php';
include_once '/xampp/htdocs/final/app/password_validator.php';
include_once '/xampp/htdocs/final/app/password_helper.php';


// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
  $_SESSION['mensaje'] = "Debes iniciar sesión para continuar";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['mensaje'] = "Acceso no autorizado";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

// Obtener datos del formulario
$userId = $_SESSION['usuario_id'];
$currentPassword = trim($_POST['contrasena_actual'] ?? '');
$newPassword = trim($_POST['nueva_contrasena'] ?? '');
$confirmPassword = trim($_POST['confirmar_contrasena'] ?? '');

// Instanciar usuario
$user = new Usuarios();
$usuarioData = $user->getById($userId);

if (!$usuarioData) {
  $_SESSION['mensaje'] = "Usuario no encontrado en el sistema";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// Verificar si requiere cambio forzado
$requiereCambio = $user->requiereCambioContrasena($userId);

// Si NO es cambio forzado, validar contraseña actual
if (!$requiereCambio) {
  if (empty($currentPassword)) {
    $_SESSION['mensaje'] = "Debes ingresar tu contraseña actual";
    $_SESSION['tipo_mensaje'] = 'error';
    header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
    exit();
  }

  // Verificar contraseña actual
  if (!PasswordHelper::verify($currentPassword, $usuarioData->contrasena)) {
    $_SESSION['mensaje'] = "La contraseña actual es incorrecta";
    $_SESSION['tipo_mensaje'] = 'error';
    header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
    exit();
  }
}

// Validar que las contraseñas coincidan
if (!PasswordValidator::match($newPassword, $confirmPassword)) {
  $_SESSION['mensaje'] = "Las contraseñas no coinciden";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
  exit();
}

// Validar nueva contraseña
$validation = PasswordValidator::validate($newPassword);
if (!$validation['valid']) {
  $_SESSION['mensaje'] = "Error en la nueva contraseña:<br>" . implode("<br>• ", $validation['errors']);
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
  exit();
}

// Verificar que la nueva contraseña sea diferente a la actual
if (!PasswordValidator::isDifferent($newPassword, $usuarioData->contrasena)) {
  $_SESSION['mensaje'] = "La nueva contraseña debe ser diferente a la actual";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
  exit();
}

// Cambiar contraseña
if ($user->cambiarContrasena($userId, $newPassword)) {
  $_SESSION['mensaje'] = "¡Contraseña cambiada exitosamente!";
  $_SESSION['tipo_mensaje'] = 'success';

  // Si era cambio forzado, también actualizar sesión
  if ($requiereCambio) {
    // Obtener información actualizada del usuario
    $infoUsuario = $user->info($usuarioData->usuario);
    if (!empty($infoUsuario)) {
      $info = $infoUsuario[0];
      $_SESSION['usuario_nombre'] = $info->nombre . ' ' . $info->apellido;
      $_SESSION['usuario_rol'] = $info->cargo;
    }
  }

  // Redirigir según el caso
  if ($requiereCambio) {
    header('Location: ' . URL . '/admin/index.php');
  } else {
    header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
  }
  exit();
} else {
  $_SESSION['mensaje'] = "Error al guardar la nueva contraseña. Por favor, intenta nuevamente.";
  $_SESSION['tipo_mensaje'] = 'error';
  header('Location: ' . URL . '/views/usuarios/cambiar_contrasena.php');
  exit();
}
