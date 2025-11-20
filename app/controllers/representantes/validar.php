<?php
header('Content-Type: application/json');

// Incluir las clases necesarias
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/representantes/representantes.php");

try {
  // Verificar que la solicitud sea POST
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    throw new Exception('Método no permitido');
  }

  // Obtener y validar la cédula
  if (!isset($_POST['cedula']) || empty($_POST['cedula'])) {
    throw new Exception('Cédula no proporcionada');
  }

  $cedula = trim($_POST['cedula']);

  // Validar que sea numérica
  if (!is_numeric($cedula)) {
    throw new Exception('La cédula debe contener solo números');
  }

  // Conectar a la base de datos
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  // Crear instancia del controlador
  $representanteController = new RepresentanteController($pdo);

  // Validar representante

  $resultado = $representanteController->validarRepresentante($cedula);
  if ($resultado['existe']) {
    echo json_encode($resultado);
  } else {
    $resultado2 = $representanteController->validarDocente($cedula);
    if ($resultado2['existe']) {
      echo json_encode($resultado2);
    } else {
      echo json_encode(['Existe' => false]);
    }
  }
  // Devolver resultado en formato JSON
  // echo json_encode($resultado);
} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'error' => true,
    'message' => $e->getMessage()
  ]);
} finally {
  // Cerrar conexión si existe
  if (isset($conexion)) {
    $conexion->desconectar();
  }
}
