<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/representantes/RepresentanteController.php';

$database = new Conexion();
$db = $database->conectar();

$representanteController = new RepresentanteController($db);

// Obtener datos para los selects
$estados = $representanteController->obtenerEstados()->fetchAll(PDO::FETCH_ASSOC);
$profesiones = $representanteController->obtenerProfesiones()->fetchAll(PDO::FETCH_ASSOC);

include_once("/xampp/htdocs/final/layout/layaout1.php");
?>

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
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Registrar Nuevo Docente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                        <li class="breadcrumb-item active">Nuevo</li>
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
                            <h3 class="card-title">Información del Docente</h3>
                        </div>
                        
                        <form id="formDocente" action="docente_guardar.php" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5>Datos Personales</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="nacionalidad">Nacionalidad <span class="text-danger">*</span></label>
                                            <select name="nacionalidad" id="nacionalidad" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <option value="Venezolano">Venezolano</option>
                                                <option value="Extranjero">Extranjero</option>
                                            </select>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="cedula">Cédula de Identidad <span class="text-danger">*</span></label>
                                            <input type="text" name="cedula" id="cedula" class="form-control solo-numeros" required maxlength="20" placeholder="Solo números">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="primer_nombre">Primer Nombre <span class="text-danger">*</span></label>
                                            <input type="text" name="primer_nombre" id="primer_nombre" class="form-control solo-letras" required placeholder="Solo letras">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" name="segundo_nombre" id="segundo_nombre" class="form-control solo-letras" placeholder="Solo letras">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="primer_apellido">Primer Apellido <span class="text-danger">*</span></label>
                                            <input type="text" name="primer_apellido" id="primer_apellido" class="form-control solo-letras" required placeholder="Solo letras">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control solo-letras" placeholder="Solo letras">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="sexo">Sexo <span class="text-danger">*</span></label>
                                            <select name="sexo" id="sexo" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <option value="Masculino">Masculino</option>
                                                <option value="Femenino">Femenino</option>
                                            </select>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                            <input type="date" name="fecha_nac" id="fecha_nac" class="form-control" required>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="lugar_nac">Lugar de Nacimiento <span class="text-danger">*</span></label>
                                            <input type="text" name="lugar_nac" id="lugar_nac" class="form-control solo-letras" required placeholder="Solo letras">
                                            <small class="form-text text-muted">Solo se permiten letras</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5>Información de Contacto</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="telefono">Teléfono Móvil <span class="text-danger">*</span></label>
                                            <input type="text" name="telefono" id="telefono" class="form-control solo-numeros" required maxlength="11" placeholder="Solo números">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" name="telefono_hab" id="telefono_hab" class="form-control solo-numeros" maxlength="11" placeholder="Solo números">
                                            <small class="form-text text-muted">Solo se permiten números</small>
                                        </div>

                                        <div class="form-group campo-obligatorio">
                                            <label for="correo">Correo Electrónico <span class="text-danger">*</span></label>
                                            <input type="email" name="correo" id="correo" class="form-control" required placeholder="usuario@dominio.com">
                                            <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                        </div>

                                        <h5 class="mt-4">Información Profesional</h5>

                                        <div class="form-group campo-obligatorio">
                                            <label for="id_profesion">Profesión <span class="text-danger">*</span></label>
                                            <select name="id_profesion" id="id_profesion" class="form-control" required>
                                                <option value="">Seleccione Profesión</option>
                                                <?php foreach ($profesiones as $prof): ?>
                                                    <option value="<?php echo $prof['id_profesion']; ?>">
                                                        <?php echo htmlspecialchars($prof['profesion']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5>Información de Dirección <span class="text-danger">* (Todos los campos son obligatorios)</span></h5>
                                        
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="estado">Estado <span class="text-danger">*</span></label>
                                                            <select name="estado" id="estado" class="form-control" required>
                                                                <option value="">Seleccionar Estado</option>
                                                                <?php foreach ($estados as $estado): ?>
                                                                    <option value="<?php echo $estado['id_estado']; ?>">
                                                                        <?php echo htmlspecialchars($estado['nom_estado']); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="municipio">Municipio <span class="text-danger">*</span></label>
                                                            <select name="municipio" id="municipio" class="form-control" required disabled>
                                                                <option value="">Primero seleccione un estado</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="parroquia">Parroquia <span class="text-danger">*</span></label>
                                                            <select name="parroquia" id="parroquia" class="form-control" required disabled>
                                                                <option value="">Primero seleccione un municipio</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="direccion">Dirección Completa <span class="text-danger">*</span></label>
                                                            <input type="text" name="direccion" id="direccion" class="form-control mayusculas" required placeholder="Dirección completa">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="calle">Calle/Avenida <span class="text-danger">*</span></label>
                                                            <input type="text" name="calle" id="calle" class="form-control mayusculas" required placeholder="Calle o avenida">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group campo-obligatorio">
                                                            <label for="casa">Casa/Edificio <span class="text-danger">*</span></label>
                                                            <input type="text" name="casa" id="casa" class="form-control mayusculas" required placeholder="Casa o edificio">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h5>Información del Usuario</h5>

                                        <div class="alert alert-info">
                                            <h6><i class="icon fas fa-info"></i> Información Automática</h6>
                                            <p class="mb-1">• El nombre de usuario se generará automáticamente con la cédula del docente.</p>
                                            <p class="mb-0">• La contraseña por defecto será la cédula del docente. Se recomienda que el docente cambie su contraseña al primer acceso.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

<script>
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
            var errorExistente = campo.parentNode.querySelector('.error-validacion');
            if (errorExistente) {
                errorExistente.remove();
            }
            
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
        
        // 2. SOLO LETRAS
        var camposLetras = document.querySelectorAll('.solo-letras');
        camposLetras.forEach(function(campo) {
            campo.addEventListener('input', function() {
                soloLetras(this);
            });
            
            campo.addEventListener('blur', function() {
                soloLetras(this);
            });
        });
        
        // 3. SOLO NÚMEROS
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
        
        // ========== SISTEMA DE DIRECCIÓN ==========
        
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
        
        var formulario = document.getElementById('formDocente');
        formulario.addEventListener('submit', function(e) {
            console.log('🔍 Validando formulario...');
            var errores = [];
            var isValid = true;
            
            // Validar campos obligatorios
            var camposObligatorios = [
                { id: 'nacionalidad', nombre: 'Nacionalidad' },
                { id: 'cedula', nombre: 'Cédula' },
                { id: 'primer_nombre', nombre: 'Primer nombre' },
                { id: 'primer_apellido', nombre: 'Primer apellido' },
                { id: 'sexo', nombre: 'Sexo' },
                { id: 'fecha_nac', nombre: 'Fecha de nacimiento' },
                { id: 'lugar_nac', nombre: 'Lugar de nacimiento' },
                { id: 'telefono', nombre: 'Teléfono móvil' },
                { id: 'correo', nombre: 'Correo electrónico' },
                { id: 'id_profesion', nombre: 'Profesión' },
                { id: 'estado', nombre: 'Estado' },
                { id: 'municipio', nombre: 'Municipio' },
                { id: 'parroquia', nombre: 'Parroquia' },
                { id: 'direccion', nombre: 'Dirección completa' },
                { id: 'calle', nombre: 'Calle/Avenida' },
                { id: 'casa', nombre: 'Casa/Edificio' }
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
            
            // Validar cédula (solo números y longitud mínima)
            var cedula = document.getElementById('cedula').value;
            if (cedula) {
                if (!/^\d+$/.test(cedula)) {
                    errores.push('La cédula debe contener solo números');
                    isValid = false;
                }
                if (cedula.length < 6) {
                    errores.push('La cédula debe tener al menos 6 dígitos');
                    isValid = false;
                }
            }
            
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
        var inputs = document.querySelectorAll('input, select');
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            input.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        console.log('✅ Todas las validaciones configuradas correctamente');
    });
</script>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>