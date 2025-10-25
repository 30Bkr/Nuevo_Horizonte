<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/personas.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");

$roles = new Roles();
$listarRoles = $roles->listar();

// include('../../app/controllers/docentes/listado_de_docentes.php');
?>
<div class="content-wrapper">
  <div class="content">
    <div class="container">
      <div class="row">
        <h1>Creación de un nuevo Docente</h1>
      </div>
      <br>
      <form action="../../app/controllers/docentes/createD.php" method="post">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Datos del docente</b></h3>
                <div class="card-tools">
                  <button type="submit" class="btn btn-primary btn-lg">Crear</button>
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
                          <option value="<?= $role->id_rol; ?>"><?php echo "$role->nombre_rol" ?></option>
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
                      <input type="text" name="nombres" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="apellidos">Apellidos</label>
                      <input type="text" name="apellidos" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="cedula">Cedula de identidad</label>
                      <input type="number" name="cedula" class="form-control" required>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="correo">Correo</label>
                      <input type="email" name="correo" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono">Numero telefonico</label>
                      <input type="text" name="telefono" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono_hab">Numero de habitación</label>
                      <input type="text" name="telefono_hab" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="especialidad">Especialidad</label>
                      <input type="text" name="especialidad" class="form-control" required>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="estado">Estado</label>
                      <input type="text" name="estado" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="parroquia">Parroquia</label>
                      <input type="text" name="parroquia" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="calle">Calle</label>
                      <input type="text" name="calle" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="casa">Casa</label>
                      <input type="text" name="casa" class="form-control" required>
                    </div>
                  </div>

                </div>
                <br>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="lugar_nac">Lugar de nacimiento</label>
                      <input type="text" name="lugar_nac" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="fecha_nac">Fecha de nacimiento</label>
                      <input type="date" name="fecha_nac" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="sexo">Sexo</label>
                      <select name="sexo" id="" class="form-control">
                        <option value="masculino">Masculino</option>
                        <option value="femenino">femenino</option>
                        <option value="otro">Otro</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="nacionalidad">Nacionalidad</label>
                      <select name="nacionalidad" id="" class="form-control">
                        <option value="venezolano">Venezolano</option>
                        <option value="extranjero">Extranjero</option>
                        <option value="otro">Otro</option>
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
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
// include('../../app/controllers/docentes/listado_de_docentes.php');
?>