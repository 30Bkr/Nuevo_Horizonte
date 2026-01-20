<?php
// admin/estudiantes/estudiantes_edit.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Establecer título de página
$_SESSION['page_title'] = 'Editar Estudiante';

// Incluir archivos necesarios
require_once '/xampp/htdocs/final/app/conexion.php';
require_once '/xampp/htdocs/final/app/controllers/estudiantes/EstudianteController.php';
require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/global/check_permissions.php';

// Verificar permisos (ajusta según tus necesidades)
if (!PermissionManager::canViewAny(['admin/estudiantes/estudiantes_list.php'])) {
  // Incluir Notification para mostrar mensaje
  require_once '/xampp/htdocs/final/global/notifications.php';
  Notification::set("No tienes permisos para acceder a esta sección", "error");
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

$id_estudiante = $_GET['id'] ?? '';

if (empty($id_estudiante) || !is_numeric($id_estudiante)) {
  require_once '/xampp/htdocs/final/global/notifications.php';
  Notification::set("ID de estudiante inválido", "error");
  header("Location: estudiantes_list.php");
  exit();
}

try {
  $database = new Conexion();
  $db = $database->conectar();

  if (!$db) {
    throw new Exception("Error de conexión a la base de datos");
  }

  $controller = new EstudianteController($db);

  // Obtener datos del estudiante
  if (!$controller->obtener($id_estudiante)) {
    throw new Exception("Estudiante no encontrado");
  }

  $estudiante = $controller->estudiante;

  // Obtener patologías y discapacidades seleccionadas
  $patologias_seleccionadas = $controller->estudiante->obtenerPatologiasEstudiante($id_estudiante);
  $discapacidades_seleccionadas = $controller->estudiante->obtenerDiscapacidadesEstudiante($id_estudiante);

  // Procesar formulario de actualización
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($controller->actualizar($id_estudiante, $_POST)) {
      require_once '/xampp/htdocs/final/global/notifications.php';
      Notification::set("Estudiante actualizado exitosamente", "success");
      header("Location: estudiantes_list.php");
      exit();
    } else {
      require_once '/xampp/htdocs/final/global/notifications.php';
      Notification::set("Error al actualizar el estudiante", "error");
    }
  }
} catch (Exception $e) {
  require_once '/xampp/htdocs/final/global/notifications.php';
  Notification::set($e->getMessage(), "error");
  header("Location: estudiantes_list.php");
  exit();
}

// Obtener datos para los selects
try {
  $database = new Conexion();
  $db = $database->conectar();
  if ($db) {
    $controller_data = new EstudianteController($db);

    // Obtener estados
    $estados = $controller_data->obtenerEstados();

    // Obtener parroquias
    $parroquias = $controller_data->obtenerParroquias();

    // Obtener patologías
    $patologias = $controller_data->obtenerPatologias();

    // Obtener discapacidades
    $discapacidades = $controller_data->obtenerDiscapacidades();

    // Obtener parentescos
    $parentescos = $controller_data->obtenerParentescos();

    // Obtener profesiones - CORREGIDO: asegurar que se ejecute la consulta
    $profesiones = $controller_data->obtenerProfesiones();
  }
} catch (Exception $e) {
  // Error al cargar datos adicionales, pero continuamos
}

// Obtener datos de ubicación para el estudiante
$id_estado_estudiante = null;
$id_municipio_estudiante = null;
if ($estudiante->id_parroquia) {
  try {
    $query_ubicacion = "SELECT p.id_parroquia, p.id_municipio, m.id_estado 
                           FROM parroquias p 
                           INNER JOIN municipios m ON p.id_municipio = m.id_municipio 
                           WHERE p.id_parroquia = ?";
    $stmt_ubicacion = $db->prepare($query_ubicacion);
    $stmt_ubicacion->bindParam(1, $estudiante->id_parroquia);
    $stmt_ubicacion->execute();

    if ($stmt_ubicacion->rowCount() > 0) {
      $ubicacion = $stmt_ubicacion->fetch(PDO::FETCH_ASSOC);
      $id_estado_estudiante = $ubicacion['id_estado'];
      $id_municipio_estudiante = $ubicacion['id_municipio'];

      // Obtener municipios para el estado del estudiante
      $municipios_estudiante = $controller_data->obtenerMunicipiosPorEstado($id_estado_estudiante);

      // Obtener parroquias para el municipio del estudiante
      $parroquias_estudiante = $controller_data->obtenerParroquiasPorMunicipio($id_municipio_estudiante);
    }
  } catch (Exception $e) {
    // Error al obtener datos de ubicación
  }
}

// Incluir layout1.php al inicio
require_once '/xampp/htdocs/final/layout/layaout1.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
  <!-- Content Header -->
  <div class="content-header">
    <?php
    // Mostrar notificaciones
    require_once '/xampp/htdocs/final/global/notifications.php';
    Notification::show();
    ?>
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Editar Estudiante</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="estudiantes_list.php">Estudiantes</a></li>
            <li class="breadcrumb-item active">Editar</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Editar Datos del Estudiante</h3>
            </div>
            <form method="POST" id="formEstudiante">
              <div class="card-body">
                <!-- Datos Personales del Estudiante -->
                <h5 class="text-primary mb-3">
                  <i class="fas fa-user-graduate"></i> Información Personal del Estudiante
                </h5>
                <div class="row">
                  <!-- Nacionalidad como lista desplegable -->
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="nacionalidad">Nacionalidad <span class="text-danger">* (Obligatorio)</span></label>
                      <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                        <option value="">Seleccione...</option>
                        <option value="Venezolano" <?php echo ($estudiante->nacionalidad ?? '') == 'VENEZOLANO' ? 'selected' : ''; ?>>Venezolano</option>
                        <option value="Extranjero" <?php echo ($estudiante->nacionalidad ?? '') == 'EXTRANJERO' ? 'selected' : ''; ?>>Extranjero</option>
                      </select>
                    </div>
                  </div>

                  <!-- Cédula editable -->
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="cedula">Cédula <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="cedula" name="cedula"
                        value="<?php echo htmlspecialchars($estudiante->cedula ?? ''); ?>" required
                        maxlength="20">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>

                  <!-- Fecha de nacimiento -->
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                        value="<?php echo $estudiante->fecha_nac ?? ''; ?>" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="primer_nombre">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="primer_nombre" name="primer_nombre"
                        value="<?php echo htmlspecialchars($estudiante->primer_nombre ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="segundo_nombre">Segundo Nombre</label>
                      <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                        value="<?php echo htmlspecialchars($estudiante->segundo_nombre ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="primer_apellido">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                        value="<?php echo htmlspecialchars($estudiante->primer_apellido ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="segundo_apellido">Segundo Apellido</label>
                      <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                        value="<?php echo htmlspecialchars($estudiante->segundo_apellido ?? ''); ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="sexo">Sexo <span class="text-danger">* (Obligatorio)</span></label>
                      <select class="form-control" id="sexo" name="sexo" required>
                        <option value="">Seleccione...</option>
                        <option value="Masculino" <?php echo ($estudiante->sexo ?? 'MASCULINO') == 'MASCULINO' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo ($estudiante->sexo ?? 'FEMENINO') == 'FEMENINO' ? 'selected' : ''; ?>>Femenino</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="lugar_nac">Lugar de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                        value="<?php echo htmlspecialchars($estudiante->lugar_nac ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="telefono">Teléfono Móvil</label>
                      <input type="text" class="form-control" id="telefono" name="telefono"
                        value="<?php echo htmlspecialchars($estudiante->telefono ?? ''); ?>" maxlength="11">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="telefono_hab">Teléfono Habitación</label>
                      <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                        value="<?php echo htmlspecialchars($estudiante->telefono_hab ?? ''); ?>" maxlength="11">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="correo">Correo Electrónico</label>
                      <input type="email" class="form-control" id="correo" name="correo"
                        value="<?php echo htmlspecialchars($estudiante->correo ?? ''); ?>">
                      <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                    </div>
                  </div>
                </div>

                <!-- Dirección del Estudiante - CON SCROLL -->
                <h5 class="text-primary mb-3 mt-4">
                  <i class="fas fa-map-marker-alt"></i> Dirección del Estudiante
                </h5>

                <!-- Campo para controlar si comparte dirección con el representante -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="comparte_direccion">¿El estudiante vive en la misma dirección del representante? <span class="text-danger">* (Obligatorio)</span></label>
                      <select class="form-control" id="comparte_direccion" name="comparte_direccion" required>
                        <option value="">Seleccione...</option>
                        <option value="1" <?php echo ($estudiante->comparte_direccion ?? '1') == '1' ? 'selected' : ''; ?>>Sí</option>
                        <option value="0" <?php echo ($estudiante->comparte_direccion ?? '1') == '0' ? 'selected' : ''; ?>>No</option>
                      </select>
                      <small class="form-text text-muted">
                        Si selecciona "No", podrá ingresar una dirección diferente para el estudiante.
                      </small>
                    </div>
                  </div>
                </div>

                <!-- Campos de dirección del estudiante - CON SCROLL -->
                <div id="direccion_estudiante" style="<?php echo ($estudiante->comparte_direccion ?? '1') == '1' ? 'display: none;' : ''; ?>">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="estado_e">Estado</label>
                        <select class="form-control select2 select-direccion large-select" id="estado_e" name="estado_e" style="width: 100%;"
                          data-placeholder="Seleccione un estado...">
                          <option value=""></option>
                          <?php
                          if (isset($estados) && $estados) {
                            // Reiniciar el puntero si es necesario
                            if (method_exists($estados, 'fetch')) {
                              while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($id_estado_estudiante ?? '') == $estado['id_estado'] ? 'selected' : '';
                                echo "<option value='{$estado['id_estado']}' {$selected}>{$estado['nom_estado']}</option>";
                              }
                            } else {
                              echo "<option value=''>Error: No se pudieron cargar los estados</option>";
                            }
                          } else {
                            echo "<option value=''>No hay estados disponibles</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="municipio_e">Municipio</label>
                        <select class="form-control select2 select-direccion large-select" id="municipio_e" name="municipio_e" style="width: 100%;"
                          data-placeholder="Primero seleccione un estado" <?php echo !$id_estado_estudiante ? 'disabled' : ''; ?>>
                          <option value=""></option>
                          <?php
                          if (isset($municipios_estudiante) && $municipios_estudiante) {
                            while ($municipio = $municipios_estudiante->fetch(PDO::FETCH_ASSOC)) {
                              $selected = ($id_municipio_estudiante ?? '') == $municipio['id_municipio'] ? 'selected' : '';
                              echo "<option value='{$municipio['id_municipio']}' {$selected}>{$municipio['nom_municipio']}</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="parroquia_e">Parroquia</label>
                        <select class="form-control select2 select-direccion large-select" id="parroquia_e" name="id_parroquia" style="width: 100%;"
                          data-placeholder="Primero seleccione un municipio" <?php echo !$id_municipio_estudiante ? 'disabled' : ''; ?>>
                          <option value=""></option>
                          <?php
                          if (isset($parroquias_estudiante) && $parroquias_estudiante) {
                            while ($parroquia = $parroquias_estudiante->fetch(PDO::FETCH_ASSOC)) {
                              $selected = ($estudiante->id_parroquia ?? '') == $parroquia['id_parroquia'] ? 'selected' : '';
                              echo "<option value='{$parroquia['id_parroquia']}' {$selected}>{$parroquia['nom_parroquia']}</option>";
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="direccion_e">Dirección Completa</label>
                        <input type="text" class="form-control" id="direccion_e" name="direccion"
                          value="<?php echo htmlspecialchars($estudiante->direccion ?? ''); ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="calle_e">Calle/Avenida</label>
                        <input type="text" class="form-control" id="calle_e" name="calle"
                          value="<?php echo htmlspecialchars($estudiante->calle ?? ''); ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="casa_e">Casa/Edificio</label>
                        <input type="text" class="form-control" id="casa_e" name="casa"
                          value="<?php echo htmlspecialchars($estudiante->casa ?? ''); ?>">
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Sección de Salud del Estudiante -->
                <h5 class="text-primary mb-3 mt-4">
                  <i class="fas fa-heartbeat"></i> Información de Salud del Estudiante
                </h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Patologías/Alergias</label>

                      <!-- Contenedor para los selects dinámicos -->
                      <div id="contenedor-patologias">
                        <!-- Select Principal -->
                        <div class="mb-2 patologia-item">
                          <select name="patologias[]" class="form-control select-patologia">
                            <option value="">Seleccione una patología...</option>
                            <option value="0">Ninguna</option>
                            <?php
                            // Cargar patologías desde la base de datos
                            if (isset($patologias) && $patologias) {
                              if (method_exists($patologias, 'fetch')) {
                                while ($patologia = $patologias->fetch(PDO::FETCH_ASSOC)) {
                                  $selected = in_array($patologia['id_patologia'], $patologias_seleccionadas) ? 'selected' : '';
                                  echo "<option value='{$patologia['id_patologia']}' {$selected}>{$patologia['nom_patologia']}</option>";
                                }
                              }
                            } else {
                              echo "<option value=''>No hay patologías registradas</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- Botón para agregar más patologías -->
                      <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-patologia">
                          <i class="fas fa-plus"></i> Agregar otra patología
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Discapacidades</label>

                      <!-- Contenedor para los selects dinámicos -->
                      <div id="contenedor-discapacidades">
                        <!-- Select Principal -->
                        <div class="mb-2 discapacidad-item">
                          <select name="discapacidades[]" class="form-control select-discapacidad">
                            <option value="">Seleccione una discapacidad...</option>
                            <option value="0">Ninguna</option>
                            <?php
                            // Cargar discapacidades desde la base de datos
                            if (isset($discapacidades) && $discapacidades) {
                              if (method_exists($discapacidades, 'fetch')) {
                                while ($discapacidad = $discapacidades->fetch(PDO::FETCH_ASSOC)) {
                                  $selected = in_array($discapacidad['id_discapacidad'], $discapacidades_seleccionadas) ? 'selected' : '';
                                  echo "<option value='{$discapacidad['id_discapacidad']}' {$selected}>{$discapacidad['nom_discapacidad']}</option>";
                                }
                              }
                            } else {
                              echo "<option value=''>No hay discapacidades registradas</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </div>

                      <!-- Botón para agregar más discapacidades -->
                      <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btn-agregar-discapacidad">
                          <i class="fas fa-plus"></i> Agregar otra discapacidad
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Datos del Representante -->
                <h5 class="text-primary mb-3 mt-4">
                  <i class="fas fa-user-tie"></i> Datos del Representante
                </h5>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="primer_nombre_rep">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="primer_nombre_rep" name="primer_nombre_rep"
                        value="<?php echo htmlspecialchars($estudiante->primer_nombre_rep ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="segundo_nombre_rep">Segundo Nombre</label>
                      <input type="text" class="form-control" id="segundo_nombre_rep" name="segundo_nombre_rep"
                        value="<?php echo htmlspecialchars($estudiante->segundo_nombre_rep ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="primer_apellido_rep">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="primer_apellido_rep" name="primer_apellido_rep"
                        value="<?php echo htmlspecialchars($estudiante->primer_apellido_rep ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="segundo_apellido_rep">Segundo Apellido</label>
                      <input type="text" class="form-control" id="segundo_apellido_rep" name="segundo_apellido_rep"
                        value="<?php echo htmlspecialchars($estudiante->segundo_apellido_rep ?? ''); ?>">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="cedula_rep">Cédula del Representante <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="cedula_rep" name="cedula_rep"
                        value="<?php echo htmlspecialchars($estudiante->cedula_rep ?? ''); ?>" required
                        maxlength="20">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="id_parentesco">Parentesco <span class="text-danger">* (Obligatorio)</span></label>
                      <select class="form-control" id="id_parentesco" name="id_parentesco" required>
                        <option value="">Seleccione...</option>
                        <?php
                        if (isset($parentescos) && $parentescos) {
                          if (method_exists($parentescos, 'fetch')) {
                            while ($parentesco = $parentescos->fetch(PDO::FETCH_ASSOC)) {
                              $selected = ($estudiante->id_parentesco ?? '') == $parentesco['id_parentesco'] ? 'selected' : '';
                              echo "<option value='{$parentesco['id_parentesco']}' {$selected}>{$parentesco['parentesco']}</option>";
                            }
                          }
                        } else {
                          echo "<option value=''>Error al cargar parentescos</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group campo-obligatorio">
                      <label for="telefono_rep">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="telefono_rep" name="telefono_rep"
                        value="<?php echo htmlspecialchars($estudiante->telefono_rep ?? ''); ?>" required maxlength="11">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono_hab_rep">Teléfono Habitación</label>
                      <input type="text" class="form-control" id="telefono_hab_rep" name="telefono_hab_rep"
                        value="<?php echo htmlspecialchars($estudiante->telefono_hab_rep ?? ''); ?>" maxlength="11">
                      <small class="form-text text-muted">Solo se permiten números</small>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="correo_rep">Correo Electrónico <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="email" class="form-control" id="correo_rep" name="correo_rep"
                        value="<?php echo htmlspecialchars($estudiante->correo_rep ?? ''); ?>" required>
                      <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="id_profesion_rep">Profesión <span class="text-danger">* (Obligatorio)</span></label>
                      <select class="form-control select2 select-profesion large-select" id="id_profesion_rep" name="id_profesion_rep" style="width: 100%;" required
                        data-placeholder="Seleccione una profesión...">
                        <option value=""></option>
                        <?php
                        if (isset($profesiones) && $profesiones) {
                          // Asegurar que las profesiones sean un array o objeto iterable
                          if (is_object($profesiones) && method_exists($profesiones, 'fetch')) {
                            while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                              $selected = ($estudiante->id_profesion_rep ?? '') == $profesion['id_profesion'] ? 'selected' : '';
                              echo "<option value='{$profesion['id_profesion']}' {$selected}>{$profesion['profesion']}</option>";
                            }
                          } elseif (is_array($profesiones)) {
                            foreach ($profesiones as $profesion) {
                              $selected = ($estudiante->id_profesion_rep ?? '') == $profesion['id_profesion'] ? 'selected' : '';
                              echo "<option value='{$profesion['id_profesion']}' {$selected}>{$profesion['profesion']}</option>";
                            }
                          } else {
                            echo "<option value=''>No se pudieron cargar las profesiones</option>";
                          }
                        } else {
                          echo "<option value=''>No hay profesiones disponibles</option>";
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group campo-obligatorio">
                      <label for="ocupacion_rep">Ocupación <span class="text-danger">* (Obligatorio)</span></label>
                      <input type="text" class="form-control" id="ocupacion_rep" name="ocupacion_rep"
                        value="<?php echo htmlspecialchars($estudiante->ocupacion_rep ?? ''); ?>" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="lugar_trabajo_rep">Lugar de Trabajo</label>
                      <input type="text" class="form-control" id="lugar_trabajo_rep" name="lugar_trabajo_rep"
                        value="<?php echo htmlspecialchars($estudiante->lugar_trabajo_rep ?? ''); ?>">
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-warning">
                  <i class="fas fa-save"></i> Actualizar Estudiante
                </button>
                <a href="estudiantes_list.php" class="btn btn-default">
                  <i class="fas fa-arrow-left"></i> Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<style>
  .is-invalid {
    border-color: #dc3545 !important;
  }

  .text-danger {
    color: #dc3545 !important;
    font-weight: bold;
  }

  .form-group label {
    font-weight: 500;
  }

  .campo-obligatorio {
    border-left: 3px solid #dc3545;
    padding-left: 10px;
  }

  .select2-container--bootstrap4 .select2-selection--multiple {
    min-height: 38px;
  }

  .select2-container--bootstrap4 .select2-selection--single {
    height: 38px;
  }

  .patologia-item,
  .discapacidad-item {
    transition: all 0.3s ease;
  }

  .btn-eliminar-patologia,
  .btn-eliminar-discapacidad {
    opacity: 0.7;
    transition: opacity 0.3s ease;
  }

  .btn-eliminar-patologia:hover,
  .btn-eliminar-discapacidad:hover {
    opacity: 1;
  }

  .select-patologia,
  .select-discapacidad {
    min-width: 200px;
  }

  .bg-light {
    background-color: #f8f9fa !important;
  }

  /* Estilos mejorados para selects CON SCROLL */
  .select2-container--bootstrap4 .select2-selection {
    height: 38px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
  }

  .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: 36px !important;
    padding-left: 12px !important;
  }

  .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
  }

  .select2-container--bootstrap4 .select2-dropdown {
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
  }

  .select2-container--bootstrap4 .select2-results__option {
    padding: 8px 12px !important;
  }

  .select2-container--bootstrap4 .select2-results__option--highlighted {
    background-color: #007bff !important;
    color: white !important;
  }

  /* Asegurar que el dropdown se muestre correctamente */
  .select2-container--open .select2-dropdown {
    z-index: 9999 !important;
  }

  /* Estilos para selects deshabilitados */
  .select2-container--bootstrap4 .select2-selection--single[aria-disabled="true"] {
    background-color: #e9ecef !important;
    opacity: 1 !important;
  }

  /* SCROLL PERSONALIZADO PARA SELECTS GRANDES */
  .select2-container--bootstrap4 .select2-results {
    max-height: 200px !important;
    overflow-y: auto !important;
  }

  /* Scroll personalizado para Webkit (Chrome, Safari, Edge) */
  .select2-container--bootstrap4 .select2-results::-webkit-scrollbar {
    width: 8px;
  }

  .select2-container--bootstrap4 .select2-results::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  .select2-container--bootstrap4 .select2-results::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
  }

  .select2-container--bootstrap4 .select2-results::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  /* Scroll para Firefox */
  .select2-container--bootstrap4 .select2-results {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
  }

  /* Estilos específicos para selects de dirección y profesiones con más altura */
  .select-direccion .select2-results,
  .select-profesion .select2-results {
    max-height: 250px !important;
  }

  /* Búsqueda en selects grandes */
  .select2-container--bootstrap4 .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da !important;
    border-radius: 0.25rem !important;
    padding: 6px 12px !important;
  }

  /* Mejorar el contenedor del dropdown para selects grandes */
  .select2-container--bootstrap4 .select2-dropdown {
    min-width: 300px !important;
  }

  /* Estilos para selects con muchos elementos */
  .large-select .select2-results {
    max-height: 300px !important;
  }
</style>

<!-- Cargar jQuery si no está cargado -->
<script>
if (typeof jQuery === 'undefined') {
    document.write('<script src="/final/public/plugins/jquery/jquery.min.js"><\/script>');
    console.log('jQuery cargado mediante document.write');
}
</script>

<script>

  $(function() {
    // Inicializar Select2 para todos los selects CON SCROLL MEJORADO
    $('.select2').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('body'),
      placeholder: function() {
        return $(this).data('placeholder') || 'Seleccione...';
      },
      allowClear: true,
      language: {
        noResults: function() {
          return "No se encontraron resultados";
        },
        searching: function() {
          return "Buscando...";
        }
      },
      // Configuración para mejorar el scroll
      dropdownCssClass: "scrollable-dropdown",
      scrollAfterSelect: true
    });

    // Configuración ESPECÍFICA para selects grandes (dirección y profesiones)
    $('.large-select').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $('body'),
      placeholder: function() {
        return $(this).data('placeholder') || 'Seleccione...';
      },
      allowClear: true,
      language: {
        noResults: function() {
          return "No se encontraron resultados";
        },
        searching: function() {
          return "Buscando...";
        }
      },
      // Configuraciones específicas para lists grandes
      dropdownCssClass: "large-dropdown",
      scrollAfterSelect: true,
      // Limitar altura máxima del dropdown
      dropdownAutoWidth: false,
      // Habilitar búsqueda para mejor manejo de muchas opciones
      minimumResultsForSearch: 3
    });

    // Función para convertir texto a mayúsculas
    function convertirMayusculas(elemento) {
      elemento.value = elemento.value.toUpperCase();
    }

    // Aplicar conversión a mayúsculas en tiempo real para todos los inputs de texto editables
    $('input[type="text"]:not([readonly])').on('input', function() {
      convertirMayusculas(this);
    });

    // Solo letras (para nombres, apellidos, lugar de nacimiento, dirección, calle, casa, ocupación, lugar de trabajo)
    $('#primer_nombre, #segundo_nombre, #primer_apellido, #segundo_apellido, #lugar_nac, #direccion_e, #calle_e, #primer_nombre_rep, #segundo_nombre_rep, #primer_apellido_rep, #segundo_apellido_rep, #ocupacion_rep, #lugar_trabajo_rep').on('input', function() {
      this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
      convertirMayusculas(this);
    });

    // Validación específica para casa/apto (permite letras, números y caracteres especiales comunes)
    $('#casa_e').on('input', function() {
      // Permitir letras, números, guiones, #, y espacios
      this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-#]/g, '');
      convertirMayusculas(this);
    });

    // Solo números (para teléfonos y cédulas)
    $('#cedula, #cedula_rep, #telefono, #telefono_hab, #telefono_rep, #telefono_hab_rep').on('input', function() {
      this.value = this.value.replace(/\D/g, '');
    });

    // Validación de correo electrónico
    $('#correo, #correo_rep').on('blur', function() {
      const email = this.value;
      if (email && !isValidEmail(email)) {
        alert('Por favor, ingrese un correo electrónico válido (debe contener @ y dominio)');
        this.focus();
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });

    // Función para validar formato de email
    function isValidEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    // Validación en tiempo real para campos obligatorios
    $('input[required], select[required]').on('blur', function() {
      const valor = $(this).val();
      if (!valor) {
        $(this).addClass('is-invalid');
      } else {
        $(this).removeClass('is-invalid');
      }
    });

    // Manejo de dirección compartida
    $('#comparte_direccion').on('change', function() {
      const comparteDireccion = $(this).val();
      const direccionEstudiante = $('#direccion_estudiante');

      if (comparteDireccion === '0') {
        direccionEstudiante.slideDown();
        // Habilitar selects de dirección
        $('#estado_e, #municipio_e, #parroquia_e').prop('disabled', false).trigger('change');
        // Re-inicializar Select2 después de habilitar
        $('#estado_e, #municipio_e, #parroquia_e').select2({
          theme: 'bootstrap4',
          width: '100%',
          dropdownParent: $('body')
        });
      } else {
        direccionEstudiante.slideUp();
        // Deshabilitar selects de dirección
        $('#estado_e, #municipio_e, #parroquia_e').prop('disabled', true).trigger('change');
        // Limpiar campos
        $('#estado_e').val('').trigger('change');
        $('#municipio_e').val('').trigger('change');
        $('#parroquia_e').val('').trigger('change');
        $('#direccion_e, #calle_e, #casa_e').val('');
      }
    });

    // Cargar municipios cuando cambie el estado
    $('#estado_e').on('change', function() {
      const estadoId = $(this).val();
      const municipioSelect = $('#municipio_e');
      const parroquiaSelect = $('#parroquia_e');

      if (estadoId) {
        municipioSelect.prop('disabled', false);
        parroquiaSelect.prop('disabled', true);
        parroquiaSelect.html('<option value=""></option>').trigger('change');

        // Re-inicializar Select2 después de habilitar
        municipioSelect.select2({
          theme: 'bootstrap4',
          width: '100%',
          dropdownParent: $('body'),
          placeholder: 'Seleccionar Municipio'
        });

        // Cargar municipios via AJAX
        $.ajax({
          url: '<?= URL; ?>/app/controllers/ubicaciones/municipios.php',
          method: 'POST',
          data: {
            estado_id: estadoId
          },
          dataType: 'json',
          success: function(data) {
            municipioSelect.html('<option value=""></option>');
            if (data && data.length > 0) {
              $.each(data, function(index, municipio) {
                municipioSelect.append('<option value="' + municipio.id_municipio + '">' + municipio.nom_municipio + '</option>');
              });
            } else {
              municipioSelect.append('<option value="">No hay municipios disponibles</option>');
            }
            municipioSelect.trigger('change');
          },
          error: function() {
            alert('Error al cargar municipios');
            municipioSelect.html('<option value="">Error al cargar</option>').trigger('change');
          }
        });
      } else {
        municipioSelect.prop('disabled', true);
        parroquiaSelect.prop('disabled', true);
        municipioSelect.html('<option value=""></option>').trigger('change');
        parroquiaSelect.html('<option value=""></option>').trigger('change');
      }
    });

    // Cargar parroquias cuando cambie el municipio
    $('#municipio_e').on('change', function() {
      const municipioId = $(this).val();
      const parroquiaSelect = $('#parroquia_e');

      if (municipioId) {
        parroquiaSelect.prop('disabled', false);

        // Re-inicializar Select2 después de habilitar
        parroquiaSelect.select2({
          theme: 'bootstrap4',
          width: '100%',
          dropdownParent: $('body'),
          placeholder: 'Seleccionar Parroquia'
        });

        // Cargar parroquias via AJAX
        $.ajax({
          url: '<?= URL; ?>/app/controllers/ubicaciones/parroquias.php',
          method: 'POST',
          data: {
            municipio_id: municipioId
          },
          dataType: 'json',
          success: function(data) {
            parroquiaSelect.html('<option value=""></option>');
            if (data && data.length > 0) {
              $.each(data, function(index, parroquia) {
                parroquiaSelect.append('<option value="' + parroquia.id_parroquia + '">' + parroquia.nom_parroquia + '</option>');
              });
            } else {
              parroquiaSelect.append('<option value="">No hay parroquias disponibles</option>');
            }
            parroquiaSelect.trigger('change');
          },
          error: function() {
            alert('Error al cargar parroquias');
            parroquiaSelect.html('<option value="">Error al cargar</option>').trigger('change');
          }
        });
      } else {
        parroquiaSelect.prop('disabled', true);
        parroquiaSelect.html('<option value=""></option>').trigger('change');
      }
    });

    // Validación del formulario antes de enviar
    $('#formEstudiante').on('submit', function(e) {
      let isValid = true;
      let mensajesError = [];

      // Campos obligatorios del estudiante
      const camposObligatoriosEstudiante = {
        'nacionalidad': 'Nacionalidad',
        'cedula': 'Cédula',
        'fecha_nac': 'Fecha de Nacimiento',
        'primer_nombre': 'Primer Nombre',
        'primer_apellido': 'Primer Apellido',
        'sexo': 'Sexo',
        'lugar_nac': 'Lugar de Nacimiento',
        'comparte_direccion': 'Comparte dirección con representante'
      };

      // Campos obligatorios del representante
      const camposObligatoriosRepresentante = {
        'primer_nombre_rep': 'Primer Nombre del Representante',
        'primer_apellido_rep': 'Primer Apellido del Representante',
        'cedula_rep': 'Cédula del Representante',
        'id_parentesco': 'Parentesco',
        'telefono_rep': 'Teléfono Móvil del Representante',
        'correo_rep': 'Correo Electrónico del Representante',
        'id_profesion_rep': 'Profesión del Representante',
        'ocupacion_rep': 'Ocupación del Representante'
      };

      // Validar campos obligatorios del estudiante
      for (const [campo, nombre] of Object.entries(camposObligatoriosEstudiante)) {
        const valor = campo.startsWith('id_') ?
          $(`#${campo}`).val() :
          $(`#${campo}`).val().trim();

        if (!valor) {
          mensajesError.push(`El campo "${nombre}" es obligatorio`);
          $(`#${campo}`).addClass('is-invalid');
          isValid = false;
        } else {
          $(`#${campo}`).removeClass('is-invalid');
        }
      }

      // Validar campos obligatorios del representante
      for (const [campo, nombre] of Object.entries(camposObligatoriosRepresentante)) {
        const valor = campo.startsWith('id_') ?
          $(`#${campo}`).val() :
          $(`#${campo}`).val().trim();

        if (!valor) {
          mensajesError.push(`El campo "${nombre}" es obligatorio`);
          $(`#${campo}`).addClass('is-invalid');
          isValid = false;
        } else {
          $(`#${campo}`).removeClass('is-invalid');
        }
      }

      // Validar teléfonos (solo números)
      const telefono = $('#telefono').val();
      const telefonoHab = $('#telefono_hab').val();
      const telefonoRep = $('#telefono_rep').val();
      const telefonoHabRep = $('#telefono_hab_rep').val();

      if (telefono && !/^\d+$/.test(telefono)) {
        mensajesError.push('El teléfono móvil del estudiante debe contener solo números');
        isValid = false;
      }

      if (telefonoHab && !/^\d+$/.test(telefonoHab)) {
        mensajesError.push('El teléfono de habitación del estudiante debe contener solo números');
        isValid = false;
      }

      if (telefonoRep && !/^\d+$/.test(telefonoRep)) {
        mensajesError.push('El teléfono móvil del representante debe contener solo números');
        isValid = false;
      }

      if (telefonoHabRep && !/^\d+$/.test(telefonoHabRep)) {
        mensajesError.push('El teléfono de habitación del representante debe contener solo números');
        isValid = false;
      }

      // Validar correos electrónicos
      const correo = $('#correo').val();
      const correoRep = $('#correo_rep').val();

      if (correo && !isValidEmail(correo)) {
        mensajesError.push('Por favor, ingrese un correo electrónico válido para el estudiante (formato: usuario@dominio.com)');
        isValid = false;
      }

      if (correoRep && !isValidEmail(correoRep)) {
        mensajesError.push('Por favor, ingrese un correo electrónico válido para el representante (formato: usuario@dominio.com)');
        isValid = false;
      }

      // Validar fecha de nacimiento (no puede ser futura)
      const fechaNac = $('#fecha_nac').val();
      if (fechaNac) {
        const hoy = new Date().toISOString().split('T')[0];
        if (fechaNac > hoy) {
          mensajesError.push('La fecha de nacimiento no puede ser futura');
          isValid = false;
        }
      }

      // Mostrar errores si los hay
      if (!isValid) {
        e.preventDefault();
        alert('Por favor, corrija los siguientes errores:\n\n• ' + mensajesError.join('\n• '));

        // Scroll al primer error
        $('.is-invalid').first().focus();
      }
    });

    // Limpiar validación cuando el usuario empiece a escribir
    $('input, select').on('input change', function() {
      $(this).removeClass('is-invalid');
    });
  });
</script>

<!-- Manejo de patologias con select adicionales -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const contenedorPatologias = document.getElementById('contenedor-patologias');
    const btnAgregarPatologia = document.getElementById('btn-agregar-patologia');

    // Obtener las patologías desde el primer select
    function obtenerOpcionesPatologias() {
      const primerSelect = document.querySelector('.select-patologia');
      if (!primerSelect) return '';

      const opciones = Array.from(primerSelect.options)
        .filter(option => option.value !== '')
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join('');

      return opciones;
    }

    // Función para crear un nuevo select de patología
    function crearSelectPatologia() {
      const opciones = obtenerOpcionesPatologias();

      const div = document.createElement('div');
      div.className = 'mb-2 patologia-item d-flex align-items-center';

      div.innerHTML = `
                <select name="patologias[]" class="form-control select-patologia me-2">
                    <option value="">Seleccione una patología...</option>
                    ${opciones}
                </select>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-patologia">
                    <i class="fas fa-times"></i>
                </button>
            `;

      return div;
    }

    // Agregar nuevo select
    btnAgregarPatologia.addEventListener('click', function() {
      const nuevoSelect = crearSelectPatologia();
      contenedorPatologias.appendChild(nuevoSelect);

      // Agregar evento al botón eliminar
      const btnEliminar = nuevoSelect.querySelector('.btn-eliminar-patologia');
      btnEliminar.addEventListener('click', function() {
        nuevoSelect.remove();
      });
    });

    // Eliminar select (evento delegado)
    contenedorPatologias.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-eliminar-patologia') ||
        e.target.closest('.btn-eliminar-patologia')) {
        const btn = e.target.classList.contains('btn-eliminar-patologia') ?
          e.target : e.target.closest('.btn-eliminar-patologia');
        btn.closest('.patologia-item').remove();
      }
    });
  });
</script>

<!-- Manejo de discapacidades con select adicionales -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const contenedorDiscapacidades = document.getElementById('contenedor-discapacidades');
    const btnAgregarDiscapacidad = document.getElementById('btn-agregar-discapacidad');

    // Obtener las opciones de discapacidades desde el primer select
    function obtenerOpcionesDiscapacidades() {
      const primerSelect = document.querySelector('.select-discapacidad');
      if (!primerSelect) return '';

      const opciones = Array.from(primerSelect.options)
        .filter(option => option.value !== '')
        .map(option => `<option value="${option.value}">${option.text}</option>`)
        .join('');

      return opciones;
    }

    // Función para crear un nuevo select de discapacidad
    function crearSelectDiscapacidad() {
      const opciones = obtenerOpcionesDiscapacidades();

      const div = document.createElement('div');
      div.className = 'mb-2 discapacidad-item d-flex align-items-center';

      div.innerHTML = `
                <select name="discapacidades[]" class="form-control select-discapacidad me-2">
                    <option value="">Seleccione una discapacidad...</option>
                    ${opciones}
                </select>
                <button type="button" class="btn btn-outline-danger btn-sm btn-eliminar-discapacidad">
                    <i class="fas fa-times"></i>
                </button>
            `;

      return div;
    }

    // Agregar nuevo select
    btnAgregarDiscapacidad.addEventListener('click', function() {
      const nuevoSelect = crearSelectDiscapacidad();
      contenedorDiscapacidades.appendChild(nuevoSelect);

      // Agregar evento al botón eliminar
      const btnEliminar = nuevoSelect.querySelector('.btn-eliminar-discapacidad');
      btnEliminar.addEventListener('click', function() {
        nuevoSelect.remove();
      });
    });

    // Eliminar select (evento delegado)
    contenedorDiscapacidades.addEventListener('click', function(e) {
      if (e.target.classList.contains('btn-eliminar-discapacidad') ||
        e.target.closest('.btn-eliminar-discapacidad')) {
        const btn = e.target.classList.contains('btn-eliminar-discapacidad') ?
          e.target : e.target.closest('.btn-eliminar-discapacidad');
        btn.closest('.discapacidad-item').remove();
      }
    });
  });
</script>

<?php
// Incluir layout2.php al final
require_once '/xampp/htdocs/final/layout/layaout2.php';
