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
<style>
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .is-valid {
        border-color: #28a745 !important;
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
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
</style>
</head>

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

</div>

<script>
$(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // ========== FUNCIONES DE VALIDACIÓN ==========
    
    // Función para convertir texto a mayúsculas
    function convertirMayusculas(elemento) {
        elemento.value = elemento.value.toUpperCase();
    }

    // Función para validar solo letras
    function validarSoloLetras(event) {
        const key = event.key;
        // Permitir teclas de control
        if (event.ctrlKey || event.altKey ||
            key === 'Backspace' || key === 'Delete' ||
            key === 'Tab' || key === 'Escape' ||
            key === 'Enter' || key === 'ArrowLeft' ||
            key === 'ArrowRight' || key === 'Home' ||
            key === 'End') {
            return true;
        }

        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]$/;
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Función para validar solo números
    function validarSoloNumeros(event) {
        const key = event.key;
        // Permitir teclas de control
        if (event.ctrlKey || event.altKey ||
            key === 'Backspace' || key === 'Delete' ||
            key === 'Tab' || key === 'Escape' ||
            key === 'Enter' || key === 'ArrowLeft' ||
            key === 'ArrowRight' || key === 'Home' ||
            key === 'End') {
            return true;
        }

        const regex = /^[0-9]$/;
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    // Función para validar email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // ========== APLICAR VALIDACIONES A CAMPOS EDITABLES ==========

    // 1. CONVERSIÓN A MAYÚSCULAS para todos los campos de texto editables
    $('input[type="text"]:not([readonly])').on('input', function() {
        convertirMayusculas(this);
    });

    // 2. VALIDACIÓN DE LUGAR DE NACIMIENTO
    $('#lugar_nac').on('keypress', validarSoloLetras);
    $('#lugar_nac').on('blur', function() {
        let valor = this.value;
        valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
        valor = valor.replace(/\s+/g, ' ').trim();
        this.value = valor;
        convertirMayusculas(this);
    });

    // 3. VALIDACIÓN DE TELÉFONOS
    $('#telefono, #telefono_hab').on('keypress', validarSoloNumeros);
    $('#telefono, #telefono_hab').on('blur', function() {
        let valor = this.value;
        valor = valor.replace(/[^0-9]/g, '');
        this.value = valor;
        
        // Validar longitud
        if (this.id === 'telefono' && valor && valor.length < 10) {
            mostrarError(this, 'El teléfono móvil debe tener al menos 10 dígitos');
            $(this).addClass('is-invalid');
        } else {
            ocultarError(this);
            $(this).removeClass('is-invalid');
        }
    });

    // 4. VALIDACIÓN DE CAMPOS DE DIRECCIÓN
    $('#direccion, #calle, #casa').on('blur', function() {
        let valor = this.value;
        valor = valor.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-#\.]/g, '');
        valor = valor.replace(/\s+/g, ' ').trim();
        this.value = valor;
        convertirMayusculas(this);
    });

    // 5. VALIDACIÓN DE CORREO ELECTRÓNICO
    $('#correo').on('blur', function() {
        const email = this.value.trim();
        if (email && !isValidEmail(email)) {
            $(this).addClass('is-invalid');
            mostrarError(this, 'Formato de correo inválido: usuario@dominio.com');
        } else if (email) {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
            ocultarError(this);
        } else {
            $(this).removeClass('is-invalid is-valid');
            ocultarError(this);
        }
    });

    // 6. VALIDACIÓN DE CAMPOS OBLIGATORIOS
    $('select[required]').on('change', function() {
        if (!this.value) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
            $(this).addClass('is-valid');
        }
    });

    // ========== FUNCIONES AUXILIARES ==========

    function mostrarError(campo, mensaje) {
        ocultarError(campo);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = mensaje;
        errorDiv.id = `error-${campo.id}`;
        campo.parentNode.appendChild(errorDiv);
    }

    function ocultarError(campo) {
        const errorId = `error-${campo.id}`;
        const errorExistente = document.getElementById(errorId);
        if (errorExistente) {
            errorExistente.remove();
        }
    }

    // ========== VALIDACIÓN DEL FORMULARIO AL ENVIAR ==========

    $('#formEditar').on('submit', function(e) {
        let isValid = true;
        let mensajesError = [];

        // Validar campos obligatorios
        const camposObligatorios = {
            'nacionalidad': 'Nacionalidad',
            'sexo': 'Sexo', 
            'fecha_nac': 'Fecha de Nacimiento',
            'telefono': 'Teléfono Móvil',
            'id_profesion': 'Profesión'
        };

        for (const [campo, nombre] of Object.entries(camposObligatorios)) {
            const elemento = document.getElementById(campo);
            const valor = elemento.value.trim();
            
            if (!valor) {
                mensajesError.push(`"${nombre}" es obligatorio`);
                $(elemento).addClass('is-invalid');
                isValid = false;
            } else {
                $(elemento).removeClass('is-invalid');
            }
        }

        // Validar teléfono móvil
        const telefono = $('#telefono').val().trim();
        if (telefono && !/^\d{10,11}$/.test(telefono)) {
            mensajesError.push('El teléfono móvil debe tener 10-11 dígitos');
            isValid = false;
        }

        // Validar correo
        const correo = $('#correo').val().trim();
        if (correo && !isValidEmail(correo)) {
            mensajesError.push('El correo electrónico no tiene un formato válido');
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

        if (!isValid) {
            e.preventDefault();
            alert('ERRORES EN EL FORMULARIO:\n\n• ' + mensajesError.join('\n• '));
            $('.is-invalid').first().focus();
        }
    });

    // Limpiar validación al interactuar
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    console.log('✅ Validaciones de formulario cargadas correctamente');
});
</script>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>