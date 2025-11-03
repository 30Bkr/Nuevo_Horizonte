<?php
    // Iniciamos sesión en el header para todas las páginas de admin
    session_start();
    
    // Seguridad: Verificamos si hay una sesión activa
    if (!isset($_SESSION['id_usuario'])) {
        header("Location: ../../login/"); // Redirigir al login
        exit;
    }
    
    // Opcional: Verificar rol (si es necesario en todas las páginas)
    // if ($_SESSION['id_rol'] != 1) {
    //    echo "Acceso Denegado";
    //    exit;
    // }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Horizonte - Panel Administrativo</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-alpha1/dist/css/adminlte.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-bs-toggle="dropdown" href="#">
                    <i class="fa fa-user me-1"></i>
                    <?php echo htmlspecialchars($_SESSION['nom_usuario']); ?>
                    <i class="fa fa-caret-down ms-1"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <a href="#" class="dropdown-item">
                        <i class="fa fa-user-circle me-2"></i> Mi Perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="../../login/logout.php" class="dropdown-item">
                        <i class="fa fa-sign-out-alt me-2"></i> Salir del Sistema
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="../index.php" class="brand-link">
            <span class="brand-text font-weight-light">Nuevo Horizonte</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    
                    <li class="nav-item">
                        <a href="../dashboard.php" class="nav-link"> <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Tablero</p>
                        </a>
                    </li>
                    
                    <li class="nav-item menu-open"> <a href="#" class="nav-link active"> <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Administración
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="usuarios_listado.php" class="nav-link active"> <i class="far fa-circle nav-icon"></i>
                                    <p>Gestión de Usuarios</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    </ul>
            </nav>
            </div>
        </aside>

    <div class="content-wrapper">
    