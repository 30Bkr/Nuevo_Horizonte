<?php
session_start();

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/representantes/RepresentanteController.php';

$id_representante = $_GET['id'] ?? '';

if (empty($id_representante) || !is_numeric($id_representante)) {
    $_SESSION['error'] = "ID de representante inválido";
    header("Location: representantes_list.php");
    exit();
}

try {
    $database = new Conexion();
    $db = $database->conectar();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $controller = new RepresentanteController($db);
    
    // Obtener datos del representante
    if (!$controller->obtener($id_representante)) {
        throw new Exception("Representante no encontrado");
    }

    $representante = $controller->representante;

    // Procesar formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($controller->actualizar($id_representante, $_POST)) {
            $_SESSION['success'] = "Representante actualizado exitosamente";
            header("Location: representantes_list.php");
            exit();
        }
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: representantes_list.php");
    exit();
}

// Obtener datos para los selects
try {
    $database = new Conexion();
    $db = $database->conectar();
    if ($db) {
        $controller_data = new RepresentanteController($db);
        
        // Obtener estados
        $estados = $controller_data->obtenerEstados();
        
        // Obtener profesiones
        $profesiones = $controller_data->obtenerProfesiones();
    }
} catch (Exception $e) {
    // Error al cargar datos adicionales, pero continuamos
}

include_once("/xampp/htdocs/final/layout/layaout1.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Representante - Nuevo Horizonte</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/final/public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/final/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    
    <!-- Estilos CSS para campos inválidos -->
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .text-danger {
            color: #dc3545 !important;
            font-weight: bold;
        }
        .form-group label {
            font-weight: 500;
        }
        .campo-obligatorio {
            border-left: 3px solid #dc3545;
            padding-left: 10px;
        }
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 38px;
        }
        .select2-container--bootstrap4 .select2-selection--single {
            height: 38px;
        }
    </style>
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
                    <a href="representantes_list.php" class="nav-link">Representantes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Editar Representante</a>
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
                            <a href="estudiantes_list.php" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Estudiantes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="representantes_list.php" class="nav-link active">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Representantes</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Editar Representante</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="representantes_list.php">Representantes</a></li>
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
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Editar Datos del Representante</h3>
                                </div>
                                <form method="POST" id="formRepresentante">
                                    <div class="card-body">
                                        <!-- Datos Personales del Representante -->
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-user-tie"></i> Información Personal del Representante
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="nacionalidad">Nacionalidad <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Venezolano" <?php echo ($representante->nacionalidad ?? '') == 'Venezolano' ? 'selected' : ''; ?>>Venezolano</option>
                                                        <option value="Extranjero" <?php echo ($representante->nacionalidad ?? '') == 'Extranjero' ? 'selected' : ''; ?>>Extranjero</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="cedula">Cédula <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                                           value="<?php echo htmlspecialchars($representante->cedula ?? ''); ?>" required
                                                           maxlength="20">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="fecha_nac">Fecha de Nacimiento</label>
                                                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                           value="<?php echo $representante->fecha_nac ?? ''; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_nombre">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                                                           value="<?php echo htmlspecialchars($representante->primer_nombre ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_nombre">Segundo Nombre</label>
                                                    <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                           value="<?php echo htmlspecialchars($representante->segundo_nombre ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_apellido">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                           value="<?php echo htmlspecialchars($representante->primer_apellido ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_apellido">Segundo Apellido</label>
                                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                           value="<?php echo htmlspecialchars($representante->segundo_apellido ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="sexo">Sexo <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control" id="sexo" name="sexo" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Masculino" <?php echo ($representante->sexo ?? '') == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                        <option value="Femenino" <?php echo ($representante->sexo ?? '') == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="lugar_nac">Lugar de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                           value="<?php echo htmlspecialchars($representante->lugar_nac ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="telefono">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                                           value="<?php echo htmlspecialchars($representante->telefono ?? ''); ?>" required maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="telefono_hab">Teléfono Habitación</label>
                                                    <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                                                           value="<?php echo htmlspecialchars($representante->telefono_hab ?? ''); ?>" maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="correo">Correo Electrónico <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="email" class="form-control" id="correo" name="correo"
                                                           value="<?php echo htmlspecialchars($representante->correo ?? ''); ?>" required>
                                                    <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Información Profesional -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-briefcase"></i> Información Profesional
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="id_profesion">Profesión <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control select2" id="id_profesion" name="id_profesion" style="width: 100%;" required>
                                                        <option value="">Seleccione una profesión...</option>
                                                        <?php
                                                        if (isset($profesiones) && $profesiones) {
                                                            while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = ($representante->id_profesion ?? '') == $profesion['id_profesion'] ? 'selected' : '';
                                                                echo "<option value='{$profesion['id_profesion']}' {$selected}>{$profesion['profesion']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>Error al cargar profesiones</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="ocupacion">Ocupación <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="ocupacion" name="ocupacion"
                                                           value="<?php echo htmlspecialchars($representante->ocupacion ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lugar_trabajo">Lugar de Trabajo</label>
                                                    <input type="text" class="form-control" id="lugar_trabajo" name="lugar_trabajo"
                                                           value="<?php echo htmlspecialchars($representante->lugar_trabajo ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dirección del Representante -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-map-marker-alt"></i> Dirección del Representante
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="id_estado">Estado <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control select2" id="id_estado" name="id_estado" style="width: 100%;" required>
                                                        <option value="">Seleccione un estado...</option>
                                                        <?php
                                                        if (isset($estados) && $estados) {
                                                            while ($estado = $estados->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = ($representante->id_estado ?? '') == $estado['id_estado'] ? 'selected' : '';
                                                                echo "<option value='{$estado['id_estado']}' {$selected}>{$estado['nom_estado']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>Error al cargar estados</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="id_municipio">Municipio <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control select2" id="id_municipio" name="id_municipio" style="width: 100%;" required disabled>
                                                        <option value="">Primero seleccione un estado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="id_parroquia">Parroquia <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control select2" id="id_parroquia" name="id_parroquia" style="width: 100%;" required disabled>
                                                        <option value="">Primero seleccione un municipio</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="direccion">Dirección Completa <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                                           value="<?php echo htmlspecialchars($representante->direccion ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="calle">Calle</label>
                                                    <input type="text" class="form-control" id="calle" name="calle"
                                                           value="<?php echo htmlspecialchars($representante->calle ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="casa">Casa/Apto</label>
                                                    <input type="text" class="form-control" id="casa" name="casa"
                                                           value="<?php echo htmlspecialchars($representante->casa ?? ''); ?>">
                                                    <small class="form-text text-muted">Letras y números permitidos</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Actualizar Representante
                                        </button>
                                        <a href="representantes_list.php" class="btn btn-default">
                                            <i class="fas fa-arrow-left"></i> Cancelar
                                        </a>
                                    </div>
                                </form>
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

    <!-- jQuery -->
    <script src="/final/public/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 -->
    <script src="/final/public/plugins/select2/js/select2.full.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/final/public/dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function () {
            // Verificar que Select2 esté cargado
            if (typeof $.fn.select2 === 'undefined') {
                console.error('Select2 no está cargado correctamente');
                // Cargar Select2 desde CDN como fallback
                $.getScript('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js', function() {
                    initializeSelect2();
                });
            } else {
                initializeSelect2();
            }

            function initializeSelect2() {
                // Inicializar Select2 para todos los selects
                $('.select2').select2({
                    theme: 'bootstrap4',
                    width: 'resolve'
                });

                // Función para convertir texto a mayúsculas
                function convertirMayusculas(elemento) {
                    elemento.value = elemento.value.toUpperCase();
                }

                // Aplicar conversión a mayúsculas en tiempo real para todos los inputs de texto editables
                $('input[type="text"]:not([readonly])').on('input', function() {
                    convertirMayusculas(this);
                });

                // Solo letras (para nombres, apellidos, lugar de nacimiento, dirección, calle, ocupación, lugar de trabajo)
                $('#primer_nombre, #segundo_nombre, #primer_apellido, #segundo_apellido, #lugar_nac, #direccion, #calle, #ocupacion, #lugar_trabajo').on('input', function() {
                    this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                    convertirMayusculas(this);
                });

                // Solo números (para teléfonos y cédulas)
                $('#cedula, #telefono, #telefono_hab').on('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });

                // Casa/Apto - permite letras y números
                $('#casa').on('input', function() {
                    this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-#]/g, '');
                    convertirMayusculas(this);
                });

                // Validación de correo electrónico
                $('#correo').on('blur', function() {
                    const email = this.value;
                    if (email && !isValidEmail(email)) {
                        alert('Por favor, ingrese un correo electrónico válido (debe contener @ y dominio)');
                        this.focus();
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Función para validar formato de email
                function isValidEmail(email) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return emailRegex.test(email);
                }

                // Cargar municipios según estado seleccionado
                $('#id_estado').on('change', function() {
                    const idEstado = $(this).val();
                    const municipioSelect = $('#id_municipio');
                    const parroquiaSelect = $('#id_parroquia');

                    console.log('Estado seleccionado:', idEstado);

                    if (idEstado) {
                        municipioSelect.prop('disabled', false);
                        parroquiaSelect.prop('disabled', true).html('<option value="">Primero seleccione un municipio</option>');

                        $.ajax({
                            url: 'obtener_municipios.php',
                            type: 'POST',
                            data: { id_estado: idEstado },
                            dataType: 'json',
                            success: function(response) {
                                console.log('Respuesta municipios:', response);
                                if (response.success) {
                                    municipioSelect.html('<option value="">Seleccione un municipio...</option>');
                                    response.municipios.forEach(function(municipio) {
                                        municipioSelect.append(new Option(municipio.nom_municipio, municipio.id_municipio));
                                    });
                                    // Re-inicializar Select2
                                    municipioSelect.select2({
                                        theme: 'bootstrap4',
                                        width: 'resolve'
                                    });
                                } else {
                                    municipioSelect.html('<option value="">Error al cargar municipios</option>');
                                    console.error('Error en respuesta:', response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                municipioSelect.html('<option value="">Error al cargar municipios</option>');
                                console.error('Error AJAX:', error);
                                console.error('Status:', status);
                                console.error('Response:', xhr.responseText);
                            }
                        });
                    } else {
                        municipioSelect.prop('disabled', true).html('<option value="">Primero seleccione un estado</option>');
                        parroquiaSelect.prop('disabled', true).html('<option value="">Primero seleccione un municipio</option>');
                    }
                });

                // Cargar parroquias según municipio seleccionado
                $('#id_municipio').on('change', function() {
                    const idMunicipio = $(this).val();
                    const parroquiaSelect = $('#id_parroquia');

                    console.log('Municipio seleccionado:', idMunicipio);

                    if (idMunicipio) {
                        parroquiaSelect.prop('disabled', false);

                        $.ajax({
                            url: 'obtener_parroquias.php',
                            type: 'POST',
                            data: { id_municipio: idMunicipio },
                            dataType: 'json',
                            success: function(response) {
                                console.log('Respuesta parroquias:', response);
                                if (response.success) {
                                    parroquiaSelect.html('<option value="">Seleccione una parroquia...</option>');
                                    response.parroquias.forEach(function(parroquia) {
                                        parroquiaSelect.append(new Option(parroquia.nom_parroquia, parroquia.id_parroquia));
                                    });
                                    // Re-inicializar Select2
                                    parroquiaSelect.select2({
                                        theme: 'bootstrap4',
                                        width: 'resolve'
                                    });
                                } else {
                                    parroquiaSelect.html('<option value="">Error al cargar parroquias</option>');
                                    console.error('Error en respuesta:', response.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                parroquiaSelect.html('<option value="">Error al cargar parroquias</option>');
                                console.error('Error AJAX:', error);
                                console.error('Status:', status);
                                console.error('Response:', xhr.responseText);
                            }
                        });
                    } else {
                        parroquiaSelect.prop('disabled', true).html('<option value="">Primero seleccione un municipio</option>');
                    }
                });

                // Validación en tiempo real para campos obligatorios
                $('input[required], select[required]').on('blur', function() {
                    const valor = $(this).val();
                    if (!valor) {
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validación del formulario antes de enviar
                $('#formRepresentante').on('submit', function(e) {
                    let isValid = true;
                    let mensajesError = [];

                    // Campos obligatorios
                    const camposObligatorios = {
                        'nacionalidad': 'Nacionalidad',
                        'cedula': 'Cédula',
                        'primer_nombre': 'Primer Nombre',
                        'primer_apellido': 'Primer Apellido',
                        'sexo': 'Sexo',
                        'lugar_nac': 'Lugar de Nacimiento',
                        'telefono': 'Teléfono Móvil',
                        'correo': 'Correo Electrónico',
                        'id_profesion': 'Profesión',
                        'ocupacion': 'Ocupación',
                        'id_estado': 'Estado',
                        'id_municipio': 'Municipio',
                        'id_parroquia': 'Parroquia',
                        'direccion': 'Dirección Completa'
                    };

                    // Validar campos obligatorios
                    for (const [campo, nombre] of Object.entries(camposObligatorios)) {
                        const valor = campo.startsWith('id_') ? 
                            $(`#${campo}`).val() : 
                            $(`#${campo}`).val().trim();

                        if (!valor) {
                            mensajesError.push(`El campo "${nombre}" es obligatorio`);
                            $(`#${campo}`).addClass('is-invalid');
                            isValid = false;
                        } else {
                            $(`#${campo}`).removeClass('is-invalid');
                        }
                    }

                    // Validar teléfonos (solo números)
                    const telefono = $('#telefono').val();
                    const telefonoHab = $('#telefono_hab').val();

                    if (telefono && !/^\d+$/.test(telefono)) {
                        mensajesError.push('El teléfono móvil debe contener solo números');
                        isValid = false;
                    }

                    if (telefonoHab && !/^\d+$/.test(telefonoHab)) {
                        mensajesError.push('El teléfono de habitación debe contener solo números');
                        isValid = false;
                    }

                    // Validar correo electrónico
                    const correo = $('#correo').val();

                    if (correo && !isValidEmail(correo)) {
                        mensajesError.push('Por favor, ingrese un correo electrónico válido (formato: usuario@dominio.com)');
                        isValid = false;
                    }

                    // Validar fecha de nacimiento (no puede ser futura)
                    const fechaNac = $('#fecha_nac').val();
                    if (fechaNac) {
                        const hoy = new Date().toISOString().split('T')[0];
                        if (fechaNac > hoy) {
                            mensajesError.push('La fecha de nacimiento no puede ser futura');
                            isValid = false;
                        }
                    }

                    // Mostrar errores si los hay
                    if (!isValid) {
                        e.preventDefault();
                        alert('Por favor, corrija los siguientes errores:\n\n• ' + mensajesError.join('\n• '));

                        // Scroll al primer error
                        $('.is-invalid').first().focus();
                    }
                });

                // Limpiar validación cuando el usuario empiece a escribir
                $('input, select').on('input change', function() {
                    $(this).removeClass('is-invalid');
                });

                // Cargar datos de dirección si ya existen
                <?php if ($representante->id_estado): ?>
                setTimeout(() => {
                    $('#id_estado').val('<?php echo $representante->id_estado; ?>').trigger('change');
                    
                    // Esperar a que carguen los municipios y luego seleccionar el municipio
                    setTimeout(() => {
                        $('#id_municipio').val('<?php echo $representante->id_municipio; ?>').trigger('change');
                        
                        // Esperar a que carguen las parroquias y luego seleccionar la parroquia
                        setTimeout(() => {
                            $('#id_parroquia').val('<?php echo $representante->id_parroquia; ?>').trigger('change');
                        }, 500);
                    }, 500);
                }, 100);
                <?php endif; ?>
            }
        });
    </script>
</body>
</html>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>