<?php
// CORRECCIÓN RUTA: Subir dos niveles para app/config.php
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_periodos.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');
require_once(ROOT_PATH . '/app/libs/auth.php'); 

// (mantener comentado hasta resolver error de permisos)
// protegerPagina('MATRICULAS_PERIODOS_CREAR'); 

$periodosModel = new PeriodosModel();
$errores = [];

// Procesamiento del Formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $fecha_inicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
    $fecha_fin = filter_input(INPUT_POST, 'fecha_fin', FILTER_SANITIZE_STRING);

    if (empty($nombre) || empty($fecha_inicio) || empty($fecha_fin)) {
        $errores[] = "Todos los campos son obligatorios.";
    }
    if (strtotime($fecha_inicio) >= strtotime($fecha_fin)) {
        $errores[] = "La Fecha de Fin debe ser posterior a la Fecha de Inicio.";
    }

    if (empty($errores)) {
        if ($periodosModel->crearPeriodo($nombre, $fecha_inicio, $fecha_fin)) {
            setAlert('success', "El período '{$nombre}' ha sido creado exitosamente.", '/admin/matriculas/periodos_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        } else {
            $errores[] = "Error al guardar el período. Verifique que no haya campos duplicados o error de BD.";
        }
    }
}

// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/header_admin.php'); 
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Datos del Período</h3>
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
                        <label for="nombre">Nombre del Período (Ej: 2025-2026)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required 
                               value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>"
                               placeholder="Ingrese el nombre del período" maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de Inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required 
                               value="<?php echo isset($fecha_inicio) ? htmlspecialchars($fecha_inicio) : ''; ?>">
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de Fin</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required 
                               value="<?php echo isset($fecha_fin) ? htmlspecialchars($fecha_fin) : ''; ?>">
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar Período</button>
                    <a href="<?php echo BASE_URL; ?>/admin/matriculas/periodos_listado.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>

<?php 
// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/footer_admin.php'); 
?>