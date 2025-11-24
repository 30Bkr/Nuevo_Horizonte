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

    // Obtener información completa del grado para mostrar
    $info_grado = $grado->obtenerGradoPorId($id);

    // Obtener estadísticas del grado (número de estudiantes registrados)
    $estadisticas = $grado->obtenerEstadisticasGrado($id);
    $total_estudiantes = $estadisticas['total_estudiantes'] ?? 0;
    $capacidad_actual = $grado->capacidad;

    // Procesar actualización - SOLO capacidad es editable
    if ($_POST) {
        $nueva_capacidad = $_POST['capacidad'];

        // Validar que la nueva capacidad no sea menor a los estudiantes registrados
        if ($nueva_capacidad < $total_estudiantes) {
            $_SESSION['error'] = "La capacidad no puede ser menor a la cantidad de estudiantes ya registrados ($total_estudiantes estudiantes).";
        } else {
            $grado->capacidad = $nueva_capacidad;

            if ($grado->actualizar()) {
                $_SESSION['success'] = "Grado actualizado exitosamente.";
                header("Location: grados_list.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo actualizar el grado.";
            }
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: grados_list.php");
    exit();
}
include_once("/xampp/htdocs/final/layout/layaout1.php");

?>
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
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Editar Grado/Sección</h3>
                        </div>
                        <form method="post" action="grado_editar.php?id=<?php echo $id; ?>" id="formEditarGrado">
                            <div class="card-body">
                                <!-- Información del Grado -->
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info-circle"></i> Información del Grado</h5>
                                    <p><strong>Grado:</strong> <?php echo htmlspecialchars($info_grado['nombre_grado'] ?? ''); ?></p>
                                    <p><strong>Sección:</strong> <?php echo htmlspecialchars($info_grado['seccion'] ?? ''); ?></p>
                                    <p><strong>Estudiantes Registrados:</strong> <?php echo $total_estudiantes; ?> estudiantes</p>
                                    <p><strong>Capacidad Actual:</strong> <?php echo $capacidad_actual; ?> estudiantes</p>
                                </div>

                                <!-- Grado (solo lectura) -->
                                <div class="form-group">
                                    <label for="grado_display">Grado/Nivel:</label>
                                    <input type="text" class="form-control" id="grado_display"
                                        value="<?php echo htmlspecialchars($info_grado['nombre_grado'] ?? ''); ?>"
                                        readonly style="background-color: #f8f9fa;">
                                    <small class="form-text text-muted">El grado no se puede modificar</small>
                                </div>

                                <!-- Sección (solo lectura) -->
                                <div class="form-group">
                                    <label for="seccion_display">Sección:</label>
                                    <input type="text" class="form-control" id="seccion_display"
                                        value="<?php echo htmlspecialchars($info_grado['seccion'] ?? ''); ?>"
                                        readonly style="background-color: #f8f9fa;">
                                    <small class="form-text text-muted">La sección no se puede modificar</small>
                                </div>

                                <!-- Capacidad (editable) -->
                                <div class="form-group">
                                    <label for="capacidad">Capacidad:</label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad"
                                        min="<?php echo $total_estudiantes; ?>" max="50"
                                        value="<?php echo $grado->capacidad; ?>" required
                                        onchange="validarCapacidad()">
                                    <small class="form-text text-muted">
                                        Número máximo de estudiantes permitidos.
                                        <strong>Mínimo permitido: <?php echo $total_estudiantes; ?> estudiantes</strong>
                                        (actualmente registrados)
                                    </small>
                                </div>

                                <!-- Mensaje de validación en tiempo real -->
                                <div id="mensajeValidacion" class="alert alert-warning" style="display: none;">
                                    <i class="icon fas fa-exclamation-triangle"></i>
                                    <span id="textoMensaje"></span>
                                </div>

                                <!-- Campos ocultos para mantener la integridad de los datos -->
                                <input type="hidden" name="id_nivel" value="<?php echo $grado->id_nivel; ?>">
                                <input type="hidden" name="id_seccion" value="<?php echo $grado->id_seccion; ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-warning" id="btnActualizar">
                                    <i class="fas fa-save"></i> Actualizar Capacidad
                                </button>
                                <a href="grados_list.php" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Información</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Grado/Nivel:</strong> No se puede modificar una vez creado.</p>
                            <p><strong>Sección:</strong> No se puede modificar una vez creada.</p>
                            <p><strong>Capacidad:</strong> Es el único campo editable. Representa el número máximo de estudiantes.</p>

                            <div class="alert alert-warning">
                                <h6><i class="icon fas fa-exclamation-triangle"></i> Restricción de Capacidad</h6>
                                <small>
                                    La capacidad no puede ser menor a la cantidad de estudiantes ya registrados
                                    (<?php echo $total_estudiantes; ?> estudiantes).
                                    Si necesita reducir la capacidad, primero debe reubicar o dar de baja estudiantes.
                                </small>
                            </div>

                            <div class="alert alert-success">
                                <h6><i class="icon fas fa-users"></i> Estadísticas Actuales</h6>
                                <small>
                                    <strong>Estudiantes registrados:</strong> <?php echo $total_estudiantes; ?><br>
                                    <strong>Capacidad actual:</strong> <?php echo $capacidad_actual; ?><br>
                                    <strong>Cupos disponibles:</strong> <?php echo $capacidad_actual - $total_estudiantes; ?><br>
                                    <strong>Ocupación:</strong> <?php echo $capacidad_actual > 0 ? number_format(($total_estudiantes / $capacidad_actual) * 100, 1) : 0; ?>%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>

<script>
    // Función para validar la capacidad en tiempo real
    function validarCapacidad() {
        var capacidadInput = document.getElementById('capacidad');
        var capacidad = parseInt(capacidadInput.value);
        var totalEstudiantes = <?php echo $total_estudiantes; ?>;
        var mensajeDiv = document.getElementById('mensajeValidacion');
        var textoMensaje = document.getElementById('textoMensaje');
        var btnActualizar = document.getElementById('btnActualizar');

        if (capacidad < totalEstudiantes) {
            // Capacidad menor a estudiantes registrados
            textoMensaje.textContent = 'La capacidad no puede ser menor a ' + totalEstudiantes + ' (estudiantes ya registrados).';
            mensajeDiv.className = 'alert alert-danger';
            mensajeDiv.style.display = 'block';
            btnActualizar.disabled = true;
            capacidadInput.setCustomValidity('La capacidad no puede ser menor a la cantidad de estudiantes registrados.');
        } else if (capacidad === totalEstudiantes) {
            // Capacidad igual a estudiantes registrados
            textoMensaje.textContent = 'La capacidad será igual al número de estudiantes registrados. No habrá cupos disponibles.';
            mensajeDiv.className = 'alert alert-warning';
            mensajeDiv.style.display = 'block';
            btnActualizar.disabled = false;
            capacidadInput.setCustomValidity('');
        } else {
            // Capacidad válida
            var cuposDisponibles = capacidad - totalEstudiantes;
            textoMensaje.textContent = 'Cupos disponibles: ' + cuposDisponibles + ' estudiantes.';
            mensajeDiv.className = 'alert alert-success';
            mensajeDiv.style.display = 'block';
            btnActualizar.disabled = false;
            capacidadInput.setCustomValidity('');
        }
    }

    // Validar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        validarCapacidad();

        // Validar antes de enviar el formulario
        document.getElementById('formEditarGrado').addEventListener('submit', function(e) {
            var capacidad = parseInt(document.getElementById('capacidad').value);
            var totalEstudiantes = <?php echo $total_estudiantes; ?>;

            if (capacidad < totalEstudiantes) {
                e.preventDefault();
                alert('Error: La capacidad no puede ser menor a ' + totalEstudiantes + ' estudiantes (ya registrados).');
                return false;
            }
        });
    });

    // Validar en tiempo real mientras el usuario escribe
    document.getElementById('capacidad').addEventListener('input', function() {
        validarCapacidad();
    });
</script>