<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no especificado.');

try {
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);
    
    if ($grado->obtenerPorId($id)) {
        if ($grado->eliminar()) {
            $_SESSION['success'] = "Grado eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "No se pudo eliminar el grado. Verifique que no tenga estudiantes inscritos.";
        }
    } else {
        $_SESSION['error'] = "Grado no encontrado.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header("Location: grados_list.php");
exit();
?>