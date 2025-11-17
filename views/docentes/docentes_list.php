<?php
session_start();
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
    <link rel="stylesheet" href="../../public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../public/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
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
                        <h1>Gestión de Docentes</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
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
                                <table id="tablaDocentes" class="table table-bordered table-striped">
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
                                    <tbody>
                                        <?php
                                        include_once '../../conexion.php';
                                        include_once '../../models/Docente.php';

                                        $database = new Conexion();
                                        $db = $database->conectar();
                                        
                                        if ($db) {
                                            $docente = new Docente($db);
                                            $stmt = $docente->listarDocentes();
                                            
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
                                                            <a href='docente_editar.php?id={$row['id_docente']}' class='btn btn-warning btn-sm' title='Editar'>
                                                                <i class='fas fa-edit'></i>
                                                            </a>
                                                            <a href='docente_ver.php?id={$row['id_docente']}' class='btn btn-info btn-sm' title='Ver'>
                                                                <i class='fas fa-eye'></i>
                                                            </a>
                                                            <button type='button' class='btn btn-danger btn-sm' title='Eliminar' onclick='confirmarEliminacion({$row['id_docente']})'>
                                                                <i class='fas fa-trash'></i>
                                                            </button>
                                                        </div>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>Error de conexión a la base de datos</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
    <?php include '../../includes/footer.php'; ?>

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../../public/dist/js/adminlte.min.js"></script>

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

function confirmarEliminacion(id) {
    if (confirm('¿Está seguro de que desea eliminar este docente?')) {
        window.location.href = 'docente_eliminar.php?id=' + id;
    }
}
</script>
</body>
</html>