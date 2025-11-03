<?php
session_start();
require_once('models_login.php');

//require_once(ROOT_PATH . '/app/models/models_seguridad.php'); 

$response = ['status' => 'error', 'message' => 'Ocurrió un error inesperado.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $usuario = trim($_POST['usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (empty($usuario) || empty($contrasena)) {
        $response['message'] = 'Usuario y Contraseña son requeridos.';
    } else {
        $model = new LoginModel();
        $userData = $model->getUsuarioByUsername($usuario);

        if ($userData) {
            // Usuario encontrado. Verificamos contraseña.
            if (password_verify($contrasena, $userData['contrasena'])) {
                
                // --- SESIÓN ACTUALIZADA ---
                // Guardamos los datos en la sesión
                $_SESSION['id_usuario'] = $userData['id_usuario'];
                $_SESSION['usuario'] = $userData['usuario'];
                
                // Temporalmente, usamos el 'usuario' como nombre a mostrar
                $_SESSION['nombre_completo'] = $userData['usuario']; 
                
                $_SESSION['id_rol'] = $userData['id_rol'];
                $_SESSION['rol_nombre'] = $userData['nom_rol'];
                $_SESSION['autenticado'] = true;
                // --- FIN SESIÓN ---

                $response['status'] = 'success';
                $response['message'] = '¡Acceso Concedido!';
                
                // Redirección basada en rol
                if ($userData['id_rol'] == 1) { // Asumiendo 1 = Admin
                    $response['redirect'] = '../admin/dashboard.php';
                } else {
                    $response['redirect'] = '../global/dashboard_usuario.php';
                }

            } else {
                $response['message'] = 'La contraseña introducida es incorrecta.';
            }
        } else {
            $response['message'] = 'El usuario introducido no existe o se encuentra inactivo.';
        }
    }
} else {
    $response['message'] = 'Método de solicitud no válido.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>