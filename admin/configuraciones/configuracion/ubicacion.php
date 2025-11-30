<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");

// Crear instancia de conexión y controlador
$conexion = new Conexion();
$conn = $conexion->conectar();
$ubicacionController = new UbicacionController($conn);

// Procesar actualización de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_estado'])) {
  $id_estado = $_POST['id_estado'];
  $estatus = $_POST['estatus'];

  try {
    // Validar si se intenta inhabilitar un estado que está en uso
    if ($estatus == 0 && $ubicacionController->estadoEnUso($id_estado)) {
      $conteo = $ubicacionController->obtenerConteoUsosEstado($id_estado);
      $_SESSION['mensaje'] = "No se puede inhabilitar el estado porque está siendo usado en $conteo dirección(es) activa(s)";
      $_SESSION['tipo_mensaje'] = "error";
    } else {
      if ($ubicacionController->actualizarEstado($id_estado, $estatus)) {
        $accion = $estatus == 1 ? 'habilitado' : 'inhabilitado';
        $_SESSION['mensaje'] = "Estado $accion correctamente";
        $_SESSION['tipo_mensaje'] = "success";
      } else {
        $_SESSION['mensaje'] = "Error al actualizar el estado";
        $_SESSION['tipo_mensaje'] = "error";
      }
    }
  } catch (Exception $e) {
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
  }

  header("Location: ubicacion.php");
  exit();
}

try {
  $estados = $ubicacionController->obtenerTodosLosEstados();
  $estadisticas = $ubicacionController->obtenerEstadisticasEstados();
  $conteo_en_uso = $ubicacionController->obtenerConteoEstadosEnUso();
} catch (Exception $e) {
  $_SESSION['mensaje'] = $e->getMessage();
  $_SESSION['tipo_mensaje'] = "error";
  $estados = [];
  $estadisticas = ['total' => 0, 'habilitados' => 0, 'inhabilitados' => 0];
  $conteo_en_uso = 0;
}
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <h1 class="mb-0">Gestión de Ubicaciones</h1>
          <p class="text-muted">Administra los estados, municipios y parroquias del sistema</p>
        </div>
      </div>

      <!-- Card de Estados -->
      <div class="row">
        <div class="col-12">
          <div class="card card-purple card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-map-marker-alt mr-2"></i>
                Estados
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
                      <th>Nombre del Estado</th>
                      <th>Fecha de Creación</th>
                      <th>Última Actualización</th>
                      <th>En Uso</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (count($estados) > 0): ?>
                      <?php foreach ($estados as $estado):
                        try {
                          $en_uso = $ubicacionController->estadoEnUso($estado['id_estado']);
                          $conteo_usos = $ubicacionController->obtenerConteoUsosEstado($estado['id_estado']);
                        } catch (Exception $e) {
                          $en_uso = false;
                          $conteo_usos = 0;
                        }
                      ?>
                        <tr>
                          <td><?php echo $estado['id_estado']; ?></td>
                          <td><?php echo htmlspecialchars($estado['nom_estado']); ?></td>
                          <td><?php echo date('d/m/Y H:i', strtotime($estado['creacion'])); ?></td>
                          <td>
                            <?php
                            if ($estado['actualizacion']) {
                              echo date('d/m/Y H:i', strtotime($estado['actualizacion']));
                            } else {
                              echo 'No actualizado';
                            }
                            ?>
                          </td>
                          <td>
                            <?php if ($en_uso): ?>
                              <span class="badge badge-info" data-toggle="tooltip" title="Usado en <?php echo $conteo_usos; ?> dirección(es) activa(s)">
                                <i class="fas fa-link mr-1"></i>En uso (<?php echo $conteo_usos; ?>)
                              </span>
                            <?php else: ?>
                              <span class="badge badge-secondary">
                                <i class="fas fa-unlink mr-1"></i>Sin uso
                              </span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge badge-<?php echo $estado['estatus'] == 1 ? 'success' : 'danger'; ?>">
                              <?php echo $estado['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                            </span>
                          </td>
                          <td>
                            <form method="POST" class="d-inline">
                              <input type="hidden" name="id_estado" value="<?php echo $estado['id_estado']; ?>">
                              <input type="hidden" name="estatus" value="<?php echo $estado['estatus'] == 1 ? 0 : 1; ?>">
                              <?php if ($estado['estatus'] == 1 && $en_uso): ?>
                                <button type="button"
                                  class="btn btn-sm btn-secondary"
                                  data-toggle="tooltip"
                                  title="No se puede inhabilitar porque está en uso en direcciones activas">
                                  <i class="fas fa-lock mr-1"></i> Bloqueado
                                </button>
                              <?php else: ?>
                                <button type="submit" name="actualizar_estado"
                                  class="btn btn-sm btn-<?php echo $estado['estatus'] == 1 ? 'warning' : 'success'; ?>"
                                  onclick="return confirm('¿Estás seguro de <?php echo $estado['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?> este estado?')">
                                  <i class="fas fa-<?php echo $estado['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                  <?php echo $estado['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                                </button>
                              <?php endif; ?>
                            </form>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center">No hay estados registrados</td>
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
            <span class="info-box-icon bg-info"><i class="fas fa-info-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Estados</span>
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
              <li><strong>Estados en uso:</strong> No se pueden inhabilitar porque están siendo utilizados en direcciones activas</li>
              <li><strong>Estados sin uso:</strong> Se pueden inhabilitar sin problemas</li>
              <li><strong>Estados inhabilitados:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen el estado no se verán afectados al inhabilitarlo</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Leyenda de estados -->
      <div class="row mt-3">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h6 class="card-title mb-0">Leyenda de Estados</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <span class="badge badge-success mr-2">Habilitado</span>
                  <small>Disponible para nuevos registros</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-danger mr-2">Inhabilitado</span>
                  <small>No disponible para nuevos registros</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-info mr-2">En uso</span>
                  <small>Usado en direcciones activas</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-secondary mr-2">Sin uso</span>
                  <small>No usado en direcciones activas</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- (Los estilos y scripts se mantienen igual) -->
<style>
  .card-purple {
    border-color: #6f42c1;
  }

  .card-purple>.card-header {
    background-color: #6f42c1;
    color: white;
  }

  .btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
  }

  .btn-purple:hover {
    background-color: #5a379c;
    border-color: #5a379c;
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

  .badge-info {
    background-color: #17a2b8;
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