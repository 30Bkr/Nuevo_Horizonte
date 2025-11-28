<?php
session_start();

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';

$id_estudiante = $_GET['id'] ?? '';

if (empty($id_estudiante) || !is_numeric($id_estudiante)) {
    $_SESSION['error'] = "ID de estudiante inválido";
    header("Location: estudiantes_list.php");
    exit();
}

try {
    $database = new Conexion();
    $db = $database->conectar();

    if (!$db) {
        throw new Exception("Error de conexión a la base de datos");
    }

    $controller = new EstudianteController($db);
    
    // Obtener datos del estudiante
    if (!$controller->obtener($id_estudiante)) {
        throw new Exception("Estudiante no encontrado");
    }

    $estudiante = $controller->estudiante;

    // Obtener patologías y discapacidades seleccionadas
    $patologias_seleccionadas = $controller->estudiante->obtenerPatologiasEstudiante($id_estudiante);
    $discapacidades_seleccionadas = $controller->estudiante->obtenerDiscapacidadesEstudiante($id_estudiante);

    // Procesar formulario de actualización
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($controller->actualizar($id_estudiante, $_POST)) {
            $_SESSION['success'] = "Estudiante actualizado exitosamente";
            header("Location: estudiantes_list.php");
            exit();
        }
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: estudiantes_list.php");
    exit();
}

// Obtener datos para los selects
try {
    $database = new Conexion();
    $db = $database->conectar();
    if ($db) {
        $controller_data = new EstudianteController($db);
        
        // Obtener parroquias
        $parroquias = $controller_data->obtenerParroquias();
        
        // Obtener patologías
        $patologias = $controller_data->obtenerPatologias();
        
        // Obtener discapacidades
        $discapacidades = $controller_data->obtenerDiscapacidades();
        
        // Obtener parentescos
        $parentescos = $controller_data->obtenerParentescos();
        
        // Obtener profesiones
        $profesiones = $controller_data->obtenerProfesiones();
    }
} catch (Exception $e) {
    // Error al cargar datos adicionales, pero continuamos
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Estudiante - Nuevo Horizonte</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/final/public/plugins/select2/css/select2.min.css">
    
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
                    <a href="estudiantes_list.php" class="nav-link">Estudiantes</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Editar Estudiante</a>
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
                            <a href="estudiantes_list.php" class="nav-link active">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>Estudiantes</p>
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
                            <h1>Editar Estudiante</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="estudiantes_list.php">Estudiantes</a></li>
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
                                    <h3 class="card-title">Editar Datos del Estudiante</h3>
                                </div>
                                <form method="POST" id="formEstudiante">
                                    <div class="card-body">
                                        <!-- Datos Personales del Estudiante -->
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-user-graduate"></i> Información Personal del Estudiante
                                        </h5>
                                        <div class="row">
                                            <!-- Nacionalidad como lista desplegable -->
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="nacionalidad">Nacionalidad <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control" id="nacionalidad" name="nacionalidad" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Venezolano" <?php echo ($estudiante->nacionalidad ?? '') == 'Venezolano' ? 'selected' : ''; ?>>Venezolano</option>
                                                        <option value="Extranjero" <?php echo ($estudiante->nacionalidad ?? '') == 'Extranjero' ? 'selected' : ''; ?>>Extranjero</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <!-- Cédula editable -->
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="cedula">Cédula <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                                           value="<?php echo htmlspecialchars($estudiante->cedula ?? ''); ?>" required
                                                           maxlength="20">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            
                                            <!-- Fecha de nacimiento -->
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="fecha_nac">Fecha de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                           value="<?php echo $estudiante->fecha_nac ?? ''; ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_nombre">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                                                           value="<?php echo htmlspecialchars($estudiante->primer_nombre ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="segundo_nombre">Segundo Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_nombre ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_apellido">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_apellido ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="segundo_apellido">Segundo Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_apellido ?? ''); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="sexo">Sexo <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control" id="sexo" name="sexo" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Masculino" <?php echo ($estudiante->sexo ?? '') == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                        <option value="Femenino" <?php echo ($estudiante->sexo ?? '') == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="lugar_nac">Lugar de Nacimiento <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                           value="<?php echo htmlspecialchars($estudiante->lugar_nac ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="telefono">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono ?? ''); ?>" required maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="telefono_hab">Teléfono Habitación</label>
                                                    <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_hab ?? ''); ?>" maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="correo">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="correo" name="correo"
                                                           value="<?php echo htmlspecialchars($estudiante->correo ?? ''); ?>">
                                                    <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dirección del Estudiante -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-map-marker-alt"></i> Dirección del Estudiante
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
                                                                $selected = ($estudiante->id_parroquia ?? '') == $parroquia['id_parroquia'] ? 'selected' : '';
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
                                                           value="<?php echo htmlspecialchars($estudiante->direccion ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="calle">Calle</label>
                                                    <input type="text" class="form-control" id="calle" name="calle"
                                                           value="<?php echo htmlspecialchars($estudiante->calle ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="casa">Casa/Apto</label>
                                                    <input type="text" class="form-control" id="casa" name="casa"
                                                           value="<?php echo htmlspecialchars($estudiante->casa ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sección de Salud del Estudiante -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-heartbeat"></i> Información de Salud del Estudiante
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="patologias">Patologías/Enfermedades</label>
                                                    <select class="form-control select2" id="patologias" name="patologias[]" multiple="multiple" style="width: 100%;">
                                                        <option value="">Seleccione patologías...</option>
                                                        <?php
                                                        if (isset($patologias) && $patologias) {
                                                            while ($patologia = $patologias->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = in_array($patologia['id_patologia'], $patologias_seleccionadas) ? 'selected' : '';
                                                                echo "<option value='{$patologia['id_patologia']}' {$selected}>{$patologia['nom_patologia']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>Error al cargar patologías</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="form-text text-muted">Seleccione las patologías que presente el estudiante (opcional)</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="discapacidades">Discapacidades</label>
                                                    <select class="form-control select2" id="discapacidades" name="discapacidades[]" multiple="multiple" style="width: 100%;">
                                                        <option value="">Seleccione discapacidades...</option>
                                                        <?php
                                                        if (isset($discapacidades) && $discapacidades) {
                                                            while ($discapacidad = $discapacidades->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = in_array($discapacidad['id_discapacidad'], $discapacidades_seleccionadas) ? 'selected' : '';
                                                                echo "<option value='{$discapacidad['id_discapacidad']}' {$selected}>{$discapacidad['nom_discapacidad']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>Error al cargar discapacidades</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="form-text text-muted">Seleccione las discapacidades que presente el estudiante (opcional)</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Datos del Representante -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-user-tie"></i> Datos del Representante
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_nombre_rep">Primer Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_nombre_rep" name="primer_nombre_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_nombre_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="segundo_nombre_rep">Segundo Nombre <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="segundo_nombre_rep" name="segundo_nombre_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_nombre_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="primer_apellido_rep">Primer Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="primer_apellido_rep" name="primer_apellido_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_apellido_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="segundo_apellido_rep">Segundo Apellido <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="segundo_apellido_rep" name="segundo_apellido_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_apellido_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="cedula_rep">Cédula del Representante <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="cedula_rep" name="cedula_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->cedula_rep ?? ''); ?>" required
                                                           maxlength="20">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="id_parentesco">Parentesco <span class="text-danger">* (Obligatorio)</span></label>
                                                    <select class="form-control" id="id_parentesco" name="id_parentesco" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php
                                                        if (isset($parentescos) && $parentescos) {
                                                            while ($parentesco = $parentescos->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = ($estudiante->id_parentesco ?? '') == $parentesco['id_parentesco'] ? 'selected' : '';
                                                                echo "<option value='{$parentesco['id_parentesco']}' {$selected}>{$parentesco['parentesco']}</option>";
                                                            }
                                                        } else {
                                                            echo "<option value=''>Error al cargar parentescos</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group campo-obligatorio">
                                                    <label for="telefono_rep">Teléfono Móvil <span class="text-danger">* (Obligatorio)</span></label>
                                                    <input type="text" class="form-control" id="telefono_rep" name="telefono_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_rep ?? ''); ?>" required maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="telefono_hab_rep">Teléfono Habitación</label>
                                                    <input type="text" class="form-control" id="telefono_hab_rep" name="telefono_hab_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_hab_rep ?? ''); ?>" maxlength="11">
                                                    <small class="form-text text-muted">Solo se permiten números</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="correo_rep">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="correo_rep" name="correo_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->correo_rep ?? ''); ?>">
                                                    <small class="form-text text-muted">Formato: usuario@dominio.com</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="id_profesion_rep">Profesión</label>
                                                    <select class="form-control select2" id="id_profesion_rep" name="id_profesion_rep" style="width: 100%;">
                                                        <option value="">Seleccione una profesión...</option>
                                                        <?php
                                                        if (isset($profesiones) && $profesiones) {
                                                            while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = ($estudiante->id_profesion_rep ?? '') == $profesion['id_profesion'] ? 'selected' : '';
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
                                                <div class="form-group">
                                                    <label for="ocupacion_rep">Ocupación</label>
                                                    <input type="text" class="form-control" id="ocupacion_rep" name="ocupacion_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->ocupacion_rep ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lugar_trabajo_rep">Lugar de Trabajo</label>
                                                    <input type="text" class="form-control" id="lugar_trabajo_rep" name="lugar_trabajo_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->lugar_trabajo_rep ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save"></i> Actualizar Estudiante
                                        </button>
                                        <a href="estudiantes_list.php" class="btn btn-default">
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
        $(function () {
            // Inicializar Select2 para todos los selects
            $('.select2').select2({
                theme: 'bootstrap4',
                width: 'resolve'
            });

            // Inicializar específicamente para selects múltiples
            $('#patologias, #discapacidades').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione...',
                allowClear: true,
                width: '100%'
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
            $('#primer_nombre, #segundo_nombre, #primer_apellido, #segundo_apellido, #lugar_nac, #direccion, #calle, #casa, #primer_nombre_rep, #segundo_nombre_rep, #primer_apellido_rep, #segundo_apellido_rep, #ocupacion_rep, #lugar_trabajo_rep').on('input', function() {
                this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
                convertirMayusculas(this);
            });

            // Solo números (para teléfonos y cédulas)
            $('#cedula, #cedula_rep, #telefono, #telefono_hab, #telefono_rep, #telefono_hab_rep').on('input', function() {
                this.value = this.value.replace(/\D/g, '');
            });

            // Validación de correo electrónico
            $('#correo, #correo_rep').on('blur', function() {
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
            $('#formEstudiante').on('submit', function(e) {
                let isValid = true;
                let mensajesError = [];

                // Campos obligatorios del estudiante
                const camposObligatoriosEstudiante = {
                    'nacionalidad': 'Nacionalidad',
                    'cedula': 'Cédula',
                    'fecha_nac': 'Fecha de Nacimiento',
                    'primer_nombre': 'Primer Nombre',
                    'segundo_nombre': 'Segundo Nombre',
                    'primer_apellido': 'Primer Apellido',
                    'segundo_apellido': 'Segundo Apellido',
                    'sexo': 'Sexo',
                    'lugar_nac': 'Lugar de Nacimiento',
                    'telefono': 'Teléfono Móvil'
                };

                // Campos obligatorios del representante
                const camposObligatoriosRepresentante = {
                    'primer_nombre_rep': 'Primer Nombre del Representante',
                    'segundo_nombre_rep': 'Segundo Nombre del Representante',
                    'primer_apellido_rep': 'Primer Apellido del Representante',
                    'segundo_apellido_rep': 'Segundo Apellido del Representante',
                    'cedula_rep': 'Cédula del Representante',
                    'id_parentesco': 'Parentesco',
                    'telefono_rep': 'Teléfono Móvil del Representante'
                };

                // Validar campos obligatorios del estudiante
                for (const [campo, nombre] of Object.entries(camposObligatoriosEstudiante)) {
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

                // Validar campos obligatorios del representante
                for (const [campo, nombre] of Object.entries(camposObligatoriosRepresentante)) {
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
                const telefonoRep = $('#telefono_rep').val();
                const telefonoHabRep = $('#telefono_hab_rep').val();

                if (telefono && !/^\d+$/.test(telefono)) {
                    mensajesError.push('El teléfono móvil del estudiante debe contener solo números');
                    isValid = false;
                }

                if (telefonoHab && !/^\d+$/.test(telefonoHab)) {
                    mensajesError.push('El teléfono de habitación del estudiante debe contener solo números');
                    isValid = false;
                }

                if (telefonoRep && !/^\d+$/.test(telefonoRep)) {
                    mensajesError.push('El teléfono móvil del representante debe contener solo números');
                    isValid = false;
                }

                if (telefonoHabRep && !/^\d+$/.test(telefonoHabRep)) {
                    mensajesError.push('El teléfono de habitación del representante debe contener solo números');
                    isValid = false;
                }

                // Validar correos electrónicos
                const correo = $('#correo').val();
                const correoRep = $('#correo_rep').val();

                if (correo && !isValidEmail(correo)) {
                    mensajesError.push('Por favor, ingrese un correo electrónico válido para el estudiante (formato: usuario@dominio.com)');
                    isValid = false;
                }

                if (correoRep && !isValidEmail(correoRep)) {
                    mensajesError.push('Por favor, ingrese un correo electrónico válido para el representante (formato: usuario@dominio.com)');
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
</body>
</html>