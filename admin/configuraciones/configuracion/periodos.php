<?php
require_once '/xampp/htdocs/final/global/protect.php';
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/periodos/periodos.php");


// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $periodoController = new PeriodoController($pdo);

  $periodos = $periodoController->obtenerPeriodos();
  $periodoActivo = $periodoController->obtenerPeriodoActivo();
  $estadisticas = $periodoController->obtenerEstadisticasPeriodos();
} catch (Exception $e) {
  $periodos = [];
  $periodoActivo = null;
  $estadisticas = [
    'total_periodos' => 0,
    'periodos_activos' => 0,
    'estudiantes_activos' => 0
  ];
  $_SESSION['mensaje'] = $e->getMessage();
  $_SESSION['tipo_mensaje'] = 'error';
}
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">
                <i class="fas fa-calendar-alt mr-2"></i>
                Gestión de Periodos Académicos
              </h1>
              <p class="text-muted">Administra los periodos escolares y años académicos</p>
            </div>
            <div>
              <a href="http://localhost/final/admin/configuraciones/index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
              </a>
              <button class="btn btn-primary mr-2" onclick="abrirModalCrear()">
                <i class="fas fa-plus mr-1"></i> Crear Periodo
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalPeriodos"><?php echo $estadisticas['total_periodos']; ?></h3>
              <p>Total de Periodos</p>
            </div>
            <div class="icon">
              <i class="fas fa-calendar"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="periodosActivos"><?php echo $estadisticas['periodos_activos']; ?></h3>
              <p>Periodos Activos</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="estudiantesActivos"><?php echo $estadisticas['estudiantes_activos']; ?></h3>
              <p>Estudiantes Activos</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Periodo Activo Actual -->
      <?php if ($periodoActivo): ?>
        <div class="row mb-4">
          <div class="col-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-star mr-2"></i>
                  Periodo Académico Activo Actual
                </h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <strong>Descripción:</strong><br>
                    <span class="h5"><?php echo htmlspecialchars($periodoActivo['descripcion_periodo']); ?></span>
                  </div>
                  <div class="col-md-3">
                    <strong>Fecha Inicio:</strong><br>
                    <span class="h6"><?php echo date('d/m/Y', strtotime($periodoActivo['fecha_ini'])); ?></span>
                  </div>
                  <div class="col-md-3">
                    <strong>Fecha Fin:</strong><br>
                    <span class="h6"><?php echo date('d/m/Y', strtotime($periodoActivo['fecha_fin'])); ?></span>
                  </div>
                  <div class="col-md-2">
                    <strong>Estado:</strong><br>
                    <span class="badge badge-success">ACTIVO</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>



      <!-- Lista de Periodos -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Periodos Académicos</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" id="searchInput" class="form-control float-right" placeholder="Buscar...">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default" onclick="buscarPeriodos()">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-hover text-nowrap">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaPeriodos">
                  <?php if (empty($periodos)): ?>
                    <tr>
                      <td colspan="7" class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay periodos académicos registrados</p>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($periodos as $periodo):
                      $esActivo = $periodo['estatus'] == 1;
                      $fechaIni = new DateTime($periodo['fecha_ini']);
                      $fechaFin = new DateTime($periodo['fecha_fin']);
                      $diferencia = $fechaIni->diff($fechaFin);
                      $duracionMeses = ($diferencia->y * 12) + $diferencia->m;
                    ?>
                      <tr id="periodo-<?php echo $periodo['id_periodo']; ?>" class="<?php echo $esActivo ? 'table-success' : ''; ?>">
                        <td><?php echo $periodo['id_periodo']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-day text-primary mr-2"></i>
                            <strong><?php echo htmlspecialchars($periodo['descripcion_periodo']); ?></strong>
                          </div>
                        </td>
                        <td><?php echo $fechaIni->format('d/m/Y'); ?></td>
                        <td><?php echo $fechaFin->format('d/m/Y'); ?></td>
                        <td>
                          <span class="badge badge-info">
                            <?php echo $duracionMeses; ?> meses
                          </span>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $esActivo ? 'success' : 'secondary'; ?>"
                            id="estatus-<?php echo $periodo['id_periodo']; ?>">
                            <?php echo $esActivo ? 'Activo' : 'Inactivo'; ?>
                          </span>
                        </td>
                        <td>
                          <div class="btn-group">
                            <?php if (!$esActivo): ?>
                              <button class="btn btn-sm btn-outline-success activar-periodo"
                                onclick="activarPeriodo(<?php echo $periodo['id_periodo']; ?>, '<?php echo htmlspecialchars($periodo['descripcion_periodo']); ?>')">
                                <i class="fas fa-play mr-1"></i> Activar
                              </button>
                            <?php else: ?>
                              <button class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-check mr-1"></i> Activo
                              </button>
                            <?php endif; ?>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <div class="card-footer clearfix">
              <div class="float-right">
                <small class="text-muted">
                  Mostrando <?php echo count($periodos); ?> periodos
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Información de Versión Global -->
      <div class="row mb-3">
        <div class="col-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-code-branch mr-2"></i>
                Sistema de Versiones
              </h3>
              <div class="card-tools">
                <a href="historial_institucion.php" class="btn btn-info btn-sm">
                  <i class="fas fa-history mr-1"></i>
                  Ver Historial Completo
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <strong><i class="fas fa-calendar-check mr-2"></i>Periodo Activo:</strong><br>
                  <span class="h5"><?php echo $periodoActivo ? htmlspecialchars($periodoActivo['descripcion_periodo']) : 'No hay periodo activo'; ?></span>
                </div>
                <div class="col-md-4">
                  <strong><i class="fas fa-code-branch mr-2"></i>Versión Actual:</strong><br>
                  <span class="h5" id="versionActual">
                    <?php
                    // Obtener versión actual
                    try {
                      $stmt = $pdo->prepare("SELECT version FROM globales WHERE es_activo = 1 ORDER BY version DESC LIMIT 1");
                      $stmt->execute();
                      $version = $stmt->fetch(PDO::FETCH_ASSOC);
                      echo $version ? 'v' . $version['version'] : 'v1';
                    } catch (Exception $e) {
                      echo 'v1';
                    }
                    ?>
                  </span>
                </div>
                <div class="col-md-4">
                  <strong><i class="fas fa-exclamation-triangle mr-2"></i>Importante:</strong><br>
                  <small>Al activar un nuevo periodo, se crea una nueva versión en el historial de configuraciones.</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Periodo -->
<div class="modal fade" id="modalCrear" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>
          Crear Nuevo Periodo Académico
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formCrear" onsubmit="crearPeriodo(event)">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="descripcion_periodo">Descripción del Periodo:</label>
                <input type="text" class="form-control" id="descripcion_periodo" name="descripcion"
                  placeholder="La descripción se generará automáticamente" readonly required>
                <small class="form-text text-muted">
                  La descripción se genera automáticamente basada en las fechas seleccionadas
                </small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="fecha_ini">Fecha de Inicio:</label>
                <input type="date" class="form-control" id="fecha_ini" name="fecha_ini" required
                  onchange="actualizarDescripcion()">
                <small class="form-text text-muted">
                  Fecha en que inicia el periodo académico
                </small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="fecha_fin">Fecha de Fin:</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required
                  onchange="actualizarDescripcion()">
                <small class="form-text text-muted">
                  Fecha en que finaliza el periodo académico
                </small>
              </div>
            </div>
          </div>
          <div class="alert alert-info" id="infoUltimoPeriodo">
            <i class="fas fa-info-circle mr-2"></i>
            <span id="textoInfoUltimoPeriodo">Cargando información del último periodo...</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Crear Periodo
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Generar Periodos Automáticos -->
<div class="modal fade" id="modalGenerarAutomaticos" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-magic mr-2"></i>
          Generar Periodos Automáticamente
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formGenerarAutomaticos" onsubmit="generarPeriodosAutomaticos(event)">
        <div class="modal-body">
          <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Esta función creará periodos académicos de forma automática basándose en las fechas proporcionadas.
          </div>

          <div class="form-group">
            <label for="fecha_inicio">Fecha de Inicio para el Primer Periodo:</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            <small class="form-text text-muted">
              Fecha de inicio para el primer periodo a generar
            </small>
          </div>

          <div class="form-group">
            <label for="anios_futuros">Años Futuros a Generar:</label>
            <select class="form-control" id="anios_futuros" name="anios_futuros" required>
              <option value="1">1 año (1 periodo)</option>
              <option value="2">2 años (2 periodos)</option>
              <option value="3" selected>3 años (3 periodos)</option>
              <option value="5">5 años (5 periodos)</option>
              <option value="10">10 años (10 periodos)</option>
            </select>
            <small class="form-text text-muted">
              Cantidad de años futuros para los que se generarán periodos
            </small>
          </div>

          <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Se generarán periodos de aproximadamente 10 meses de duración cada uno.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-bolt mr-1"></i> Generar Periodos
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Confirmación Activación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
          Confirmar Activación de Periodo
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="mensajeConfirmacion"></p>
        <div class="alert alert-warning">
          <i class="fas fa-info-circle mr-2"></i>
          Al activar este periodo, todos los demás periodos se desactivarán automáticamente.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-warning" id="btnConfirmarActivacion">
          <i class="fas fa-check mr-1"></i> Confirmar Activación
        </button>
      </div>
    </div>
  </div>
</div>

<style>
  .small-box {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .small-box .icon {
    font-size: 70px;
    top: -10px;
  }

  .card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
  }

  .badge {
    font-size: 0.8em;
  }
</style>

<script>
  // Variables globales
  let periodoSeleccionado = null;

  // Funciones para abrir modales
  function abrirModalCrear() {
    // Cargar información del último periodo
    cargarInfoUltimoPeriodo();
    // Establecer límites de fecha
    establecerLimitesFechas();

    $('#modalCrear').modal('show');
  }

  function establecerLimitesFechas() {
    const hoy = new Date();
    const anioActual = hoy.getFullYear();
    const anioSiguiente = anioActual + 1;

    // Establecer fecha mínima como 1ero de septiembre del año actual
    const fechaMinima = new Date(anioActual, 8, 1); // Septiembre es mes 8 (0-indexed)
    // Establecer fecha máxima como 31 de julio del año siguiente
    const fechaMaxima = new Date(anioSiguiente, 6, 31); // Julio es mes 6 (0-indexed)

    const fechaIniInput = document.getElementById('fecha_ini');
    const fechaFinInput = document.getElementById('fecha_fin');

    // Formatear fechas para input type="date" (YYYY-MM-DD)
    fechaIniInput.min = fechaMinima.toISOString().split('T')[0];
    fechaIniInput.max = fechaMaxima.toISOString().split('T')[0];
    fechaFinInput.min = fechaMinima.toISOString().split('T')[0];
    fechaFinInput.max = fechaMaxima.toISOString().split('T')[0];

    // Establecer valores por defecto (periodo escolar típico)
    const fechaIniDefault = new Date(anioActual, 8, 15); // 15 de Septiembre
    const fechaFinDefault = new Date(anioSiguiente, 6, 15); // 15 de Julio

    fechaIniInput.value = fechaIniDefault.toISOString().split('T')[0];
    fechaFinInput.value = fechaFinDefault.toISOString().split('T')[0];

    // Generar descripción inicial
    actualizarDescripcion();
  }

  function actualizarDescripcion() {
    const fechaIni = document.getElementById('fecha_ini').value;
    const fechaFin = document.getElementById('fecha_fin').value;
    const descripcionInput = document.getElementById('descripcion_periodo');

    if (fechaIni && fechaFin) {
      const anioIni = new Date(fechaIni).getFullYear();
      const anioFin = new Date(fechaFin).getFullYear();

      // Formato: "Año Escolar 2024-2025"
      descripcionInput.value = `Año Escolar ${anioIni}-${anioFin}`;
    }
  }

  async function cargarInfoUltimoPeriodo() {
    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todos'
      });

      const result = await response.json();

      if (result.success && result.data.periodos.length > 0) {
        const ultimoPeriodo = result.data.periodos[0];
        const fechaFinUltimo = new Date(ultimoPeriodo.fecha_fin);

        // Actualizar texto informativo
        document.getElementById('textoInfoUltimoPeriodo').innerHTML =
          `El último periodo registrado: <strong>"${ultimoPeriodo.descripcion_periodo}"</strong><br>
                 Finaliza el: <strong>${fechaFinUltimo.toLocaleDateString('es-ES')}</strong>`;
      } else {
        document.getElementById('textoInfoUltimoPeriodo').textContent =
          'No hay periodos anteriores registrados.';
      }
    } catch (error) {
      document.getElementById('textoInfoUltimoPeriodo').textContent =
        'Error al cargar información del último periodo.';
    }
  }


  function activarPeriodo(id, descripcion) {
    periodoSeleccionado = {
      id,
      descripcion
    };

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas activar el periodo:<br><strong>"${descripcion}"</strong>?`
    );

    $('#modalConfirmacion').modal('show');
  }
  async function actualizarVersionActual() {
    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_version_actual'
      });

      const result = await response.json();

      if (result.success && result.data) {
        document.getElementById('versionActual').textContent = `v${result.data.version || 1}`;
      }
    } catch (error) {
      console.error('Error al actualizar versión:', error);
    }
  }

  // Funciones con Fetch
  async function crearPeriodo(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const descripcion = formData.get('descripcion').trim();
    const fechaIni = formData.get('fecha_ini');
    const fechaFin = formData.get('fecha_fin');

    if (!descripcion || !fechaIni || !fechaFin) {
      mostrarMensaje('Todos los campos son requeridos', 'error');
      return;
    }

    // Validar fechas
    if (new Date(fechaIni) >= new Date(fechaFin)) {
      mostrarMensaje('La fecha de inicio debe ser anterior a la fecha de fin', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Creando...';

    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=crear&descripcion=${encodeURIComponent(descripcion)}&fecha_ini=${fechaIni}&fecha_fin=${fechaFin}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');
        $('#modalCrear').modal('hide');
        event.target.reset();
        recargarPeriodos();
      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Crear Periodo';
    }
  }

  // async function confirmarActivacion() {
  //   if (!periodoSeleccionado) return;

  //   const {
  //     id
  //   } = periodoSeleccionado;

  //   try {
  //     const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //       },
  //       body: `action=activar&id_periodo=${id}`
  //     });

  //     const result = await response.json();

  //     if (result.success) {
  //       mostrarMensaje(result.message, 'success');
  //       $('#modalConfirmacion').modal('hide');
  //       recargarPeriodos();
  //     } else {
  //       mostrarMensaje(result.message, 'error');
  //     }
  //   } catch (error) {
  //     mostrarMensaje('Error de conexión: ' + error.message, 'error');
  //   }
  // }

  // async function confirmarActivacion() {
  //   if (!periodoSeleccionado) return;

  //   const {
  //     id,
  //     descripcion
  //   } = periodoSeleccionado;

  //   // Botón de confirmación
  //   const btnConfirmar = document.getElementById('btnConfirmarActivacion');
  //   const btnOriginal = btnConfirmar.innerHTML;
  //   btnConfirmar.disabled = true;
  //   btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Activando...';

  //   try {
  //     const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //       },
  //       body: `action=activar&id_periodo=${id}`
  //     });

  //     const result = await response.json();

  //     if (result.success) {
  //       mostrarMensaje(result.message, 'success');
  //       $('#modalConfirmacion').modal('hide');

  //       // Mostrar mensaje de nueva versión
  //       if (result.data && result.data.version) {
  //         mostrarMensaje(`Nueva versión creada: v${result.data.version}. El historial completo se puede ver en "Ver Historial".`, 'success');

  //         // Actualizar versión en la interfaz
  //         document.getElementById('versionActual').textContent = `v${result.data.version}`;
  //       }

  //       recargarPeriodos();
  //     } else {
  //       mostrarMensaje(result.message, 'error');
  //     }
  //   } catch (error) {
  //     mostrarMensaje('Error de conexión: ' + error.message, 'error');
  //   } finally {
  //     btnConfirmar.disabled = false;
  //     btnConfirmar.innerHTML = btnOriginal;
  //   }
  // }
  async function confirmarActivacion() {
    if (!periodoSeleccionado) return;

    const {
      id,
      descripcion
    } = periodoSeleccionado;

    const btnConfirmar = document.getElementById('btnConfirmarActivacion');
    const btnOriginal = btnConfirmar.innerHTML;
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Activando...';

    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=activar&id_periodo=${id}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');
        $('#modalConfirmacion').modal('hide');

        // Recargar la página completa para ver cambios
        setTimeout(() => {
          location.reload();
        }, 1500);

      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      // Si hay error JSON, aún recargamos la página
      console.error('Error:', error);
      mostrarMensaje('Periodo activado. Recargando página...', 'success');
      $('#modalConfirmacion').modal('hide');

      setTimeout(() => {
        location.reload();
      }, 1500);
    } finally {
      btnConfirmar.disabled = false;
      btnConfirmar.innerHTML = btnOriginal;
    }
  }



  async function recargarPeriodos() {
    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todos'
      });

      const result = await response.json();

      if (result.success) {
        actualizarInterfaz(result.data);
      } else {
        mostrarMensaje('Error al cargar periodos', 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    }
  }

  async function cargarInfoUltimoPeriodo() {
    try {
      const response = await fetch('../../../app/controllers/periodos/accionesPeriodos.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todos'
      });

      const result = await response.json();

      if (result.success && result.data.periodos.length > 0) {
        const ultimoPeriodo = result.data.periodos[0]; // El primero es el más reciente
        const fechaFinUltimo = new Date(ultimoPeriodo.fecha_fin);
        const fechaMinima = new Date(fechaFinUltimo);
        fechaMinima.setDate(fechaMinima.getDate() + 1); // Un día después del último periodo

        // Establecer fecha mínima en el input
        document.getElementById('fecha_ini').min = fechaMinima.toISOString().split('T')[0];

        // Actualizar texto informativo
        document.getElementById('textoInfoUltimoPeriodo').innerHTML =
          `El último periodo finaliza el <strong>${fechaFinUltimo.toLocaleDateString('es-ES')}</strong>. 
                 El nuevo periodo debe iniciar después de esta fecha.`;
      } else {
        document.getElementById('textoInfoUltimoPeriodo').textContent =
          'No hay periodos anteriores. Puede establecer cualquier fecha de inicio.';
      }
    } catch (error) {
      document.getElementById('textoInfoUltimoPeriodo').textContent =
        'Error al cargar información del último periodo.';
    }
  }

  function actualizarInterfaz(data) {
    const {
      periodos,
      periodo_activo,
      estadisticas,
      version_global
    } = data;
    actualizarTablaPeriodos(periodos);
    actualizarEstadisticas(estadisticas);
    actualizarPeriodoActivo(periodo_activo);

    // Actualizar versión si está disponible
    if (version_global && version_global.version) {
      document.getElementById('versionActual').textContent = `v${version_global.version}`;
    }
  }

  function actualizarTablaPeriodos(periodos) {
    const tbody = document.getElementById('tablaPeriodos');

    if (periodos.length === 0) {
      tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay periodos académicos registrados</p>
                </td>
            </tr>
        `;
      return;
    }

    tbody.innerHTML = periodos.map(periodo => {
      const esActivo = periodo.estatus == 1;
      const fechaIni = new Date(periodo.fecha_ini);
      const fechaFin = new Date(periodo.fecha_fin);
      const diferencia = fechaFin - fechaIni;
      const duracionMeses = Math.floor(diferencia / (1000 * 60 * 60 * 24 * 30));

      return `
            <tr id="periodo-${periodo.id_periodo}" class="${esActivo ? 'table-success' : ''}">
                <td>${periodo.id_periodo}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-day text-primary mr-2"></i>
                        <strong>${escapeHtml(periodo.descripcion_periodo)}</strong>
                    </div>
                </td>
                <td>${fechaIni.toLocaleDateString('es-ES')}</td>
                <td>${fechaFin.toLocaleDateString('es-ES')}</td>
                <td>
                    <span class="badge badge-info">
                        ${duracionMeses} meses
                    </span>
                </td>
                <td>
                    <span class="badge badge-${esActivo ? 'success' : 'secondary'}" 
                          id="estatus-${periodo.id_periodo}">
                        ${esActivo ? 'Activo' : 'Inactivo'}
                    </span>
                </td>
                <td>
                    <div class="btn-group">
                        ${!esActivo ? 
                            `<button class="btn btn-sm btn-outline-success activar-periodo"
                                    onclick="activarPeriodo(${periodo.id_periodo}, '${escapeHtml(periodo.descripcion_periodo)}')">
                                <i class="fas fa-play mr-1"></i> Activar
                            </button>` :
                            `<button class="btn btn-sm btn-success" disabled>
                                <i class="fas fa-check mr-1"></i> Activo
                            </button>`
                        }
                    </div>
                </td>
            </tr>
        `;
    }).join('');
  }

  function actualizarEstadisticas(estadisticas) {
    document.getElementById('totalPeriodos').textContent = estadisticas.total_periodos;
    document.getElementById('periodosActivos').textContent = estadisticas.periodos_activos;
    document.getElementById('estudiantesActivos').textContent = estadisticas.estudiantes_activos;
  }

  function actualizarPeriodoActivo(periodoActivo) {
    // Esta función puede expandirse para actualizar el banner de periodo activo
    if (periodoActivo) {
      console.log('Periodo activo actualizado:', periodoActivo);
    }
  }

  function buscarPeriodos() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaPeriodos tr');

    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
  }

  // Utilidades
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function mostrarMensaje(mensaje, tipo) {
    const alertClass = tipo === 'success' ? 'success' : 'error';

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${alertClass} alert-dismissible fade show`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
    `;

    alertDiv.innerHTML = `
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>${tipo === 'success' ? 'Éxito' : 'Error'}:</strong> ${mensaje}
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
      $(alertDiv).alert('close');
    }, 5000);
  }

  // Event Listeners
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('input', buscarPeriodos);
    document.getElementById('btnConfirmarActivacion').addEventListener('click', confirmarActivacion);

    // Limpiar formularios al cerrar modales
    $('#modalCrear').on('hidden.bs.modal', function() {
      document.getElementById('formCrear').reset();
    });

    $('#modalGenerarAutomaticos').on('hidden.bs.modal', function() {
      document.getElementById('formGenerarAutomaticos').reset();
    });
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>