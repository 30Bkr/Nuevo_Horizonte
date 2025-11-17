<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

if (isset($_GET['id'])) {
    $database = new Conexion();
    $db = $database->conectar();
    
    if ($db) {
        $docente = new Docente($db);
        $id = $_GET['id'];

        try {
            // Primero verificar si el docente existe
            if ($docente->obtenerPorId($id)) {
                // Eliminar el docente (soft delete)
                if ($docente->eliminar($id)) {
                    $_SESSION['success'] = "Docente eliminado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al eliminar el docente.";
                }
            } else {
                $_SESSION['error'] = "El docente no existe o ya fue eliminado.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = "Error de conexión a la base de datos";
    }
} else {
    $_SESSION['error'] = "ID de docente no especificado";
}

// Redirigir de vuelta al listado
header("Location: docentes_list.php");
exit();
?>