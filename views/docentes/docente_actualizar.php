<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

if ($_POST) {
    $database = new Conexion();
    $db = $database->conectar();
    
    if ($db) {
        $docente = new Docente($db);

        // Asignar valores (EXCLUYENDO LA CÉDULA)
        $docente->id_docente = $_POST['id_docente'];
        $docente->id_persona = $_POST['id_persona'];
        $docente->id_direccion = $_POST['id_direccion'];
        $docente->primer_nombre = $_POST['primer_nombre'];
        $docente->segundo_nombre = $_POST['segundo_nombre'];
        $docente->primer_apellido = $_POST['primer_apellido'];
        $docente->segundo_apellido = $_POST['segundo_apellido'];
        // $docente->cedula = $_POST['cedula']; // NO se asigna, no se puede editar
        $docente->telefono = $_POST['telefono'];
        $docente->telefono_hab = $_POST['telefono_hab'];
        $docente->correo = $_POST['correo'];
        $docente->lugar_nac = $_POST['lugar_nac'];
        $docente->fecha_nac = $_POST['fecha_nac'];
        $docente->sexo = $_POST['sexo'];
        $docente->nacionalidad = $_POST['nacionalidad'];
        $docente->id_profesion = $_POST['id_profesion'];
        $docente->usuario = $_POST['usuario'];

        try {
            // Verificar si el usuario ya existe (excluyendo el actual)
            if ($docente->usuarioExiste($docente->usuario, $docente->id_persona)) {
                $_SESSION['error'] = "El nombre de usuario {$docente->usuario} ya está en uso.";
                header("Location: docente_editar.php?id=" . $docente->id_docente);
                exit();
            }

            // Actualizar el docente
            if ($docente->actualizar()) {
                $_SESSION['success'] = "Docente actualizado exitosamente.";
                header("Location: docentes_list.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al actualizar el docente.";
                header("Location: docente_editar.php?id=" . $docente->id_docente);
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: docente_editar.php?id=" . $docente->id_docente);
            exit();
        }
    } else {
        $_SESSION['error'] = "Error de conexión a la base de datos";
        header("Location: docente_editar.php?id=" . $_POST['id_docente']);
        exit();
    }
} else {
    header("Location: docentes_list.php");
    exit();
}
?>