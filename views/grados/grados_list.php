<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

$database = new Conexion();
$db = $database->conectar();
$grado = new Grado($db);

// Debug: Mostrar período activo (solo comentario, no consume datos)
$periodoActivo = $grado->getPeriodoActivo();

// Obtener todos los grados y guardarlos en un array inmediatamente
$datosGrados = [];
try {
    $stmt = $grado->listarGradosConAlumnos();

    // Guardar todos los resultados en un array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $datosGrados[] = $row;
    }

    // Debug: Contar resultados (comentario HTML)
    echo "<!-- DEBUG: Período Activo ID = $periodoActivo -->";
    echo "<!-- DEBUG: Total de grados encontrados = " . count($datosGrados) . " -->";
} catch (Exception $e) {
    $error = $e->getMessage();
    echo "<!-- DEBUG: Error = $error -->";
}

include_once("/xampp/htdocs/final/layout/layaout1.php");
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestión de Grados, Años y Secciones</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Configuraciones</li>
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

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                    Error al cargar los grados: <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Listado de Grados, Años y Secciones</h3>
                            <div class="card-tools">
                                <a href="http://localhost/final/admin/configuraciones/index.php" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                                <a href="grado_nuevo.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Crear
                                </a>
                                <a href="grados_pdf.php" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fas fa-print"></i> Imprimir PDF General
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            if (!empty($datosGrados)) {
                                echo '<table id="tablaGrados" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Grado/Año</th>
                                                <th>Sección</th>
                                                <th>Capacidad</th>
                                                <th>Alumnos Registrados</th>
                                                <th>Disponibilidad</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

                                $totalCapacidad = 0;
                                $totalAlumnos = 0;

                                foreach ($datosGrados as $row) {
                                    $porcentaje = $row['capacidad'] > 0 ? ($row['total_alumnos'] / $row['capacidad']) * 100 : 0;
                                    $clase_progress = $porcentaje >= 90 ? 'bg-danger' : ($porcentaje >= 70 ? 'bg-warning' : 'bg-success');
                                    $cuposDisponibles = $row['capacidad'] - $row['total_alumnos'];

                                    $totalCapacidad += $row['capacidad'];
                                    $totalAlumnos += $row['total_alumnos'];

                                    // Obtener el estado actual del grado
                                    $estado_grado = $grado->obtenerEstadoGrado($row['id_nivel_seccion']);
                                    $estado_texto = $estado_grado ? 'Activo' : 'Inactivo';
                                    $estado_clase = $estado_grado ? 'success' : 'danger';
                                    $estado_icono = $estado_grado ? 'check' : 'times';

                                    echo "<tr>";
                                    echo "<td></td>"; // DataTables se encargará de la numeración
                                    echo "<td>" . htmlspecialchars($row['nombre_grado']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['seccion']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['capacidad']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['total_alumnos']) . "</td>";
                                    echo "<td>
                                            <div class='progress progress-sm'>
                                                <div class='progress-bar {$clase_progress}' style='width: {$porcentaje}%'></div>
                                            </div>
                                            <small>" . htmlspecialchars($cuposDisponibles) . " cupos disponibles (" . number_format($porcentaje, 1) . "%)</small>
                                        </td>";
                                    echo "<td>
                                            <span class='badge badge-{$estado_clase}'>
                                                <i class='fas fa-{$estado_icono}'></i> {$estado_texto}
                                            </span>
                                        </td>";
                                    echo "<td>
                                            <div class='btn-group'>";

                                    // Botón para ver estudiantes
                                    echo "<a href='estudiantes_por_grado.php?id_nivel_seccion=" . urlencode($row['id_nivel_seccion']) . "' 
                                                class='btn btn-info btn-sm' title='Ver Estudiantes'>
                                                <i class='fas fa-users'></i>
                                            </a>";

                                    // Botón para editar
                                    echo "<a href='grado_editar.php?id=" . urlencode($row['id_nivel_seccion']) . "' 
                                                class='btn btn-warning btn-sm' title='Editar'>
                                                <i class='fas fa-edit'></i>
                                            </a>";

                                    // Botón para habilitar/inhabilitar
                                    if ($estado_grado) {
                                        echo "<button type='button' class='btn btn-danger btn-sm' 
                                                    title='Inhabilitar Grado' 
                                                    onclick='confirmarCambioEstado(" . htmlspecialchars($row['id_nivel_seccion']) . ", false)'>
                                                    <i class='fas fa-ban'></i>
                                                </button>";
                                    } else {
                                        echo "<button type='button' class='btn btn-success btn-sm' 
                                                    title='Habilitar Grado' 
                                                    onclick='confirmarCambioEstado(" . htmlspecialchars($row['id_nivel_seccion']) . ", true)'>
                                                    <i class='fas fa-check'></i>
                                                </button>";
                                    }

                                    echo "</div>
                                        </td>";
                                    echo "</tr>";
                                }

                                echo '</tbody>';
                                echo '<tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right"><strong>TOTALES:</strong></th>
                                            <th><strong>' . $totalCapacidad . '</strong></th>
                                            <th><strong>' . $totalAlumnos . '</strong></th>
                                            <th colspan="3">
                                                <strong>' . ($totalCapacidad - $totalAlumnos) . ' cupos disponibles totales</strong>
                                            </th>
                                        </tr>
                                    </tfoot>';
                                echo '</table>';
                            } else {
                                echo "<div class='alert alert-info'>No hay grados/secciones registrados en el sistema.</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>

<!-- Scripts -->
<script src="/final/public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function() {
        // Inicialización de DataTables
        var table = $('#tablaGrados').DataTable({
            "responsive": true,
            "autoWidth": false,
            "columnDefs": [{
                    "targets": 0, // La columna #
                    "orderable": false,
                    "searchable": false
                },
                {
                    "orderable": false,
                    "targets": [7] // Columna Acciones
                }
            ],
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
                [1, "asc"],
                [2, "asc"]
            ],
            // Numeración automática
            "drawCallback": function(settings) {
                var api = this.api();
                var startIndex = api.page.info().start;

                api.column(0, {
                    page: 'current'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            }
        });
    });

    function confirmarCambioEstado(id, habilitar) {
        var accion = habilitar ? 'habilitar' : 'inhabilitar';
        var mensaje = habilitar ?
            '¿Está seguro de que desea habilitar este grado/sección?' :
            '¿Está seguro de que desea inhabilitar este grado/sección?\n\nNota: No se podrán realizar nuevas inscripciones en grados inhabilitados.';

        if (window.confirm(mensaje)) {
            window.location.href = 'grado_cambiar_estado.php?id=' + id + '&accion=' + accion;
        }
    }
</script>