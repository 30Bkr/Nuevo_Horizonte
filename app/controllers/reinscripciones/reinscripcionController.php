<?php
include_once("/xampp/htdocs/final/app/conexion.php");

class ReinscripcionController
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  // Validar si un estudiante ya está inscrito en el período activo
  public function estudianteInscritoEnPeriodoActivo($id_estudiante, $id_periodo_activo)
  {
    try {
      $sql = "SELECT COUNT(*) as total 
                    FROM inscripciones 
                    WHERE id_estudiante = ? 
                    AND id_periodo = ? 
                    AND estatus = 1";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_estudiante, $id_periodo_activo]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result['total'] > 0;
    } catch (PDOException $e) {
      error_log("Error verificando inscripción: " . $e->getMessage());
      return false;
    }
  }

  // Realizar la reinscripción
  public function realizarReinscripcion($datos)
  {
    try {
      $this->pdo->beginTransaction();

      // ========== NUEVA VALIDACIÓN: VERIFICAR PERÍODO VIGENTE ==========
      if (isset($datos['id_periodo'])) {
        $id_periodo = $datos['id_periodo'];

        // Verificar que el período esté vigente
        $sql_periodo = "SELECT fecha_ini, fecha_fin FROM periodos WHERE id_periodo = ? AND estatus = 1";
        $stmt_periodo = $this->pdo->prepare($sql_periodo);
        $stmt_periodo->execute([$id_periodo]);
        $periodo = $stmt_periodo->fetch(PDO::FETCH_ASSOC);

        if ($periodo) {
          $fecha_actual = date('Y-m-d');
          $fecha_ini = $periodo['fecha_ini'];
          $fecha_fin = $periodo['fecha_fin'];

          if ($fecha_actual < $fecha_ini) {
            throw new Exception("El período académico no ha iniciado. Inicia el: " . date('d/m/Y', strtotime($fecha_ini)));
          }

          if ($fecha_actual > $fecha_fin) {
            throw new Exception("El período académico ha finalizado. Finalizó el: " . date('d/m/Y', strtotime($fecha_fin)));
          }
        } else {
          throw new Exception("El período académico seleccionado no existe o no está activo");
        }
      }

      // 1. Verificar si el estudiante ya está inscrito en el período
      if ($this->estudianteInscritoEnPeriodoActivo($datos['id_estudiante_existente'], $datos['id_periodo'])) {
        throw new Exception("El estudiante ya está inscrito en este período académico.");
      }

      // 2. Actualizar datos del estudiante
      $this->actualizarDatosEstudiante($datos);

      // 3. Actualizar datos del representante
      $this->actualizarDatosRepresentante($datos);

      // 4. Manejar dirección del estudiante
      $id_direccion_estudiante = $this->manejarDireccionEstudiante($datos);

      // 5. Actualizar patologías y discapacidades
      $this->actualizarPatologiasEstudiante($datos);
      $this->actualizarDiscapacidadesEstudiante($datos);

      // 6. Crear nueva inscripción
      $id_inscripcion = $this->crearInscripcion($datos);

      $this->pdo->commit();
      return [
        'success' => true,
        'message' => 'Reinscripción realizada exitosamente',
        'id_inscripcion' => $id_inscripcion
      ];
    } catch (Exception $e) {
      $this->pdo->rollBack();
      return [
        'success' => false,
        'message' => $e->getMessage()
      ];
    }
  }

  private function actualizarDatosEstudiante($datos)
  {
    // Primero obtener el id_persona del estudiante
    $sql_persona = "SELECT id_persona FROM estudiantes WHERE id_estudiante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$datos['id_estudiante_existente']]);
    $estudiante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if (!$estudiante) {
      throw new Exception("No se encontró el estudiante.");
    }

    $sql = "UPDATE personas SET 
                primer_nombre = ?, 
                segundo_nombre = ?, 
                primer_apellido = ?, 
                segundo_apellido = ?, 
                cedula = ?, 
                telefono = ?, 
                correo = ?, 
                lugar_nac = ?, 
                fecha_nac = ?, 
                sexo = ?, 
                nacionalidad = ?,
                actualizacion = NOW()
                WHERE id_persona = ?";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      $datos['primer_nombre_e'],
      $datos['segundo_nombre_e'] ?? null,
      $datos['primer_apellido_e'],
      $datos['segundo_apellido_e'] ?? null,
      $datos['cedula_e'],
      $datos['telefono_e'] ?? null,
      $datos['correo_e'] ?? null,
      $datos['lugar_nac_e'],
      $datos['fecha_nac_e'],
      $datos['sexo_e'],
      $datos['nacionalidad_e'],
      $estudiante['id_persona']
    ]);
  }

  private function actualizarDatosRepresentante($datos)
  {
    // Primero obtener el id_persona del representante
    $sql_persona = "SELECT id_persona FROM representantes WHERE id_representante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$datos['id_representante_existente']]);
    $representante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if (!$representante) {
      throw new Exception("No se encontró el representante.");
    }

    $sql = "UPDATE personas SET 
                primer_nombre = ?, 
                segundo_nombre = ?, 
                primer_apellido = ?, 
                segundo_apellido = ?, 
                telefono = ?, 
                telefono_hab = ?, 
                correo = ?, 
                lugar_nac = ?, 
                fecha_nac = ?, 
                sexo = ?, 
                nacionalidad = ?,
                actualizacion = NOW()
                WHERE id_persona = ?";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      $datos['primer_nombre_r'],
      $datos['segundo_nombre_r'] ?? null,
      $datos['primer_apellido_r'],
      $datos['segundo_apellido_r'] ?? null,
      $datos['telefono_r'],
      $datos['telefono_hab_r'] ?? null,
      $datos['correo_r'],
      $datos['lugar_nac_r'],
      $datos['fecha_nac_r'],
      $datos['sexo_r'],
      $datos['nacionalidad_r'],
      $representante['id_persona']
    ]);

    // Actualizar datos profesionales del representante
    $sql_repre = "UPDATE representantes SET 
                     ocupacion = ?, 
                     lugar_trabajo = ?, 
                     id_profesion = ?,
                     actualizacion = NOW()
                     WHERE id_representante = ?";

    $stmt_repre = $this->pdo->prepare($sql_repre);
    $stmt_repre->execute([
      $datos['ocupacion_r'],
      $datos['lugar_trabajo_r'] ?? null,
      $datos['profesion_r'],
      $datos['id_representante_existente']
    ]);
  }

  private function manejarDireccionEstudiante($datos)
  {
    if ($datos['juntos'] == '1') {
      // Usar la misma dirección del representante
      return $datos['id_direccion_repre_compartida'];
    } else {
      // Crear nueva dirección para el estudiante
      return $this->crearNuevaDireccion($datos);
    }
  }

  private function crearNuevaDireccion($datos)
  {
    $sql = "INSERT INTO direcciones (
                id_parroquia, direccion, calle, casa, creacion
            ) VALUES (?, ?, ?, ?, NOW())";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      $datos['parroquia_e'],
      $datos['direccion_e'],
      $datos['calle_e'] ?? null,
      $datos['casa_e'] ?? null
    ]);

    $id_nueva_direccion = $this->pdo->lastInsertId();

    // Actualizar la dirección en la persona del estudiante
    $this->actualizarDireccionEstudiante($datos['id_estudiante_existente'], $id_nueva_direccion);

    return $id_nueva_direccion;
  }

  private function actualizarDireccionEstudiante($id_estudiante, $id_direccion)
  {
    // Obtener id_persona del estudiante
    $sql_persona = "SELECT id_persona FROM estudiantes WHERE id_estudiante = ?";
    $stmt_persona = $this->pdo->prepare($sql_persona);
    $stmt_persona->execute([$id_estudiante]);
    $estudiante = $stmt_persona->fetch(PDO::FETCH_ASSOC);

    if ($estudiante) {
      $sql = "UPDATE personas SET id_direccion = ?, actualizacion = NOW() WHERE id_persona = ?";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute([$id_direccion, $estudiante['id_persona']]);
    }
  }

  private function actualizarPatologiasEstudiante($datos)
  {
    // Eliminar patologías actuales
    $sql_delete = "DELETE FROM estudiantes_patologias WHERE id_estudiante = ?";
    $stmt_delete = $this->pdo->prepare($sql_delete);
    $stmt_delete->execute([$datos['id_estudiante_existente']]);

    // Insertar nuevas patologías
    if (isset($datos['patologias'])) {
      $sql_insert = "INSERT INTO estudiantes_patologias (id_estudiante, id_patologia, creacion) VALUES (?, ?, NOW())";
      $stmt_insert = $this->pdo->prepare($sql_insert);

      foreach ($datos['patologias'] as $patologia) {
        if ($patologia != '0' && !empty($patologia)) {
          $stmt_insert->execute([$datos['id_estudiante_existente'], $patologia]);
        }
      }
    }
  }

  private function actualizarDiscapacidadesEstudiante($datos)
  {
    // Eliminar discapacidades actuales
    $sql_delete = "DELETE FROM estudiantes_discapacidades WHERE id_estudiante = ?";
    $stmt_delete = $this->pdo->prepare($sql_delete);
    $stmt_delete->execute([$datos['id_estudiante_existente']]);

    // Insertar nuevas discapacidades
    if (isset($datos['discapacidades'])) {
      $sql_insert = "INSERT INTO estudiantes_discapacidades (id_estudiante, id_discapacidad, creacion) VALUES (?, ?, NOW())";
      $stmt_insert = $this->pdo->prepare($sql_insert);

      foreach ($datos['discapacidades'] as $discapacidad) {
        if ($discapacidad != '0' && !empty($discapacidad)) {
          $stmt_insert->execute([$datos['id_estudiante_existente'], $discapacidad]);
        }
      }
    }
  }

  private function crearInscripcion($datos)
  {
    $sql = "INSERT INTO inscripciones (
                id_estudiante, id_periodo, id_nivel_seccion, id_usuario, 
                fecha_inscripcion, observaciones, creacion
            ) VALUES (?, ?, ?, ?, CURDATE(), ?, NOW())";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
      $datos['id_estudiante_existente'],
      $datos['id_periodo'],
      $datos['id_seccion'], // Asegúrate de que este campo existe
      $_SESSION['id_usuario'] ?? 1, // Asumiendo que hay sesión de usuario
      $datos['observaciones'] ?? null
    ]);

    return $this->pdo->lastInsertId();
  }
}

// Procesar la reinscripción
if ($_POST) {
  try {
    session_start(); // Añadir esto para tener acceso a $_SESSION
    $conexion = new Conexion();
    $pdo = $conexion->conectar();
    $reinscripcionController = new ReinscripcionController($pdo);

    $resultado = $reinscripcionController->realizarReinscripcion($_POST);
    if ($resultado['success']) {
      // REDIRIGIR AL ADMIN DESPUÉS DE ÉXITO
      $_SESSION['mensaje_exito'] = $resultado['message'];
      header("Location: /final/admin/index.php");
      exit();
    } else {
      // Redirigir de vuelta con error
      $_SESSION['mensaje_error'] = $resultado['message'];
      header("Location: " . $_SERVER['HTTP_REFERER']);
      exit();
    }
  } catch (Exception $e) {
    $_SESSION['mensaje_error'] = 'Error: ' . $e->getMessage();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
  }
}
