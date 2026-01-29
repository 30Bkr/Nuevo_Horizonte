<?php
// admin/configuraciones/configuracion/parentesco.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Establecer título de página
$_SESSION['page_title'] = 'Gestión de Parentescos';

// Incluir archivos necesarios
require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/notifications.php';
require_once '/xampp/htdocs/final/app/conexion.php';
require_once '/xampp/htdocs/final/app/controllers/parentesco/parentescoController.php';

// Verificar permisos
if (!PermissionManager::canViewAny(['admin/configuraciones/configuracion/parentesco.php'])) {
  Notification::set("No tienes permisos para acceder a esta sección", "error");
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

// Obtener datos iniciales
try {
  $conexion = new Conexion();
  $pdo = $conexion->conectar();
  $parentescoController = new ParentescoController($pdo);

  // Usar el nuevo método para obtener todas los parentescos
  $parentescos = $parentescoController->obtenerTodosLosParentescos();
  foreach ($parentescos as &$parentesco) {
    $parentesco['en_uso'] = $parentescoController->parentescoEnUso($parentesco['id_parentesco']);
    $parentesco['conteo_usos'] = $parentescoController->obtenerConteoUsosParentesco($parentesco['id_parentesco']);
  }
  unset($parentesco);
  $totalUsos = $parentescoController->contarUsosParentesco();
} catch (Exception $e) {
  $parentescos = [];
  $totalUsos = 0;
  Notification::set("Error al cargar parentescos: " . $e->getMessage(), "error");
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
          <h1 class="m-0">Gestión de Parentescos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/configuraciones/index.php">Configuraciones</a></li>
            <li class="breadcrumb-item active">Parentescos</li>
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
                <i class="fas fa-users mr-2"></i>
                Gestión de Parentescos
              </h1>
              <p class="text-muted">Administra el catálogo de parentescos familiares</p>
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
              <h3 id="totalParentescos"><?php echo count($parentescos); ?></h3>
              <p>Total de Parentescos</p>
            </div>
            <div class="icon">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="parentescosActivos"><?php echo count(array_filter($parentescos, function ($p) {
                                            return $p['estatus'] == 1;
                                          })); ?></h3>
              <p>Parentescos Activos</p>
            </div>
            <div class="icon">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="parentescosInactivos"><?php echo count(array_filter($parentescos, function ($p) {
                                              return $p['estatus'] == 0;
                                            })); ?></h3>
              <p>Parentescos Inactivos</p>
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
              <i class="fas fa-user-friends"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabla de Parentescos -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Parentescos Registrados</h3>
              <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" id="searchInput" class="form-control float-right" placeholder="Buscar...">
                  <div class="input-group-append">
                    <button type="button" class="btn btn-default" onclick="buscarParentescos()">
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
                    <th>Nombre del Parentesco</th>
                    <th>Estatus</th>
                    <th>Fecha de Creación</th>
                    <th>Última Actualización</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="tablaParentescos">
                  <?php if (empty($parentescos)): ?>
                    <tr>
                      <td colspan="6" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay parentescos registrados</p>
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($parentescos as $parentesco): ?>
                      <tr id="parentesco-<?php echo $parentesco['id_parentesco']; ?>">
                        <td><?php echo $parentesco['id_parentesco']; ?></td>
                        <td>
                          <div class="d-flex align-items-center">
                            <i class="fas fa-user-friends text-primary mr-2"></i>
                            <span id="nombre-<?php echo $parentesco['id_parentesco']; ?>">
                              <?php echo htmlspecialchars($parentesco['parentesco']); ?>
                            </span>
                          </div>
                        </td>
                        <td>
                          <span class="badge badge-<?php echo $parentesco['estatus'] == 1 ? 'success' : 'danger'; ?>"
                            id="estatus-<?php echo $parentesco['id_parentesco']; ?>">
                            <?php echo $parentesco['estatus'] == 1 ? 'Activo' : 'Inactivo'; ?>
                          </span>
                        </td>
                        <td>
                          <?php echo date('d/m/Y H:i', strtotime($parentesco['creacion'])); ?>
                        </td>
                        <td>
                          <?php
                          if ($parentesco['actualizacion']) {
                            echo date('d/m/Y H:i', strtotime($parentesco['actualizacion']));
                          } else {
                            echo '<span class="text-muted">Sin actualizar</span>';
                          }
                          ?>
                        </td>
                        <td>
                          <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary"
                              onclick="editarParentesco(<?php echo $parentesco['id_parentesco']; ?>, '<?php echo htmlspecialchars($parentesco['parentesco']); ?>', <?php echo $parentesco['estatus']; ?>)">
                              <i class="fas fa-edit"></i>
                            </button>
                            <?php if ($parentesco['estatus'] == 1): ?>
                              <?php if ($parentesco['en_uso']): ?>
                                <!-- Permite desactivar pero con advertencia -->
                                <button class="btn btn-sm btn-outline-warning"
                                  data-toggle="tooltip"
                                  title="Desactivar (en uso en <?php echo $parentesco['conteo_usos']; ?> registro(s))"
                                  onclick="cambiarEstatusConAdvertencia(<?php echo $parentesco['id_parentesco']; ?>, '<?php echo htmlspecialchars($parentesco['parentesco']); ?>', 0, <?php echo $parentesco['conteo_usos']; ?>)">
                                  <i class="fas fa-exclamation-triangle"></i>
                                </button>
                              <?php else: ?>
                                <button class="btn btn-sm btn-outline-danger"
                                  onclick="cambiarEstatus(<?php echo $parentesco['id_parentesco']; ?>, '<?php echo htmlspecialchars($parentesco['parentesco']); ?>', 0)">
                                  <i class="fas fa-pause"></i>
                                </button>
                              <?php endif; ?>
                            <?php else: ?>
                              <button class="btn btn-sm btn-outline-success"
                                onclick="cambiarEstatus(<?php echo $parentesco['id_parentesco']; ?>, '<?php echo htmlspecialchars($parentesco['parentesco']); ?>', 1)">
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
                  Mostrando <span id="contadorParentescos"><?php echo count($parentescos); ?></span> parentescos
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
              <li><strong>Parentescos en uso:</strong> Se pueden desactivar pero aparecerán advertencias ya que están siendo utilizados en las relaciones estudiantes-representantes</li>
              <li><strong>Parentescos sin uso:</strong> Se pueden desactivar sin problemas</li>
              <li><strong>Parentescos inactivos:</strong> No aparecerán en los formularios de nuevos registros</li>
              <li>Los registros existentes que ya usen el parentesco no se verán afectados al desactivarlo</li>
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
                  <span class="badge badge-success mr-2">Activo</span>
                  <small>Disponible para nuevos registros</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-danger mr-2">Inactivo</span>
                  <small>No disponible para nuevos registros</small>
                </div>
                <!-- <div class="col-md-3">
                  <span class="badge badge-info mr-2">En uso</span>
                  <small>Usado en relaciones estudiantes-representantes</small>
                </div>
                <div class="col-md-3">
                  <span class="badge badge-secondary mr-2">Sin uso</span>
                  <small>No usado en el sistema</small>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal Agregar Parentesco -->
<div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-plus-circle mr-2"></i>
          Agregar Nuevo Parentesco
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formAgregar" onsubmit="agregarParentesco(event)">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_parentesco">Nombre del Parentesco:</label>
            <input type="text" class="form-control" id="nombre_parentesco" name="nombre_parentesco"
              placeholder="Ej: Hermano, Hermana, Primo, Prima..." required>
            <small class="form-text text-muted">
              Ingresa el nombre completo del parentesco familiar
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

<!-- Modal Editar Parentesco -->
<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fas fa-edit mr-2"></i>
          Editar Parentesco
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="formEditar" onsubmit="actualizarParentesco(event)">
        <input type="hidden" id="id_parentesco_edit" name="id_parentesco">
        <div class="modal-body">
          <div class="form-group">
            <label for="nombre_parentesco_edit">Nombre del Parentesco:</label>
            <input type="text" class="form-control" id="nombre_parentesco_edit" name="nombre_parentesco" required>
          </div>
          <div class="form-group">
            <label for="estatus_parentesco">Estatus:</label>
            <select class="form-control" id="estatus_parentesco" name="estatus">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Actualizar Parentesco
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
  let parentescoSeleccionado = null;

  // Nueva función para desactivar con advertencia cuando está en uso
  function cambiarEstatusConAdvertencia(id, nombre, nuevoEstatus, conteoUsos) {
    parentescoSeleccionado = {
      id,
      nombre,
      nuevoEstatus
    };

    const mensaje = nuevoEstatus == 1 ?
      'Podrá ser asignado a estudiantes y representantes nuevamente.' :
      `ADVERTENCIA: Este parentesco está en uso en ${conteoUsos} registro(s).<br><br>` +
      `Al desactivarlo:<br>` +
      `✓ No aparecerá en los formularios de nuevos registros<br>` +
      `✓ Las relaciones que ya lo usan conservarán el parentesco<br>` +
      `✓ No afectará los registros existentes`;

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${nuevoEstatus == 1 ? 'activar' : 'desactivar'}</strong> el parentesco:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<div style="background-color: ${nuevoEstatus == 1 ? '#d4edda' : '#fff3cd'}; padding: 10px; border-radius: 5px; border-left: 4px solid ${nuevoEstatus == 1 ? '#28a745' : '#ffc107'};">` +
      `<small>${mensaje}</small>` +
      `</div>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Actualiza la función cambiarEstatus para casos normales (sin uso)
  function cambiarEstatus(id, nombre, nuevoEstatus) {
    parentescoSeleccionado = {
      id,
      nombre,
      nuevoEstatus
    };

    const accion = nuevoEstatus == 1 ? 'activar' : 'desactivar';
    const mensaje = nuevoEstatus == 1 ?
      'Podrá ser asignado a estudiantes y representantes nuevamente.' :
      'No podrá ser asignado a nuevas relaciones estudiantes-representantes.';

    $('#mensajeConfirmacion').html(
      `¿Estás seguro de que deseas <strong>${accion}</strong> el parentesco:<br><strong>"${nombre}"</strong>?<br><br>` +
      `<small class="text-muted">${mensaje}</small>`
    );

    $('#modalConfirmacion').modal('show');
  }

  // Funciones para abrir modales
  function abrirModalAgregar() {
    $('#modalAgregar').modal('show');
  }

  function editarParentesco(id, nombre, estatus) {
    $('#id_parentesco_edit').val(id);
    $('#nombre_parentesco_edit').val(nombre);
    $('#estatus_parentesco').val(estatus);
    $('#modalEditar').modal('show');
  }

  async function agregarParentesco(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const nombre = formData.get('nombre_parentesco').trim();

    if (!nombre) {
      mostrarNotificacion('El nombre del parentesco es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Guardando...';

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/parentesco/accionesParentesco.php', {
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
        recargarParentescos();
      } else {
        // Si es duplicado, no cerramos el modal para que corrija
        if (result.duplicate) {
          mostrarNotificacion(result.message, 'warning');
        } else {
          mostrarNotificacion(result.message, 'error');
          $('#modalAgregar').modal('hide');
        }
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Guardar Parentesco';
    }
  }

  async function actualizarParentesco(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const id = formData.get('id_parentesco');
    const nombre = formData.get('nombre_parentesco').trim();
    const estatus = formData.get('estatus');

    if (!nombre) {
      mostrarNotificacion('El nombre del parentesco es requerido', 'error');
      return;
    }

    const boton = event.target.querySelector('button[type="submit"]');
    boton.disabled = true;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...';

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/parentesco/accionesParentesco.php', {
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
        recargarParentescos();
      } else {
        // Si es duplicado, mantenemos abierto el modal
        if (result.duplicate) {
          mostrarNotificacion(result.message, 'warning');
        } else {
          mostrarNotificacion(result.message, 'error');
        }
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    } finally {
      boton.disabled = false;
      boton.innerHTML = '<i class="fas fa-save mr-1"></i> Actualizar Parentesco';
    }
  }

  // async function confirmarCambioEstatus() {
  //   if (!parentescoSeleccionado) return;

  //   const {
  //     id,
  //     nombre,
  //     nuevoEstatus
  //   } = parentescoSeleccionado;

  //   try {
  //     const response = await fetch('<?= URL; ?>/app/controllers/parentesco/accionesParentesco.php', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //       },
  //       body: `action=actualizar&id=${id}&nombre=${encodeURIComponent(nombre)}&estatus=${nuevoEstatus}`
  //     });

  //     const result = await response.json();

  //     if (result.success) {
  //       mostrarNotificacion(result.message, 'success');
  //       $('#modalConfirmacion').modal('hide');
  //       recargarParentescos();
  //     } else {
  //       mostrarNotificacion(result.message, 'error');
  //       $('#modalConfirmacion').modal('hide');
  //     }
  //   } catch (error) {
  //     mostrarNotificacion('Error de conexión: ' + error.message, 'error');
  //   }
  // }
  // En parentesco.php, prueba cambiar:
  async function confirmarCambioEstatus() {
    if (!parentescoSeleccionado) return;

    const {
      id,
      nombre,
      nuevoEstatus
    } = parentescoSeleccionado;

    try {
      const response = await fetch('<?= URL; ?>/app/controllers/parentesco/accionesParentesco.php', {
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

        // En lugar de recargarParentescos(), usa location.reload() temporalmente
        location.reload();
      } else {
        mostrarNotificacion(result.message, 'error');
        $('#modalConfirmacion').modal('hide');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    }
  }

  async function recargarParentescos() {
    try {
      const response = await fetch('<?= URL; ?>/app/controllers/parentesco/accionesParentesco.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=obtener_todos'
      });

      const text = await response.text();
      let result;
      try {
        result = JSON.parse(text);
      } catch (e) {
        console.error('Error parseando JSON:', e);
        mostrarNotificacion('Error al cargar parentescos: Respuesta no válida del servidor', 'error');
        return;
      }

      if (result.success) {
        actualizarTabla(result.data);
        actualizarEstadisticas(result.data);
      } else {
        mostrarNotificacion('Error al cargar parentescos: ' + result.message, 'error');
      }
    } catch (error) {
      mostrarNotificacion('Error de conexión: ' + error.message, 'error');
    }
  }

  function actualizarTabla(parentescos) {
    const tbody = document.getElementById('tablaParentescos');

    if (parentescos.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" class="text-center py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">No hay parentescos registrados</p>
          </td>
        </tr>
      `;
      return;
    }

    // Construir la tabla con la información de uso que ya viene del servidor
    tbody.innerHTML = parentescos.map(parentesco => {

      return `
      <tr id="parentesco-${parentesco.id_parentesco}">
        <td>${parentesco.id_parentesco}</td>
        <td>
          <div class="d-flex align-items-center">
            <i class="fas fa-user-friends text-primary mr-2"></i>
            <span id="nombre-${parentesco.id_parentesco}">${escapeHtml(parentesco.parentesco)}</span>
          </div>
        </td>
        <td>
          <span class="badge badge-${parentesco.estatus == 1 ? 'success' : 'danger'}" 
                id="estatus-${parentesco.id_parentesco}">
            ${parentesco.estatus == 1 ? 'Activo' : 'Inactivo'}
          </span>
        </td>
        <td>${formatFecha(parentesco.creacion)}</td>
        <td>${parentesco.actualizacion ? formatFecha(parentesco.actualizacion) : '<span class="text-muted">Sin actualizar</span>'}</td>
        <td>
          <div class="btn-group">
            <button class="btn btn-sm btn-outline-primary" 
                    onclick="editarParentesco(${parentesco.id_parentesco}, ${JSON.stringify(parentesco.parentesco)}, ${parentesco.estatus})">
              <i class="fas fa-edit"></i>
            </button>
            ${parentesco.estatus == 1 ? 
              (parentesco.en_uso ?  <!-- Usa parentesco.en_uso directamente -->
                `<button class="btn btn-sm btn-outline-warning"
                        data-toggle="tooltip"
                        title="Desactivar (en uso en ${parentesco.conteo_usos} registro(s))"
                        onclick="cambiarEstatusConAdvertencia(${parentesco.id_parentesco}, ${JSON.stringify(parentesco.parentesco)}, 0, ${parentesco.conteo_usos})">
                  <i class="fas fa-exclamation-triangle"></i>
                </button>` :
                `<button class="btn btn-sm btn-outline-danger"
                        onclick="cambiarEstatus(${parentesco.id_parentesco}, ${JSON.stringify(parentesco.parentesco)}, 0)">
                  <i class="fas fa-pause"></i>
                </button>`
              ) :
              `<button class="btn btn-sm btn-outline-success"
                      onclick="cambiarEstatus(${parentesco.id_parentesco}, ${JSON.stringify(parentesco.parentesco)}, 1)">
                <i class="fas fa-play"></i>
              </button>`
            }
          </div>
        </td>
      </tr>
    `;
    }).join('');

    document.getElementById('contadorParentescos').textContent = parentescos.length;

    // Re-inicializar tooltips
    $('[data-toggle="tooltip"]').tooltip();
  }

  function actualizarEstadisticas(parentescos) {
    const activos = parentescos.filter(p => p.estatus == 1).length;
    const inactivos = parentescos.filter(p => p.estatus == 0).length;

    document.getElementById('totalParentescos').textContent = parentescos.length;
    document.getElementById('parentescosActivos').textContent = activos;
    document.getElementById('parentescosInactivos').textContent = inactivos;
  }

  function buscarParentescos() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tablaParentescos tr');

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
    document.getElementById('searchInput').addEventListener('input', buscarParentescos);
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