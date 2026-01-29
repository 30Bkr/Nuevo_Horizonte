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
                      id="filtroMunicipios"
                      class="form-control"
                      placeholder="Buscar municipio por nombre o estado..."
                      aria-label="Buscar municipio">
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
                      <span id="contadorResultados">0</span> de <?php echo count($municipios); ?> municipios
                    </div>
                    <div class="form-inline">
                      <label for="itemsPorPagina" class="mr-2">Mostrar:</label>
                      <select id="itemsPorPagina" class="form-control form-control-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="0">Todos</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contenedor de la tabla -->
              <div id="tablaMunicipiosContainer">
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
                    <tbody id="cuerpoTablaMunicipios">
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
                          <tr data-id="<?php echo $municipio['id_municipio']; ?>"
                            data-nombre="<?php echo htmlspecialchars(strtolower($municipio['nom_municipio'])); ?>"
                            data-estado="<?php echo htmlspecialchars(strtolower($municipio['nom_estado'])); ?>"
                            data-id-texto="<?php echo $municipio['id_municipio']; ?>">
                            <td class="col-id"><?php echo $municipio['id_municipio']; ?></td>
                            <td class="col-nombre"><?php echo htmlspecialchars($municipio['nom_municipio']); ?></td>
                            <td class="col-estado"><?php echo htmlspecialchars($municipio['nom_estado']); ?></td>
                            <td class="col-creacion"><?php echo date('d/m/Y H:i', strtotime($municipio['creacion'])); ?></td>
                            <td class="col-actualizacion">
                              <?php
                              if ($municipio['actualizacion']) {
                                echo date('d/m/Y H:i', strtotime($municipio['actualizacion']));
                              } else {
                                echo 'No actualizado';
                              }
                              ?>
                            </td>
                            <td class="col-en-uso">
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
                            <td class="col-estatus">
                              <span class="badge badge-<?php echo $municipio['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $municipio['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                              </span>
                            </td>
                            <td class="col-acciones">
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

              <!-- Paginación -->
              <div class="row mt-3">
                <div class="col-md-6">
                  <nav id="paginacion" aria-label="Paginación de municipios">
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

      <!-- Información adicional (se mantiene igual) -->
      <!-- ... resto de tu código ... -->

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
  #filtroMunicipios:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  .bg-highlight {
    background-color: #fff3cd !important;
    color: #856404 !important;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
  }

  .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
  }

  .page-link {
    color: #007bff;
  }

  .page-link:hover {
    color: #0056b3;
    background-color: #e9ecef;
    border-color: #dee2e6;
  }
</style>

<!-- Mantén todo igual hasta la tabla, luego modifica solo el script -->

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Elementos DOM
    const filtroInput = document.getElementById('filtroMunicipios');
    const limpiarBtn = document.getElementById('limpiarBusqueda');
    const infoBusqueda = document.getElementById('infoBusqueda');
    const contadorResultados = document.getElementById('contadorResultados');
    const itemsPorPaginaSelect = document.getElementById('itemsPorPagina');
    const cuerpoTabla = document.getElementById('cuerpoTablaMunicipios');
    const tablaContainer = document.getElementById('tablaMunicipiosContainer');
    const paginacion = document.getElementById('listaPaginacion');
    const infoPaginacion = document.getElementById('infoPaginacion');

    // Variables de estado
    let todosMunicipios = [];
    let municipiosFiltrados = [];
    let municipiosPagina = [];
    let paginaActual = 1;
    let itemsPorPagina = 10;
    let terminoBusqueda = '';
    let columnaOrden = 1; // Columna por defecto para ordenar (nombre)
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
      // Recopilar todos los municipios de la tabla
      todosMunicipios = Array.from(cuerpoTabla.querySelectorAll('tr')).map(fila => {
        // Solo procesar filas con datos (no la fila de "no hay municipios")
        if (fila.querySelector('td[colspan]')) {
          return null; // Esta fila es el mensaje "no hay municipios"
        }

        const municipio = {
          elemento: fila.cloneNode(true), // Clonar la fila para preservarla
          datos: {
            id: getTextoTd(fila, 0),
            nombre: getTextoTd(fila, 1),
            estado: getTextoTd(fila, 2),
            creacion: getTextoTd(fila, 3),
            actualizacion: getTextoTd(fila, 4),
            enUsoHTML: getHtmlTd(fila, 5),
            estatusHTML: getHtmlTd(fila, 6),
            accionesHTML: getHtmlTd(fila, 7)
          }
        };

        // Guardar también el HTML completo de la fila para reconstruirla
        municipio.htmlCompleto = fila.innerHTML;

        return municipio;
      }).filter(m => m !== null); // Filtrar nulos

      console.log('Municipios cargados:', todosMunicipios.length); // Debug

      // Configurar eventos
      configurarEventos();

      // Si no hay municipios, mostrar mensaje
      if (todosMunicipios.length === 0) {
        cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center">No hay municipios registrados</td>
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
        municipiosFiltrados = [...todosMunicipios];
        limpiarBtn.style.display = 'none';
        infoBusqueda.style.display = 'none';
        return;
      }

      // Filtrar municipios
      municipiosFiltrados = todosMunicipios.filter(municipio => {
        const nombreNormalizado = normalizarTexto(municipio.datos.nombre);
        const estadoNormalizado = normalizarTexto(municipio.datos.estado);
        const idTexto = municipio.datos.id;

        return nombreNormalizado.includes(terminoNormalizado) ||
          estadoNormalizado.includes(terminoNormalizado) ||
          idTexto.includes(terminoNormalizado);
      });

      // Actualizar UI
      limpiarBtn.style.display = 'block';
      infoBusqueda.style.display = 'block';
      contadorResultados.textContent = municipiosFiltrados.length;
    }

    // Aplicar ordenamiento
    function aplicarOrden() {
      municipiosFiltrados.sort((a, b) => {
        let valorA, valorB;

        switch (columnaOrden) {
          case 0: // ID
            valorA = parseInt(a.datos.id) || 0;
            valorB = parseInt(b.datos.id) || 0;
            break;

          case 1: // Nombre del municipio
            valorA = normalizarTexto(a.datos.nombre);
            valorB = normalizarTexto(b.datos.nombre);
            break;

          case 2: // Estado
            valorA = normalizarTexto(a.datos.estado);
            valorB = normalizarTexto(b.datos.estado);
            break;

          case 3: // Fecha de creación
            valorA = a.datos.creacion;
            valorB = b.datos.creacion;
            break;

          case 4: // Fecha de actualización
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
        municipiosPagina = [...municipiosFiltrados];
        return;
      }

      const inicio = (paginaActual - 1) * itemsPorPagina;
      const fin = inicio + itemsPorPagina;
      municipiosPagina = municipiosFiltrados.slice(inicio, fin);
    }

    // Actualizar tabla
    function actualizarTabla() {
      // Si no hay municipios originales, salir
      if (todosMunicipios.length === 0) {
        return;
      }

      // Aplicar filtro si hay término de búsqueda
      if (terminoBusqueda) {
        aplicarFiltro();
      } else {
        municipiosFiltrados = [...todosMunicipios];
      }

      // Paginar resultados
      paginarResultados();

      // Limpiar tabla
      cuerpoTabla.innerHTML = '';

      // Mostrar mensaje si no hay resultados después del filtro
      if (municipiosPagina.length === 0) {
        cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-search fa-2x text-muted mb-2"></i>
                        <p class="mb-0">No se encontraron municipios que coincidan con "${terminoBusqueda}"</p>
                    </td>
                </tr>
            `;
      } else {
        // Agregar municipios a la tabla
        municipiosPagina.forEach(municipio => {
          const fila = document.createElement('tr');

          // Resaltar término de búsqueda si existe
          if (terminoBusqueda) {
            // Reconstruir cada celda con resaltado
            const idHTML = resaltarTexto(municipio.datos.id, terminoBusqueda);
            const nombreHTML = resaltarTexto(municipio.datos.nombre, terminoBusqueda);
            const estadoHTML = resaltarTexto(municipio.datos.estado, terminoBusqueda);

            fila.innerHTML = `
                        <td>${idHTML}</td>
                        <td>${nombreHTML}</td>
                        <td>${estadoHTML}</td>
                        <td>${municipio.datos.creacion}</td>
                        <td>${municipio.datos.actualizacion}</td>
                        <td>${municipio.datos.enUsoHTML}</td>
                        <td>${municipio.datos.estatusHTML}</td>
                        <td>${municipio.datos.accionesHTML}</td>
                    `;
          } else {
            // Usar el HTML completo original
            fila.innerHTML = municipio.htmlCompleto;
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
      const totalPaginas = itemsPorPagina === 0 ? 1 : Math.ceil(municipiosFiltrados.length / itemsPorPagina);

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
                Mostrando todos los ${municipiosFiltrados.length} municipios
            `;
      } else {
        const inicio = (paginaActual - 1) * itemsPorPagina + 1;
        const fin = Math.min(paginaActual * itemsPorPagina, municipiosFiltrados.length);
        infoPaginacion.innerHTML = `
                Mostrando ${inicio} a ${fin} de ${municipiosFiltrados.length} municipios
                ${terminoBusqueda ? `(filtrados por: "${terminoBusqueda}")` : ''}
            `;
      }
    }

    // Inicializar la aplicación
    inicializar();
  });
</script>

<!-- Mantén el resto del código igual -->

<?php
// Cerrar conexión
$conexion->desconectar();
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>