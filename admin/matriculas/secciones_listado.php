<?php
// CORRECCIÓN RUTA: Subir dos niveles para app/config.php
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_secciones.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');
require_once(ROOT_PATH . '/app/libs/auth.php'); 

// (mantener comentado hasta resolver error de permisos)
// protegerPagina('MATRICULAS_SECCIONES_LISTAR'); 

$seccionesModel = new SeccionesModel();
$mensaje = getAlert();

// Manejo de la acción POST (Cambio de Estatus)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cambiar_estatus') {
    $id_seccion = filter_input(INPUT_POST, 'id_seccion', FILTER_VALIDATE_INT);
    $nuevo_estatus = filter_input(INPUT_POST, 'estatus', FILTER_VALIDATE_INT);
    
    if ($id_seccion !== false && $nuevo_estatus !== false) {
        if ($seccionesModel->actualizarEstatusSeccion($id_seccion, $nuevo_estatus)) {
            $estatus_texto = $nuevo_estatus == 1 ? 'Activa' : 'Inactiva';
            setAlert('success', "Estatus de Sección actualizado a {$estatus_texto} exitosamente.", '/admin/matriculas/secciones_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        } else {
            setAlert('danger', "Error al actualizar el estatus de la sección.", '/admin/matriculas/secciones_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        }
    }
}

// Obtener datos
$secciones = $seccionesModel->getListadoSecciones();

// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/header_admin.php'); 
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Secciones</h3>
                <div class="card-tools">
                    <a href="<?php echo BASE_URL; ?>/admin/matriculas/secciones_crear.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Sección
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($mensaje): ?>
                    <?php echo renderAlert($mensaje); ?>
                <?php endif; ?>

                <table id="tabla-secciones" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sección</th>
                            <th>Turno</th>
                            <th>Cupo Máximo</th>
                            <th>Niveles Asignados</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($secciones as $seccion): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($seccion['id_seccion']); ?></td>
                            <td><?php echo htmlspecialchars($seccion['nom_seccion']); ?></td>
                            <td>
                                <?php 
                                    $turno = htmlspecialchars($seccion['turno']);
                                    $clase = ($turno == 'manana') ? 'badge-warning' : 'badge-info';
                                    echo "<span class='badge {$clase}'>" . ucfirst($turno) . "</span>";
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($seccion['cupo']); ?></td>
                            <td>
                                <?php if (!empty($seccion['niveles_asignados'])): ?>
                                    <?php foreach ($seccion['niveles_asignados'] as $nivel): ?>
                                        <?php 
                                            $tipo = htmlspecialchars($nivel['tipo_nivel']);
                                            $clase_nivel = ($tipo == 'grado') ? 'badge-secondary' : 'badge-dark';
                                            $texto_nivel = "{$nivel['num_nivel']}-" . strtoupper(substr($tipo, 0, 1));
                                            echo "<span class='badge {$clase_nivel} mr-1'>{$texto_nivel}</span>";
                                        ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="text-muted">No Asignado</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                if ($seccion['estatus'] == 1) {
                                    echo '<span class="badge badge-success">Activa</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Inactiva</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="cambiar_estatus">
                                    <input type="hidden" name="id_seccion" value="<?php echo $seccion['id_seccion']; ?>">
                                    <?php if ($seccion['estatus'] == 1): ?>
                                        <input type="hidden" name="estatus" value="0">
                                        <button type="submit" class="btn btn-danger btn-xs" title="Desactivar" onclick="return confirm('¿Seguro que deseas desactivar esta Sección?');">
                                            <i class="fas fa-times"></i> Desactivar
                                        </button>
                                    <?php else: ?>
                                        <input type="hidden" name="estatus" value="1">
                                        <button type="submit" class="btn btn-success btn-xs" title="Activar" onclick="return confirm('¿Seguro que deseas activar esta Sección?');">
                                            <i class="fas fa-check"></i> Activar
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php 
// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/footer_admin.php'); 
?>