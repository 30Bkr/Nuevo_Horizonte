<?php
include_once('../global/utils.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= URL ?>/public/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="<?= URL ?>/public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= URL ?>/public/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="./stylelogin.css">
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b><?= NAME_PROJECT ?></b></a>
            <img src="../public/images/logo_escuela.jpg" alt="">
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Inicio de sesión</p>

                <form action="./controllerLogin.php" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="email" class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Recordar contraseña
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <?php
                session_start();
                if (isset($_SESSION['mensaje'])) {
                    $message = $_SESSION['mensaje'];
                ?>
                    <script>
                        let mensaje = '<?= $message; ?>';
                        alert(mensaje);
                        Swal.fire({
                            position: "top-end",
                            icon: "error",
                            title: "<?= $message ?>",
                            showConfirmButton: false,
                            timer: 5000
                        });
                    </script>
                <?php
                }
                session_destroy();
                ?>

                <!-- /.social-auth-links -->

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= URL ?>/public/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= URL ?>/public/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= URL ?>/public/dist/js/adminlte.min.js"></script>
</body>

</html>