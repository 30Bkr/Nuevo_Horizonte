<?php
include_once('/xampp/htdocs/final/global/utils.php');
include_once('/xampp/htdocs/final/app/users.php');
require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/protect.php';


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$esto = $_SESSION['usuario_email'];
$user = new Usuarios;
$info = $user->consultar($esto);

$nombreUsuario = $_SESSION['usuario_nombre_completo'] ?? $_SESSION['usuario_email'] ?? 'Usuario';
$rolUsuario = $_SESSION['usuario_nombre'] ?? 'Usuario';

require_once '/xampp/htdocs/final/app/conexion.php';
$conexion = new Conexion();
$pdo = $conexion->conectar();

try {
  $sql = "SELECT p.descripcion_periodo 
            FROM periodos p
            INNER JOIN globales g ON p.id_periodo = g.id_periodo
            WHERE g.es_activo = 1
            AND p.estatus = 1
            LIMIT 1";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $periodo = $stmt->fetch(PDO::FETCH_OBJ);

  $periodoTexto = $periodo ? $periodo->descripcion_periodo : "No activo";
} catch (PDOException $e) {
  error_log("Error obteniendo periodo activo: " . $e->getMessage());
  $periodoTexto = "Error";
}
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
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= URL; ?>/public/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= URL; ?>/public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="<?= URL; ?>/public/plugins/select2/css/select2.min.css">

  <link rel="stylesheet" href="<?= URL; ?>/public/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= URL; ?>/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- <link rel="stylesheet" href="/final/public/plugins/fontawesome-free/css/all.min.css"> -->
  <!-- <link rel="stylesheet" href="/final/public/dist/css/adminlte.min.css"> -->
  <!-- <link rel="stylesheet" href="/final/public/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css"> -->
</head>

<body class="hold-transition sidebar-mini">
  <?php
  // Incluir y mostrar notificaciones JUSTO DESPUÉS DE <body>
  require_once '/xampp/htdocs/final/global/notifications.php';
  Notification::show();
  ?>
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
          <span class="nav-link periodo-activo">
            <i class="fas fa-calendar-alt mr-1"></i>
            <?= htmlspecialchars($periodoTexto); ?>
          </span>
        </li>
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
            <a href="#" class="d-block"><?php echo $info[0]->usuario ?></a>
          </div>
        </div>

        <!-- <!-- Sidebar Menu --90i        <nav class="mt-2"> -->
        <ul class=" mt-2 nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <?php
          if (PermissionManager::canViewAny(['admin/configuraciones/index.php'])) { ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-person-lines-fill">
                  <!-- <img src="<?= URL; ?>/public/images/roles.svg" alt="Inscripcion"> -->
                  <i class="fas fa-cog mr-1"></i>

                </i>
                <p>
                  Configuraciones
                  <i class="right fas fa-angle-left"></i>

                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/configuraciones/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Configuraciones</p>
                  </a>
                </li>
              </ul>
            </li>

          <?php
          }
          ?>

          <!-- // Docentes  -->

          <?php if (PermissionManager::canViewAny(['views/docentes/docentes_list.php'])) { ?>

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
                  <a href="<?= URL; ?>/views/docentes/docentes_list.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
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

          <!-- Estudiantes -->

          <?php if (PermissionManager::canViewAny(['admin/estudiantes/estudiantes_list.php'])) { ?>
            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-card-list">
                  <img src="<?= URL; ?>/public/images/estudiante.svg" alt="Inscripcion">

                </i>
                <p>
                  Estudiantes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/estudiantes/estudiantes_list.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
                  </a>
                </li>
              </ul>
            </li>

          <?php
          }
          ?>


          <!-- Inscripciones -->

          <?php
          if (PermissionManager::canViewAny(['admin/inscripciones/indexf2.php'])) { ?>
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
                  <a href="<?= URL; ?>/admin/inscripciones/indexf2.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Inscripción</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/reinscripciones/reinscripcion2.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Reinscripción</p>
                  </a>
                </li>
                <!-- <li class="nav-item">
                  <a href="<?= URL; ?>/admin/inscripciones/secundaria.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Secundaria</p>
                  </a>
                </li> -->
                <!-- <li class="nav-item">
                  <a href="<?= URL; ?>/admin/inscripciones/registro.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
                  </a>
                </li> -->
              </ul>
            </li>
          <?php
          }
          ?>



          <?php if (PermissionManager::canViewAny(['views/grados/grados_list_solo_lectura.php'])) { ?>
            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-card-list">
                  <img src="<?= URL; ?>/public/images/secciones.svg" alt="Inscripcion">

                </i>
                <p>
                  Niveles
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/views/grados/grados_list_solo_lectura.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
                  </a>
                </li>
              </ul>
            </li>

          <?php
          }
          ?>

          <?php if (PermissionManager::canViewAny(['admin/roles_permisos/index.php']) && PermissionManager::isAdmin()): ?>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas bi bi-shield-lock">
                  <img src="<?= URL; ?>/public/images/shield.svg" alt="Seguridad">
                </i>
                <p>
                  Seguridad
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/roles_permisos/index.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles y Permisos</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php endif; ?>


          <?php if (PermissionManager::canViewAny(['admin/representantes/representantes_list.php'])) { ?>

            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-person-rolodex">
                  <img src="<?= URL; ?>/public/images/profesor.svg" alt="Inscripcion">
                </i>

                <p>
                  Representantes
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL; ?>/admin/representantes/representantes_list.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Listado</p>
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


          <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_rol_id'] != 1): ?>
            <li class="nav-item ">
              <a href="#" class="nav-link">
                <!-- <i class="nav-icon fas fa-tachometer-alt"></i> -->
                <i class="nav-icon fas bi bi-person-rolodex">
                  <img src="<?= URL; ?>/public/images/profesor.svg" alt="Inscripcion">
                </i>

                <p>
                  Usuario
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= URL ?>/views/usuarios/cambiar_contrasena.php" class="nav-link">
                    <i class="nav-icon fas fa-key"></i>
                    <p>Cambiar Contraseña</p>
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
          <?php endif; ?>

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
    <!-- Notificaciones -->