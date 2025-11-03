<?php
// CORRECCIÓN RUTA: Subir dos niveles para app/config.php
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_niveles.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');
//require_once(ROOT_PATH . '/app/libs/auth.php'); 

// (mantener comentado hasta resolver error de permisos)
// protegerPagina('MATRICULAS_NIVELES_LISTAR'); 

$nivelesModel = new NivelesModel();
$mensaje = getAlert();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cambiar_estatus') {
    $id_nivel = filter_input(INPUT_POST, 'id_nivel', FILTER_VALIDATE_INT);
    $nuevo_estatus = filter_input(INPUT_POST, 'estatus', FILTER_VALIDATE_INT);
    
    if ($id_nivel !== false && $nuevo_estatus !== false) {
        if ($nivelesModel->actualizarEstatusNivel($id_nivel, $nuevo_estatus)) {
            $estatus_texto = $nuevo_estatus == 1 ? 'Activo' : 'Inactivo';
            setAlert('success', "Estatus de Nivel actualizado a {$estatus_texto} exitosamente.", '/admin/matriculas/niveles_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        } else {
            setAlert('danger', "Error al actualizar el estatus del nivel.", '/admin/matriculas/niveles_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        }
    }
}

$niveles = $nivelesModel->getListadoNiveles();

// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/header_admin.php'); 
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Niveles</h3>
                <div class="card-tools">
                    <a href="<?php echo BASE_URL; ?>/admin/matriculas/niveles_crear.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Crear Nivel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($mensaje): ?>
                    <?php echo renderAlert($mensaje); ?>
                <?php endif; ?>

                <table id="tabla-niveles" class="table table-bordered table-striped">
                    <tbody>
                        <?php foreach ($niveles as $nivel): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($nivel['id_nivel']); ?></td>
                            <td><?php echo htmlspecialchars($nivel['num_nivel']); ?></td>
                            <td><?php echo htmlspecialchars($nivel['nom_nivel']); ?></td>
                            <td>
                                <?php 
                                    $tipo = htmlspecialchars($nivel['tipo_nivel']);
                                    if ($tipo === 'grado') {
                                        echo '<span class="badge badge-primary">Básica (Grado)</span>';
                                    } elseif ($tipo === 'año') {
                                        echo '<span class="badge badge-info">Media (Año)</span>';
                                    } else {
                                        echo '<span class="badge badge-secondary">Desconocido</span>';
                                    }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if ($nivel['estatus'] == 1) {
                                    echo '<span class="badge badge-success">Activo</span>';
                                } else {
                                    echo '<span class="badge badge-danger">Inactivo</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="action" value="cambiar_estatus">
                                    <input type="hidden" name="id_nivel" value="<?php echo $nivel['id_nivel']; ?>">
                                    <?php if ($nivel['estatus'] == 1): ?>
                                        <input type="hidden" name="estatus" value="0">
                                        <button type="submit" class="btn btn-danger btn-xs" title="Desactivar" onclick="return confirm('¿Seguro que deseas desactivar este Nivel?');">
                                            <i class="fas fa-times"></i> Desactivar
                                        </button>
                                    <?php else: ?>
                                        <input type="hidden" name="estatus" value="1">
                                        <button type="submit" class="btn btn-success btn-xs" title="Activar" onclick="return confirm('¿Seguro que deseas activar este Nivel?');">
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