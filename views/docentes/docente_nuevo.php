<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Docente - Nuevo Horizonte</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <?php include '../../includes/navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include '../../includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Registrar Nuevo Docente</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                            <li class="breadcrumb-item active">Nuevo</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Mensajes de alerta -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Información del Docente</h3>
                            </div>
                            <!-- form start -->
                            <form id="formDocente" action="docente_guardar.php" method="post">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Datos Personales</h5>
                                            
                                            <div class="form-group">
                                                <label for="primer_nombre">Primer Nombre *</label>
                                                <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="segundo_nombre">Segundo Nombre</label>
                                                <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
                                            </div>

                                            <div class="form-group">
                                                <label for="primer_apellido">Primer Apellido *</label>
                                                <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="segundo_apellido">Segundo Apellido</label>
                                                <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                                            </div>

                                            <div class="form-group">
                                                <label for="cedula">Cédula *</label>
                                                <input type="text" class="form-control" id="cedula" name="cedula" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5>Información de Contacto</h5>

                                            <div class="form-group">
                                                <label for="telefono">Teléfono Móvil</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono">
                                            </div>

                                            <div class="form-group">
                                                <label for="telefono_hab">Teléfono Habitación</label>
                                                <input type="text" class="form-control" id="telefono_hab" name="telefono_hab">
                                            </div>

                                            <div class="form-group">
                                                <label for="correo">Correo Electrónico</label>
                                                <input type="email" class="form-control" id="correo" name="correo">
                                            </div>

                                            <div class="form-group">
                                                <label for="id_profesion">Profesión</label>
                                                <select class="form-control" id="id_profesion" name="id_profesion">
                                                    <option value="">Seleccionar profesión...</option>
                                                    <?php
                                                    include_once __DIR__ . '/../../app/conexion.php';
                                                    include_once __DIR__ . '/../../models/Docente.php';
                                                    
                                                    $database = new Conexion();
                                                    $db = $database->conectar();
                                                    
                                                    if ($db) {
                                                        $docente = new Docente($db);
                                                        $profesiones = $docente->obtenerProfesiones();
                                                        
                                                        while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='{$row['id_profesion']}'>{$row['profesion']}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Información del Usuario</h5>

                                            <div class="form-group">
                                                <label for="usuario">Nombre de Usuario *</label>
                                                <input type="text" class="form-control" id="usuario" name="usuario" required>
                                                <small class="form-text text-muted">El docente usará este usuario para acceder al sistema</small>
                                            </div>

                                            <div class="alert alert-info">
                                                <h6><i class="icon fas fa-info"></i> Información</h6>
                                                La contraseña por defecto será la cédula del docente. Se recomienda que el docente cambie su contraseña al primer acceso.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar Docente
                                    </button>
                                    <a href="docentes_list.php" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

</div>

<!-- jQuery -->
<script src="../../public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../public/dist/js/adminlte.min.js"></script>

<script>
$(function () {
    // Validación del formulario
    $('#formDocente').on('submit', function(e) {
        let cedula = $('#cedula').val();
        let usuario = $('#usuario').val();
        let isValid = true;

        // Validar cédula (solo números)
        if (cedula && !/^\d+$/.test(cedula)) {
            alert('La cédula debe contener solo números');
            isValid = false;
        }

        // Validar usuario (sin espacios)
        if (usuario && /\s/.test(usuario)) {
            alert('El nombre de usuario no puede contener espacios');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>