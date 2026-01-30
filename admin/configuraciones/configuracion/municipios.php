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
                      <?php 
                      // Ordenar municipios alfabéticamente
                      usort($municipios, function($a, $b) {
                        return strcmp($a['nom_municipio'], $b['nom_municipio']);
                      });
                      
                      $contador = 1;
                      foreach ($municipios as $municipio):
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
                          <!-- CAMBIA ESTA LÍNEA: -->
                          <td class="col-id"><?php echo $contador; ?></td>
                          <!-- FIN DEL CAMBIO -->
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
                              <button type="button"
                                class="btn btn-sm btn-<?php echo $municipio['estatus'] == 1 ? 'warning' : 'success'; ?> btn-confirmar-municipio"
                                data-id="<?php echo $municipio['id_municipio']; ?>"
                                data-nombre="<?php echo htmlspecialchars($municipio['nom_municipio']); ?>"
                                data-estado="<?php echo htmlspecialchars($municipio['nom_estado']); ?>"
                                data-estatus="<?php echo $municipio['estatus']; ?>"
                                data-en-uso="<?php echo $en_uso ? '1' : '0'; ?>"
                                data-conteo-usos="<?php echo $conteo_usos; ?>"
                                data-accion="<?php echo $municipio['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?>"
                                data-toggle="modal"
                                data-target="#modalConfirmacionMunicipio">
                                <i class="fas fa-<?php echo $municipio['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                <?php echo $municipio['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                              </button>
                            </form>
                          </td>
                        </tr>
                      <?php 
                      $contador++;
                      endforeach; ?>
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

  /* Estilos específicos para el modal de municipios */
  .btn-confirmar-municipio {
    transition: all 0.3s ease;
    min-width: 100px;
  }

  .btn-confirmar-municipio:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  /* Animación para el icono del modal */
  #modalIconoMunicipio i {
    filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.2));
    animation: pulseMunicipio 2s infinite;
  }

  @keyframes pulseMunicipio {
    0% {
      transform: scale(1);
    }

    50% {
      transform: scale(1.1);
    }

    100% {
      transform: scale(1);
    }
  }

  /* Estilos para las alertas dentro del modal */
  .modal-body .alert ul {
    margin-top: 8px;
    margin-bottom: 0;
  }

  .modal-body .alert li {
    padding-left: 5px;
    margin-bottom: 3px;
  }

  /* Mejora responsiva para el modal de municipios */
  @media (max-width: 576px) {
    .btn-confirmar-municipio {
      min-width: 90px;
      font-size: 0.85rem;
      padding: 0.25rem 0.5rem;
    }

    .modal-body .alert {
      font-size: 0.9rem;
    }
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
    // function actualizarTabla() {
    //   // Si no hay municipios originales, salir
    //   if (todosMunicipios.length === 0) {
    //     return;
    //   }

    //   // Aplicar filtro si hay término de búsqueda
    //   if (terminoBusqueda) {
    //     aplicarFiltro();
    //   } else {
    //     municipiosFiltrados = [...todosMunicipios];
    //   }

    //   // Paginar resultados
    //   paginarResultados();

    //   // Limpiar tabla
    //   cuerpoTabla.innerHTML = '';

    //   // Mostrar mensaje si no hay resultados después del filtro
    //   if (municipiosPagina.length === 0) {
    //     cuerpoTabla.innerHTML = `
    //             <tr>
    //                 <td colspan="8" class="text-center py-4">
    //                     <i class="fas fa-search fa-2x text-muted mb-2"></i>
    //                     <p class="mb-0">No se encontraron municipios que coincidan con "${terminoBusqueda}"</p>
    //                 </td>
    //             </tr>
    //         `;
    //   } else {
    //     // Agregar municipios a la tabla
    //     municipiosPagina.forEach(municipio => {
    //       const fila = document.createElement('tr');

    //       // Resaltar término de búsqueda si existe
    //       if (terminoBusqueda) {
    //         // Reconstruir cada celda con resaltado
    //         const idHTML = resaltarTexto(municipio.datos.id, terminoBusqueda);
    //         const nombreHTML = resaltarTexto(municipio.datos.nombre, terminoBusqueda);
    //         const estadoHTML = resaltarTexto(municipio.datos.estado, terminoBusqueda);

    //         fila.innerHTML = `
    //                     <td>${idHTML}</td>
    //                     <td>${nombreHTML}</td>
    //                     <td>${estadoHTML}</td>
    //                     <td>${municipio.datos.creacion}</td>
    //                     <td>${municipio.datos.actualizacion}</td>
    //                     <td>${municipio.datos.enUsoHTML}</td>
    //                     <td>${municipio.datos.estatusHTML}</td>
    //                     <td>${municipio.datos.accionesHTML}</td>
    //                 `;
    //       } else {
    //         // Usar el HTML completo original
    //         fila.innerHTML = municipio.htmlCompleto;
    //       }

    //       cuerpoTabla.appendChild(fila);
    //     });

    //     // Re-inicializar tooltips
    //     if (typeof $ !== 'undefined' && $.fn.tooltip) {
    //       setTimeout(() => {
    //         $('[data-toggle="tooltip"]').tooltip();
    //       }, 100);
    //     }
    //   }

    //   // Actualizar paginación
    //   actualizarPaginacion();
    // }

    // Actualizar tabla - VERSIÓN MEJORADA
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

          // Usar el HTML completo ORIGINAL que incluye los botones con data attributes
          fila.innerHTML = municipio.htmlCompleto;

          // Solo si hay término de búsqueda, aplicar resaltado
          if (terminoBusqueda) {
            const celdas = fila.querySelectorAll('td');
            // Resaltar en ID
            if (celdas[0]) {
              celdas[0].innerHTML = resaltarTexto(municipio.datos.id, terminoBusqueda);
            }
            // Resaltar en Nombre
            if (celdas[1]) {
              celdas[1].innerHTML = resaltarTexto(municipio.datos.nombre, terminoBusqueda);
            }
            // Resaltar en Estado
            if (celdas[2]) {
              celdas[2].innerHTML = resaltarTexto(municipio.datos.estado, terminoBusqueda);
            }
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

<!-- Modal de Confirmación para Municipios -->
<div id="modalConfirmacionMunicipio" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          <span id="modalTituloMunicipio">Confirmar Acción</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modalIconoMunicipio" class="text-center mb-3" style="font-size: 48px;">
          <!-- Icono dinámico -->
        </div>
        <h6 id="modalPreguntaMunicipio" class="text-center mb-3"></h6>
        <div id="modalDetalleMunicipio" class="alert alert-info" style="display: none;">
          <i class="fas fa-info-circle mr-2"></i>
          <span id="modalDetalleTextoMunicipio"></span>
        </div>
        <div id="modalAdvertenciaMunicipio" class="alert alert-danger" style="display: none;">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <strong>¡ADVERTENCIA!</strong>
          <span id="modalAdvertenciaTextoMunicipio"></span>
        </div>
        <div id="modalParroquiasMunicipio" class="alert alert-warning" style="display: none;">
          <i class="fas fa-map-signs mr-2"></i>
          <strong>Importante:</strong>
          <span id="modalParroquiasTextoMunicipio"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <form id="modalFormMunicipio" method="POST" class="d-inline">
          <input type="hidden" name="id_municipio" id="modalIdMunicipio">
          <input type="hidden" name="estatus" id="modalEstatusMunicipio">
          <button type="submit" name="actualizar_municipio" class="btn btn-success" id="modalBotonConfirmarMunicipio">
            <i class="fas fa-check mr-1"></i>
            <span id="modalBotonTextoMunicipio">Confirmar</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  // Script para el modal de confirmación de municipios CON EVENT DELEGATION
  document.addEventListener('DOMContentLoaded', function() {
    // Configurar el modal de confirmación para municipios usando EVENT DELEGATION
    const modalMunicipio = document.getElementById('modalConfirmacionMunicipio');
    const cuerpoTabla = document.getElementById('cuerpoTablaMunicipios');

    // Usar event delegation en el contenedor de la tabla
    cuerpoTabla.addEventListener('click', function(e) {
      // Verificar si el click fue en un botón de confirmación
      const boton = e.target.closest('.btn-confirmar-municipio');

      if (boton) {
        e.preventDefault();
        e.stopPropagation();

        const id = boton.getAttribute('data-id');
        const nombre = boton.getAttribute('data-nombre');
        const estado = boton.getAttribute('data-estado');
        const estatus = parseInt(boton.getAttribute('data-estatus'));
        const enUso = boton.getAttribute('data-en-uso') === '1';
        const conteoUsos = parseInt(boton.getAttribute('data-conteo-usos'));
        const accion = boton.getAttribute('data-accion');

        // Determinar el nuevo estado
        const nuevoEstatus = estatus === 1 ? 0 : 1;

        // Configurar el modal
        configurarModalMunicipio({
          id: id,
          nombre: nombre,
          estado: estado,
          estatusActual: estatus,
          nuevoEstatus: nuevoEstatus,
          enUso: enUso,
          conteoUsos: conteoUsos,
          accion: accion
        });

        // Mostrar el modal usando jQuery (ya que estás usando Bootstrap)
        $('#modalConfirmacionMunicipio').modal('show');
      }
    });

    // Configurar el modal con los datos del municipio
    function configurarModalMunicipio(datos) {
      const modalTitulo = document.getElementById('modalTituloMunicipio');
      const modalIcono = document.getElementById('modalIconoMunicipio');
      const modalPregunta = document.getElementById('modalPreguntaMunicipio');
      const modalDetalle = document.getElementById('modalDetalleMunicipio');
      const modalDetalleTexto = document.getElementById('modalDetalleTextoMunicipio');
      const modalAdvertencia = document.getElementById('modalAdvertenciaMunicipio');
      const modalAdvertenciaTexto = document.getElementById('modalAdvertenciaTextoMunicipio');
      const modalParroquias = document.getElementById('modalParroquiasMunicipio');
      const modalParroquiasTexto = document.getElementById('modalParroquiasTextoMunicipio');
      const modalIdMunicipio = document.getElementById('modalIdMunicipio');
      const modalEstatus = document.getElementById('modalEstatusMunicipio');
      const modalBotonConfirmar = document.getElementById('modalBotonConfirmarMunicipio');
      const modalBotonTexto = document.getElementById('modalBotonTextoMunicipio');

      // Configurar según la acción
      if (datos.accion === 'inhabilitar') {
        // INHABILITAR MUNICIPIO
        modalTitulo.textContent = 'Confirmar Inhabilitación de Municipio';
        modalIcono.innerHTML = '<i class="fas fa-ban text-warning"></i>';
        modalPregunta.innerHTML = `
        ¿Estás seguro que deseas <strong>INHABILITAR</strong> el municipio:<br>
        <span class="text-primary">"${datos.nombre}"</span><br>
        del estado: <span class="text-info">${datos.estado}</span>?
      `;

        // Botón de advertencia
        modalBotonConfirmar.className = 'btn btn-warning';
        modalBotonTexto.textContent = 'Sí, inhabilitar municipio';

        // Mostrar información sobre parroquias
        modalParroquias.style.display = 'block';
        modalParroquiasTexto.textContent = 'Al inhabilitar este municipio, todas sus parroquias también se inhabilitarán automáticamente.';

        if (datos.enUso) {
          // ADVERTENCIA si está en uso
          modalDetalle.style.display = 'block';
          modalDetalleTexto.textContent = `Este municipio está siendo usado en ${datos.conteoUsos} dirección(es) activa(s).`;

          modalAdvertencia.style.display = 'block';
          modalAdvertenciaTexto.innerHTML = `
          <ul class="mb-0 pl-3">
            <li>No aparecerá en nuevos registros</li>
            <li>Las direcciones existentes seguirán funcionando</li>
            <li>No se perderán datos asociados</li>
            <li>Las parroquias del municipio también se inhabilitarán</li>
          </ul>
        `;
        } else {
          modalDetalle.style.display = 'block';
          modalDetalleTexto.textContent = 'Este municipio no está en uso actualmente.';

          modalAdvertencia.style.display = 'block';
          modalAdvertencia.className = 'alert alert-warning';
          modalAdvertenciaTexto.innerHTML = `
          <strong>Recuerda:</strong>
          <ul class="mb-0 pl-3">
            <li>Todas las parroquias se inhabilitarán automáticamente</li>
            <li>No afectará a direcciones existentes</li>
            <li>Se puede habilitar nuevamente cuando sea necesario</li>
          </ul>
        `;
        }

      } else {
        // HABILITAR MUNICIPIO
        modalTitulo.textContent = 'Confirmar Habilitación de Municipio';
        modalIcono.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
        modalPregunta.innerHTML = `
        ¿Estás seguro que deseas <strong>HABILITAR</strong> el municipio:<br>
        <span class="text-primary">"${datos.nombre}"</span><br>
        del estado: <span class="text-info">${datos.estado}</span>?
      `;

        // Botón de éxito
        modalBotonConfirmar.className = 'btn btn-success';
        modalBotonTexto.textContent = 'Sí, habilitar municipio';

        modalDetalle.style.display = 'block';
        modalDetalle.className = 'alert alert-success';
        modalDetalleTexto.innerHTML = `
        <i class="fas fa-check-circle mr-2"></i>
        Este municipio volverá a estar disponible para:
        <ul class="mb-0 pl-3 mt-2">
          <li>Nuevos registros de dirección</li>
          <li>Edición de direcciones existentes</li>
          <li>Selección de parroquias</li>
          <li>Todos los formularios del sistema</li>
        </ul>
      `;

        modalAdvertencia.style.display = 'none';
        modalParroquias.style.display = 'block';
        modalParroquias.className = 'alert alert-info';
        modalParroquiasTexto.textContent = 'Nota: Las parroquias de este municipio permanecerán con su estado actual (habilitadas o inhabilitadas) y deberán gestionarse por separado.';
      }

      // Configurar valores del formulario
      modalIdMunicipio.value = datos.id;
      modalEstatus.value = datos.nuevoEstatus;
    }

    // Resetear el modal cuando se cierre
    $('#modalConfirmacionMunicipio').on('hidden.bs.modal', function() {
      const modalDetalle = document.getElementById('modalDetalleMunicipio');
      const modalAdvertencia = document.getElementById('modalAdvertenciaMunicipio');
      const modalParroquias = document.getElementById('modalParroquiasMunicipio');

      modalDetalle.style.display = 'none';
      modalAdvertencia.style.display = 'none';
      modalParroquias.style.display = 'none';
    });

    // También necesitas asegurar que el formulario dentro del modal funcione
    document.getElementById('modalFormMunicipio').addEventListener('submit', function(e) {
      // El formulario se enviará normalmente, el modal se cerrará automáticamente
      console.log('Formulario de municipio enviado');
    });
  });
</script>



<?php
// Cerrar conexión
$conexion->desconectar();
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>