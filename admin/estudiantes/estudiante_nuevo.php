<?php
session_start();

// Incluir archivos
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $database = new Conexion();
        $db = $database->conectar();

        if (!$db) {
            throw new Exception("Error de conexión a la base de datos");
        }

        $controller = new EstudianteController($db);

        if ($controller->crear($_POST)) {
            $_SESSION['success'] = "Estudiante creado exitosamente";
            header("Location: estudiantes_list.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
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
                    <h1>Nuevo Estudiante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="estudiantes_list.php">Estudiantes</a></li>
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
                    <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Datos del Estudiante</h3>
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
                                                value="<?php echo $_POST['primer_nombre'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_nombre">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre"
                                                value="<?php echo $_POST['segundo_nombre'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="primer_apellido">Primer Apellido *</label>
                                            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido"
                                                value="<?php echo $_POST['primer_apellido'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_apellido">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido"
                                                value="<?php echo $_POST['segundo_apellido'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cedula">Cédula *</label>
                                            <input type="text" class="form-control" id="cedula" name="cedula"
                                                value="<?php echo $_POST['cedula'] ?? ''; ?>" required maxlength="8">
                                            <small class="form-text text-muted">Solo números, mínimo 6 dígitos</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="fecha_nac">Fecha de Nacimiento *</label>
                                            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac"
                                                value="<?php echo $_POST['fecha_nac'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="sexo">Sexo *</label>
                                            <select class="form-control" id="sexo" name="sexo" required>
                                                <option value="">Seleccione...</option>
                                                <option value="Masculino" <?php echo ($_POST['sexo'] ?? '') == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                                                <option value="Femenino" <?php echo ($_POST['sexo'] ?? '') == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="nacionalidad">Nacionalidad *</label>
                                            <input type="text" class="form-control" id="nacionalidad" name="nacionalidad"
                                                value="<?php echo $_POST['nacionalidad'] ?? 'Venezolano'; ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="lugar_nac">Lugar de Nacimiento</label>
                                            <input type="text" class="form-control" id="lugar_nac" name="lugar_nac"
                                                value="<?php echo $_POST['lugar_nac'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono">Teléfono Móvil *</label>
                                            <input type="text" class="form-control" id="telefono" name="telefono"
                                                value="<?php echo $_POST['telefono'] ?? ''; ?>" required maxlength="11">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="telefono_hab">Teléfono Habitación</label>
                                            <input type="text" class="form-control" id="telefono_hab" name="telefono_hab"
                                                value="<?php echo $_POST['telefono_hab'] ?? ''; ?>" maxlength="11">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo" name="correo"
                                                value="<?php echo $_POST['correo'] ?? ''; ?>">
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
                                                        $controller = new EstudianteController($db);
                                                        $parroquias = $controller->obtenerParroquias();
                                                        while ($parroquia = $parroquias->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = ($_POST['id_parroquia'] ?? '') == $parroquia['id_parroquia'] ? 'selected' : '';
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
                                                value="<?php echo $_POST['direccion'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="calle">Calle</label>
                                            <input type="text" class="form-control" id="calle" name="calle"
                                                value="<?php echo $_POST['calle'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="casa">Casa/Apto</label>
                                            <input type="text" class="form-control" id="casa" name="casa"
                                                value="<?php echo $_POST['casa'] ?? ''; ?>">
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
                                                value="<?php echo $_POST['primer_nombre_rep'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_nombre_rep">Segundo Nombre</label>
                                            <input type="text" class="form-control" id="segundo_nombre_rep" name="segundo_nombre_rep"
                                                value="<?php echo $_POST['segundo_nombre_rep'] ?? ''; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="primer_apellido_rep">Primer Apellido *</label>
                                            <input type="text" class="form-control" id="primer_apellido_rep" name="primer_apellido_rep"
                                                value="<?php echo $_POST['primer_apellido_rep'] ?? ''; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="segundo_apellido_rep">Segundo Apellido</label>
                                            <input type="text" class="form-control" id="segundo_apellido_rep" name="segundo_apellido_rep"
                                                value="<?php echo $_POST['segundo_apellido_rep'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="cedula_rep">Cédula del Representante *</label>
                                            <input type="text" class="form-control" id="cedula_rep" name="cedula_rep"
                                                value="<?php echo $_POST['cedula_rep'] ?? ''; ?>" required maxlength="8">
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
                                                        $controller = new EstudianteController($db);
                                                        $parentescos = $controller->obtenerParentescos();
                                                        while ($parentesco = $parentescos->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = ($_POST['id_parentesco'] ?? '') == $parentesco['id_parentesco'] ? 'selected' : '';
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
                                                value="<?php echo $_POST['telefono_rep'] ?? ''; ?>" required maxlength="11">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="telefono_hab_rep">Teléfono Habitación</label>
                                            <input type="text" class="form-control" id="telefono_hab_rep" name="telefono_hab_rep"
                                                value="<?php echo $_POST['telefono_hab_rep'] ?? ''; ?>" maxlength="11">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="correo_rep">Correo Electrónico</label>
                                            <input type="email" class="form-control" id="correo_rep" name="correo_rep"
                                                value="<?php echo $_POST['correo_rep'] ?? ''; ?>">
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
                                                        $controller = new EstudianteController($db);
                                                        $profesiones = $controller->obtenerProfesiones();
                                                        while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                            $selected = ($_POST['id_profesion_rep'] ?? '') == $profesion['id_profesion'] ? 'selected' : '';
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
                                                value="<?php echo $_POST['ocupacion_rep'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lugar_trabajo_rep">Lugar de Trabajo</label>
                                            <input type="text" class="form-control" id="lugar_trabajo_rep" name="lugar_trabajo_rep"
                                                value="<?php echo $_POST['lugar_trabajo_rep'] ?? ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Guardar Estudiante
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


<script>
    $(function() {
        // Inicializar Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Validación de solo números para cédulas y teléfonos
        $('#cedula, #cedula_rep, #telefono, #telefono_hab, #telefono_rep, #telefono_hab_rep').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validación de formulario
        $('#formEstudiante').on('submit', function() {
            const cedula = $('#cedula').val();
            const cedulaRep = $('#cedula_rep').val();

            if (cedula.length < 6) {
                alert('La cédula del estudiante debe tener al menos 6 dígitos');
                return false;
            }

            if (cedulaRep.length < 6) {
                alert('La cédula del representante debe tener al menos 6 dígitos');
                return false;
            }

            if (cedula === cedulaRep) {
                alert('El estudiante no puede ser su propio representante');
                return false;
            }

            return true;
        });
    });
</script>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>