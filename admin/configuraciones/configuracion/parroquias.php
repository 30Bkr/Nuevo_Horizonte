<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");

// Crear instancia de conexión y controlador
$conexion = new Conexion();
$conn = $conexion->conectar();
$ubicacionController = new UbicacionController($conn);

// Variables de filtro
$id_estado_filtro = isset($_GET['id_estado']) ? intval($_GET['id_estado']) : null;
$id_municipio_filtro = isset($_GET['id_municipio']) ? intval($_GET['id_municipio']) : null;

// Procesar actualización de estado de parroquia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_parroquia'])) {
  $id_parroquia = $_POST['id_parroquia'];
  $estatus = $_POST['estatus'];

  try {
    if ($ubicacionController->actualizarParroquia($id_parroquia, $estatus)) {
      $accion = $estatus == 1 ? 'habilitada' : 'inhabilitada';
      $_SESSION['mensaje'] = "Parroquia $accion correctamente";
      $_SESSION['tipo_mensaje'] = "success";
    } else {
      $_SESSION['mensaje'] = "Error al actualizar la parroquia";
      $_SESSION['tipo_mensaje'] = "error";
    }
  } catch (Exception $e) {
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
  }

  // Redirigir manteniendo los filtros
  $redirect_url = $_SERVER['PHP_SELF'];
  $params = [];
  if ($id_estado_filtro) $params[] = "id_estado=" . $id_estado_filtro;
  if ($id_municipio_filtro) $params[] = "id_municipio=" . $id_municipio_filtro;
  if (!empty($params)) $redirect_url .= "?" . implode("&", $params);

  echo '<script>window.location.href = "' . $redirect_url . '";</script>';
  exit();
}

try {
  // Obtener todos los estados para el select
  $estados = $ubicacionController->obtenerEstados();

  // Obtener municipios según el estado seleccionado
  $municipios = [];
  if ($id_estado_filtro) {
    $municipios = $ubicacionController->obtenerMunicipiosPorEstado($id_estado_filtro);
  }

  // Obtener parroquias según filtros
  $parroquias = $ubicacionController->obtenerTodasLasParroquias($id_municipio_filtro, $id_estado_filtro);

  // Obtener estadísticas
  $estadisticas = $ubicacionController->obtenerEstadisticasParroquias($id_municipio_filtro, $id_estado_filtro);
  $conteo_en_uso = $ubicacionController->obtenerConteoParroquiasEnUso($id_municipio_filtro, $id_estado_filtro);
} catch (Exception $e) {
  $_SESSION['mensaje'] = $e->getMessage();
  $_SESSION['tipo_mensaje'] = "error";
  $estados = [];
  $municipios = [];
  $parroquias = [];
  $estadisticas = ['total' => 0, 'habilitados' => 0, 'inhabilitados' => 0];
  $conteo_en_uso = 0;
}
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4 p-2">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">Gestión de Parroquias</h1>
              <p class="text-muted">Administra las parroquias del sistema</p>
            </div>
            <div>
              <div class="btn-group">
                <a href="http://localhost/final/admin/configuraciones/configuracion/ubicacion.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Estados
                </a>
                <a href="municipios.php" class="btn btn-info ml-2">
                  <i class="fas fa-city mr-1"></i> Municipios
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtros -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filtros</h5>
            </div>
            <div class="card-body">
              <form method="GET" class="form-inline">
                <div class="form-group mr-3">
                  <label for="id_estado" class="mr-2">Estado:</label>
                  <select name="id_estado" id="id_estado" class="form-control" onchange="this.form.submit()">
                    <option value="">Todos los estados</option>
                    <?php foreach ($estados as $estado): ?>
                      <option value="<?php echo $estado['id_estado']; ?>" <?php echo $id_estado_filtro == $estado['id_estado'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($estado['nom_estado']); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <?php if ($id_estado_filtro && count($municipios) > 0): ?>
                  <div class="form-group mr-3">
                    <label for="id_municipio" class="mr-2">Municipio:</label>
                    <select name="id_municipio" id="id_municipio" class="form-control" onchange="this.form.submit()">
                      <option value="">Todos los municipios</option>
                      <?php foreach ($municipios as $municipio): ?>
                        <option value="<?php echo $municipio['id_municipio']; ?>" <?php echo $id_municipio_filtro == $municipio['id_municipio'] ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($municipio['nom_municipio']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php endif; ?>

                <?php if ($id_estado_filtro || $id_municipio_filtro): ?>
                  <a href="parroquias.php" class="btn btn-secondary">Quitar filtros</a>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Card de Parroquias -->
      <div class="row">
        <div class="col-12">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-map-signs mr-2"></i>
                Parroquias <?php echo ($id_estado_filtro || $id_municipio_filtro) ? '(Filtradas)' : ''; ?>
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th>ID</th>
                      <th>Parroquia</th>
                      <th>Municipio</th>
                      <th>Estado</th>
                      <th>Fecha de Creación</th>
                      <th>Última Actualización</th>
                      <th>En Uso</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($parroquias) > 0): ?>
                      <?php foreach ($parroquias as $parroquia):
                        try {
                          $en_uso = $ubicacionController->parroquiaEnUso($parroquia['id_parroquia']);
                          $conteo_usos = $ubicacionController->obtenerConteoUsosParroquia($parroquia['id_parroquia']);
                        } catch (Exception $e) {
                          $en_uso = false;
                          $conteo_usos = 0;
                        }
                      ?>
                        <tr>
                          <td><?php echo $parroquia['id_parroquia']; ?></td>
                          <td><?php echo htmlspecialchars($parroquia['nom_parroquia']); ?></td>
                          <td><?php echo htmlspecialchars($parroquia['nom_municipio']); ?></td>
                          <td><?php echo htmlspecialchars($parroquia['nom_estado']); ?></td>
                          <td><?php echo date('d/m/Y H:i', strtotime($parroquia['creacion'])); ?></td>
                          <td>
                            <?php
                            if ($parroquia['actualizacion']) {
                              echo date('d/m/Y H:i', strtotime($parroquia['actualizacion']));
                            } else {
                              echo 'No actualizado';
                            }
                            ?>
                          </td>
                          <td>
                            <?php if ($en_uso): ?>
                              <span class="badge badge-warning" data-toggle="tooltip" title="Usada en <?php echo $conteo_usos; ?> dirección(es) activa(s)">
                                <i class="fas fa-exclamation-triangle mr-1"></i>En uso (<?php echo $conteo_usos; ?>)
                              </span>
                            <?php else: ?>
                              <span class="badge badge-secondary">
                                <i class="fas fa-check-circle mr-1"></i>Sin uso
                              </span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge badge-<?php echo $parroquia['estatus'] == 1 ? 'success' : 'danger'; ?>">
                              <?php echo $parroquia['estatus'] == 1 ? 'Habilitada' : 'Inhabilitada'; ?>
                            </span>
                          </td>
                          <td>
                            <form method="POST" class="d-inline">
                              <input type="hidden" name="id_parroquia" value="<?php echo $parroquia['id_parroquia']; ?>">
                              <input type="hidden" name="estatus" value="<?php echo $parroquia['estatus'] == 1 ? 0 : 1; ?>">
                              <button type="submit" name="actualizar_parroquia"
                                class="btn btn-sm btn-<?php echo $parroquia['estatus'] == 1 ? 'warning' : 'success'; ?>"
                                onclick="return confirm('¿Estás seguro de <?php echo $parroquia['estatus'] == 1 ? 'INHABILITAR' : 'HABILITAR'; ?> esta parroquia?<?php echo $en_uso && $parroquia['estatus'] == 1 ? '\n\nADVERTENCIA: Esta parroquia está siendo usada en ' . $conteo_usos . ' dirección(es) activa(s).\nAl inhabilitarla, no aparecerá en nuevos registros pero las direcciones existentes seguirán funcionando.' : ''; ?>')">
                                <i class="fas fa-<?php echo $parroquia['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                <?php echo $parroquia['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                              </button>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="9" class="text-center">
                          <?php if ($id_estado_filtro && empty($municipios)): ?>
                            No hay municipios disponibles para el estado seleccionado
                          <?php else: ?>
                            No hay parroquias registradas
                          <?php endif; ?>
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Información adicional -->
      <div class="row mt-4">
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-map-signs"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Parroquias</span>
              <span class="info-box-number"><?php echo $estadisticas['total']; ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Habilitadas</span>
              <span class="info-box-number"><?php echo $estadisticas['habilitados']; ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Inhabilitadas</span>
              <span class="info-box-number"><?php echo $estadisticas['inhabilitados']; ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-link"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">En Uso</span>
              <span class="info-box-number"><?php echo $conteo_en_uso; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Notas importantes -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="alert alert-info">
            <h5><i class="icon fas fa-info"></i> Información Importante</h5>
            <ul class="mb-0">
              <li><strong>Parroquias en uso:</strong> Pueden ser inhabilitadas, pero aparecerá una advertencia</li>
              <li><strong>Parroquias sin uso:</strong> Pueden ser inhabilitadas sin problemas</li>
              <li><strong>Parroquias inhabilitadas:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen la parroquia NO se verán afectados al inhabilitarla</li>
              <li>Selecciona primero un estado para ver los municipios disponibles</li>
              <li>Selecciona un municipio para filtrar sus parroquias</li>
              <li>Para habilitar una parroquia, simplemente haga clic en el botón "Habilitar"</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .card-success {
    border-color: #28a745;
  }

  .card-success>.card-header {
    background-color: #28a745;
    color: white;
  }

  .table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
  }

  .badge-success {
    background-color: #28a745;
  }

  .badge-danger {
    background-color: #dc3545;
  }

  .badge-warning {
    background-color: #ffc107;
    color: #212529;
  }

  .badge-secondary {
    background-color: #6c757d;
  }

  .info-box {
    box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
    border-radius: 0.25rem;
    background: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: 0.5rem;
    position: relative;
    width: 100%;
  }

  .info-box .info-box-icon {
    border-radius: 0.25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
  }

  .info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
  }
</style>

<script>
  // Inicializar tooltips
  $(function() {
    $('[data-toggle="tooltip"]').tooltip()
  })
</script>

<?php
// Cerrar conexión
$conexion->desconectar();
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>