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
$permisos = $model->getPermisos();

// Procesar acciones
$accion = $_GET['accion'] ?? 'listar';
$id_rol = $_GET['id'] ?? 0;

// Mensajes
$mensaje = '';
$tipoMensaje = 'info';
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
                          <button type="button" class="btn btn-warning btn-sm"
                            onclick="editarRol(<?= $rol->id_rol; ?>)">
                            <i class="fas fa-edit"></i> Editar
                          </button>
                          <?php if ($rol->id_rol != 1): // No permitir eliminar Administrador 
                          ?>
                            <button type="button" class="btn btn-danger btn-sm"
                              onclick="eliminarRol(<?= $rol->id_rol; ?>, '<?= htmlspecialchars($rol->nom_rol); ?>')">
                              <i class="fas fa-trash"></i> Eliminar
                            </button>
                          <?php endif; ?>
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

          <!-- Gestión de Permisos -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-key mr-2"></i>
                Permisos para: <?= htmlspecialchars($rol->nom_rol); ?>
              </h3>
              <div class="card-tools">
                <a href="?accion=listar" class="btn btn-secondary btn-sm">
                  <i class="fas fa-arrow-left"></i> Volver
                </a>
              </div>
            </div>

            <form id="formPermisos" action="guardar_permisos.php" method="POST">
              <input type="hidden" name="id_rol" value="<?= $rol->id_rol; ?>">

              <div class="card-body">
                <div class="row">
                  <?php foreach ($permisos as $permiso):
                    $checked = in_array($permiso->id_permiso, $permisosRol) ? 'checked' : '';
                  ?>
                    <div class="col-md-4 mb-3">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                          class="custom-control-input"
                          id="permiso_<?= $permiso->id_permiso; ?>"
                          name="permisos[]"
                          value="<?= $permiso->id_permiso; ?>"
                          <?= $checked; ?>>
                        <label class="custom-control-label" for="permiso_<?= $permiso->id_permiso; ?>">
                          <strong><?= htmlspecialchars($permiso->nom_url); ?></strong><br>
                          <small class="text-muted"><?= htmlspecialchars($permiso->url); ?></small><br>
                          <small class="text-muted"><?= htmlspecialchars($permiso->descripcion); ?></small>
                        </label>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-success">
                  <i class="fas fa-save"></i> Guardar Permisos
                </button>
                <a href="?accion=listar" class="btn btn-secondary">Cancelar</a>
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

  function editarRol(id_rol) {
    // Aquí deberías hacer una petición AJAX para obtener los datos del rol
    // Por ahora, redirigimos a una página de edición
    window.location.href = '?accion=editar&id=' + id_rol;
  }

  function editarPermisos(id_rol) {
    window.location.href = '?accion=permisos&id=' + id_rol;
  }

  function eliminarRol(id_rol, nombre) {
    if (confirm('¿Está seguro de eliminar el rol "' + nombre + '"?\n\nNota: Los usuarios con este rol perderán sus permisos.')) {
      window.location.href = 'eliminar_rol.php?id=' + id_rol;
    }
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
</script>

<style>
  .custom-checkbox {
    padding: 10px;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    transition: all 0.3s;
  }

  .custom-checkbox:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
  }

  .custom-control-label {
    cursor: pointer;
    width: 100%;
  }
</style>
<?php
require_once '/xampp/htdocs/final/layout/layaout2.php';
?>