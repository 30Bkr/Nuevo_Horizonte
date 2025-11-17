<?php
session_start();

// Incluir archivos desde la carpeta app
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Docentes - Nuevo Horizonte</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/final/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar simplificado -->
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
        </ul>
    </nav>

    <!-- Sidebar simplificado -->
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
                        <a href="docentes_list.php" class="nav-link active">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Docentes</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Gestión de Docentes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item active">Docentes</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Mensajes de alerta -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> Éxito!</h5>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Listado de Docentes</h3>
                                <div class="card-tools">
                                    <a href="docente_nuevo.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Nuevo Docente
                                    </a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <?php
                                try {
                                    $database = new Conexion();
                                    $db = $database->conectar();
                                    
                                    if ($db) {
                                        echo "<p class='text-success'>✓ Conexión a la base de datos exitosa</p>";
                                        
                                        $docente = new Docente($db);
                                        $stmt = $docente->listarDocentes();
                                        
                                        if ($stmt) {
                                            if ($stmt->rowCount() > 0) {
                                                echo '<table id="tablaDocentes" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Cédula</th>
                                                                <th>Nombre Completo</th>
                                                                <th>Profesión</th>
                                                                <th>Teléfono</th>
                                                                <th>Correo</th>
                                                                <th>Usuario</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                                
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                    $nombreCompleto = $row['primer_nombre'] . ' ' . 
                                                                     ($row['segundo_nombre'] ? $row['segundo_nombre'] . ' ' : '') . 
                                                                     $row['primer_apellido'] . ' ' . 
                                                                     ($row['segundo_apellido'] ? $row['segundo_apellido'] : '');
                                                    
                                                    echo "<tr>";
                                                    echo "<td>{$row['id_docente']}</td>";
                                                    echo "<td>{$row['cedula']}</td>";
                                                    echo "<td>{$nombreCompleto}</td>";
                                                    echo "<td>{$row['profesion']}</td>";
                                                    echo "<td>{$row['telefono']}</td>";
                                                    echo "<td>{$row['correo']}</td>";
                                                    echo "<td>{$row['usuario']}</td>";
                                                    echo "<td>
                                                            <div class='btn-group'>
                                                                <button class='btn btn-warning btn-sm' title='Editar'>
                                                                    <i class='fas fa-edit'></i>
                                                                </button>
                                                                <button class='btn btn-info btn-sm' title='Ver'>
                                                                    <i class='fas fa-eye'></i>
                                                                </button>
                                                                <button type='button' class='btn btn-danger btn-sm' title='Eliminar'>
                                                                    <i class='fas fa-trash'></i>
                                                                </button>
                                                            </div>
                                                          </td>";
                                                    echo "</tr>";
                                                }
                                                
                                                echo '</tbody></table>';
                                            } else {
                                                echo "<div class='alert alert-info'>No hay docentes registrados en el sistema.</div>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-warning'>No se pudo obtener la lista de docentes.</div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger'>✗ Error de conexión a la base de datos</div>";
                                    }
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
                                }
                                ?>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong>
        Todos los derechos reservados.
    </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="/final/public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="/final/public/dist/js/adminlte.min.js"></script>

<script>
$(function () {
    $('#tablaDocentes').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plugins/1.10.25/i18n/Spanish.json"
        },
        "order": [[2, "asc"]]
    });
});
</script>
</body>
</html>