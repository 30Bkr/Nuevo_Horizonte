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
    
    // Procesar actualización
    if ($_POST) {
        $grado->id_nivel = $_POST['id_nivel'];
        $grado->id_seccion = $_POST['id_seccion'];
        $grado->capacidad = $_POST['capacidad'];
        
        if ($grado->actualizar()) {
            $_SESSION['success'] = "Grado actualizado exitosamente.";
            header("Location: grados_list.php");
            exit();
        } else {
            $_SESSION['error'] = "No se pudo actualizar el grado.";
        }
    }
    
    // Cargar datos para selects
    $niveles = $grado->obtenerNiveles();
    $secciones = $grado->obtenerSecciones();
    
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

    <!-- Navbar y Sidebar (igual que en grado_nuevo.php) -->
    <!-- ... código del navbar y sidebar similar a grado_nuevo.php ... -->

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
                                    <div class="form-group">
                                        <label for="id_nivel">Grado/Nivel:</label>
                                        <select class="form-control" id="id_nivel" name="id_nivel" required>
                                            <option value="">Seleccione un grado</option>
                                            <?php 
                                            $niveles->execute();
                                            while ($nivel = $niveles->fetch(PDO::FETCH_ASSOC)): 
                                                $selected = ($nivel['id_nivel'] == $grado->id_nivel) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $nivel['id_nivel']; ?>" <?php echo $selected; ?>>
                                                    <?php echo $nivel['nom_nivel']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="id_seccion">Sección:</label>
                                        <select class="form-control" id="id_seccion" name="id_seccion" required>
                                            <option value="">Seleccione una sección</option>
                                            <?php 
                                            $secciones->execute();
                                            while ($seccion = $secciones->fetch(PDO::FETCH_ASSOC)): 
                                                $selected = ($seccion['id_seccion'] == $grado->id_seccion) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $seccion['id_seccion']; ?>" <?php echo $selected; ?>>
                                                    <?php echo $seccion['nom_seccion']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="capacidad">Capacidad:</label>
                                        <input type="number" class="form-control" id="capacidad" name="capacidad" 
                                               min="1" max="50" value="<?php echo $grado->capacidad; ?>" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                    <a href="grados_list.php" class="btn btn-default">
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