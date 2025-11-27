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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="primer_nombre">Primer Nombre *</label>
                                                    <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" 
                                                           value="<?php echo htmlspecialchars($estudiante->primer_nombre); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_nombre">Segundo Nombre</label>
                                                    <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_nombre ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="primer_apellido">Primer Apellido *</label>
                                                    <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_apellido); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_apellido">Segundo Apellido</label>
                                                    <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_apellido ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="cedula">Cédula *</label>
                                                    <input type="text" class="form-control" id="cedula" name="cedula" 
                                                           value="<?php echo htmlspecialchars($estudiante->cedula); ?>" required readonly
                                                           style="background-color: #e9ecef;">
                                                    <small class="form-text text-muted">La cédula no se puede modificar</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="fecha_nac">Fecha de Nacimiento *</label>
                                                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                           value="<?php echo $estudiante->fecha_nac; ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sexo">Sexo *</label>
                                                    <select class="form-control" id="sexo" name="sexo" required>
                                                        <option value="">Seleccione...</option>
                                                        <option value="Masculino" <?php echo $estudiante->sexo == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                        <option value="Femenino" <?php echo $estudiante->sexo == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="nacionalidad">Nacionalidad *</label>
                                                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad"
                                                           value="<?php echo htmlspecialchars($estudiante->nacionalidad); ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="lugar_nac">Lugar de Nacimiento</label>
                                                    <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                           value="<?php echo htmlspecialchars($estudiante->lugar_nac ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="telefono">Teléfono Móvil *</label>
                                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono); ?>" required maxlength="11">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="telefono_hab">Teléfono Habitación</label>
                                                    <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_hab ?? ''); ?>" maxlength="11">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="correo">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="correo" name="correo"
                                                           value="<?php echo htmlspecialchars($estudiante->correo ?? ''); ?>">
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
                                                    <label for="id_parroquia">Parroquia *</label>
                                                    <select class="form-control select2" id="id_parroquia" name="id_parroquia" required>
                                                        <option value="">Seleccione una parroquia...</option>
                                                        <?php
                                                        try {
                                                            $database = new Conexion();
                                                            $db = $database->conectar();
                                                            if ($db) {
                                                                $controller_parroquias = new EstudianteController($db);
                                                                $parroquias = $controller_parroquias->obtenerParroquias();
                                                                while ($parroquia = $parroquias->fetch(PDO::FETCH_ASSOC)) {
                                                                    $selected = $estudiante->id_parroquia == $parroquia['id_parroquia'] ? 'selected' : '';
                                                                    echo "<option value='{$parroquia['id_parroquia']}' {$selected}>
                                                                            {$parroquia['nom_parroquia']} - {$parroquia['nom_municipio']} - {$parroquia['nom_estado']}
                                                                          </option>";
                                                                }
                                                            }
                                                        } catch (Exception $e) {
                                                            echo "<option value=''>Error al cargar parroquias</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="direccion">Dirección Principal *</label>
                                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                                           value="<?php echo htmlspecialchars($estudiante->direccion); ?>" required>
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

                                        <!-- Datos del Representante -->
                                        <h5 class="text-primary mb-3 mt-4">
                                            <i class="fas fa-user-tie"></i> Datos del Representante
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="primer_nombre_rep">Primer Nombre *</label>
                                                    <input type="text" class="form-control" id="primer_nombre_rep" name="primer_nombre_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_nombre_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_nombre_rep">Segundo Nombre</label>
                                                    <input type="text" class="form-control" id="segundo_nombre_rep" name="segundo_nombre_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_nombre_rep ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="primer_apellido_rep">Primer Apellido *</label>
                                                    <input type="text" class="form-control" id="primer_apellido_rep" name="primer_apellido_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->primer_apellido_rep ?? ''); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="segundo_apellido_rep">Segundo Apellido</label>
                                                    <input type="text" class="form-control" id="segundo_apellido_rep" name="segundo_apellido_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->segundo_apellido_rep ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="cedula_rep">Cédula del Representante *</label>
                                                    <input type="text" class="form-control" id="cedula_rep" name="cedula_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->cedula_rep ?? ''); ?>" required readonly
                                                           style="background-color: #e9ecef;">
                                                    <small class="form-text text-muted">La cédula del representante no se puede modificar</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="id_parentesco">Parentesco *</label>
                                                    <select class="form-control" id="id_parentesco" name="id_parentesco" required>
                                                        <option value="">Seleccione...</option>
                                                        <?php
                                                        try {
                                                            $database = new Conexion();
                                                            $db = $database->conectar();
                                                            if ($db) {
                                                                $controller_parentescos = new EstudianteController($db);
                                                                $parentescos = $controller_parentescos->obtenerParentescos();
                                                                while ($parentesco = $parentescos->fetch(PDO::FETCH_ASSOC)) {
                                                                    $selected = ($estudiante->id_parentesco ?? '') == $parentesco['id_parentesco'] ? 'selected' : '';
                                                                    echo "<option value='{$parentesco['id_parentesco']}' {$selected}>{$parentesco['parentesco']}</option>";
                                                                }
                                                            }
                                                        } catch (Exception $e) {
                                                            echo "<option value=''>Error al cargar parentescos</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="telefono_rep">Teléfono Móvil *</label>
                                                    <input type="text" class="form-control" id="telefono_rep" name="telefono_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_rep ?? ''); ?>" required maxlength="11">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="telefono_hab_rep">Teléfono Habitación</label>
                                                    <input type="text" class="form-control" id="telefono_hab_rep" name="telefono_hab_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->telefono_hab_rep ?? ''); ?>" maxlength="11">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="correo_rep">Correo Electrónico</label>
                                                    <input type="email" class="form-control" id="correo_rep" name="correo_rep"
                                                           value="<?php echo htmlspecialchars($estudiante->correo_rep ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="id_profesion_rep">Profesión</label>
                                                    <select class="form-control select2" id="id_profesion_rep" name="id_profesion_rep">
                                                        <option value="">Seleccione una profesión...</option>
                                                        <?php
                                                        try {
                                                            $database = new Conexion();
                                                            $db = $database->conectar();
                                                            if ($db) {
                                                                $controller_profesiones = new EstudianteController($db);
                                                                $profesiones = $controller_profesiones->obtenerProfesiones();
                                                                while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                                    $selected = ($estudiante->id_profesion_rep ?? '') == $profesion['id_profesion'] ? 'selected' : '';
                                                                    echo "<option value='{$profesion['id_profesion']}' {$selected}>{$profesion['profesion']}</option>";
                                                                }
                                                            }
                                                        } catch (Exception $e) {
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
            // Inicializar Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Validación de solo números para teléfonos
            $('#telefono, #telefono_hab, #telefono_rep, #telefono_hab_rep').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
</body>
</html>