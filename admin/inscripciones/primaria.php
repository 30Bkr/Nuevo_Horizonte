<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/persona.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");


$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();



$roles = new Roles();
$listarRoles = $roles->listar();

$docente = new Persona();
// $info = $docente->consultar($_GET['id_persona']);
// $rol = $info[0]->especialidad;
?>
<div class="content-wrapper">

  <div class="content">
    <!-- <div class="content-wrapper"> -->
    <br>
    <div class="container">
      <div class="row">
        <h1>Creación de un nuevo estudiante</h1>
      </div>
      <br>
      <form action="http://localhost/final/app/controllers/inscripciones/inscripciong.php" method="post" id="for">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><b>Datos del estudiante</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
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
                      <label for="cedula">Documento de identidad</label>
                      <input type="text" name="cedula" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="correo">Correo electronico</label>
                      <input type="email" name="correo" class="form-control" required>
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="fecha_nac">Fecha de nacimiento</label>
                      <input type="date" name="fecha_nac" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="lugar_nac">Lugar de nacimiento</label>
                      <input type="text" name="lugar_nac" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono">Telefono propio</label>
                      <input type="text" name="telefono" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono_hab">Telefono de habitación</label>
                      <input type="text" name="telefono_hab" class="form-control" required>
                    </div>
                  </div>



                  <div class="col-md-3" hidden>
                    <div class="form-group">
                      <label for="">Nombre del rol</label>
                      <a href="http://localhost/project/admin/roles/create.php" style="margin-left: 5px" class="btn btn-primary btn-sm"><i class="bi bi-file-plus"></i></a>
                      <div class="form-inline">
                        <select name="id_rol" id="" class="form-control" hidden>
                          <?php
                          foreach ($listarRoles as $role) { ?>
                            <?php
                            if ($role->nombre_rol == "ALUMNO") {
                            ?>
                              <option value="<?= $role->id_rol; ?>" selected><?php echo "$role->nombre_rol" ?></option>
                            <?php } else { ?>
                              <option value="<?= $role->id_rol; ?>"><?php echo "$role->nombre_rol" ?></option>
                            <?php } ?>

                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="sexo">Sexo</label>
                      <input type="text" name="sexo" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="nacionalidad">Nacionalidad</label>
                      <input type="text" name="nacionalidad" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="alergias">Alergías</label>
                      <input type="text" name="alergias" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="condiciones">Condiciones</label>
                      <input type="text" name="condiciones" class="form-control" required>
                    </div>
                  </div>

                </div>
              </div>
              <div class="card-header">
                <h3 class="card-title"><b>Dirección del estudiante</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="estado">Estado</label>
                      <input type="address" name="estado" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="parroquia">Parroquía</label>
                      <input type="address" name="parroquia" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="calle">Calle</label>
                      <input type="address" name="calle" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="casa">Casa</label>
                      <input type="address" name="casa" class="form-control" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-danger">
              <div class="card-header">
                <h3 class="card-title"><b>Datos del representante</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="nombresr">Nombres</label>
                      <input type="text" name="nombresr" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="apellidosr">Apellidos</label>
                      <input type="text" name="apellidosr" class="form-control" required>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="cedular">Documento de identidad</label>
                      <input type="number" name="cedular" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="correor">Correo electronico</label>
                      <input type="email" name="correor" class="form-control" required>
                    </div>
                  </div>

                </div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="fecha_nacr">Fecha de nacimiento</label>
                      <input type="date" name="fecha_nacr" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="lugar_nacr">Lugar de nacimiento</label>
                      <input type="text" name="lugar_nacr" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="relacion">Relacion(Estudiante)</label>
                      <input type="text" name="relacion" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefonor">Telefono propio</label>
                      <input type="text" name="telefonor" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="telefono_habr">Telefono de habitación</label>
                      <input type="text" name="telefono_habr" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="sexor">Sexo</label>
                      <input type="text" name="sexor" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="nacionalidadr">Nacionalidad</label>
                      <input type="text" name="nacionalidadr" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="ocupacionr">Ocupación</label>
                      <input type="textarea" name="ocupacionr" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="lugar_trabajor">Lugar de trabajo</label>
                      <input type="textarea" name="lugar_trabajor" class="form-control" required>
                    </div>
                  </div>

                </div>
              </div>
              <div class="card-header">
                <h3 class="card-title"><b>Dirección del representante</b></h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="estador">Estado</label>
                      <input type="address" name="estador" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="parroquiar">Parroquía</label>
                      <input type="address" name="parroquiar" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="caller">Calle</label>
                      <input type="address" name="caller" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="casar">Casa</label>
                      <input type="address" name="casar" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="">Nombre del rol</label>
                      <a href="http://localhost/project/admin/roles/create.php" style="margin-left: 5px" class="btn btn-primary btn-sm"><i class="bi bi-file-plus"></i></a>
                      <div class="form-inline">
                        <select name="id_rolr" id="" class="form-control">
                          <?php
                          foreach ($listarRoles as $role) { ?>
                            <?php
                            if ($role->nombre_rol == "REPRESENTANTE") {
                            ?>
                              <option value="<?= $role->id_rol; ?>" selected><?php echo "$role->nombre_rol" ?></option>
                            <?php } else { ?>
                              <option value="<?= $role->id_rol; ?>"><?php echo "$role->nombre_rol" ?></option>
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
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-warning">
              <div class="card-header">
                <h3 class="card-title"><b>Datos académicos</b></h3>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                  <thead>
                    <tr>
                      <th>
                        <center>Grado</center>
                      </th>
                      <th>
                        <center>Sección</center>
                      </th>
                      <th>
                        <center>Capacidad</center>
                      </th>
                      <th>
                        <center>Turno</center>
                      </th>
                      <th>
                        <center>Seleccione</center>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $i = 0;
                    while ($i < count($listaGrados)) {
                      echo "<tr>";
                      echo "<label for='gradopo'>";

                      echo "<td>" . $listaGrados[$i]->grado . "</td>";
                      echo "<td>" . $listaGrados[$i]->nom_seccion . "</td>";
                      echo "<td>" . $listaGrados[$i]->capacidad . "</td>";
                      echo "<td>" . $listaGrados[$i]->turno . "</td>";
                      echo "<td style='display: flex;
                           justify-content: center;'>
                           <div class='btn-group' role='group' aria-label='Basic example'>
                            <input type='radio' name='gradopo' value=" . $listaGrados[$i]->id_grado_seccion . " 
                           </div>
                        </td>";
                      echo "</label>";

                      echo "</tr>";

                      $i++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg">Registrar</button>
              <a href="http://localhost/project/admin/estudiantes" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
          </div>
        </div>

      </form>

      <!-- /.row -->
    </div><!-- /.container-fluid -->
    <!-- </div> -->

    <script>
      document.getElementById('formI').addEventListener('submit', function(event) {
        event.preventDefault();
        const {
          nombres,
          apellidos,
          celular
        } = event.target
        alert("nombres:", nombres.value)
        alert("celular:", celular.value)
        console.log(celular.value);

      })
    </script>

  </div>
</div>
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>