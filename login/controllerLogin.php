<?php


// 1. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  session_start();
  Notification::set("Método no permitido", "error");
  header('Location: ' . URL . '/login/index.php');
  exit();
}

include_once('../app/users.php');
include_once('../app/password_helper.php');
include_once('../global/utils.php');
require_once '/xampp/htdocs/final/global/notifications.php';

// 2. Validar campos
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
  session_start();
  Notification::set("Usuario y contraseña son obligatorios", "error");
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// 3. Buscar usuario
$user = new Usuarios();
$listaUsuario = $user->consultar($email);

if (empty($listaUsuario)) {
  session_start();
  Notification::set("Usuario o contraseña incorrectos", "error");
  header('Location: ' . URL . '/login/index.php');
  exit();
}

$usuarioData = $listaUsuario[0];
$hashAlmacenado = $usuarioData->contrasena;

// 4. VERIFICAR CONTRASEÑA
if (PasswordHelper::verify($password, $hashAlmacenado)) {
  // ✅ Contraseña correcta

  // 5. ¿Es SHA256? Entonces migrar a BCRYPT
  if (PasswordHelper::getHashType($hashAlmacenado) === 'SHA256') {
    if (PasswordHelper::migrateToBCRYPT(
      $usuarioData->id_usuario,
      $password,
      $hashAlmacenado
    )) {
      error_log("Usuario {$email} migrado a BCRYPT automáticamente");
      session_start();
    }
  }

  // 6. INICIAR SESIÓN
  session_start();

  // Obtener info del usuario
  $infoUsuario = $user->info($usuarioData->usuario);

  if (!empty($infoUsuario)) {
    $info = $infoUsuario[0];
    $_SESSION['usuario_id'] = $usuarioData->id_usuario;
    $_SESSION['usuario_email'] = $usuarioData->usuario;
    $_SESSION['usuario_nombre'] = $info->nombre . ' ' . $info->apellido;
    $_SESSION['usuario_rol'] = $info->cargo;
    $_SESSION['usuario_rol_id'] = $usuarioData->id_rol;
  } else {
    $_SESSION['usuario_id'] = $usuarioData->id_usuario;
    $_SESSION['usuario_email'] = $usuarioData->usuario;
    $_SESSION['usuario_rol'] = $info->cargo;
    $_SESSION['usuario_rol_id'] = $usuarioData->id_rol;
  }

  // *** NUEVO: Verificar si requiere cambio de contraseña ***
  $requiereCambio = $user->requiereCambioContrasena($usuarioData->id_usuario);
  $contrasenaMigrada = $user->contrasenaMigrada($usuarioData->id_usuario);

  // Forzar cambio si:
  // 1. Está marcado como que requiere cambio, o
  // 2. No es administrador y no ha migrado su contraseña (usuario nuevo)
  if ($requiereCambio || ($usuarioData->id_rol != 1 && $contrasenaMigrada == 0)) {
    Notification::set("Por seguridad, debe cambiar su contraseña antes de continuar", "warning");
    header('Location:' . URL . '/views/usuarios/cambiar_contrasena.php?forzado=1');
    exit();
  }



  // 7. REDIRIGIR A TODOS AL MISMO DASHBOARD
  Notification::set("Inicio de sesión exitoso", "success");
  header('Location:' . URL . '/admin/index.php');
  exit();
} else {
  // ❌ Contraseña incorrecta
  Notification::set("Usuario o contraseña incorrectos", "error");
  header('Location: ' . URL . '/login/index.php');
  exit();
}
