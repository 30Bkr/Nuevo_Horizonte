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

  /* FIX para DataTables y layout */
  .dataTables_wrapper {
    margin-top: 15px;
  }

  .dataTables_filter input {
    margin-left: 10px !important;
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    padding: 6px 12px !important;
  }

  .dataTables_length select {
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    padding: 6px 12px !important;
  }

  .dataTables_info {
    padding-top: 10px !important;
  }

  .dataTables_paginate .paginate_button {
    margin: 0 2px !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 4px !important;
  }

  /* Asegurar que la tabla ocupe el 100% */
  #tabla-inscripciones {
    width: 100% !important;
  }

  /* Espaciado para el contenido */
  .chart-wrapper {
    margin-bottom: 20px;
    height: 300px;
  }

  canvas {
    width: 100% !important;
    height: 300px !important;
  }
</style>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mt-3">
        <div class="col-12">
          <div class="page-header">
            <h1><i class="fas fa-tachometer-alt mr-2"></i>Panel de Estadísticas </h1>
            <p class="text-muted">Visualización de matrícula y comunidad educativa: métricas de inscripciones por periodo, flujo mensual y actividad diaria.</p>
          </div>
        </div>
      </div>

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
      </div>

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

            <!-- Tabla de datos CON DATATABLES -->
            <div class="mt-4">
              <h5>Detalle por día</h5>
              <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover" id="tabla-inscripciones">
                  <thead class="thead-light">
                    <tr>
                      <th>Día</th>
                      <th>Fecha</th>
                      <th>Día Semana</th>
                      <th class="text-center">Inscripciones</th>
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
    let dataTableInstance = null;
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
          maintainAspectRatio: false,
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

    // ========== ACTUALIZAR TABLA CON DATATABLES ==========


    // ========== ACTUALIZAR TABLA CON DATATABLES (VERSIÓN ROBUSTA) ==========
    function actualizarTabla(datos) {
      // Obtener la tabla y su contenedor
      const tableContainer = document.querySelector('.table-responsive');
      const oldTable = document.getElementById('tabla-inscripciones');

      // Destruir DataTable anterior
      if (dataTableInstance) {
        try {
          dataTableInstance.destroy();
        } catch (e) {
          console.log('Error al destruir DataTable:', e);
        }
        dataTableInstance = null;
      }

      // Crear nueva tabla HTML
      const newTableHTML = `
    <table class="table table-sm table-bordered table-hover" id="tabla-inscripciones">
      <thead class="thead-light">
        <tr>
          <th>Día</th>
          <th>Fecha</th>
          <th>Día Semana</th>
          <th class="text-center">Inscripciones</th>
        </tr>
      </thead>
      <tbody>
        ${datos.inscripciones.map(item => `
          <tr>
            <td>${item.dia}</td>
            <td>${item.fecha_formateada}</td>
            <td>${item.dia_semana}</td>
            <td class="text-center">
              <span class="badge ${item.cantidad > 0 ? 'badge-success' : 'badge-secondary'}">
                ${item.cantidad}
              </span>
            </td>
          </tr>
        `).join('')}
      </tbody>
    </table>
  `;

      // Reemplazar la tabla completa
      tableContainer.innerHTML = newTableHTML;

      // Inicializar DataTable en la nueva tabla
      setTimeout(function() {
        if (typeof $.fn.DataTable !== 'undefined') {
          dataTableInstance = $('#tabla-inscripciones').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [
              [10, 25, 50, -1],
              [10, 25, 50, "Todos"]
            ],
            "language": {
              "decimal": "",
              "emptyTable": "No hay inscripciones para este mes",
              "info": "Mostrando _START_ a _END_ de _TOTAL_ días",
              "infoEmpty": "Mostrando 0 a 0 de 0 días",
              "infoFiltered": "(filtrado de _MAX_ días totales)",
              "infoPostFix": "",
              "thousands": ",",
              "lengthMenu": "Mostrar _MENU_ días por página",
              "loadingRecords": "Cargando...",
              "processing": "Procesando...",
              "search": "Buscar día:",
              "zeroRecords": "No se encontraron días coincidentes",
              "paginate": {
                "first": "Primero",
                "last": "Último",
                "next": "Siguiente",
                "previous": "Anterior"
              }
            },
            "order": [
              [0, "asc"]
            ],
            "drawCallback": function(settings) {
              $('.dataTables_length, .dataTables_filter').show();
            }
          });

          console.log('DataTable inicializado con', datos.inscripciones.length, 'días');
        }
      }, 100);
    }

    // ========== CARGAR DATATABLES DINÁMICAMENTE SI NO ESTÁ DISPONIBLE ==========
    function cargarDataTables() {
      // Verificar si ya está cargado
      if (typeof $.fn.DataTable !== 'undefined') {
        return;
      }

      console.log('Cargando DataTables dinámicamente...');

      // Cargar DataTables JS
      const script = document.createElement('script');
      script.src = '/final/public/plugins/datatables/jquery.dataTables.min.js';
      script.onload = function() {
        // Cargar DataTables Bootstrap 4 JS
        const script2 = document.createElement('script');
        script2.src = '/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';
        script2.onload = function() {
          // Cargar CSS de DataTables
          const link = document.createElement('link');
          link.rel = 'stylesheet';
          link.href = '/final/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css';
          document.head.appendChild(link);

          console.log('DataTables cargado dinámicamente');
        };
        document.body.appendChild(script2);
      };
      document.body.appendChild(script);
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

    // Verificar y cargar DataTables si es necesario
    setTimeout(cargarDataTables, 500);
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>