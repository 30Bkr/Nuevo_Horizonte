<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/controllers/roles/roles.php");
$roles = new Roles();
$lista = $roles->listar2();
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <h1>Listado de Roles</h1>
      </div>
      <div class="row">
        <div class="col-md-8">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Lista de roles</h3>
              <!-- <div class="card-tools">
                <a href="create.php" class="btn btn-primary"> Nuevo rol <strong>+</strong>
                </a>
              </div> -->
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>Rol</th>
                    <th>Usuario</th>
                    <th>
                      <center>Acciones</center>
                    </th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 0;
                  while ($i < count($lista)) {
                    echo "<tr>";
                    echo "<td style='text-align: center'>" . $i + 1 . "</td>";
                    echo "<td>" . $lista[$i]->nombre_rol . "</td>";
                    echo "<td>" . $lista[$i]->descripcion . "</td>";
                    echo "<td style='display: flex;
                         justify-content: center;'>
                         <div class='btn-group' role='group' aria-label='Basic example'>
                         <a href=edit.php?id_rol=" . $lista[$i]->id_rol . "  class='btn btn-success'> 
                          <img src='../../public/images/pencil.svg' alt='ELIMINAR'>
                         </a>
                         <a href=eliminarUsuario.php?id=" . $lista[$i]->id_rol . " style='margin-left: 8px' class='btn btn-danger'>
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
        <div class="col-md-4">
          <div class="card card-outline card-seconday">
            <div class="card-header">
              <h3 class="card-title">Usuarios con el permiso</h3>
            </div>
            <div class="card-body">
              <table class="table table-hover table-bordered">

              </table>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>