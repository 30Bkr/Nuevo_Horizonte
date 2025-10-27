<?php
// include('../../app/controllers/cursos/cursos.php');
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/personas.php");
$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();
?>

<div class="content-wrapper">
  <br>
  <div class="content">
    <div class="container-fluid">

      <div class="row">
        <h1>Cursos</h1>
      </div>
      <br>
      <div class="row">
        <div class='col-md-8'>
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Listado de grados</h3>
              <div class="card-tools">
                <a href="createG.php" class="btn btn-primary"> Nuevo grado <strong>+</strong>
                </a>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Grado</th>
                    <th>Seccion</th>
                    <th>Capacidad</th>
                    <th>Turno</th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  while ($i < count($listaGrados)) {
                    echo "<tr>";
                    echo "<td>" . $listaGrados[$i]->grado . "</td>";
                    echo "<td>" . $listaGrados[$i]->nom_seccion . "</td>";
                    echo "<td>" . $listaGrados[$i]->capacidad . "</td>";
                    echo "<td>" . $listaGrados[$i]->turno . "</td>";
                    echo "<td style='display: flex;
                           justify-content: center;'>
                           <div class='btn-group' role='group' aria-label='Basic example'>
                           <a href=editG.php?id_grados_secciones=" . $listaGrados[$i]->id_grados_secciones . "  class='btn btn-success'> 
                            <img src='../../public/images/pencil.svg' alt='ELIMINAR'>
                           </a>
                           <a href=eliminarUsuario.php?id=" . $listaGrados[$i]->id_grados_secciones . " style='margin-left: 8px' class='btn btn-danger'>
                            <img src='../../public/images/trash.svg' alt='ELIMINAR'>
                           </a>
                           
                           </div>
                        </td>";
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
        <div class='col-md-8 center'>

          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Listado de años</h3>
              <div class="card-tools">
                <a href="createA.php" class="btn btn-primary"> Nuevo año <strong>+</strong>
                </a>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Año</th>
                    <th>Seccion</th>
                    <th>Capacidad</th>
                    <th>Turno</th>
                    <th>
                      <center>Acciones</center>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  while ($i < count($listaAnos)) {
                  ?>
                    <tr>
                      <td><?= $listaAnos[$i]->año  ?></td>
                      <td><?= $listaAnos[$i]->nom_seccion  ?></td>
                      <td><?= $listaAnos[$i]->capacidad  ?></td>
                      <td><?= $listaAnos[$i]->turno  ?></td>
                      <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal_asignacion<?= $listaAnos[$i]->id_años_secciones ?>">
                            <i class="bi bi-check-circle"></i>
                            Edit
                          </button>

                          <div class="modal fade" id="modal_asignacion<?= $listaAnos[$i]->id_años_secciones ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                <div class="modal-header" style="background-color: #dbcd59;">

                                </div>
                                <div class="modal-body">
                                  <div class="row">

                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                        </div>

                      </td>
                    </tr>
                    <!-- echo "<tr>";
                      echo "<td>" . $listaAnos[$i]->año . "</td>";
                      echo "<td>" . $listaAnos[$i]->nom_seccion . "</td>";
                      echo "<td>" . $listaAnos[$i]->capacidad . "</td>";
                      echo "<td>" . $listaAnos[$i]->turno . "</td>";
                      echo "<td style='display: flex;
                             justify-content: center;'>
                             <div class='btn-group' role='group' aria-label='Basic example'>
                             <a href=editA.php?id_años_secciones=" . $listaAnos[$i]->id_años_secciones . "  class='btn btn-success'> 
                              <img src='../../public/images/pencil.svg' alt='ELIMINAR'>
                             </a>
                             <a href=eliminarUsuario.php?id_años_secciones=" . $listaAnos[$i]->id_años_secciones . " style='margin-left: 8px' class='btn btn-danger'>
                              <img src='../../public/images/trash.svg' alt='ELIMINAR'>
                             </a>
                             
                             </div>
                          </td>";
                      echo "</tr>";
  
                      $i++; -->

                  <?php
                  }
                  ?>
                </tbody>
              </table>

            </div>
          </div>


        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->

  </div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>