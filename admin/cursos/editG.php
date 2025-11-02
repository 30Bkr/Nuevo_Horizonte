<?php


include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");
// require('/xampp/htdocs/project/app/controllers/cursos/cursos.php');
$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();

$edicion = $cursos->consultarGS($_GET['id_grados_secciones']);
?>
<div class="content-wrapper">
  <div class="content">
    <div class="container">
      <div class="row">
        <h1>Curso: <?php echo $edicion[0]->grado ?></h1>
      </div>
      <br>
      <form action="http://localhost/final/app/controllers/cursos/editGrado.php" method='post'>
        <input type="hidden" name="id_persona" value="<?php echo $edicion[0]->id_grados_secciones ?>">
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
                      <input type="number" name="grado" class="form-control" value="<?php echo $edicion[0]->grado ?>" maxlength="1" required>
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
                      <input type="text" name="seccion" class="form-control" value="<?php echo $edicion[0]->nom_seccion ?>" maxlength="1" required>
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
                      <input type="text" name="turno" class="form-control" value="<?php echo $edicion[0]->turno ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="capacidad">Capacidad</label>
                      <input type="text" name="capacidad" class="form-control" value="<?php echo $edicion[0]->capacidad ?>" required>
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
              <button class="btn btn-primary btn-lg">Editar</button>
              <a href="http://localhost/project/admin/cursos/index.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.row -->
</div><!-- /.container-fluid -->
</div>
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>