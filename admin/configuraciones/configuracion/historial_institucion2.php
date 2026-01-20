<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verificar permisos de usuario
if (!isset($_SESSION['usuario_id'])) {
  $_SESSION['mensaje'] = "Debe iniciar sesión para acceder a esta página";
  header('Location: /final/login/index.php');
  exit();
}

// configuracion/historial_institucion.php
include_once("/xampp/htdocs/final/app/conexion.php");

$conexion = new Conexion();
$pdo = $conexion->conectar();

// Obtener historial completo
$historial = [];
$totalVersiones = 0;
$versionActiva = 0;

try {
  // Consulta principal para obtener historial
  $sql = "SELECT 
                g.*,
                u.usuario,
                CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_completo,
                CASE 
                    WHEN g.es_activo = 1 THEN 'Activo'
                    ELSE 'Histórico'
                END as estado
            FROM globales g
            LEFT JOIN usuarios u ON g.id_usuario_modificacion = u.id_usuario
            LEFT JOIN personas p ON u.id_persona = p.id_persona
            ORDER BY g.version DESC";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $historial = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalVersiones = count($historial);

  // Obtener versión activa
  foreach ($historial as $registro) {
    if ($registro['es_activo'] == 1) {
      $versionActiva = $registro['version'];
      break;
    }
  }

  // Si no hay versión activa, usar la más reciente
  if ($versionActiva == 0 && $totalVersiones > 0) {
    $versionActiva = $historial[0]['version'];
  }
} catch (PDOException $e) {
  $error = "Error al cargar el historial: " . $e->getMessage();
}

// Restaurar versión (si se solicita)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['restaurar_version'])) {
  $version_a_restaurar = intval($_POST['restaurar_version']);
  $id_usuario_actual = $_SESSION['usuario_id'];
  $motivo = "Restauración a versión $version_a_restaurar por " . ($_SESSION['usuario_nombre'] ?? 'Usuario');

  try {
    $pdo->beginTransaction();

    // Desactivar versión actual
    $updateSql = "UPDATE globales SET es_activo = 0 WHERE es_activo = 1";
    $updateStmt = $pdo->prepare($updateSql);
    $updateStmt->execute();

    // Obtener datos de la versión a restaurar
    $selectSql = "SELECT * FROM globales WHERE version = ?";
    $selectStmt = $pdo->prepare($selectSql);
    $selectStmt->execute([$version_a_restaurar]);
    $versionAntigua = $selectStmt->fetch(PDO::FETCH_ASSOC);

    if ($versionAntigua) {
      // Obtener nueva versión
      $versionSql = "SELECT COALESCE(MAX(version), 0) + 1 as nueva_version FROM globales";
      $versionStmt = $pdo->prepare($versionSql);
      $versionStmt->execute();
      $nuevaVersion = $versionStmt->fetchColumn();

      // Insertar como nueva versión activa
      $insertSql = "INSERT INTO globales (
                nom_instituto, nom_directora, ci_directora, direccion,
                edad_min, edad_max, id_periodo,
                version, es_activo, id_usuario_modificacion, motivo_cambio, fecha_modificacion
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, NOW())";

      $insertStmt = $pdo->prepare($insertSql);
      $insertStmt->execute([
        $versionAntigua['nom_instituto'],
        $versionAntigua['nom_directora'],
        $versionAntigua['ci_directora'],
        $versionAntigua['direccion'],
        $versionAntigua['edad_min'],
        $versionAntigua['edad_max'],
        $versionAntigua['id_periodo'],
        $nuevaVersion,
        $id_usuario_actual,
        $motivo
      ]);

      $pdo->commit();
      $_SESSION['success_msg'] = "✅ Versión $version_a_restaurar restaurada correctamente como versión $nuevaVersion";
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      $error_msg = "Versión no encontrada";
      $pdo->rollBack();
    }
  } catch (PDOException $e) {
    $pdo->rollBack();
    $error_msg = "Error al restaurar versión: " . $e->getMessage();
  }
}

include_once("/xampp/htdocs/final/layout/layaout1.php");
?>

<div class="content-wrapper">
  <div class="content">
    <div class="container-fluid">
      <div class="row mb-4">
        <div class="col-12">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="mb-0">
                <i class="fas fa-history mr-2"></i>
                Historial de Cambios
              </h1>
              <p class="text-muted">Registro completo de todas las modificaciones realizadas</p>
            </div>
            <div>
              <a href="javascript:history.back()" class="btn btn-primary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Configuración
              </a>
            </div>
          </div>
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

      <!-- Panel de estadísticas -->
      <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
          <div class="info-box bg-primary">
            <span class="info-box-icon">
              <i class="fas fa-code-branch"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Versiones Totales</span>
              <span class="info-box-number"><?php echo $totalVersiones; ?></span>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="info-box bg-success">
            <span class="info-box-icon">
              <i class="fas fa-check-circle"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Versión Activa</span>
              <span class="info-box-number">v<?php echo $versionActiva; ?></span>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="info-box bg-info">
            <span class="info-box-icon">
              <i class="fas fa-user-edit"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Usuario Actual</span>
              <span class="info-box-number"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Usuario'); ?></span>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="info-box bg-warning">
            <span class="info-box-icon">
              <i class="fas fa-users"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Usuarios Únicos</span>
              <span class="info-box-number">
                <?php
                $usuariosUnicos = [];
                foreach ($historial as $r) {
                  if (!empty($r['nombre_completo'])) {
                    $usuariosUnicos[$r['nombre_completo']] = true;
                  }
                }
                echo count($usuariosUnicos);
                ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-list mr-2"></i>
                Registro de Versiones
              </h3>
              <div class="card-tools">
                <span class="badge badge-info">
                  <?php echo $totalVersiones; ?> registros encontrados
                </span>
              </div>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th style="width: 5%">Versión</th>
                      <th style="width: 20%">Institución</th>
                      <th style="width: 15%">Directora</th>
                      <th style="width: 10%">Cédula</th>
                      <th style="width: 15%">Modificado por</th>
                      <th style="width: 15%">Fecha</th>
                      <th style="width: 10%">Estado</th>
                      <th style="width: 10%">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($historial)): ?>
                      <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                          <i class="fas fa-inbox fa-3x mb-3"></i><br>
                          <h5>No hay registros de historial disponibles</h5>
                          <p class="mb-0">Los cambios aparecerán aquí después de actualizar la información</p>
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($historial as $registro): ?>
                        <?php $esActivo = ($registro['es_activo'] == 1); ?>
                        <tr class="<?php echo $esActivo ? 'table-success' : ''; ?>">
                          <td>
                            <span class="badge <?php echo $esActivo ? 'badge-success' : 'badge-secondary'; ?>">
                              v<?php echo $registro['version']; ?>
                            </span>
                          </td>
                          <td>
                            <strong><?php echo htmlspecialchars($registro['nom_instituto']); ?></strong>
                            <?php if (!empty($registro['motivo_cambio'])): ?>
                              <br>
                              <small class="text-muted">
                                <i class="fas fa-comment mr-1"></i>
                                <?php echo htmlspecialchars(substr($registro['motivo_cambio'], 0, 50)); ?>
                                <?php if (strlen($registro['motivo_cambio']) > 50): ?>...<?php endif; ?>
                              </small>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php echo htmlspecialchars($registro['nom_directora'] ?? 'N/A'); ?>
                          </td>
                          <td>
                            <code><?php echo htmlspecialchars($registro['ci_directora'] ?? 'N/A'); ?></code>
                          </td>
                          <td>
                            <small>
                              <i class="fas fa-user mr-1"></i>
                              <?php echo htmlspecialchars($registro['nombre_completo'] ?? 'Usuario ' . $registro['id_usuario_modificacion']); ?>
                              <?php if (!empty($registro['usuario'])): ?>
                                <br>
                                <small class="text-muted">
                                  <i class="fas fa-at mr-1"></i>
                                  <?php echo htmlspecialchars($registro['usuario']); ?>
                                </small>
                              <?php endif; ?>
                            </small>
                          </td>
                          <td>
                            <small>
                              <i class="fas fa-calendar mr-1"></i>
                              <?php echo date('d/m/Y', strtotime($registro['fecha_modificacion'])); ?><br>
                              <i class="fas fa-clock mr-1"></i>
                              <?php echo date('H:i:s', strtotime($registro['fecha_modificacion'])); ?>
                            </small>
                          </td>
                          <td>
                            <?php if ($esActivo): ?>
                              <span class="badge badge-success">
                                <i class="fas fa-check-circle mr-1"></i>
                                Activo
                              </span>
                            <?php else: ?>
                              <span class="badge badge-secondary">
                                <i class="fas fa-history mr-1"></i>
                                Histórico
                              </span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <div class="btn-group" role="group">
                              <button type="button"
                                class="btn btn-sm btn-outline-info"
                                data-toggle="modal"
                                data-target="#detalleModal<?php echo $registro['version']; ?>"
                                title="Ver detalles">
                                <i class="fas fa-eye"></i>
                              </button>

                              <?php if (!$esActivo): ?>
                                <form method="POST" action="" style="display: inline;">
                                  <input type="hidden" name="restaurar_version" value="<?php echo $registro['version']; ?>">
                                  <button type="submit"
                                    class="btn btn-sm btn-outline-warning"
                                    onclick="return confirm('¿Restaurar esta versión?\\n\\nVersión: v<?php echo $registro['version']; ?>\\nInstitución: <?php echo htmlspecialchars($registro['nom_instituto']); ?>\\n\\nLa versión actual pasará al historial y esta será la nueva versión activa.')"
                                    title="Restaurar esta versión">
                                    <i class="fas fa-undo"></i>
                                  </button>
                                </form>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>

                        <!-- Modal para detalles -->
                        <div class="modal fade" id="detalleModal<?php echo $registro['version']; ?>" tabindex="-1" role="dialog">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                              <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                  <i class="fas fa-info-circle mr-2"></i>
                                  Detalles Complejos - Versión <?php echo $registro['version']; ?>
                                  <?php if ($esActivo): ?>
                                    <span class="badge badge-light ml-2">ACTUAL</span>
                                  <?php endif; ?>
                                </h5>
                                <button type="button" class="close text-white" data-dismiss="modal">
                                  <span>&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <div class="row">
                                  <div class="col-md-6">
                                    <h6><i class="fas fa-university mr-2"></i>Información de la Institución</h6>
                                    <div class="card card-body mb-3">
                                      <strong>Nombre:</strong><br>
                                      <?php echo htmlspecialchars($registro['nom_instituto']); ?>
                                    </div>

                                    <h6><i class="fas fa-user-tie mr-2"></i>Directora</h6>
                                    <div class="card card-body mb-3">
                                      <strong>Nombre:</strong> <?php echo htmlspecialchars($registro['nom_directora'] ?? 'N/A'); ?><br>
                                      <strong>Cédula:</strong> <?php echo htmlspecialchars($registro['ci_directora'] ?? 'N/A'); ?>
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <h6><i class="fas fa-map-marker-alt mr-2"></i>Dirección</h6>
                                    <div class="card card-body mb-3">
                                      <?php echo nl2br(htmlspecialchars($registro['direccion'] ?? 'N/A')); ?>
                                    </div>

                                    <h6><i class="fas fa-cogs mr-2"></i>Parámetros</h6>
                                    <div class="card card-body mb-3">
                                      <strong>Edad mínima:</strong> <?php echo $registro['edad_min']; ?> años<br>
                                      <strong>Edad máxima:</strong> <?php echo $registro['edad_max']; ?> años<br>
                                      <strong>Periodo ID:</strong> <?php echo $registro['id_periodo']; ?>
                                    </div>
                                  </div>
                                </div>

                                <div class="row mt-3">
                                  <div class="col-md-12">
                                    <h6><i class="fas fa-sticky-note mr-2"></i>Motivo del Cambio</h6>
                                    <div class="card card-body bg-light">
                                      <?php echo nl2br(htmlspecialchars($registro['motivo_cambio'] ?? 'No especificado')); ?>
                                    </div>
                                  </div>
                                </div>

                                <div class="row mt-3">
                                  <div class="col-md-6">
                                    <h6><i class="fas fa-user-edit mr-2"></i>Información de Modificación</h6>
                                    <div class="card card-body">
                                      <strong>Usuario:</strong> <?php echo htmlspecialchars($registro['nombre_completo'] ?? 'N/A'); ?><br>
                                      <strong>Email:</strong> <?php echo htmlspecialchars($registro['usuario'] ?? 'N/A'); ?><br>
                                      <strong>ID Usuario:</strong> <?php echo $registro['id_usuario_modificacion']; ?>
                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <h6><i class="fas fa-calendar-alt mr-2"></i>Fechas</h6>
                                    <div class="card card-body">
                                      <strong>Modificación:</strong> <?php echo date('d/m/Y H:i:s', strtotime($registro['fecha_modificacion'])); ?><br>
                                      <strong>Estado:</strong>
                                      <?php if ($esActivo): ?>
                                        <span class="badge badge-success">Activo</span>
                                      <?php else: ?>
                                        <span class="badge badge-secondary">Histórico</span>
                                      <?php endif; ?>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                  <i class="fas fa-times mr-2"></i>
                                  Cerrar
                                </button>
                                <?php if (!$esActivo): ?>
                                  <form method="POST" action="">
                                    <input type="hidden" name="restaurar_version" value="<?php echo $registro['version']; ?>">
                                    <button type="submit"
                                      class="btn btn-warning"
                                      onclick="return confirm('¿Restaurar esta versión?\\n\\nLa versión actual pasará al historial y esta será la nueva versión activa.')">
                                      <i class="fas fa-undo mr-2"></i>
                                      Restaurar esta Versión
                                    </button>
                                  </form>
                                <?php endif; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="card-footer clearfix">
              <div class="float-left">
                <small class="text-muted">
                  <i class="fas fa-info-circle mr-1"></i>
                  Mostrando <?php echo $totalVersiones; ?> versiones en orden cronológico inverso
                </small>
              </div>
              <div class="float-right">
                <a href="javascript:history.back()" class="btn btn-primary btn-sm">
                  <i class="fas fa-edit mr-1"></i>
                  Crear Nueva Versión
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Información adicional -->
      <div class="row mt-4">
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-question-circle mr-2"></i>
                ¿Cómo funciona el sistema de versiones?
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4">
                  <h5><i class="fas fa-plus-circle text-success mr-2"></i>Crear Nueva Versión</h5>
                  <p>Al guardar cambios en la configuración, se crea una nueva versión manteniendo la anterior en el historial.</p>
                </div>
                <div class="col-md-4">
                  <h5><i class="fas fa-eye text-info mr-2"></i>Ver Historial</h5>
                  <p>Todas las versiones anteriores se mantienen para auditoría y pueden ser consultadas en cualquier momento.</p>
                </div>
                <div class="col-md-4">
                  <h5><i class="fas fa-undo text-warning mr-2"></i>Restaurar Versiones</h5>
                  <p>Puedes restaurar cualquier versión anterior, la cual se convertirá en la nueva versión activa.</p>
                </div>
              </div>
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

  .table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.03);
  }

  .info-box {
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    color: white;
  }

  .info-box-icon {
    border-radius: 0.5rem 0 0 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
  }

  .info-box-content {
    padding: 10px;
  }

  .badge {
    font-size: 0.85em;
    font-weight: 600;
  }

  .modal-lg {
    max-width: 800px;
  }
</style>

<script>
  // Script para mejorar la experiencia
  document.addEventListener('DOMContentLoaded', function() {
    // Agregar tooltips a los botones
    $('[title]').tooltip();

    // Confirmación antes de restaurar
    $('form[action=""] button[type="submit"]').on('click', function(e) {
      if (!confirm('¿Está seguro de restaurar esta versión?\n\nEsta acción no se puede deshacer.')) {
        e.preventDefault();
      }
    });

    // Mostrar mensaje si hay éxito
    <?php if (isset($_SESSION['success_msg'])): ?>
      setTimeout(function() {
        $('.alert-success').fadeOut(1000);
      }, 5000);
    <?php endif; ?>
  });
</script>

<?php
include_once("/xampp/htdocs/final/layout/layaout2.php");
include_once("/xampp/htdocs/final/layout/mensajes.php");

?>