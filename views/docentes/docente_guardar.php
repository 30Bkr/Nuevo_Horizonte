<?php
session_start();

include_once '../../conexion.php';
include_once '../../models/Docente.php';

if ($_POST) {
    $database = new Conexion();
    $db = $database->conectar();
    
    if ($db) {
        $docente = new Docente($db);

        // Asignar valores
        $docente->primer_nombre = $_POST['primer_nombre'];
        $docente->segundo_nombre = $_POST['segundo_nombre'];
        $docente->primer_apellido = $_POST['primer_apellido'];
        $docente->segundo_apellido = $_POST['segundo_apellido'];
        $docente->cedula = $_POST['cedula'];
        $docente->telefono = $_POST['telefono'];
        $docente->telefono_hab = $_POST['telefono_hab'];
        $docente->correo = $_POST['correo'];
        $docente->id_profesion = $_POST['id_profesion'];
        $docente->usuario = $_POST['usuario'];

        // Valores por defecto
        $docente->id_parroquia = 1;
        $docente->direccion = "Por definir";
        $docente->lugar_nac = "Caracas";
        $docente->fecha_nac = "1990-01-01";
        $docente->sexo = "Masculino";
        $docente->nacionalidad = "Venezolana";

        try {
            // Verificar si la cédula ya existe
            if ($docente->cedulaExiste($docente->cedula)) {
                $_SESSION['error'] = "La cédula {$docente->cedula} ya está registrada en el sistema.";
                header("Location: docente_nuevo.php");
                exit();
            }

            // Verificar si el usuario ya existe
            if ($docente->usuarioExiste($docente->usuario)) {
                $_SESSION['error'] = "El nombre de usuario {$docente->usuario} ya está en uso.";
                header("Location: docente_nuevo.php");
                exit();
            }

            // Crear el docente
            if ($docente->crear()) {
                $_SESSION['success'] = "Docente registrado exitosamente. Usuario creado: {$docente->usuario}";
                header("Location: docentes_list.php");
                exit();
            } else {
                $_SESSION['error'] = "Error al registrar el docente.";
                header("Location: docente_nuevo.php");
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: docente_nuevo.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error de conexión a la base de datos";
        header("Location: docente_nuevo.php");
        exit();
    }
} else {
    header("Location: docente_nuevo.php");
    exit();
}
?>