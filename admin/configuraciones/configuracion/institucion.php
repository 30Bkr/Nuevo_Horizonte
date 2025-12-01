<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
// configuracion/institucion.php
include_once("/xampp/htdocs/final/app/conexion.php");


// Incluir el controlador

include_once("/xampp/htdocs/final/app/controllers/globales/globales.php");
$conexion = new Conexion();
$pdo = $conexion->conectar();
// Crear instancia del controlador
$globalesController = new GlobalesController($pdo);

// Obtener datos actuales de la institución
$institucionData = $globalesController->obtenerVariablesGlobales();
$institucion = $institucionData['success'] ? $institucionData['data'] : null;

// Obtener información adicional de globales (campos específicos)
$infoInstitucion = [];
try {
  $sql = "SELECT nom_instituto, nom_directora, ci_directora, direccion FROM globales ORDER BY id_globales DESC LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $infoInstitucion = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = "Error al cargar información de la institución: " . $e->getMessage();
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom_instituto = trim($_POST['nom_instituto'] ?? '');
  $nom_directora = trim($_POST['nom_directora'] ?? '');
  $ci_directora = trim($_POST['ci_directora'] ?? '');
  $direccion = trim($_POST['direccion'] ?? '');

  // Validaciones básicas
  if (empty($nom_instituto)) {
    $error_msg = "El nombre de la institución es obligatorio";
  } else {
    try {
      // Verificar si ya existe un registro en globales
      $checkSql = "SELECT id_globales FROM globales LIMIT 1";
      $checkStmt = $pdo->prepare($checkSql);
      $checkStmt->execute();
      $exists = $checkStmt->fetch();

      if ($exists) {
        // Actualizar el registro existente
        $sql = "UPDATE globales SET 
                        nom_instituto = ?, 
                        nom_directora = ?, 
                        ci_directora = ?, 
                        direccion = ?
                        WHERE id_globales = 1";
      } else {
        // Insertar nuevo registro con valores por defecto para otros campos
        $sql = "INSERT INTO globales (nom_instituto, nom_directora, ci_directora, direccion, 
                        edad_min, edad_max, id_periodo) 
                        VALUES (?, ?, ?, ?, 3, 19, 1)";
      }

      $stmt = $pdo->prepare($sql);

      if ($exists) {
        $success = $stmt->execute([
          $nom_instituto,
          $nom_directora,
          $ci_directora,
          $direccion
        ]);
      } else {
        $success = $stmt->execute([
          $nom_instituto,
          $nom_directora,
          $ci_directora,
          $direccion
        ]);
      }

      if ($success) {
        $_SESSION['success_msg'] = "Información de la institución actualizada correctamente";
        // Recargar datos
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
      } else {
        $error_msg = "Error al actualizar la información";
      }
    } catch (PDOException $e) {
      $error_msg = "Error en la base de datos: " . $e->getMessage();
    }
  }
}

include_once("/xampp/htdocs/final/layout/layaout1.php");

?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <h1 class="mb-0">
            <i class="fas fa-school mr-2"></i>
            Configuración de la Institución
          </h1>
          <p class="text-muted">Administra la información general de la institución educativa</p>
        </div>
      </div>

      <?php if (isset($error_msg)): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <i class="icon fas fa-ban"></i>
          <?php echo htmlspecialchars($error_msg); ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <i class="icon fas fa-check"></i>
          <?php echo htmlspecialchars($_SESSION['success_msg']); ?>
        </div>
        <?php unset($_SESSION['success_msg']); ?>
      <?php endif; ?>

      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle mr-2"></i>
                Información General
              </h3>
            </div>
            <form method="POST" action="">
              <div class="card-body">
                <div class="form-group">
                  <label for="nom_instituto">
                    <i class="fas fa-university mr-1"></i>
                    Nombre de la Institución *
                  </label>
                  <input type="text"
                    class="form-control"
                    id="nom_instituto"
                    name="nom_instituto"
                    placeholder="Ej: Unidad Educativa 'Nuevo Horizonte'"
                    value="<?php echo htmlspecialchars($infoInstitucion['nom_instituto'] ?? ''); ?>"
                    required>
                  <small class="text-muted">Nombre completo de la institución educativa</small>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="nom_directora">
                        <i class="fas fa-user-tie mr-1"></i>
                        Nombre de la Directora
                      </label>
                      <input type="text"
                        class="form-control"
                        id="nom_directora"
                        name="nom_directora"
                        placeholder="Ej: María Rodríguez"
                        value="<?php echo htmlspecialchars($infoInstitucion['nom_directora'] ?? ''); ?>">
                      <small class="text-muted">Nombre completo de la directora actual</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ci_directora">
                        <i class="fas fa-id-card mr-1"></i>
                        Cédula de la Directora
                      </label>
                      <input type="text"
                        class="form-control"
                        id="ci_directora"
                        name="ci_directora"
                        placeholder="Ej: V-12.345.678"
                        value="<?php echo htmlspecialchars($infoInstitucion['ci_directora'] ?? ''); ?>">
                      <small class="text-muted">Número de cédula de identidad</small>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="direccion">
                    <i class="fas fa-map-marker-alt mr-1"></i>
                    Dirección de la Institución
                  </label>
                  <textarea class="form-control"
                    id="direccion"
                    name="direccion"
                    rows="3"
                    placeholder="Ej: Av. Principal #123, Sector El Paraíso, Caracas"><?php echo htmlspecialchars($infoInstitucion['direccion'] ?? ''); ?></textarea>
                  <small class="text-muted">Dirección completa de la institución</small>
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save mr-2"></i>
                  Guardar Cambios
                </button>
                <a href="index.php" class="btn btn-default">
                  <i class="fas fa-times mr-2"></i>
                  Cancelar
                </a>
              </div>
            </form>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-lightbulb mr-2"></i>
                Información Importante
              </h3>
            </div>
            <div class="card-body">
              <p><strong>Nombre de la Institución:</strong> Aparecerá en:</p>
              <ul>
                <li>Reportes e impresiones</li>
                <li>Comunicados oficiales</li>
                <li>Certificados y constancias</li>
                <li>Interfaz del sistema</li>
              </ul>

              <hr>

              <p><strong>Datos de la Directora:</strong> Se utilizarán para:</p>
              <ul>
                <li>Firmas en documentos oficiales</li>
                <li>Autorizaciones</li>
                <li>Comunicaciones formales</li>
              </ul>

              <hr>

              <p><strong>Dirección:</strong> Es importante para:</p>
              <ul>
                <li>Comunicaciones postales</li>
                <li>Ubicación en documentos</li>
                <li>Referencia para padres y representantes</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .card {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
  }

  .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
  }

  .card-title {
    color: #495057;
    font-weight: 600;
  }

  .form-control:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }

  textarea.form-control {
    resize: vertical;
    min-height: 100px;
  }
</style>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");
?>