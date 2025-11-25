<?php
session_start();
include_once("/xampp/htdocs/final/layout/layaout1.php");

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

$database = new Conexion();
$db = $database->conectar();
$grado = new Grado($db);

// Obtener información del grado/sección
$id_nivel_seccion = $_GET['id_nivel_seccion'] ?? 0;

// Validar que se haya proporcionado un ID válido
if ($id_nivel_seccion == 0) {
    $_SESSION['error'] = "Debe seleccionar un grado/sección válido";
    header("Location: grados_list.php");
    exit();
}

$info_grado = $grado->obtenerGradoPorId($id_nivel_seccion);

if (!$info_grado) {
    $_SESSION['error'] = "Grado/Sección no encontrado";
    header("Location: grados_list.php");
    exit();
}

// Obtener estudiantes del grado/sección (SOLO del período activo)
$estudiantes = $grado->obtenerEstudiantesPorGrado($id_nivel_seccion);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Estudiantes de <?php echo $info_grado['nombre_grado'] . ' - ' . $info_grado['seccion']; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="grados_list.php">Grados</a></li>
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

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Lista de Estudiantes -
                        <?php echo $info_grado['nombre_grado'] . ' - ' . $info_grado['seccion']; ?>
                        (Capacidad: <?php echo $info_grado['capacidad']; ?> estudiantes)
                    </h3>
                    <div class="card-tools">
                        <a href="estudiantes_por_grado_pdf.php?id_nivel_seccion=<?php echo $id_nivel_seccion; ?>"
                            class="btn btn-success btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Imprimir Lista
                        </a>
                        <a href="grados_list.php" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Estudiantes</span>
                                    <span class="info-box-number"><?php echo $estudiantes->rowCount(); ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo $info_grado['capacidad'] > 0 ? ($estudiantes->rowCount() / $info_grado['capacidad']) * 100 : 0; ?>%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <?php echo number_format(($estudiantes->rowCount() / $info_grado['capacidad']) * 100, 1); ?>% de ocupación
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-door-open"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cupos Disponibles</span>
                                    <span class="info-box-number"><?php echo $info_grado['capacidad'] - $estudiantes->rowCount(); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="tablaEstudiantes" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cédula</th>
                                <th>Nombre Completo</th>
                                <th>Sexo</th>
                                <th>Edad</th>
                                <th>Fecha Inscripción</th>
                                <th>Representante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($estudiantes->rowCount() > 0):
                                $contador = 1;
                                while ($estudiante = $estudiantes->fetch(PDO::FETCH_ASSOC)):
                                    $edad = $estudiante['fecha_nac'] ? floor((time() - strtotime($estudiante['fecha_nac'])) / 31556926) : 'N/A';
                                    $nombre_completo_estudiante = htmlspecialchars(
                                        $estudiante['primer_nombre'] . ' ' .
                                            ($estudiante['segundo_nombre'] ? $estudiante['segundo_nombre'] . ' ' : '') .
                                            $estudiante['primer_apellido'] . ' ' .
                                            ($estudiante['segundo_apellido'] ? $estudiante['segundo_apellido'] : '')
                                    );
                                    $nombre_completo_representante = $estudiante['representante_nombre'] ? 
                                        htmlspecialchars($estudiante['representante_nombre']) . 
                                        ($estudiante['parentesco'] ? ' (' . htmlspecialchars($estudiante['parentesco']) . ')' : '') : 
                                        'No asignado';
                            ?>
                                    <tr>
                                        <td><?php echo $contador++; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                                        <td><?php echo $nombre_completo_estudiante; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['sexo']); ?></td>
                                        <td><?php echo $edad; ?> años</td>
                                        <td><?php echo date('d/m/Y', strtotime($estudiante['fecha_inscripcion'])); ?></td>
                                        <td><?php echo $nombre_completo_representante; ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <!-- Botón para ver ficha del estudiante -->
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="verFichaEstudiante('<?php echo $estudiante['cedula']; ?>')"
                                                        title="Ver Ficha del Estudiante">
                                                    <i class="fas fa-user"></i> Estudiante
                                                </button>
                                                
                                                <!-- Botón para ver ficha del representante -->
                                                <?php if ($estudiante['representante_nombre']): ?>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        onclick="verFichaRepresentante('<?php echo $estudiante['cedula']; ?>')"
                                                        title="Ver Ficha del Representante">
                                                    <i class="fas fa-user-tie"></i> Representante
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No hay estudiantes inscritos en este grado/sección</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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

<!-- Modal para Ficha del Estudiante -->
<div class="modal fade" id="modalFichaEstudiante" tabindex="-1" role="dialog" aria-labelledby="modalFichaEstudianteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFichaEstudianteLabel">Ficha del Estudiante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoFichaEstudiante">
                <!-- Contenido cargado por AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Ficha del Representante -->
<div class="modal fade" id="modalFichaRepresentante" tabindex="-1" role="dialog" aria-labelledby="modalFichaRepresentanteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFichaRepresentanteLabel">Ficha del Representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="contenidoFichaRepresentante">
                <!-- Contenido cargado por AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablaEstudiantes').DataTable({
            "responsive": true,
            "autoWidth": false,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Todos"]
            ],
            "pageLength": 10,
            "order": [
                [0, "asc"]
            ],
            "language": {
                "processing": "Procesando...",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron resultados",
                "emptyTable": "No hay datos disponibles en esta tabla",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                "search": "Buscar:",
                "loadingRecords": "Cargando...",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                },
                "aria": {
                    "sortAscending": ": Activar para ordenar la columna ascendente",
                    "sortDescending": ": Activar para ordenar la columna descendente"
                },
                "buttons": {
                    "copy": "Copiar",
                    "colvis": "Columnas visibles"
                }
            },
            "dom": '<"top"lf>rt<"bottom"ip><"clear">'
        });
    });

    // Función para ver ficha del estudiante
    function verFichaEstudiante(cedula) {
        $('#contenidoFichaEstudiante').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Cargando información...</div>');
        $('#modalFichaEstudiante').modal('show');
        
        $.ajax({
            url: 'ficha_estudiante.php',
            type: 'GET',
            data: { cedula: cedula },
            success: function(response) {
                $('#contenidoFichaEstudiante').html(response);
            },
            error: function() {
                $('#contenidoFichaEstudiante').html('<div class="alert alert-danger">Error al cargar la información del estudiante.</div>');
            }
        });
    }

    // Función para ver ficha del representante
    function verFichaRepresentante(cedulaEstudiante) {
        $('#contenidoFichaRepresentante').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Cargando información...</div>');
        $('#modalFichaRepresentante').modal('show');
        
        $.ajax({
            url: 'ficha_representante.php',
            type: 'GET',
            data: { cedula_estudiante: cedulaEstudiante },
            success: function(response) {
                $('#contenidoFichaRepresentante').html(response);
            },
            error: function() {
                $('#contenidoFichaRepresentante').html('<div class="alert alert-danger">Error al cargar la información del representante.</div>');
            }
        });
    }
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>