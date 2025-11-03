<?php
// include('../../app/controllers/cursos/cursos.php');
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/personas.php");
include_once("/xampp/htdocs/final/app/controllers/cursos/cursos.php");

$cursos = new Cursos();
$listaGrados = $cursos->mostrarGrados();
$listaAnos = $cursos->mostrarAños();
$turnoss = ['Tarde', 'Mañana'];

$grado_filtro = $_GET['grado_filtro'] ?? '';
$seccion_filtro = $_GET['seccion_filtro'] ?? '';

$listaGradosFiltrada = $listaGrados;

//if (!empty($grado_filtro) || !empty($seccion_filtro)) {
//     $listaGradosFiltrada = array_filter($listaGrados, function($grado) use ($grado_filtro, $seccion_filtro) {
//         $match_grado = empty($grado_filtro) || ($grado->grado == $grado_filtro);
//         $match_seccion = empty($seccion_filtro) || ($grado->nom_seccion == $seccion_filtro);
//         return $match_grado && $match_seccion;
//     });
// }

// $grados_unicos = array_unique(array_column($listaGrados, 'grado'));
// $secciones_unicas = array_unique(array_column($listaGrados, 'nom_seccion'));

?>


<div class="content-wrapper">
  <br>
  <div class="content">
    <div class="container">
      <!-- <br>
      <div class="row">
        <div class="col-md-2"></div>
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
        <div class="col-md-2"></div>

      </div> -->


      <div class="row">
        <div class="col-md-2">

        </div>
        <div class='col-md-8 center'>

          <div class="card card-outline card-success overlay dark ">
            <div class="card-header">
              <h3 class="card-title"><strong>LISTADO DE GRADOS</strong></h3>
              <div class="card-tools">
                <a href="http://localhost/reportesPractica/reportesentencia.php" class="btn bg-gradient-secondary btn-md">Reportes</a> 
                <button type="button" style="width: 148px;" class="btn bg-gradient-success btn-md" data-toggle="modal" data-target="#modal_asignacion_grados">
                  + NUEVO GRADO
                </button>
                <?php include_once("/xampp/htdocs/final/admin/cursos/ediciones/nuevoGrado.php") ?>

                </a>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>Grado</th>
                    <th>Sección</th>
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
                  foreach ($listaGrados as $lg) {
                    $edicion = $cursos->consultarGS($lg->id_grados_secciones);
                  ?>
                    <tr>
                      <td><?= $lg->grado  ?></td>
                      <td><?= $lg->nom_seccion  ?></td>
                      <td><?= $lg->capacidad ?></td>
                      <td><?= $lg->turno ?></td>
                      <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                          <button type="button" class="btn btn-sm bg-gradient-primary" data-toggle="modal" data-target="#modal_asignacion<?= $lg->id_grados_secciones ?>">
                            <img src='../../public/images/pencil.svg' style="color: white;" alt='editar'>
                          </button>
                          <?php include("/xampp/htdocs/final/admin/cursos/ediciones/editarG.php") ?>
                        </div>
                      </td>
                    </tr>
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