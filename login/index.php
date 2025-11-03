<?php 
    // Mantenemos la lógica de sesión
    session_start();
    if (isset($_SESSION['id_usuario'])) {
        header("Location: ../admin/index.php"); // O al dashboard
        exit;
    }

    // Incluimos el NUEVO header de login
    include '../layout/header_login.php'; 
?>

<div class="container-fluid p-0">
    <div class="row g-0 h-100">
        
        <div class="col-lg-7 d-none d-lg-block">
            <div class="login-image-side"></div>
        </div>

        <div class="col-lg-5 d-flex align-items-center justify-content-center">
            
            <div class="card shadow-lg" style="width: 25rem;">
                <div class="card-body p-5">
                    
                    <div class="text-center mb-4">
                        <h3 class="mt-3">Bienvenido</h3>
                        <p class="text-muted">Sistema de Inscripción "Nuevo Horizonte"</p>
                    </div>
                    
                    <div id="loginAlerts"></div>

                    <form id="loginForm" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-sign-in-alt me-2"></i> Ingresar
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    // Incluimos el NUEVO footer de login
    include '../layout/footer_login.php'; 
?>