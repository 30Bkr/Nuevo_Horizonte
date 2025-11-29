<?php
// /final/app/controllers/inscripciones/reinscripciong2.php

// Iniciar buffer inmediatamente
ob_start();

// Configurar para desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir conexión y la nueva clase
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("ReinscripcionManager.php");

class ReinscripcionHandler
{
  private $manager;

  public function __construct($pdo)
  {
    $this->manager = new ReinscripcionManager($pdo);
  }

  /**
   * Maneja la solicitud de reinscripción
   */
  public function handleRequest()
  {
    try {
      // Verificar método
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $this->sendError('Método no permitido');
      }

      // Procesar reinscripción
      $resultado = $this->manager->procesarReinscripcion($_POST);

      $this->sendSuccess(
        'Reinscripción realizada exitosamente',
        $resultado
      );
    } catch (Exception $e) {
      error_log("Error en reinscripción: " . $e->getMessage());
      $this->sendError("Error en el proceso: " . $e->getMessage());
    }
  }

  /**
   * Envía respuesta de éxito
   */
  private function sendSuccess($message, $data = [])
  {
    $this->sendJsonResponse(true, $message, $data);
  }

  /**
   * Envía respuesta de error
   */
  private function sendError($message)
  {
    $this->sendJsonResponse(false, $message);
  }

  /**
   * Envía respuesta JSON
   */
  private function sendJsonResponse($success, $message, $additionalData = [])
  {
    // Limpiar cualquier output previo
    if (ob_get_length()) ob_clean();

    header('Content-Type: application/json');
    $response = array_merge([
      'success' => $success,
      'message' => $message
    ], $additionalData);

    echo json_encode($response);
    exit;
  }
}

// Ejecutar el handler
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  $handler = new ReinscripcionHandler($pdo);
  $handler->handleRequest();
} catch (Exception $e) {
  // Manejar errores de conexión
  if (ob_get_length()) ob_clean();
  header('Content-Type: application/json');
  http_response_code(500);
  echo json_encode([
    'success' => false,
    'message' => 'Error de conexión: ' . $e->getMessage()
  ]);
}

// Limpiar buffer final
ob_end_flush();
