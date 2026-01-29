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
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("Location: representantes_list.php");
    exit();
}

// require_once '/xampp/htdocs/final/global/check_permissions.php';

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// PermissionManager::requirePermission();
include_once("/xampp/htdocs/final/layout/layaout1.php");



?>
<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Información del Representante</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="representantes_list.php">Representantes</a></li>
                        <li class="breadcrumb-item active">Ver</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user-tie"></i>
                                <?php echo htmlspecialchars($representante->primer_nombre . ' ' . $representante->primer_apellido); ?>
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-<?php echo $representante->estatus == 1 ? 'success' : 'danger'; ?>">
                                    <?php echo $representante->estatus == 1 ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Información Personal -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-id-card"></i> Información Personal
                                    </h5>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <strong>Nombre Completo:</strong><br>
                                    <?php echo htmlspecialchars($representante->primer_nombre . ' ' .
                                        ($representante->segundo_nombre ? $representante->segundo_nombre . ' ' : '') .
                                        $representante->primer_apellido . ' ' .
                                        ($representante->segundo_apellido ? $representante->segundo_apellido : '')); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Cédula:</strong><br>
                                    <?php echo htmlspecialchars($representante->cedula); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Fecha de Nacimiento:</strong><br>
                                    <?php echo date('d/m/Y', strtotime($representante->fecha_nac)); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Edad:</strong><br>
                                    <?php
                                    $fecha_nac = new DateTime($representante->fecha_nac);
                                    $hoy = new DateTime();
                                    $edad = $hoy->diff($fecha_nac)->y;
                                    echo $edad . ' años';
                                    ?>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <strong>Sexo:</strong><br>
                                    <?php echo htmlspecialchars($representante->sexo); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Nacionalidad:</strong><br>
                                    <?php echo htmlspecialchars($representante->nacionalidad); ?>
                                </div>
                                <div class="col-md-3">
                                    <strong>Lugar de Nacimiento:</strong><br>
                                    <?php echo htmlspecialchars($representante->lugar_nac ?? 'No especificado'); ?>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <strong>Teléfono Móvil:</strong><br>
                                    <?php echo htmlspecialchars($representante->telefono); ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Teléfono Habitación:</strong><br>
                                    <?php echo htmlspecialchars($representante->telefono_hab ?? 'No especificado'); ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Correo Electrónico:</strong><br>
                                    <?php echo htmlspecialchars($representante->correo ?? 'No especificado'); ?>
                                </div>
                            </div>

                            <!-- Información Profesional -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-briefcase"></i> Información Profesional
                                    </h5>
                                    <hr>
                                </div>
                                <div class="col-md-4">
                                    <strong>Profesión:</strong><br>
                                    <?php
                                    try {
                                        $database = new Conexion();
                                        $db = $database->conectar();
                                        if ($db) {
                                            $controller_profesiones = new RepresentanteController($db);
                                            $profesiones = $controller_profesiones->obtenerProfesiones();
                                            while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                if ($profesion['id_profesion'] == $representante->id_profesion) {
                                                    echo htmlspecialchars($profesion['profesion']);
                                                    break;
                                                }
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo 'No especificada';
                                    }
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Ocupación:</strong><br>
                                    <?php echo htmlspecialchars($representante->ocupacion ?? 'No especificada'); ?>
                                </div>
                                <div class="col-md-4">
                                    <strong>Lugar de Trabajo:</strong><br>
                                    <?php echo htmlspecialchars($representante->lugar_trabajo ?? 'No especificado'); ?>
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-map-marker-alt"></i> Dirección
                                    </h5>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <strong>Dirección Completa:</strong><br>
                                    <?php
                                    echo htmlspecialchars($representante->direccion);
                                    if ($representante->calle) echo ', Calle: ' . htmlspecialchars($representante->calle);
                                    if ($representante->casa) echo ', Casa/Apto: ' . htmlspecialchars($representante->casa);
                                    ?>
                                </div>
                            </div>

                            <!-- Estudiantes a cargo -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5 class="text-primary">
                                        <i class="fas fa-user-graduate"></i> Estudiantes a Cargo
                                    </h5>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <?php
                                    try {
                                        $database = new Conexion();
                                        $db = $database->conectar();
                                        if ($db) {
                                            $query_estudiantes = "SELECT 
                                                                    e.id_estudiante,
                                                                    p.primer_nombre,
                                                                    p.segundo_nombre,
                                                                    p.primer_apellido,
                                                                    p.segundo_apellido,
                                                                    p.cedula,
                                                                    par.parentesco
                                                                FROM estudiantes_representantes er
                                                                INNER JOIN estudiantes e ON er.id_estudiante = e.id_estudiante
                                                                INNER JOIN personas p ON e.id_persona = p.id_persona
                                                                INNER JOIN parentesco par ON er.id_parentesco = par.id_parentesco
                                                                WHERE er.id_representante = ? AND er.estatus = 1 AND e.estatus = 1
                                                                ORDER BY p.primer_apellido, p.primer_nombre";

                                            $stmt_estudiantes = $db->prepare($query_estudiantes);
                                            $stmt_estudiantes->bindParam(1, $id_representante);
                                            $stmt_estudiantes->execute();

                                            if ($stmt_estudiantes->rowCount() > 0) {
                                                echo '<div class="table-responsive"><table class="table table-sm table-bordered">';
                                                echo '<thead><tr><th>Nombre</th><th>Cédula</th><th>Parentesco</th></tr></thead><tbody>';

                                                while ($estudiante = $stmt_estudiantes->fetch(PDO::FETCH_ASSOC)) {
                                                    $nombreCompleto = $estudiante['primer_nombre'] . ' ' .
                                                        ($estudiante['segundo_nombre'] ? $estudiante['segundo_nombre'] . ' ' : '') .
                                                        $estudiante['primer_apellido'] . ' ' .
                                                        ($estudiante['segundo_apellido'] ? $estudiante['segundo_apellido'] : '');

                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($nombreCompleto) . "</td>";
                                                    echo "<td>" . htmlspecialchars($estudiante['cedula']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($estudiante['parentesco']) . "</td>";
                                                    echo "</tr>";
                                                }

                                                echo '</tbody></table></div>';
                                            } else {
                                                echo '<div class="alert alert-info">No tiene estudiantes asignados.</div>';
                                            }
                                        }
                                    } catch (Exception $e) {
                                        echo '<div class="alert alert-warning">Error al cargar estudiantes</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <a href="representante_editar.php?id=<?php echo $id_representante; ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="representantes_list.php" class="btn btn-default">
                                <i class="fas fa-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>