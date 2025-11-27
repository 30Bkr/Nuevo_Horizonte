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
        
        // Validar que no exista el nivel
        $query_check = "SELECT id_nivel FROM niveles WHERE (num_nivel = ? OR nom_nivel = ?) AND estatus = 1";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->execute([$num_nivel, $nom_nivel]);
        
        if ($stmt_check->rowCount() > 0) {
            $_SESSION['error'] = "Ya existe un nivel con ese número o nombre.";
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
                $_SESSION['success'] = "Sección creada exitosamente.";
                header("Location: grado_nuevo.php");
                exit();
            } else {
                $_SESSION['error'] = "No se pudo crear la sección.";
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo Grado/Año - Nuevo Horizonte</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
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
                <a href="grados_list.php" class="nav-link">Grados/Años</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link active">Nuevo Grado/Año</a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
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
                        <a href="docentes_list.php" class="nav-link">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>Docentes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="grados_list.php" class="nav-link active">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>Grados/Años</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

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
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="grados_list.php">Grados/Años</a></li>
                            <li class="breadcrumb-item active">Nuevo</li>
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
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-8">
                        <!-- Formulario Principal para Crear Grado/Sección -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Registrar Nuevo Grado/Año/Sección</h3>
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
                                        <i class="fas fa-save"></i> Guardar Grado/Año/Sección
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
                                    <small><i class="icon fas fa-info-circle"></i> Solo se muestran grados/años y secciones activos en las listas.</small>
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

<!-- Modal para Nuevo Grado/Año (MODIFICADO) -->
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
                        <label for="tipo_nivel">Nivel:</label>
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
                        <i class="fas fa-save"></i> Crear Grado/Año
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Nueva Sección -->
<div class="modal fade" id="modalNuevaSeccion" tabindex="-1" role="dialog" aria-labelledby="modalNuevaSeccionLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevaSeccionLabel">Crear Nueva Sección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="grado_nuevo.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nuevo_nom_seccion">Nombre de la Sección:</label>
                        <input type="text" class="form-control" id="nuevo_nom_seccion" name="nuevo_nom_seccion" 
                               required placeholder="Ej: C" maxlength="5">
                        <small class="form-text text-muted">Letra que identifica la sección (A, B, C, etc.)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="crear_seccion" class="btn btn-primary">
                        <i class="fas fa-save"></i> Crear Sección
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/final/public/dist/js/adminlte.min.js"></script>

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

// Validar formulario del modal antes de enviar
document.getElementById('formNuevoNivel').addEventListener('submit', function(e) {
    var tipoNivel = document.getElementById('tipo_nivel').value;
    var numNivel = document.getElementById('nuevo_num_nivel').value;
    
    if (!tipoNivel || !numNivel) {
        e.preventDefault();
        alert('Por favor, complete todos los campos del Grado/Año.');
        return false;
    }
});

// Limpiar modal cuando se cierre
$('#modalNuevoNivel').on('hidden.bs.modal', function () {
    document.getElementById('formNuevoNivel').reset();
    document.getElementById('nombre_grado_auto').value = '';
    document.getElementById('nuevo_nom_nivel').value = '';
    document.getElementById('btnCrearNivel').disabled = true;
});

// Recargar la página después de cerrar modales para actualizar los selects
$('#modalNuevaSeccion').on('hidden.bs.modal', function () {
    location.reload();
});
</script>
</body>
</html>