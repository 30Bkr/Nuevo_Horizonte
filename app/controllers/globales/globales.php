<?php
class GlobalesController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  /**
   * Obtiene las edades mínima y máxima desde la tabla globales
   */
  public function obtenerEdades()
  {
    try {
      // Consulta para obtener las edades globales (último registro activo)
      $sql = "SELECT edad_min, edad_max FROM globales ORDER BY id_globales DESC LIMIT 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        return [
          'success' => true,
          'edad_min' => (int)$result['edad_min'],
          'edad_max' => (int)$result['edad_max']
        ];
      } else {
        return [
          'success' => false,
          'error' => 'No se encontraron registros en la tabla globales'
        ];
      }
    } catch (PDOException $e) {
      error_log("Error en obtenerEdades: " . $e->getMessage());
      return [
        'success' => false,
        'error' => 'Error en la base de datos: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Obtiene todas las variables globales
   */
  public function obtenerVariablesGlobales()
  {
    try {
      $sql = "SELECT edad_min, edad_max, nom_instituto, id_periodo FROM globales ORDER BY id_globales DESC LIMIT 1";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($result) {
        return [
          'success' => true,
          'data' => [
            'edad_min' => (int)$result['edad_min'],
            'edad_max' => (int)$result['edad_max'],
            'nom_instituto' => $result['nom_instituto'],
            'id_periodo' => (int)$result['id_periodo']
          ]
        ];
      } else {
        return [
          'success' => false,
          'error' => 'No se encontraron registros en la tabla globales'
        ];
      }
    } catch (PDOException $e) {
      error_log("Error en obtenerVariablesGlobales: " . $e->getMessage());
      return [
        'success' => false,
        'error' => 'Error en la base de datos: ' . $e->getMessage()
      ];
    }
  }

  /**
   * Actualiza las edades mínima y máxima
   */
  public function actualizarEdades($edad_min, $edad_max, $id_periodo)
  {
    try {
      $sql = "INSERT INTO globales (edad_min, edad_max, nom_instituto, id_periodo) 
                    VALUES (?, ?, ?, ?)";
      $stmt = $this->pdo->prepare($sql);

      // Puedes mantener el nombre del instituto actual o pasarlo como parámetro
      $nom_instituto = "Nombre del Instituto"; // Ajusta según necesites

      $stmt->execute([$edad_min, $edad_max, $nom_instituto, $id_periodo]);

      return [
        'success' => true,
        'message' => 'Edades actualizadas correctamente'
      ];
    } catch (PDOException $e) {
      error_log("Error en actualizarEdades: " . $e->getMessage());
      return [
        'success' => false,
        'error' => 'Error al actualizar edades: ' . $e->getMessage()
      ];
    }
  }
}
