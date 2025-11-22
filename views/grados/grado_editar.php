<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

$id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: ID no especificado.');

try {
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);
    
    if (!$grado->obtenerPorId($id)) {
        $_SESSION['error'] = "Grado no encontrado.";
        header("Location: grados_list.php");
        exit();
    }
    
    // Procesar actualización - SOLO capacidad es editable
    if ($_POST) {
        $grado->capacidad = $_POST['capacidad'];
        
        if ($grado->actualizar()) {
            $_SESSION['success'] = "Grado actualizado exitosamente.";
            header("Location: grados_list.php");
            exit();
        } else {
            $_SESSION['error'] = "No se pudo actualizar el grado.";
        }
    }
    
    // Obtener información completa del grado para mostrar
    $info_grado = $grado->obtenerGradoPorId($id);
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: grados_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Grado - Nuevo Horizonte</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/final/index.php" class="nav-link">Inicio</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="grados_list.php" class="nav-link">Grados</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link active">Editar Grado</a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="/final/index.php" class="brand-link">
            <span class="brand-text font-weight-light">Nuevo Horizonte</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="/final/index.php" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="docentes_list.php" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Docentes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="grados_list.php" class="nav-link active">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>Grados</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Editar Grado/Sección</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="grados_list.php">Grados</a></li>
                            <li class="breadcrumb-item active">Editar</li>
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
                        <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Editar Grado/Sección</h3>
                            </div>
                            <form method="post" action="grado_editar.php?id=<?php echo $id; ?>">
                                <div class="card-body">
                                    <!-- Grado (solo lectura) -->
                                    <div class="form-group">
                                        <label for="grado_display">Grado/Nivel:</label>
                                        <input type="text" class="form-control" id="grado_display" 
                                               value="<?php echo htmlspecialchars($info_grado['nombre_grado'] ?? ''); ?>" 
                                               readonly style="background-color: #f8f9fa;">
                                        <small class="form-text text-muted">El grado no se puede modificar</small>
                                    </div>
                                    
                                    <!-- Sección (solo lectura) -->
                                    <div class="form-group">
                                        <label for="seccion_display">Sección:</label>
                                        <input type="text" class="form-control" id="seccion_display" 
                                               value="<?php echo htmlspecialchars($info_grado['seccion'] ?? ''); ?>" 
                                               readonly style="background-color: #f8f9fa;">
                                        <small class="form-text text-muted">La sección no se puede modificar</small>
                                    </div>
                                    
                                    <!-- Capacidad (editable) -->
                                    <div class="form-group">
                                        <label for="capacidad">Capacidad:</label>
                                        <input type="number" class="form-control" id="capacidad" name="capacidad" 
                                               min="1" max="50" value="<?php echo $grado->capacidad; ?>" required>
                                        <small class="form-text text-muted">Número máximo de estudiantes permitidos</small>
                                    </div>

                                    <!-- Campos ocultos para mantener la integridad de los datos -->
                                    <input type="hidden" name="id_nivel" value="<?php echo $grado->id_nivel; ?>">
                                    <input type="hidden" name="id_seccion" value="<?php echo $grado->id_seccion; ?>">
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Actualizar Capacidad
                                    </button>
                                    <a href="grados_list.php" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Información</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Grado/Nivel:</strong> No se puede modificar una vez creado.</p>
                                <p><strong>Sección:</strong> No se puede modificar una vez creada.</p>
                                <p><strong>Capacidad:</strong> Es el único campo editable. Representa el número máximo de estudiantes.</p>
                                <div class="alert alert-warning">
                                    <small>
                                        <i class="icon fas fa-exclamation-triangle"></i> 
                                        Si necesita cambiar el grado o sección, debe crear uno nuevo y eliminar este.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong>
        Todos los derechos reservados.
    </footer>
</div>

<!-- Scripts -->
<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/final/public/dist/js/adminlte.min.js"></script>
</body>
</html>