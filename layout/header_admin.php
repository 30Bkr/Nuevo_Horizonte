<?php
    // Iniciamos sesión en el header
    session_start();
    
    // Seguridad: Verificamos si hay una sesión activa
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: /nuevo_horizonte/login/"); // Redirigir al login
        exit;
    }

    // Definimos las variables que usa tu plantilla
    $nombre_usuario_sesion = $_SESSION['nom_usuario'] ?? 'Usuario';
    $rol_usuario_sesion = $_SESSION['rol_nombre'] ?? 'Sin Rol';

    // Definimos una constante para las rutas absolutas (¡Mejora recomendada!)
    define('BASE_URL', '/nuevo_horizonte');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración | Nuevo Horizonte</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
</head>
<body class="hold-transition sidebar-mini"> 
<div class="wrapper">
    
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" role="button">
                    <i class="fas fa-user-circle"></i>
                    Hola, <strong><?php echo htmlspecialchars($nombre_usuario_sesion); ?></strong>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/login/logout.php" role="button">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="brand-link">
             <span class="brand-text font-weight-light">Nuevo Horizonte</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block"><?php echo htmlspecialchars($nombre_usuario_sesion); ?></a>
                    <span class="text-white-50 small"><?php echo htmlspecialchars($rol_usuario_sesion); ?></span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="nav-link active"> <i class="nav-icon bi bi-columns-gap"></i>
                            <p>Tablero</p>
                        </a>
                    </li>

                    <?php if ($rol_usuario_sesion == 'ADMINISTRADOR'): // Control de acceso por Rol ?>
                    <li class="nav-header">ADMINISTRACIÓN</li>
                    <li class="nav-item"> <a href="#" class="nav-link"> <i class="nav-icon bi bi-person-fill-gear"></i>
                            <p>
                                Gestión de Usuarios
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo BASE_URL; ?>/admin/usuarios/usuarios_crear.php" class="nav-link">
                                    <i class="nav-icon bi bi-person-fill-add"></i>
                                    <p>Crear</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo BASE_URL; ?>/admin/usuarios/usuarios_listado.php" class="nav-link">
                                    <i class="nav-icon bi bi-person-lines-fill"></i>
                                    <p>Listado</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <li class="nav-header">INSCRIPCIONES</li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-mortarboard-fill"></i>
                            <p>
                                Gestión de Matriculas
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon bi bi-file-earmark-person-fill"></i>
                            <p>
                                Proceso de Inscripción
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="<?php echo BASE_URL; ?>/login/logout.php" class="nav-link">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>Salir</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
    