<?php
// Controlador para peticiones Ajax del módulo de Usuarios
session_start();
require_once '../../conexion.php';
require_once '../../models/model_usuario.php'; // Nombre actualizado

// Preparamos la respuesta
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Acción no válida o error.'];

// Verificamos que el usuario sea Admin (ID 1)
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    $response['message'] = 'Acceso denegado. Permisos insuficientes.';
    echo json_encode($response);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;
$usuarioModel = new ModelUsuario(); // Clase actualizada
// $pdo viene de 'conexion.php'

try {
    switch ($action) {
        
        case 'listar':
            $usuarios = $usuarioModel->obtenerTodos($pdo);
            // DataTables espera un objeto 'data'
            echo json_encode(['data' => $usuarios]); 
            exit; // Salimos para no enviar el $response de abajo

        case 'crear':
            $nombre = $_POST['nom_usuario'] ?? null;
            $usuario = $_POST['usuario'] ?? null;
            $idRol = $_POST['id_rol'] ?? null;

            // 1. Validar campos
            if (empty($nombre) || empty($usuario) || empty($idRol)) {
                $response['message'] = 'Todos los campos son obligatorios.';
                echo json_encode($response);
                exit;
            }
            
            // 2. Verificar si el usuario ya existe
            if ($usuarioModel->verificarUsuarioExiste($pdo, $usuario)) {
                $response['message'] = 'El usuario (cédula) ya se encuentra registrado.';
                echo json_encode($response);
                exit;
            }

            // 3. Hashear la contraseña predeterminada
            $contrasenaDefault = "12345678";
            $contrasenaHash = password_hash($contrasenaDefault, PASSWORD_BCRYPT);

            // 4. Intentar crear el usuario
            $creado = $usuarioModel->crearUsuario($pdo, $nombre, $usuario, $idRol, $contrasenaHash);

            if ($creado) {
                $response['success'] = true;
                $response['message'] = '¡Usuario creado exitosamente!';
            } else {
                $response['message'] = 'Error al crear el usuario en la base de datos.';
            }
            
            echo json_encode($response);
            exit;
            
        case 'actualizar':
            // Lógica para actualizar (Próximo paso)
            // ...
            break;

        default:
            echo json_encode($response); // Envía {'success': false, 'message': 'Acción no válida...'}
            break;
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    echo json_encode($response);
}
?>