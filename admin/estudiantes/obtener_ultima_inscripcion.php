<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['id_estudiante']) || empty($_POST['id_estudiante'])) {
        throw new Exception("ID de estudiante no proporcionado");
    }

    $id_estudiante = $_POST['id_estudiante'];

    // Conectar a la base de datos
    $database = new Conexion();
    $db = $database->conectar();

    // Obtener la última inscripción del estudiante
    $sql = "SELECT id_inscripcion 
            FROM inscripciones 
            WHERE id_estudiante = :id_estudiante 
            AND estatus = 1 
            ORDER BY fecha_inscripcion DESC, id_inscripcion DESC 
            LIMIT 1";

    $stmt = $db->prepare($sql);
    $stmt->execute([':id_estudiante' => $id_estudiante]);
    $inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($inscripcion) {
        echo json_encode([
            'success' => true,
            'id_inscripcion' => $inscripcion['id_inscripcion']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'El estudiante no tiene inscripciones activas'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>