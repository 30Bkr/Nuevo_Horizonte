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
              <!-- Barra de búsqueda y controles -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-search"></i>
                      </span>
                    </div>
                    <input type="text"
                      id="filtroParroquias"
                      class="form-control"
                      placeholder="Buscar parroquia por nombre, municipio o estado..."
                      aria-label="Buscar parroquia">
                    <div class="input-group-append">
                      <button id="limpiarBusqueda" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <small class="form-text text-muted">
                    Escribe para filtrar en tiempo real
                  </small>
                </div>
                <div class="col-md-6">
                  <div class="d-flex justify-content-between align-items-center">
                    <div id="infoBusqueda" class="alert alert-info mb-0 py-2" style="display: none;">
                      <i class="fas fa-filter mr-1"></i>
                      <span id="contadorResultados">0</span> de <?php echo count($parroquias); ?> parroquias
                    </div>
                    <div class="form-inline">
                      <label for="itemsPorPagina" class="mr-2">Mostrar:</label>
                      <select id="itemsPorPagina" class="form-control form-control-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="0">Todas</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contenedor de la tabla -->
              <div id="tablaParroquiasContainer">
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
                    <tbody id="cuerpoTablaParroquias">
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
                            <td class="col-id"><?php echo $parroquia['id_parroquia']; ?></td>
                            <td class="col-parroquia"><?php echo htmlspecialchars($parroquia['nom_parroquia']); ?></td>
                            <td class="col-municipio"><?php echo htmlspecialchars($parroquia['nom_municipio']); ?></td>
                            <td class="col-estado"><?php echo htmlspecialchars($parroquia['nom_estado']); ?></td>
                            <td class="col-creacion"><?php echo date('d/m/Y H:i', strtotime($parroquia['creacion'])); ?></td>
                            <td class="col-actualizacion">
                              <?php
                              if ($parroquia['actualizacion']) {
                                echo date('d/m/Y H:i', strtotime($parroquia['actualizacion']));
                              } else {
                                echo 'No actualizado';
                              }
                              ?>
                            </td>
                            <td class="col-en-uso">
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
                            <td class="col-estatus">
                              <span class="badge badge-<?php echo $parroquia['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $parroquia['estatus'] == 1 ? 'Habilitada' : 'Inhabilitada'; ?>
                              </span>
                            </td>
                            <td class="col-acciones">
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

              <!-- Paginación -->
              <div class="row mt-3">
                <div class="col-md-6">
                  <nav id="paginacion" aria-label="Paginación de parroquias">
                    <ul class="pagination pagination-sm" id="listaPaginacion">
                      <!-- La paginación se generará dinámicamente -->
                    </ul>
                  </nav>
                </div>
                <div class="col-md-6 text-right">
                  <div id="infoPaginacion" class="text-muted">
                    <!-- La información de paginación se actualizará aquí -->
                  </div>
                </div>
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
              <li><strong>Nueva función:</strong> Use el campo de búsqueda para filtrar parroquias en tiempo real</li>
              <li><strong>Nueva función:</strong> Use la paginación para navegar por grandes listas de parroquias</li>
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
    cursor: pointer;
    user-select: none;
  }

  .table th:hover {
    background-color: #e9ecef;
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

  /* Estilos para el filtro y paginación */
  #filtroParroquias:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
  }

  .bg-highlight {
    background-color: #fff3cd !important;
    color: #856404 !important;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
  }

  .page-item.active .page-link {
    background-color: #28a745;
    border-color: #28a745;
  }

  .page-link {
    color: #28a745;
  }

  .page-link:hover {
    color: #1e7e34;
    background-color: #e9ecef;
    border-color: #dee2e6;
  }

  .sorting-asc::after {
    content: " ▲";
    font-size: 0.8em;
    opacity: 0.7;
  }

  .sorting-desc::after {
    content: " ▼";
    font-size: 0.8em;
    opacity: 0.7;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const filtroInput = document.getElementById('filtroParroquias');
    const limpiarBtn = document.getElementById('limpiarBusqueda');
    const infoBusqueda = document.getElementById('infoBusqueda');
    const contadorResultados = document.getElementById('contadorResultados');
    const itemsPorPaginaSelect = document.getElementById('itemsPorPagina');
    const cuerpoTabla = document.getElementById('cuerpoTablaParroquias');
    const tablaContainer = document.getElementById('tablaParroquiasContainer');
    const paginacion = document.getElementById('listaPaginacion');
    const infoPaginacion = document.getElementById('infoPaginacion');

    // Variables de estado
    let todasParroquias = [];
    let parroquiasFiltradas = [];
    let parroquiasPagina = [];
    let paginaActual = 1;
    let itemsPorPagina = 10;
    let terminoBusqueda = '';
    let columnaOrden = 1; // Columna por defecto para ordenar (nombre de parroquia)
    let ordenAscendente = true;

    // Función para extraer texto de una celda TD
    function getTextoTd(fila, indiceColumna) {
      const celdas = fila.querySelectorAll('td');
      if (celdas.length > indiceColumna) {
        return celdas[indiceColumna].textContent.trim();
      }
      return '';
    }

    // Función para extraer HTML de una celda TD
    function getHtmlTd(fila, indiceColumna) {
      const celdas = fila.querySelectorAll('td');
      if (celdas.length > indiceColumna) {
        return celdas[indiceColumna].innerHTML;
      }
      return '';
    }

    // Inicialización
    function inicializar() {
      // Recopilar todas las parroquias de la tabla
      todasParroquias = Array.from(cuerpoTabla.querySelectorAll('tr')).map(fila => {
        // Solo procesar filas con datos (no la fila de "no hay parroquias")
        if (fila.querySelector('td[colspan]')) {
          return null; // Esta fila es el mensaje "no hay parroquias"
        }

        const parroquia = {
          elemento: fila.cloneNode(true), // Clonar la fila para preservarla
          datos: {
            id: getTextoTd(fila, 0),
            nombre: getTextoTd(fila, 1),
            municipio: getTextoTd(fila, 2),
            estado: getTextoTd(fila, 3),
            creacion: getTextoTd(fila, 4),
            actualizacion: getTextoTd(fila, 5),
            enUsoHTML: getHtmlTd(fila, 6),
            estatusHTML: getHtmlTd(fila, 7),
            accionesHTML: getHtmlTd(fila, 8)
          }
        };

        // Guardar también el HTML completo de la fila para reconstruirla
        parroquia.htmlCompleto = fila.innerHTML;

        return parroquia;
      }).filter(p => p !== null); // Filtrar nulos

      console.log('Parroquias cargadas:', todasParroquias.length); // Debug

      // Configurar eventos
      configurarEventos();

      // Si no hay parroquias, mostrar mensaje
      if (todasParroquias.length === 0) {
        cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">No hay parroquias registradas</td>
                </tr>
            `;
        itemsPorPaginaSelect.disabled = true;
        return;
      }

      // Aplicar orden inicial
      aplicarOrden();

      // Actualizar la tabla
      actualizarTabla();
    }

    // Configurar eventos
    function configurarEventos() {
      // Filtro de búsqueda
      filtroInput.addEventListener('input', function() {
        terminoBusqueda = this.value.trim();
        paginaActual = 1;
        aplicarFiltro();
        actualizarTabla();
      });

      // Limpiar búsqueda
      limpiarBtn.addEventListener('click', function() {
        filtroInput.value = '';
        terminoBusqueda = '';
        paginaActual = 1;
        aplicarFiltro();
        actualizarTabla();
        filtroInput.focus();
      });

      // Cambiar items por página
      itemsPorPaginaSelect.addEventListener('change', function() {
        itemsPorPagina = parseInt(this.value);
        paginaActual = 1;
        actualizarTabla();
      });

      // Ordenar por columnas (click en cabeceras)
      document.querySelectorAll('thead th').forEach((th, index) => {
        th.addEventListener('click', () => {
          if (columnaOrden === index) {
            ordenAscendente = !ordenAscendente;
          } else {
            columnaOrden = index;
            ordenAscendente = true;
          }
          aplicarOrden();
          actualizarTabla();
        });
      });

      // Tecla ESC para limpiar filtro
      filtroInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
          this.value = '';
          terminoBusqueda = '';
          paginaActual = 1;
          aplicarFiltro();
          actualizarTabla();
        }
      });
    }

    // Normalizar texto para búsqueda
    function normalizarTexto(texto) {
      if (!texto) return '';
      return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
    }

    // Aplicar filtro de búsqueda
    function aplicarFiltro() {
      const terminoNormalizado = normalizarTexto(terminoBusqueda);

      if (!terminoNormalizado) {
        parroquiasFiltradas = [...todasParroquias];
        limpiarBtn.style.display = 'none';
        infoBusqueda.style.display = 'none';
        return;
      }

      // Filtrar parroquias
      parroquiasFiltradas = todasParroquias.filter(parroquia => {
        const nombreNormalizado = normalizarTexto(parroquia.datos.nombre);
        const municipioNormalizado = normalizarTexto(parroquia.datos.municipio);
        const estadoNormalizado = normalizarTexto(parroquia.datos.estado);
        const idTexto = parroquia.datos.id;

        return nombreNormalizado.includes(terminoNormalizado) ||
          municipioNormalizado.includes(terminoNormalizado) ||
          estadoNormalizado.includes(terminoNormalizado) ||
          idTexto.includes(terminoNormalizado);
      });

      // Actualizar UI
      limpiarBtn.style.display = 'block';
      infoBusqueda.style.display = 'block';
      contadorResultados.textContent = parroquiasFiltradas.length;
    }

    // Aplicar ordenamiento
    function aplicarOrden() {
      parroquiasFiltradas.sort((a, b) => {
        let valorA, valorB;

        switch (columnaOrden) {
          case 0: // ID
            valorA = parseInt(a.datos.id) || 0;
            valorB = parseInt(b.datos.id) || 0;
            break;

          case 1: // Nombre de la parroquia
            valorA = normalizarTexto(a.datos.nombre);
            valorB = normalizarTexto(b.datos.nombre);
            break;

          case 2: // Municipio
            valorA = normalizarTexto(a.datos.municipio);
            valorB = normalizarTexto(b.datos.municipio);
            break;

          case 3: // Estado
            valorA = normalizarTexto(a.datos.estado);
            valorB = normalizarTexto(b.datos.estado);
            break;

          case 4: // Fecha de creación
            valorA = a.datos.creacion;
            valorB = b.datos.creacion;
            break;

          case 5: // Fecha de actualización
            valorA = a.datos.actualizacion;
            valorB = b.datos.actualizacion;
            break;

          default:
            valorA = a.datos.nombre;
            valorB = b.datos.nombre;
        }

        // Comparar valores
        if (valorA < valorB) return ordenAscendente ? -1 : 1;
        if (valorA > valorB) return ordenAscendente ? 1 : -1;
        return 0;
      });

      // Actualizar indicadores de orden en cabeceras
      document.querySelectorAll('thead th').forEach((th, index) => {
        th.classList.remove('sorting-asc', 'sorting-desc');
        if (index === columnaOrden) {
          th.classList.add(ordenAscendente ? 'sorting-asc' : 'sorting-desc');
        }
      });
    }

    // Resaltar texto en los resultados
    function resaltarTexto(texto, busqueda) {
      if (!busqueda || !texto) return texto;

      const terminoNormalizado = normalizarTexto(busqueda);
      const textoNormalizado = normalizarTexto(texto);

      if (!textoNormalizado.includes(terminoNormalizado)) {
        return texto;
      }

      // Buscar coincidencias manteniendo mayúsculas y acentos
      const regex = new RegExp(`(${busqueda.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
      return texto.replace(regex, '<span class="bg-highlight">$1</span>');
    }

    // Paginar resultados
    function paginarResultados() {
      if (itemsPorPagina === 0) {
        parroquiasPagina = [...parroquiasFiltradas];
        return;
      }

      const inicio = (paginaActual - 1) * itemsPorPagina;
      const fin = inicio + itemsPorPagina;
      parroquiasPagina = parroquiasFiltradas.slice(inicio, fin);
    }

    // Actualizar tabla
    function actualizarTabla() {
      // Si no hay parroquias originales, salir
      if (todasParroquias.length === 0) {
        return;
      }

      // Aplicar filtro si hay término de búsqueda
      if (terminoBusqueda) {
        aplicarFiltro();
      } else {
        parroquiasFiltradas = [...todasParroquias];
      }

      // Paginar resultados
      paginarResultados();

      // Limpiar tabla
      cuerpoTabla.innerHTML = '';

      // Mostrar mensaje si no hay resultados después del filtro
      if (parroquiasPagina.length === 0) {
        cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                        <p class="mb-0">No se encontraron parroquias que coincidan con "${terminoBusqueda}"</p>
                    </td>
                </tr>
            `;
      } else {
        // Agregar parroquias a la tabla
        parroquiasPagina.forEach(parroquia => {
          const fila = document.createElement('tr');

          // Resaltar término de búsqueda si existe
          if (terminoBusqueda) {
            // Reconstruir cada celda con resaltado
            const idHTML = resaltarTexto(parroquia.datos.id, terminoBusqueda);
            const nombreHTML = resaltarTexto(parroquia.datos.nombre, terminoBusqueda);
            const municipioHTML = resaltarTexto(parroquia.datos.municipio, terminoBusqueda);
            const estadoHTML = resaltarTexto(parroquia.datos.estado, terminoBusqueda);

            fila.innerHTML = `
                        <td>${idHTML}</td>
                        <td>${nombreHTML}</td>
                        <td>${municipioHTML}</td>
                        <td>${estadoHTML}</td>
                        <td>${parroquia.datos.creacion}</td>
                        <td>${parroquia.datos.actualizacion}</td>
                        <td>${parroquia.datos.enUsoHTML}</td>
                        <td>${parroquia.datos.estatusHTML}</td>
                        <td>${parroquia.datos.accionesHTML}</td>
                    `;
          } else {
            // Usar el HTML completo original
            fila.innerHTML = parroquia.htmlCompleto;
          }

          cuerpoTabla.appendChild(fila);
        });

        // Re-inicializar tooltips
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
          setTimeout(() => {
            $('[data-toggle="tooltip"]').tooltip();
          }, 100);
        }
      }

      // Actualizar paginación
      actualizarPaginacion();
    }

    // Actualizar paginación
    function actualizarPaginacion() {
      const totalPaginas = itemsPorPagina === 0 ? 1 : Math.ceil(parroquiasFiltradas.length / itemsPorPagina);

      // Limpiar paginación
      paginacion.innerHTML = '';

      // Mostrar paginación solo si hay más de una página
      if (totalPaginas <= 1 || itemsPorPagina === 0) {
        paginacion.style.display = 'none';
      } else {
        paginacion.style.display = 'flex';

        // Botón anterior
        const liAnterior = document.createElement('li');
        liAnterior.className = `page-item ${paginaActual === 1 ? 'disabled' : ''}`;
        liAnterior.innerHTML = `
                <a class="page-link" href="#" data-page="${paginaActual - 1}" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            `;
        paginacion.appendChild(liAnterior);

        // Números de página
        const paginasMostrar = [];
        const paginasALaVista = 3;

        // Calcular rango de páginas a mostrar
        let inicio = Math.max(1, paginaActual - paginasALaVista);
        let fin = Math.min(totalPaginas, paginaActual + paginasALaVista);

        // Ajustar para mostrar siempre el mismo número de páginas si es posible
        if (fin - inicio < paginasALaVista * 2) {
          if (paginaActual < paginasALaVista) {
            fin = Math.min(totalPaginas, paginasALaVista * 2 + 1);
          } else if (paginaActual > totalPaginas - paginasALaVista) {
            inicio = Math.max(1, totalPaginas - paginasALaVista * 2);
          }
        }

        // Primera página si no está en el rango
        if (inicio > 1) {
          paginasMostrar.push(1);
          if (inicio > 2) paginasMostrar.push('...');
        }

        // Páginas del rango
        for (let i = inicio; i <= fin; i++) {
          paginasMostrar.push(i);
        }

        // Última página si no está en el rango
        if (fin < totalPaginas) {
          if (fin < totalPaginas - 1) paginasMostrar.push('...');
          paginasMostrar.push(totalPaginas);
        }

        // Crear elementos de página
        paginasMostrar.forEach(pagina => {
          const li = document.createElement('li');
          if (pagina === '...') {
            li.className = 'page-item disabled';
            li.innerHTML = '<span class="page-link">...</span>';
          } else {
            li.className = `page-item ${pagina === paginaActual ? 'active' : ''}`;
            li.innerHTML = `
                        <a class="page-link" href="#" data-page="${pagina}">${pagina}</a>
                    `;
          }
          paginacion.appendChild(li);
        });

        // Botón siguiente
        const liSiguiente = document.createElement('li');
        liSiguiente.className = `page-item ${paginaActual === totalPaginas ? 'disabled' : ''}`;
        liSiguiente.innerHTML = `
                <a class="page-link" href="#" data-page="${paginaActual + 1}" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            `;
        paginacion.appendChild(liSiguiente);

        // Configurar eventos de paginación
        paginacion.querySelectorAll('.page-link[data-page]').forEach(link => {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            const nuevaPagina = parseInt(this.getAttribute('data-page'));
            if (nuevaPagina >= 1 && nuevaPagina <= totalPaginas && nuevaPagina !== paginaActual) {
              paginaActual = nuevaPagina;
              actualizarTabla();
              window.scrollTo({
                top: tablaContainer.offsetTop - 20,
                behavior: 'smooth'
              });
            }
          });
        });
      }

      // Actualizar información de paginación
      if (itemsPorPagina === 0) {
        infoPaginacion.innerHTML = `
                Mostrando todas las ${parroquiasFiltradas.length} parroquias
            `;
      } else {
        const inicio = (paginaActual - 1) * itemsPorPagina + 1;
        const fin = Math.min(paginaActual * itemsPorPagina, parroquiasFiltradas.length);
        infoPaginacion.innerHTML = `
                Mostrando ${inicio} a ${fin} de ${parroquiasFiltradas.length} parroquias
                ${terminoBusqueda ? `(filtradas por: "${terminoBusqueda}")` : ''}
            `;
      }
    }

    // Inicializar la aplicación
    inicializar();
  });
</script>

<script>
  // Inicializar tooltips para elementos estáticos
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