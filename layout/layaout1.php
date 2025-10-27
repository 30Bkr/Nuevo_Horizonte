<?php
include_once('/xampp/htdocs/final/global/utils.php');
include_once('/xampp/htdocs/final/app/users.php');


session_start();
$esto = $_SESSION['sesion_email'];
$user = new Usuarios;
$info = $user->info($esto);
// $mira = $_SESSION['nombre'];
if (isset($_SESSION['sesion_email'])) {
  // echo $info[0]->apellido;
  $_SESSION['mensaje'] = "Bienvenido al sistema Nuevo Horizonte nerd";
  $_SESSION['icono'] = "success";
} else {
  echo "EL usuario no paso por el login";
  $_SESSION['mensaje'] = "Es necesario iniciar sesión";
  $_SESSION['icono'] = "error";
  header('Location: ' . URL . '/login/index.php');
}
// session_start();
// if (isset($_SESSION['email'])) {
//   echo "EL usuario paso por el login";
// } else {
//   echo "EL usuario no paso por el login";
//   header('Location: ' . URL . '/login/index.php');
// }
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= NAME_PROJECT; ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= URL; ?>/public/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= URL; ?>/public/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?= URL; ?>/admin/index.php" class="nav-link">Inicio</a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index3.html" class="brand-link">
        <img src="<?= URL; ?>/public/images/perfil.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= NAME_PROJECT; ?></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= URL; ?>/public/images/perfil.svg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo $info[0]->nombre . " " . $info[0]->apellido ?></a>
          </div>
        </div>

        <!-- <!-- Sidebar Menu --90i        <nav class="mt-2"> -->
        <ul class=" mt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <?php
          if (true) { ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-person-lines-fill">
                  <img src="<?= URL; ?>/public/images/roles.svg" alt="Inscripcion">

                </i>
                <p>
                  Roles
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/roles/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de roles</p>
                  </a>
                </li>
              </ul>
            </li>

          <?php
          }
          ?>

          <?php
          if (true) { ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-mortarboard-fill">
                  <img src="<?= URL; ?>/public/images/inscripcion.svg" alt="Inscripcion">

                </i>
                <p>
                  Incripciones
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/inscripciones/primaria.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Primaria</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/inscripciones/secundaria.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Secundaria</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/inscripciones/registro.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php
          }
          ?>

          <?php if (true) { ?>

            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-person-rolodex">
                  <img src="<?= URL; ?>/public/images/profesor.svg" alt="Inscripcion">
                </i>

                <p>
                  Docentes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/docentes/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de docentes</p>
                  </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="/project/admin/roles/index.php" class="nav-link active">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Asignar materias</p>
                    </a>
                  </li> -->
              </ul>
            </li>

          <?php
          }
          ?>

          <?php if (true) { ?>
            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-card-list">
                  <img src="<?= URL; ?>/public/images/secciones.svg" alt="Inscripcion">

                </i>
                <p>
                  Cursos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/cursos/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado de cursos</p>
                  </a>
                </li>
              </ul>
            </li>

          <?php
          }
          ?>
          <li class="nav-item">
            <a href="<?= URL; ?>/login/logout.php" class="nav-link active" style="background-color: #c40c0cff;">
              <!-- <img src="../../public/images/door-open.svg" alt="" class="nav-icon"> -->
              <i class="nav-icon fas bi bi-door-open">
                <img src="<?= URL; ?>/public/images/salir.svg" alt="Inscripcion">

              </i>
              <p>Cerrar sesión</p>
            </a>
          </li>
        </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>