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
    <title>Ver Estudiante - Nuevo Horizonte</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
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
                    <a href="#" class="nav-link">Ver Estudiante</a>
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
                            <h1>Información del Estudiante</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="estudiantes_list.php">Estudiantes</a></li>
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
                                        <i class="fas fa-user-graduate"></i> 
                                        <?php echo htmlspecialchars($estudiante->primer_nombre . ' ' . $estudiante->primer_apellido); ?>
                                    </h3>
                                    <div class="card-tools">
                                        <span class="badge badge-<?php echo $estudiante->estatus == 1 ? 'success' : 'danger'; ?>">
                                            <?php echo $estudiante->estatus == 1 ? 'Activo' : 'Inactivo'; ?>
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
                                            <?php echo htmlspecialchars($estudiante->primer_nombre . ' ' . 
                                                ($estudiante->segundo_nombre ? $estudiante->segundo_nombre . ' ' : '') . 
                                                $estudiante->primer_apellido . ' ' . 
                                                ($estudiante->segundo_apellido ? $estudiante->segundo_apellido : '')); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Cédula:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->cedula); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Fecha de Nacimiento:</strong><br>
                                            <?php echo date('d/m/Y', strtotime($estudiante->fecha_nac)); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Edad:</strong><br>
                                            <?php 
                                                $fecha_nac = new DateTime($estudiante->fecha_nac);
                                                $hoy = new DateTime();
                                                $edad = $hoy->diff($fecha_nac)->y;
                                                echo $edad . ' años';
                                            ?>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <strong>Sexo:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->sexo); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Nacionalidad:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->nacionalidad); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Lugar de Nacimiento:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->lugar_nac ?? 'No especificado'); ?>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <strong>Teléfono Móvil:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->telefono); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Teléfono Habitación:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->telefono_hab ?? 'No especificado'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Correo Electrónico:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->correo ?? 'No especificado'); ?>
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
                                                echo htmlspecialchars($estudiante->direccion);
                                                if ($estudiante->calle) echo ', Calle: ' . htmlspecialchars($estudiante->calle);
                                                if ($estudiante->casa) echo ', Casa/Apto: ' . htmlspecialchars($estudiante->casa);
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Sección de Salud -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-heartbeat"></i> Información de Salud
                                            </h5>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Patologías/Enfermedades:</strong><br>
                                            <?php
                                            try {
                                                $database = new Conexion();
                                                $db = $database->conectar();
                                                if ($db) {
                                                    $query_patologias = "SELECT p.nom_patologia 
                                                                    FROM estudiantes_patologias ep 
                                                                    INNER JOIN patologias p ON ep.id_patologia = p.id_patologia 
                                                                    WHERE ep.id_estudiante = ? AND ep.estatus = 1";
                                                    $stmt_patologias = $db->prepare($query_patologias);
                                                    $stmt_patologias->bindParam(1, $id_estudiante);
                                                    $stmt_patologias->execute();
                                                    
                                                    $patologias = [];
                                                    while ($patologia = $stmt_patologias->fetch(PDO::FETCH_ASSOC)) {
                                                        $patologias[] = $patologia['nom_patologia'];
                                                    }
                                                    
                                                    if (!empty($patologias)) {
                                                        echo implode(', ', array_map('htmlspecialchars', $patologias));
                                                    } else {
                                                        echo 'Ninguna registrada';
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                echo 'Error al cargar patologías';
                                            }
                                            ?>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <strong>Discapacidades:</strong><br>
                                            <?php
                                            try {
                                                $database = new Conexion();
                                                $db = $database->conectar();
                                                if ($db) {
                                                    $query_discapacidades = "SELECT d.nom_discapacidad 
                                                                        FROM estudiantes_discapacidades ed 
                                                                        INNER JOIN discapacidades d ON ed.id_discapacidad = d.id_discapacidad 
                                                                        WHERE ed.id_estudiante = ? AND ed.estatus = 1";
                                                    $stmt_discapacidades = $db->prepare($query_discapacidades);
                                                    $stmt_discapacidades->bindParam(1, $id_estudiante);
                                                    $stmt_discapacidades->execute();
                                                    
                                                    $discapacidades = [];
                                                    while ($discapacidad = $stmt_discapacidades->fetch(PDO::FETCH_ASSOC)) {
                                                        $discapacidades[] = $discapacidad['nom_discapacidad'];
                                                    }
                                                    
                                                    if (!empty($discapacidades)) {
                                                        echo implode(', ', array_map('htmlspecialchars', $discapacidades));
                                                    } else {
                                                        echo 'Ninguna registrada';
                                                    }
                                                }
                                            } catch (Exception $e) {
                                                echo 'Error al cargar discapacidades';
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <!-- Representante -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <h5 class="text-primary">
                                                <i class="fas fa-user-tie"></i> Datos del Representante
                                            </h5>
                                            <hr>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Nombre Completo:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->primer_nombre_rep . ' ' . 
                                                ($estudiante->segundo_nombre_rep ? $estudiante->segundo_nombre_rep . ' ' : '') . 
                                                $estudiante->primer_apellido_rep . ' ' . 
                                                ($estudiante->segundo_apellido_rep ? $estudiante->segundo_apellido_rep : '')); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Cédula:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->cedula_rep); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Parentesco:</strong><br>
                                            <?php 
                                                try {
                                                    $database = new Conexion();
                                                    $db = $database->conectar();
                                                    if ($db) {
                                                        $controller_parentescos = new EstudianteController($db);
                                                        $parentescos = $controller_parentescos->obtenerParentescos();
                                                        while ($parentesco = $parentescos->fetch(PDO::FETCH_ASSOC)) {
                                                            if ($parentesco['id_parentesco'] == $estudiante->id_parentesco) {
                                                                echo htmlspecialchars($parentesco['parentesco']);
                                                                break;
                                                            }
                                                        }
                                                    }
                                                } catch (Exception $e) {
                                                    echo 'No especificado';
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <strong>Teléfono Móvil:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->telefono_rep); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Teléfono Habitación:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->telefono_hab_rep ?? 'No especificado'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Correo Electrónico:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->correo_rep ?? 'No especificado'); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Profesión:</strong><br>
                                            <?php 
                                                if ($estudiante->id_profesion_rep) {
                                                    try {
                                                        $database = new Conexion();
                                                        $db = $database->conectar();
                                                        if ($db) {
                                                            $controller_profesiones = new EstudianteController($db);
                                                            $profesiones = $controller_profesiones->obtenerProfesiones();
                                                            while ($profesion = $profesiones->fetch(PDO::FETCH_ASSOC)) {
                                                                if ($profesion['id_profesion'] == $estudiante->id_profesion_rep) {
                                                                    echo htmlspecialchars($profesion['profesion']);
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    } catch (Exception $e) {
                                                        echo 'No especificada';
                                                    }
                                                } else {
                                                    echo 'No especificada';
                                                }
                                            ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Ocupación:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->ocupacion_rep ?? 'No especificada'); ?>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Lugar de Trabajo:</strong><br>
                                            <?php echo htmlspecialchars($estudiante->lugar_trabajo_rep ?? 'No especificado'); ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <a href="estudiante_editar.php?id=<?php echo $id_estudiante; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <a href="estudiantes_list.php" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Volver al Listado
                                    </a>
                                </div>
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
    <!-- AdminLTE App -->
    <script src="/final/public/dist/js/adminlte.min.js"></script>
</body>
</html>