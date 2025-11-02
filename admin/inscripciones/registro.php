<?php
include_once("/xampp/htdocs/final/layout/layaout1.php");
include_once("/xampp/htdocs/final/app/personas.php");

$persona = new Persona();
$lista = $persona->mostrarById(2);
// include('../../app/controllers/docentes/listado_de_docentes.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <br>
  <div class="content">
    <div class="container">
      <div class="row">
        <h1>Listado de alumnos</h1>
      </div>
      <br>
      <div class="row">

        <div class="col-md-12">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Alumnos registrados</h3>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-striped table-bordered table-hover table-sm">
                <thead>
                  <tr>
                    <th>
                      <center>Nro</center>
                    </th>
                    <th>
                      <center>Nombres del estudiante</center>
                    </th>
                    <th>
                      <center>Ci</center>
                    </th>

                    <th>
                      <center>Email</center>
                    </th>
                    <th>
                      <center>Estado</center>
                    </th>
                    <th>
                      <center>Fecha de nacimiento</center>
                    </th>
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
                    echo "<td>" . $lista[$i]->nombres . " " . $lista[$i]->apellidos . "</td>";
                    echo "<td>" . $lista[$i]->cedula . "</td>";
                    echo "<td>" . $lista[$i]->correo . "</td>";
                    echo "<td>" . $lista[$i]->estado . "</td>";
                    echo "<td>" . $lista[$i]->fecha_nac . "</td>";
                    echo "<td style='display: flex;
                         justify-content: center;'>
                         <div class='btn-group' role='group' aria-label='Basic example'>
                         <a href=edit.php?id_persona=" . $lista[$i]->id_persona . "  class='btn btn-success'> 
                          <img src='../../public/images/pencil.svg' alt='ELIMINAR'>
                         </a>
                         <a href=mostrar.php?id=" . $lista[$i]->id_persona . " style='margin-left: 8px' class='btn btn-warning'>
                          <img src='../../public/images/perfil.svg' alt='ELIMINAR'>
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

<script>
  $(function() {
    $("#example1").DataTable({
      "pageLength": 5,
      "language": {
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Docentes",
        "infoEmpty": "Mostrando 0 a 0 de 0 Docentes",
        "infoFiltered": "(Filtrado de _MAX_ total Docentes)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Docentes",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscador:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
          "first": "Primero",
          "last": "Ultimo",
          "next": "Siguiente",
          "previous": "Anterior"
        }
      },
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      buttons: [{
          extend: 'collection',
          text: 'Reportes',
          orientation: 'landscape',
          buttons: [{
            text: 'Copiar',
            extend: 'copy',
          }, {
            extend: 'pdf'
          }, {
            extend: 'csv'
          }, {
            extend: 'excel'
          }, {
            text: 'Imprimir',
            extend: 'print'
          }]
        },
        {
          extend: 'colvis',
          text: 'Visor de columnas',
          collectionLayout: 'fixed three-column'
        }
      ],
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  });
</script>