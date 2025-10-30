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
    <div class="container">

      <!-- <div class="row">
        <h1>Cursos</h1>
      </div>
      <br>
      <div class="row">
        <div class='col-md-8'>
          <div class="card card-outline card-success">
            <div class="card-header">
              <h3 class="card-title"><strong>LISTADO DE GRADOS</strong></h3>
              <div class="card-tools">
                <button type="button" style="width: 128px;" class="btn bg-gradient-success btn-md" data-toggle="modal" data-target="#modal_asignacionAño">
                  + Nuevo Grado
                </button>
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
      </div> -->

      <div class="row">
        <div class="col-md-2">

        </div>
        <div class='col-md-8 center'>

          <div class="card card-outline card-success overlay dark ">
            <div class="card-header">
              <h3 class="card-title"><strong>LISTADO DE AÑOS</strong></h3>
              <div class="card-tools">
                <button type="button" style="width: 128px;" class="btn bg-gradient-success btn-md" data-toggle="modal" data-target="#modal_asignacionAño">
                  + Nuevo año
                </button>
                <?php include_once("/xampp/htdocs/final/admin/cursos/ediciones/nuevoAño.php") ?>

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
                    include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
                    $cursos = new Cursos();

                    $edicion = $cursos->consultarAS($listaAnos[$i]->id_años_secciones);
                  ?>
                    <tr>
                      <td><?= $listaAnos[$i]->año  ?></td>
                      <td><?= $listaAnos[$i]->nom_seccion  ?></td>
                      <td><?= $listaAnos[$i]->capacidad  ?></td>
                      <td><?= $listaAnos[$i]->turno  ?></td>
                      <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <button type="button" class="btn btn-sm bg-gradient-primary" data-toggle="modal" data-target="#modal_asignacion<?= $listaAnos[$i]->id_años_secciones ?>">
                            <img src='../../public/images/pencil.svg' style="color: white;" alt='editar'>
                          </button>
                          <?php include_once("/xampp/htdocs/final/admin/cursos/ediciones/editarA.php") ?>
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
                    $i++;
                  }
                  ?>
                </tbody>
              </table>

            </div>
          </div>


        </div>
        <div class="col-md-2">

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