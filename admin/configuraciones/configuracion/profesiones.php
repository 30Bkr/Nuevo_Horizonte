<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/conexion.php");
include_once("/xampp/htdocs/final/app/controllers/profesiones/profesiones.php");

// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $profesionController = new ProfesionController($pdo);

  $profesiones = $profesionController->obtenerProfesiones();
  $totalUsos = $profesionController->contarUsosProfesion();
} catch (Exception $e) {
  $profesiones = [];
  $totalUsos = 0;
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
                <i class="fas fa-briefcase mr-2"></i>
                Gestión de Profesiones
              </h1>
              <p class="text-muted">Administra el catálogo de profesiones y oficios</p>
            </div>
            <button class="btn btn-primary" onclick="abrirModalAgregar()">
              <i class="fas fa-plus mr-1"></i> Agregar Profesión
            </button>
          </div>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalProfesiones"><?php echo count($profesiones); ?></h3>
              <p>Total de Profesiones</p>
            </div>
            <div class="icon">
              <i class="fas fa-briefcase"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="profesionesActivas"><?php echo count(array_filter($profesiones, function ($p) {
                                            return $p['estatus'] == 1;
                                          })); ?></h3>
              <p>Profesiones Activas</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="profesionesInactivas"><?php echo count(array_filter($profesiones, function ($p) {
                                              return $p['estatus'] == 0;
                                            })); ?></h3>
              <p>Profesiones Inactivas</p>
            </div>
            <div class="icon">
              <i class="fas fa-pause-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3><?php echo $totalUsos; ?></h3>
              <p>Usos en el Sistema</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Profesiones -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Profesiones Registradas</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" id="searchInput" class="form-control float-right" placeholder="Buscar...">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default" onclick="buscarProfesiones()">
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
                    <th>Nombre de la Profesión</th>
                    <th>Estatus</th>
                    <th>Fecha de Creación</th>
                    <th>Última Actualización</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaProfesiones">
                  <?php if (empty($profesiones)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay profesiones registradas</p>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($profesiones as $profesion): ?>
                      <tr id="profesion-<?php echo $profesion['id_profesion']; ?>">
                        <td><?php echo $profesion['id_profesion']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie text-primary mr-2"></i>
                            <span id="nombre-<?php echo $profesion['id_profesion']; ?>">
                              <?php echo htmlspecialchars($profesion['profesion']); ?>
                            </span>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $profesion['estatus'] == 1 ? 'success' : 'danger'; ?>"
                            id="estatus-<?php echo $profesion['id_profesion']; ?>">
                            <?php echo $profesion['estatus'] == 1 ? 'Activa' : 'Inactiva'; ?>
                          </span>
                        </td>
                        <td>
                          <?php echo date('d/m/Y H:i', strtotime($profesion['creacion'])); ?>
                        </td>
                        <td>
                          <?php
                          if ($profesion['actualizacion']) {
                            echo date('d/m/Y H:i', strtotime($profesion['actualizacion']));
                          } else {
                            echo '<span class="text-muted">Sin actualizar</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary"
                              onclick="editarProfesion(<?php echo $profesion['id_profesion']; ?>, '<?php echo htmlspecialchars($profesion['profesion']); ?>', <?php echo $profesion['estatus']; ?>)">
                              <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($profesion['estatus'] == 1): ?>
                              <button class="btn btn-sm btn-outline-danger"
                                onclick="cambiarEstatus(<?php echo $profesion['id_profesion']; ?>, '<?php echo htmlspecialchars($profesion['profesion']); ?>', 0)">
                                <i class="fas fa-pause"></i>
                              </button>
                            <?php else: ?>
                              <button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(<?php echo $profesion['id_profesion']; ?>, '<?php echo htmlspecialchars($profesion['profesion']); ?>', 1)">
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
                  Mostrando <span id="contadorProfesiones"><?php echo count($profesiones); ?></span> profesiones
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Agregar Profesión -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>
          Agregar Nueva Profesión
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formAgregar" onsubmit="agregarProfesion(event)">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_profesion">Nombre de la Profesión:</label>
            <input type="text" class="form-control" id="nombre_profesion" name="nombre_profesion"
              placeholder="Ej: Ingeniero, Médico, Abogado, Carpintero..." required>
            <small class="form-text text-muted">
              Ingresa el nombre completo de la profesión u oficio
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Guardar Profesión
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Profesión -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit mr-2"></i>
          Editar Profesión
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formEditar" onsubmit="actualizarProfesion(event)">
        <input type="hidden" id="id_profesion_edit" name="id_profesion">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_profesion_edit">Nombre de la Profesión:</label>
            <input type="text" class="form-control" id="nombre_profesion_edit" name="nombre_profesion" required>
          </div>
          <div class="form-group">
            <label for="estatus_profesion">Estatus:</label>
            <select class="form-control" id="estatus_profesion" name="estatus">
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
            <i class="fas fa-save mr-1"></i> Actualizar Profesión
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
  let profesionSeleccionada = null;

  // Funciones para abrir modales
  function abrirModalAgregar() {
    $('#modalAgregar').modal('show');
  }

  function editarProfesion(id, nombre, estatus) {
    $('#id_profesion_edit').val(id);
    $('#nombre_profesion_edit').val(nombre);
    $('#estatus_profesion').val(estatus);
    $('#modalEditar').modal('show');
  }

  function cambiarEstatus(id, nombre, nuevoEstatus) {
    profesionSeleccionada = {
      id,
      nombre,
      nuevoEstatus
    };

    const accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstatus == 1 ?
      'Podrá ser asignada a representantes y docentes nuevamente.' :
      'No podrá ser asignada a nuevos representantes o docentes.';

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${accion}</strong> la profesión:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<small class="text-muted">${mensaje}</small>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Funciones con Fetch
  async function agregarProfesion(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const nombre = formData.get('nombre_profesion').trim();

    if (!nombre) {
      mostrarMensaje('El nombre de la profesión es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

    try {
      const response = await fetch('../../../app/controllers/profesiones/accionesProfesiones.php', {
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
        recargarProfesiones();
      } else {
        const esDuplicado = result.duplicate || false;
        mostrarMensaje(result.message, 'error', esDuplicado);
        if (!esDuplicado) {
          $('#modalAgregar').modal('hide');
        }
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar Profesión';
    }
  }

  async function actualizarProfesion(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const id = formData.get('id_profesion');
    const nombre = formData.get('nombre_profesion').trim();
    const estatus = formData.get('estatus');

    if (!nombre) {
      mostrarMensaje('El nombre de la profesión es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...';

    try {
      const response = await fetch('../../../app/controllers/profesiones/accionesProfesiones.php', {
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
        recargarProfesiones();
      } else {
        mostrarMensaje(result.message, 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Actualizar Profesión';
    }
  }

  async function confirmarCambioEstatus() {
    if (!profesionSeleccionada) return;

    const {
      id,
      nombre,
      nuevoEstatus
    } = profesionSeleccionada;

    try {
      const response = await fetch('../../../app/controllers/profesiones/accionesProfesiones.php', {
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
        recargarProfesiones();
      } else {
        const esDuplicado = result.duplicate || false;
        mostrarMensaje(result.message, 'error', esDuplicado);
        $('#modalConfirmacion').modal('hide');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    }
  }

  async function recargarProfesiones() {
    try {
      const response = await fetch('../../../app/controllers/profesiones/accionesProfesiones.php', {
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
        mostrarMensaje('Error al cargar profesiones', 'error');
      }
    } catch (error) {
      mostrarMensaje('Error de conexión: ' + error.message, 'error');
    }
  }

  function actualizarTabla(profesiones) {
    const tbody = document.getElementById('tablaProfesiones');

    if (profesiones.length === 0) {
      tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay profesiones registradas</p>
                </td>
            </tr>
        `;
      return;
    }

    tbody.innerHTML = profesiones.map(profesion => `
        <tr id="profesion-${profesion.id_profesion}">
            <td>${profesion.id_profesion}</td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-tie text-primary mr-2"></i>
                    <span id="nombre-${profesion.id_profesion}">${escapeHtml(profesion.profesion)}</span>
                </div>
            </td>
            <td>
                <span class="badge badge-${profesion.estatus == 1 ? 'success' : 'danger'}" 
                      id="estatus-${profesion.id_profesion}">
                    ${profesion.estatus == 1 ? 'Activa' : 'Inactiva'}
                </span>
            </td>
            <td>${formatFecha(profesion.creacion)}</td>
            <td>${profesion.actualizacion ? formatFecha(profesion.actualizacion) : '<span class="text-muted">Sin actualizar</span>'}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" 
                            onclick="editarProfesion(${profesion.id_profesion}, '${escapeHtml(profesion.profesion)}', ${profesion.estatus})">
                        <i class="fas fa-edit"></i>
                    </button>
                    ${profesion.estatus == 1 ? 
                        `<button class="btn btn-sm btn-outline-danger"
                                onclick="cambiarEstatus(${profesion.id_profesion}, '${escapeHtml(profesion.profesion)}', 0)">
                            <i class="fas fa-pause"></i>
                        </button>` :
                        `<button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(${profesion.id_profesion}, '${escapeHtml(profesion.profesion)}', 1)">
                            <i class="fas fa-play"></i>
                        </button>`
                    }
                </div>
            </td>
        </tr>
    `).join('');

    document.getElementById('contadorProfesiones').textContent = profesiones.length;
  }

  function actualizarEstadisticas(profesiones) {
    const activas = profesiones.filter(p => p.estatus == 1).length;
    const inactivas = profesiones.filter(p => p.estatus == 0).length;

    document.getElementById('totalProfesiones').textContent = profesiones.length;
    document.getElementById('profesionesActivas').textContent = activas;
    document.getElementById('profesionesInactivas').textContent = inactivas;
  }

  function buscarProfesiones() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaProfesiones tr');

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


  function mostrarMensaje(mensaje, tipo, esDuplicado = false) {
    // Determinar la clase de Bootstrap según el tipo
    let alertClass = '';
    let icono = '';

    if (tipo === 'success') {
      alertClass = 'alert-success';
      icono = '<i class="fas fa-check-circle mr-2"></i>';
    } else if (esDuplicado) {
      alertClass = 'alert-warning';
      icono = '<i class="fas fa-exclamation-triangle mr-2"></i>';
    } else {
      alertClass = 'alert-danger';
      icono = '<i class="fas fa-exclamation-circle mr-2"></i>';
    }

    // Crear elemento de alerta de Bootstrap
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.style.cssText = `
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 9999;
        min-width: 350px;
        max-width: 500px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-left: 4px solid ${esDuplicado ? '#ffc107' : tipo === 'success' ? '#28a745' : '#dc3545'};
    `;

    // Título según el tipo
    let titulo = '';
    if (tipo === 'success') {
      titulo = 'Éxito';
    } else if (esDuplicado) {
      titulo = 'Advertencia';
    } else {
      titulo = 'Error';
    }

    alertDiv.innerHTML = `
        <div class="d-flex align-items-center">
            ${icono}
            <div class="flex-grow-1">
                <strong class="d-block">${titulo}</strong>
                <span class="small">${mensaje}</span>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    // Agregar al cuerpo del documento
    document.body.appendChild(alertDiv);

    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
      if (alertDiv.parentNode) {
        // Agregar animación de salida
        alertDiv.classList.remove('show');
        alertDiv.classList.add('fade');
        setTimeout(() => {
          if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
          }
        }, 150);
      }
    }, 5000);

    // También permitir cerrar haciendo click
    alertDiv.addEventListener('click', function(e) {
      if (e.target === this || e.target.classList.contains('close')) {
        this.classList.remove('show');
        this.classList.add('fade');
        setTimeout(() => {
          if (this.parentNode) {
            this.parentNode.removeChild(this);
          }
        }, 150);
      }
    });
  }

  // Event Listeners
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('input', buscarProfesiones);
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