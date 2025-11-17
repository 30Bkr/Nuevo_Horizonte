<?php
session_start();

include_once '../../conexion.php';
include_once '../../models/Docente.php';

if (isset($_GET['id'])) {
    $database = new Conexion();
    $db = $database->conectar();
    
    if ($db) {
        $docente = new Docente($db);
        $id = $_GET['id'];

        if ($docente->eliminar($id)) {
            $_SESSION['success'] = "Docente eliminado exitosamente.";
        } else {
            $_SESSION['error'] = "Error al eliminar el docente.";
        }
    } else {
        $_SESSION['error'] = "Error de conexión a la base de datos";
    }
}

header("Location: docentes_list.php");
exit();
?>