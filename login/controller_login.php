<?php
session_start();

// Incluimos la conexión
require_once '../app/conexion.php'; 
// Incluimos el modelo con el NUEVO NOMBRE
require_once '../app/models/model_usuario.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Error desconocido.'];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido.");
    }

    $usuario = $_POST['usuario'] ?? null;
    $contrasena = $_POST['contrasena'] ?? null;

    if (empty($usuario) || empty($contrasena)) {
        throw new Exception("Por favor, ingrese usuario y contraseña.");
    }

    // Instanciamos el modelo con el NUEVO NOMBRE
    $usuarioModel = new ModelUsuario();

    // 1. Buscar al usuario
    $datosUsuario = $usuarioModel->obtenerUsuarioPorUsuario($pdo, $usuario);

    // 2. Validar si el usuario existe y está activo
    if (!$datosUsuario || $datosUsuario['estatus'] == 0) {
        throw new Exception("Usuario no encontrado o inactivo.");
    }

    // 3. Verificar la contraseña
    if (password_verify($contrasena, $datosUsuario['contrasena'])) {
        
        $_SESSION['id_usuario'] = $datosUsuario['id_usuario'];
        $_SESSION['nom_usuario'] = $datosUsuario['nom_usuario'];
        $_SESSION['id_rol'] = $datosUsuario['id_rol'];
        $_SESSION['rol_nombre'] = $datosUsuario['nom_rol']; 

        $response['success'] = true;
        $response['message'] = 'Inicio de sesión exitoso.';
        
    } else {
        throw new Exception("Contraseña incorrecta.");
    }

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);