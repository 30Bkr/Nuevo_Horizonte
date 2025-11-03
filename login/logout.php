<?php
session_start(); // 1. Unirse a la sesión existente

// 2. Destruir todas las variables de sesión
$_SESSION = array();

// 3. Borrar la cookie de sesión (si se está usando)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión
session_destroy();

// 5. Redirigir al login
// Usamos la ruta absoluta como en tu menú
header("Location: /nuevo_horizonte/login/");
exit;
?>