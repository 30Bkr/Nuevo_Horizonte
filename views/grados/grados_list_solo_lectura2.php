<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';



$database = new Conexion();
$db = $database->conectar();
$grado = new Grado($db);

echo "=== DEBUG MODE ===<br>";
$idPeriodo = $grado->getPeriodoActivo();
echo "Período activo obtenido: " . $idPeriodo . "<br>";

// DEBUG: Verificar qué grados existen en la base de datos
$query = "SELECT ns.id_nivel_seccion, n.nom_nivel, s.nom_seccion, ns.capacidad, ns.estatus 
          FROM niveles_secciones ns
          JOIN niveles n ON ns.id_nivel = n.id_nivel
          JOIN secciones s ON ns.id_seccion = s.id_seccion
          WHERE ns.estatus = 1";
$stmt = $db->prepare($query);
$stmt->execute();
$gradosExistentes = $stmt->rowCount();
echo "Grados existentes en BD: " . $gradosExistentes . "<br>";

// Mostrar los grados
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  echo "Grado: " . $row['nom_nivel'] . " - " . $row['nom_seccion'] . " (ID: " . $row['id_nivel_seccion'] . ")<br>";
}

// DEBUG: Verificar inscripciones en el período activo
$queryInscripciones = "SELECT COUNT(*) as total FROM inscripciones WHERE id_periodo = ? AND estatus = 1";
$stmtIns = $db->prepare($queryInscripciones);
$stmtIns->execute([$idPeriodo]);
$inscripciones = $stmtIns->fetch(PDO::FETCH_ASSOC);
echo "Inscripciones en período " . $idPeriodo . ": " . $inscripciones['total'] . "<br>";

// DEBUG: Ejecutar la consulta del método listarGradosConAlumnos
echo "<br>=== Consulta de listarGradosConAlumnos ===<br>";
$queryTest = "SELECT 
                ns.id_nivel_seccion,
                n.nom_nivel as nombre_grado,
                s.nom_seccion as seccion,
                ns.capacidad,
                ns.estatus,
                COUNT(i.id_inscripcion) as total_alumnos
              FROM niveles_secciones ns
              INNER JOIN niveles n ON ns.id_nivel = n.id_nivel
              INNER JOIN secciones s ON ns.id_seccion = s.id_seccion
              LEFT JOIN inscripciones i ON ns.id_nivel_seccion = i.id_nivel_seccion 
                AND i.estatus = 1
                AND i.id_periodo = :id_periodo
              WHERE ns.estatus = 1
              GROUP BY ns.id_nivel_seccion
              ORDER BY n.num_nivel, s.nom_seccion";

$stmtTest = $db->prepare($queryTest);
$stmtTest->bindParam(":id_periodo", $idPeriodo, PDO::PARAM_INT);
$stmtTest->execute();

echo "Resultados de la consulta: " . $stmtTest->rowCount() . "<br>";
if ($stmtTest->rowCount() > 0) {
  while ($row = $stmtTest->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id_nivel_seccion'] . " - " .
      $row['nombre_grado'] . " " . $row['seccion'] . " - " .
      "Alumnos: " . $row['total_alumnos'] . "/" . $row['capacidad'] . "<br>";
  }
} else {
  echo "NO HAY RESULTADOS<br>";
}

echo "<hr>";

// Agrega esto después de la conexión para verificar globales
$queryGlobales = "SELECT * FROM globales WHERE es_activo = 1";
$stmtGlobales = $db->prepare($queryGlobales);
$stmtGlobales->execute();
$globalActivo = $stmtGlobales->fetch(PDO::FETCH_ASSOC);

echo "=== Información de Globales ===<br>";
if ($globalActivo) {
  echo "ID Global: " . $globalActivo['id_globales'] . "<br>";
  echo "Versión: " . $globalActivo['version'] . "<br>";
  echo "Período ID: " . $globalActivo['id_periodo'] . "<br>";
  echo "Activo: " . $globalActivo['es_activo'] . "<br>";
  echo "Nombre Institución: " . $globalActivo['nom_instituto'] . "<br>";
} else {
  echo "NO HAY REGISTRO ACTIVO EN GLOBALES<br>";
}

// Verificar períodos
$queryPeriodos = "SELECT * FROM periodos WHERE estatus = 1";
$stmtPeriodos = $db->prepare($queryPeriodos);
$stmtPeriodos->execute();
echo "Períodos activos en BD: " . $stmtPeriodos->rowCount() . "<br>";

// Ahora continuar con el código normal
$stmt = $grado->listarGradosConAlumnos();


include_once("/xampp/htdocs/final/layout/layaout1.php");
?>

<!-- Content Wrapper -->
<div class="content-wrapper">


  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Consulta de Grados, Años y Secciones</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
            <li class="breadcrumb-item active">Grados/Años</li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Mensajes de alerta -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
          <?php echo $_SESSION['success'];
          unset($_SESSION['success']); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
          <?php echo $_SESSION['error'];
          unset($_SESSION['error']); ?>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Listado de Grados, Años y Secciones</h3>
              <div class="card-tools">
                <a href="grados_pdf.php" class="btn btn-success btn-sm" target="_blank">
                  <i class="fas fa-print"></i> Imprimir PDF General
                </a>
              </div>
            </div>
            <div class="card-body">
              <?php
              try {
                if ($stmt->rowCount() > 0) {
                  echo '<table id="tablaGrados" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Grado/Año</th>
                                                    <th>Sección</th>
                                                    <th>Capacidad</th>
                                                    <th>Alumnos Registrados</th>
                                                    <th>Disponibilidad</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                  $totalCapacidad = 0;
                  $totalAlumnos = 0;

                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $porcentaje = $row['capacidad'] > 0 ? ($row['total_alumnos'] / $row['capacidad']) * 100 : 0;
                    $clase_progress = $porcentaje >= 90 ? 'bg-danger' : ($porcentaje >= 70 ? 'bg-warning' : 'bg-success');
                    $cuposDisponibles = $row['capacidad'] - $row['total_alumnos'];

                    $totalCapacidad += $row['capacidad'];
                    $totalAlumnos += $row['total_alumnos'];

                    // Obtener el estado actual del grado
                    $estado_grado = $grado->obtenerEstadoGrado($row['id_nivel_seccion']);
                    $estado_texto = $estado_grado ? 'Activo' : 'Inactivo';
                    $estado_clase = $estado_grado ? 'success' : 'danger';
                    $estado_icono = $estado_grado ? 'check' : 'times';

                    echo "<tr>";
                    // MODIFICACIÓN: Celda vacía para que DataTables inserte el número de fila.
                    echo "<td></td>";
                    echo "<td>{$row['nombre_grado']}</td>";
                    echo "<td>{$row['seccion']}</td>";
                    echo "<td>{$row['capacidad']}</td>";
                    echo "<td>{$row['total_alumnos']}</td>";
                    echo "<td>
                                            <div class='progress progress-sm'>
                                                <div class='progress-bar {$clase_progress}' style='width: {$porcentaje}%'></div>
                                            </div>
                                            <small>{$cuposDisponibles} cupos disponibles (" . number_format($porcentaje, 1) . "%)</small>
                                        </td>";
                    echo "<td>
                                            <span class='badge badge-{$estado_clase}'>
                                                <i class='fas fa-{$estado_icono}'></i> {$estado_texto}
                                            </span>
                                        </td>";
                    echo "<td>
                                            <div class='btn-group'>
                                                <a href='estudiantes_por_grado.php?id_nivel_seccion={$row['id_nivel_seccion']}' 
                                                    class='btn btn-info btn-sm' title='Ver Estudiantes'>
                                                    <i class='fas fa-users'></i> Ver Estudiantes
                                                </a>
                                            </div>
                                        </td>";
                    echo "</tr>";
                  }

                  echo '</tbody>';
                  echo '<tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right"><strong>TOTALES:</strong></th>
                                                <th><strong>' . $totalCapacidad . '</strong></th>
                                                <th><strong>' . $totalAlumnos . '</strong></th>
                                                <th colspan="3">
                                                    <strong>' . ($totalCapacidad - $totalAlumnos) . ' cupos disponibles totales</strong>
                                                </th>
                                            </tr>
                                        </tfoot>';
                  echo '</table>';
                } else {
                  echo "<div class='alert alert-info'>No hay grados/secciones registrados en el sistema.</div>";
                }
              } catch (Exception $e) {
                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

</div>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>

<!-- Scripts -->
<script src="/final/public/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/final/public/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script>
  $(function() {
    $('#tablaGrados').DataTable({
      "responsive": true,
      "autoWidth": false,
      "language": {
        "decimal": "",
        "emptyTable": "No hay datos disponibles en la tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 a 0 de 0 registros",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "No se encontraron registros coincidentes",
        "paginate": {
          "first": "Primero",
          "last": "Último",
          "next": "Siguiente",
          "previous": "Anterior"
        },
        "aria": {
          "sortAscending": ": activar para ordenar ascendente",
          "sortDescending": ": activar para ordenar descendente"
        }
      },
      "order": [
        [1, "asc"],
        [2, "asc"]
      ],
      // MODIFICACIÓN: Implementación de la numeración continua
      "drawCallback": function(settings) {
        var api = this.api();
        // Obtiene el índice de inicio de la página actual
        var startIndex = api.page.info().start;

        // Recorre las celdas de la columna 0 (#) en la página actual
        api.column(0, {
          page: 'current'
        }).nodes().each(function(cell, i) {
          // Calcula la numeración continua (índice base + índice de fila + 1)
          cell.innerHTML = startIndex + i + 1;
        });
      }
    });
  });
</script>