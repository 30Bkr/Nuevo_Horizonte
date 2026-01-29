<?php
// admin/configuraciones/configuracion/institucion.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Establecer título de página
$_SESSION['page_title'] = 'Configuración de la Institución';

// Incluir archivos necesarios
require_once '/xampp/htdocs/final/global/protect.php';
require_once '/xampp/htdocs/final/global/check_permissions.php';
require_once '/xampp/htdocs/final/global/notifications.php';
require_once '/xampp/htdocs/final/app/conexion.php';

// Verificar permisos específicos para esta página
if (!PermissionManager::canViewAny(['admin/configuraciones/configuracion/institucion.php'])) {
  Notification::set("No tienes permisos para acceder a esta sección", "error");
  header('Location: ' . URL . '/admin/index.php');
  exit();
}

// Incluir controlador de globales
require_once '/xampp/htdocs/final/app/controllers/globales/globales.php';

$conexion = new Conexion();
$pdo = $conexion->conectar();
$globalesController = new GlobalesController($pdo);

// Obtener la versión activa actual
$infoInstitucion = [];
try {
  $sql = "SELECT g.*, 
                   u.usuario,
                   CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_usuario
            FROM globales g
            LEFT JOIN usuarios u ON g.id_usuario_modificacion = u.id_usuario
            LEFT JOIN personas p ON u.id_persona = p.id_persona
            WHERE g.es_activo = 1 
            ORDER BY g.version DESC 
            LIMIT 1";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $infoInstitucion = $stmt->fetch(PDO::FETCH_ASSOC);

  // Si no hay registro activo, obtener el último
  if (!$infoInstitucion) {
    $sql = "SELECT g.*, 
                       u.usuario,
                       CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_usuario
                FROM globales g
                LEFT JOIN usuarios u ON g.id_usuario_modificacion = u.id_usuario
                LEFT JOIN personas p ON u.id_persona = p.id_persona
                ORDER BY g.version DESC 
                LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $infoInstitucion = $stmt->fetch(PDO::FETCH_ASSOC);
  }
} catch (PDOException $e) {
  Notification::set("Error al cargar información: " . $e->getMessage(), "error");
  $infoInstitucion = [];
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nom_instituto = trim($_POST['nom_instituto'] ?? '');
  $nom_directora = trim($_POST['nom_directora'] ?? '');
  $ci_directora = trim($_POST['ci_directora'] ?? '');
  $direccion = trim($_POST['direccion'] ?? '');
  $motivo_cambio = trim($_POST['motivo_cambio'] ?? 'Actualización de información institucional');

  // Obtener ID del usuario actual DESDE LA SESIÓN
  $id_usuario_actual = $_SESSION['usuario_id'] ?? 0;
  $nombre_usuario = $_SESSION['usuario_nombre'] ?? 'Usuario';

  if (empty($nom_instituto)) {
    Notification::set("El nombre de la institución es obligatorio", "error");
  } elseif (empty($motivo_cambio)) {
    Notification::set("Debe especificar un motivo para la modificación", "error");
  } else {
    try {
      // Iniciar transacción
      $pdo->beginTransaction();

      // 1. Desactivar registro actual (si existe)
      $updateSql = "UPDATE globales SET es_activo = 0 WHERE es_activo = 1";
      $updateStmt = $pdo->prepare($updateSql);
      $updateStmt->execute();

      // 2. Obtener siguiente versión
      $versionSql = "SELECT COALESCE(MAX(version), 0) + 1 as nueva_version FROM globales";
      $versionStmt = $pdo->prepare($versionSql);
      $versionStmt->execute();
      $nuevaVersion = $versionStmt->fetchColumn();

      // 3. Verificar periodo activo
      $periodoSql = "SELECT id_periodo FROM periodos WHERE estatus = 1 LIMIT 1";
      $periodoStmt = $pdo->prepare($periodoSql);
      $periodoStmt->execute();
      $id_periodo = $periodoStmt->fetchColumn();

      if (!$id_periodo) {
        // Crear periodo por defecto si no existe
        $periodoDefault = "SELECT id_periodo FROM periodos ORDER BY id_periodo DESC LIMIT 1";
        $stmtPeriodo = $pdo->prepare($periodoDefault);
        $stmtPeriodo->execute();
        $id_periodo = $stmtPeriodo->fetchColumn() ?: 1;
      }

      // 4. Insertar nueva versión
      $insertSql = "INSERT INTO globales (
                nom_instituto, nom_directora, ci_directora, direccion,
                edad_min, edad_max, id_periodo,
                version, es_activo, id_usuario_modificacion, motivo_cambio, fecha_modificacion
            ) VALUES (?, ?, ?, ?, 3, 19, ?, ?, 1, ?, ?, NOW())";

      $insertStmt = $pdo->prepare($insertSql);
      $success = $insertStmt->execute([
        $nom_instituto,
        $nom_directora,
        $ci_directora,
        $direccion,
        $id_periodo,
        $nuevaVersion,
        $id_usuario_actual,
        $motivo_cambio
      ]);

      if ($success) {
        $pdo->commit();
        Notification::set("✅ Información actualizada correctamente (Versión $nuevaVersion)", "success");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
      } else {
        Notification::set("Error al guardar la nueva versión", "error");
        $pdo->rollBack();
      }
    } catch (PDOException $e) {
      $pdo->rollBack();
      Notification::set("Error en la base de datos: " . $e->getMessage(), "error");
    }
  }
}

// Incluir layout1.php al inicio
require_once '/xampp/htdocs/final/layout/layaout1.php';
?>

<div class="content-wrapper" style="margin-left: 250px;">
  <!-- Content Header -->
  <div class="content-header">
    <?php
    // Mostrar notificaciones
    Notification::show();
    ?>
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Configuración de la Institución</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/index.php">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?= URL; ?>/admin/configuraciones/index.php">Configuraciones</a></li>
            <li class="breadcrumb-item active">Institución</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <!-- Header -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">
                <i class="fas fa-school mr-2"></i>
                Configuración de la Institución
              </h1>
              <p class="text-muted">Administra la información general de la institución educativa</p>
            </div>
            <div>
              <a href="historial_institucion.php" class="btn btn-info">
                <i class="fas fa-history mr-2"></i>
                Ver Historial de Modificaciones
              </a>
              <a href="<?= URL; ?>/admin/configuraciones/index.php" class="btn btn-secondary ml-2">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle mr-2"></i>
                Información General
                <?php if (isset($infoInstitucion['version'])): ?>
                  <span class="badge badge-light ml-2">Versión <?php echo $infoInstitucion['version']; ?></span>
                <?php endif; ?>
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
                        Nombre del Director(a)
                      </label>
                      <input type="text"
                        class="form-control"
                        id="nom_directora"
                        name="nom_directora"
                        placeholder="Ej: María Rodríguez"
                        value="<?php echo htmlspecialchars($infoInstitucion['nom_directora'] ?? ''); ?>">
                      <small class="text-muted">Nombre completo del director(a) actual</small>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ci_directora">
                        <i class="fas fa-id-card mr-1"></i>
                        Cédula del Director(a)
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

                <div class="form-group">
                  <label for="motivo_cambio">
                    <i class="fas fa-sticky-note mr-1"></i>
                    Motivo de la modificación *
                  </label>
                  <textarea class="form-control"
                    id="motivo_cambio"
                    name="motivo_cambio"
                    rows="2"
                    placeholder="Ej: Cambio de directora, Actualización de datos, Corrección de información, etc."
                    required><?php echo htmlspecialchars($infoInstitucion['motivo_cambio'] ?? 'Actualización de información institucional'); ?></textarea>
                  <small class="text-muted">Explique brevemente por qué realiza este cambio</small>
                </div>

                <?php if (isset($infoInstitucion['nombre_usuario']) && isset($infoInstitucion['fecha_modificacion'])): ?>
                  <div class="alert alert-light border mt-3">
                    <small>
                      <i class="fas fa-info-circle mr-1"></i>
                      <strong>Información de la versión actual:</strong><br>
                      <i class="fas fa-user-edit mr-1"></i>
                      Última modificación por: <strong><?php echo htmlspecialchars($infoInstitucion['nombre_usuario']); ?></strong><br>
                      <i class="fas fa-calendar-alt mr-1"></i>
                      Fecha: <?php echo date('d/m/Y H:i', strtotime($infoInstitucion['fecha_modificacion'])); ?><br>
                      <?php if (!empty($infoInstitucion['motivo_cambio'])): ?>
                        <i class="fas fa-sticky-note mr-1"></i>
                        Motivo: <?php echo htmlspecialchars($infoInstitucion['motivo_cambio']); ?>
                      <?php endif; ?>
                    </small>
                  </div>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save mr-2"></i>
                  Guardar
                </button>
                <button type="reset" class="btn btn-default">
                  <i class="fas fa-undo mr-2"></i>
                  Limpiar
                </button>
              </div>
            </form>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-lightbulb mr-2"></i>
                Sistema de Versiones
              </h3>
            </div>
            <div class="card-body">
              <p><strong>¿Cómo funciona?</strong></p>
              <ul class="mb-3">
                <li>✅ Cada modificación crea una nueva versión</li>
                <li>✅ Se mantiene el historial completo</li>
                <li>✅ Solo la última versión está activa</li>
                <li>✅ Puedes ver modificaciones anteriores</li>
              </ul>

              <hr>

              <p><strong>Beneficios:</strong></p>
              <ul class="mb-3">
                <li>📋 Auditoría de modificaciones</li>
                <li>↩️ Recuperación de versiones anteriores</li>
                <li>👤 Control de quién modificó qué</li>
                <li>📝 Registro de motivos de modificaciones</li>
              </ul>

              <hr>

              <p><strong>Usuario actual:</strong></p>
              <div class="alert alert-secondary py-2">
                <i class="fas fa-user mr-2"></i>
                <strong><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></strong>
                <small class="d-block text-muted mt-1">
                  <i class="fas fa-id-card mr-1"></i>
                  ID: <?php echo $_SESSION['usuario_id'] ?? 'N/A'; ?>
                </small>
              </div>

              <div class="text-center mt-3">
                <a href="historial_institucion.php" class="btn btn-outline-info btn-block">
                  <i class="fas fa-history mr-1"></i>
                  Explorar Historial Completo
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
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
    min-height: 80px;
  }

  .badge-light {
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #dee2e6;
  }

  .content-wrapper {
    transition: margin-left 0.3s ease;
  }
</style>

<?php
// Incluir layout2.php al final
require_once '/xampp/htdocs/final/layout/layaout2.php';
?>