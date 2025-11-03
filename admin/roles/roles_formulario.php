<?php 
require_once(ROOT_PATH . '/app/controllers/alerts.php');
require_once('../../app/config.php'); 
include('../../layout/header_admin.php'); 
require_once(ROOT_PATH . '/app/models/models_roles.php');

$rolModel = new RolModel();
$id_rol = isset($_GET['id_rol']) ? (int)$_GET['id_rol'] : 0;
$accion = 'crear'; // Acción por defecto
$rol = ['nom_rol' => '', 'descripcion' => '']; // Valores por defecto

if ($id_rol > 0) {
    $rol = $rolModel->getRolPorId($id_rol);
    if ($rol) {
        $accion = 'actualizar'; 
    } else {
        setAlert('error', 'Rol no encontrado.', 'roles_listado.php');
        header("Location: roles_listado.php");
        exit();
    }
}

$titulo = ($accion == 'crear') ? 'Crear Nuevo Rol' : 'Editar Rol: ' . htmlspecialchars($rol['nom_rol']);
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('.content-header .col-sm-6').innerHTML = '<h1><i class="fas fa-users-cog"></i> <?php echo $titulo; ?></h1>';
    });
</script>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card card-<?php echo ($accion == 'crear') ? 'success' : 'info'; ?>">
            <div class="card-header">
                <h3 class="card-title"><?php echo $titulo; ?></h3>
            </div>
            
            <form action="../../app/controllers/controller_roles.php" method="POST">
                
                <input type="hidden" name="accion" value="<?php echo $accion; ?>">
                <?php if ($accion == 'actualizar'): ?>
                    <input type="hidden" name="id_rol" value="<?php echo htmlspecialchars($id_rol); ?>">
                <?php endif; ?>

                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="inputNomRol">Nombre del Rol</label>
                        <input type="text" class="form-control" id="inputNomRol" name="nom_rol" 
                               value="<?php echo htmlspecialchars($rol['nom_rol']); ?>" required 
                               placeholder="Ej: Administrador, Docente, Operador">
                    </div>

                    <div class="form-group">
                        <label for="inputDescripcion">Descripción (Opcional)</label>
                        <textarea class="form-control" id="inputDescripcion" name="descripcion" rows="3" 
                                  placeholder="Detalles sobre las responsabilidades del rol."><?php echo htmlspecialchars($rol['descripcion']); ?></textarea>
                    </div>
                    
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-<?php echo ($accion == 'crear') ? 'success' : 'info'; ?>">
                        <i class="fas fa-save"></i> Guardar Rol
                    </button>
                    <a href="roles_listado.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
include('../../layout/footer_admin.php'); 
?>