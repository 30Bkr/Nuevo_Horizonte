<?php
session_start();


include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

// Procesar formulario principal
if ($_POST && isset($_POST['guardar_grado'])) {
    try {
        $database = new Conexion();
        $db = $database->conectar();

        $grado = new Grado($db);

        $grado->id_nivel = $_POST['id_nivel'];
        $grado->id_seccion = $_POST['id_seccion'];
        $grado->capacidad = $_POST['capacidad'];

        // Verificar si ya existe la combinación
        if ($grado->existeCombinacion($grado->id_nivel, $grado->id_seccion)) {
            $_SESSION['error'] = "Ya existe un grado/año con esta combinación de nivel y sección.";
        } else {
            if ($grado->crear()) {
                $_SESSION['success'] = "Grado/año/sección creado exitosamente.";
                header("Location: grados_list.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo crear el grado/año/sección.";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Procesar creación de nuevo nivel
if ($_POST && isset($_POST['crear_nivel'])) {
    try {
        $database = new Conexion();
        $db = $database->conectar();

        $num_nivel = $_POST['nuevo_num_nivel'];
        $nom_nivel = $_POST['nuevo_nom_nivel'];

        // Validar que no exista el nivel con el mismo nombre
        $query_check = "SELECT id_nivel FROM niveles WHERE nom_nivel = ? AND estatus = 1";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->execute([$nom_nivel]);

        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = "Ya existe un nivel con ese nombre exacto.";
        } else {
            $query = "INSERT INTO niveles (num_nivel, nom_nivel) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            if ($stmt->execute([$num_nivel, $nom_nivel])) {
                $_SESSION['success'] = "Grado/Año creado exitosamente.";
                header("Location: grado_nuevo.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo crear el Grado/Año.";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al crear Grado/Año: " . $e->getMessage();
    }
}

// Procesar creación de nueva sección
if ($_POST && isset($_POST['crear_seccion'])) {
    try {
        $database = new Conexion();
        $db = $database->conectar();

        $nom_seccion = $_POST['nuevo_nom_seccion'];

        // VALIDACIÓN ADICIONAL EN EL SERVIDOR
        if (!preg_match('/^[A-Z]$/', $nom_seccion)) {
            $_SESSION['error'] = "La sección debe ser una sola letra mayúscula (A-Z).";
        } else {
            // Validar que no exista la sección
            $query_check = "SELECT id_seccion FROM secciones WHERE nom_seccion = ? AND estatus = 1";
            $stmt_check = $db->prepare($query_check);
            $stmt_check->execute([$nom_seccion]);

            if ($stmt_check->rowCount() > 0) {
                $_SESSION['error'] = "Ya existe una sección con ese nombre.";
            } else {
                $query = "INSERT INTO secciones (nom_seccion) VALUES (?)";
                $stmt = $db->prepare($query);
                if ($stmt->execute([$nom_seccion])) {
                    $_SESSION['success'] = "Sección '" . $nom_seccion . "' creada exitosamente.";
                    header("Location: grado_nuevo.php");
                    exit();
                } else {
                    $_SESSION['error'] = "No se pudo crear la sección.";
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al crear sección: " . $e->getMessage();
    }
}

// Obtener datos para los selects
try {
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);

    // Estos métodos ya están configurados para obtener solo registros activos
    $niveles = $grado->obtenerNiveles();
    $secciones = $grado->obtenerSecciones();
} catch (Exception $e) {
    $_SESSION['error'] = "Error al cargar datos: " . $e->getMessage();
}
include_once("/xampp/htdocs/final/layout/layaout1.php");
?>


<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nuevo Grado/Año/Sección</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="grados_list.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="grados_list.php">Configuraciones</a></li>
                        <li class="breadcrumb-item active">Niveles</li>
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

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <!-- Formulario Principal para Crear Grado/Sección -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Crear Nuevo Grado/Año/Sección</h3>
                        </div>
                        <form method="post" action="grado_nuevo.php" id="formGrado">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="id_nivel">Grado/Año:</label>
                                    <div class="input-group">
                                        <select class="form-control" id="id_nivel" name="id_nivel" required onchange="validarCombinacion()">
                                            <option value="">Seleccione un grado/año</option>
                                            <?php
                                            if (isset($niveles)) {
                                                while ($nivel = $niveles->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                                    <option value="<?php echo $nivel['id_nivel']; ?>">
                                                        <?php echo $nivel['nom_nivel']; ?>
                                                    </option>
                                            <?php
                                                endwhile;
                                            }
                                            ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#modalNuevoNivel">
                                                <i class="fas fa-plus"></i> Nuevo
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="id_seccion">Sección:</label>
                                    <div class="input-group">
                                        <select class="form-control" id="id_seccion" name="id_seccion" required onchange="validarCombinacion()">
                                            <option value="">Seleccione una sección</option>
                                            <?php
                                            if (isset($secciones)) {
                                                while ($seccion = $secciones->fetch(PDO::FETCH_ASSOC)):
                                            ?>
                                                    <option value="<?php echo $seccion['id_seccion']; ?>">
                                                        <?php echo $seccion['nom_seccion']; ?>
                                                    </option>
                                            <?php
                                                endwhile;
                                            }
                                            ?>
                                        </select>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#modalNuevaSeccion">
                                                <i class="fas fa-plus"></i> Nueva
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="capacidad">Capacidad:</label>
                                    <input type="number" class="form-control" id="capacidad" name="capacidad"
                                        min="1" max="50" required placeholder="Ej: 25">
                                    <small class="form-text text-muted">Número máximo de estudiantes permitidos</small>
                                </div>

                                <!-- Mensaje de validación en tiempo real -->
                                <div id="mensajeValidacion" class="alert" style="display: none;">
                                    <i class="icon fas fa-info-circle"></i> <span id="textoMensaje"></span>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="guardar_grado" class="btn btn-primary" id="btnGuardar">
                                    <i class="fas fa-save"></i> Guardar
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
                            <p><strong>Grado/Año:</strong> Corresponde al año académico (1er Grado, 2do Grado, etc.)</p>
                            <p><strong>Sección:</strong> Letra que identifica el grupo (A, B, C, etc.)</p>
                            <p><strong>Capacidad:</strong> Número máximo de estudiantes que puede tener la sección</p>
                            <div class="alert alert-warning">
                                <small><i class="icon fas fa-exclamation-triangle"></i> No se puede crear una combinación de grado/año y sección que ya existe.</small>
                            </div>
                            <div class="alert alert-info">
                                <small><i class="icon fas fa-info-circle"></i> Ahora puedes crear tanto "Primer Grado" como "Primer Año" sin conflictos.</small>
                            </div>
                        </div>
                    </div>
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

<!-- Modal para Nuevo Grado/Año -->
<div class="modal fade" id="modalNuevoNivel" tabindex="-1" role="dialog" aria-labelledby="modalNuevoNivelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoNivelLabel">Crear Nuevo Grado/Año</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="grado_nuevo.php" id="formNuevoNivel">
                <div class="modal-body">
                    <!-- NUEVO FLUJO: Selección de Grado/Año -->
                    <div class="form-group">
                        <label for="tipo_nivel">Grado/Año:</label>
                        <select class="form-control" id="tipo_nivel" name="tipo_nivel" required onchange="actualizarNombreGradoModal()">
                            <option value="">Seleccione el tipo</option>
                            <option value="grado">Grado</option>
                            <option value="año">Año</option>
                        </select>
                        <small class="form-text text-muted">Seleccione si es Grado o Año</small>
                    </div>

                    <!-- NUEVO: Selección de número -->
                    <div class="form-group">
                        <label for="nuevo_num_nivel">Número:</label>
                        <select class="form-control" id="nuevo_num_nivel" name="nuevo_num_nivel" required onchange="actualizarNombreGradoModal()">
                            <option value="">Seleccione el número</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                        <small class="form-text text-muted">Número ordinal (1, 2, 3, etc.)</small>
                    </div>

                    <!-- NUEVO: Campo de nombre automático -->
                    <div class="form-group">
                        <label for="nombre_grado_auto">Nombre del Grado/Año (automático):</label>
                        <input type="text" class="form-control" id="nombre_grado_auto" name="nombre_grado_auto" readonly>
                        <small class="form-text text-muted">Se generará automáticamente según su selección</small>
                    </div>

                    <!-- Campo oculto para enviar el nombre final -->
                    <input type="hidden" id="nuevo_nom_nivel" name="nuevo_nom_nivel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="crear_nivel" class="btn btn-primary" id="btnCrearNivel" disabled>
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Nueva Sección (MODIFICADO) -->
<div class="modal fade" id="modalNuevaSeccion" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSeccionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaSeccionLabel">Crear Nueva Sección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="grado_nuevo.php" id="formNuevaSeccion">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nuevo_nom_seccion">Nombre de la Sección:</label>
                        <input type="text" class="form-control" id="nuevo_nom_seccion" name="nuevo_nom_seccion"
                            required placeholder="Ej: C" maxlength="1"
                            oninput="validarSeccion(this)" onkeypress="return soloLetras(event)">
                        <small class="form-text text-muted">Ingrese una sola letra (A-Z)</small>
                        <div id="mensajeErrorSeccion" class="invalid-feedback" style="display: none;">
                            Por favor, ingrese solo una letra (A-Z)
                        </div>
                        <div id="mensajeOkSeccion" class="valid-feedback" style="display: none;">
                            ✓ Sección válida
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="crear_seccion" class="btn btn-primary" id="btnCrearSeccion" disabled>
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Función para validar combinación en tiempo real
    function validarCombinacion() {
        var idNivel = document.getElementById('id_nivel').value;
        var idSeccion = document.getElementById('id_seccion').value;
        var mensajeDiv = document.getElementById('mensajeValidacion');
        var textoMensaje = document.getElementById('textoMensaje');
        var btnGuardar = document.getElementById('btnGuardar');

        // Ocultar mensaje si no hay selección completa
        if (!idNivel || !idSeccion) {
            mensajeDiv.style.display = 'none';
            btnGuardar.disabled = false;
            return;
        }

        // Realizar petición AJAX para verificar combinación
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'validar_grado_seccion.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var respuesta = JSON.parse(xhr.responseText);

                if (respuesta.existe) {
                    // Combinación ya existe
                    mensajeDiv.className = 'alert alert-danger';
                    textoMensaje.innerHTML = '<strong>¡Combinación existente!</strong> Ya existe un ' +
                        respuesta.nombre_grado + ' - Sección ' + respuesta.seccion +
                        ' en el sistema.';
                    mensajeDiv.style.display = 'block';
                    btnGuardar.disabled = true;
                } else {
                    // Combinación disponible
                    mensajeDiv.className = 'alert alert-success';
                    textoMensaje.innerHTML = '<strong>¡Combinación disponible!</strong> Puede crear ' +
                        respuesta.nombre_grado + ' - Sección ' + respuesta.seccion + '.';
                    mensajeDiv.style.display = 'block';
                    btnGuardar.disabled = false;
                }
            }
        };

        xhr.send('id_nivel=' + idNivel + '&id_seccion=' + idSeccion);
    }

    // Función para actualizar el nombre del grado automáticamente en el modal
    function actualizarNombreGradoModal() {
        var tipoSelect = document.getElementById('tipo_nivel');
        var numeroSelect = document.getElementById('nuevo_num_nivel');
        var nombreInput = document.getElementById('nombre_grado_auto');
        var hiddenInput = document.getElementById('nuevo_nom_nivel');
        var btnCrear = document.getElementById('btnCrearNivel');

        var tipoValor = tipoSelect.value;
        var numeroValor = numeroSelect.value;

        // Generar nombre del grado basado en el tipo y número seleccionado
        var nombresGrados = {
            '1': 'Primer',
            '2': 'Segundo',
            '3': 'Tercer',
            '4': 'Cuarto',
            '5': 'Quinto',
            '6': 'Sexto'
        };

        if (tipoValor && numeroValor) {
            var nombreBase = nombresGrados[numeroValor] || numeroValor;
            var nombreCompleto = nombreBase + ' ' + (tipoValor === 'grado' ? 'Grado' : 'Año');

            nombreInput.value = nombreCompleto;
            hiddenInput.value = nombreCompleto;
            btnCrear.disabled = false;
        } else {
            nombreInput.value = '';
            hiddenInput.value = '';
            btnCrear.disabled = true;
        }
    }

    // Función para validar que solo se ingresen letras
    function soloLetras(event) {
        var charCode = event.keyCode || event.which;
        var charStr = String.fromCharCode(charCode);

        // Permitir solo letras (A-Z, a-z) y algunas teclas de control
        if (!/^[A-Za-z]$/.test(charStr) &&
            charCode !== 8 && // Backspace
            charCode !== 9 && // Tab
            charCode !== 13 && // Enter
            charCode !== 46) { // Delete
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Función para validar y formatear la sección
    function validarSeccion(input) {
        var valor = input.value;
        var mensajeError = document.getElementById('mensajeErrorSeccion');
        var mensajeOk = document.getElementById('mensajeOkSeccion');
        var btnCrear = document.getElementById('btnCrearSeccion');

        // Limpiar el valor - quitar espacios y caracteres no deseados
        valor = valor.replace(/[^A-Za-z]/g, '');

        // Tomar solo el primer carácter si se ingresó más de uno
        if (valor.length > 1) {
            valor = valor.charAt(0);
        }

        // Convertir a mayúsculas
        valor = valor.toUpperCase();

        // Actualizar el valor del input
        input.value = valor;

        // Validar
        if (valor.length === 1 && /^[A-Z]$/.test(valor)) {
            // Sección válida
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            mensajeError.style.display = 'none';
            mensajeOk.style.display = 'block';
            btnCrear.disabled = false;
        } else if (valor.length === 0) {
            // Campo vacío
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
            mensajeError.style.display = 'none';
            mensajeOk.style.display = 'none';
            btnCrear.disabled = true;
        } else {
            // Sección inválida
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
            mensajeError.style.display = 'block';
            mensajeOk.style.display = 'none';
            btnCrear.disabled = true;
        }
    }

    // Validar al cargar la página si ya hay selecciones
    document.addEventListener('DOMContentLoaded', function() {
        validarCombinacion();
    });

    // Validar antes de enviar el formulario principal
    document.getElementById('formGrado').addEventListener('submit', function(e) {
        var idNivel = document.getElementById('id_nivel').value;
        var idSeccion = document.getElementById('id_seccion').value;

        if (!idNivel || !idSeccion) {
            e.preventDefault();
            alert('Por favor, seleccione tanto el grado/año como la sección.');
            return false;
        }
    });

    // Validar formulario del modal de grado/año antes de enviar
    document.getElementById('formNuevoNivel').addEventListener('submit', function(e) {
        var tipoNivel = document.getElementById('tipo_nivel').value;
        var numNivel = document.getElementById('nuevo_num_nivel').value;

        if (!tipoNivel || !numNivel) {
            e.preventDefault();
            alert('Por favor, complete todos los campos del Grado/Año.');
            return false;
        }
    });

    // Validar formulario de sección antes de enviar
    document.getElementById('formNuevaSeccion').addEventListener('submit', function(e) {
        var seccionInput = document.getElementById('nuevo_nom_seccion');
        var valor = seccionInput.value;

        if (!/^[A-Z]$/.test(valor)) {
            e.preventDefault();
            alert('Por favor, ingrese una sección válida (una sola letra de A-Z).');
            seccionInput.focus();
            return false;
        }
    });

    // Limpiar modal de grado/año cuando se cierre
    $('#modalNuevoNivel').on('hidden.bs.modal', function() {
        document.getElementById('formNuevoNivel').reset();
        document.getElementById('nombre_grado_auto').value = '';
        document.getElementById('nuevo_nom_nivel').value = '';
        document.getElementById('btnCrearNivel').disabled = true;
    });

    // Limpiar modal de sección cuando se cierre
    $('#modalNuevaSeccion').on('hidden.bs.modal', function() {
        document.getElementById('formNuevaSeccion').reset();
        document.getElementById('nuevo_nom_seccion').classList.remove('is-invalid', 'is-valid');
        document.getElementById('mensajeErrorSeccion').style.display = 'none';
        document.getElementById('mensajeOkSeccion').style.display = 'none';
        document.getElementById('btnCrearSeccion').disabled = true;
    });

    // Recargar la página después de cerrar modales para actualizar los selects
    $('#modalNuevoNivel, #modalNuevaSeccion').on('hidden.bs.modal', function() {
        location.reload();
    });
</script>
<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>