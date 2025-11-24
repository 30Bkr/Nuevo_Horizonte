<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

if ($_POST && isset($_POST['id_docente']) && isset($_POST['estado'])) {
    $database = new Conexion();
    $db = $database->conectar();
    
    if ($db) {
        $docente = new Docente($db);
        $id_docente = $_POST['id_docente'];
        $estado = $_POST['estado'];

        try {
            if ($docente->cambiarEstado($id_docente, $estado)) {
                $accion = $estado ? 'habilitado' : 'inhabilitado';
                echo json_encode([
                    'success' => true,
                    'message' => "Docente {$accion} exitosamente.",
                    'nuevo_estado' => $estado
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => "Error al cambiar el estado del docente."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => "Error de conexión a la base de datos"
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => "Datos incompletos"
    ]);
}
?>