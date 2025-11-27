<?php
session_start();
include_once("/xampp/htdocs/final/layout/layaout1.php");
?>
<!-- <!DOCTYPE html>
<html lang="es"> -->

<!-- <head> -->
<!-- <meta charset="utf-8"> -->
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<!-- <title>Registrar Nuevo Docente - Nuevo Horizonte</title> -->

<!-- Google Font: Source Sans Pro -->
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
<!-- Font Awesome -->
<!-- <link rel="stylesheet" href="../../public/plugins/fontawesome-free/css/all.min.css"> -->
<!-- Theme style -->
<!-- <link rel="stylesheet" href="../../public/dist/css/adminlte.min.css"> -->
<!-- Select2 -->
<!-- <link rel="stylesheet" href="../../public/plugins/select2/css/select2.min.css"> -->
<!-- <link rel="stylesheet" href="../../public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> -->

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
<!-- </head> -->

<!-- <body class="hold-transition sidebar-mini"> -->
<!-- <div class="wrapper"> -->

<!-- Navbar -->
<!-- /.navbar -->

<!-- Main Sidebar Container -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Registrar Nuevo Docente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="../../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
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
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Información del Docente</h3>
                        </div>
                        <!-- form start -->
                        <form id="formDocente" action="docente_guardar.php" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos Personales</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="nacionalidad">Nacionalidad <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Venezolano">Venezolano</option>
                                                <option value="Extranjero">Extranjero</option>
                                            </select>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="cedula">Cédula <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="cedula" name="cedula" required maxlength="20">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="primer_nombre">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" required>
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="primer_apellido">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" required>
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="sexo">Sexo <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control" id="sexo" name="sexo" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento</label>
                                            <input type="text" class="form-control" id="lugar_nac" name="lugar_nac">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Información de Contacto</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="telefono">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="telefono" name="telefono" required maxlength="11">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" class="form-control" id="telefono_hab" name="telefono_hab" maxlength="11">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo">
                                            <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                        </div>

                                        <h5 class="mt-4">Información Profesional</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="id_profesion">Profesión <span class="text-danger">* (Obligatorio)</span></label>
                                            <select class="form-control select2" id="id_profesion" name="id_profesion" style="width: 100%;" required>
                                                <option value="">Seleccionar profesión...</option>
                                                <?php
                                                include_once __DIR__ . '/../../app/conexion.php';
                                                include_once __DIR__ . '/../../models/Docente.php';

                                                $database = new Conexion();
                                                $db = $database->conectar();

                                                if ($db) {
                                                    $docente = new Docente($db);
                                                    $profesiones = $docente->obtenerProfesiones();

                                                    while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row['id_profesion']}'>{$row['profesion']}</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <h5 class="mt-4">Información de Dirección</h5>

                                        <div class="form-group">
                                            <label for="id_parroquia">Parroquia</label>
                                            <select class="form-control select2" id="id_parroquia" name="id_parroquia" style="width: 100%;">
                                                <option value="">Seleccionar parroquia...</option>
                                                <?php
                                                if ($db) {
                                                    $parroquias = $docente->obtenerParroquias();
                                                    while ($row = $parroquias->fetch(PDO::FETCH_ASSOC)) {
                                                        $texto = $row['nom_parroquia'] . ' - ' . $row['nom_municipio'] . ' - ' . $row['nom_estado'];
                                                        echo "<option value='{$row['id_parroquia']}'>{$texto}</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="direccion">Dirección</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion">
                                        </div>

                                        <div class="form-group">
                                            <label for="calle">Calle</label>
                                            <input type="text" class="form-control" id="calle" name="calle">
                                        </div>

                                        <div class="form-group">
                                            <label for="casa">Casa/Edificio</label>
                                            <input type="text" class="form-control" id="casa" name="casa">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5>Información del Usuario</h5>

                                        <div class="alert alert-info">
                                            <h6><i class="icon fas fa-info"></i> Información Automática</h6>
                                            El nombre de usuario se generará automáticamente con la cédula del docente.<br>
                                            La contraseña por defecto será la cédula del docente. Se recomienda que el docente cambie su contraseña al primer acceso.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Docente
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

<!-- Footer -->

<!-- </div> -->

<!-- jQuery -->
<!-- <script src="../../public/plugins/jquery/jquery.min.js"></script> -->
<!-- Bootstrap 4 -->
<!-- <script src="../../public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- Select2 -->
<!-- <script src="../../public/plugins/select2/js/select2.full.min.js"></script> -->
<!-- AdminLTE App -->
<!-- <script src="../../public/dist/js/adminlte.min.js"></script> -->

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

        // Aplicar conversión a mayúsculas en tiempo real para todos los inputs de texto
        $('input[type="text"]').on('input', function() {
            convertirMayusculas(this);
        });

        // Solo letras (para nombres, apellidos y lugar de nacimiento)
        $('#primer_nombre, #segundo_nombre, #primer_apellido, #segundo_apellido, #lugar_nac').on('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            convertirMayusculas(this);
        });

        // Solo números (para cédula y teléfonos)
        $('#cedula, #telefono, #telefono_hab').on('input', function() {
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
        $('#formDocente').on('submit', function(e) {
            let isValid = true;
            let mensajesError = [];

            // Campos obligatorios
            const camposObligatorios = {
                'nacionalidad': 'Nacionalidad',
                'cedula': 'Cédula',
                'primer_nombre': 'Primer Nombre',
                'primer_apellido': 'Primer Apellido',
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

            // Validar cédula (solo números y longitud mínima)
            const cedula = $('#cedula').val();
            if (cedula) {
                if (!/^\d+$/.test(cedula)) {
                    mensajesError.push('La cédula debe contener solo números');
                    isValid = false;
                }
                if (cedula.length < 6) {
                    mensajesError.push('La cédula debe tener al menos 6 dígitos');
                    isValid = false;
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