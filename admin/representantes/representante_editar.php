<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';

// Obtener ID del representante
$id_representante = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_representante <= 0) {
    $_SESSION['error'] = "ID de representante inválido";
    header("Location: representantes_list.php");
    exit;
}

try {
    $database = new Conexion();
    $db = $database->conectar();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    // Obtener datos del representante
    $sql = "SELECT 
                r.id_representante,
                p.id_persona,
                p.primer_nombre,
                p.segundo_nombre,
                p.primer_apellido,
                p.segundo_apellido,
                p.cedula,
                p.telefono,
                p.telefono_hab,
                p.correo,
                p.lugar_nac,
                p.fecha_nac,
                p.sexo,
                p.nacionalidad,
                r.ocupacion,
                r.lugar_trabajo,
                r.id_profesion,
                d.id_direccion,
                d.direccion,
                d.calle,
                d.casa,
                d.id_parroquia,
                pa.id_municipio,
                m.id_estado
            FROM representantes r
            JOIN personas p ON r.id_persona = p.id_persona
            LEFT JOIN direcciones d ON p.id_direccion = d.id_direccion
            LEFT JOIN parroquias pa ON d.id_parroquia = pa.id_parroquia
            LEFT JOIN municipios m ON pa.id_municipio = m.id_municipio
            WHERE r.id_representante = ? AND p.estatus = 1 AND r.estatus = 1";

    $stmt = $db->prepare($sql);
    $stmt->execute([$id_representante]);
    $representante = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$representante) {
        $_SESSION['error'] = "Representante no encontrado o inactivo";
        header("Location: representantes_list.php");
        exit;
    }

    // Obtener lista de profesiones
    $sql_profesiones = "SELECT id_profesion, profesion FROM profesiones WHERE estatus = 1 ORDER BY profesion";
    $stmt_profesiones = $db->prepare($sql_profesiones);
    $stmt_profesiones->execute();
    $profesiones = $stmt_profesiones->fetchAll(PDO::FETCH_ASSOC);

    // Obtener lista de estados
    $sql_estados = "SELECT id_estado, nom_estado FROM estados WHERE estatus = 1 ORDER BY nom_estado";
    $stmt_estados = $db->prepare($sql_estados);
    $stmt_estados->execute();
    $estados = $stmt_estados->fetchAll(PDO::FETCH_ASSOC);

    // Si tiene municipio, obtener municipios del estado
    $municipios = [];
    if ($representante['id_estado']) {
        $sql_municipios = "SELECT id_municipio, nom_municipio FROM municipios 
                           WHERE id_estado = ? AND estatus = 1 ORDER BY nom_municipio";
        $stmt_municipios = $db->prepare($sql_municipios);
        $stmt_municipios->execute([$representante['id_estado']]);
        $municipios = $stmt_municipios->fetchAll(PDO::FETCH_ASSOC);
    }

    // Si tiene parroquia, obtener parroquias del municipio
    $parroquias = [];
    if ($representante['id_municipio']) {
        $sql_parroquias = "SELECT id_parroquia, nom_parroquia FROM parroquias 
                           WHERE id_municipio = ? AND estatus = 1 ORDER BY nom_parroquia";
        $stmt_parroquias = $db->prepare($sql_parroquias);
        $stmt_parroquias->execute([$representante['id_municipio']]);
        $parroquias = $stmt_parroquias->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: representantes_list.php");
    exit;
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
                <div class="col-md-12">
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><b>Datos del Representante</b></h3>
                            <div class="card-tools">
                                <a href="representantes_list.php" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="formEditarRepresentante" action="http://localhost/final/app/controllers/representantes/procesar_editar_representante.php" method="POST">
                                <!-- Campos ocultos -->
                                <input type="hidden" name="id_representante" value="<?php echo $representante['id_representante']; ?>">
                                <input type="hidden" name="id_persona" value="<?php echo $representante['id_persona']; ?>">
                                <input type="hidden" name="id_direccion" value="<?php echo $representante['id_direccion'] ?? ''; ?>">

                                <!-- Datos Personales -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nacionalidad">Nacionalidad *</label>
                                            <select name="nacionalidad" id="nacionalidad" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <option value="Venezolano" <?php echo ($representante['nacionalidad'] == 'Venezolano') ? 'selected' : ''; ?>>Venezolano</option>
                                                <option value="Extranjero" <?php echo ($representante['nacionalidad'] == 'Extranjero') ? 'selected' : ''; ?>>Extranjero</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cedula">Cédula de Identidad *</label>
                                            <input type="text" name="cedula" id="cedula"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['cedula']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sexo">Sexo *</label>
                                            <select name="sexo" id="sexo" class="form-control" required>
                                                <option value="">Seleccionar</option>
                                                <option value="Masculino" <?php echo ($representante['sexo'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="Femenino" <?php echo ($representante['sexo'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fecha_nac">Fecha de Nacimiento *</label>
                                            <input type="date" name="fecha_nac" id="fecha_nac"
                                                class="form-control"
                                                value="<?php echo $representante['fecha_nac']; ?>"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="primer_nombre">Primer Nombre *</label>
                                            <input type="text" name="primer_nombre" id="primer_nombre"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['primer_nombre']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" name="segundo_nombre" id="segundo_nombre"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['segundo_nombre'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="primer_apellido">Primer Apellido *</label>
                                            <input type="text" name="primer_apellido" id="primer_apellido"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['primer_apellido']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" name="segundo_apellido" id="segundo_apellido"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['segundo_apellido'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento *</label>
                                            <input type="text" name="lugar_nac" id="lugar_nac"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['lugar_nac']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico *</label>
                                            <input type="email" name="correo" id="correo"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['correo']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono Móvil *</label>
                                            <input type="text" name="telefono" id="telefono"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['telefono']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" name="telefono_hab" id="telefono_hab"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['telefono_hab'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos Profesionales -->
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="profesion">Profesión *</label>
                                            <select name="profesion" id="profesion" class="form-control" required>
                                                <option value="">Seleccione Profesión</option>
                                                <?php foreach ($profesiones as $prof): ?>
                                                    <option value="<?php echo $prof['id_profesion']; ?>"
                                                        <?php echo ($representante['id_profesion'] == $prof['id_profesion']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($prof['profesion']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="ocupacion">Ocupación *</label>
                                            <input type="text" name="ocupacion" id="ocupacion"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['ocupacion']); ?>"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="lugar_trabajo">Lugar de Trabajo</label>
                                            <input type="text" name="lugar_trabajo" id="lugar_trabajo"
                                                class="form-control"
                                                value="<?php echo htmlspecialchars($representante['lugar_trabajo'] ?? ''); ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Dirección -->
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h3 class="card-title"><b>Dirección del Representante</b></h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="estado">Estado *</label>
                                                    <select name="estado" id="estado" class="form-control" required>
                                                        <option value="">Seleccionar Estado</option>
                                                        <?php foreach ($estados as $estado): ?>
                                                            <option value="<?php echo $estado['id_estado']; ?>"
                                                                <?php echo ($representante['id_estado'] == $estado['id_estado']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($estado['nom_estado']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="municipio">Municipio *</label>
                                                    <select name="municipio" id="municipio" class="form-control"
                                                        <?php echo empty($municipios) ? 'disabled' : ''; ?> required>
                                                        <option value="">Seleccionar Municipio</option>
                                                        <?php foreach ($municipios as $mun): ?>
                                                            <option value="<?php echo $mun['id_municipio']; ?>"
                                                                <?php echo ($representante['id_municipio'] == $mun['id_municipio']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($mun['nom_municipio']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="parroquia">Parroquia *</label>
                                                    <select name="parroquia" id="parroquia" class="form-control"
                                                        <?php echo empty($parroquias) ? 'disabled' : ''; ?> required>
                                                        <option value="">Seleccionar Parroquia</option>
                                                        <?php foreach ($parroquias as $parr): ?>
                                                            <option value="<?php echo $parr['id_parroquia']; ?>"
                                                                <?php echo ($representante['id_parroquia'] == $parr['id_parroquia']) ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($parr['nom_parroquia']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="direccion">Dirección Completa *</label>
                                                    <input type="text" name="direccion" id="direccion"
                                                        class="form-control"
                                                        value="<?php echo htmlspecialchars($representante['direccion'] ?? ''); ?>"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="calle">Calle/Avenida</label>
                                                    <input type="text" name="calle" id="calle"
                                                        class="form-control"
                                                        value="<?php echo htmlspecialchars($representante['calle'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="casa">Casa/Edificio</label>
                                                    <input type="text" name="casa" id="casa"
                                                        class="form-control"
                                                        value="<?php echo htmlspecialchars($representante['casa'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botones -->
                                <div class="row mt-4">
                                    <div class="col-md-12 text-right">
                                        <a href="representantes_list.php" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancelar
                                        </a>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Actualizar Representante
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- JavaScript -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== FUNCIONES DE UBICACIÓN ==========

        function cargarMunicipios(estadoId) {
            console.log('Cargando municipios para estado ID:', estadoId);

            // Crear FormData
            const formData = new FormData();
            formData.append('estado_id', estadoId);

            // Mostrar loading en el select
            const municipioSelect = document.getElementById('municipio');
            municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
            municipioSelect.disabled = true;

            return fetch('/final/app/controllers/ubicaciones/municipios.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta de municipios:', response);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de municipios recibidos:', data);

                    municipioSelect.innerHTML = '<option value="">Seleccionar Municipio</option>';

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(municipio => {
                            municipioSelect.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
                        });
                    } else {
                        municipioSelect.innerHTML = '<option value="">No hay municipios disponibles</option>';
                    }

                    municipioSelect.disabled = false;
                    return data;
                })
                .catch(error => {
                    console.error('Error en cargarMunicipios:', error);
                    municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                    municipioSelect.disabled = true;

                    // Mostrar error al usuario
                    mostrarAlerta('error', 'Error', 'Error al cargar los municipios: ' + error.message);
                    throw error;
                });
        }

        function cargarParroquias(municipioId) {
            console.log('Cargando parroquias para municipio ID:', municipioId);

            const formData = new FormData();
            formData.append('municipio_id', municipioId);

            // Mostrar loading en el select
            const parroquiaSelect = document.getElementById('parroquia');
            parroquiaSelect.innerHTML = '<option value="">Cargando parroquias...</option>';
            parroquiaSelect.disabled = true;

            return fetch('/final/app/controllers/ubicaciones/parroquias.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta de parroquias:', response);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de parroquias recibidos:', data);

                    parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(parroquia => {
                            parroquiaSelect.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
                        });
                    } else {
                        parroquiaSelect.innerHTML = '<option value="">No hay parroquias disponibles</option>';
                    }

                    parroquiaSelect.disabled = false;
                    return data;
                })
                .catch(error => {
                    console.error('Error en cargarParroquias:', error);
                    parroquiaSelect.innerHTML = '<option value="">Error al cargar</option>';
                    parroquiaSelect.disabled = true;

                    mostrarAlerta('error', 'Error', 'Error al cargar las parroquias: ' + error.message);
                    throw error;
                });
        }

        // ========== EVENT LISTENERS ==========

        // Evento para cambio de estado
        document.getElementById('estado').addEventListener('change', function() {
            const estadoId = this.value;
            console.log('Estado seleccionado:', estadoId);

            if (estadoId) {
                cargarMunicipios(estadoId)
                    .then(() => {
                        // Limpiar y habilitar municipio
                        const municipioSelect = document.getElementById('municipio');
                        municipioSelect.value = '';
                        municipioSelect.disabled = false;

                        // Limpiar y deshabilitar parroquia
                        const parroquiaSelect = document.getElementById('parroquia');
                        parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                        parroquiaSelect.disabled = true;
                    })
                    .catch(error => {
                        console.error('Error en cambio de estado:', error);
                    });
            } else {
                // Limpiar y deshabilitar ambos
                const municipioSelect = document.getElementById('municipio');
                municipioSelect.innerHTML = '<option value="">Seleccionar Municipio</option>';
                municipioSelect.disabled = true;

                const parroquiaSelect = document.getElementById('parroquia');
                parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                parroquiaSelect.disabled = true;
            }
        });

        // Evento para cambio de municipio
        document.getElementById('municipio').addEventListener('change', function() {
            const municipioId = this.value;
            console.log('Municipio seleccionado:', municipioId);

            if (municipioId) {
                cargarParroquias(municipioId)
                    .then(() => {
                        // Habilitar parroquia
                        document.getElementById('parroquia').disabled = false;
                    })
                    .catch(error => {
                        console.error('Error en cambio de municipio:', error);
                    });
            } else {
                // Limpiar y deshabilitar parroquia
                const parroquiaSelect = document.getElementById('parroquia');
                parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                parroquiaSelect.disabled = true;
            }
        });

        // ========== VALIDACIÓN DEL FORMULARIO ==========
        document.getElementById('formEditarRepresentante').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validar campos básicos
            const camposRequeridos = [
                'primer_nombre', 'primer_apellido', 'cedula', 'telefono',
                'correo', 'lugar_nac', 'fecha_nac', 'sexo', 'nacionalidad',
                'ocupacion', 'profesion', 'estado', 'municipio', 'parroquia', 'direccion'
            ];

            let hayErrores = false;
            const errores = [];

            // Validar cada campo
            camposRequeridos.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (!elemento || !elemento.value || elemento.value.trim() === '') {
                    elemento.classList.add('is-invalid');
                    hayErrores = true;
                    const label = elemento.previousElementSibling ?
                        elemento.previousElementSibling.textContent.replace('*', '').trim() :
                        campo;
                    errores.push(`El campo "${label}" es requerido`);
                } else {
                    elemento.classList.remove('is-invalid');
                }
            });

            // Validaciones específicas
            const email = document.getElementById('correo').value;
            if (email && !isValidEmail(email)) {
                document.getElementById('correo').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El correo electrónico no es válido');
            }

            const telefono = document.getElementById('telefono').value;
            if (telefono && !/^\d+$/.test(telefono)) {
                document.getElementById('telefono').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El teléfono solo debe contener números');
            }

            const telefonoHab = document.getElementById('telefono_hab').value;
            if (telefonoHab && !/^\d+$/.test(telefonoHab)) {
                document.getElementById('telefono_hab').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El teléfono de habitación solo debe contener números');
            }

            const cedula = document.getElementById('cedula').value;
            if (cedula && !/^\d+$/.test(cedula)) {
                document.getElementById('cedula').classList.add('is-invalid');
                hayErrores = true;
                errores.push('La cédula solo debe contener números');
            }

            const fechaNac = document.getElementById('fecha_nac').value;
            if (fechaNac) {
                const fecha = new Date(fechaNac);
                const hoy = new Date();
                if (fecha > hoy) {
                    document.getElementById('fecha_nac').classList.add('is-invalid');
                    hayErrores = true;
                    errores.push('La fecha de nacimiento no puede ser futura');
                }
            }

            // Mostrar errores si los hay
            if (hayErrores) {
                let mensajeError = '<h5>Por favor corrija los siguientes errores:</h5><ul>';
                errores.forEach(error => {
                    mensajeError += `<li>${error}</li>`;
                });
                mensajeError += '</ul>';

                mostrarAlerta('error', 'Error de Validación', mensajeError);
                return false;
            }

            // Confirmar envío
            if (confirm('¿Está seguro de actualizar los datos del representante?')) {
                enviarFormulario();
            }

            return false;
        });

        // Función para validar email
        function isValidEmail(email) {
            const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        // Función para mostrar alertas
        function mostrarAlerta(type, title, message) {
            // Crear alerta con Bootstrap
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-${type === 'error' ? 'ban' : 'check'}"></i> ${title}</h5>
            ${message}
        `;

            // Insertar al inicio del contenido
            const content = document.querySelector('.content');
            if (content) {
                content.prepend(alertDiv);
            }

            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Función para mostrar loading
        function mostrarLoading(mostrar) {
            let overlay = document.getElementById('loadingOverlay');

            if (mostrar) {
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'loadingOverlay';
                    overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                `;
                    overlay.innerHTML = `
                    <div class="spinner-border text-light" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                `;
                    document.body.appendChild(overlay);
                }
                overlay.style.display = 'flex';
            } else if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // Función para enviar formulario con fetch
        function enviarFormulario() {
            const form = document.getElementById('formEditarRepresentante');
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Mostrar loading
            mostrarLoading(true);
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            submitBtn.disabled = true;

            // Enviar datos con fetch
            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta HTTP:', response.status, response.statusText);
                    return response.text();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);

                    // Ocultar loading
                    mostrarLoading(false);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    try {
                        // Intentar parsear como JSON
                        const jsonData = JSON.parse(data);

                        if (jsonData.success || jsonData.redirect) {
                            mostrarAlerta('success', '¡Éxito!', jsonData.message || 'Representante actualizado correctamente');

                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = jsonData.redirect || 'representantes_list.php';
                            }, 2000);
                        } else {
                            throw new Error(jsonData.message || 'Error desconocido');
                        }
                    } catch (e) {
                        // Si no es JSON, verificar si hay redirección en el HTML
                        if (data.includes('Location:') || data.includes('window.location') || data.includes('success')) {
                            mostrarAlerta('success', '¡Éxito!', 'Representante actualizado correctamente');

                            setTimeout(() => {
                                window.location.href = 'representantes_list.php';
                            }, 2000);
                        } else {
                            // Mostrar error
                            mostrarAlerta('error', 'Error', 'Error en la respuesta del servidor: ' + e.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);

                    // Ocultar loading
                    mostrarLoading(false);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Por favor intente nuevamente.');
                });
        }

        // ========== DEPURACIÓN ==========

        // Verificar que los archivos de ubicaciones existan
        function testUbicaciones() {
            console.log('=== TEST DE UBICACIONES ===');
            console.log('1. URL municipios:', '/final/app/controllers/ubicaciones/municipios.php');
            console.log('2. URL parroquias:', '/final/app/controllers/ubicaciones/parroquias.php');

            // Hacer una prueba rápida con el estado actual
            const estadoActual = document.getElementById('estado').value;
            if (estadoActual) {
                console.log('Estado actual seleccionado:', estadoActual);

                // Probar carga de municipios
                const testFormData = new FormData();
                testFormData.append('estado_id', estadoActual);

                fetch('/final/app/controllers/ubicaciones/municipios.php', {
                        method: 'POST',
                        body: testFormData
                    })
                    .then(response => {
                        console.log('Respuesta HTTP municipios:', response.status, response.statusText);
                        return response.text();
                    })
                    .then(text => {
                        console.log('Respuesta texto municipios (primeros 300 caracteres):', text.substring(0, 300));
                        try {
                            const json = JSON.parse(text);
                            console.log('JSON parseado municipios:', json);

                            if (json.error) {
                                console.error('Error del servidor:', json.error);
                            } else if (Array.isArray(json)) {
                                console.log('Municipios encontrados:', json.length);
                            }
                        } catch (e) {
                            console.error('No es JSON válido:', e.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error en test municipios:', error);
                    });
            }
        }

        // Ejecutar test al cargar la página (solo en desarrollo - descomentar para debug)
        // testUbicaciones();
    });
</script> -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== CONVERSIÓN AUTOMÁTICA A MAYÚSCULAS ==========

        function convertirMayusculas(elemento) {
            elemento.value = elemento.value.toUpperCase();
        }

        // Aplicar conversión a mayúsculas en tiempo real
        const inputsTexto = document.querySelectorAll('input[type="text"]:not([readonly])');
        inputsTexto.forEach(input => {
            input.addEventListener('input', function() {
                convertirMayusculas(this);
            });
            if (input.value) {
                convertirMayusculas(input);
            }
        });

        const textareas = document.querySelectorAll('textarea:not([readonly])');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                convertirMayusculas(this);
            });
            if (textarea.value) {
                convertirMayusculas(textarea);
            }
        });

        // ========== FUNCIONES DE VALIDACIÓN ==========

        // Función para validar solo letras (incluye espacios y acentos)
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

            // Expresión regular que permite letras, espacios y caracteres acentuados
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

            // Solo permitir números
            const regex = /^[0-9]$/;

            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }

            return true;
        }

        // Función para validar formato de correo electrónico
        function validarEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        // ========== APLICAR VALIDACIONES A LOS CAMPOS DEL REPRESENTANTE ==========

        // Campos de nombres y apellidos (solo letras)
        const camposLetras = [
            'primer_nombre', 'segundo_nombre',
            'primer_apellido', 'segundo_apellido',
            'lugar_nac', 'ocupacion', 'lugar_trabajo'
        ];

        camposLetras.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.addEventListener('keydown', validarSoloLetras);

                // Validación adicional al perder el foco
                campo.addEventListener('blur', function() {
                    let valor = this.value;
                    // Remover caracteres no permitidos
                    valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
                    // Remover espacios múltiples
                    valor = valor.replace(/\s+/g, ' ').trim();
                    this.value = valor;
                });
            }
        });

        // Campos numéricos
        const camposNumeros = ['telefono', 'telefono_hab', 'cedula'];

        camposNumeros.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.addEventListener('keydown', validarSoloNumeros);

                // Validación adicional al perder el foco
                campo.addEventListener('blur', function() {
                    let valor = this.value;
                    // Remover caracteres no numéricos
                    valor = valor.replace(/[^0-9]/g, '');
                    this.value = valor;
                });
            }
        });

        // Cédula (solo números, pero readonly, así que solo validación de formato)
        const cedulaField = document.getElementById('cedula');
        if (cedulaField) {
            cedulaField.addEventListener('blur', function() {
                let valor = this.value;
                valor = valor.replace(/[^0-9]/g, '');
                this.value = valor;
            });
        }

        // Validación de correo electrónico
        const correoField = document.getElementById('correo');
        if (correoField) {
            correoField.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && !validarEmail(email)) {
                    this.classList.add('is-invalid');
                    mostrarError(this, 'Por favor ingrese un correo electrónico válido (formato: usuario@dominio.com)');
                } else if (email && validarEmail(email)) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                    ocultarError(this);
                } else {
                    this.classList.remove('is-invalid', 'is-valid');
                    ocultarError(this);
                }
            });

            // Remover clases de validación al empezar a escribir
            correoField.addEventListener('input', function() {
                this.classList.remove('is-invalid', 'is-valid');
                ocultarError(this);
            });
        }

        // ========== VALIDACIÓN DE CAMPOS DE DIRECCIÓN ==========
        const camposDireccion = ['direccion', 'calle', 'casa'];

        camposDireccion.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo) {
                campo.addEventListener('blur', function() {
                    let valor = this.value;
                    // Permitir letras, números, espacios, guiones, # y puntos
                    valor = valor.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ\s\-#\.]/g, '');
                    // Remover espacios múltiples
                    valor = valor.replace(/\s+/g, ' ').trim();
                    this.value = valor;
                });
            }
        });

        // ========== FUNCIONES AUXILIARES ==========
        function mostrarError(campo, mensaje) {
            // Remover error anterior si existe
            ocultarError(campo);

            // Crear elemento de error
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.style.display = 'block';
            errorDiv.textContent = mensaje;
            errorDiv.id = `error-${campo.id}`;

            // Insertar después del campo
            campo.parentNode.appendChild(errorDiv);
        }

        function ocultarError(campo) {
            const errorId = `error-${campo.id}`;
            const errorExistente = document.getElementById(errorId);
            if (errorExistente) {
                errorExistente.remove();
            }
        }

        // ========== FUNCIONES DE UBICACIÓN ==========

        function cargarMunicipios(estadoId) {
            console.log('Cargando municipios para estado ID:', estadoId);

            const formData = new FormData();
            formData.append('estado_id', estadoId);

            // Mostrar loading en el select
            const municipioSelect = document.getElementById('municipio');
            municipioSelect.innerHTML = '<option value="">Cargando municipios...</option>';
            municipioSelect.disabled = true;

            return fetch('/final/app/controllers/ubicaciones/municipios.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta de municipios:', response);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de municipios recibidos:', data);

                    municipioSelect.innerHTML = '<option value="">Seleccionar Municipio</option>';

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(municipio => {
                            municipioSelect.innerHTML += `<option value="${municipio.id_municipio}">${municipio.nom_municipio}</option>`;
                        });
                    } else {
                        municipioSelect.innerHTML = '<option value="">No hay municipios disponibles</option>';
                    }

                    municipioSelect.disabled = false;
                    return data;
                })
                .catch(error => {
                    console.error('Error en cargarMunicipios:', error);
                    municipioSelect.innerHTML = '<option value="">Error al cargar</option>';
                    municipioSelect.disabled = true;

                    mostrarAlerta('error', 'Error', 'Error al cargar los municipios: ' + error.message);
                    throw error;
                });
        }

        function cargarParroquias(municipioId) {
            console.log('Cargando parroquias para municipio ID:', municipioId);

            const formData = new FormData();
            formData.append('municipio_id', municipioId);

            // Mostrar loading en el select
            const parroquiaSelect = document.getElementById('parroquia');
            parroquiaSelect.innerHTML = '<option value="">Cargando parroquias...</option>';
            parroquiaSelect.disabled = true;

            return fetch('/final/app/controllers/ubicaciones/parroquias.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta de parroquias:', response);
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Datos de parroquias recibidos:', data);

                    parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(parroquia => {
                            parroquiaSelect.innerHTML += `<option value="${parroquia.id_parroquia}">${parroquia.nom_parroquia}</option>`;
                        });
                    } else {
                        parroquiaSelect.innerHTML = '<option value="">No hay parroquias disponibles</option>';
                    }

                    parroquiaSelect.disabled = false;
                    return data;
                })
                .catch(error => {
                    console.error('Error en cargarParroquias:', error);
                    parroquiaSelect.innerHTML = '<option value="">Error al cargar</option>';
                    parroquiaSelect.disabled = true;

                    mostrarAlerta('error', 'Error', 'Error al cargar las parroquias: ' + error.message);
                    throw error;
                });
        }

        // ========== EVENT LISTENERS PARA UBICACIÓN ==========

        // Evento para cambio de estado
        document.getElementById('estado').addEventListener('change', function() {
            const estadoId = this.value;
            console.log('Estado seleccionado:', estadoId);

            if (estadoId) {
                cargarMunicipios(estadoId)
                    .then(() => {
                        // Limpiar y habilitar municipio
                        const municipioSelect = document.getElementById('municipio');
                        municipioSelect.value = '';
                        municipioSelect.disabled = false;

                        // Limpiar y deshabilitar parroquia
                        const parroquiaSelect = document.getElementById('parroquia');
                        parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                        parroquiaSelect.disabled = true;
                    })
                    .catch(error => {
                        console.error('Error en cambio de estado:', error);
                    });
            } else {
                // Limpiar y deshabilitar ambos
                const municipioSelect = document.getElementById('municipio');
                municipioSelect.innerHTML = '<option value="">Seleccionar Municipio</option>';
                municipioSelect.disabled = true;

                const parroquiaSelect = document.getElementById('parroquia');
                parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                parroquiaSelect.disabled = true;
            }
        });

        // Evento para cambio de municipio
        document.getElementById('municipio').addEventListener('change', function() {
            const municipioId = this.value;
            console.log('Municipio seleccionado:', municipioId);

            if (municipioId) {
                cargarParroquias(municipioId)
                    .then(() => {
                        // Habilitar parroquia
                        document.getElementById('parroquia').disabled = false;
                    })
                    .catch(error => {
                        console.error('Error en cambio de municipio:', error);
                    });
            } else {
                // Limpiar y deshabilitar parroquia
                const parroquiaSelect = document.getElementById('parroquia');
                parroquiaSelect.innerHTML = '<option value="">Seleccionar Parroquia</option>';
                parroquiaSelect.disabled = true;
            }
        });

        // ========== VALIDACIÓN COMPLETA DEL FORMULARIO ==========
        document.getElementById('formEditarRepresentante').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validar campos básicos
            const camposRequeridos = [
                'primer_nombre', 'primer_apellido', 'cedula', 'telefono',
                'correo', 'lugar_nac', 'fecha_nac', 'sexo', 'nacionalidad',
                'ocupacion', 'profesion', 'estado', 'municipio', 'parroquia', 'direccion'
            ];

            let hayErrores = false;
            const errores = [];

            // Validar cada campo requerido
            camposRequeridos.forEach(campo => {
                const elemento = document.getElementById(campo);
                if (!elemento || !elemento.value || elemento.value.trim() === '') {
                    elemento.classList.add('is-invalid');
                    hayErrores = true;
                    const label = elemento.previousElementSibling ?
                        elemento.previousElementSibling.textContent.replace('*', '').trim() :
                        campo;
                    errores.push(`El campo "${label}" es requerido`);
                } else {
                    elemento.classList.remove('is-invalid');
                }
            });

            // Validaciones específicas
            const email = document.getElementById('correo').value;
            if (email && !validarEmail(email)) {
                document.getElementById('correo').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El correo electrónico no es válido');
            }

            const telefono = document.getElementById('telefono').value;
            if (telefono && !/^\d+$/.test(telefono)) {
                document.getElementById('telefono').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El teléfono solo debe contener números');
            }

            const telefonoHab = document.getElementById('telefono_hab').value;
            if (telefonoHab && !/^\d+$/.test(telefonoHab)) {
                document.getElementById('telefono_hab').classList.add('is-invalid');
                hayErrores = true;
                errores.push('El teléfono de habitación solo debe contener números');
            }

            const cedula = document.getElementById('cedula').value;
            if (cedula && !/^\d+$/.test(cedula)) {
                document.getElementById('cedula').classList.add('is-invalid');
                hayErrores = true;
                errores.push('La cédula solo debe contener números');
            }

            const fechaNac = document.getElementById('fecha_nac').value;
            if (fechaNac) {
                const fecha = new Date(fechaNac);
                const hoy = new Date();
                if (fecha > hoy) {
                    document.getElementById('fecha_nac').classList.add('is-invalid');
                    hayErrores = true;
                    errores.push('La fecha de nacimiento no puede ser futura');
                }
            }

            // Validar que el estado tenga municipios cargados
            const estadoId = document.getElementById('estado').value;
            const municipioSelect = document.getElementById('municipio');
            if (estadoId && (municipioSelect.disabled || municipioSelect.options.length <= 1)) {
                document.getElementById('estado').classList.add('is-invalid');
                hayErrores = true;
                errores.push('Por favor seleccione un estado válido (debe tener municipios disponibles)');
            }

            // Validar que el municipio tenga parroquias cargadas
            const municipioId = document.getElementById('municipio').value;
            const parroquiaSelect = document.getElementById('parroquia');
            if (municipioId && (parroquiaSelect.disabled || parroquiaSelect.options.length <= 1)) {
                document.getElementById('municipio').classList.add('is-invalid');
                hayErrores = true;
                errores.push('Por favor seleccione un municipio válido (debe tener parroquias disponibles)');
            }

            // Mostrar errores si los hay
            if (hayErrores) {
                let mensajeError = '<h5>Por favor corrija los siguientes errores:</h5><ul>';
                errores.forEach(error => {
                    mensajeError += `<li>${error}</li>`;
                });
                mensajeError += '</ul>';

                mostrarAlerta('error', 'Error de Validación', mensajeError);
                return false;
            }

            // Confirmar envío
            if (confirm('¿Está seguro de actualizar los datos del representante?')) {
                enviarFormulario();
            }

            return false;
        });

        // Función para mostrar alertas
        function mostrarAlerta(type, title, message) {
            // Crear alerta con Bootstrap
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-${type === 'error' ? 'ban' : 'check'}"></i> ${title}</h5>
            ${message}
        `;

            // Insertar al inicio del contenido
            const content = document.querySelector('.content');
            if (content) {
                content.prepend(alertDiv);
            }

            // Auto-eliminar después de 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Función para mostrar loading
        function mostrarLoading(mostrar) {
            let overlay = document.getElementById('loadingOverlay');

            if (mostrar) {
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'loadingOverlay';
                    overlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.5);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                `;
                    overlay.innerHTML = `
                    <div class="spinner-border text-light" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                `;
                    document.body.appendChild(overlay);
                }
                overlay.style.display = 'flex';
            } else if (overlay) {
                overlay.style.display = 'none';
            }
        }

        // Función para enviar formulario con fetch
        function enviarFormulario() {
            const form = document.getElementById('formEditarRepresentante');
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Mostrar loading
            mostrarLoading(true);
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
            submitBtn.disabled = true;

            // Enviar datos con fetch
            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    console.log('Respuesta HTTP:', response.status, response.statusText);
                    return response.text();
                })
                .then(data => {
                    console.log('Datos recibidos:', data);

                    // Ocultar loading
                    mostrarLoading(false);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    try {
                        // Intentar parsear como JSON
                        const jsonData = JSON.parse(data);

                        if (jsonData.success || jsonData.redirect) {
                            mostrarAlerta('success', '¡Éxito!', jsonData.message || 'Representante actualizado correctamente');

                            // Redirigir después de 2 segundos
                            setTimeout(() => {
                                window.location.href = jsonData.redirect || 'representantes_list.php';
                            }, 2000);
                        } else {
                            throw new Error(jsonData.message || 'Error desconocido');
                        }
                    } catch (e) {
                        // Si no es JSON, verificar si hay redirección en el HTML
                        if (data.includes('Location:') || data.includes('window.location') || data.includes('success')) {
                            mostrarAlerta('success', '¡Éxito!', 'Representante actualizado correctamente');

                            setTimeout(() => {
                                window.location.href = 'representantes_list.php';
                            }, 2000);
                        } else {
                            // Mostrar error
                            mostrarAlerta('error', 'Error', 'Error en la respuesta del servidor: ' + e.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error en fetch:', error);

                    // Ocultar loading
                    mostrarLoading(false);
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    mostrarAlerta('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Por favor intente nuevamente.');
                });
        }

        // ========== APLICAR ESTILOS DE VALIDACIÓN ==========
        const style = document.createElement('style');
        style.textContent = `
        .is-valid {
            border-color: #28a745 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .is-invalid {
            border-color: #dc3545 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #dc3545;
        }
        
        /* Estilo para selects con validación */
        select.is-valid {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%2328a745' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e");
            background-position: right 2rem center, right .75rem center;
            background-size: 16px 12px, 8px 10px;
        }
        
        select.is-invalid {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23dc3545' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e"), url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='4' height='5' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e");
            background-position: right 2rem center, right .75rem center;
            background-size: 16px 12px, 8px 10px;
        }
    `;
        document.head.appendChild(style);

        console.log('✅ Todas las validaciones cargadas correctamente');
    });
</script>



<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
?>