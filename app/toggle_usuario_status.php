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
$newStatus = $_POST['new_status'] ?? '';

if (empty($userId) || !in_array($newStatus, ['0', '1'])) {
    Notification::set("Datos inválidos", "error");
    header('Location: /final/admin/roles_permisos/usuarios_list.php');
    exit();
}

// No permitir desactivarse a sí mismo
if ($userId == $_SESSION['usuario_id']) {
    Notification::set("No puedes cambiar tu propio estado", "error");
    header('Location: /final/admin/roles_permisos/usuarios_list.php');
    exit();
}

// Cambiar estado
try {
    $conexion = new Conexion();
    $objConexion = $conexion->conectar();

    $sql = "UPDATE usuarios SET estatus = :status WHERE id_usuario = :id";
    $stmt = $objConexion->prepare($sql);
    $stmt->bindParam(':status', $newStatus);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        $statusText = $newStatus == '1' ? 'activada' : 'inactivada';
        Notification::set("Cuenta {$statusText} exitosamente", "success");
    } else {
        Notification::set("Error al cambiar el estado de la cuenta", "error");
    }
} catch (PDOException $e) {
    error_log("Error en toggle_usuario_status: " . $e->getMessage());
    Notification::set("Error en el sistema", "error");
}

header('Location: /final/admin/roles_permisos/usuarios_list.php');
exit();
