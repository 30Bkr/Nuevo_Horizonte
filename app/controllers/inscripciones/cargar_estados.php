<?php
include_once("/xampp/htdocs/final/app/conexion.php");

header('Content-Type: application/json');

// Habilitar logging de errores
error_log("=== cargar_estados.php ejecutado ===");

try {
  $conexion = new Conexion();
  $conn = $conexion->conectar();

  error_log("✅ Conexión a BD establecida");

  $query = "SELECT id_estado as id, nom_estado as nombre 
              FROM estados 
              WHERE estatus = 1 
              ORDER BY nom_estado";

  error_log("🔍 Ejecutando query: " . $query);

  $stmt = $conn->prepare($query);
  $stmt->execute();

  $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $count = count($estados);

  error_log("📊 Estados encontrados: " . $count);

  if ($count > 0) {
    error_log("📝 Estados: " . json_encode($estados));

    echo json_encode([
      'success' => true,
      'estados' => $estados,
      'debug' => [
        'total_estados' => $count,
        'query' => $query
      ]
    ]);
  } else {
    error_log("⚠️ No se encontraron estados activos");

    echo json_encode([
      'success' => false,
      'error' => 'No se encontraron estados en la base de datos',
      'debug' => [
        'total_estados' => 0,
        'query' => $query
      ]
    ]);
  }
} catch (PDOException $e) {
  $error_msg = "❌ Error PDO: " . $e->getMessage();
  error_log($error_msg);

  echo json_encode([
    'success' => false,
    'error' => $error_msg,
    'debug' => [
      'error_code' => $e->getCode(),
      'error_info' => $e->getMessage()
    ]
  ]);
} catch (Exception $e) {
  $error_msg = "❌ Error general: " . $e->getMessage();
  error_log($error_msg);

  echo json_encode([
    'success' => false,
    'error' => $error_msg
  ]);
}

error_log("=== cargar_estados.php finalizado ===");
