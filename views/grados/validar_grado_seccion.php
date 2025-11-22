<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_nivel = $_POST['id_nivel'] ?? '';
        $id_seccion = $_POST['id_seccion'] ?? '';
        
        if (empty($id_nivel) || empty($id_seccion)) {
            echo json_encode(['existe' => false]);
            exit();
        }
        
        $database = new Conexion();
        $db = $database->conectar();
        $grado = new Grado($db);
        
        // Verificar si la combinación ya existe
        $existe = $grado->existeCombinacion($id_nivel, $id_seccion);
        
        // Obtener nombres para el mensaje
        $nombres = [];
        if ($existe) {
            $query = "SELECT n.nom_nivel as nombre_grado, s.nom_seccion as seccion
                      FROM niveles n, secciones s 
                      WHERE n.id_nivel = ? AND s.id_seccion = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id_nivel, $id_seccion]);
            $nombres = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Si no existe, obtener nombres igualmente para el mensaje de disponibilidad
            $query = "SELECT n.nom_nivel as nombre_grado, s.nom_seccion as seccion
                      FROM niveles n, secciones s 
                      WHERE n.id_nivel = ? AND s.id_seccion = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$id_nivel, $id_seccion]);
            $nombres = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        echo json_encode([
            'existe' => $existe,
            'nombre_grado' => $nombres['nombre_grado'] ?? '',
            'seccion' => $nombres['seccion'] ?? ''
        ]);
    }
} catch (Exception $e) {
    echo json_encode(['existe' => false, 'error' => $e->getMessage()]);
}
?>