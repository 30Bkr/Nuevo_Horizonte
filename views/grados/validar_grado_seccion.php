<?php
// validar_grado_seccion.php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

if ($_POST && isset($_POST['id_nivel']) && isset($_POST['id_seccion'])) {
    try {
        $database = new Conexion();
        $db = $database->conectar();
        $grado = new Grado($db);
        
        $id_nivel = $_POST['id_nivel'];
        $id_seccion = $_POST['id_seccion'];
        
        // Verificar si existe la combinación usando IDs
        $existe = $grado->existeCombinacion($id_nivel, $id_seccion);
        
        // Obtener nombres para el mensaje
        $query = "SELECT n.nom_nivel, s.nom_seccion 
                  FROM niveles n, secciones s 
                  WHERE n.id_nivel = ? AND s.id_seccion = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_nivel, $id_seccion]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'existe' => $existe,
            'nombre_grado' => $datos['nom_nivel'] ?? '',
            'seccion' => $datos['nom_seccion'] ?? ''
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'existe' => false,
            'nombre_grado' => '',
            'seccion' => '',
            'error' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'existe' => false,
        'nombre_grado' => '',
        'seccion' => '',
        'error' => 'Datos insuficientes'
    ]);
}
?>