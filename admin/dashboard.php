<?php
    // 1. Incluimos el nuevo header
    // La sesión y la seguridad ya se manejan dentro de header_admin.php
    include '../layout/header_admin.php'; 
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Tablero Principal</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Tablero</li>
                </ol>
            </div>
        </div>
    </div></section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bienvenido al Sistema</h3>
                    </div>
                    <div class="card-body">
                        ¡Hola, <strong><?php echo $nombre_usuario_sesion; ?></strong>!
                        <p>Has iniciado sesión como <strong><?php echo $rol_usuario_sesion; ?></strong>.</p>
                        <p>Desde aquí podrás gestionar las inscripciones y la administración del sistema.</p>
                        
                        </div>
                </div>
            </div>
        </div>
    </div></section>
<?php
    // 2. Incluimos el nuevo footer
    include '../layout/footer_admin.php'; 
?>