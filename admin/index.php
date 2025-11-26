<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/dashboard/dashboard.php");

try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $dashboardController = new DashboardController($pdo);

  // Obtener estadísticas generales
  $estadisticas = $dashboardController->obtenerEstadisticasGenerales();
  $datos_estadisticas = $estadisticas['success'] ? $estadisticas['data'] : null;

  // Obtener inscripciones de la semana actual
  $inscripciones_semana = $dashboardController->obtenerInscripcionesPorMes();
  $datos_semana = $inscripciones_semana['success'] ? $inscripciones_semana['data'] : null;

  // Obtener semanas disponibles
  $semanas_disponibles = $dashboardController->obtenerMesesDisponibles();
  // $lista_semanas = $semanas_disponibles['success'] ? $semanas_disponibles['semanas'] : [];
} catch (Exception $e) {
  $datos_estadisticas = null;
  $datos_semana = null;
  $lista_semanas = [];
  $_SESSION['mensaje'] = "Error al cargar el dashboard: " . $e->getMessage();
  $_SESSION['tipo_mensaje'] = 'error';
}
?>

<style>
  .stat-card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
  }

  .stat-icon {
    font-size: 3rem;
    opacity: 0.7;
  }

  .chart-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
  }

  .week-selector {
    max-width: 300px;
  }

  .bg-students {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
  }

  .bg-teachers {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
  }

  .bg-representatives {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
  }

  .bg-inscriptions {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <!-- Header -->


      <!-- Estadísticas Generales -->
      <div class="row mt-4">
        <!-- Estudiantes -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="stat-card bg-students p-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h3 class="mb-0" id="total-estudiantes">
                  <?php echo $datos_estadisticas ? number_format($datos_estadisticas['total_estudiantes']) : '0'; ?>
                </h3>
                <p class="mb-0">Estudiantes</p>
              </div>
              <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Docentes -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="stat-card bg-teachers p-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h3 class="mb-0" id="total-docentes">
                  <?php echo $datos_estadisticas ? number_format($datos_estadisticas['total_docentes']) : '0'; ?>
                </h3>
                <p class="mb-0">Docentes</p>
              </div>
              <div class="stat-icon">
                <i class="fas fa-chalkboard-teacher"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Representantes -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="stat-card bg-representatives p-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h3 class="mb-0" id="total-representantes">
                  <?php echo $datos_estadisticas ? number_format($datos_estadisticas['total_representantes']) : '0'; ?>
                </h3>
                <p class="mb-0">Representantes</p>
              </div>
              <div class="stat-icon">
                <i class="fas fa-users"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Inscripciones del Mes -->
        <!-- Inscripciones del Periodo -->
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
          <div class="stat-card bg-inscriptions p-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h3 class="mb-0" id="inscripciones-periodo">
                  <?php echo $datos_estadisticas ? number_format($datos_estadisticas['inscripciones_periodo']) : '0'; ?>
                </h3>
                <p class="mb-0">Inscripciones del Periodo</p>
                <small><?php echo $datos_estadisticas ? $datos_estadisticas['periodo_activo'] : 'Sin periodo activo'; ?></small>
              </div>
              <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- Gráfico de Inscripciones por Semana -->
        <!-- <div class="row">
        <div class="col-12">
          <div class="chart-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h4 class="mb-0">
                <i class="fas fa-chart-line mr-2 text-primary"></i>
                Inscripciones por Semana
              </h4>
              <div class="week-selector">
                <select class="form-control" id="select-semana">
                  <option value="">Cargando semanas...</option>
                </select>
              </div>
            </div>

            <div id="info-semana" class="mb-3">
              <?php if ($datos_semana): ?>
                <p class="text-muted mb-2">
                  <strong>Semana <?php echo $datos_semana['semana']; ?> del <?php echo $datos_semana['anio']; ?></strong> |
                  <?php echo $datos_semana['rango_semana']; ?>
                </p>
              <?php endif; ?>
            </div>

            Gráfico
            <div class="chart-wrapper">
              <canvas id="chart-inscripciones" height="100"></canvas>
            </div>

            Tabla de datos
            <div class="mt-4">
              <h5>Detalle por día</h5>
              <div class="table-responsive">
                <table class="table table-sm table-bordered" id="tabla-inscripciones">
                  <thead class="thead-light">
                    <tr>
                      <th>Día</th>
                      <th>Fecha</th>
                      <th>Inscripciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    Los datos se cargarán dinámicamente
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div> -->


        <!-- Gráfico de Inscripciones por Mes -->
        <div class="row">
          <div class="col-12">
            <div class="chart-container">
              <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                  <i class="fas fa-chart-line mr-2 text-primary"></i>
                  Inscripciones por Mes - Periodo Activo
                </h4>
                <div class="month-selector">
                  <select class="form-control" id="select-mes">
                    <option value="">Cargando meses...</option>
                  </select>
                </div>
              </div>

              <div id="info-mes" class="mb-3">
                <?php if ($datos_estadisticas): ?>
                  <p class="text-muted mb-2">
                    <strong>Periodo: <?php echo $datos_estadisticas['periodo_activo']; ?></strong>
                  </p>
                <?php endif; ?>
              </div>

              <!-- Gráfico -->
              <div class="chart-wrapper">
                <canvas id="chart-inscripciones" height="100"></canvas>
              </div>

              <!-- Tabla de datos -->
              <div class="mt-4">
                <h5>Detalle por día</h5>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered" id="tabla-inscripciones">
                    <thead class="thead-light">
                      <tr>
                        <th>Día</th>
                        <th>Fecha</th>
                        <th>Día Semana</th>
                        <th>Inscripciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Los datos se cargarán dinámicamente -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Incluir Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let chartInstance = null;
      const ctx = document.getElementById('chart-inscripciones').getContext('2d');

      // ========== CARGAR MESES DISPONIBLES ==========
      function cargarMesesDisponibles() {
        fetch('/final/app/controllers/dashboard/obtener_estadisticas.php?action=meses_disponibles')
          .then(response => response.json())
          .then(data => {
            const select = document.getElementById('select-mes');
            select.innerHTML = '';

            if (data.success && data.meses.length > 0) {
              data.meses.forEach(mes => {
                const option = document.createElement('option');
                option.value = `${mes.mes}-${mes.anio}`;
                option.textContent = `${mes.mes_nombre} ${mes.anio} - ${mes.total_inscripciones} inscripciones`;

                // Seleccionar el mes actual por defecto
                const mesActual = '<?php echo date("n-Y"); ?>';
                if (`${mes.mes}-${mes.anio}` === mesActual) {
                  option.selected = true;
                }

                select.appendChild(option);
              });
            } else {
              select.innerHTML = '<option value="">No hay meses disponibles</option>';
            }
          })
          .catch(error => {
            console.error('Error al cargar meses:', error);
            document.getElementById('select-mes').innerHTML = '<option value="">Error al cargar</option>';
          });
      }

      // ========== CARGAR DATOS DEL MES ==========
      function cargarDatosMes(mes, anio) {
        const url = `/final/app/controllers/dashboard/obtener_estadisticas.php?action=inscripciones_mes&mes=${mes}&anio=${anio}`;

        fetch(url)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              actualizarGrafico(data.data);
              actualizarTabla(data.data);
              actualizarInfoMes(data.data);
            } else {
              console.error('Error al cargar datos:', data.message);
              mostrarMensajeError('Error al cargar los datos del mes');
            }
          })
          .catch(error => {
            console.error('Error:', error);
            mostrarMensajeError('Error de conexión');
          });
      }

      // ========== ACTUALIZAR GRÁFICO ==========
      function actualizarGrafico(datos) {
        // Destruir gráfico anterior si existe
        if (chartInstance) {
          chartInstance.destroy();
        }

        const labels = datos.inscripciones.map(item => item.dia);
        const data = datos.inscripciones.map(item => item.cantidad);
        const diasSemana = datos.inscripciones.map(item => item.dia_semana);

        chartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Inscripciones',
              data: data,
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: {
                display: false
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const index = context.dataIndex;
                    return `Inscripciones: ${context.parsed.y} (${diasSemana[index]})`;
                  }
                }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  stepSize: 1
                },
                title: {
                  display: true,
                  text: 'Cantidad de Inscripciones'
                }
              },
              x: {
                title: {
                  display: true,
                  text: 'Días del Mes'
                }
              }
            }
          }
        });
      }

      // ========== ACTUALIZAR TABLA ==========
      function actualizarTabla(datos) {
        const tbody = document.querySelector('#tabla-inscripciones tbody');
        tbody.innerHTML = '';

        datos.inscripciones.forEach(item => {
          const row = document.createElement('tr');
          row.innerHTML = `
                <td>${item.dia}</td>
                <td>${item.fecha_formateada}</td>
                <td>${item.dia_semana}</td>
                <td class="text-center">
                    <span class="badge ${item.cantidad > 0 ? 'badge-success' : 'badge-secondary'}">
                        ${item.cantidad}
                    </span>
                </td>
            `;
          tbody.appendChild(row);
        });

        // Agregar fila de total
        const totalRow = document.createElement('tr');
        totalRow.className = 'table-info font-weight-bold';
        totalRow.innerHTML = `
            <td colspan="3"><strong>Total del mes</strong></td>
            <td class="text-center">
                <span class="badge badge-primary">${datos.total_mes}</span>
            </td>
        `;
        tbody.appendChild(totalRow);
      }

      // ========== ACTUALIZAR INFORMACIÓN DEL MES ==========
      function actualizarInfoMes(datos) {
        const infoDiv = document.getElementById('info-mes');
        infoDiv.innerHTML = `
            <p class="text-muted mb-2">
                <strong>${datos.mes_nombre} ${datos.anio}</strong> | 
                Periodo: ${datos.periodo_activo} | 
                Total: ${datos.total_mes} inscripciones
            </p>
        `;
      }

      // ========== MOSTRAR MENSAJE DE ERROR ==========
      function mostrarMensajeError(mensaje) {
        const infoDiv = document.getElementById('info-mes');
        infoDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                ${mensaje}
            </div>
        `;
      }

      // ========== EVENT LISTENERS ==========
      document.getElementById('select-mes').addEventListener('change', function() {
        const valor = this.value;
        if (valor) {
          const [mes, anio] = valor.split('-');
          cargarDatosMes(mes, anio);
        }
      });

      // ========== INICIALIZACIÓN ==========
      cargarMesesDisponibles();

      // Cargar datos del mes actual inicialmente
      const mesActual = '<?php echo date("n"); ?>';
      const anioActual = '<?php echo date("Y"); ?>';
      cargarDatosMes(mesActual, anioActual);
    });
  </script>
  <!-- <script>
  document.addEventListener('DOMContentLoaded', function() {
    let chartInstance = null;
    const ctx = document.getElementById('chart-inscripciones').getContext('2d');

    // ========== CARGAR SEMANAS DISPONIBLES ==========
    function cargarSemanasDisponibles() {
      fetch('/final/app/controllers/dashboard/obtener_estadisticas.php?action=semanas_disponibles')
        .then(response => response.json())
        .then(data => {
          const select = document.getElementById('select-semana');
          select.innerHTML = '';

          if (data.success && data.semanas.length > 0) {
            data.semanas.forEach(semana => {
              const fechaInicio = new Date(semana.fecha_inicio).toLocaleDateString('es-ES');
              const option = document.createElement('option');
              option.value = `${semana.semana}-${semana.anio}`;
              option.textContent = `Semana ${semana.semana} del ${semana.anio} (${fechaInicio}) - ${semana.total_inscripciones} inscripciones`;

              // Seleccionar la semana actual por defecto
              const semanaActual = '<?php echo date("W-Y"); ?>';
              if (`${semana.semana}-${semana.anio}` === semanaActual) {
                option.selected = true;
              }

              select.appendChild(option);
            });
          } else {
            select.innerHTML = '<option value="">No hay semanas disponibles</option>';
          }
        })
        .catch(error => {
          console.error('Error al cargar semanas:', error);
          document.getElementById('select-semana').innerHTML = '<option value="">Error al cargar</option>';
        });
    }

    // ========== CARGAR DATOS DE LA SEMANA ==========
    function cargarDatosSemana(semana, anio) {
      const url = `/final/app/controllers/dashboard/obtener_estadisticas.php?action=inscripciones_semana&semana=${semana}&anio=${anio}`;

      fetch(url)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            actualizarGrafico(data.data);
            actualizarTabla(data.data);
            actualizarInfoSemana(data.data);
          } else {
            console.error('Error al cargar datos:', data.message);
            mostrarMensajeError('Error al cargar los datos de la semana');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          mostrarMensajeError('Error de conexión');
        });
    }

    // ========== ACTUALIZAR GRÁFICO ==========
    function actualizarGrafico(datos) {
      // Destruir gráfico anterior si existe
      if (chartInstance) {
        chartInstance.destroy();
      }

      const labels = datos.inscripciones.map(item => item.dia);
      const data = datos.inscripciones.map(item => item.cantidad);
      const fechas = datos.inscripciones.map(item => item.fecha_formateada);

      chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Inscripciones',
            data: data,
            backgroundColor: [
              'rgba(102, 126, 234, 0.8)',
              'rgba(240, 147, 251, 0.8)',
              'rgba(79, 172, 254, 0.8)',
              'rgba(67, 233, 123, 0.8)',
              'rgba(255, 193, 7, 0.8)',
              'rgba(220, 53, 69, 0.8)',
              'rgba(108, 117, 125, 0.8)'
            ],
            borderColor: [
              'rgba(102, 126, 234, 1)',
              'rgba(240, 147, 251, 1)',
              'rgba(79, 172, 254, 1)',
              'rgba(67, 233, 123, 1)',
              'rgba(255, 193, 7, 1)',
              'rgba(220, 53, 69, 1)',
              'rgba(108, 117, 125, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const index = context.dataIndex;
                  return `Inscripciones: ${context.parsed.y} (${fechas[index]})`;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                stepSize: 1
              },
              title: {
                display: true,
                text: 'Cantidad de Inscripciones'
              }
            },
            x: {
              title: {
                display: true,
                text: 'Días de la Semana'
              }
            }
          }
        }
      });
    }

    // ========== ACTUALIZAR TABLA ==========
    function actualizarTabla(datos) {
      const tbody = document.querySelector('#tabla-inscripciones tbody');
      tbody.innerHTML = '';

      let totalInscripciones = 0;

      datos.inscripciones.forEach(item => {
        const row = document.createElement('tr');
        row.innerHTML = `
                <td>${item.dia}</td>
                <td>${item.fecha_formateada}</td>
                <td class="text-center">
                    <span class="badge ${item.cantidad > 0 ? 'badge-success' : 'badge-secondary'}">
                        ${item.cantidad}
                    </span>
                </td>
            `;
        tbody.appendChild(row);
        totalInscripciones += item.cantidad;
      });

      // Agregar fila de total
      const totalRow = document.createElement('tr');
      totalRow.className = 'table-info font-weight-bold';
      totalRow.innerHTML = `
            <td colspan="2"><strong>Total de la semana</strong></td>
            <td class="text-center">
                <span class="badge badge-primary">${totalInscripciones}</span>
            </td>
        `;
      tbody.appendChild(totalRow);
    }

    // ========== ACTUALIZAR INFORMACIÓN DE LA SEMANA ==========
    function actualizarInfoSemana(datos) {
      const infoDiv = document.getElementById('info-semana');
      infoDiv.innerHTML = `
            <p class="text-muted mb-2">
                <strong>Semana ${datos.semana} del ${datos.anio}</strong> | 
                ${datos.rango_semana}
            </p>
        `;
    }

    // ========== MOSTRAR MENSAJE DE ERROR ==========
    function mostrarMensajeError(mensaje) {
      const infoDiv = document.getElementById('info-semana');
      infoDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                ${mensaje}
            </div>
        `;
    }

    // ========== EVENT LISTENERS ==========
    document.getElementById('select-semana').addEventListener('change', function() {
      const valor = this.value;
      if (valor) {
        const [semana, anio] = valor.split('-');
        cargarDatosSemana(semana, anio);
      }
    });

    // ========== INICIALIZACIÓN ==========
    cargarSemanasDisponibles();

    // Cargar datos de la semana actual inicialmente
    const semanaActual = '<?php echo date("W"); ?>';
    const anioActual = '<?php echo date("Y"); ?>';
    cargarDatosSemana(semanaActual, anioActual);
  });
</script> -->

  <?php
  include_once("/xampp/htdocs/final/layout/layaout2.php");
  include_once("/xampp/htdocs/final/layout/mensajes.php");
  ?>

  <!-- TABLA DE PARENTEZCO  PARA PARENTEZCO DEL REPRESENTANTE ESTUDIANTE -->
  <!-- Modificar fecha para que tambien sea por mes -->