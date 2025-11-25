<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/patologias/patologias.php");


// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $patologiaController = new PatologiaController($pdo);

  $patologias = $patologiaController->obtenerPatologias();
  $totalAsignaciones = $patologiaController->contarAsignacionesEstudiantes();
} catch (Exception $e) {
  $patologias = [];
  $totalAsignaciones = 0;
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
                <i class="fas fa-heartbeat mr-2"></i>
                Gestión de Patologías
              </h1>
              <p class="text-muted">Administra el catálogo de patologías y condiciones médicas</p>
            </div>
            <button class="btn btn-primary" onclick="abrirModalAgregar()">
              <i class="fas fa-plus mr-1"></i> Agregar Patología
            </button>
          </div>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalPatologias"><?php echo count($patologias); ?></h3>
              <p>Total de Patologías</p>
            </div>
            <div class="icon">
              <i class="fas fa-heartbeat"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="patologiasActivas"><?php echo count(array_filter($patologias, function ($p) {
                                            return $p['estatus'] == 1;
                                          })); ?></h3>
              <p>Patologías Activas</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="patologiasInactivas"><?php echo count(array_filter($patologias, function ($p) {
                                              return $p['estatus'] == 0;
                                            })); ?></h3>
              <p>Patologías Inactivas</p>
            </div>
            <div class="icon">
              <i class="fas fa-pause-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3><?php echo $totalAsignaciones; ?></h3>
              <p>Asignaciones a Estudiantes</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-injured"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Patologías -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Patologías Registradas</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" id="searchInput" class="form-control float-right" placeholder="Buscar...">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default" onclick="buscarPatologias()">
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
                    <th>Nombre de la Patología</th>
                    <th>Estatus</th>
                    <th>Fecha de Creación</th>
                    <th>Última Actualización</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaPatologias">
                  <?php if (empty($patologias)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay patologías registradas</p>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($patologias as $patologia): ?>
                      <tr id="patologia-<?php echo $patologia['id_patologia']; ?>">
                        <td><?php echo $patologia['id_patologia']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-stethoscope text-primary mr-2"></i>
                            <span id="nombre-<?php echo $patologia['id_patologia']; ?>">
                              <?php echo htmlspecialchars($patologia['nom_patologia']); ?>
                            </span>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $patologia['estatus'] == 1 ? 'success' : 'danger'; ?>"
                            id="estatus-<?php echo $patologia['id_patologia']; ?>">
                            <?php echo $patologia['estatus'] == 1 ? 'Activa' : 'Inactiva'; ?>
                          </span>
                        </td>
                        <td>
                          <?php echo date('d/m/Y H:i', strtotime($patologia['creacion'])); ?>
                        </td>
                        <td>
                          <?php
                          if ($patologia['actualizacion']) {
                            echo date('d/m/Y H:i', strtotime($patologia['actualizacion']));
                          } else {
                            echo '<span class="text-muted">Sin actualizar</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary"
                              onclick="editarPatologia(<?php echo $patologia['id_patologia']; ?>, '<?php echo htmlspecialchars($patologia['nom_patologia']); ?>', <?php echo $patologia['estatus']; ?>)">
                              <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($patologia['estatus'] == 1): ?>
                              <button class="btn btn-sm btn-outline-danger"
                                onclick="cambiarEstatus(<?php echo $patologia['id_patologia']; ?>, '<?php echo htmlspecialchars($patologia['nom_patologia']); ?>', 0)">
                                <i class="fas fa-pause"></i>
                              </button>
                            <?php else: ?>
                              <button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(<?php echo $patologia['id_patologia']; ?>, '<?php echo htmlspecialchars($patologia['nom_patologia']); ?>', 1)">
                                <i class="fas fa-play"></i>
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
                  Mostrando <span id="contadorPatologias"><?php echo count($patologias); ?></span> patologías
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar Patología -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>
          Agregar Nueva Patología
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formAgregar" onsubmit="agregarPatologia(event)">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_patologia">Nombre de la Patología:</label>
            <input type="text" class="form-control" id="nombre_patologia" name="nombre_patologia"
              placeholder="Ej: Asma, Alergia a lácteos, Diabetes..." required>
            <small class="form-text text-muted">
              Ingresa el nombre completo de la patología o condición médica
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Guardar Patología
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Patología -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit mr-2"></i>
          Editar Patología
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formEditar" onsubmit="actualizarPatologia(event)">
        <input type="hidden" id="id_patologia_edit" name="id_patologia">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_patologia_edit">Nombre de la Patología:</label>
            <input type="text" class="form-control" id="nombre_patologia_edit" name="nombre_patologia" required>
          </div>
          <div class="form-group">
            <label for="estatus_patologia">Estatus:</label>
            <select class="form-control" id="estatus_patologia" name="estatus">
              <option value="1">Activa</option>
              <option value="0">Inactiva</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Actualizar Patología
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-exclamation-triangle mr-2 text-warning"></i>
          Confirmar Acción
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="mensajeConfirmacion"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i> Cancelar
        </button>
        <button type="button" class="btn btn-warning" id="btnConfirmarCambio">
          <i class="fas fa-check mr-1"></i> Confirmar
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

  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
  }

  .badge {
    font-size: 0.8em;
  }

  .btn-group .btn {
    margin-right: 2px;
  }

  .card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
  }
</style>

<script>
  // Variables globales
  let patologiaSeleccionada = null;

  // Funciones para abrir modales
  function abrirModalAgregar() {
    $('#modalAgregar').modal('show');
  }

  function editarPatologia(id, nombre, estatus) {
    $('#id_patologia_edit').val(id);
    $('#nombre_patologia_edit').val(nombre);
    $('#estatus_patologia').val(estatus);
    $('#modalEditar').modal('show');
  }

  function cambiarEstatus(id, nombre, nuevoEstatus) {
    patologiaSeleccionada = {
      id,
      nombre,
      nuevoEstatus
    };

    const accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstatus == 1 ?
      'Los estudiantes podrán ser asignados a esta patología nuevamente.' :
      'Los estudiantes ya no podrán ser asignados a esta patología.';

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${accion}</strong> la patología:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<small class="text-muted">${mensaje}</small>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Funciones con Fetch
  async function agregarPatologia(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const nombre = formData.get('nombre_patologia').trim();

    if (!nombre) {
      mostrarMensaje('El nombre de la patología es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

    try {
      const response = await fetch('../../app/controllers/patologias/acccionesPatologias.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=agregar&nombre=${encodeURIComponent(nombre)}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');
        $('#modalAgregar').modal('hide');
        event.target.reset();
        recargarPatologias();
      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar Patología';
    }
  }

  async function actualizarPatologia(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const id = formData.get('id_patologia');
    const nombre = formData.get('nombre_patologia').trim();
    const estatus = formData.get('estatus');

    if (!nombre) {
      mostrarMensaje('El nombre de la patología es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...';

    try {
      const response = await fetch('../../../app/controllers/patologias/acccionesPatologias.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&id=${id}&nombre=${encodeURIComponent(nombre)}&estatus=${estatus}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');
        $('#modalEditar').modal('hide');
        recargarPatologias();
      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Actualizar Patología';
    }
  }

  async function confirmarCambioEstatus() {
    if (!patologiaSeleccionada) return;

    const {
      id,
      nombre,
      nuevoEstatus
    } = patologiaSeleccionada;

    try {
      const response = await fetch('../../../app/controllers/patologias/acccionesPatologias.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&id=${id}&nombre=${encodeURIComponent(nombre)}&estatus=${nuevoEstatus}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarMensaje(result.message, 'success');
        $('#modalConfirmacion').modal('hide');
        recargarPatologias();
      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    }
  }

  async function recargarPatologias() {
    try {
      const response = await fetch('../../../app/controllers/patologias/acccionesPatologias.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todas'
      });

      const result = await response.json();

      if (result.success) {
        actualizarTabla(result.data);
        actualizarEstadisticas(result.data);
      } else {
        mostrarMensaje('Error al cargar patologías', 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    }
  }

  function actualizarTabla(patologias) {
    const tbody = document.getElementById('tablaPatologias');

    if (patologias.length === 0) {
      tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay patologías registradas</p>
                </td>
            </tr>
        `;
      return;
    }

    tbody.innerHTML = patologias.map(patologia => `
        <tr id="patologia-${patologia.id_patologia}">
            <td>${patologia.id_patologia}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="fas fa-stethoscope text-primary mr-2"></i>
                    <span id="nombre-${patologia.id_patologia}">${escapeHtml(patologia.nom_patologia)}</span>
                </div>
            </td>
            <td>
                <span class="badge badge-${patologia.estatus == 1 ? 'success' : 'danger'}" 
                      id="estatus-${patologia.id_patologia}">
                    ${patologia.estatus == 1 ? 'Activa' : 'Inactiva'}
                </span>
            </td>
            <td>${formatFecha(patologia.creacion)}</td>
            <td>${patologia.actualizacion ? formatFecha(patologia.actualizacion) : '<span class="text-muted">Sin actualizar</span>'}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" 
                            onclick="editarPatologia(${patologia.id_patologia}, '${escapeHtml(patologia.nom_patologia)}', ${patologia.estatus})">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${patologia.estatus == 1 ? 
                        `<button class="btn btn-sm btn-outline-danger"
                                onclick="cambiarEstatus(${patologia.id_patologia}, '${escapeHtml(patologia.nom_patologia)}', 0)">
                            <i class="fas fa-pause"></i>
                        </button>` :
                        `<button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(${patologia.id_patologia}, '${escapeHtml(patologia.nom_patologia)}', 1)">
                            <i class="fas fa-play"></i>
                        </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');

    document.getElementById('contadorPatologias').textContent = patologias.length;
  }

  function actualizarEstadisticas(patologias) {
    const activas = patologias.filter(p => p.estatus == 1).length;
    const inactivas = patologias.filter(p => p.estatus == 0).length;

    document.getElementById('totalPatologias').textContent = patologias.length;
    document.getElementById('patologiasActivas').textContent = activas;
    document.getElementById('patologiasInactivas').textContent = inactivas;
  }

  function buscarPatologias() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaPatologias tr');

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

  function formatFecha(fechaString) {
    const fecha = new Date(fechaString);
    return fecha.toLocaleDateString('es-ES') + ' ' + fecha.toLocaleTimeString('es-ES');
  }

  function mostrarMensaje(mensaje, tipo) {
    // Aquí puedes integrar con tu sistema de mensajes existente
    alert(`${tipo.toUpperCase()}: ${mensaje}`);
  }

  // Event Listeners
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('input', buscarPatologias);
    document.getElementById('btnConfirmarCambio').addEventListener('click', confirmarCambioEstatus);

    // Limpiar formulario al cerrar modal
    $('#modalAgregar').on('hidden.bs.modal', function() {
      document.getElementById('formAgregar').reset();
    });
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>