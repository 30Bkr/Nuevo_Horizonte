<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no especificado.');
$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

try {
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);
    
    if ($grado->obtenerPorId($id)) {
        if ($accion === 'habilitar') {
            if ($grado->habilitar()) {
                $_SESSION['success'] = "Grado habilitado exitosamente.";
            } else {
                $_SESSION['error'] = "No se pudo habilitar el grado.";
            }
        } elseif ($accion === 'inhabilitar') {
            if ($grado->inhabilitar()) {
                $_SESSION['success'] = "Grado inhabilitado exitosamente.";
            } else {
                $_SESSION['error'] = "No se pudo inhabilitar el grado.";
            }
        } else {
            $_SESSION['error'] = "Acción no válida.";
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