<?php
session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_estudiante = $_POST['id_estudiante'] ?? '';
        $estado = $_POST['estado'] ?? '';

        if (empty($id_estudiante) || !is_numeric($id_estudiante)) {
            throw new Exception("ID de estudiante inválido");
        }

        $database = new Conexion();
        $db = $database->conectar();

        if (!$db) {
            throw new Exception("Error de conexión a la base de datos");
        }

        $controller = new EstudianteController($db);
        
        if ($controller->cambiarEstado($id_estudiante, $estado)) {
            $response['success'] = true;
            $response['message'] = $estado ? 'Estudiante habilitado exitosamente' : 'Estudiante inhabilitado exitosamente';
        } else {
            throw new Exception("No se pudo cambiar el estado del estudiante");
        }
    } else {
        throw new Exception("Método no permitido");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>