<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

// Procesar formulario
if ($_POST) {
    try {
        $database = new Conexion();
        $db = $database->conectar();
        
        $grado = new Grado($db);
        
        $grado->id_nivel = $_POST['id_nivel'];
        $grado->id_seccion = $_POST['id_seccion'];
        $grado->capacidad = $_POST['capacidad'];
        
        // Verificar si ya existe la combinación
        if ($grado->existeCombinacion($grado->id_nivel, $grado->id_seccion)) {
            $_SESSION['error'] = "Ya existe un grado con esta combinación de nivel y sección.";
        } else {
            if ($grado->crear()) {
                $_SESSION['success'] = "Grado/sección creado exitosamente.";
                header("Location: grados_list.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo crear el grado/sección.";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Grado - Nuevo Horizonte</title>
    
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
                <a href="#" class="nav-link active">Nuevo Grado</a>
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
                        <h1>Nuevo Grado/Sección</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="grados_list.php">Grados</a></li>
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
                        <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Registrar Nuevo Grado/Sección</h3>
                            </div>
                            <form method="post" action="grado_nuevo.php">
                                <div class="card-body">
                                    <?php
                                    try {
                                        $database = new Conexion();
                                        $db = $database->conectar();
                                        $grado = new Grado($db);
                                        
                                        $niveles = $grado->obtenerNiveles();
                                        $secciones = $grado->obtenerSecciones();
                                    ?>
                                    <div class="form-group">
                                        <label for="id_nivel">Grado/Nivel:</label>
                                        <select class="form-control" id="id_nivel" name="id_nivel" required>
                                            <option value="">Seleccione un grado</option>
                                            <?php while ($nivel = $niveles->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?php echo $nivel['id_nivel']; ?>">
                                                    <?php echo $nivel['nom_nivel']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="id_seccion">Sección:</label>
                                        <select class="form-control" id="id_seccion" name="id_seccion" required>
                                            <option value="">Seleccione una sección</option>
                                            <?php while ($seccion = $secciones->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?php echo $seccion['id_seccion']; ?>">
                                                    <?php echo $seccion['nom_seccion']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="capacidad">Capacidad:</label>
                                        <input type="number" class="form-control" id="capacidad" name="capacidad" 
                                               min="1" max="50" required placeholder="Ej: 25">
                                        <small class="form-text text-muted">Número máximo de estudiantes permitidos</small>
                                    </div>
                                    <?php } catch (Exception $e) { ?>
                                        <div class="alert alert-danger">Error al cargar datos: <?php echo $e->getMessage(); ?></div>
                                    <?php } ?>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Guardar
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
                                <p><strong>Grado/Nivel:</strong> Corresponde al año académico (1er Grado, 2do Grado, etc.)</p>
                                <p><strong>Sección:</strong> Letra que identifica el grupo (A, B, C, etc.)</p>
                                <p><strong>Capacidad:</strong> Número máximo de estudiantes que puede tener la sección</p>
                                <div class="alert alert-warning">
                                    <small><i class="icon fas fa-exclamation-triangle"></i> No se puede crear una combinación de grado y sección que ya existe.</small>
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