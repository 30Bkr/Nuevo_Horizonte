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

// Detectar desde qué módulo se está accediendo
$volver_a = 'grados_list.php'; // Por defecto (módulo completo)
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'grados_list_solo_lectura') !== false) {
    $volver_a = 'grados_list_solo_lectura.php';
}

$info_grado = $grado->obtenerGradoPorId($id_nivel_seccion);

if (!$info_grado) {
    $_SESSION['error'] = "Grado/Sección no encontrado";
    header("Location: " . $volver_a);
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
                        <li class="breadcrumb-item"><a href="<?php echo $volver_a; ?>">Grados</a></li>
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
                        <a href="<?php echo $volver_a; ?>" class="btn btn-default btn-sm">
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
                                <th>Discapacidades</th>
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
                                    $discapacidades = $estudiante['discapacidades'] ? htmlspecialchars($estudiante['discapacidades']) : 'Ninguna';
                            ?>
                                    <tr>
                                        <td><?php echo $contador++; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['cedula']); ?></td>
                                        <td><?php echo $nombre_completo_estudiante; ?></td>
                                        <td><?php echo htmlspecialchars($estudiante['sexo']); ?></td>
                                        <td><?php echo $edad; ?> años</td>
                                        <td>
                                            <?php if ($estudiante['discapacidades']): ?>
                                                <span class="badge badge-info" title="<?php echo $discapacidades; ?>">
                                                    <i class="fas fa-wheelchair"></i> Discapacidad
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Ninguna</span>
                                            <?php endif; ?>
                                        </td>
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
                                    <td colspan="9" class="text-center">No hay estudiantes inscritos en este grado/año/sección</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
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

<!-- Modal para Subir Foto (NUEVO) -->
<div class="modal fade" id="modalSubirFoto" tabindex="-1" role="dialog" aria-labelledby="modalSubirFotoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSubirFotoLabel">Subir Foto Carnet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSubirFoto" enctype="multipart/form-data">
                    <input type="hidden" id="fotoCedula" name="cedula">
                    <input type="hidden" id="fotoTipo" name="tipo">
                    
                    <div class="form-group">
                        <label for="fotoArchivo">Seleccionar imagen:</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="fotoArchivo" name="foto" accept="image/*" required>
                            <label class="custom-file-label" for="fotoArchivo">Seleccionar archivo...</label>
                        </div>
                        <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB. Recomendado: 150x180px</small>
                    </div>
                    
                    <div class="text-center mt-3">
                        <img id="previewFoto" src="" alt="Vista previa" class="img-thumbnail d-none" style="max-width: 150px; max-height: 180px; object-fit: cover;">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnSubirFoto">
                    <i class="fas fa-upload"></i> Subir Foto
                </button>
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

        // Configurar input file para mostrar nombre del archivo
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Vista previa de la foto - FIXED
        $(document).on('change', '#fotoArchivo', function() {
            var file = this.files[0];
            if (file) {
                // Validar tamaño (máximo 2MB)
                if (file.size > 2097152) {
                    alert('La imagen no debe superar 2MB');
                    $(this).val('');
                    $(this).next('.custom-file-label').html('Seleccionar archivo...');
                    $('#previewFoto').addClass('d-none').attr('src', '');
                    return;
                }
                
                // Validar tipo de archivo
                var allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Formato no permitido. Use JPG, PNG o GIF');
                    $(this).val('');
                    $(this).next('.custom-file-label').html('Seleccionar archivo...');
                    $('#previewFoto').addClass('d-none').attr('src', '');
                    return;
                }
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewFoto').attr('src', e.target.result).removeClass('d-none');
                };
                reader.onerror = function() {
                    alert('Error al leer el archivo');
                    $('#previewFoto').addClass('d-none').attr('src', '');
                };
                reader.readAsDataURL(file);
            } else {
                $('#previewFoto').addClass('d-none').attr('src', '');
            }
        });

        // Subir foto - FIXED
        $('#btnSubirFoto').click(function() {
            var fileInput = $('#fotoArchivo')[0];
            if (!fileInput.files || fileInput.files.length === 0) {
                alert('Por favor, seleccione una imagen');
                return;
            }
            
            var formData = new FormData($('#formSubirFoto')[0]);
            
            // Mostrar indicador de carga
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Subiendo...').prop('disabled', true);
            
            $.ajax({
                url: 'subir_foto.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.success) {
                            // Mostrar mensaje de éxito
                            alert('¡Foto subida correctamente!');
                            $('#modalSubirFoto').modal('hide');
                            
                            // Recargar la ficha correspondiente después de un breve delay
                            setTimeout(function() {
                                if ($('#fotoTipo').val() === 'estudiante') {
                                    verFichaEstudiante($('#fotoCedula').val());
                                } else {
                                    verFichaRepresentante($('#fotoCedula').val());
                                }
                            }, 500);
                            
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e, response);
                        alert('Error al procesar la respuesta del servidor');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('Error de conexión al subir la foto. Verifique la consola para más detalles.');
                },
                complete: function() {
                    // Restaurar botón
                    $('#btnSubirFoto').html('<i class="fas fa-upload"></i> Subir Foto').prop('disabled', false);
                }
            });
        });

        // Limpiar modal al cerrar
        $('#modalSubirFoto').on('hidden.bs.modal', function() {
            $('#formSubirFoto')[0].reset();
            $('.custom-file-label').html('Seleccionar archivo...');
            $('#previewFoto').addClass('d-none').attr('src', '');
            $('#btnSubirFoto').prop('disabled', false);
        });
        
        // También limpiar al abrir
        $('#modalSubirFoto').on('show.bs.modal', function() {
            $('#formSubirFoto')[0].reset();
            $('.custom-file-label').html('Seleccionar archivo...');
            $('#previewFoto').addClass('d-none').attr('src', '');
            $('#btnSubirFoto').prop('disabled', false);
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
            error: function(xhr, status, error) {
                console.error('Error loading student info:', error);
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
            error: function(xhr, status, error) {
                console.error('Error loading representative info:', error);
                $('#contenidoFichaRepresentante').html('<div class="alert alert-danger">Error al cargar la información del representante.</div>');
            }
        });
    }

    // Función para abrir modal de subir foto
    function abrirModalFoto(cedula, tipo) {
        $('#fotoCedula').val(cedula);
        $('#fotoTipo').val(tipo);
        
        // Configurar título del modal según el tipo
        var titulo = (tipo === 'estudiante') ? 'Subir Foto del Estudiante' : 'Subir Foto del Representante';
        $('#modalSubirFotoLabel').text(titulo);
        
        // Resetear formulario y mostrar modal
        $('#formSubirFoto')[0].reset();
        $('.custom-file-label').html('Seleccionar archivo...');
        $('#previewFoto').addClass('d-none').attr('src', '');
        $('#btnSubirFoto').prop('disabled', false);
        $('#modalSubirFoto').modal('show');
        
        // Enfocar el input file después de que el modal se muestre
        setTimeout(function() {
            $('#fotoArchivo').focus();
        }, 500);
    }
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>