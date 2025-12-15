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

// Procesar actualización de estado de municipio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_municipio'])) {
  $id_municipio = $_POST['id_municipio'];
  $estatus = $_POST['estatus'];

  try {
    if ($ubicacionController->actualizarMunicipio($id_municipio, $estatus)) {
      $accion = $estatus == 1 ? 'habilitado' : 'inhabilitado';
      $_SESSION['mensaje'] = "Municipio $accion correctamente";
      $_SESSION['tipo_mensaje'] = "success";
    } else {
      $_SESSION['mensaje'] = "Error al actualizar el municipio";
      $_SESSION['tipo_mensaje'] = "error";
    }
  } catch (Exception $e) {
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
  }

  // Redirigir manteniendo el filtro
  $redirect_url = $_SERVER['PHP_SELF'];
  if ($id_estado_filtro) {
    $redirect_url .= "?id_estado=" . $id_estado_filtro;
  }
  echo '<script>window.location.href = "' . $redirect_url . '";</script>';
  exit();
}

try {
  // Obtener todos los estados para el select
  $estados = $ubicacionController->obtenerEstados();

  // Obtener municipios según filtro
  $municipios = $ubicacionController->obtenerTodosLosMunicipios($id_estado_filtro);

  // Obtener estadísticas
  $estadisticas = $ubicacionController->obtenerEstadisticasMunicipios($id_estado_filtro);
  $conteo_en_uso = $ubicacionController->obtenerConteoMunicipiosEnUso($id_estado_filtro);
} catch (Exception $e) {
  $_SESSION['mensaje'] = $e->getMessage();
  $_SESSION['tipo_mensaje'] = "error";
  $estados = [];
  $municipios = [];
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
              <h1 class="mb-0">Gestión de Municipios</h1>
              <p class="text-muted">Administra los municipios del sistema</p>
            </div>
            <div>
              <div class="btn-group">
                <a href="http://localhost/final/admin/configuraciones/configuracion/ubicacion.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Estados
                </a>
                <a href="parroquias.php" class="btn btn-success ml-2">
                  <i class="fas fa-map-signs mr-1"></i> Parroquias
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtro por Estado -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0"><i class="fas fa-filter mr-2"></i>Filtrar por Estado</h5>
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
                <?php if ($id_estado_filtro): ?>
                  <a href="municipios.php" class="btn btn-secondary">Quitar filtro</a>
                <?php endif; ?>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Card de Municipios -->
      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-city mr-2"></i>
                Municipios <?php echo $id_estado_filtro ? '(Filtrados)' : ''; ?>
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
                    <?php if (count($municipios) > 0): ?>
                      <?php foreach ($municipios as $municipio):
                        try {
                          $en_uso = $ubicacionController->municipioEnUso($municipio['id_municipio']);
                          $conteo_usos = $ubicacionController->obtenerConteoUsosMunicipio($municipio['id_municipio']);
                        } catch (Exception $e) {
                          $en_uso = false;
                          $conteo_usos = 0;
                        }
                      ?>
                        <tr>
                          <td><?php echo $municipio['id_municipio']; ?></td>
                          <td><?php echo htmlspecialchars($municipio['nom_municipio']); ?></td>
                          <td><?php echo htmlspecialchars($municipio['nom_estado']); ?></td>
                          <td><?php echo date('d/m/Y H:i', strtotime($municipio['creacion'])); ?></td>
                          <td>
                            <?php
                            if ($municipio['actualizacion']) {
                              echo date('d/m/Y H:i', strtotime($municipio['actualizacion']));
                            } else {
                              echo 'No actualizado';
                            }
                            ?>
                          </td>
                          <td>
                            <?php if ($en_uso): ?>
                              <span class="badge badge-warning" data-toggle="tooltip" title="Usado en <?php echo $conteo_usos; ?> dirección(es) activa(s)">
                                <i class="fas fa-exclamation-triangle mr-1"></i>En uso (<?php echo $conteo_usos; ?>)
                              </span>
                            <?php else: ?>
                              <span class="badge badge-secondary">
                                <i class="fas fa-check-circle mr-1"></i>Sin uso
                              </span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge badge-<?php echo $municipio['estatus'] == 1 ? 'success' : 'danger'; ?>">
                              <?php echo $municipio['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                            </span>
                          </td>
                          <td>
                            <form method="POST" class="d-inline">
                              <input type="hidden" name="id_municipio" value="<?php echo $municipio['id_municipio']; ?>">
                              <input type="hidden" name="estatus" value="<?php echo $municipio['estatus'] == 1 ? 0 : 1; ?>">
                              <button type="submit" name="actualizar_municipio"
                                class="btn btn-sm btn-<?php echo $municipio['estatus'] == 1 ? 'warning' : 'success'; ?>"
                                onclick="return confirm('¿Estás seguro de <?php echo $municipio['estatus'] == 1 ? 'INHABILITAR' : 'HABILITAR'; ?> este municipio?<?php echo $en_uso && $municipio['estatus'] == 1 ? '\n\nADVERTENCIA: Este municipio está siendo usado en ' . $conteo_usos . ' dirección(es) activa(s).\nAl inhabilitarlo, no aparecerá en nuevos registros pero las direcciones existentes seguirán funcionando.\n\nNOTA: Al inhabilitar un municipio, todas sus parroquias también se inhabilitarán.' : ''; ?>')">
                                <i class="fas fa-<?php echo $municipio['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                <?php echo $municipio['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                              </button>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="8" class="text-center">No hay municipios registrados</td>
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
            <span class="info-box-icon bg-info"><i class="fas fa-city"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Municipios</span>
              <span class="info-box-number"><?php echo $estadisticas['total']; ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Habilitados</span>
              <span class="info-box-number"><?php echo $estadisticas['habilitados']; ?></span>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Inhabilitados</span>
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
              <li><strong>Municipios en uso:</strong> Pueden ser inhabilitados, pero aparecerá una advertencia</li>
              <li><strong>Municipios sin uso:</strong> Pueden ser inhabilitados sin problemas</li>
              <li><strong>Municipios inhabilitados:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen el municipio NO se verán afectados al inhabilitarlo</li>
              <li><strong>IMPORTANTE:</strong> Al inhabilitar un municipio, todas sus parroquias también se inhabilitarán</li>
              <li>Para habilitar un municipio, simplemente haga clic en el botón "Habilitar"</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .card-primary {
    border-color: #007bff;
  }

  .card-primary>.card-header {
    background-color: #007bff;
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