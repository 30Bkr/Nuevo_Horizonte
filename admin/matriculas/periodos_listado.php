<?php
// Usamos __DIR__ para asegurar la ruta de config.php desde admin/matriculas/
require_once(__DIR__ . '/../../app/config.php'); 
session_start();

// Inclusiones de Modelos y Controladores
require_once(ROOT_PATH . '/app/models/models_periodos.php');
// NOTA: Se comenta la inclusión de alerts.php para evitar el error de getAlert()
// require_once(ROOT_PATH . '/app/controllers/alerts.php'); 

// No se usa la protección de roles, ya que se eliminó por completo
// if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
//     header("Location: " . BASE_URL . "/login/index.php"); 
//     exit();
// }


// -----------------------------------------------------
// 1. LÓGICA DE PROCESAMIENTO
// -----------------------------------------------------

$periodosModel = new PeriodosModel();
$periodos = $periodosModel->getListadoPeriodos();

// No se necesita $mensaje = getAlert();


// -----------------------------------------------------
// 2. LAYOUT Y VISTA
// -----------------------------------------------------

// NOTA: Usamos ROOT_PATH para layouts ya que se definió en config.php
include_once(ROOT_PATH . '/layout/header_admin.php'); 
?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-calendar-event"></i> Listado de Períodos Escolares</h3>
                        <div class="card-tools">
                            <a href="<?php echo BASE_URL; ?>/admin/matriculas/periodos_crear.php" class="btn btn-sm btn-light">
                                <i class="fas fa-plus-circle"></i> Crear Nuevo Período
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <?php 
                        // NOTA: La lógica para mostrar el mensaje de alerta de Bootstrap fue eliminada
                        // Se confía únicamente en SweetAlert2 (que se muestra en el footer_admin.php)
                        ?>

                        <table id="tablaPeriodos" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Fecha Inicio</th>
                                    <th>Fecha Fin</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($periodos)): ?>
                                    <?php foreach ($periodos as $periodo): ?>
                                        <?php
                                        // Formatear fechas
                                        $inicio = date('d/m/Y', strtotime($periodo['fecha_inicio']));
                                        $fin = date('d/m/Y', strtotime($periodo['fecha_fin']));
                                        
                                        // Clase para el estatus
                                        $estatus_clase = ($periodo['estatus'] == 1) ? 'badge-success' : 'badge-danger';
                                        $estatus_texto = ($periodo['estatus'] == 1) ? 'Activo' : 'Inactivo';
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($periodo['id_periodo']); ?></td>
                                            <td><?php echo htmlspecialchars($periodo['nom_periodo']); ?></td>
                                            <td><?php echo $inicio; ?></td>
                                            <td><?php echo $fin; ?></td>
                                            <td><span class="badge <?php echo $estatus_clase; ?>"><?php echo $estatus_texto; ?></span></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/admin/matriculas/periodos_editar.php?id=<?php echo $periodo['id_periodo']; ?>" class="btn btn-sm btn-info" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-warning btnCambiarEstatus" 
                                                        data-id="<?php echo $periodo['id_periodo']; ?>" 
                                                        data-estatus="<?php echo $periodo['estatus']; ?>"
                                                        title="Cambiar Estatus">
                                                    <i class="fas fa-toggle-<?php echo ($periodo['estatus'] == 1) ? 'on' : 'off'; ?>"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No se encontraron períodos escolares.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php 
include_once(ROOT_PATH . '/layout/footer_admin.php'); 
?>

<script>
$(document).ready(function() {
    $('#tablaPeriodos').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
        }
    });

    // Lógica para el botón de Cambiar Estatus (Requiere un controller_periodos.php para procesar la acción)
    $('.btnCambiarEstatus').on('click', function() {
        var id = $(this).data('id');
        var estatus_actual = $(this).data('estatus');
        var nuevo_estatus = estatus_actual == 1 ? 0 : 1;
        
        Swal.fire({
            title: '¿Está seguro?',
            text: "¿Desea cambiar el estatus del período?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cambiar estatus',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir al controlador para cambiar estatus
                window.location.href = '<?php echo BASE_URL; ?>/admin/matriculas/controller_periodos.php?action=cambiar_estatus&id=' + id + '&estatus=' + nuevo_estatus;
            }
        })
    });
});
</script>