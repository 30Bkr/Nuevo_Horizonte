<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Debug Session</title>
  <style>
    body {
      font-family: Arial;
      padding: 20px;
    }

    pre {
      background: #f4f4f4;
      padding: 15px;
      border-radius: 5px;
    }

    .success {
      color: green;
    }

    .error {
      color: red;
    }
  </style>
</head>

<body>
  <h1>Depuración de Sesión</h1>

  <?php if (!isset($_SESSION['usuario_id'])): ?>
    <p class="error">NO hay sesión activa. Debes iniciar sesión primero.</p>
  <?php else: ?>
    <pre>
=== DATOS DE SESIÓN ===
Usuario ID: <?= $_SESSION['usuario_id'] ?? 'NO SET' ?>

Usuario Email: <?= $_SESSION['usuario_email'] ?? 'NO SET' ?>

Usuario Nombre: <?= $_SESSION['usuario_nombre'] ?? 'NO SET' ?>

Usuario Rol (cargo): <?= $_SESSION['usuario_rol'] ?? 'NO SET' ?>
Valor exacto: '<?= $_SESSION['usuario_rol'] ?? '' ?>'
Longitud: <?= strlen($_SESSION['usuario_rol'] ?? '') ?>

Usuario Rol ID: <?= $_SESSION['usuario_rol_id'] ?? 'NO SET' ?>
        </pre>

    <h3>Verificaciones:</h3>
    <p>¿Es exactamente 'Administrador'?
      <strong class="<?= ($_SESSION['usuario_rol'] === 'Administrador') ? 'success' : 'error' ?>">
        <?= ($_SESSION['usuario_rol'] === 'Administrador') ? 'SÍ' : 'NO' ?>
      </strong>
    </p>

    <p>¿Es ID = 1 (Administrador)?
      <strong class="<?= ($_SESSION['usuario_rol_id'] == 1) ? 'success' : 'error' ?>">
        <?= ($_SESSION['usuario_rol_id'] == 1) ? 'SÍ' : 'NO' ?>
      </strong>
    </p>

    <h3>Toda la sesión:</h3>
    <pre><?php print_r($_SESSION); ?></pre>

    <h3>Probar redirección a roles:</h3>
    <a href="<?= URL ?>/admin/roles_permisos/index.php" target="_blank">
      Ir a Gestión de Roles
    </a>
  <?php endif; ?>
</body>

</html>