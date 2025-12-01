<?php
session_start();
include_once("/xampp/htdocs/final/layout/layaout1.php");

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
// CORRECCIÓN: Se corrige el doble cierre de comilla en esta línea.
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';
?>

<div class="content-wrapper">
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

    <section class="content">
        <div class="container-fluid">
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
                        <div class="card-header">
                            <h3 class="card-title">Listado de Estudiantes</h3>
                            <div class="card-tools">
                                </div>
                        </div>
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
                                                            <th>N°</th>
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

                                            // MODIFICACIÓN: Se elimina la lógica de $estudiantes, usort y $contador_base.
                                            // Se itera directamente y DataTables se encargará de la paginación y ordenamiento.
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

                                                // Botón para generar constancia (solo si tiene inscripciones)
                                                // Se ajusta la llamada a generarConstancia para pasar el evento
                                                $boton_constancia = $row['inscripciones_count'] > 0 ?
                                                    '<button type="button" class="btn btn-info btn-sm" title="Generar Constancia" onclick="generarConstancia(event, ' . $row['id_estudiante'] . ')">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>' :
                                                    '<button type="button" class="btn btn-info btn-sm" title="Sin inscripciones" disabled>
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>';

                                                echo "<tr>";
                                                // MODIFICACIÓN: Dejamos la columna # vacía. DataTables la llenará con el número de secuencia.
                                                echo "<td></td>"; 
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
                                                        {$boton_constancia}
                                                        {$boton_estado}
                                                    </div>
                                                </td>";
                                                echo "</tr>";
                                            } // Cierre del while

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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/final/public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
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
            // Se elimina el 'order': [[0, "asc"]] para que la columna # se pueda ordenar correctamente con los datos originales.
            "drawCallback": function(settings) {
                // MODIFICACIÓN: Lógica para la numeración continua a través de las páginas
                var api = this.api();
                var startIndex = api.page.info().start; // Índice de inicio de la página actual

                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    // La numeración continua es: Índice de inicio + índice de la fila en la página + 1
                    cell.innerHTML = startIndex + i + 1;
                });
            }
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
                // CORRECCIÓN: Se usa coma (,) en lugar de punto y coma (;)
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

    // CORRECCIÓN: Se agrega 'event' como argumento
    function generarConstancia(event, id_estudiante) { 
        // Mostrar mensaje de carga
        const boton = event.target;
        const originalHTML = boton.innerHTML;
        boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        boton.disabled = true;

        // Obtener la última inscripción del estudiante
        $.ajax({
            // CORRECCIÓN: Se usa coma (,) en lugar de punto y coma (;)
            url: 'obtener_ultima_inscripcion.php', 
            type: 'POST',
            data: {
                id_estudiante: id_estudiante
            }, // CORRECCIÓN: Se usa coma (,) en lugar de punto y coma (;)
            // CORRECCIÓN: Se usa coma (,) en lugar de punto y coma (;)
            dataType: 'json', 
            // CORRECCIÓN: Se usa coma (,) en lugar de punto y coma (;)
            success: function(response) {
                // Restaurar botón
                boton.innerHTML = originalHTML;
                boton.disabled = false;

                if (response.success && response.id_inscripcion) {
                    // Abrir la constancia en una nueva pestaña
                    window.open('/final/app/controllers/inscripciones/generar_constancia_estudiante.php?id_inscripcion=' + response.id_inscripcion + '&v=' + Date.now(), '_blank');
                }
            }, 
            error: function(xhr, status, error) {
                // Restaurar botón
                boton.innerHTML = originalHTML;
                boton.disabled = false;
                
                alert('Error al procesar la solicitud: ' + error);
            }
        });
    }
</script>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>