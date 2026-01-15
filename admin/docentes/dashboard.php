<?php
require_once '/xampp/htdocs/final/global/protect.php';
include_once("/xampp/htdocs/final/layout/layaout1.php");

// include('../../app/controllers/docentes/listado_de_docentes.php');
?>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <h1>Dashboard Docente</h1>
      <p>Bienvenido docente <?php echo $_SESSION['usuario_nombre']; ?></p>
      <!-- Contenido específico para docentes -->
    </div>
  </section>
</div>

<?php

include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");

?>