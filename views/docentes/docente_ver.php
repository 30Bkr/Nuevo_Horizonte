<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

$database = new Conexion();
$db = $database->conectar();

$docente = new Docente($db);

// Obtener datos del docente a visualizar
$docente_encontrado = false;
if (isset($_GET['id'])) {
    $docente_encontrado = $docente->obtenerPorId($_GET['id']);
}

if (!$docente_encontrado) {
    $_SESSION['error'] = "Docente no encontrado";
    header("Location: docentes_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ver Docente - Nuevo Horizonte</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
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
                <a href="docentes_list.php" class="nav-link">Docentes</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Ver Docente</a>
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
                        <h1>Información del Docente</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                            <li class="breadcrumb-item active">Ver</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <?php echo htmlspecialchars($docente->primer_nombre . ' ' . $docente->primer_apellido); ?>
                                </h3>
                                <div class="card-tools">
                                    <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos Personales</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Cédula:</th>
                                                <td><?php echo htmlspecialchars($docente->cedula); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nombre Completo:</th>
                                                <td>
                                                    <?php 
                                                    echo htmlspecialchars($docente->primer_nombre . ' ' . 
                                                        ($docente->segundo_nombre ? $docente->segundo_nombre . ' ' : '') . 
                                                        $docente->primer_apellido . ' ' . 
                                                        ($docente->segundo_apellido ? $docente->segundo_apellido : ''));
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Fecha Nacimiento:</th>
                                                <td><?php echo $docente->fecha_nac ? date('d/m/Y', strtotime($docente->fecha_nac)) : 'No especificado'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Sexo:</th>
                                                <td><?php echo htmlspecialchars($docente->sexo); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Nacionalidad:</th>
                                                <td><?php echo htmlspecialchars($docente->nacionalidad); ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h5>Información de Contacto</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Teléfono Móvil:</th>
                                                <td><?php echo htmlspecialchars($docente->telefono); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Teléfono Habitación:</th>
                                                <td><?php echo htmlspecialchars($docente->telefono_hab); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Correo Electrónico:</th>
                                                <td><?php echo htmlspecialchars($docente->correo); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Profesión:</th>
                                                <td>
                                                    <?php
                                                    if ($docente->id_profesion) {
                                                        $profesiones = $docente->obtenerProfesiones();
                                                        $profesion_nombre = '';
                                                        while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                            if ($row['id_profesion'] == $docente->id_profesion) {
                                                                $profesion_nombre = $row['profesion'];
                                                                break;
                                                            }
                                                        }
                                                        echo htmlspecialchars($profesion_nombre);
                                                    } else {
                                                        echo 'No especificado';
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h5>Información del Usuario</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Usuario:</th>
                                                <td><?php echo htmlspecialchars($docente->usuario); ?></td>
                                            </tr>
                                            <tr>
                                                <th>Rol:</th>
                                                <td>Docente</td>
                                            </tr>
                                            <tr>
                                                <th>Estado:</th>
                                                <td><span class="badge badge-success">Activo</span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="docentes_list.php" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Volver al Listado
                                </a>
                                <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Editar Información
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong>
    </footer>

</div>

<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/final/public/dist/js/adminlte.min.js"></script>
</body>
</html>