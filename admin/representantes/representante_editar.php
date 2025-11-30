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
        
        // Obtener parroquias
        $parroquias = $controller_data->obtenerParroquias();
        
        // Obtener profesiones
        $profesiones = $controller_data->obtenerProfesiones();
    }
} catch (Exception $e) {
    // Error al cargar datos adicionales, pero continuamos
}

include_once("/xampp/htdocs/final/layout/layaout1.php");
?>
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
                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento</label>
                                            <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                   value="<?php echo htmlspecialchars($representante->lugar_nac ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group campo-obligatorio">
                                            <label for="telefono">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                            <input type="text" class="form-control" id="telefono" name="telefono"
                                                   value="<?php echo htmlspecialchars($representante->telefono ?? ''); ?>" maxlength="11">
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
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo"
                                                   value="<?php echo htmlspecialchars($representante->correo ?? ''); ?>">
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
                                        <div class="form-group">
                                            <label for="id_parroquia">Parroquia</label>
                                            <select class="form-control select2" id="id_parroquia" name="id_parroquia" style="width: 100%;">
                                                <option value="">Seleccione una parroquia...</option>
                                                <?php
                                                if (isset($parroquias) && $parroquias) {
                                                    while ($parroquia = $parroquias->fetch(PDO::FETCH_ASSOC)) {
                                                        $selected = ($representante->id_parroquia ?? '') == $parroquia['id_parroquia'] ? 'selected' : '';
                                                        echo "<option value='{$parroquia['id_parroquia']}' {$selected}>
                                                                {$parroquia['nom_parroquia']} - {$parroquia['nom_municipio']} - {$parroquia['nom_estado']}
                                                              </option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>Error al cargar parroquias</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="direccion">Dirección Principal</label>
                                            <input type="text" class="form-control" id="direccion" name="direccion"
                                                   value="<?php echo htmlspecialchars($representante->direccion ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="calle">Calle</label>
                                            <input type="text" class="form-control" id="calle" name="calle"
                                                   value="<?php echo htmlspecialchars($representante->calle ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="casa">Casa/Apto</label>
                                            <input type="text" class="form-control" id="casa" name="casa"
                                                   value="<?php echo htmlspecialchars($representante->casa ?? ''); ?>">
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

<!-- jQuery -->
<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="/final/public/plugins/select2/js/select2.full.min.js"></script>
<!-- AdminLTE App -->
<script src="/final/public/dist/js/adminlte.min.js"></script>

<script>
    $(function () {
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

        // Solo letras (para nombres, apellidos, lugar de nacimiento, dirección, calle, casa, ocupación, lugar de trabajo)
        $('#primer_nombre, #segundo_nombre, #primer_apellido, #segundo_apellido, #lugar_nac, #direccion, #calle, #ocupacion, #lugar_trabajo').on('input', function() {
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            convertirMayusculas(this);
        });

        // Validación específica para casa/apto (permite letras, números y caracteres especiales comunes)
        $('#casa').on('input', function() {
            // Permitir letras, números, guiones, #, y espacios
            this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s\-#]/g, '');
            convertirMayusculas(this);
        });

        // Solo números (para teléfonos y cédulas)
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
                'telefono': 'Teléfono Móvil',
                'id_profesion': 'Profesión',
                'ocupacion': 'Ocupación'
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
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>