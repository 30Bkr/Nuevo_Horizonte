<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

$database = new Conexion();
$db = $database->conectar();

$docente = new Docente($db);

// Obtener datos del docente a editar
$docente_encontrado = false;
if (isset($_GET['id'])) {
    $docente_encontrado = $docente->obtenerPorId($_GET['id']);
}

if (!$docente_encontrado) {
    $_SESSION['error'] = "Docente no encontrado";
    header("Location: docentes_list.php");
    exit();
}
include_once("/xampp/htdocs/final/layout/layaout1.php");

?>
<!-- <!DOCTYPE html>
<html lang="es"> -->

<!-- <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Docente - Nuevo Horizonte</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/final/public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/final/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->

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
</style>
</head>

<!-- <body class="hold-transition sidebar-mini">
    <div class="wrapper"> -->

<!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
                    <a href="docentes_list.php" class="nav-link">Docentes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Editar Docente</a>
                </li>
            </ul>
        </nav> -->

<!-- Sidebar -->
<!-- <aside class="main-sidebar sidebar-dark-primary elevation-4">
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
                    </ul>
                </nav>
            </div>
        </aside> -->

<!-- Content Wrapper -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Editar Docente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Mensajes de alerta -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Editar Información del Docente</h3>
                        </div>
                        <form id="formEditar" action="docente_actualizar.php" method="post">
                            <input type="hidden" name="id_docente" value="<?php echo $docente->id_docente; ?>">
                            <input type="hidden" name="id_persona" value="<?php echo $docente->id_persona; ?>">
                            <input type="hidden" name="id_direccion" value="<?php echo $docente->id_direccion; ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos Personales</h5>

                                        <!-- Nacionalidad como primer campo -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="nacionalidad">Nacionalidad <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Venezolano" <?php echo ($docente->nacionalidad == 'Venezolano') ? 'selected' : ''; ?>>Venezolano</option>
                                                <option value="Extranjero" <?php echo ($docente->nacionalidad == 'Extranjero') ? 'selected' : ''; ?>>Extranjero</option>
                                            </select>
                                        </div>

                                        <!-- Cédula como segundo campo -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="cedula">Cédula <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="cedula" name="cedula"
                                                value="<?php echo htmlspecialchars($docente->cedula); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">La cédula no se puede editar después del registro</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="primer_nombre">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="primer_nombre" name="primer_nombre"
                                                value="<?php echo htmlspecialchars($docente->primer_nombre); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">El primer nombre no se puede editar después del registro</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                value="<?php echo htmlspecialchars($docente->segundo_nombre); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">El segundo nombre no se puede editar después del registro</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="primer_apellido">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                value="<?php echo htmlspecialchars($docente->primer_apellido); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">El primer apellido no se puede editar después del registro</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                value="<?php echo htmlspecialchars($docente->segundo_apellido); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">El segundo apellido no se puede editar después del registro</small>
                                        </div>

                                        <!-- Sexo como campo obligatorio y editable -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="sexo">Sexo <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control" id="sexo" name="sexo" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Masculino" <?php echo ($docente->sexo == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="Femenino" <?php echo ($docente->sexo == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>

                                        <!-- Fecha de nacimiento como campo obligatorio -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                value="<?php echo $docente->fecha_nac; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento</label>
                                            <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                value="<?php echo htmlspecialchars($docente->lugar_nac); ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Información de Contacto</h5>

                                        <!-- Teléfono móvil como campo obligatorio -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="telefono">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="telefono" name="telefono"
                                                value="<?php echo htmlspecialchars($docente->telefono); ?>" required maxlength="11">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                                                value="<?php echo htmlspecialchars($docente->telefono_hab); ?>" maxlength="11">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo"
                                                value="<?php echo htmlspecialchars($docente->correo); ?>">
                                            <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                        </div>

                                        <h5 class="mt-4">Información Profesional</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="id_profesion">Profesión <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control select2" id="id_profesion" name="id_profesion" style="width: 100%;" required>
                                                <option value="">Seleccionar profesión...</option>
                                                <?php
                                                $profesiones = $docente->obtenerProfesiones();
                                                while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = ($docente->id_profesion == $row['id_profesion']) ? 'selected' : '';
                                                    echo "<option value='{$row['id_profesion']}' $selected>{$row['profesion']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h5>Información de Dirección</h5>

                                        <div class="form-group">
                                            <label for="id_parroquia">Parroquia</label>
                                            <select class="form-control select2" id="id_parroquia" name="id_parroquia" style="width: 100%;">
                                                <option value="">Seleccionar parroquia...</option>
                                                <?php
                                                $parroquias = $docente->obtenerParroquias();
                                                while ($row = $parroquias->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = ($docente->id_parroquia == $row['id_parroquia']) ? 'selected' : '';
                                                    $texto = $row['nom_parroquia'] . ' - ' . $row['nom_municipio'] . ' - ' . $row['nom_estado'];
                                                    echo "<option value='{$row['id_parroquia']}' $selected>{$texto}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="direccion">Dirección</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion"
                                                value="<?php echo htmlspecialchars($docente->direccion); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="calle">Calle</label>
                                            <input type="text" class="form-control" id="calle" name="calle"
                                                value="<?php echo htmlspecialchars($docente->calle); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="casa">Casa/Edificio</label>
                                            <input type="text" class="form-control" id="casa" name="casa"
                                                value="<?php echo htmlspecialchars($docente->casa); ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Información del Usuario</h5>

                                        <div class="form-group">
                                            <label for="usuario">Nombre de Usuario</label>
                                            <input type="text" class="form-control" id="usuario" name="usuario"
                                                value="<?php echo htmlspecialchars($docente->usuario); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">El usuario no se puede editar (se genera automáticamente con la cédula)</small>
                                        </div>

                                        <div class="alert alert-info">
                                            <h6><i class="icon fas fa-info"></i> Información</h6>
                                            Los siguientes datos no se pueden editar después del registro: nombres, apellidos, cédula, fecha de nacimiento, lugar de nacimiento y profesión.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Actualizar Docente
                                </button>
                                <a href="docentes_list.php" class="btn btn-default">
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

<!-- <footer class="main-footer">
            <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong>
        </footer> -->

</div>



<script>
    $(function() {
        // Inicializar Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Función para convertir texto a mayúsculas
        function convertirMayusculas(elemento) {
            elemento.value = elemento.value.toUpperCase();
        }

        // Aplicar conversión a mayúsculas en tiempo real para todos los inputs de texto editables
        $('input[type="text"]:not([readonly])').on('input', function() {
            convertirMayusculas(this);
        });

        // Solo letras (para lugar de nacimiento, dirección, calle, casa)
        $('#lugar_nac, #direccion, #calle, #casa').on('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9.,#\-]/g, '');
            convertirMayusculas(this);
        });

        // Solo números (para teléfonos)
        $('#telefono, #telefono_hab').on('input', function() {
            this.value = this.value.replace(/\D/g, '');
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
        $('#formEditar').on('submit', function(e) {
            let isValid = true;
            let mensajesError = [];

            // Campos obligatorios
            const camposObligatorios = {
                'nacionalidad': 'Nacionalidad',
                'sexo': 'Sexo',
                'fecha_nac': 'Fecha de Nacimiento',
                'telefono': 'Teléfono Móvil',
                'id_profesion': 'Profesión'
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
    });
</script>
<!-- </body>

</html> -->
<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>