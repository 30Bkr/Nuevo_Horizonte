<?php
session_start();
require_once __DIR__ . '/../../app/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

// Validar que el usuario tenga sesión iniciada (cualquier usuario logueado)
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión para subir fotos.']);
    exit();
}

$database = new Conexion();
$db = $database->conectar();

$cedula = $_POST['cedula'] ?? '';
$tipo = $_POST['tipo'] ?? ''; // 'estudiante' o 'representante'
$foto = $_FILES['foto'] ?? null;

if (empty($cedula) || empty($tipo) || !$foto) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit();
}

// Validar tipo de archivo
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
if (!in_array($foto['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Formato no permitido. Use JPG, PNG o GIF']);
    exit();
}

// Validar tamaño (máximo 2MB)
if ($foto['size'] > 2097152) {
    echo json_encode(['success' => false, 'message' => 'La imagen no debe superar 2MB']);
    exit();
}

// Crear directorio si no existe
$upload_dir = '/final/uploads/fotos/';
$absolute_path = $_SERVER['DOCUMENT_ROOT'] . $upload_dir;

if (!is_dir($absolute_path)) {
    if (!mkdir($absolute_path, 0777, true)) {
        echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio para fotos']);
        exit();
    }
}

// Generar nombre único para el archivo
$file_extension = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
$new_filename = $tipo . '_' . $cedula . '_' . time() . '.' . $file_extension;
$target_path = $absolute_path . $new_filename;

// Mover archivo
if (move_uploaded_file($foto['tmp_name'], $target_path)) {
    // Actualizar en la base de datos
    $field_name = ($tipo == 'estudiante') ? 'foto_estudiante' : 'foto_representante';
    $sql = "UPDATE personas SET $field_name = :foto_path WHERE cedula = :cedula";
    
    $stmt = $db->prepare($sql);
    $foto_path = $upload_dir . $new_filename;
    $stmt->bindParam(':foto_path', $foto_path);
    $stmt->bindParam(':cedula', $cedula);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Foto actualizada correctamente',
            'foto_path' => $foto_path
        ]);
    } else {
        // Eliminar archivo si falla la actualización en BD
        if (file_exists($target_path)) {
            unlink($target_path);
        }
        echo json_encode(['success' => false, 'message' => 'Error al actualizar en base de datos']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen al servidor']);
}
?>