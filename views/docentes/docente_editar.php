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

// Obtener datos de ubicación para los selects
include_once("/xampp/htdocs/final/app/controllers/ubicaciones/ubicaciones.php");
$ubicacionController = new UbicacionController($db);
$estados = $ubicacionController->obtenerEstados();

// Obtener municipios y parroquias si ya tiene dirección
$municipios = [];
$parroquias = [];
if ($docente->id_parroquia) {
    // Obtener municipio y estado actual del docente
    $query = "SELECT m.id_municipio, m.id_estado 
              FROM parroquias p 
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio 
              WHERE p.id_parroquia = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$docente->id_parroquia]);
    $ubicacion_actual = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ubicacion_actual) {
        $municipios = $ubicacionController->obtenerMunicipiosPorEstado($ubicacion_actual['id_estado']);
        $parroquias = $ubicacionController->obtenerParroquiasPorMunicipio($ubicacion_actual['id_municipio']);
    }
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
                        <form id="formEditarDocente" action="docente_actualizar.php" method="post">
                            <input type="hidden" name="id_docente" value="<?php echo $docente->id_docente; ?>">
                            <input type="hidden" name="id_persona" value="<?php echo $docente->id_persona; ?>">
                            <input type="hidden" name="id_direccion" value="<?php echo $docente->id_direccion; ?>">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos Personales</h5>

                                        <!-- Nacionalidad -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="nacionalidad">Nacionalidad <span class="text-danger">*</span></label>
                                            <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Venezolano" <?php echo ($docente->nacionalidad == 'Venezolano') ? 'selected' : ''; ?>>Venezolano</option>
                                                <option value="Extranjero" <?php echo ($docente->nacionalidad == 'Extranjero') ? 'selected' : ''; ?>>Extranjero</option>
                                            </select>
                                        </div>

                                        <!-- Cédula -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="cedula">Cédula <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="cedula" name="cedula"
                                                value="<?php echo htmlspecialchars($docente->cedula); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">La cédula no se puede editar</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="primer_nombre">Primer Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="primer_nombre" name="primer_nombre"
                                                value="<?php echo htmlspecialchars($docente->primer_nombre); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">No editable</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                value="<?php echo htmlspecialchars($docente->segundo_nombre); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">No editable</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="primer_apellido">Primer Apellido <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                value="<?php echo htmlspecialchars($docente->primer_apellido); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">No editable</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                value="<?php echo htmlspecialchars($docente->segundo_apellido); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">No editable</small>
                                        </div>

                                        <!-- Sexo -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="sexo">Sexo <span class="text-danger">*</span></label>
                                            <select class="form-control" id="sexo" name="sexo" required>
                                                <option value="">Seleccionar...</option>
                                                <option value="Masculino" <?php echo ($docente->sexo == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="Femenino" <?php echo ($docente->sexo == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Fecha de nacimiento -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                value="<?php echo $docente->fecha_nac; ?>" required>
                                        </div>

                                        <!-- Lugar de nacimiento -->
                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento</label>
                                            <input type="text" class="form-control solo-letras" id="lugar_nac" name="lugar_nac"
                                                value="<?php echo htmlspecialchars($docente->lugar_nac); ?>"
                                                placeholder="Solo letras y espacios">
                                        </div>

                                        <h5>Información de Contacto</h5>

                                        <!-- Teléfono móvil -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="telefono">Teléfono Móvil <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control solo-numeros" id="telefono" name="telefono"
                                                value="<?php echo htmlspecialchars($docente->telefono); ?>" required
                                                maxlength="11" placeholder="Solo números">
                                        </div>

                                        <!-- Teléfono habitación -->
                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" class="form-control solo-numeros" id="telefono_hab" name="telefono_hab"
                                                value="<?php echo htmlspecialchars($docente->telefono_hab); ?>"
                                                maxlength="11" placeholder="Solo números">
                                        </div>

                                        <!-- Correo electrónico -->
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo"
                                                value="<?php echo htmlspecialchars($docente->correo); ?>"
                                                placeholder="usuario@dominio.com">
                                        </div>

                                        <h5 class="mt-4">Información Profesional</h5>

                                        <!-- Profesión -->
                                        <div class="form-group campo-obligatorio">
                                            <label for="id_profesion">Profesión <span class="text-danger">*</span></label>
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
                                    <div class="col-md-12">
                                        <h5>Información de Dirección</h5>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="estado">Estado</label>
                                                    <select name="estado" id="estado" class="form-control" required>
                                                        <option value="">Seleccionar Estado</option>
                                                        <?php
                                                        foreach ($estados as $estado) {
                                                            $selected = (isset($ubicacion_actual['id_estado']) && $ubicacion_actual['id_estado'] == $estado['id_estado']) ? 'selected' : '';
                                                            echo "<option value='{$estado['id_estado']}' $selected>{$estado['nom_estado']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="municipio">Municipio</label>
                                                    <select name="municipio" id="municipio" class="form-control" required <?php echo empty($municipios) ? 'disabled' : ''; ?>>
                                                        <option value="">Primero seleccione un estado</option>
                                                        <?php
                                                        if (!empty($municipios)) {
                                                            foreach ($municipios as $municipio) {
                                                                $selected = (isset($ubicacion_actual['id_municipio']) && $ubicacion_actual['id_municipio'] == $municipio['id_municipio']) ? 'selected' : '';
                                                                echo "<option value='{$municipio['id_municipio']}' $selected>{$municipio['nom_municipio']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="parroquia">Parroquia</label>
                                                    <select name="parroquia" id="parroquia" class="form-control" required <?php echo empty($parroquias) ? 'disabled' : ''; ?>>
                                                        <option value="">Primero seleccione un municipio</option>
                                                        <?php
                                                        if (!empty($parroquias)) {
                                                            foreach ($parroquias as $parroquia) {
                                                                $selected = ($docente->id_parroquia == $parroquia['id_parroquia']) ? 'selected' : '';
                                                                echo "<option value='{$parroquia['id_parroquia']}' $selected>{$parroquia['nom_parroquia']}</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="direccion">Dirección Completa</label>
                                                    <input type="text" class="form-control mayusculas" id="direccion" name="direccion"
                                                        value="<?php echo htmlspecialchars($docente->direccion); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="calle">Calle/Avenida</label>
                                                    <input type="text" class="form-control mayusculas" id="calle" name="calle"
                                                        value="<?php echo htmlspecialchars($docente->calle); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="casa">Casa/Edificio</label>
                                                    <input type="text" class="form-control mayusculas" id="casa" name="casa"
                                                        value="<?php echo htmlspecialchars($docente->casa); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h5>Información del Usuario</h5>

                                        <!-- Usuario -->
                                        <div class="form-group">
                                            <label for="usuario">Nombre de Usuario</label>
                                            <input type="text" class="form-control" id="usuario" name="usuario"
                                                value="<?php echo htmlspecialchars($docente->usuario); ?>" readonly style="background-color: #e9ecef;">
                                            <small class="form-text text-muted">Generado automáticamente con la cédula</small>
                                        </div>

                                        <div class="alert alert-info">
                                            <h6><i class="icon fas fa-info"></i> Información</h6>
                                            <p class="mb-1">• Campos marcados con <span class="text-danger">*</span> son obligatorios</p>
                                            <p class="mb-1">• Los nombres, apellidos y cédula no se pueden editar</p>
                    
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

<script>
// VALIDACIONES GARANTIZADAS - SIN DEPENDENCIAS EXTERNAS
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Iniciando validaciones...');
    
    // ========== FUNCIONES BÁSICAS ==========
    
    // Convertir a mayúsculas
    function convertirMayusculas(input) {
        input.value = input.value.toUpperCase();
    }
    
    // Validar solo letras
    function soloLetras(input) {
        input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
        convertirMayusculas(input);
    }
    
    // Validar solo números
    function soloNumeros(input) {
        input.value = input.value.replace(/\D/g, '');
    }
    
    // Validar email
    function validarEmail(email) {
        return email.includes('@') && email.includes('.') && email.length > 5;
    }
    
    // Mostrar error
    function mostrarError(campo, mensaje) {
        // Remover error anterior
        var errorExistente = campo.parentNode.querySelector('.error-validacion');
        if (errorExistente) {
            errorExistente.remove();
        }
        
        // Crear nuevo error
        var errorDiv = document.createElement('div');
        errorDiv.className = 'error-validacion invalid-feedback d-block';
        errorDiv.textContent = mensaje;
        campo.parentNode.appendChild(errorDiv);
        campo.classList.add('is-invalid');
        campo.classList.remove('is-valid');
    }
    
    // Limpiar error
    function limpiarError(campo) {
        var errorExistente = campo.parentNode.querySelector('.error-validacion');
        if (errorExistente) {
            errorExistente.remove();
        }
        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
    }
    
    // ========== APLICAR EVENTOS ==========
    
    // 1. MAYÚSCULAS AUTOMÁTICAS
    var camposMayusculas = document.querySelectorAll('.mayusculas');
    camposMayusculas.forEach(function(campo) {
        campo.addEventListener('input', function() {
            convertirMayusculas(this);
        });
        
        campo.addEventListener('blur', function() {
            convertirMayusculas(this);
        });
    });
    
    // 2. SOLO LETRAS (Lugar de nacimiento)
    var campoLugarNac = document.getElementById('lugar_nac');
    if (campoLugarNac) {
        campoLugarNac.addEventListener('input', function() {
            soloLetras(this);
        });
        
        campoLugarNac.addEventListener('blur', function() {
            soloLetras(this);
        });
    }
    
    // 3. SOLO NÚMEROS (Teléfonos)
    var camposNumeros = document.querySelectorAll('.solo-numeros');
    camposNumeros.forEach(function(campo) {
        campo.addEventListener('input', function() {
            soloNumeros(this);
        });
        
        campo.addEventListener('blur', function() {
            soloNumeros(this);
            // Validar longitud mínima para teléfono móvil
            if (this.id === 'telefono' && this.value.length > 0 && this.value.length < 10) {
                mostrarError(this, 'Mínimo 10 dígitos');
            } else if (this.value.length >= 10) {
                limpiarError(this);
            }
        });
    });
    
    // 4. VALIDAR EMAIL
    var campoEmail = document.getElementById('correo');
    if (campoEmail) {
        campoEmail.addEventListener('blur', function() {
            var email = this.value.trim();
            if (email && !validarEmail(email)) {
                mostrarError(this, 'Formato inválido: usuario@dominio.com');
            } else if (email) {
                limpiarError(this);
            }
        });
    }
    
    // ========== SISTEMA DE DIRECCIÓN (ESTADO, MUNICIPIO, PARROQUIA) ==========
    
    // Cargar municipios cuando cambie el estado
    document.getElementById('estado').addEventListener('change', function() {
        const estadoId = this.value;
        const municipioSelect = document.getElementById('municipio');
        const parroquiaSelect = document.getElementById('parroquia');

        if (estadoId) {
            municipioSelect.disabled = false;
            parroquiaSelect.disabled = true;
            parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
            cargarMunicipios(estadoId);
        } else {
            municipioSelect.disabled = true;
            parroquiaSelect.disabled = true;
            municipioSelect.innerHTML = '<option value="">Primero seleccione un estado</option>';
            parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        }
    });

    // Cargar parroquias cuando cambie el municipio
    document.getElementById('municipio').addEventListener('change', function() {
        const municipioId = this.value;
        const parroquiaSelect = document.getElementById('parroquia');

        if (municipioId) {
            parroquiaSelect.disabled = false;
            cargarParroquias(municipioId);
        } else {
            parroquiaSelect.disabled = true;
            parroquiaSelect.innerHTML = '<option value="">Primero seleccione un municipio</option>';
        }
    });

    function cargarMunicipios(estadoId) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('estado_id', estadoId);

            fetch('/final/app/controllers/ubicaciones/municipios.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                const select = document.getElementById('municipio');
                select.innerHTML = '<option value="">Seleccionar Municipio</option>';

                data.forEach(municipio => {
                    select.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
                });
                resolve();
            })
            .catch(error => {
                console.error('Error al cargar municipios:', error);
                reject(error);
            });
        });
    }

    function cargarParroquias(municipioId) {
        return new Promise((resolve, reject) => {
            const formData = new FormData();
            formData.append('municipio_id', municipioId);

            fetch('/final/app/controllers/ubicaciones/parroquias.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                const select = document.getElementById('parroquia');
                select.innerHTML = '<option value="">Seleccionar Parroquia</option>';

                data.forEach(parroquia => {
                    select.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
                });
                resolve();
            })
            .catch(error => {
                console.error('Error al cargar parroquias:', error);
                reject(error);
            });
        });
    }
    
    // ========== VALIDACIÓN AL ENVIAR FORMULARIO ==========
    
    var formulario = document.getElementById('formEditarDocente');
    formulario.addEventListener('submit', function(e) {
        console.log('🔍 Validando formulario...');
        var errores = [];
        var isValid = true;
        
        // Validar campos obligatorios
        var camposObligatorios = [
            { id: 'nacionalidad', nombre: 'Nacionalidad' },
            { id: 'sexo', nombre: 'Sexo' },
            { id: 'fecha_nac', nombre: 'Fecha de nacimiento' },
            { id: 'telefono', nombre: 'Teléfono móvil' },
            { id: 'id_profesion', nombre: 'Profesión' },
            { id: 'estado', nombre: 'Estado' },
            { id: 'municipio', nombre: 'Municipio' },
            { id: 'parroquia', nombre: 'Parroquia' }
        ];
        
        camposObligatorios.forEach(function(campo) {
            var elemento = document.getElementById(campo.id);
            if (!elemento.value.trim()) {
                errores.push(campo.nombre + ' es obligatorio');
                elemento.classList.add('is-invalid');
                isValid = false;
            } else {
                elemento.classList.remove('is-invalid');
            }
        });
        
        // Validar teléfono móvil (longitud)
        var telefono = document.getElementById('telefono').value;
        if (telefono && telefono.length < 10) {
            errores.push('Teléfono móvil debe tener al menos 10 dígitos');
            isValid = false;
        }
        
        // Validar email
        var email = document.getElementById('correo').value;
        if (email && !validarEmail(email)) {
            errores.push('Correo electrónico debe tener formato: usuario@dominio.com');
            isValid = false;
        }
        
        // Validar fecha no futura
        var fechaNac = document.getElementById('fecha_nac').value;
        if (fechaNac) {
            var hoy = new Date().toISOString().split('T')[0];
            if (fechaNac > hoy) {
                errores.push('La fecha de nacimiento no puede ser futura');
                isValid = false;
            }
        }
        
        // Si hay errores, prevenir envío y mostrar alerta
        if (!isValid) {
            e.preventDefault();
            alert('❌ ERRORES EN EL FORMULARIO:\n\n• ' + errores.join('\n• '));
            console.log('❌ Formulario bloqueado por errores:', errores);
        } else {
            console.log('✅ Formulario válido, enviando...');
        }
    });
    
    // Limpiar validación al interactuar
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    console.log('✅ Todas las validaciones configuradas correctamente');
});
</script>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>