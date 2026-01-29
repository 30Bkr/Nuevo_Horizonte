<?php
// admin/configuraciones/discapacidades.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Establecer título de página
$_SESSION['page_title'] = 'Gestión de Discapacidades';

// Incluir archivos necesarios
require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/notifications.php';
require_once '/xampp/htdocs/final/app/conexion.php';
require_once '/xampp/htdocs/final/app/controllers/discapacidades/discapacidades.php';

// Verificar permisos - ajusta según tus necesidades
if (!PermissionManager::canViewAny(['admin/configuraciones/index.php'])) {
  Notification::set("No tienes permisos para acceder a esta sección", "error");
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $discapacidadController = new DiscapacidadController($pdo);

  // Usar el nuevo método para obtener todas las discapacidades
  $discapacidades = $discapacidadController->obtenerTodasLasDiscapacidades();
  foreach ($discapacidades as &$discapacidad) {
    $discapacidad['en_uso'] = $discapacidadController->discapacidadEnUso($discapacidad['id_discapacidad']);
    $discapacidad['conteo_usos'] = $discapacidadController->obtenerConteoUsosDiscapacidad($discapacidad['id_discapacidad']);
  }
  unset($discapacidad);
  $totalAsignaciones = $discapacidadController->contarAsignacionesEstudiantes();
} catch (Exception $e) {
  $discapacidades = [];
  $totalAsignaciones = 0;
  Notification::set("Error al cargar discapacidades: " . $e->getMessage(), "error");
}

// Incluir layout1.php al inicio
require_once '/xampp/htdocs/final/layout/layaout1.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
  <!-- Content Header -->
  <div class="content-header">
    <?php
    // Mostrar notificaciones
    Notification::show();
    ?>
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Gestión de Discapacidades</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/configuraciones/index.php">Configuraciones</a></li>
            <li class="breadcrumb-item active">Discapacidades</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mb-4 p-2">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">
                <i class="fas fa-wheelchair mr-2"></i>
                Gestión de Discapacidades
              </h1>
              <p class="text-muted">Administra el catálogo de discapacidades y condiciones especiales</p>
            </div>
            <div>
              <a href="<?= URL; ?>/admin/configuraciones/index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
              </a>
              <button class="btn btn-primary" onclick="abrirModalAgregar()">
                <i class="fas fa-plus mr-1"></i> Crear
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="totalDiscapacidades"><?php echo count($discapacidades); ?></h3>
              <p>Total de Discapacidades</p>
            </div>
            <div class="icon">
              <i class="fas fa-wheelchair"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="discapacidadesActivas"><?php echo count(array_filter($discapacidades, function ($d) {
                                                return $d['estatus'] == 1;
                                              })); ?></h3>
              <p>Discapacidades Activas</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="discapacidadesInactivas"><?php echo count(array_filter($discapacidades, function ($d) {
                                                  return $d['estatus'] == 0;
                                                })); ?></h3>
              <p>Discapacidades Inactivas</p>
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
              <i class="fas fa-user-friends"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Discapacidades -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Discapacidades Registradas</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" id="searchInput" class="form-control float-right" placeholder="Buscar...">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default" onclick="buscarDiscapacidades()">
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
                    <th>Nombre de la Discapacidad</th>
                    <th>Estatus</th>
                    <th>Fecha de Creación</th>
                    <th>Última Actualización</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaDiscapacidades">
                  <?php if (empty($discapacidades)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay discapacidades registradas</p>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($discapacidades as $discapacidad): ?>
                      <tr id="discapacidad-<?php echo $discapacidad['id_discapacidad']; ?>">
                        <td><?php echo $discapacidad['id_discapacidad']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-universal-access text-primary mr-2"></i>
                            <span id="nombre-<?php echo $discapacidad['id_discapacidad']; ?>">
                              <?php echo htmlspecialchars($discapacidad['nom_discapacidad']); ?>
                            </span>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $discapacidad['estatus'] == 1 ? 'success' : 'danger'; ?>"
                            id="estatus-<?php echo $discapacidad['id_discapacidad']; ?>">
                            <?php echo $discapacidad['estatus'] == 1 ? 'Activa' : 'Inactiva'; ?>
                          </span>
                        </td>
                        <td>
                          <?php echo date('d/m/Y H:i', strtotime($discapacidad['creacion'])); ?>
                        </td>
                        <td>
                          <?php
                          if ($discapacidad['actualizacion']) {
                            echo date('d/m/Y H:i', strtotime($discapacidad['actualizacion']));
                          } else {
                            echo '<span class="text-muted">Sin actualizar</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary"
                              onclick="editarDiscapacidad(<?php echo $discapacidad['id_discapacidad']; ?>, '<?php echo htmlspecialchars($discapacidad['nom_discapacidad']); ?>', <?php echo $discapacidad['estatus']; ?>)">
                              <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($discapacidad['estatus'] == 1): ?>
                              <?php if ($discapacidad['en_uso']): ?>
                                <!-- Permite desactivar pero con advertencia -->
                                <button class="btn btn-sm btn-outline-warning"
                                  data-toggle="tooltip"
                                  title="Desactivar (en uso en <?php echo $discapacidad['conteo_usos']; ?> estudiante(s))"
                                  onclick="cambiarEstatusConAdvertencia(<?php echo $discapacidad['id_discapacidad']; ?>, '<?php echo htmlspecialchars($discapacidad['nom_discapacidad']); ?>', 0, <?php echo $discapacidad['conteo_usos']; ?>)">
                                  <i class="fas fa-exclamation-triangle"></i>
                                </button>
                              <?php else: ?>
                                <button class="btn btn-sm btn-outline-danger"
                                  onclick="cambiarEstatus(<?php echo $discapacidad['id_discapacidad']; ?>, '<?php echo htmlspecialchars($discapacidad['nom_discapacidad']); ?>', 0)">
                                  <i class="fas fa-pause"></i>
                                </button>
                              <?php endif; ?>
                            <?php else: ?>
                              <button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(<?php echo $discapacidad['id_discapacidad']; ?>, '<?php echo htmlspecialchars($discapacidad['nom_discapacidad']); ?>', 1)">
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
                  Mostrando <span id="contadorDiscapacidades"><?php echo count($discapacidades); ?></span> discapacidades
                </small>
              </div>
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
              <!-- <li><strong>Discapacidades en uso:</strong> No se pueden desactivar porque están siendo utilizadas por estudiantes</li> -->
              <li><strong>Discapacidades sin uso:</strong> Se pueden desactivar sin problemas</li>
              <li><strong>Discapacidades inactivas:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen la discapacidad no se verán afectados al desactivarla</li>
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
                  <span class="badge badge-success mr-2">Activa</span>
                  <small>Disponible para nuevos registros</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-danger mr-2">Inactiva</span>
                  <small>No disponible para nuevos registros</small>
                </div>
                <!-- <div class="col-md-3">
                  <span class="badge badge-info mr-2">En uso</span>
                  <small>Usada por estudiantes activos</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-secondary mr-2">Sin uso</span>
                  <small>No usada por estudiantes</small>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Agregar Discapacidad -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>
          Agregar Nueva Discapacidad
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formAgregar" onsubmit="agregarDiscapacidad(event)">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_discapacidad">Nombre de la Discapacidad:</label>
            <input type="text" class="form-control" id="nombre_discapacidad" name="nombre_discapacidad"
              placeholder="Ej: Discapacidad visual, Discapacidad auditiva, Autismo..." required>
            <small class="form-text text-muted">
              Ingresa el nombre completo de la discapacidad o condición especial
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Editar Discapacidad -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit mr-2"></i>
          Editar Discapacidad
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formEditar" onsubmit="actualizarDiscapacidad(event)">
        <input type="hidden" id="id_discapacidad_edit" name="id_discapacidad">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_discapacidad_edit">Nombre de la Discapacidad:</label>
            <input type="text" class="form-control" id="nombre_discapacidad_edit" name="nombre_discapacidad" required>
          </div>
          <div class="form-group">
            <label for="estatus_discapacidad">Estatus:</label>
            <select class="form-control" id="estatus_discapacidad" name="estatus">
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
            <i class="fas fa-save mr-1"></i> Actualizar Discapacidad
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
  let discapacidadSeleccionada = null;

  // Nueva función para desactivar con advertencia cuando está en uso
  function cambiarEstatusConAdvertencia(id, nombre, nuevoEstatus, conteoUsos) {
    discapacidadSeleccionada = {
      id,
      nombre,
      nuevoEstatus
    };

    const mensaje = nuevoEstatus == 1 ?
      'Los estudiantes podrán ser asignados a esta discapacidad nuevamente.' :
      `ADVERTENCIA: Esta discapacidad está en uso por ${conteoUsos} estudiante(s).<br><br>` +
      `Al desactivarla:<br>` +
      `✓ No aparecerá en los formularios de nuevos registros<br>` +
      `✓ Los estudiantes que ya la tienen asignada conservarán la asignación<br>` +
      `✓ No afectará los registros existentes`;

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${nuevoEstatus == 1 ? 'activar' : 'desactivar'}</strong> la discapacidad:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<div style="background-color: ${nuevoEstatus == 1 ? '#d4edda' : '#fff3cd'}; padding: 10px; border-radius: 5px; border-left: 4px solid ${nuevoEstatus == 1 ? '#28a745' : '#ffc107'};">` +
      `<small>${mensaje}</small>` +
      `</div>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Actualiza la función cambiarEstatus para casos normales (sin uso)
  function cambiarEstatus(id, nombre, nuevoEstatus) {
    discapacidadSeleccionada = {
      id,
      nombre,
      nuevoEstatus
    };

    const accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstatus == 1 ?
      'Los estudiantes podrán ser asignados a esta discapacidad nuevamente.' :
      'Los estudiantes ya no podrán ser asignados a esta discapacidad.';

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${accion}</strong> la discapacidad:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<small class="text-muted">${mensaje}</small>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Funciones para abrir modales
  function abrirModalAgregar() {
    $('#modalAgregar').modal('show');
  }

  function editarDiscapacidad(id, nombre, estatus) {
    $('#id_discapacidad_edit').val(id);
    $('#nombre_discapacidad_edit').val(nombre);
    $('#estatus_discapacidad').val(estatus);
    $('#modalEditar').modal('show');
  }

  function cambiarEstatus(id, nombre, nuevoEstatus) {
    discapacidadSeleccionada = {
      id,
      nombre,
      nuevoEstatus
    };

    const accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstatus == 1 ?
      'Los estudiantes podrán ser asignados a esta discapacidad nuevamente.' :
      'Los estudiantes ya no podrán ser asignados a esta discapacidad.';

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${accion}</strong> la discapacidad:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<small class="text-muted">${mensaje}</small>`
    );

    $('#modalConfirmacion').modal('show');
  }

  async function agregarDiscapacidad(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const nombre = formData.get('nombre_discapacidad').trim();

    if (!nombre) {
      mostrarNotificacion('El nombre de la discapacidad es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/discapacidades/accionesDiscapacidades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=agregar&nombre=${encodeURIComponent(nombre)}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarNotificacion(result.message, 'success');
        $('#modalAgregar').modal('hide');
        event.target.reset();
        recargarDiscapacidades();
      } else {
        mostrarNotificacion(result.message, 'error');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar Discapacidad';
    }
  }

  async function actualizarDiscapacidad(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const id = formData.get('id_discapacidad');
    const nombre = formData.get('nombre_discapacidad').trim();
    const estatus = formData.get('estatus');

    if (!nombre) {
      mostrarNotificacion('El nombre de la discapacidad es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...';

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/discapacidades/accionesDiscapacidades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&id=${id}&nombre=${encodeURIComponent(nombre)}&estatus=${estatus}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarNotificacion(result.message, 'success');
        $('#modalEditar').modal('hide');
        recargarDiscapacidades();
      } else {
        mostrarNotificacion(result.message, 'error');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Actualizar Discapacidad';
    }
  }

  async function confirmarCambioEstatus() {
    if (!discapacidadSeleccionada) return;

    const {
      id,
      nombre,
      nuevoEstatus
    } = discapacidadSeleccionada;

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/discapacidades/accionesDiscapacidades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=actualizar&id=${id}&nombre=${encodeURIComponent(nombre)}&estatus=${nuevoEstatus}`
      });

      const result = await response.json();

      if (result.success) {
        mostrarNotificacion(result.message, 'success');
        $('#modalConfirmacion').modal('hide');
        recargarDiscapacidades();
      } else {
        mostrarNotificacion(result.message, 'error');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    }
  }

  async function recargarDiscapacidades() {
    try {
      const response = await fetch('<?= URL; ?>/app/controllers/discapacidades/accionesDiscapacidades.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todas'
      });

      const text = await response.text();
      let result;
      try {
        result = JSON.parse(text);
      } catch (e) {
        console.error('Error parseando JSON:', e);
        mostrarNotificacion('Error al cargar discapacidades: Respuesta no válida del servidor', 'error');
        return;
      }

      if (result.success) {
        actualizarTabla(result.data);
        actualizarEstadisticas(result.data);
      } else {
        mostrarNotificacion('Error al cargar discapacidades: ' + result.message, 'error');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    }
  }

  function actualizarTabla(discapacidades) {
    const tbody = document.getElementById('tablaDiscapacidades');

    if (discapacidades.length === 0) {
      tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay discapacidades registradas</p>
                </td>
            </tr>
        `;
      return;
    }

    // Construir la tabla con la información de uso que ya viene del servidor
    tbody.innerHTML = discapacidades.map(discapacidad => {
      const enUso = discapacidad.en_uso || false;
      const conteoUsos = discapacidad.conteo_usos || 0;

      return `
            <tr id="discapacidad-${discapacidad.id_discapacidad}">
                <td>${discapacidad.id_discapacidad}</td>
                <td>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-universal-access text-primary mr-2"></i>
                        <span id="nombre-${discapacidad.id_discapacidad}">${escapeHtml(discapacidad.nom_discapacidad)}</span>
                    </div>
                </td>
                <td>
                    <span class="badge badge-${discapacidad.estatus == 1 ? 'success' : 'danger'}" 
                          id="estatus-${discapacidad.id_discapacidad}">
                        ${discapacidad.estatus == 1 ? 'Activa' : 'Inactiva'}
                    </span>
                </td>
                <td>${formatFecha(discapacidad.creacion)}</td>
                <td>${discapacidad.actualizacion ? formatFecha(discapacidad.actualizacion) : '<span class="text-muted">Sin actualizar</span>'}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="editarDiscapacidad(${discapacidad.id_discapacidad}, '${escapeHtml(discapacidad.nom_discapacidad)}', ${discapacidad.estatus})">
                            <i class="fas fa-edit"></i>
                        </button>
                        ${discapacidad.estatus == 1 ? 
                            (enUso ? 
                                `<button class="btn btn-sm btn-outline-warning"
                                        data-toggle="tooltip"
                                        title="Desactivar (en uso en ${conteoUsos} estudiante(s))"
                                        onclick="cambiarEstatusConAdvertencia(${discapacidad.id_discapacidad}, '${escapeHtml(discapacidad.nom_discapacidad)}', 0, ${conteoUsos})">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </button>` :
                                `<button class="btn btn-sm btn-outline-danger"
                                        onclick="cambiarEstatus(${discapacidad.id_discapacidad}, '${escapeHtml(discapacidad.nom_discapacidad)}', 0)">
                                    <i class="fas fa-pause"></i>
                                </button>`
                            ) :
                            `<button class="btn btn-sm btn-outline-success"
                                    onclick="cambiarEstatus(${discapacidad.id_discapacidad}, '${escapeHtml(discapacidad.nom_discapacidad)}', 1)">
                                <i class="fas fa-play"></i>
                            </button>`
                        }
                    </div>
                </td>
            </tr>
        `;
    }).join('');

    document.getElementById('contadorDiscapacidades').textContent = discapacidades.length;

    // Re-inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
  }

  function actualizarEstadisticas(discapacidades) {
    const activas = discapacidades.filter(d => d.estatus == 1).length;
    const inactivas = discapacidades.filter(d => d.estatus == 0).length;

    document.getElementById('totalDiscapacidades').textContent = discapacidades.length;
    document.getElementById('discapacidadesActivas').textContent = activas;
    document.getElementById('discapacidadesInactivas').textContent = inactivas;
  }

  function buscarDiscapacidades() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaDiscapacidades tr');

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

  function mostrarNotificacion(mensaje, tipo = 'info', tiempo = 5000) {
    const iconos = {
      'success': '✓',
      'error': '✗',
      'warning': '⚠',
      'info': 'ℹ'
    };

    const titulos = {
      'success': 'Éxito',
      'error': 'Error',
      'warning': 'Advertencia',
      'info': 'Información'
    };

    const colores = {
      'success': {
        bg: '#28a745',
        border: '#1e7e34'
      },
      'error': {
        bg: '#dc3545',
        border: '#c82333'
      },
      'warning': {
        bg: '#ffc107',
        border: '#e0a800'
      },
      'info': {
        bg: '#17a2b8',
        border: '#117a8b'
      }
    };

    const color = colores[tipo] || colores.info;

    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    const idNotificacion = 'notificacion-' + Date.now();
    notificacion.id = idNotificacion;
    notificacion.className = 'global-notification';
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        background: ${color.bg};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-left: 4px solid ${color.border};
        animation: slideIn 0.3s ease;
        min-width: 300px;
        max-width: 400px;
    `;

    notificacion.innerHTML = `
        <div style="display: flex; align-items: flex-start; justify-content: space-between;">
            <div style="display: flex; align-items: flex-start; gap: 10px; flex: 1;">
                <span style="font-size: 20px; font-weight: bold; margin-top: 2px;">${iconos[tipo]}</span>
                <div style="flex: 1;">
                    <strong style="font-size: 16px; display: block; margin-bottom: 5px;">${titulos[tipo]}</strong>
                    <span style="font-size: 14px; word-wrap: break-word;">${mensaje}</span>
                </div>
            </div>
            <button onclick="document.getElementById('${idNotificacion}').remove()" 
                    style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; margin-left: 10px; flex-shrink: 0;">
                &times;
            </button>
        </div>
    `;

    // Remover notificaciones antiguas si hay muchas
    const notificaciones = document.querySelectorAll('.global-notification');
    if (notificaciones.length > 3) {
      notificaciones[0].remove();
    }

    document.body.appendChild(notificacion);

    // Auto-eliminar después del tiempo especificado
    setTimeout(() => {
      if (document.getElementById(idNotificacion)) {
        notificacion.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notificacion.remove(), 300);
      }
    }, tiempo);
  }

  // Añadir estilos CSS para animaciones
  const estilo = document.createElement('style');
  estilo.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
  document.head.appendChild(estilo);

  // Event Listeners
  document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('input', buscarDiscapacidades);
    document.getElementById('btnConfirmarCambio').addEventListener('click', confirmarCambioEstatus);

    // Limpiar formulario al cerrar modal
    $('#modalAgregar').on('hidden.bs.modal', function() {
      document.getElementById('formAgregar').reset();
    });

    // Inicializar tooltips de Bootstrap
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>

<?php
// Incluir layout2.php al final
require_once '/xampp/htdocs/final/layout/layaout2.php';
?>