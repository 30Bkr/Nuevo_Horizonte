<?php
    include '../../layout/header_admin.php'; 
    
    // Verificación de Rol (solo Admin puede crear)
    if ($_SESSION['id_rol'] != 1) {
        echo '<section class="content"><div class="container-fluid"><div class="alert alert-danger">Acceso Denegado.</div></div></section>';
        include '../../layout/footer_admin.php';
        exit;
    }

    // (Necesitaremos cargar los roles desde la BD para el select)
    // $roles = $rolModel->obtenerTodos($pdo); 
    // Por ahora, lo dejamos estático
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Crear Nuevo Usuario</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="../dashboard.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="usuarios_listado.php">Usuarios</a></li>
                    <li class="breadcrumb-item active">Crear</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Usuario</h3>
            </div>
            
            <form id="formCrearUsuario" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom_usuario">Nombre Completo</label>
                                <input type="text" class="form-control" id="nom_usuario" name="nom_usuario" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="usuario">Usuario (Cédula)</label>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_rol">Rol del Sistema</label>
                                <select class="form-control" id="id_rol" name="id_rol" required>
                                    <option value="">Seleccione un rol...</option>
                                    <option value="1">ADMINISTRADOR</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contraseña</label>
                                <input type="text" class="form-control" value="12345678" disabled>
                                <small class="form-text text-muted">
                                    La contraseña predeterminada es <strong>12345678</strong>. El usuario deberá cambiarla.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i> Guardar Usuario
                    </button>
                    <a href="usuarios_listado.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php 
    include '../../layout/footer_admin.php'; 
?>