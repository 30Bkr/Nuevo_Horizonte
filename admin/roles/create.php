<?php
include_once("/xampp/htdocs/final/global/utils.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");
$roles = new Roles();
$lista = $roles->listar();

?>



<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <h1>Creación de roles</h1>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Rol</h3>
            </div>
            <div class="card-body">
              <form action="<?= URL ?>/app/controllers/roles/createRol.php" method="post" id="roles">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="nombre_rol">Nombre del rol</label>
                      <input type="text" class="form-control" name="nombre_rol" require>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="descripcion">Descripción</label>
                      <input type="text" class="form-control" name="descripcion" require>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary">Crear</button>
                      <a href="../" class="btn btn-secondary">Cancelar</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    document.getElementById('roles').addEventListener('submit', function(event) {
      event.preventDefault();
      const {
        nombre_rol,
        descripcion
      } = event.target
      // console.log('Nombre:', nombre_rol.value);
      // console.log('Descripcion:', descripcion.value);
      // console.log('Evento', event.target);
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const emptyRegex = /^\s*$/;
      if (emptyRegex.test(nombre_rol.value)) {
        alert('Es obligatorio llenar el campo: Nombre');
      } else if (emptyRegex.test(descripcion.value)) {
        alert('Es obligatorio llenar el campo: Descripcion')
      } else if (emailRegex.test(nombre_rol.value)) {
        alert('No es valido enviar correos electronicos.')
      } else if (emailRegex.test(descripcion.value)) {
        alert('No es valido enviar correos electronicos.')
      } else {
        //Falta añadir proteccion contra inyeccion de codigo
        //Falta añadir proteccion contra inyeccion de codigo
        //Falta añadir proteccion contra inyeccion de codigo
        //Falta añadir proteccion contra inyeccion de codigo
        alert('Todo corre bien');
        this.submit();
      }
    })
  </script>
</div>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>