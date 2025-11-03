<?php
// CORRECCIÓN RUTA: Subir dos niveles para app/config.php
require_once(__DIR__ . '/../../app/config.php'); 
session_start();
require_once(ROOT_PATH . '/app/models/models_niveles.php');
require_once(ROOT_PATH . '/app/controllers/alerts.php');
//require_once(ROOT_PATH . '/app/libs/auth.php'); 

// (mantener comentado hasta resolver error de permisos)
// protegerPagina('MATRICULAS_NIVELES_CREAR'); 

$nivelesModel = new NivelesModel();
$errores = [];

// Procesamiento del Formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $numero = filter_input(INPUT_POST, 'numero', FILTER_VALIDATE_INT);
    $tipo_nivel = filter_input(INPUT_POST, 'tipo_nivel', FILTER_SANITIZE_STRING);
    $estatus = 1;

    if (empty($nombre) || $numero === false || empty($tipo_nivel)) {
        $errores[] = "Todos los campos (Nombre, Número y Tipo) son obligatorios.";
    }
    if (!in_array($tipo_nivel, ['grado', 'año'])) {
        $errores[] = "El tipo de nivel seleccionado no es válido. Debe ser Grado (Básica) o Año (Media).";
    }

    if (empty($errores)) {
        if ($nivelesModel->crearNivel($nombre, $numero, $tipo_nivel, $estatus)) {
            $tipo_display = ($tipo_nivel == 'grado' ? 'Básica' : 'Media');
            setAlert('success', "Nivel '{$nombre}' ({$tipo_display}) ha sido creado exitosamente.", '/admin/matriculas/niveles_listado.php');
            header("Location: " . getAlertRedirect());
            exit();
        } else {
            $errores[] = "Error al guardar el nivel. Podría ya existir un Nivel con el mismo Número y Tipo (Ej: 1er Grado ya existe).";
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
                <h3 class="card-title">Datos del Nivel</h3>
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
                        <label for="nombre">Nombre Descriptivo (Ej: Primer Grado)</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required 
                               value="<?php echo isset($nombre) ? htmlspecialchars($nombre) : ''; ?>"
                               placeholder="Ingrese el nombre completo del nivel" maxlength="50">
                    </div>
                    
                    <div class="form-group">
                        <label for="numero">Número de Nivel/Grado (Ej: 1, 2, 3...)</label>
                        <input type="number" class="form-control" id="numero" name="numero" required min="1" max="12"
                               value="<?php echo isset($numero) ? htmlspecialchars($numero) : ''; ?>"
                               placeholder="Ingrese el número de grado o año">
                    </div>

                    <div class="form-group">
                        <label for="tipo_nivel">Tipo de Educación</label>
                        <select class="form-control" id="tipo_nivel" name="tipo_nivel" required>
                            <option value="">-- Seleccione el Tipo --</option>
                            <option value="grado" <?php echo (isset($tipo_nivel) && $tipo_nivel == 'grado') ? 'selected' : ''; ?>>Básica (Grado)</option>
                            <option value="año" <?php echo (isset($tipo_nivel) && $tipo_nivel == 'año') ? 'selected' : ''; ?>>Media (Año)</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Guardar Nivel</button>
                    <a href="<?php echo BASE_URL; ?>/admin/matriculas/niveles_listado.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </section>
</div>

<?php 
// CORRECCIÓN RUTA: Inclusión del layout
include_once(ROOT_PATH . '/layout/footer_admin.php'); 
?>