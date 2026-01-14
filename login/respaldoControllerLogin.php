<?php
include_once('../app/users.php');
include_once('../app/password_helper.php'); // NUEVO
include_once('../global/utils.php');

// 1. Validar método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  session_start();
  $_SESSION['mensaje'] = "Método no permitido";
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// 2. Validar campos
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
  session_start();
  $_SESSION['mensaje'] = "Usuario y contraseña son obligatorios";
  header('Location: ' . URL . '/login/index.php');
  exit();
}

// 3. Buscar usuario
$user = new Usuarios();
$listaUsuario = $user->consultar($email);

if (empty($listaUsuario)) {
  session_start();
  $_SESSION['mensaje'] = "Usuario no encontrado";
  header('Location: ' . URL . '/login/index.php');
  exit();
}

$usuarioData = $listaUsuario[0];
$hashAlmacenado = $usuarioData->contrasena;

// 4. VERIFICAR CONTRASEÑA (NUEVO SISTEMA HÍBRIDO)
if (PasswordHelper::verify($password, $hashAlmacenado)) {
  // ✅ Contraseña correcta

  // 5. ¿Es SHA256? Entonces migrar a BCRYPT
  if (PasswordHelper::getHashType($hashAlmacenado) === 'SHA256') {
    // Migrar automáticamente
    if (PasswordHelper::migrateToBCRYPT(
      $usuarioData->id_usuario,
      $password,
      $hashAlmacenado
    )) {
      // Registrar en logs
      error_log("Usuario {$email} migrado a BCRYPT automáticamente");

      // Opcional: Notificar al usuario
      session_start();
      $_SESSION['mensaje_info'] = "Tu contraseña ha sido actualizada automáticamente por seguridad";
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
  } else {
    $_SESSION['usuario_id'] = $usuarioData->id_usuario;
    $_SESSION['usuario_email'] = $usuarioData->usuario;
    $_SESSION['usuario_rol'] = 'Usuario';
  }

  $_SESSION['icono'] = "success";

  // 7. Redirigir según rol
  if ($_SESSION['usuario_rol'] === 'Administrador') {
    header('Location:' . URL . '/admin/index.php');
  } else {
    header('Location:' . URL . '/dashboard/index.php');
  }
  exit();
} else {
  // ❌ Contraseña incorrecta
  session_start();
  $_SESSION['mensaje'] = "Usuario o contraseña incorrectos";
  header('Location: ' . URL . '/login/index.php');
  exit();
}
