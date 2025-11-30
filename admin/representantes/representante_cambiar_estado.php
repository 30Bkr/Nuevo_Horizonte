<?php
session_start();
header('Content-Type: application/json');

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/representantes/RepresentanteController.php';

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_representante = $_POST['id_representante'] ?? '';
        $estado = $_POST['estado'] ?? '';

        if (empty($id_representante) || !is_numeric($id_representante)) {
            throw new Exception("ID de representante inválido");
        }

        $database = new Conexion();
        $db = $database->conectar();

        if (!$db) {
            throw new Exception("Error de conexión a la base de datos");
        }

        $controller = new RepresentanteController($db);
        
        if ($controller->cambiarEstado($id_representante, $estado)) {
            $response['success'] = true;
            $response['message'] = $estado ? 'Representante habilitado exitosamente' : 'Representante inhabilitado exitosamente';
        } else {
            throw new Exception("No se pudo cambiar el estado del representante");
        }
    } else {
        throw new Exception("Método no permitido");
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>