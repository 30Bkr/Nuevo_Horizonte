<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';

if (!isset($_GET['cedula'])) {
    echo '<div class="alert alert-danger">Cédula no especificada.</div>';
    exit();
}

$cedula = $_GET['cedula'];

try {
    $database = new Conexion();
    $db = $database->conectar();
    
    // Consulta para obtener información completa del estudiante
    $query = "SELECT 
                p.*,
                e.id_estudiante,
                e.creacion as fecha_registro,
                d.direccion,
                d.calle,
                d.casa,
                par.nom_parroquia,
                mun.nom_municipio,
                est.nom_estado,
                GROUP_CONCAT(DISTINCT pat.nom_patologia SEPARATOR ', ') as patologias,
                GROUP_CONCAT(DISTINCT disc.nom_discapacidad SEPARATOR ', ') as discapacidades
              FROM personas p
              INNER JOIN estudiantes e ON p.id_persona = e.id_persona
              INNER JOIN direcciones d ON p.id_direccion = d.id_direccion
              INNER JOIN parroquias par ON d.id_parroquia = par.id_parroquia
              INNER JOIN municipios mun ON par.id_municipio = mun.id_municipio
              INNER JOIN estados est ON mun.id_estado = est.id_estado
              LEFT JOIN estudiantes_patologias ep ON e.id_estudiante = ep.id_estudiante
              LEFT JOIN patologias pat ON ep.id_patologia = pat.id_patologia
              LEFT JOIN estudiantes_discapacidades ed ON e.id_estudiante = ed.id_estudiante
              LEFT JOIN discapacidades disc ON ed.id_discapacidad = disc.id_discapacidad
              WHERE p.cedula = :cedula
              GROUP BY p.id_persona";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cedula', $cedula);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calcular edad
        $edad = '';
        if ($estudiante['fecha_nac']) {
            $fecha_nac = new DateTime($estudiante['fecha_nac']);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nac)->y;
        }
        
        echo '
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Información Personal del Estudiante</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="photo-container" style="border: 2px solid #ddd; padding: 10px; border-radius: 5px; background: #f9f9f9;">
                                    <h5><i class="fas fa-id-card"></i> Foto Carnet</h5>';
                                    
        // Mostrar foto del estudiante
        if (!empty($estudiante['foto_estudiante']) && file_exists($_SERVER['DOCUMENT_ROOT'] . $estudiante['foto_estudiante'])) {
            echo '<img src="' . htmlspecialchars($estudiante['foto_estudiante']) . '" 
                      alt="Foto del estudiante" 
                      class="img-thumbnail mt-2" 
                      style="max-width: 150px; max-height: 180px; object-fit: cover;">';
        } else {
            echo '<div class="mt-3">
                    <i class="fas fa-user fa-5x text-muted"></i><br>
                    <small class="text-muted">Sin foto carnet</small>
                  </div>';
        }
        
        // Botón para cambiar foto (SIN verificación de rol)
        echo '<div class="mt-3">
                <button type="button" class="btn btn-outline-primary btn-sm btn-block" 
                        onclick="subirFotoEstudiante(\'' . htmlspecialchars($estudiante['cedula']) . '\')">
                    <i class="fas fa-camera"></i> Cambiar Foto
                </button>
              </div>';
        
        echo '              </div>
                                <div class="mt-3">
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <th width="40%">Cédula:</th>
                                            <td>' . htmlspecialchars($estudiante['cedula']) . '</td>
                                        </tr>
                                        <tr>
                                            <th>Edad:</th>
                                            <td>' . $edad . ' años</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><i class="fas fa-user"></i> Datos Personales</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Nombre Completo:</th>
                                                <td>' . htmlspecialchars($estudiante['primer_nombre'] . ' ' . 
                                                    ($estudiante['segundo_nombre'] ? $estudiante['segundo_nombre'] . ' ' : '') . 
                                                    $estudiante['primer_apellido'] . ' ' . 
                                                    ($estudiante['segundo_apellido'] ? $estudiante['segundo_apellido'] : '')) . '</td>
                                            </tr>
                                            <tr>
                                                <th>Fecha de Nacimiento:</th>
                                                <td>' . ($estudiante['fecha_nac'] ? date('d/m/Y', strtotime($estudiante['fecha_nac'])) : 'No especificada') . '</td>
                                            </tr>
                                            <tr>
                                                <th>Sexo:</th>
                                                <td>' . htmlspecialchars($estudiante['sexo']) . '</td>
                                            </tr>
                                            <tr>
                                                <th>Lugar de Nacimiento:</th>
                                                <td>' . htmlspecialchars($estudiante['lugar_nac']) . '</td>
                                            </tr>
                                            <tr>
                                                <th>Nacionalidad:</th>
                                                <td>' . htmlspecialchars($estudiante['nacionalidad']) . '</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h5><i class="fas fa-phone"></i> Información de Contacto</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="30%">Teléfono Móvil:</th>
                                                <td>' . htmlspecialchars($estudiante['telefono'] ?: 'No especificado') . '</td>
                                            </tr>
                                            <tr>
                                                <th>Teléfono Habitación:</th>
                                                <td>' . htmlspecialchars($estudiante['telefono_hab'] ?: 'No especificado') . '</td>
                                            </tr>
                                            <tr>
                                                <th>Correo Electrónico:</th>
                                                <td>' . htmlspecialchars($estudiante['correo'] ?: 'No especificado') . '</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5><i class="fas fa-home"></i> Dirección</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Dirección:</th>
                                        <td>' . htmlspecialchars($estudiante['direccion']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Calle/Avenida:</th>
                                        <td>' . htmlspecialchars($estudiante['calle'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Casa/Edificio:</th>
                                        <td>' . htmlspecialchars($estudiante['casa'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Parroquia:</th>
                                        <td>' . htmlspecialchars($estudiante['nom_parroquia']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Municipio:</th>
                                        <td>' . htmlspecialchars($estudiante['nom_municipio']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td>' . htmlspecialchars($estudiante['nom_estado']) . '</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><i class="fas fa-heartbeat"></i> Información Médica</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Patologías/Enfermedades:</th>
                                        <td>' . ($estudiante['patologias'] ? htmlspecialchars($estudiante['patologias']) : 'Ninguna registrada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Discapacidades:</th>
                                        <td>' . ($estudiante['discapacidades'] ? htmlspecialchars($estudiante['discapacidades']) : 'Ninguna registrada') . '</td>
                                    </tr>
                                </table>
                                
                                <h5 class="mt-3"><i class="fas fa-info-circle"></i> Información del Registro</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Fecha de Registro:</th>
                                        <td>' . date('d/m/Y H:i:s', strtotime($estudiante['fecha_registro'])) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td><span class="badge badge-success">Activo</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        function subirFotoEstudiante(cedula) {
            // Esta función se implementará en el archivo principal
            // que contiene el modal para subir fotos
            if (typeof abrirModalFoto === "function") {
                abrirModalFoto(cedula, "estudiante");
            } else {
                alert("Funcionalidad no disponible");
            }
        }
        </script>';
    } else {
        echo '<div class="alert alert-warning">No se encontró información del estudiante.</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar la información: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>