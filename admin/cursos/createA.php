<?php


include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");

// require('/xampp/htdocs/project/app/controllers/cursos/cursos.php');
// capacidad que no permita ingresar letras.
// Que no exista 2 secciones repetidas para 1 mismo año.

$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();
?>
<div class="content-wrapper">

  <div class="content">
    <div class="container">
      <div class="row">
        <h1>Creación de un nuevo curso <?php echo '(Año)' ?></h1>
      </div>
      <br>
      <form action="http://localhost/final/app/controllers/cursos/createAño.php" id="grados" method='post'>
        <div class="row">
          <div class="col-md-10">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Llene los datos del año</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="año">Año</label>
                      <input type="number" name="año" class="form-control" maxlength="1" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Llene los datos de la sección</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="seccion">Seccion</label>
                      <input type="text" name="seccion" class="form-control" maxlength="1" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Llene datos del curso</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="turno">Turno</label>
                      <input type="text" name="turno" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="capacidad">Capacidad</label>
                      <input type="text" name="capacidad" class="form-control" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <button class="btn btn-primary btn-lg">Registrar</button>
              <a href="http://localhost/project/admin/estudiantes" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
          </div>
        </div>
      </form>
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
  </div>
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>