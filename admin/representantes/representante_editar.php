<?php
session_start();

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/personas/personas2.php';

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

    // Instanciar el controlador
    $personasController = new PersonasController($db);

    // Obtener datos del representante
    $representante = $personasController->obtenerRepresentantePorId($id_representante);

    if (!$representante) {
        throw new Exception("Representante no encontrado");
    }

    // DEPURACIÓN: Ver qué datos se están obteniendo
    error_log("=== DATOS DEL REPRESENTANTE EN VISTA ===");
    error_log("Nacionalidad: " . ($representante['nacionalidad'] ?? 'NO ENCONTRADO'));
    error_log("Sexo: " . ($representante['sexo'] ?? 'NO ENCONTRADO'));
    error_log("Datos completos: " . print_r($representante, true));

    // Procesar formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validar datos antes de procesar
        $errores = [];

        // Validar cédula única (excepto para el mismo)
        if (!$personasController->validarCedulaUnica($_POST['cedula'], $representante['id_persona'])) {
            $errores[] = "La cédula ya está registrada para otra persona";
        }

        // Validar correo único (excepto para el mismo)
        if (!$personasController->validarCorreoUnico($_POST['correo'], $representante['id_persona'])) {
            $errores[] = "El correo electrónico ya está registrado para otra persona";
        }

        // Validar formato de email
        if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no tiene un formato válido";
        }

        // Validar teléfono
        if (!preg_match('/^\d+$/', $_POST['telefono'])) {
            $errores[] = "El teléfono móvil debe contener solo números";
        }

        if (!empty($_POST['telefono_hab']) && !preg_match('/^\d+$/', $_POST['telefono_hab'])) {
            $errores[] = "El teléfono de habitación debe contener solo números";
        }

        // Validar fecha de nacimiento (no futura)
        if (!empty($_POST['fecha_nac']) && $_POST['fecha_nac'] > date('Y-m-d')) {
            $errores[] = "La fecha de nacimiento no puede ser futura";
        }

        if (empty($errores)) {
            $resultado = $personasController->actualizarRepresentante($id_representante, $_POST);

            if ($resultado['success']) {
                $_SESSION['success'] = "Representante actualizado exitosamente";
                header("Location: representantes_list.php");
                exit();
            } else {
                $_SESSION['error'] = $resultado['message'] ?? "Error al actualizar el representante";
            }
        } else {
            $_SESSION['error'] = implode("<br>", $errores);
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: representantes_list.php");
    exit();
}

// Obtener datos para los selects
try {
    if ($db) {
        // Obtener estados
        $estados = $personasController->obtenerEstados();

        // Obtener profesiones
        $profesiones = $personasController->obtenerProfesiones();
    }
} catch (Exception $e) {
    // Error al cargar datos adicionales
    $errorDatos = $e->getMessage();
}

// DEPURACIÓN EN PANTALLA (solo para desarrollo)
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    echo "<pre>";
    echo "=== DATOS DEL REPRESENTANTE ===\n";
    echo "ID Representante: " . ($representante['id_representante'] ?? '') . "\n";
    echo "Nacionalidad: " . ($representante['nacionalidad'] ?? 'NO ENCONTRADO') . "\n";
    echo "Sexo: " . ($representante['sexo'] ?? 'NO ENCONTRADO') . "\n";
    echo "Prueba isset(nacionalidad): " . (isset($representante['nacionalidad']) ? 'TRUE' : 'FALSE') . "\n";
    echo "Prueba isset(sexo): " . (isset($representante['sexo']) ? 'TRUE' : 'FALSE') . "\n";
    echo "Valor nacionalidad: '" . ($representante['nacionalidad'] ?? '') . "'\n";
    echo "Valor sexo: '" . ($representante['sexo'] ?? '') . "'\n";
    echo "</pre>";
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Representante - Nuevo Horizonte</title>
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="/final/public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/final/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .campo-obligatorio {
            border-left: 3px solid #dc3545;
            padding-left: 10px;
        }

        .form-group label {
            font-weight: 500;
        }

        /* Estilo para depuración */
        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 12px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar y Sidebar (igual que antes) -->

        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- DEPURACIÓN (solo para desarrollo) -->
                    <?php if (isset($_GET['debug']) && $_GET['debug'] == '1'): ?>
                        <div class="debug-info">
                            <strong>DEBUG:</strong><br>
                            Nacionalidad: <?php echo htmlspecialchars($representante['nacionalidad'] ?? 'NO ENCONTRADO'); ?><br>
                            Sexo: <?php echo htmlspecialchars($representante['sexo'] ?? 'NO ENCONTRADO'); ?><br>
                            Nacionalidad == 'Venezolano': <?php echo ($representante['nacionalidad'] ?? '') == 'Venezolano' ? 'TRUE' : 'FALSE'; ?><br>
                            Sexo == 'Masculino': <?php echo ($representante['sexo'] ?? '') == 'Masculino' ? 'TRUE' : 'FALSE'; ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">Editar Representante: <?php echo htmlspecialchars($representante['primer_nombre'] . ' ' . $representante['primer_apellido']); ?></h3>
                                </div>
                                <form method="POST" id="formRepresentante">
                                    <div class="card-body">
                                        <!-- Datos Personales -->
                                        <h5 class="text-primary mb-3"><i class="fas fa-user-tie"></i> Información Personal</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="nacionalidad">Nacionalidad <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                        <option value="">Seleccione...</option>
                                                        <!-- VERSION DEPURADA -->
                                                        <option value="Venezolano" <?php
                                                                                    $nacionalidad = $representante['nacionalidad'] ?? '';
                                                                                    echo ($nacionalidad == 'Venezolano') ? 'selected' : '';
                                                                                    ?>>Venezolano</option>
                                                        <option value="Extranjero" <?php
                                                                                    $nacionalidad = $representante['nacionalidad'] ?? '';
                                                                                    echo ($nacionalidad == 'Extranjero') ? 'selected' : '';
                                                                                    ?>>Extranjero</option>
                                                    </select>
                                                    <!-- DEBUG -->
                                                    <?php if (isset($_GET['debug'])): ?>
                                                        <small class="text-muted">Valor actual: <?php echo htmlspecialchars($representante['nacionalidad'] ?? 'Vacío'); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Cédula <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="cedula" value="<?php echo htmlspecialchars($representante['cedula'] ?? ''); ?>" required maxlength="20">
                                                    <small class="text-muted">Solo números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Fecha de Nacimiento</label>
                                                    <input type="date" class="form-control" name="fecha_nac" value="<?php echo $representante['fecha_nac'] ?? ''; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Primer Nombre <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="primer_nombre" value="<?php echo htmlspecialchars($representante['primer_nombre'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Segundo Nombre</label>
                                                    <input type="text" class="form-control" name="segundo_nombre" value="<?php echo htmlspecialchars($representante['segundo_nombre'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Primer Apellido <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="primer_apellido" value="<?php echo htmlspecialchars($representante['primer_apellido'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Segundo Apellido</label>
                                                    <input type="text" class="form-control" name="segundo_apellido" value="<?php echo htmlspecialchars($representante['segundo_apellido'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="sexo">Sexo <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="sexo" name="sexo" required>
                                                        <option value="">Seleccione...</option>
                                                        <!-- VERSION DEPURADA -->
                                                        <option value="Masculino" <?php
                                                                                    $sexo = $representante['sexo'] ?? '';
                                                                                    echo ($sexo == 'Masculino') ? 'selected' : '';
                                                                                    ?>>Masculino</option>
                                                        <option value="Femenino" <?php
                                                                                    $sexo = $representante['sexo'] ?? '';
                                                                                    echo ($sexo == 'Femenino') ? 'selected' : '';
                                                                                    ?>>Femenino</option>
                                                    </select>
                                                    <!-- DEBUG -->
                                                    <?php if (isset($_GET['debug'])): ?>
                                                        <small class="text-muted">Valor actual: <?php echo htmlspecialchars($representante['sexo'] ?? 'Vacío'); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Lugar de Nacimiento <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="lugar_nac" value="<?php echo htmlspecialchars($representante['lugar_nac'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Teléfono Móvil <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="telefono" value="<?php echo htmlspecialchars($representante['telefono'] ?? ''); ?>" required maxlength="11">
                                                    <small class="text-muted">Solo números</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Teléfono Habitación</label>
                                                    <input type="text" class="form-control" name="telefono_hab" value="<?php echo htmlspecialchars($representante['telefono_hab'] ?? ''); ?>" maxlength="11">
                                                    <small class="text-muted">Solo números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Correo Electrónico <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="correo" value="<?php echo htmlspecialchars($representante['correo'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Información Profesional -->
                                        <h5 class="text-primary mb-3 mt-4"><i class="fas fa-briefcase"></i> Información Profesional</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Profesión <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" name="id_profesion" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php foreach ($profesiones as $profesion): ?>
                                                            <option value="<?php echo $profesion['id_profesion']; ?>" <?php echo ($representante['id_profesion'] ?? '') == $profesion['id_profesion'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($profesion['profesion']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Ocupación <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="ocupacion" value="<?php echo htmlspecialchars($representante['ocupacion'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Lugar de Trabajo</label>
                                                    <input type="text" class="form-control" name="lugar_trabajo" value="<?php echo htmlspecialchars($representante['lugar_trabajo'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dirección -->
                                        <h5 class="text-primary mb-3 mt-4"><i class="fas fa-map-marker-alt"></i> Dirección</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Estado <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="id_estado" name="id_estado" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php foreach ($estados as $estado): ?>
                                                            <option value="<?php echo $estado['id_estado']; ?>" <?php echo ($representante['id_estado'] ?? '') == $estado['id_estado'] ? 'selected' : ''; ?>>
                                                                <?php echo htmlspecialchars($estado['nom_estado']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Municipio <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="id_municipio" name="id_municipio" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php if (isset($representante['id_estado']) && $representante['id_estado']): ?>
                                                            <?php
                                                            $municipios = $personasController->obtenerMunicipiosPorEstado($representante['id_estado']);
                                                            foreach ($municipios as $municipio): ?>
                                                                <option value="<?php echo $municipio['id_municipio']; ?>" <?php echo ($representante['id_municipio'] ?? '') == $municipio['id_municipio'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($municipio['nom_municipio']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Parroquia <span class="text-danger">*</span></label>
                                                    <select class="form-control select2" id="id_parroquia" name="id_parroquia" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php if (isset($representante['id_municipio']) && $representante['id_municipio']): ?>
                                                            <?php
                                                            $parroquias = $personasController->obtenerParroquiasPorMunicipio($representante['id_municipio']);
                                                            foreach ($parroquias as $parroquia): ?>
                                                                <option value="<?php echo $parroquia['id_parroquia']; ?>" <?php echo ($representante['id_parroquia'] ?? '') == $parroquia['id_parroquia'] ? 'selected' : ''; ?>>
                                                                    <?php echo htmlspecialchars($parroquia['nom_parroquia']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group campo-obligatorio">
                                                    <label>Dirección Completa <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="direccion" value="<?php echo htmlspecialchars($representante['direccion'] ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Calle</label>
                                                    <input type="text" class="form-control" name="calle" value="<?php echo htmlspecialchars($representante['calle'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Casa/Apto</label>
                                                    <input type="text" class="form-control" name="casa" value="<?php echo htmlspecialchars($representante['casa'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

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
    </div>

    <!-- Scripts -->
    <script src="/final/public/plugins/jquery/jquery.min.js"></script>
    <script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/final/public/plugins/select2/js/select2.full.min.js"></script>
    <script src="/final/public/dist/js/adminlte.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: 'resolve'
            });

            // Variables para guardar selecciones
            let estadoSeleccionado = '<?php echo $representante['id_estado'] ?? ""; ?>';
            let municipioSeleccionado = '<?php echo $representante['id_municipio'] ?? ""; ?>';
            let parroquiaSeleccionada = '<?php echo $representante['id_parroquia'] ?? ""; ?>';

            // Función para cargar municipios con Fetch API - CORREGIDA
            async function cargarMunicipios(idEstado) {
                const selectMunicipio = document.getElementById('id_municipio');
                const selectParroquia = document.getElementById('id_parroquia');

                if (!idEstado || idEstado === '') {
                    selectMunicipio.innerHTML = '<option value="">Seleccione un municipio...</option>';
                    selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                    selectMunicipio.disabled = true;
                    selectParroquia.disabled = true;
                    $(selectMunicipio).trigger('change.select2');
                    $(selectParroquia).trigger('change.select2');
                    return;
                }

                // Mostrar loading
                selectMunicipio.disabled = true;
                selectMunicipio.innerHTML = '<option value="">Cargando municipios...</option>';

                // Limpiar y deshabilitar parroquia
                selectParroquia.disabled = true;
                selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                $(selectParroquia).trigger('change.select2');

                try {
                    const formData = new FormData();
                    formData.append('id_estado', idEstado);

                    console.log('Enviando solicitud para municipios del estado:', idEstado);

                    const response = await fetch('obtener_municipios.php', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    console.log('Respuesta recibida, status:', response.status);

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
                    }

                    const data = await response.json();
                    console.log('Datos recibidos:', data);

                    if (data.success && Array.isArray(data.municipios)) {
                        // Limpiar select
                        selectMunicipio.innerHTML = '<option value="">Seleccione un municipio...</option>';

                        // Agregar municipios
                        data.municipios.forEach(municipio => {
                            const option = document.createElement('option');
                            option.value = municipio.id_municipio;
                            option.textContent = municipio.nom_municipio;

                            // Seleccionar si es el municipio guardado
                            if (parseInt(municipio.id_municipio) === parseInt(municipioSeleccionado)) {
                                option.selected = true;
                                console.log('Municipio seleccionado automáticamente:', municipio.nom_municipio);
                            }

                            selectMunicipio.appendChild(option);
                        });

                        // Habilitar y actualizar select
                        selectMunicipio.disabled = false;
                        $(selectMunicipio).trigger('change.select2');

                        // Si hay municipio seleccionado, cargar parroquias automáticamente
                        if (municipioSeleccionado && selectMunicipio.value === municipioSeleccionado) {
                            console.log('Cargando parroquias para municipio:', municipioSeleccionado);
                            await cargarParroquias(municipioSeleccionado);
                        }
                    } else {
                        throw new Error(data.message || 'Error en la estructura de datos');
                    }
                } catch (error) {
                    console.error('Error al cargar municipios:', error);
                    selectMunicipio.innerHTML = '<option value="">Error al cargar municipios</option>';
                    selectMunicipio.disabled = false;
                    $(selectMunicipio).trigger('change.select2');
                }
            }

            // Función para cargar parroquias con Fetch API - CORREGIDA
            async function cargarParroquias(idMunicipio) {
                const selectParroquia = document.getElementById('id_parroquia');

                if (!idMunicipio || idMunicipio === '') {
                    selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                    selectParroquia.disabled = true;
                    $(selectParroquia).trigger('change.select2');
                    return;
                }

                // Mostrar loading
                selectParroquia.disabled = true;
                selectParroquia.innerHTML = '<option value="">Cargando parroquias...</option>';

                try {
                    const formData = new FormData();
                    formData.append('id_municipio', idMunicipio);

                    console.log('Enviando solicitud para parroquias del municipio:', idMunicipio);

                    const response = await fetch('obtener_parroquias.php', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                        },
                        body: formData
                    });

                    console.log('Respuesta recibida, status:', response.status);

                    if (!response.ok) {
                        throw new Error(`Error HTTP: ${response.status} ${response.statusText}`);
                    }

                    const data = await response.json();
                    console.log('Datos recibidos:', data);

                    if (data.success && Array.isArray(data.parroquias)) {
                        // Limpiar select
                        selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';

                        // Agregar parroquias
                        data.parroquias.forEach(parroquia => {
                            const option = document.createElement('option');
                            option.value = parroquia.id_parroquia;
                            option.textContent = parroquia.nom_parroquia;

                            // Seleccionar si es la parroquia guardada
                            if (parseInt(parroquia.id_parroquia) === parseInt(parroquiaSeleccionada)) {
                                option.selected = true;
                                console.log('Parroquia seleccionada automáticamente:', parroquia.nom_parroquia);
                            }

                            selectParroquia.appendChild(option);
                        });

                        // Habilitar y actualizar select
                        selectParroquia.disabled = false;
                        $(selectParroquia).trigger('change.select2');
                    } else {
                        throw new Error(data.message || 'Error en la estructura de datos');
                    }
                } catch (error) {
                    console.error('Error al cargar parroquias:', error);
                    selectParroquia.innerHTML = '<option value="">Error al cargar parroquias</option>';
                    selectParroquia.disabled = false;
                    $(selectParroquia).trigger('change.select2');
                }
            }

            // Evento cambio de estado - CORREGIDO
            document.getElementById('id_estado').addEventListener('change', function() {
                estadoSeleccionado = this.value;
                municipioSeleccionado = ''; // Reset municipio
                parroquiaSeleccionada = ''; // Reset parroquia

                console.log('Estado cambiado a:', estadoSeleccionado);

                // Actualizar select de municipio visualmente
                const selectMunicipio = document.getElementById('id_municipio');
                selectMunicipio.innerHTML = '<option value="">Cargando...</option>';
                selectMunicipio.disabled = true;

                // Limpiar parroquia
                const selectParroquia = document.getElementById('id_parroquia');
                selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                selectParroquia.disabled = true;

                $(selectMunicipio).trigger('change.select2');
                $(selectParroquia).trigger('change.select2');

                // Cargar municipios
                if (estadoSeleccionado) {
                    cargarMunicipios(estadoSeleccionado);
                }
            });

            // Evento cambio de municipio - CORREGIDO
            document.getElementById('id_municipio').addEventListener('change', function() {
                municipioSeleccionado = this.value;
                parroquiaSeleccionada = ''; // Reset parroquia

                console.log('Municipio cambiado a:', municipioSeleccionado);

                // Actualizar select de parroquia visualmente
                const selectParroquia = document.getElementById('id_parroquia');
                selectParroquia.innerHTML = '<option value="">Cargando...</option>';
                selectParroquia.disabled = true;

                $(selectParroquia).trigger('change.select2');

                // Cargar parroquias
                if (municipioSeleccionado) {
                    cargarParroquias(municipioSeleccionado);
                }
            });

            // Evento cambio de parroquia
            document.getElementById('id_parroquia').addEventListener('change', function() {
                parroquiaSeleccionada = this.value;
                console.log('Parroquia cambiada a:', parroquiaSeleccionada);
            });

            // Cargar municipios si ya hay estado seleccionado al cargar la página
            if (estadoSeleccionado) {
                console.log('Cargando municipios iniciales para estado:', estadoSeleccionado);
                // Pequeño delay para asegurar que Select2 se inicializó
                setTimeout(() => {
                    cargarMunicipios(estadoSeleccionado);
                }, 300);
            }

            // Validaciones del formulario (mantener igual)
            document.getElementById('formRepresentante').addEventListener('submit', function(e) {
                let valid = true;
                const mensajes = [];

                // Validar campos obligatorios
                const camposObligatorios = [
                    'nacionalidad', 'cedula', 'primer_nombre', 'primer_apellido',
                    'sexo', 'lugar_nac', 'telefono', 'correo',
                    'id_profesion', 'ocupacion', 'id_estado', 'id_municipio',
                    'id_parroquia', 'direccion'
                ];

                camposObligatorios.forEach(campo => {
                    const elemento = document.querySelector(`[name="${campo}"]`);
                    if (!elemento.value.trim()) {
                        elemento.classList.add('is-invalid');
                        mensajes.push(`El campo ${campo.replace('_', ' ')} es obligatorio`);
                        valid = false;
                    } else {
                        elemento.classList.remove('is-invalid');
                    }
                });

                // Validar cédula (solo números)
                const cedula = document.querySelector('[name="cedula"]');
                if (cedula.value && !/^\d+$/.test(cedula.value)) {
                    cedula.classList.add('is-invalid');
                    mensajes.push('La cédula debe contener solo números');
                    valid = false;
                }

                // Validar teléfonos (solo números)
                const telefono = document.querySelector('[name="telefono"]');
                const telefonoHab = document.querySelector('[name="telefono_hab"]');

                if (telefono.value && !/^\d+$/.test(telefono.value)) {
                    telefono.classList.add('is-invalid');
                    mensajes.push('El teléfono móvil debe contener solo números');
                    valid = false;
                }

                if (telefonoHab.value && !/^\d+$/.test(telefonoHab.value)) {
                    telefonoHab.classList.add('is-invalid');
                    mensajes.push('El teléfono de habitación debe contener solo números');
                    valid = false;
                }

                // Validar email
                const correo = document.querySelector('[name="correo"]');
                if (correo.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value)) {
                    correo.classList.add('is-invalid');
                    mensajes.push('El correo electrónico no es válido');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    alert('Por favor corrija los siguientes errores:\n\n• ' + mensajes.join('\n• '));
                    document.querySelector('.is-invalid').focus();
                }
            });

            // Remover error al escribir
            document.querySelectorAll('input, select').forEach(el => {
                el.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
    </script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: 'resolve'
            });

            // Variables para guardar selecciones
            let estadoSeleccionado = '<?php echo $representante['id_estado'] ?? ""; ?>';
            let municipioSeleccionado = '<?php echo $representante['id_municipio'] ?? ""; ?>';

            // Función para cargar municipios con Fetch
            async function cargarMunicipios(idEstado) {
                const selectMunicipio = document.getElementById('id_municipio');
                const selectParroquia = document.getElementById('id_parroquia');

                if (!idEstado) {
                    selectMunicipio.innerHTML = '<option value="">Seleccione un municipio...</option>';
                    selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                    return;
                }

                // Mostrar loading
                selectMunicipio.disabled = true;
                selectMunicipio.innerHTML = '<option value="">Cargando municipios...</option>';

                try {
                    const formData = new FormData();
                    formData.append('id_estado', idEstado);

                    const response = await fetch('obtener_municipios.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) throw new Error('Error en la respuesta');

                    const data = await response.json();

                    if (data.success) {
                        selectMunicipio.innerHTML = '<option value="">Seleccione un municipio...</option>';
                        data.municipios.forEach(municipio => {
                            const option = new Option(municipio.nom_municipio, municipio.id_municipio);
                            if (municipio.id_municipio == municipioSeleccionado) {
                                option.selected = true;
                            }
                            selectMunicipio.appendChild(option);
                        });

                        selectMunicipio.disabled = false;
                        $(selectMunicipio).trigger('change.select2');

                        // Si hay municipio seleccionado, cargar parroquias
                        if (municipioSeleccionado) {
                            await cargarParroquias(municipioSeleccionado);
                        }
                    } else {
                        throw new Error(data.message || 'Error al cargar municipios');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    selectMunicipio.innerHTML = '<option value="">Error al cargar</option>';
                }
            }

            // Función para cargar parroquias con Fetch
            async function cargarParroquias(idMunicipio) {
                const selectParroquia = document.getElementById('id_parroquia');

                if (!idMunicipio) {
                    selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                    return;
                }

                // Mostrar loading
                selectParroquia.disabled = true;
                selectParroquia.innerHTML = '<option value="">Cargando parroquias...</option>';

                try {
                    const formData = new FormData();
                    formData.append('id_municipio', idMunicipio);

                    const response = await fetch('obtener_parroquias.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) throw new Error('Error en la respuesta');

                    const data = await response.json();

                    if (data.success) {
                        selectParroquia.innerHTML = '<option value="">Seleccione una parroquia...</option>';
                        data.parroquias.forEach(parroquia => {
                            const option = new Option(parroquia.nom_parroquia, parroquia.id_parroquia);
                            if (parroquia.id_parroquia == '<?php echo $representante['id_parroquia'] ?? ""; ?>') {
                                option.selected = true;
                            }
                            selectParroquia.appendChild(option);
                        });

                        selectParroquia.disabled = false;
                        $(selectParroquia).trigger('change.select2');
                    } else {
                        throw new Error(data.message || 'Error al cargar parroquias');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    selectParroquia.innerHTML = '<option value="">Error al cargar</option>';
                }
            }

            // Evento cambio de estado
            document.getElementById('id_estado').addEventListener('change', function() {
                estadoSeleccionado = this.value;
                municipioSeleccionado = ''; // Reset municipio
                cargarMunicipios(estadoSeleccionado);
            });

            // Evento cambio de municipio
            document.getElementById('id_municipio').addEventListener('change', function() {
                municipioSeleccionado = this.value;
                cargarParroquias(municipioSeleccionado);
            });

            // Cargar municipios si ya hay estado seleccionado
            if (estadoSeleccionado) {
                setTimeout(() => cargarMunicipios(estadoSeleccionado), 100);
            }

            // Validaciones
            document.getElementById('formRepresentante').addEventListener('submit', function(e) {
                let valid = true;
                const mensajes = [];

                // Validar campos obligatorios
                const camposObligatorios = [
                    'nacionalidad', 'cedula', 'primer_nombre', 'primer_apellido',
                    'sexo', 'lugar_nac', 'telefono', 'correo',
                    'id_profesion', 'ocupacion', 'id_estado', 'id_municipio',
                    'id_parroquia', 'direccion'
                ];

                camposObligatorios.forEach(campo => {
                    const elemento = document.querySelector(`[name="${campo}"]`);
                    if (!elemento.value.trim()) {
                        elemento.classList.add('is-invalid');
                        mensajes.push(`El campo ${campo.replace('_', ' ')} es obligatorio`);
                        valid = false;
                    } else {
                        elemento.classList.remove('is-invalid');
                    }
                });

                // Validar cédula (solo números)
                const cedula = document.querySelector('[name="cedula"]');
                if (cedula.value && !/^\d+$/.test(cedula.value)) {
                    cedula.classList.add('is-invalid');
                    mensajes.push('La cédula debe contener solo números');
                    valid = false;
                }

                // Validar teléfonos (solo números)
                const telefono = document.querySelector('[name="telefono"]');
                const telefonoHab = document.querySelector('[name="telefono_hab"]');

                if (telefono.value && !/^\d+$/.test(telefono.value)) {
                    telefono.classList.add('is-invalid');
                    mensajes.push('El teléfono móvil debe contener solo números');
                    valid = false;
                }

                if (telefonoHab.value && !/^\d+$/.test(telefonoHab.value)) {
                    telefonoHab.classList.add('is-invalid');
                    mensajes.push('El teléfono de habitación debe contener solo números');
                    valid = false;
                }

                // Validar email
                const correo = document.querySelector('[name="correo"]');
                if (correo.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo.value)) {
                    correo.classList.add('is-invalid');
                    mensajes.push('El correo electrónico no es válido');
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                    alert('Por favor corrija los siguientes errores:\n\n• ' + mensajes.join('\n• '));
                    document.querySelector('.is-invalid').focus();
                }
            });

            // Remover error al escribir
            document.querySelectorAll('input, select').forEach(el => {
                el.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        });
    </script> -->
</body>

</html>