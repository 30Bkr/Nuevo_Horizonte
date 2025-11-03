<?php
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_secciones.php');
require_once(ROOT_PATH . '/app/models/models_niveles.php'); // Necesario para obtener la lista de niveles
require_once(ROOT_PATH . '/app/controllers/alerts.php');

$seccionesModel = new SeccionesModel();
$nivelesModel = new NivelesModel();
$errores = [];

// Obtener la lista de niveles activos para el selector (checkboxes)
$niveles_activos = $nivelesModel->getNivelesActivos();

// Procesamiento del Formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $turno = filter_input(INPUT_POST, 'turno', FILTER_SANITIZE_STRING);
    $cupo = filter_input(INPUT_POST, 'cupo', FILTER_VALIDATE_INT);
    // Recoge los IDs de los niveles seleccionados (puede ser un array)
    $niveles_ids = $_POST['niveles'] ?? []; 

    // 1. Validación Básica
    if (empty($nombre) || empty($turno) || $cupo === false || $cupo <= 0) {
        $errores[] = "Todos los campos (Nombre, Turno y Cupo) son obligatorios y Cupo debe ser un número positivo.";
    }
    if (!in_array($turno, ['manana', 'tarde'])) {
        $errores[] = "El turno seleccionado no es válido.";
    }
    if (empty($niveles_ids) || !is_array($niveles_ids)) {
        $errores[] = "Debe asignar esta sección a al menos un Nivel (Grado/Año).";
    }

    // 2. Ejecución
    if (empty($errores)) {
        if ($seccionesModel->crearSeccion($nombre, $turno, $cupo, $niveles_ids)) {
            setAlert('success', "La sección '{$nombre}' ha sido creada y asignada exitosamente.", '/admin/matriculas/secciones_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        } else {
            $errores[] = "Error al guardar la sección. Verifique los logs de la BD.";
        }
    }
    
    // Mantener los valores si hay errores para recargar el formulario
    $nombre_val = $nombre;
    $turno_val = $turno;
    $cupo_val = $cupo;
} else {
    // Inicializar variables
    $nombre_val = '';
    $turno_val = '';
    $cupo_val = '';
    $niveles_ids = [];
}


include_once(ROOT_PATH . '/layout/header_admin.php'); 
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Crear Nueva Sección</h3>
            </div>
            
            <form method="POST" action="">
                <div class="card-body">
                    
                    <?php if (!empty($errores)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errores as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="nombre">Nombre de la Sección (Ej: A, B, Única)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required 
                               value="<?php echo htmlspecialchars($nombre_val); ?>"
                               placeholder="Ingrese el nombre (letra) de la sección" maxlength="10">
                    </div>
                    
                    <div class="form-group">
                        <label for="turno">Turno</label>
                        <select class="form-control" id="turno" name="turno" required>
                            <option value="">-- Seleccione el Turno --</option>
                            <option value="manana" <?php echo ($turno_val == 'manana') ? 'selected' : ''; ?>>Mañana</option>
                            <option value="tarde" <?php echo ($turno_val == 'tarde') ? 'selected' : ''; ?>>Tarde</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="cupo">Cupo Máximo</label>
                        <input type="number" class="form-control" id="cupo" name="cupo" required min="1" 
                               value="<?php echo htmlspecialchars($cupo_val); ?>"
                               placeholder="Ingrese el número máximo de estudiantes">
                    </div>

                    <hr>
                    <h4>Asignar a Niveles (Grados/Años)</h4>
                    <p class="text-muted">Seleccione los niveles a los que esta sección aplica. Por ejemplo, "Sección A" para "1er Grado" y "2do Grado".</p>
                    
                    <?php if (empty($niveles_activos)): ?>
                        <div class="alert alert-warning">No hay niveles activos. Cree niveles primero.</div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($niveles_activos as $nivel): ?>
                                <?php 
                                    $id = htmlspecialchars($nivel['id_nivel']);
                                    $nombre_nivel = htmlspecialchars("{$nivel['num_nivel']} {$nivel['nom_nivel']}");
                                    $is_checked = in_array($id, $niveles_ids) ? 'checked' : '';
                                ?>
                                <div class="col-md-3">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="nivel_<?php echo $id; ?>" name="niveles[]" value="<?php echo $id; ?>" <?php echo $is_checked; ?>>
                                            <label for="nivel_<?php echo $id; ?>">
                                                <?php echo $nombre_nivel; ?> 
                                                <small class="text-muted">(<?php echo ucfirst($nivel['tipo_nivel']); ?>)</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar Sección</button>
                    <a href="<?php echo BASE_URL; ?>/admin/matriculas/secciones_listado.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>

<?php 
include_once(ROOT_PATH . '/layout/footer_admin.php'); 
?>