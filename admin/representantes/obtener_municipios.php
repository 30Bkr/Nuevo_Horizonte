<?php
session_start();

// Incluir archivos con rutas absolutas
$root = $_SERVER['DOCUMENT_ROOT'] . '/final';
include_once $root . '/app/conexion.php';
include_once $root . '/app/controllers/representantes/RepresentanteController.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['id_estado']) || empty($_POST['id_estado'])) {
        throw new Exception("ID de estado no proporcionado");
    }

    $id_estado = $_POST['id_estado'];

    // Conectar a la base de datos
    $database = new Conexion();
    $db = $database->conectar();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $controller = new RepresentanteController($db);
    $municipios = $controller->obtenerMunicipiosPorEstado($id_estado);

    $municipios_array = [];
    while ($municipio = $municipios->fetch(PDO::FETCH_ASSOC)) {
        $municipios_array[] = $municipio;
    }

    echo json_encode([
        'success' => true,
        'municipios' => $municipios_array
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>