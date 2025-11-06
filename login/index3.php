<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de sesión</title>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> -->
  <!-- <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      height: 100vh;
      overflow: hidden;
      background: #f5f5f5;
    }

    .login-container {
      display: flex;
      height: 100vh;
      width: 100%;
    }

    /* Sección de imagen (60%) */
    .image-section {
      flex: 0 0 60%;
      background:
        linear-gradient(rgba(44, 62, 80, 0.1), rgba(44, 62, 80, 0.1)),
        url('../public/images/escuela_optimo.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      position: relative;
    }

    .image-content {
      text-align: center;
      padding: 2rem;
      max-width: 600px;
    }

    .image-content h1 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      font-weight: 700;
    }

    .image-content p {
      font-size: 1.2rem;
      margin-bottom: 2rem;
      line-height: 1.6;
    }


    /* Sección de login (40%) */
    .login-section {
      flex: 0 0 40%;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }

    .login-box {
      width: 100%;
      max-width: 400px;
    }

    .logo {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo h2 {
      color: #2c3e50;
      font-size: 2rem;
      font-weight: 700;
    }

    .logo p {
      color: #7f8c8d;
      margin-top: 0.5rem;
    }

    .login-form {
      background: #f8f9fa;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0px 24px 16px -30px;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #2c3e50;
      font-weight: 600;
    }

    .input-with-icon {
      position: relative;
    }

    .input-with-icon i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #7f8c8d;
    }

    .input-with-icon input {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 3rem;
      border: 2px solid #e9ecef;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .input-with-icon input:focus {
      outline: none;
      border-color: #3498db;
      box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }

    .login-btn {
      width: 100%;
      padding: 0.75rem;
      background: #3498db;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .login-btn:hover {
      background: #2980b9;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .image-section {
        flex: 0 0 40%;
      }

      .login-section {
        flex: 0 0 60%;
      }

      .features {
        grid-template-columns: 1fr;
      }

      .image-content h1 {
        font-size: 2rem;
      }
    }

    @media (max-width: 480px) {
      .image-section {
        display: none;
      }

      .login-section {
        flex: 0 0 100%;
      }

      .login-form {
        padding: 1.5rem;
      }
    }
  </style> -->
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
          <div class="d-flex justify-content-center mt-1">
            <img src="../public/images/insignia_escuela.png" alt="insignia-escuela" style="height: 7rem;">
            <h3>Nuevo Horizonte</h3>
          </div>
        </div>


        <form class="login-form">

          <div class="form-group">
            <label for="username">Usuario</label>
            <div class="input-with-icon">
              <i>
                <img src="../public/images/usuario.svg" alt="" style="width: 28px; height:28px ;">
              </i>
              <input type="text" id="username" placeholder="Ingresa tu usuario" required>
            </div>
          </div>

          <div class="form-group">
            <label for="password">Contraseña</label>
            <div class="input-with-icon">
              <i>
                <img src="../public/images/contrasena.svg" alt="" style="width: 28px; height:28px ;">
              </i>
              <input type="password" id="password" placeholder="Ingresa tu contraseña" required>
            </div>
          </div>

          <button type="submit" class="login-btn">
            Iniciar Sesión
          </button>
        </form>
      </div>
    </div>
  </div>


</body>

</html>