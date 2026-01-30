<?php
session_start();
require_once '/xampp/htdocs/final/app/conexion.php';

header('Content-Type: application/json');

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();

  if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'buscar') {
      // Buscar representante por cédula
      $cedula = trim($_POST['cedula'] ?? '');

      if (empty($cedula)) {
        echo json_encode(['success' => false, 'message' => 'Debe ingresar una cédula']);
        exit;
      }

      $sql = "SELECT 
                    r.id_representante, 
                    p.*, 
                    r.id_profesion, 
                    r.ocupacion, 
                    r.lugar_trabajo,
                    d.id_direccion,
                    d.direccion,
                    d.calle,
                    d.casa,
                    pr.id_parroquia,
                    pr.nom_parroquia,
                    pr.id_municipio,
                    m.id_estado,
                    f.profesion
                FROM representantes r
                INNER JOIN personas p ON r.id_persona = p.id_persona
                INNER JOIN profesiones f ON r.id_profesion = f.id_profesion
                INNER JOIN direcciones d ON p.id_direccion = d.id_direccion
                INNER JOIN parroquias pr ON d.id_parroquia = pr.id_parroquia
                INNER JOIN municipios m ON pr.id_municipio = m.id_municipio
                WHERE p.cedula = ? 
                    AND r.estatus = 1 
                    AND p.estatus = 1
                    AND d.estatus = 1
                LIMIT 1";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([$cedula]);
      $representante = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($representante) {
        echo json_encode([
          'success' => true,
          'existe' => true,
          'representante' => [
            'id_representante' => $representante['id_representante'],
            'id_persona' => $representante['id_persona'],
            'cedula' => $representante['cedula'],
            'nombre_completo' => $representante['primer_nombre'] . ' ' .
              $representante['primer_apellido'],
            'primer_nombre' => $representante['primer_nombre'],
            'segundo_nombre' => $representante['segundo_nombre'],
            'primer_apellido' => $representante['primer_apellido'],
            'segundo_apellido' => $representante['segundo_apellido'],
            'telefono' => $representante['telefono'],
            'correo' => $representante['correo'],
            'profesion' => $representante['profesion']
          ]
        ]);
      } else {
        echo json_encode([
          'success' => true,
          'existe' => false,
          'cedula' => $cedula
        ]);
      }
    } elseif ($accion === 'registrar') {
      // Registrar nuevo representante
      require_once '/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php';
      require_once '/xampp/htdocs/final/app/controllers/personas/personas.php';
      require_once '/xampp/htdocs/final/app/controllers/representantes/representantes.php';

      // Verificar si la cédula ya existe en personas
      $personasController = new PersonaController($pdo);
      $personaExistente = $personasController->buscarPorCedula($_POST['cedula_r']);

      if ($personaExistente) {
        // Persona ya existe, verificar si es representante
        $representantesController = new RepresentanteController($pdo);
        $esRepresentante = $representantesController->esRepresentante($personaExistente['id_persona']);

        if ($esRepresentante) {
          echo json_encode([
            'success' => false,
            'message' => 'Esta persona ya está registrada como representante'
          ]);
          exit;
        }

        // Persona existe pero no es representante, creamos la relación
        $id_representante = $representantesController->crearRepresentante($personaExistente['id_persona'], [
          'id_profesion' => $_POST['profesion_r'],
          'ocupacion' => $_POST['ocupacion_r'],
          'lugar_trabajo' => $_POST['lugar_trabajo_r']
        ]);

        echo json_encode([
          'success' => true,
          'id_representante' => $id_representante,
          'id_persona' => $personaExistente['id_persona'],
          'mensaje' => 'Representante registrado exitosamente'
        ]);
      } else {
        // Persona no existe, crear nueva
        $ubicacionController = new UbicacionController($pdo);

        // 1. Crear dirección
        $id_direccion = $ubicacionController->crearDireccion([
          'id_parroquia' => $_POST['parroquia_r'],
          'direccion' => $_POST['direccion_r'],
          'calle' => $_POST['calle_r'],
          'casa' => $_POST['casa_r']
        ]);

        // 2. Crear persona
        $id_persona = $personasController->crearPersona([
          'id_direccion' => $id_direccion,
          'primer_nombre' => $_POST['primer_nombre_r'],
          'segundo_nombre' => $_POST['segundo_nombre_r'],
          'primer_apellido' => $_POST['primer_apellido_r'],
          'segundo_apellido' => $_POST['segundo_apellido_r'],
          'cedula' => $_POST['cedula_r'],
          'telefono' => $_POST['telefono_r'],
          'telefono_hab' => $_POST['telefono_hab_r'],
          'correo' => $_POST['correo_r'],
          'lugar_nac' => $_POST['lugar_nac_r'],
          'fecha_nac' => $_POST['fecha_nac_r'],
          'sexo' => $_POST['sexo_r'],
          'nacionalidad' => $_POST['nacionalidad_r']
        ]);

        // 3. Crear representante
        $representantesController = new RepresentanteController($pdo);
        $id_representante = $representantesController->crearRepresentante($id_persona, [
          'id_profesion' => $_POST['profesion_r'],
          'ocupacion' => $_POST['ocupacion_r'],
          'lugar_trabajo' => $_POST['lugar_trabajo_r']
        ]);

        echo json_encode([
          'success' => true,
          'id_representante' => $id_representante,
          'id_persona' => $id_persona,
          'mensaje' => 'Representante registrado exitosamente'
        ]);
      }
    }
  }
} catch (Exception $e) {
  echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
