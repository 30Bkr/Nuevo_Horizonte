<?php 
// 1. Cargar la configuración global
require_once('../../app/config.php'); 

// 2. Incluir el Header
include('../../layout/header_admin.php'); 

// 3. Incluir el Modelo de Usuarios
require_once(ROOT_PATH . '/app/models/models_usuarios.php');
require_once(ROOT_PATH . '/app/models/models_roles.php'); // Necesitamos el modelo de roles para obtener la lista de roles

$usuarioModel = new UsuarioModel();
$rolModel = new RolModel();
$roles = $rolModel->getListadoRoles(); // Obtiene todos los roles disponibles

// 4. Obtener ID del usuario a editar
$id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;

if ($id_usuario > 0) {
    $usuario = $usuarioModel->getUsuarioPorId($id_usuario);
    if (!$usuario) {
        setAlert('error', 'Usuario no encontrado.', 'usuarios_listado.php');
        header("Location: usuarios_listado.php");
        exit();
    }
} else {
    setAlert('warning', 'ID de usuario no especificado.', 'usuarios_listado.php');
    header("Location: usuarios_listado.php");
    exit();
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('.content-header .col-sm-6').innerHTML = '<h1><i class="fas fa-edit"></i> Editar Usuario: <?php echo htmlspecialchars($usuario['usuario']); ?></h1>';
    });
</script>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Datos de Gestión de Usuario</h3>
            </div>
            
            <form id="formEditarUsuario" action="../../app/controllers/controller_usuarios.php" method="POST">
                
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($usuario['id_usuario']); ?>">

                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="inputUsuario">Nombre de Usuario</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="inputRol">Rol de Usuario</label>
                        <select class="form-control" id="inputRol" name="id_rol" required>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo htmlspecialchars($rol['id_rol']); ?>"
                                    <?php echo ($usuario['id_rol'] == $rol['id_rol']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($rol['nom_rol']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="inputEstatus">Estatus</label>
                        <select class="form-control" id="inputEstatus" name="estatus" required>
                            <option value="1" <?php echo ($usuario['estatus'] == 1) ? 'selected' : ''; ?>>Activo</option>
                            <option value="0" <?php echo ($usuario['estatus'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                        </select>
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-sync-alt"></i> Actualizar Usuario
                    </button>
                    <a href="usuarios_listado.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// 5. Incluir el Footer
include('../../layout/footer_admin.php'); 
?>