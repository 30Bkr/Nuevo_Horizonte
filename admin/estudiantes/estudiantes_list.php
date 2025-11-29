<?php
session_start();
include_once("/xampp/htdocs/final/layout/layaout1.php");


// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';
?>



<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestión de Estudiantes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Estudiantes</li>
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
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- -->
                        <div class="card-header">
                            <h3 class="card-title">Listado de Estudiantes</h3>
                            <div class="card-tools">
                                <!--
                                        <a href="estudiante_nuevo.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus"></i> Nuevo Estudiante
                                        </a>
                                         -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <?php
                            try {
                                $database = new Conexion();
                                $db = $database->conectar();

                                if ($db) {
                                    $controller = new EstudianteController($db);
                                    $stmt = $controller->listar();

                                    if ($stmt) {
                                        if ($stmt->rowCount() > 0) {
                                            echo '<table id="tablaEstudiantes" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Cédula</th>
                                                                <th>Nombre Completo</th>
                                                                <th>Teléfono</th>
                                                                <th>Correo</th>
                                                                <th>Fecha Nac.</th>
                                                                <th>Sexo</th>
                                                                <th>Inscripciones</th>
                                                                <th>Estado</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $nombreCompleto = $row['primer_nombre'] . ' ' .
                                                    ($row['segundo_nombre'] ? $row['segundo_nombre'] . ' ' : '') .
                                                    $row['primer_apellido'] . ' ' .
                                                    ($row['segundo_apellido'] ? $row['segundo_apellido'] : '');

                                                $estado_badge = $row['estatus'] == 1 ?
                                                    '<span class="badge badge-success">Activo</span>' :
                                                    '<span class="badge badge-danger">Inactivo</span>';

                                                $inscripciones_badge = $row['inscripciones_count'] > 0 ?
                                                    '<span class="badge badge-info">' . $row['inscripciones_count'] . '</span>' :
                                                    '<span class="badge badge-secondary">0</span>';

                                                $boton_estado = $row['estatus'] == 1 ?
                                                    '<button type="button" class="btn btn-danger btn-sm" title="Inhabilitar" onclick="cambiarEstado(' . $row['id_estudiante'] . ', 0)">
                                                                <i class="fas fa-ban"></i>
                                                            </button>' :
                                                    '<button type="button" class="btn btn-success btn-sm" title="Habilitar" onclick="cambiarEstado(' . $row['id_estudiante'] . ', 1)">
                                                                <i class="fas fa-check"></i>
                                                            </button>';

                                                echo "<tr>";
                                                echo "<td>{$row['id_estudiante']}</td>";
                                                echo "<td>{$row['cedula']}</td>";
                                                echo "<td>{$nombreCompleto}</td>";
                                                echo "<td>{$row['telefono']}</td>";
                                                echo "<td>{$row['correo']}</td>";
                                                echo "<td>" . date('d/m/Y', strtotime($row['fecha_nac'])) . "</td>";
                                                echo "<td>{$row['sexo']}</td>";
                                                echo "<td>{$inscripciones_badge}</td>";
                                                echo "<td>{$estado_badge}</td>";
                                                echo "<td>
                                                            <div class='btn-group'>
                                                                <a href='estudiante_editar.php?id={$row['id_estudiante']}' class='btn btn-warning btn-sm' title='Editar'>
                                                                    <i class='fas fa-edit'></i>
                                                                </a>
                                                                <a href='estudiante_ver.php?id={$row['id_estudiante']}' class='btn btn-primary btn-sm' title='Ver'>
                                                                    <i class='fas fa-eye'></i>
                                                                </a>
                                                                {$boton_estado}
                                                            </div>
                                                        </td>";
                                                echo "</tr>";
                                            }

                                            echo '</tbody></table>';
                                        } else {
                                            echo "<div class='alert alert-info'>No hay estudiantes registrados en el sistema.</div>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-warning'>No se pudo obtener la lista de estudiantes.</div>";
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
    $(function() {
        $('#tablaEstudiantes').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "decimal": "",
                "emptyTable": "No hay datos disponibles en la tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ registros",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "No se encontraron registros coincidentes",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": activar para ordenar ascendente",
                    "sortDescending": ": activar para ordenar descendente"
                }
            },
            "order": [
                [2, "asc"]
            ]
        });
    });

    function cambiarEstado(id_estudiante, nuevo_estado) {
        const accion = nuevo_estado ? 'habilitar' : 'inhabilitar';

        if (confirm(`¿Está seguro de que desea ${accion} este estudiante?`)) {
            $.ajax({
                url: 'estudiante_cambiar_estado.php',
                type: 'POST',
                data: {
                    id_estudiante: id_estudiante,
                    estado: nuevo_estado
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Error al procesar la solicitud');
                }
            });
        }
    }
</script>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>