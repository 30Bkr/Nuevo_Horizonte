<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/edades/edades.php");

// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $edadesController = new EdadesController($pdo);

  $configuracion = $edadesController->obtenerConfiguracionEdades();
  $estadisticas = $edadesController->obtenerEstadisticasEdades();
  $estudiantesFueraRango = $edadesController->obtenerEstudiantesFueraRango();

  // Intenta obtener info de modificación, pero si falla, continúa
  try {
    $infoModificacion = $edadesController->obtenerInfoUltimaModificacion();
  } catch (Exception $e) {
    $infoModificacion = null;
    error_log("Info modificación no disponible: " . $e->getMessage());
  }
} catch (Exception $e) {
  $configuracion = ['edad_min' => 5, 'edad_max' => 18, 'version' => 1];
  $estadisticas = [];
  $estudiantesFueraRango = [];
  $infoModificacion = null;
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
                <i class="fas fa-child mr-2"></i>
                Configuración de Edades
                <?php if (isset($configuracion['version'])): ?>
                  <span class="badge badge-light ml-2">Versión <?php echo $configuracion['version']; ?></span>
                <?php endif; ?>
              </h1>
              <p class="text-muted">Define los rangos de edad para la inscripción de estudiantes</p>
            </div>
            <div>
              <a href="historial_institucion.php" class="btn btn-info btn-sm">
                <i class="fas fa-history mr-1"></i>
                Ver Historial
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Configuración Actual -->
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-cog mr-2"></i>
                Configuración Actual
              </h3>
            </div>
            <div class="card-body">
              <?php if ($infoModificacion && !empty($infoModificacion['nombre_usuario'])): ?>
                <div class="alert alert-light border mb-3">
                  <small>
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong>Última modificación:</strong><br>
                    <i class="fas fa-user mr-1"></i>
                    Por: <strong><?php echo htmlspecialchars($infoModificacion['nombre_usuario']); ?></strong><br>
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Fecha: <?php echo date('d/m/Y H:i', strtotime($infoModificacion['fecha_modificacion'])); ?>
                  </small>
                </div>
              <?php endif; ?>

              <form id="formConfiguracion" onsubmit="actualizarConfiguracion(event)">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="edad_min">Edad Mínima:</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="edad_min" name="edad_min"
                          value="<?php echo $configuracion['edad_min']; ?>" min="0" max="25" required>
                        <div class="input-group-append">
                          <span class="input-group-text">años</span>
                        </div>
                      </div>
                      <small class="form-text text-muted">
                        Edad mínima requerida para inscripción
                      </small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="edad_max">Edad Máxima:</label>
                      <div class="input-group">
                        <input type="number" class="form-control" id="edad_max" name="edad_max"
                          value="<?php echo $configuracion['edad_max']; ?>" min="0" max="25" required>
                        <div class="input-group-append">
                          <span class="input-group-text">años</span>
                        </div>
                      </div>
                      <small class="form-text text-muted">
                        Edad máxima permitida para inscripción
                      </small>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-12">
                    <div class="alert alert-info">
                      <i class="fas fa-info-circle mr-2"></i>
                      <strong>Rango actual:</strong>
                      Estudiantes entre <span class="badge badge-primary" id="rangoActualMin"><?php echo $configuracion['edad_min']; ?></span>
                      y <span class="badge badge-primary" id="rangoActualMax"><?php echo $configuracion['edad_max']; ?></span> años
                    </div>
                  </div>
                </div>

                <div class="form-group mt-4">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Guardar
                  </button>
                  <a href="http://localhost/final/admin/configuraciones/index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <!-- Estadísticas Rápidas -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-bar mr-2"></i>
                Estadísticas
              </h3>
            </div>
            <div class="card-body">
              <div class="small-box bg-info mb-3">
                <div class="inner">
                  <h3 id="totalEstudiantes"><?php echo array_sum(array_column($estadisticas, 'cantidad')); ?></h3>
                  <p>Total Estudiantes Activos</p>
                </div>
                <div class="icon">
                  <i class="fas fa-users"></i>
                </div>
              </div>

              <div class="small-box mb-3" style="background-color: #ffd75f; color: #333;"">
                <div class=" inner">
                <h3 id="estudiantesFueraRango"><?php echo count($estudiantesFueraRango); ?></h3>
                <p>Estudiantes Fuera del Rango</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
            <div class="small-box bg-success mb-3">
              <div class="inner">
                <h3 id="rangoConfigurado"><?php echo $configuracion['edad_max'] - $configuracion['edad_min']; ?></h3>
                <p>Años de Rango Permitido</p>
              </div>
              <div class="icon">
                <i class="fas fa-ruler-combined"></i>
              </div>
            </div>

            <?php if (isset($configuracion['version'])): ?>
              <div class="small-box bg-secondary">
                <div class="inner">
                  <h3>v<?php echo $configuracion['version']; ?></h3>
                  <p>Versión Actual</p>
                </div>
                <div class="icon">
                  <i class="fas fa-code-branch"></i>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Distribución por Edades -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-pie mr-2"></i>
              Distribución de Estudiantes por Edad
            </h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>Edad</th>
                    <th>Cantidad de Estudiantes</th>
                    <th>Porcentaje</th>
                    <th>Estado</th>
                    <th>Barra de Progreso</th>
                  </tr>
                </thead>
                <tbody id="tablaDistribucion">
                  <?php
                  $totalEstudiantes = array_sum(array_column($estadisticas, 'cantidad'));
                  foreach ($estadisticas as $estadistica):
                    $porcentaje = $totalEstudiantes > 0 ? ($estadistica['cantidad'] / $totalEstudiantes) * 100 : 0;
                    $enRango = $estadistica['edad'] >= $configuracion['edad_min'] && $estadistica['edad'] <= $configuracion['edad_max'];
                  ?>
                    <tr class="<?php echo $enRango ? '' : 'table-warning'; ?>">
                      <td>
                        <strong><?php echo $estadistica['edad']; ?> años</strong>
                      </td>
                      <td>
                        <?php echo $estadistica['cantidad']; ?> estudiantes
                      </td>
                      <td>
                        <?php echo number_format($porcentaje, 1); ?>%
                      </td>
                      <td>
                        <span class="badge badge-<?php echo $enRango ? 'success' : 'warning'; ?>">
                          <?php echo $enRango ? 'Dentro del rango' : 'Fuera del rango'; ?>
                        </span>
                      </td>
                      <td>
                        <div class="progress" style="height: 20px;">
                          <div class="progress-bar <?php echo $enRango ? 'bg-success' : 'bg-warning'; ?>"
                            role="progressbar"
                            style="width: <?php echo $porcentaje; ?>%"
                            aria-valuenow="<?php echo $porcentaje; ?>"
                            aria-valuemin="0"
                            aria-valuemax="100">
                            <?php echo $estadistica['cantidad']; ?>
                          </div>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($estadisticas)): ?>
                    <tr>
                      <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-chart-bar fa-3x mb-3"></i>
                        <p>No hay datos de estudiantes disponibles</p>
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

    <!-- Estudiantes Fuera del Rango -->
    <?php if (!empty($estudiantesFueraRango)): ?>
      <div class="row mt-4">
        <div class="col-12">
          <div class="card card-warning">

            <div class="card-header" style="
        background-color: #ffd75f;
        color: #5a4a00;
        border-bottom: 1px solid #ffc107;
    ">>
              <h3 class="card-title">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Estudiantes Fuera del Rango de Edad Configurado
              </h3>
            </div>
            <div class="card-body">
              <div class="alert" style="
        background-color: #ffd75f;
        color: #5a4a00;
        border-bottom: 1px solid #ffc107;
    ">
                <i class="fas fa-info-circle mr-2"></i>
                Los siguientes estudiantes están fuera del rango de edad configurado
                (<strong><?php echo $configuracion['edad_min']; ?> - <?php echo $configuracion['edad_max']; ?> años</strong>).
                Esto puede requerir atención especial.
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th>Estudiante</th>
                      <th>Fecha de Nacimiento</th>
                      <th>Edad Actual</th>
                      <th>Nivel/Sección</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($estudiantesFueraRango as $estudiante):
                      $esMenor = $estudiante['edad'] < $configuracion['edad_min'];
                    ?>
                      <tr>
                        <td>
                          <strong><?php echo htmlspecialchars($estudiante['primer_nombre'] . ' ' . $estudiante['primer_apellido']); ?></strong>
                        </td>
                        <td>
                          <?php echo date('d/m/Y', strtotime($estudiante['fecha_nac'])); ?>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $esMenor ? 'info' : 'secondary'; ?>">
                            <?php echo $estudiante['edad']; ?> años
                          </span>
                        </td>
                        <td>
                          <?php echo htmlspecialchars($estudiante['nom_nivel'] . ' - ' . $estudiante['nom_seccion']); ?>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $esMenor ? 'info' : 'secondary'; ?>">
                            <?php echo $esMenor ? 'Menor al mínimo' : 'Mayor al máximo'; ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
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

  .progress {
    border-radius: 10px;
  }

  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
  }

  .badge {
    font-size: 0.8em;
  }
</style>

<script>
  // Función para mostrar mensajes
  function mostrarMensaje(mensaje, tipo, esDuplicado = false) {
    const alertClass = tipo === 'success' ? 'success' : esDuplicado ? 'warning' : 'danger';

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
        <strong>${tipo === 'success' ? 'Éxito' : esDuplicado ? 'Advertencia' : 'Error'}:</strong> ${mensaje}
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
      $(alertDiv).alert('close');
    }, 5000);
  }

  // Función principal para actualizar configuración
  // En la función actualizarConfiguracion(event)
  async function actualizarConfiguracion(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const edadMin = parseInt(formData.get('edad_min'));
    const edadMax = parseInt(formData.get('edad_max'));

    // Validación básica
    if (edadMin >= edadMax) {
      mostrarMensaje('La edad mínima debe ser menor que la edad máxima.', 'error');
      return;
    }

    if (edadMin < 0 || edadMax < 0) {
      mostrarMensaje('Las edades no pueden ser números negativos.', 'error');
      return;
    }

    if (edadMin > 25 || edadMax > 25) {
      mostrarMensaje('Las edades no pueden ser mayores a 25 años para educación básica.', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    const botonOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

    try {
      const response = await fetch('../../../app/controllers/edades/accionesEdades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&edad_min=${edadMin}&edad_max=${edadMax}`
        // El id_usuario se obtiene automáticamente de la sesión en el PHP
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');

        // Actualizar la interfaz
        document.getElementById('rangoActualMin').textContent = result.data.edad_min;
        document.getElementById('rangoActualMax').textContent = result.data.edad_max;
        document.getElementById('rangoConfigurado').textContent = result.data.edad_max - result.data.edad_min;

        // Actualizar versión si está disponible
        if (result.data.version) {
          const versionBadge = document.querySelector('.badge.badge-light');
          if (versionBadge) {
            versionBadge.textContent = `Versión ${result.data.version}`;
          }

          const versionBox = document.querySelector('.small-box.bg-secondary h3');
          if (versionBox) {
            versionBox.textContent = `v${result.data.version}`;
          }
        }

        // Recargar la página para ver cambios completos
        setTimeout(() => {
          location.reload();
        }, 1500);

      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = botonOriginal;
    }
  }
  // async function actualizarConfiguracion(event) {
  //   event.preventDefault();

  //   const formData = new FormData(event.target);
  //   const edadMin = parseInt(formData.get('edad_min'));
  //   const edadMax = parseInt(formData.get('edad_max'));

  //   // Validación básica
  //   if (edadMin >= edadMax) {
  //     mostrarMensaje('La edad mínima debe ser menor que la edad máxima.', 'error');
  //     return;
  //   }

  //   if (edadMin < 0 || edadMax < 0) {
  //     mostrarMensaje('Las edades no pueden ser números negativos.', 'error');
  //     return;
  //   }

  //   if (edadMin > 25 || edadMax > 25) {
  //     mostrarMensaje('Las edades no pueden ser mayores a 25 años para educación básica.', 'error');
  //     return;
  //   }

  //   const boton = event.target.querySelector('button[type="submit"]');
  //   const botonOriginal = boton.innerHTML;
  //   boton.disabled = true;
  //   boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

  //   try {
  //     const response = await fetch('../../../app/controllers/edades/accionesEdades.php', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //       },
  //       body: `action=actualizar&edad_min=${edadMin}&edad_max=${edadMax}`
  //     });

  //     const result = await response.json();

  //     if (result.success) {
  //       mostrarMensaje(result.message, 'success');

  //       // Actualizar la interfaz con los nuevos valores
  //       document.getElementById('rangoActualMin').textContent = result.data.edad_min;
  //       document.getElementById('rangoActualMax').textContent = result.data.edad_max;
  //       document.getElementById('rangoConfigurado').textContent = result.data.edad_max - result.data.edad_min;

  //       // Actualizar versión si está disponible
  //       if (result.data.version) {
  //         const versionBadge = document.querySelector('.badge.badge-light');
  //         if (versionBadge) {
  //           versionBadge.textContent = `Versión ${result.data.version}`;
  //         }

  //         // Actualizar la caja de versión
  //         const versionBox = document.querySelector('.small-box.bg-secondary h3');
  //         if (versionBox) {
  //           versionBox.textContent = `v${result.data.version}`;
  //         }
  //       }

  //       // Recargar estadísticas después de un breve retraso
  //       setTimeout(async () => {
  //         await recargarEstadisticas();
  //       }, 1000);

  //       // Mostrar mensaje de nueva versión
  //       if (result.data.version) {
  //         setTimeout(() => {
  //           mostrarMensaje(`Nueva versión creada: v${result.data.version}. Puedes ver el historial completo.`, 'success');
  //         }, 1500);
  //       }
  //     } else {
  //       mostrarMensaje(result.message, 'error');
  //     }
  //   } catch (error) {
  //     mostrarMensaje('Error de conexión: ' + error.message, 'error');
  //   } finally {
  //     boton.disabled = false;
  //     boton.innerHTML = botonOriginal;
  //   }
  // }

  async function recargarEstadisticas() {
    try {
      // Recargar distribución por edades
      const responseEstadisticas = await fetch('../../../app/controllers/edades/accionesEdades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_estadisticas'
      });

      const resultEstadisticas = await responseEstadisticas.json();

      if (resultEstadisticas.success) {
        actualizarTablaDistribucion(resultEstadisticas.data);
      }

      // Recargar estudiantes fuera de rango
      const responseFueraRango = await fetch('../../../app/controllers/edades/accionesEdades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_fuera_rango'
      });

      const resultFueraRango = await responseFueraRango.json();

      if (resultFueraRango.success) {
        actualizarEstudiantesFueraRango(resultFueraRango.data);
      }

    } catch (error) {
      console.error('Error al recargar estadísticas:', error);
    }
  }

  function actualizarTablaDistribucion(estadisticas) {
    const tbody = document.getElementById('tablaDistribucion');
    const totalEstudiantes = estadisticas.reduce((sum, item) => sum + parseInt(item.cantidad), 0);
    const edadMin = parseInt(document.getElementById('edad_min').value);
    const edadMax = parseInt(document.getElementById('edad_max').value);

    if (estadisticas.length === 0) {
      tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                    <p>No hay datos de estudiantes disponibles</p>
                </td>
            </tr>
        `;
      return;
    }

    tbody.innerHTML = estadisticas.map(estadistica => {
      const porcentaje = totalEstudiantes > 0 ? (estadistica.cantidad / totalEstudiantes) * 100 : 0;
      const enRango = estadistica.edad >= edadMin && estadistica.edad <= edadMax;

      return `
            <tr class="${enRango ? '' : 'table-warning'}">
                <td><strong>${estadistica.edad} años</strong></td>
                <td>${estadistica.cantidad} estudiantes</td>
                <td>${porcentaje.toFixed(1)}%</td>
                <td>
                    <span class="badge badge-${enRango ? 'success' : 'warning'}">
                        ${enRango ? 'Dentro del rango' : 'Fuera del rango'}
                    </span>
                </td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar ${enRango ? 'bg-success' : 'bg-warning'}" 
                             role="progressbar" 
                             style="width: ${porcentaje}%"
                             aria-valuenow="${porcentaje}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            ${estadistica.cantidad}
                        </div>
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    document.getElementById('totalEstudiantes').textContent = totalEstudiantes;
  }

  function actualizarEstudiantesFueraRango(estudiantes) {
    document.getElementById('estudiantesFueraRango').textContent = estudiantes.length;
  }

  // Validación en tiempo real
  document.addEventListener('DOMContentLoaded', function() {
    const edadMinInput = document.getElementById('edad_min');
    const edadMaxInput = document.getElementById('edad_max');

    function validarRango() {
      const edadMin = parseInt(edadMinInput.value);
      const edadMax = parseInt(edadMaxInput.value);

      if (edadMin >= edadMax) {
        edadMinInput.classList.add('is-invalid');
        edadMaxInput.classList.add('is-invalid');
      } else {
        edadMinInput.classList.remove('is-invalid');
        edadMaxInput.classList.remove('is-invalid');
      }
    }

    edadMinInput.addEventListener('input', validarRango);
    edadMaxInput.addEventListener('input', validarRango);
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>