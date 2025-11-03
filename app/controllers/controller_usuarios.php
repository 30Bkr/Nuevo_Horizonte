<?php
// admin/usuarios/controller_usuarios.php

// CRÍTICO: Inclusión de config.php usando la ruta relativa correcta
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_usuarios.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');

$R_LISTADO = '/admin/usuarios/usuarios_listado.php';
$R_CREAR = '/admin/usuarios/usuarios_crear.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recolección y validación de datos
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    // Asumimos que la contraseña la maneja el modelo o la vista
    $password_raw = '12345678'; // Contraseña por defecto

    // ... lógica de validación básica ...

    if (empty($nombre) || empty($email)) {
        setAlert('error', 'Nombre y Email son obligatorios.', $R_CREAR);
        header("Location: " . getAlertRedirect());
        exit();
    }
    
    $datos_usuario = [
        'nombre' => $nombre,
        'email' => $email,
        'password_hash' => password_hash($password_raw, PASSWORD_DEFAULT),
    ];

    $usuarioModel = new UsuariosModel();

    if ($usuarioModel->crearUsuario($datos_usuario)) {
        // ÉXITO
        setAlert('success', 'Usuario creado exitosamente. Contraseña por defecto: 12345678.', $R_LISTADO);
        header("Location: " . getAlertRedirect());
        exit();
    } else {
        // ERROR DE DB
        setAlert('error', 'Error al crear el usuario. Verifique los datos o consulte la BD.', $R_CREAR);
        header("Location: " . getAlertRedirect());
        exit();
    }
} else {
    // Acceso directo por GET no permitido
    header("Location: " . BASE_URL . $R_LISTADO);
    exit();
}
?>