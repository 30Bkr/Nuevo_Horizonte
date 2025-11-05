<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Sistema</title>
    <!-- Estilos de Bootstrap v5.3.8 -->
    <link rel="stylesheet" href="../public/dist/css/bootstrap.min.css">
    <!-- Iconos de Bootstrap v1.13.1 -->
    <link rel="stylesheet" href="../public/icons/font/bootstrap-icons.css">
</head>
<body class="d-flex align-items-center vh-100">
    <!-- Foto del Patio de la Escuela -->
    <div class="d-flex shadow-lg vh-100" style="width:fit-content;">
        <img src="../public/images/escuela_optimo.png" alt="">
    </div>

    <div class="shadow-lg bg-light text-primary vh-100" style="width: 40rem;">
        <div class="p-2 shadow-sm">
        <img src="../public/images/logo_mpppe.png" height="40px">
        </div>
        <div class="p-5">
            <div class="d-flex justify-content-center mt-1">
                <img src="../public/images/logo_escuela.png" alt="insignia-escuela" style="height: 7rem;">
            </div>
            <div class="text-center fs-3 fw-bold mt-4">
                Nuevo<b>Horizonte</b>
            </div>
            <div>
                <label class="mt-4">Usuario</label>
            </div>
            <div class="input-group mt-1">
                <div class="input-group-text text-bg-primary">
                    <i class="bi bi-person-fill"></i>
                </div>
                <input class="form-control" type="text" name="usuario" placeholder="Ej. V00000000">
            </div>
            <div>
                <label class="mt-2">Contraseña</label>
            </div>
            <div class="input-group mt-1">
                <div class="input-group-text text-bg-primary">
                    <i class="bi bi-key-fill"></i>
                </div>
                <input class="form-control" type="password" name="contrasena" placeholder="********">
            </div>
            <div class="btn btn-primary w-100 mt-4">
                Acceder
            </div>
        </div>
    </div>
</body>
</html>