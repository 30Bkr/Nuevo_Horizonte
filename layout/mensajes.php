<?php

if ((isset($_SESSION['mensaje'])) && (isset($_SESSION['icono']))) {
  $message = $_SESSION['mensaje'];
  $icon = $_SESSION['icono'];
?>
  <script>
    let mensaje = '<?= $message; ?>';
    // alert(mensaje);
    Swal.fire({
      position: "top-end",
      icon: "<?= $icon ?>",
      title: "<?= $message ?>",
      showConfirmButton: false,
      timer: 5000
    });
  </script>
<?php
  unset($_SESSION['mensaje']);
  unset($_SESSION['icono']);
}
?>