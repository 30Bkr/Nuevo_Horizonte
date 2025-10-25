<?php


include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");
// require('/xampp/htdocs/project/app/controllers/cursos/cursos.php');
$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();
?>
<div class="content-wrapper">

  <div class="content">
    <div class="container">
      <div class="row">
        <h1>Creación de un nuevo curso <?php echo '(Grado)' ?></h1>
      </div>
      <br>
      <form action="http://localhost/final/app/controllers/cursos/createGrado.php" method='post'>
        <div class="row">
          <div class="col-md-10">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Llene los datos del grado</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="grado">Grado</label>
                      <input type="text" name="grado" class="form-control" maxlength="1" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="descripcion">Descripción del grado</label>
                      <input type="text" name="descripcion" class="form-control" required>
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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="observacion">Descripción del grado</label>
                      <input type="text" name="observacion" class="form-control" required>
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
              <a href="http://localhost/final/admin/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>