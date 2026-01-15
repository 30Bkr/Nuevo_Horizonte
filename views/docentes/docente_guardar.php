<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

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
        
        // ========== CAMPOS DE DIRECCIÓN (ACTUALIZADOS) ==========
        // En el formulario, el campo de parroquia viene como 'parroquia' no 'id_parroquia'
        $docente->id_parroquia = $_POST['parroquia'];
        $docente->direccion = $_POST['direccion'];
        $docente->calle = $_POST['calle'];
        $docente->casa = $_POST['casa'];
        
        // Campos adicionales
        $docente->lugar_nac = $_POST['lugar_nac'];
        $docente->fecha_nac = $_POST['fecha_nac'];
        $docente->sexo = $_POST['sexo'];
        $docente->nacionalidad = $_POST['nacionalidad'];

        // El usuario se genera automáticamente con la cédula
        $docente->usuario = $docente->cedula;

        try {
            // Verificar si la cédula ya existe
            if ($docente->cedulaExiste($docente->cedula)) {
                $_SESSION['error'] = "La cédula {$docente->cedula} ya está registrada en el sistema.";
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