<?php
session_start();

include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Docente.php';
include_once("/xampp/htdocs/final/layout/layaout1.php");


$database = new Conexion();
$db = $database->conectar();

$docente = new Docente($db);

// Obtener datos del docente a visualizar
$docente_encontrado = false;
if (isset($_GET['id'])) {
    $docente_encontrado = $docente->obtenerPorId($_GET['id']);
}

if (!$docente_encontrado) {
    $_SESSION['error'] = "Docente no encontrado";
    header("Location: docentes_list.php");
    exit();
}

// Obtener nombre de la profesión
$profesion_nombre = 'No especificado';
if ($docente->id_profesion) {
    $profesiones = $docente->obtenerProfesiones();
    while ($row = $profesiones->fetch(PDO::FETCH_ASSOC)) {
        if ($row['id_profesion'] == $docente->id_profesion) {
            $profesion_nombre = $row['profesion'];
            break;
        }
    }
}

// Obtener información completa de la ubicación
$estado_nombre = 'No especificado';
$municipio_nombre = 'No especificado';
$parroquia_nombre = 'No especificado';

if ($docente->id_parroquia) {
    $query = "SELECT 
                e.nom_estado,
                m.nom_municipio, 
                p.nom_parroquia
              FROM parroquias p
              INNER JOIN municipios m ON p.id_municipio = m.id_municipio
              INNER JOIN estados e ON m.id_estado = e.id_estado
              WHERE p.id_parroquia = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$docente->id_parroquia]);
    $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ubicacion) {
        $estado_nombre = $ubicacion['nom_estado'];
        $municipio_nombre = $ubicacion['nom_municipio'];
        $parroquia_nombre = $ubicacion['nom_parroquia'];
    }
}

// Formatear fecha de nacimiento
$fecha_nac_formateada = $docente->fecha_nac ? date('d/m/Y', strtotime($docente->fecha_nac)) : 'No especificado';

// Construir nombre completo
$nombre_completo = trim($docente->primer_nombre . ' ' .
    ($docente->segundo_nombre ? $docente->segundo_nombre . ' ' : '') .
    $docente->primer_apellido . ' ' .
    ($docente->segundo_apellido ? $docente->segundo_apellido : ''));

// Función para mostrar valor o texto por defecto
function mostrarValor($valor, $textoDefecto = 'No especificado')
{
    return !empty($valor) ? htmlspecialchars($valor) : $textoDefecto;
}
?>

<style>
    .info-section {
        margin-bottom: 1.5rem;
    }

    .section-title {
        color: #495057;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .info-group {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 40%;
    }

    .info-value {
        color: #212529;
        text-align: right;
        flex: 1;
    }

    .card-header-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .badge-estado {
        font-size: 0.85rem;
    }

    .ubicacion-completa {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-top: 10px;
    }

    .ubicacion-item {
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid #dee2e6;
    }

    .ubicacion-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .info-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-value {
            text-align: left;
            margin-top: 0.25rem;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Información del Docente</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/final/index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="docentes_list.php">Docentes</a></li>
                        <li class="breadcrumb-item active">Ver</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Mostrar mensajes de sesión -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-check"></i> <?php echo $_SESSION['success'];
                                                        unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fas fa-ban"></i> <?php echo $_SESSION['error'];
                                                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <div class="card-header-actions">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-user-tie mr-2"></i><?php echo htmlspecialchars($nombre_completo); ?>
                                </h3>
                                <!-- <div class="card-tools">
                                    <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-id-card mr-1"></i> Datos Personales
                                        </h5>
                                        <div class="info-group">
                                            <span class="info-label">Cédula:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->cedula); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Nacionalidad:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->nacionalidad); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Nombre Completo:</span>
                                            <span class="info-value"><?php echo htmlspecialchars($nombre_completo); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Fecha Nacimiento:</span>
                                            <span class="info-value"><?php echo $fecha_nac_formateada; ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Lugar Nacimiento:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->lugar_nac); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Sexo:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->sexo); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-address-book mr-1"></i> Información de Contacto
                                        </h5>
                                        <div class="info-group">
                                            <span class="info-label">Teléfono Móvil:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->telefono); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Teléfono Habitación:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->telefono_hab); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Correo Electrónico:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->correo); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Profesión:</span>
                                            <span class="info-value"><?php echo htmlspecialchars($profesion_nombre); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-map-marker-alt mr-1"></i> Información de Dirección
                                        </h5>
                                        
                                        <div class="ubicacion-completa">
                                            <div class="ubicacion-item">
                                                <strong>Estado:</strong> <?php echo $estado_nombre; ?>
                                            </div>
                                            <div class="ubicacion-item">
                                                <strong>Municipio:</strong> <?php echo $municipio_nombre; ?>
                                            </div>
                                            <div class="ubicacion-item">
                                                <strong>Parroquia:</strong> <?php echo $parroquia_nombre; ?>
                                            </div>
                                            <div class="ubicacion-item">
                                                <strong>Dirección Completa:</strong> <?php echo mostrarValor($docente->direccion); ?>
                                            </div>
                                            <div class="ubicacion-item">
                                                <strong>Calle/Avenida:</strong> <?php echo mostrarValor($docente->calle); ?>
                                            </div>
                                            <div class="ubicacion-item">
                                                <strong>Casa/Edificio:</strong> <?php echo mostrarValor($docente->casa); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-section">
                                        <h5 class="section-title">
                                            <i class="fas fa-user-circle mr-1"></i> Información del Usuario
                                        </h5>
                                        <div class="info-group">
                                            <span class="info-label">Usuario:</span>
                                            <span class="info-value"><?php echo mostrarValor($docente->usuario); ?></span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Rol:</span>
                                            <span class="info-value">Docente</span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Estado:</span>
                                            <span class="info-value">
                                                <?php if ($docente->estatus == 1): ?>
                                                    <span class="badge badge-success badge-estado">Activo</span>
                                                <?php else: ?>
                                                    <span class="badge badge-danger badge-estado">Inactivo</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="info-group">
                                            <span class="info-label">Fecha Registro:</span>
                                            <span class="info-value">
                                                <?php echo $docente->creacion ? date('d/m/Y H:i', strtotime($docente->creacion)) : 'No especificado'; ?>
                                            </span>
                                        </div>
                                        <?php if ($docente->actualizacion): ?>
                                        <div class="info-group">
                                            <span class="info-label">Última Actualización:</span>
                                            <span class="info-value">
                                                <?php echo date('d/m/Y H:i', strtotime($docente->actualizacion)); ?>
                                            </span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-between">
                                <a href="docentes_list.php" class="btn btn-default">
                                    <i class="fas fa-arrow-left mr-1"></i> Volver al Listado
                                </a>
                                <div>
                                    <a href="docente_editar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit mr-1"></i> Editar Información
                                    </a>
                                    <!-- <?php if ($docente->estatus == 1): ?>  -->
                                        <!-- <a href="docente_desactivar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-warning" onclick="return confirm('¿Está seguro de que desea desactivar este docente?')">
                                            <i class="fas fa-user-slash mr-1"></i> Desactivar
                                        </a> -->
                                    <!-- <?php else: ?> -->
                                        <!-- <a href="docente_activar.php?id=<?php echo $docente->id_docente; ?>" class="btn btn-success" onclick="return confirm('¿Está seguro de que desea activar este docente?')">
                                            <i class="fas fa-user-check mr-1"></i> Activar
                                        </a> -->
                                    <!-- <?php endif; ?> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include_once('../../layout/layaout2.php');
include_once('../../layout/mensajes.php');
?>