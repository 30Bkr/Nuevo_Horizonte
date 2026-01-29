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
    if ($ubicacionController->actualizarEstado($id_estado, $estatus)) {
      $accion = $estatus == 1 ? 'habilitado' : 'inhabilitado';
      $_SESSION['mensaje'] = "Estado $accion correctamente";
      $_SESSION['tipo_mensaje'] = "success";
    } else {
      $_SESSION['mensaje'] = "Error al actualizar el estado";
      $_SESSION['tipo_mensaje'] = "error";
    }
  } catch (Exception $e) {
    $_SESSION['mensaje'] = $e->getMessage();
    $_SESSION['tipo_mensaje'] = "error";
  }

  echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
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
      <div class="row mb-4 p-2">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">Gestión de Ubicaciones</h1>
              <p class="text-muted">Administra los estados, municipios y parroquias del sistema</p>
            </div>
            <div>
              <div class="btn-group">
                <a href="http://localhost/final/admin/configuraciones/index.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>
                <a href="municipios.php" class="btn btn-info ml-2">
                  <i class="fas fa-city mr-1"></i> Municipios
                </a>
                <a href="parroquias.php" class="btn btn-success ml-2">
                  <i class="fas fa-map-signs mr-1"></i> Parroquias
                </a>
              </div>
            </div>
          </div>
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
              <!-- Barra de búsqueda en cliente -->
              <div class="row mb-4">
                <div class="col-md-6">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="fas fa-search"></i>
                      </span>
                    </div>
                    <input type="text"
                      id="filtroEstados"
                      class="form-control"
                      placeholder="Filtrar estados por nombre o ID..."
                      aria-label="Filtrar estados">
                    <div class="input-group-append">
                      <button id="limpiarFiltro" class="btn btn-secondary" style="display: none;">
                        <i class="fas fa-times mr-1"></i> Limpiar
                      </button>
                    </div>
                  </div>
                  <small class="form-text text-muted">
                    Escribe para filtrar y ordenar los estados en tiempo real
                  </small>
                </div>
                <div class="col-md-6">
                  <div id="infoFiltro" class="alert alert-info mb-0 py-2" style="display: none;">
                    <i class="fas fa-filter mr-2"></i>
                    Mostrando <span id="contadorResultados">0</span> de <?php echo count($estados); ?> estados
                    <span id="terminoFiltro"></span>
                  </div>
                </div>
              </div>

              <!-- Contenedor de la tabla -->
              <div id="tablaEstadosContainer">
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
                    <tbody id="cuerpoTablaEstados">
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
                          <tr data-id="<?php echo $estado['id_estado']; ?>"
                            data-nombre="<?php echo htmlspecialchars(strtolower($estado['nom_estado'])); ?>"
                            data-id-texto="<?php echo $estado['id_estado']; ?>">
                            <td class="col-id"><?php echo $estado['id_estado']; ?></td>
                            <td class="col-nombre"><?php echo htmlspecialchars($estado['nom_estado']); ?></td>
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
                              <span class="badge badge-<?php echo $estado['estatus'] == 1 ? 'success' : 'danger'; ?>">
                                <?php echo $estado['estatus'] == 1 ? 'Habilitado' : 'Inhabilitado'; ?>
                              </span>
                            </td>
                            <td>
                              <form method="POST" class="d-inline">
                                <input type="hidden" name="id_estado" value="<?php echo $estado['id_estado']; ?>">
                                <input type="hidden" name="estatus" value="<?php echo $estado['estatus'] == 1 ? 0 : 1; ?>">
                                <button type="submit" name="actualizar_estado"
                                  class="btn btn-sm btn-<?php echo $estado['estatus'] == 1 ? 'warning' : 'success'; ?>"
                                  onclick="return confirm('¿Estás seguro de <?php echo $estado['estatus'] == 1 ? 'INHABILITAR' : 'HABILITAR'; ?> este estado?<?php echo $en_uso && $estado['estatus'] == 1 ? '\n\nADVERTENCIA: Este estado está siendo usado en ' . $conteo_usos . ' dirección(es) activa(s).\nAl inhabilitarlo, no aparecerá en nuevos registros pero las direcciones existentes seguirán funcionando.' : ''; ?>')">
                                  <i class="fas fa-<?php echo $estado['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                  <?php echo $estado['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                                </button>
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
              <li><strong>Estados en uso:</strong> Pueden ser inhabilitados, pero aparecerá una advertencia</li>
              <li><strong>Estados sin uso:</strong> Pueden ser inhabilitados sin problemas</li>
              <li><strong>Estados inhabilitados:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen el estado NO se verán afectados al inhabilitarlo</li>
              <li>Para habilitar un estado, simplemente haga clic en el botón "Habilitar"</li>
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
                  <span class="badge badge-warning mr-2">En uso</span>
                  <small>Usado en direcciones activas (advertencia al inhabilitar)</small>
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
      <!-- Resto de tu código se mantiene igual -->
      <!-- ... (secciones de estadísticas, notas y leyenda) ... -->

    </div>
  </div>
</div>

<!-- Script para filtro en cliente -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const filtroInput = document.getElementById('filtroEstados');
    const limpiarBtn = document.getElementById('limpiarFiltro');
    const infoFiltro = document.getElementById('infoFiltro');
    const contadorResultados = document.getElementById('contadorResultados');
    const terminoFiltro = document.getElementById('terminoFiltro');
    const cuerpoTabla = document.getElementById('cuerpoTablaEstados');
    const filasOriginales = Array.from(cuerpoTabla.querySelectorAll('tr'));
    const totalEstados = filasOriginales.length;

    // Guardar los datos originales de cada fila
    const datosOriginales = filasOriginales.map(fila => {
      return {
        elemento: fila,
        nombre: fila.getAttribute('data-nombre') || '',
        id: fila.getAttribute('data-id-texto') || '',
        nombreCompleto: fila.querySelector('.col-nombre')?.textContent || '',
        idCompleto: fila.querySelector('.col-id')?.textContent || ''
      };
    });

    // Función para normalizar texto (quitar acentos, pasar a minúsculas)
    function normalizarTexto(texto) {
      return texto
        .toLowerCase()
        .normalize("NFD")
        .replace(/[\u0300-\u036f]/g, "")
        .trim();
    }

    // Función para resaltar texto en los resultados
    function resaltarTexto(texto, busqueda) {
      if (!busqueda) return texto;

      const regex = new RegExp(`(${busqueda})`, 'gi');
      return texto.replace(regex, '<span class="bg-warning text-dark">$1</span>');
    }

    // Función para aplicar el filtro
    function aplicarFiltro(termino) {
      const terminoNormalizado = normalizarTexto(termino);
      let resultadosFiltrados = [];

      // Filtrar y ordenar
      if (terminoNormalizado) {
        resultadosFiltrados = datosOriginales
          .filter(dato => {
            // Buscar en nombre y en ID
            const nombreMatch = normalizarTexto(dato.nombreCompleto).includes(terminoNormalizado);
            const idMatch = dato.idCompleto.includes(terminoNormalizado);
            return nombreMatch || idMatch;
          })
          .sort((a, b) => {
            // Ordenar por relevancia
            const aNombre = normalizarTexto(a.nombreCompleto);
            const bNombre = normalizarTexto(b.nombreCompleto);
            const aId = a.idCompleto;
            const bId = b.idCompleto;

            // Los que empiezan con el término van primero
            const aEmpiezaCon = aNombre.startsWith(terminoNormalizado) || aId.startsWith(terminoNormalizado);
            const bEmpiezaCon = bNombre.startsWith(terminoNormalizado) || bId.startsWith(terminoNormalizado);

            if (aEmpiezaCon && !bEmpiezaCon) return -1;
            if (!aEmpiezaCon && bEmpiezaCon) return 1;

            // Luego orden alfabético por nombre
            return aNombre.localeCompare(bNombre);
          });
      } else {
        // Sin filtro, orden alfabético original
        resultadosFiltrados = [...datosOriginales].sort((a, b) =>
          a.nombre.localeCompare(b.nombre)
        );
      }

      // Actualizar la tabla
      actualizarTabla(resultadosFiltrados, terminoNormalizado);

      // Actualizar información del filtro
      actualizarInfoFiltro(resultadosFiltrados.length, termino);

      // Mostrar/ocultar botón limpiar
      limpiarBtn.style.display = terminoNormalizado ? 'block' : 'none';
    }

    // Función para actualizar la tabla
    function actualizarTabla(resultados, termino) {
      // Limpiar tabla
      cuerpoTabla.innerHTML = '';

      if (resultados.length === 0) {
        cuerpoTabla.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center">
                        No se encontraron estados que coincidan con "${termino}"
                    </td>
                </tr>
            `;
        return;
      }

      // Agregar filas filtradas y ordenadas
      resultados.forEach(dato => {
        const fila = dato.elemento.cloneNode(true);

        // Resaltar el término de búsqueda si existe
        if (termino) {
          const celdaNombre = fila.querySelector('.col-nombre');
          const celdaId = fila.querySelector('.col-id');

          if (celdaNombre) {
            const nombreOriginal = dato.nombreCompleto;
            celdaNombre.innerHTML = resaltarTexto(nombreOriginal, termino);
          }

          if (celdaId) {
            const idOriginal = dato.idCompleto;
            celdaId.innerHTML = resaltarTexto(idOriginal, termino);
          }
        }

        cuerpoTabla.appendChild(fila);
      });

      // Re-inicializar tooltips si existen
      if (typeof $ !== 'undefined' && $.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
      }
    }

    // Función para actualizar la información del filtro
    function actualizarInfoFiltro(cantidad, termino) {
      if (termino) {
        contadorResultados.textContent = cantidad;
        terminoFiltro.innerHTML = ` para: <strong>"${termino}"</strong>`;
        infoFiltro.style.display = 'block';
      } else {
        infoFiltro.style.display = 'none';
      }
    }

    // Event Listeners
    filtroInput.addEventListener('input', function() {
      aplicarFiltro(this.value);
    });

    filtroInput.addEventListener('keyup', function(e) {
      // Si presiona ESC, limpiar filtro
      if (e.key === 'Escape') {
        this.value = '';
        aplicarFiltro('');
      }
    });

    limpiarBtn.addEventListener('click', function() {
      filtroInput.value = '';
      aplicarFiltro('');
      filtroInput.focus();
    });

    // Aplicar orden inicial alfabético
    aplicarFiltro('');

    // Opcional: Permitir ordenar por columnas
    const cabeceras = document.querySelectorAll('thead th');
    cabeceras.forEach((cabecera, indice) => {
      cabecera.style.cursor = 'pointer';
      cabecera.addEventListener('click', () => {
        ordenarPorColumna(indice);
      });
    });

    // Función para ordenar por columna
    function ordenarPorColumna(indiceColumna) {
      const terminoActual = normalizarTexto(filtroInput.value);
      let resultados = [];

      if (terminoActual) {
        resultados = datosOriginales.filter(dato =>
          normalizarTexto(dato.nombreCompleto).includes(terminoActual) ||
          dato.idCompleto.includes(terminoActual)
        );
      } else {
        resultados = [...datosOriginales];
      }

      // Ordenar según la columna clickeada
      resultados.sort((a, b) => {
        let valorA, valorB;

        switch (indiceColumna) {
          case 0: // ID
            valorA = parseInt(a.idCompleto) || 0;
            valorB = parseInt(b.idCompleto) || 0;
            return valorA - valorB;

          case 1: // Nombre
            valorA = normalizarTexto(a.nombreCompleto);
            valorB = normalizarTexto(b.nombreCompleto);
            return valorA.localeCompare(valorB);

          case 2: // Fecha Creación
          case 3: // Fecha Actualización
            // Aquí podrías implementar orden por fecha si lo necesitas
            return 0;

          default:
            return 0;
        }
      });

      actualizarTabla(resultados, terminoActual);
    }
  });
</script>

<style>
  /* Estilos para el filtro */
  #filtroEstados:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
  }

  /* Estilo para las cabeceras clickeables */
  thead th:hover {
    background-color: #f1f1f1;
  }

  /* Estilo para el resaltado */
  .bg-warning {
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: bold;
  }

  /* Transición suave para cambios en la tabla */
  #cuerpoTablaEstados tr {
    transition: all 0.3s ease;
  }
</style>

<?php
// Cerrar conexión
$conexion->desconectar();
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>