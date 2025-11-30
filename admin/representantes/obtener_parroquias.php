<?php
session_start();

// Incluir archivos con rutas absolutas
$root = $_SERVER['DOCUMENT_ROOT'] . '/final';
include_once $root . '/app/conexion.php';
include_once $root . '/app/controllers/representantes/RepresentanteController.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['id_municipio']) || empty($_POST['id_municipio'])) {
        throw new Exception("ID de municipio no proporcionado");
    }

    $id_municipio = $_POST['id_municipio'];

    // Conectar a la base de datos
    $database = new Conexion();
    $db = $database->conectar();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $controller = new RepresentanteController($db);
    $parroquias = $controller->obtenerParroquiasPorMunicipio($id_municipio);

    $parroquias_array = [];
    while ($parroquia = $parroquias->fetch(PDO::FETCH_ASSOC)) {
        $parroquias_array[] = $parroquia;
    }

    echo json_encode([
        'success' => true,
        'parroquias' => $parroquias_array
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>