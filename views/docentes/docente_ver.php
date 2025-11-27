<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';

$database = new Conexion();
$db = $database->conectar();

$docente = new Docente($db);

// Obtener datos del docente a visualizar
$docente_encontrado = false;
if (isset($_GET['id'])) {
    $docente_encontrado = $docente->obtenerPorId($_GET['id']);
}

if (!$docente_encontrado) {
    $_SESSION['error'] = "Docente no encontrado";
    header("Location: docentes_list.php");
    exit();
}

// Obtener nombre de la profesión
$profesion_nombre = 'No especificado';
if ($docente->id_profesion) {
    $profesiones = $docente->obtenerProfesiones();
    while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id_profesion'] == $docente->id_profesion) {
            $profesion_nombre = $row['profesion'];
            break;
        }
    }
}

// Obtener nombre de la parroquia
$parroquia_completa = 'No especificado';
if ($docente->id_parroquia) {
    $parroquias = $docente->obtenerParroquias();
    while ($row = $parroquias->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id_parroquia'] == $docente->id_parroquia) {
            $parroquia_completa = $row['nom_parroquia'] . ' - ' . $row['nom_municipio'] . ' - ' . $row['nom_estado'];
            break;
        }
    }
}

// Formatear fecha de nacimiento
$fecha_nac_formateada = $docente->fecha_nac ? date('d/m/Y', strtotime($docente->fecha_nac)) : 'No especificado';

// Construir nombre completo
$nombre_completo = trim($docente->primer_nombre . ' ' . 
    ($docente->segundo_nombre ? $docente->segundo_nombre . ' ' : '') . 
    $docente->primer_apellido . ' ' . 
    ($docente->segundo_apellido ? $docente->segundo_apellido : ''));

// Función para mostrar valor o texto por defecto
function mostrarValor($valor, $textoDefecto = 'No especificado') {
    return !empty($valor) ? htmlspecialchars($valor) : $textoDefecto;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ver Docente - Nuevo Horizonte</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css">
    
    <style>
        .info-section {
            margin-bottom: 1.5rem;
        }
        .section-title {
            color: #495057;
            border-bottom: 2px solid #007bff;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        .info-group {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 40%;
        }
        .info-value {
            color: #212529;
            text-align: right;
            flex: 1;
        }
        .card-header-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .badge-estado {
            font-size: 0.85rem;
        }
        @media (max-width: 768px) {
            .info-group {
                flex-direction: column;
                align-items: flex-start;
            }
            .info-value {
                text-align: left;
                margin-top: 0.25rem;
            }
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
                <a href="docentes_list.php" class="nav-link">Docentes</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Ver Docente</a>
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
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Información del Docente</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                            <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                            <li class="breadcrumb-item active">Ver</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Mostrar mensajes de sesión -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-check"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fas fa-ban"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="card-header-actions">
                                    <h3 class="card-title mb-0">
                                        <i class="fas fa-user-tie mr-2"></i><?php echo htmlspecialchars($nombre_completo); ?>
                                    </h3>
                                    <div class="card-tools">
                                        <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-id-card mr-1"></i> Datos Personales
                                            </h5>
                                            <div class="info-group">
                                                <span class="info-label">Cédula:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->cedula); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Nacionalidad:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->nacionalidad); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Nombre Completo:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($nombre_completo); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Fecha Nacimiento:</span>
                                                <span class="info-value"><?php echo $fecha_nac_formateada; ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Lugar Nacimiento:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->lugar_nac); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Sexo:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->sexo); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-address-book mr-1"></i> Información de Contacto
                                            </h5>
                                            <div class="info-group">
                                                <span class="info-label">Teléfono Móvil:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->telefono); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Teléfono Habitación:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->telefono_hab); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Correo Electrónico:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->correo); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Profesión:</span>
                                                <span class="info-value"><?php echo htmlspecialchars($profesion_nombre); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-map-marker-alt mr-1"></i> Información de Dirección
                                            </h5>
                                            <div class="info-group">
                                                <span class="info-label">Parroquia:</span>
                                                <span class="info-value"><?php echo $parroquia_completa; ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Dirección:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->direccion); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Calle:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->calle); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Casa/Edificio:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->casa); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-section">
                                            <h5 class="section-title">
                                                <i class="fas fa-user-circle mr-1"></i> Información del Usuario
                                            </h5>
                                            <div class="info-group">
                                                <span class="info-label">Usuario:</span>
                                                <span class="info-value"><?php echo mostrarValor($docente->usuario); ?></span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Rol:</span>
                                                <span class="info-value">Docente</span>
                                            </div>
                                            <div class="info-group">
                                                <span class="info-label">Estado:</span>
                                                <span class="info-value">
                                                    <span class="badge badge-success badge-estado">Activo</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="docentes_list.php" class="btn btn-default">
                                        <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
                                    </a>
                                    <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit mr-1"></i> Editar Información
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Copyright &copy; 2025 Nuevo Horizonte.</strong>
    </footer>

</div>

<script src="/final/public/plugins/jquery/jquery.min.js"></script>
<script src="/final/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/final/public/dist/js/adminlte.min.js"></script>
</body>
</html>