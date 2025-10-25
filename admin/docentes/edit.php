<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/personas.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");
include_once("/xampp/htdocs/final/global/utils.php");


$roles = new Roles();
$listarRoles = $roles->listar();

$docente = new Persona();
$info = $docente->consultar($_GET['id_persona']);
$rol = $info[0]->especialidad;



$sexos = ['masculino', 'femenino', 'otro'];
$pais = ['venezolano', 'extranjero'];

// include('../../app/controllers/docentes/listado_de_docentes.php');
?>

<div class="content">
  <div class="container">
    <div class="row">
      <h1>Actualizar a un Docente</h1>
    </div>
    <br>
    <form action="<?= URL ?>/admin/app/controllers/docentes/actualizarD.php" method="post">
      <input type="hidden" name="id_persona" value="<?php echo $info[0]->id_persona ?>">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title"><b><?php echo $info[0]->nombre_rol . ": " . $info[0]->nombres . " " . $info[0]->apellidos ?></b></h3>
              <div class="card-tools">
                <button type="submit" class="btn btn-primary btn-lg">Actualizar</button>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="">Nombre del rol</label>
                    <!-- <div class="form-inline"> -->
                    <select name="id_rol" id="" class="form-control">
                      <?php
                      foreach ($listarRoles as $role) { ?>
                        <?php
                        if ($info[0]->id_rol == $role->id_rol) {
                        ?>
                          <option value="<?= $role->id_rol; ?>" selected><?php echo "$role->nombre_rol" ?></option>
                        <?php } else { ?>
                          <option value="<?= $role->id_rol; ?>"><?php echo "$role->nombre_rol" ?></option>
                        <?php } ?>

                      <?php
                      }
                      ?>
                    </select>
                    <!-- </div> -->
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="nombres">Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="<?php echo $info[0]->nombres ?>" required>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="<?php echo $info[0]->apellidos ?>" required>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="cedula">Cedula de identidad</label>
                    <input type="number" name="cedula" class="form-control" value="<?php echo $info[0]->cedula ?>" required>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="correo">Correo</label>
                    <input type="email" name="correo" class="form-control" value="<?php echo $info[0]->correo ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="telefono">Numero telefonico</label>
                    <input type="text" name="telefono" class="form-control" value="<?php echo $info[0]->telefono ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="telefono_hab">Numero de habitación</label>
                    <input type="text" name="telefono_hab" class="form-control" value="<?php echo $info[0]->telefono_hab ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="especialidad">Especialidad</label>
                    <input type="text" name="especialidad" class="form-control" value="<?php echo $info[0]->especialidad ?>" required>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="estado">Estado</label>
                    <input type="text" name="estado" class="form-control" value="<?php echo $info[0]->estado ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="parroquia">Parroquia</label>
                    <input type="text" name="parroquia" class="form-control" value="<?php echo $info[0]->parroquia ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="calle">Calle</label>
                    <input type="text" name="calle" class="form-control" value="<?php echo $info[0]->calle ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="casa">Casa</label>
                    <input type="text" name="casa" class="form-control" value="<?php echo $info[0]->casa ?>" required>
                  </div>
                </div>

              </div>
              <br>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="lugar_nac">Lugar de nacimiento</label>
                    <input type="text" name="lugar_nac" class="form-control" value="<?php echo $info[0]->lugar_nac ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="fecha_nac">Fecha de nacimiento</label>
                    <input type="date" name="fecha_nac" class="form-control" value="<?php echo $info[0]->fecha_nac ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select name="sexo" id="" class="form-control">
                      <?php
                      foreach ($sexos as $sex) { ?>
                        <?php
                        if ($info[0]->sexo == $sex) {
                        ?>
                          <option value="<?= $sex; ?>" selected><?php echo strtoupper($sex) ?></option>
                        <?php } else { ?>
                          <option value="<?= $sex; ?>"><?php echo strtoupper($sex) ?></option>
                        <?php } ?>

                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="nacionalidad">Nacionalidad</label>
                    <select name="nacionalidad" id="" class="form-control">
                      <?php
                      foreach ($pais as $p) { ?>
                        <?php
                        if ($info[0]->nacionalidad == $p) {
                        ?>
                          <option value="<?= $p; ?>" selected><?php echo strtoupper($p) ?></option>
                        <?php } else { ?>
                          <option value="<?= $p; ?>"><?php echo strtoupper($p) ?></option>
                        <?php } ?>

                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </form>
    <br>
  </div>
</div>

<?php
require '../../admin/layout/part2.php';
include('../../layout/mensajes.php');
// include('../../app/controllers/docentes/listado_de_docentes.php');
?>