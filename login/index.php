<?php
include_once('../global/utils.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de sesión</title>
  <link rel="stylesheet" href="<?= URL ?>/public/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= URL ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= URL ?>/public/dist/css/adminlte.min.css">
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
  <link rel="stylesheet" href="./stylelogin.css">
</head>

<body>
  <div class="login-container">
    <!-- Sección de imagen (60%) -->
    <div class="image-section">
      <div class="image-content">
        <h1>
          <i>
            <img src="../public/images/graduado.svg" alt="">
            Nuevo Horizonte
          </i>
        </h1>
        <p>Plataforma integral de gestión educativa con herramientas avanzadas para administrar estudiantes, profesores y procesos académicos.</p>
      </div>
    </div>

    <!-- Sección de login (40%) -->
    <div class="login-section">
      <div class="login-box">
        <div class="logo">
          <h2>Bienvenido</h2>
          <p>a</p>
          <!-- <div class="d-flex justify-content-center mt-1 flex-column"> -->
          <h3>Nuevo Horizonte</h3>
          <img src="../public/images/insignia_escuela.png" alt="insignia-escuela" style="height: 7rem;">
          <!-- </div> -->
        </div>


        <form class="login-form" action="./controllerLogin.php" method="post">

          <div class="form-group">
            <label for="username">Usuario</label>
            <div class="input-with-icon">
              <i>
                <img src="../public/images/usuario.svg" alt="" style="width: 28px; height:28px ;">
              </i>
              <input type="text" id="email" name="email" placeholder="Ingresa tu usuario" required>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-with-icon">
              <i>
                <img src="../public/images/contrasena.svg" alt="" style="width: 28px; height:28px ;">
              </i>
              <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
          </div>

          <button type="submit" class="login-btn">
            Iniciar Sesión
          </button>

        </form>
      </div>
    </div>
  </div>
  <script src="<?= URL ?>/public/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= URL ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= URL ?>/public/dist/js/adminlte.min.js"></script>

</body>

</html>