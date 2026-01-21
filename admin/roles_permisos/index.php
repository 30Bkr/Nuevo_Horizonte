<?php
// admin/roles_permisos/index.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

error_log("DEBUG index.php - Sesión: " . print_r($_SESSION, true));

// Establecer título de página
$_SESSION['page_title'] = 'Gestión de Roles y Permisos';

if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
  // Incluir Notification para mostrar mensaje
  require_once '/xampp/htdocs/final/global/notifications.php';
  Notification::set("No tienes permisos para acceder a esta sección", "error");
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

// Proteger el acceso
require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/global/notifications.php';

echo "<!-- DEBUG: Antes de Notification::show() -->";
Notification::show();
echo "<!-- DEBUG: Después de Notification::show() -->";

require_once '/xampp/htdocs/final/layout/layaout1.php';

// Incluir modelo
require_once '/xampp/htdocs/final/app/controllers/roles/roles_permisos_model.php';

$model = new RolesPermisosModel();

// Obtener datos
$roles = $model->getRoles();
$permisosAgrupados = $model->getPermisosAgrupadosPorTipo();

// Procesar acciones
$accion = $_GET['accion'] ?? 'listar';
$id_rol = $_GET['id'] ?? 0;

// Contar permisos por tipo
$contadorPermisos = [
  'vista' => count($permisosAgrupados['vista']),
  'edicion' => count($permisosAgrupados['edicion']),
  'eliminacion' => count($permisosAgrupados['eliminacion']),
  'total' => count($permisosAgrupados['vista']) +
    count($permisosAgrupados['edicion']) +
    count($permisosAgrupados['eliminacion'])
];
?>

<div class="content-wrapper" style="margin-left: 250px;">
  <div class="content-header">
    <?php Notification::show(); ?>
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Gestión de Roles y Permisos</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Roles y Permisos</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <?php if ($accion == 'listar'): ?>

        <!-- Listado de Roles -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Roles del Sistema</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" onclick="nuevoRol()">
                <i class="fas fa-plus"></i> Nuevo Rol
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre del Rol</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($roles)): ?>
                    <tr>
                      <td colspan="4" class="text-center">No hay roles registrados</td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($roles as $rol): ?>
                      <tr>
                        <td><?= $rol->id_rol; ?></td>
                        <td><?= htmlspecialchars($rol->nom_rol); ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($rol->creacion)); ?></td>
                        <td>
                          <button type="button" class="btn btn-primary btn-sm"
                            onclick="editarPermisos(<?= $rol->id_rol; ?>)">
                            <i class="fas fa-key"></i> Permisos
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <?php elseif ($accion == 'permisos' && $id_rol > 0):

        // Obtener rol y sus permisos actuales
        $rol = $model->getRolById($id_rol);
        $permisosRol = $model->getPermisosByRol($id_rol);

        if (!$rol): ?>
          <div class="alert alert-danger">
            El rol no existe o ha sido eliminado.
          </div>
        <?php else: ?>

          <!-- Gestión de Permisos - REORGANIZADO POR TIPO -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-key mr-2"></i>
                Permisos para: <?= htmlspecialchars($rol->nom_rol); ?>
                <?php if ($id_rol == 1): ?>
                  <span class="badge badge-danger ml-2">Administrador</span>
                <?php endif; ?>
              </h3>
              <div class="card-tools">
                <a href="?accion=listar" class="btn btn-secondary btn-sm">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>
              </div>
            </div>

            <form id="formPermisos" action="guardar_permisos.php" method="POST">
              <input type="hidden" name="id_rol" value="<?= $rol->id_rol; ?>">

              <?php if ($id_rol == 1): ?>
                <div class="alert alert-info m-3">
                  <i class="fas fa-info-circle mr-2"></i>
                  El rol Administrador tiene todos los permisos por defecto y no puede ser modificado.
                </div>
              <?php endif; ?>

              <div class="card-body">

                <!-- Botones para seleccionar/deseleccionar por tipo -->
                <div class="row mb-3">
                  <div class="col-12">
                    <div class="btn-group flex-wrap" role="group">
                      <button type="button" class="btn btn-sm btn-outline-primary mb-1" onclick="selectAllTipo('vista')">
                        <i class="fas fa-check-square"></i> Todas las Vistas
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-warning mb-1" onclick="selectAllTipo('edicion')">
                        <i class="fas fa-check-square"></i> Todas las Ediciones
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger mb-1" onclick="selectAllTipo('eliminacion')">
                        <i class="fas fa-check-square"></i> Todas las Eliminaciones
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary mb-1" onclick="unselectAllTipo('vista')">
                        <i class="fas fa-times-circle"></i> Ninguna Vista
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary mb-1" onclick="unselectAllTipo('edicion')">
                        <i class="fas fa-times-circle"></i> Ninguna Edición
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-secondary mb-1" onclick="unselectAllTipo('eliminacion')">
                        <i class="fas fa-times-circle"></i> Ninguna Eliminación
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-success mb-1" onclick="selectAllPermisos()">
                        <i class="fas fa-check-double"></i> Todos los Permisos
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-dark mb-1" onclick="unselectAllPermisos()">
                        <i class="fas fa-ban"></i> Limpiar Todo
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Contenedores con tabs para organizar permisos por tipo -->
                <div class="nav-tabs-custom">
                  <ul class="nav nav-tabs" id="permisosTabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="vistas-tab" data-toggle="tab" href="#vistas" role="tab">
                        <i class="fas fa-eye mr-1"></i> Vistas
                        <span class="badge badge-primary ml-1"><?= $contadorPermisos['vista']; ?></span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="ediciones-tab" data-toggle="tab" href="#ediciones" role="tab">
                        <i class="fas fa-edit mr-1"></i> Ediciones
                        <span class="badge badge-warning ml-1"><?= $contadorPermisos['edicion']; ?></span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="eliminaciones-tab" data-toggle="tab" href="#eliminaciones" role="tab">
                        <i class="fas fa-trash mr-1"></i> Eliminaciones
                        <span class="badge badge-danger ml-1"><?= $contadorPermisos['eliminacion']; ?></span>
                      </a>
                    </li>
                  </ul>

                  <div class="tab-content p-3" id="permisosContent">

                    <!-- TAB: VISTAS -->
                    <div class="tab-pane fade show active" id="vistas" role="tabpanel">
                      <?php if (empty($permisosAgrupados['vista'])): ?>
                        <div class="alert alert-info">
                          <i class="fas fa-info-circle mr-2"></i>
                          No hay permisos de vista registrados.
                        </div>
                      <?php else: ?>
                        <div class="row">
                          <?php foreach ($permisosAgrupados['vista'] as $permiso):
                            $checked = in_array($permiso->id_permiso, $permisosRol) ? 'checked' : '';
                            $disabled = ($id_rol == 1) ? 'disabled' : '';
                          ?>
                            <div class="col-lg-4 col-md-6 mb-3">
                              <div class="card card-outline card-primary h-100">
                                <div class="card-header p-2">
                                  <h3 class="card-title mb-0">
                                    <div class="custom-control custom-checkbox">
                                      <input type="checkbox"
                                        class="custom-control-input permiso-checkbox permiso-vista"
                                        id="permiso_<?= $permiso->id_permiso; ?>"
                                        name="permisos[]"
                                        value="<?= $permiso->id_permiso; ?>"
                                        <?= $checked; ?>
                                        <?= $disabled; ?>
                                        data-tipo="vista">
                                      <label class="custom-control-label" for="permiso_<?= $permiso->id_permiso; ?>">
                                        <strong><?= htmlspecialchars($permiso->nom_url); ?></strong>
                                        <?php if ($id_rol == 1): ?>
                                          <span class="badge badge-success ml-1">Admin</span>
                                        <?php endif; ?>
                                      </label>
                                    </div>
                                  </h3>
                                </div>
                                <div class="card-body p-2">
                                  <p class="mb-1">
                                    <small><i class="fas fa-link mr-1"></i> <?= htmlspecialchars($permiso->url); ?></small>
                                  </p>
                                  <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    <?= htmlspecialchars($permiso->descripcion); ?>
                                  </p>
                                </div>
                                <div class="card-footer p-1">
                                  <small class="text-primary">
                                    <i class="fas fa-tag mr-1"></i> Vista
                                  </small>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>

                    <!-- TAB: EDICIONES -->
                    <div class="tab-pane fade" id="ediciones" role="tabpanel">
                      <?php if (empty($permisosAgrupados['edicion'])): ?>
                        <div class="alert alert-info">
                          <i class="fas fa-info-circle mr-2"></i>
                          No hay permisos de edición registrados.
                        </div>
                      <?php else: ?>
                        <div class="row">
                          <?php foreach ($permisosAgrupados['edicion'] as $permiso):
                            $checked = in_array($permiso->id_permiso, $permisosRol) ? 'checked' : '';
                            $disabled = ($id_rol == 1) ? 'disabled' : '';
                          ?>
                            <div class="col-lg-4 col-md-6 mb-3">
                              <div class="card card-outline card-warning h-100">
                                <div class="card-header p-2">
                                  <h3 class="card-title mb-0">
                                    <div class="custom-control custom-checkbox">
                                      <input type="checkbox"
                                        class="custom-control-input permiso-checkbox permiso-edicion"
                                        id="permiso_<?= $permiso->id_permiso; ?>"
                                        name="permisos[]"
                                        value="<?= $permiso->id_permiso; ?>"
                                        <?= $checked; ?>
                                        <?= $disabled; ?>
                                        data-tipo="edicion">
                                      <label class="custom-control-label" for="permiso_<?= $permiso->id_permiso; ?>">
                                        <strong><?= htmlspecialchars($permiso->nom_url); ?></strong>
                                        <?php if ($id_rol == 1): ?>
                                          <span class="badge badge-success ml-1">Admin</span>
                                        <?php endif; ?>
                                      </label>
                                    </div>
                                  </h3>
                                </div>
                                <div class="card-body p-2">
                                  <p class="mb-1">
                                    <small><i class="fas fa-link mr-1"></i> <?= htmlspecialchars($permiso->url); ?></small>
                                  </p>
                                  <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    <?= htmlspecialchars($permiso->descripcion); ?>
                                  </p>
                                </div>
                                <div class="card-footer p-1">
                                  <small class="text-warning">
                                    <i class="fas fa-tag mr-1"></i> Edición
                                  </small>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>

                    <!-- TAB: ELIMINACIONES -->
                    <div class="tab-pane fade" id="eliminaciones" role="tabpanel">
                      <?php if (empty($permisosAgrupados['eliminacion'])): ?>
                        <div class="alert alert-info">
                          <i class="fas fa-info-circle mr-2"></i>
                          No hay permisos de eliminación registrados.
                        </div>
                      <?php else: ?>
                        <div class="row">
                          <?php foreach ($permisosAgrupados['eliminacion'] as $permiso):
                            $checked = in_array($permiso->id_permiso, $permisosRol) ? 'checked' : '';
                            $disabled = ($id_rol == 1) ? 'disabled' : '';
                          ?>
                            <div class="col-lg-4 col-md-6 mb-3">
                              <div class="card card-outline card-danger h-100">
                                <div class="card-header p-2">
                                  <h3 class="card-title mb-0">
                                    <div class="custom-control custom-checkbox">
                                      <input type="checkbox"
                                        class="custom-control-input permiso-checkbox permiso-eliminacion"
                                        id="permiso_<?= $permiso->id_permiso; ?>"
                                        name="permisos[]"
                                        value="<?= $permiso->id_permiso; ?>"
                                        <?= $checked; ?>
                                        <?= $disabled; ?>
                                        data-tipo="eliminacion">
                                      <label class="custom-control-label" for="permiso_<?= $permiso->id_permiso; ?>">
                                        <strong><?= htmlspecialchars($permiso->nom_url); ?></strong>
                                        <?php if ($id_rol == 1): ?>
                                          <span class="badge badge-success ml-1">Admin</span>
                                        <?php endif; ?>
                                      </label>
                                    </div>
                                  </h3>
                                </div>
                                <div class="card-body p-2">
                                  <p class="mb-1">
                                    <small><i class="fas fa-link mr-1"></i> <?= htmlspecialchars($permiso->url); ?></small>
                                  </p>
                                  <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                    <?= htmlspecialchars($permiso->descripcion); ?>
                                  </p>
                                </div>
                                <div class="card-footer p-1">
                                  <small class="text-danger">
                                    <i class="fas fa-tag mr-1"></i> Eliminación
                                  </small>
                                </div>
                              </div>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <!-- Contador de permisos seleccionados -->
                <!-- <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="alert alert-info p-2">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <i class="fas fa-chart-bar mr-2"></i>
                          <strong>Resumen de selección:</strong>
                        </div>
                        <div>
                          <span class="badge badge-primary mr-2">
                            <i class="fas fa-eye"></i> Vistas: <span id="contadorVistas">0</span>/<?= $contadorPermisos['vista']; ?>
                          </span>
                          <span class="badge badge-warning mr-2">
                            <i class="fas fa-edit"></i> Ediciones: <span id="contadorEdiciones">0</span>/<?= $contadorPermisos['edicion']; ?>
                          </span>
                          <span class="badge badge-danger mr-2">
                            <i class="fas fa-trash"></i> Eliminaciones: <span id="contadorEliminaciones">0</span>/<?= $contadorPermisos['eliminacion']; ?>
                          </span>
                          <span class="badge badge-success">
                            <i class="fas fa-check-circle"></i> Total: <span id="contadorTotal">0</span>/<?= $contadorPermisos['total']; ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>

              <div class="card-footer">
                <?php if ($id_rol != 1): ?>
                  <button type="submit" class="btn btn-success" id="btnGuardar">
                    <i class="fas fa-save"></i> Guardar Permisos
                  </button>
                <?php endif; ?>
                <a href="?accion=listar" class="btn btn-secondary">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>

                <?php if ($id_rol != 1): ?>
                  <div class="float-right">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" id="selectAllTabs" onclick="toggleAllTabs()">
                      <label class="custom-control-label" for="selectAllTabs">
                        <strong>Seleccionar/Deseleccionar todo</strong>
                      </label>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </form>
          </div>

        <?php endif; ?>

      <?php endif; ?>
    </div>
  </section>
</div>

<!-- Modal para nuevo/editar rol -->
<div class="modal fade" id="modalRol" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="formRol" action="guardar_rol.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitulo">Nuevo Rol</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_rol" id="id_rol" value="0">
          <div class="form-group">
            <label for="nom_rol">Nombre del Rol *</label>
            <input type="text" class="form-control" id="nom_rol" name="nom_rol" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Funciones JavaScript
  function nuevoRol() {
    $('#id_rol').val(0);
    $('#nom_rol').val('');
    $('#modalTitulo').text('Nuevo Rol');
    $('#modalRol').modal('show');
  }

  function editarPermisos(id_rol) {
    window.location.href = '?accion=permisos&id=' + id_rol;
  }

  // Validar formulario de permisos
  $('#formPermisos').on('submit', function(e) {
    const checkboxes = $('input[name="permisos[]"]:checked');
    if (checkboxes.length === 0) {
      e.preventDefault();
      alert('Debe seleccionar al menos un permiso para el rol.');
      return false;
    }
    return true;
  });

  // FUNCIONES PARA GESTIÓN DE PERMISOS

  // Actualizar contadores
  function actualizarContadores() {
    const totalVistas = $('.permiso-vista:checked').length;
    const totalEdiciones = $('.permiso-edicion:checked').length;
    const totalEliminaciones = $('.permiso-eliminacion:checked').length;
    const totalGeneral = totalVistas + totalEdiciones + totalEliminaciones;

    $('#contadorVistas').text(totalVistas);
    $('#contadorEdiciones').text(totalEdiciones);
    $('#contadorEliminaciones').text(totalEliminaciones);
    $('#contadorTotal').text(totalGeneral);

    // Actualizar estado del checkbox general
    const totalPermisos = <?= $contadorPermisos['total']; ?>;
    const allChecked = totalGeneral === totalPermisos;
    const someChecked = totalGeneral > 0 && totalGeneral < totalPermisos;

    $('#selectAllTabs').prop('checked', allChecked);
    $('#selectAllTabs').prop('indeterminate', someChecked);
  }

  // Inicializar contadores
  $(document).ready(function() {
    actualizarContadores();

    // Actualizar contadores cuando cambia cualquier checkbox
    $('input[name="permisos[]"]').change(function() {
      actualizarContadores();
    });

    // Navegación con teclado entre tabs
    $(document).keydown(function(e) {
      if (e.ctrlKey && e.shiftKey) {
        const currentTab = $('#permisosTabs .nav-link.active');
        if (e.keyCode === 37) { // Ctrl+Shift+Flecha izquierda
          currentTab.parent().prev().find('a').tab('show');
          e.preventDefault();
        } else if (e.keyCode === 39) { // Ctrl+Shift+Flecha derecha
          currentTab.parent().next().find('a').tab('show');
          e.preventDefault();
        }
      }
    });
  });

  // Seleccionar todos los permisos de un tipo
  function selectAllTipo(tipo) {
    $('.permiso-' + tipo).prop('checked', true);
    actualizarContadores();
  }

  // Deseleccionar todos los permisos de un tipo
  function unselectAllTipo(tipo) {
    $('.permiso-' + tipo).prop('checked', false);
    actualizarContadores();
  }

  // Seleccionar todos los permisos
  function selectAllPermisos() {
    $('input[name="permisos[]"]').prop('checked', true);
    actualizarContadores();
  }

  // Deseleccionar todos los permisos
  function unselectAllPermisos() {
    $('input[name="permisos[]"]').prop('checked', false);
    actualizarContadores();
  }

  // Alternar selección de todos los tabs
  function toggleAllTabs() {
    const isChecked = $('#selectAllTabs').prop('checked');
    $('input[name="permisos[]"]').prop('checked', isChecked);
    actualizarContadores();
  }
</script>

<style>
  /* Estilos para las tarjetas de permisos */
  .card-outline {
    border-top-width: 3px;
  }

  .nav-tabs .nav-link {
    font-weight: 500;
    border-bottom-width: 3px;
  }

  .nav-tabs .nav-link.active {
    border-bottom-color: #007bff;
  }

  .nav-tabs .nav-link[href="#ediciones"].active {
    border-bottom-color: #ffc107;
  }

  .nav-tabs .nav-link[href="#eliminaciones"].active {
    border-bottom-color: #dc3545;
  }

  /* Estilos responsivos */
  @media (max-width: 768px) {
    .nav-tabs {
      flex-wrap: nowrap;
      overflow-x: auto;
      overflow-y: hidden;
    }

    .nav-tabs .nav-item {
      white-space: nowrap;
    }

    .btn-group {
      flex-wrap: wrap;
    }

    .btn-group .btn {
      margin-bottom: 5px;
      font-size: 0.8rem;
    }

    .card-body .row {
      margin-left: -5px;
      margin-right: -5px;
    }

    .col-lg-4,
    .col-md-6 {
      padding-left: 5px;
      padding-right: 5px;
    }
  }

  /* Animación suave para cambios de estado */
  input[type="checkbox"]:checked+label {
    color: #28a745 !important;
    font-weight: bold;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }

  /* Efecto para checkboxes deshabilitados (admin) */
  input[type="checkbox"]:disabled+label {
    opacity: 0.7;
  }

  /* Badges en tabs */
  .nav-tabs .badge {
    font-size: 0.7rem;
    padding: 2px 5px;
  }

  /* Footer de las tarjetas */
  .card-footer {
    background-color: rgba(0, 0, 0, 0.02);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
  }

  /* Contadores */
  #contadorVistas,
  #contadorEdiciones,
  #contadorEliminaciones,
  #contadorTotal {
    font-weight: bold;
  }

  /* Checkbox indeterminado */
  input[type="checkbox"]:indeterminate {
    background-color: #007bff;
    border-color: #007bff;
  }
</style>
<?php
require_once '/xampp/htdocs/final/layout/layaout2.php';
?>