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
                        <?php
                        // Ordenar estados alfabéticamente
                        usort($estados, function ($a, $b) {
                          return strcmp($a['nom_estado'], $b['nom_estado']);
                        });

                        $contador = 1;
                        foreach ($estados as $estado):
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
                            <!-- CAMBIA ESTA LÍNEA: -->
                            <td class="col-id"><?php echo $contador; ?></td>
                            <!-- FIN DEL CAMBIO -->
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
                                <button type="button"
                                  class="btn btn-sm btn-<?php echo $estado['estatus'] == 1 ? 'warning' : 'success'; ?> btn-confirmar"
                                  data-id="<?php echo $estado['id_estado']; ?>"
                                  data-nombre="<?php echo htmlspecialchars($estado['nom_estado']); ?>"
                                  data-estatus="<?php echo $estado['estatus']; ?>"
                                  data-en-uso="<?php echo $en_uso ? '1' : '0'; ?>"
                                  data-conteo-usos="<?php echo $conteo_usos; ?>"
                                  data-accion="<?php echo $estado['estatus'] == 1 ? 'inhabilitar' : 'habilitar'; ?>"
                                  data-toggle="modal"
                                  data-target="#modalConfirmacion">
                                  <i class="fas fa-<?php echo $estado['estatus'] == 1 ? 'times' : 'check'; ?> mr-1"></i>
                                  <?php echo $estado['estatus'] == 1 ? 'Inhabilitar' : 'Habilitar'; ?>
                                </button>
                              </form>
                            </td>
                          </tr>
                        <?php
                          $contador++;
                        endforeach; ?>
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

<!-- Modal de Confirmación Personalizado -->
<div id="modalConfirmacion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header " style="background-color: #ffd75f; color: #333;">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2"></i>
          <span id="modalTitulo">Confirmar Acción</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modalIcono" class="text-center mb-3" style="font-size: 48px;">
          <!-- Icono dinámico -->
        </div>
        <h6 id="modalPregunta" class="text-center mb-3"></h6>
        <div id="modalDetalle" class="alert alert-info" style="display: none;">
          <i class="fas fa-info-circle mr-2"></i>
          <span id="modalDetalleTexto"></span>
        </div>
        <div id="modalAdvertencia" class="alert alert-danger" style="display: none;">
          <i class="fas fa-exclamation-circle mr-2"></i>
          <strong>¡ADVERTENCIA!</strong>
          <span id="modalAdvertenciaTexto"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <form id="modalForm" method="POST" class="d-inline">
          <input type="hidden" name="id_estado" id="modalIdEstado">
          <input type="hidden" name="estatus" id="modalEstatus">
          <button type="submit" name="actualizar_estado" class="btn btn-success" id="modalBotonConfirmar">
            <i class="fas fa-check mr-1"></i>
            <span id="modalBotonTexto">Confirmar</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Configurar el modal de confirmación
    const botonesConfirmar = document.querySelectorAll('.btn-confirmar');
    const modalConfirmacion = document.getElementById('modalConfirmacion');

    botonesConfirmar.forEach(boton => {
      boton.addEventListener('click', function(e) {
        e.preventDefault();

        const id = this.getAttribute('data-id');
        const nombre = this.getAttribute('data-nombre');
        const estatus = parseInt(this.getAttribute('data-estatus'));
        const enUso = this.getAttribute('data-en-uso') === '1';
        const conteoUsos = parseInt(this.getAttribute('data-conteo-usos'));
        const accion = this.getAttribute('data-accion');

        // Determinar el nuevo estado
        const nuevoEstatus = estatus === 1 ? 0 : 1;
        const esInhabilitar = accion === 'inhabilitar';

        // Configurar el modal
        configurarModal({
          id: id,
          nombre: nombre,
          estatusActual: estatus,
          nuevoEstatus: nuevoEstatus,
          enUso: enUso,
          conteoUsos: conteoUsos,
          accion: accion
        });
      });
    });

    // Configurar el modal con los datos
    function configurarModal(datos) {
      const modalTitulo = document.getElementById('modalTitulo');
      const modalIcono = document.getElementById('modalIcono');
      const modalPregunta = document.getElementById('modalPregunta');
      const modalDetalle = document.getElementById('modalDetalle');
      const modalDetalleTexto = document.getElementById('modalDetalleTexto');
      const modalAdvertencia = document.getElementById('modalAdvertencia');
      const modalAdvertenciaTexto = document.getElementById('modalAdvertenciaTexto');
      const modalIdEstado = document.getElementById('modalIdEstado');
      const modalEstatus = document.getElementById('modalEstatus');
      const modalBotonConfirmar = document.getElementById('modalBotonConfirmar');
      const modalBotonTexto = document.getElementById('modalBotonTexto');

      // Configurar según la acción
      if (datos.accion === 'inhabilitar') {
        // INHABILITAR
        modalTitulo.textContent = 'Confirmar Inhabilitación';
        modalIcono.innerHTML = '<i class="fas fa-ban text-warning"></i>';
        modalPregunta.textContent = `¿Estás seguro que deseas INHABILITAR el estado "${datos.nombre}"?`;

        // Botón de advertencia
        modalBotonConfirmar.style = 'background-color: #ffd75f; color: #333';
        modalBotonConfirmar.className = 'btn';
        modalBotonTexto.textContent = 'Sí, inhabilitar';

        if (datos.enUso) {
          // ADVERTENCIA si está en uso
          modalDetalle.style.display = 'block';
          modalDetalleTexto.textContent = `Este estado está siendo usado en ${datos.conteoUsos} dirección(es) activa(s).`;

          modalAdvertencia.style.display = 'block';
          modalAdvertenciaTexto.innerHTML = `
          <ul class="mb-0 pl-3">
            <li>No aparecerá en nuevos registros</li>
            <li>Las direcciones existentes seguirán funcionando</li>
            <li>No se perderán datos asociados</li>
          </ul>
        `;
        } else {
          modalDetalle.style.display = 'none';
          modalAdvertencia.style.display = 'block';
          modalAdvertencia.className = 'alert';
          modalAdvertencia.style = 'background-color: #ffd75f; color: #333';
          modalAdvertenciaTexto.textContent = 'Este estado no está en uso actualmente. Puede ser inhabilitado sin problemas.';
        }

      } else {
        // HABILITAR
        modalTitulo.textContent = 'Confirmar Habilitación';
        modalIcono.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
        modalPregunta.textContent = `¿Estás seguro que deseas HABILITAR el estado "${datos.nombre}"?`;

        // Botón de éxito
        modalBotonConfirmar.className = 'btn btn-success';
        modalBotonTexto.textContent = 'Sí, habilitar';

        modalDetalle.style.display = 'block';
        modalDetalle.className = 'alert alert-success';
        modalDetalleTexto.innerHTML = `
        <i class="fas fa-check-circle mr-2"></i>
        Este estado volverá a estar disponible para:
        <ul class="mb-0 pl-3 mt-2">
          <li>Nuevos registros de dirección</li>
          <li>Edición de direcciones existentes</li>
          <li>Todos los formularios del sistema</li>
        </ul>
      `;

        modalAdvertencia.style.display = 'none';
      }

      // Configurar valores del formulario
      modalIdEstado.value = datos.id;
      modalEstatus.value = datos.nuevoEstatus;
    }

    // Resetear el modal cuando se cierre
    $('#modalConfirmacion').on('hidden.bs.modal', function() {
      const modalDetalle = document.getElementById('modalDetalle');
      const modalAdvertencia = document.getElementById('modalAdvertencia');

      modalDetalle.style.display = 'none';
      modalAdvertencia.style.display = 'none';
    });
  });
</script>
<style>
  /* Estilos para el modal personalizado */
  .modal-header.bg-warning {
    background: linear-gradient(135deg, #ffd75f 0%, #ffd862 100%);
    color: #212529;
  }

  .btn-confirmar {
    transition: all 0.3s ease;
  }

  .btn-confirmar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  #modalIcono i {
    filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.2));
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
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

  /* Animación para el modal */
  .modal.fade .modal-dialog {
    transform: translate(0, -50px);
    transition: transform 0.3s ease-out;
  }

  .modal.show .modal-dialog {
    transform: translate(0, 0);
  }

  /* Responsividad */
  @media (max-width: 576px) {
    .modal-dialog {
      margin: 10px;
    }

    .modal-content {
      border-radius: 8px;
    }
  }
</style>

<?php
// Cerrar conexión
$conexion->desconectar();
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>