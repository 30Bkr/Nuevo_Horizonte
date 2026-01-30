<?php
// estudiantes_list.php CORREGIDO - VERSIÓN SIMPLIFICADA

session_start();
require_once '/xampp/htdocs/final/global/check_permissions.php';
PermissionManager::requirePermission();

// Incluir solo lo necesario ANTES del layout
require_once __DIR__ . '/../../app/conexion.php';
require_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';

// Obtener el filtro de estado (activo/inactivo)
$filtro_activo = isset($_GET['filtro']) ? (int)$_GET['filtro'] : 1;

// Obtener datos
$database = new Conexion();
$db = $database->conectar();
$controller = new EstudianteController($db);

try {
    $stmt = $controller->listar($filtro_activo);
    $estudiantes = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Exception $e) {
    $error = $e->getMessage();
    $estudiantes = [];
}

// Incluir layout PRIMERO
include_once("/xampp/htdocs/final/layout/layaout1.php");
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
                        <div class="card-header">
                            <h3 class="card-title">Listado de Estudiantes</h3>
                            <!-- Botones y filtros en línea con el título -->
                            <div class="card-tools" style="position: absolute; right: 20px; top: 15px;">
                                <div class="d-flex align-items-center">
                                    <!-- BOTÓN PARA GENERAR MATRÍCULA -->
                                    <div class="mr-2">
                                        <a href="matricula_estudiantil_pdf.php" target="_blank" class="btn btn-success btn-sm">
                                            <i class="fas fa-file-pdf mr-1"></i> Generar Matrícula
                                        </a>
                                    </div>

                                    <!-- FILTRO DE ACTIVOS/INACTIVOS -->
                                    <div style="width: 180px;">
                                        <select id="filtroEstado" class="form-control form-control-sm" onchange="cambiarFiltro(this.value)">
                                            <option value="1" <?php echo $filtro_activo == 1 ? 'selected' : ''; ?>>Activos</option>
                                            <option value="0" <?php echo $filtro_activo == 0 ? 'selected' : ''; ?>>Inactivos</option>
                                            <option value="2" <?php echo $filtro_activo == 2 ? 'selected' : ''; ?>>Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger">Error: <?php echo htmlspecialchars($error); ?></div>
                            <?php elseif (empty($estudiantes)): ?>
                                <div class='alert alert-info'>No hay estudiantes registrados en el sistema.</div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table id="tablaEstudiantes" class="table table-bordered table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="3%">N°</th>
                                                <th width="8%">Cédula</th>
                                                <th width="20%">Nombre Completo</th>
                                                <th width="8%">Teléfono</th>
                                                <th width="15%">Correo</th>
                                                <th width="8%">Fecha Nac.</th>
                                                <th width="5%">Sexo</th>
                                                <th width="8%">Inscripciones</th>
                                                <th width="8%">Inscripción Actual</th>
                                                <th width="5%">Estado</th>
                                                <th width="12%">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $contador = 1; ?>
                                            <?php foreach ($estudiantes as $row): ?>
                                                <?php
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

                                                // Estado de inscripción en periodo activo
                                                $inscripcion_actual_badge = $row['estado_inscripcion'] == 'Inscrito' ?
                                                    '<span class="badge badge-success">Inscrito</span>' :
                                                    '<span class="badge badge-warning">No Inscrito</span>';

                                                $boton_estado = $row['estatus'] == 1 ?
                                                    '<button type="button" class="btn btn-danger btn-sm" title="Inhabilitar" onclick="cambiarEstado(' . $row['id_estudiante'] . ', 0)">
                                                        <i class="fas fa-ban"></i>
                                                    </button>' :
                                                    '<button type="button" class="btn btn-success btn-sm" title="Habilitar" onclick="cambiarEstado(' . $row['id_estudiante'] . ', 1)">
                                                        <i class="fas fa-check"></i>
                                                    </button>';

                                                $boton_constancia = $row['inscripciones_count'] > 0 ?
                                                    '<button type="button" class="btn btn-info btn-sm" title="Generar Constancia" onclick="generarConstancia(event, ' . $row['id_estudiante'] . ')">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>' :
                                                    '<button type="button" class="btn btn-info btn-sm" title="Sin inscripciones" disabled>
                                                        <i class="fas fa-file-pdf"></i>
                                                    </button>';
                                                ?>
                                                <tr>
                                                    <td><?php echo $contador++; ?></td>
                                                    <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                                                    <td><?php echo htmlspecialchars($nombreCompleto); ?></td>
                                                    <td><?php echo htmlspecialchars($row['telefono']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['correo']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($row['fecha_nac'])); ?></td>
                                                    <td><?php echo htmlspecialchars($row['sexo']); ?></td>
                                                    <td class="text-center"><?php echo $inscripciones_badge; ?></td>
                                                    <td class="text-center"><?php echo $inscripcion_actual_badge; ?></td>
                                                    <td class="text-center"><?php echo $estado_badge; ?></td>
                                                    <td class="text-center">
                                                        <div class='btn-group'>
                                                            <a href='estudiante_editar.php?id=<?php echo $row['id_estudiante']; ?>' class='btn btn-warning btn-sm' title='Editar'>
                                                                <i class='fas fa-edit'></i>
                                                            </a>
                                                            <a href='estudiante_ver.php?id=<?php echo $row['id_estudiante']; ?>' class='btn btn-primary btn-sm' title='Ver'>
                                                                <i class='fas fa-eye'></i>
                                                            </a>
                                                            <?php echo $boton_constancia; ?>
                                                            <?php echo $boton_estado; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
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

<!-- Script para DataTables - VERSIÓN CORREGIDA -->
<script>
    // Esperar a que TODO esté completamente cargado
    window.addEventListener('load', function() {
        // Pequeña pausa para asegurar que todos los scripts estén listos
        setTimeout(function() {
            inicializarDataTable();
        }, 100);
    });

    function inicializarDataTable() {
        // Verificar si jQuery está disponible
        if (typeof jQuery === 'undefined') {
            console.error('jQuery no está disponible');
            return;
        }

        // Verificar si DataTables está disponible
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables no está disponible');
            // Intentar cargar DataTables dinámicamente
            cargarDataTablesDinamicamente();
            return;
        }

        // Inicializar DataTable
        try {
            $('#tablaEstudiantes').DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Todos"]
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
                    [2, "asc"]
                ],
                "drawCallback": function(settings) {
                    // Asegurarse de que los controles sean visibles
                    $('.dataTables_length, .dataTables_filter').show();
                }
            });

            console.log('DataTable inicializado correctamente');
        } catch (error) {
            console.error('Error al inicializar DataTable:', error);
        }
    }

    function cargarDataTablesDinamicamente() {
        // Verificar si ya están cargados
        if (typeof $.fn.DataTable !== 'undefined') {
            inicializarDataTable();
            return;
        }

        console.log('Cargando DataTables dinámicamente...');

        // Cargar DataTables JS
        var script = document.createElement('script');
        script.src = '/final/public/plugins/datatables/jquery.dataTables.min.js';
        script.onload = function() {
            // Cargar DataTables Bootstrap 4 JS
            var script2 = document.createElement('script');
            script2.src = '/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js';
            script2.onload = function() {
                console.log('DataTables cargado dinámicamente');
                inicializarDataTable();
            };
            document.body.appendChild(script2);
        };
        document.body.appendChild(script);
    }

    // Función para cambiar el filtro
    function cambiarFiltro(valor) {
        window.location.href = 'estudiantes_list.php?filtro=' + valor;
    }

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

    function generarConstancia(event, id_estudiante) {
        const boton = event.currentTarget;
        const originalHTML = boton.innerHTML;
        boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        boton.disabled = true;

        $.ajax({
            url: 'obtener_ultima_inscripcion.php',
            type: 'POST',
            data: {
                id_estudiante: id_estudiante
            },
            dataType: 'json',
            success: function(response) {
                boton.innerHTML = originalHTML;
                boton.disabled = false;

                if (response.success && response.id_inscripcion) {
                    window.open('/final/app/controllers/inscripciones/generar_constancia_estudiante.php?id_inscripcion=' + response.id_inscripcion + '&v=' + Date.now(), '_blank');
                }
            },
            error: function(xhr, status, error) {
                boton.innerHTML = originalHTML;
                boton.disabled = false;

                alert('Error al procesar la solicitud: ' + error);
            }
        });
    }
</script>

<!-- CSS adicional para DataTables -->
<style>
    /* Asegurar que los controles de DataTables sean visibles */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: block !important;
        margin: 10px 0;
    }

    /* Asegurar que el buscador sea visible */
    .dataTables_filter {
        float: right !important;
        margin-bottom: 10px !important;
    }

    .dataTables_filter label {
        margin: 0 !important;
    }

    .dataTables_filter input {
        margin-left: 5px !important;
        display: inline-block !important;
    }

    /* Asegurar que la paginación sea visible */
    .dataTables_paginate {
        float: right !important;
        margin-top: 10px !important;
    }

    /* Fix para la tabla responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* Asegurar que la tabla ocupe el 100% */
    #tablaEstudiantes {
        width: 100% !important;
        margin-bottom: 0 !important;
    }

    /* Estilos para botones de acción */
    .btn-group {
        display: flex !important;
        justify-content: center !important;
        flex-wrap: nowrap !important;
    }

    .btn-group .btn {
        margin: 0 2px !important;
        padding: 3px 8px !important;
        font-size: 12px !important;
    }

    /* Posicionamiento del card-tools para que no afecte el título */
    .card-header .card-tools {
        position: absolute !important;
        right: 20px !important;
        top: 12px !important;
    }

    @media (max-width: 768px) {
        .card-header .card-tools {
            position: relative !important;
            right: 0 !important;
            top: 0 !important;
            margin-top: 10px !important;
            float: none !important;
        }

        .card-header .card-tools .d-flex {
            flex-direction: column !important;
        }

        .card-header .card-tools .d-flex>div {
            margin-bottom: 10px !important;
        }

        .card-header .card-tools .d-flex>div:last-child {
            margin-bottom: 0 !important;
        }
    }
</style>

<?php
// Cerrar el layout
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>