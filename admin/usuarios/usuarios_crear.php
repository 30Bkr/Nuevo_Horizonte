<?php 
// 1. Cargar la configuración global (USA RUTA RELATIVA)
// Desde /admin/usuarios/ se debe subir dos niveles para llegar a la raíz,
// y luego bajar a /app/config.php
require_once('../../app/config.php'); 

// 2. Incluir MODELO (USA RUTA ABSOLUTA)
// Si el error dice que models_usuarios.php falla, es porque ROOT_PATH no se estaba usando.
require_once(ROOT_PATH . '/app/models/models_usuarios.php'); 

// 3. Incluir LIBRERÍAS (USA RUTA ABSOLUTA)
require_once(ROOT_PATH . '/app/controllers/alerts.php'); // RUTA CORREGIDA
//require_once(ROOT_PATH . '/app/libs/auth.php'); 

// PROTEGER LA PÁGINA (Asegúrate de que esta línea esté después de la inclusión de auth.php)
//protegerPagina('USUARIOS_GESTIONAR'); 

// 4. Incluir el Header (USA RUTA RELATIVA)
include('../../layout/header_admin.php');

$usuarioModel = new UsuarioModel();
$roles = $usuarioModel->getRoles(); // Obtenemos los roles para el select
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inyecta el título en el content-header
        document.querySelector('.content-header .col-sm-6').innerHTML = '<h1><i class="fas fa-user-plus"></i> Crear Nuevo Usuario</h1>';
    });
</script>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos de Acceso del Usuario</h3>
            </div>
            
            <form id="formCrearUsuario" action="../../app/controllers/controller_usuarios.php" method="POST">
                
                <input type="hidden" name="accion" value="crear">

                <div class="card-body">
                    
                    <div class="form-group">
                        <label for="inputUsuario">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="inputUsuario" name="usuario" placeholder="Ej: jdoe" required>
                    </div>

                    <div class="form-group">
                        <label for="inputRol">Rol de Usuario</label>
                        <select class="form-control" id="inputRol" name="id_rol" required>
                            <option value="">Seleccione un Rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?php echo htmlspecialchars($rol['id_rol']); ?>">
                                    <?php echo htmlspecialchars($rol['nom_rol']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="alert alert-info" role="alert">
                        La contraseña del nuevo usuario será establecida por defecto como: 12345678
                    </div>
                    
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar Usuario
                    </button>
                    <a href="../dashboard.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php 
// RUTA DE INCLUSIÓN: Sube dos niveles para acceder a /layout/
include('../../layout/footer_admin.php'); 
?>