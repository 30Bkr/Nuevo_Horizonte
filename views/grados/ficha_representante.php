<?php
session_start();
include_once __DIR__ . '/../../app/conexion.php';

if (!isset($_GET['cedula_estudiante'])) {
    echo '<div class="alert alert-danger">Cédula del estudiante no especificada.</div>';
    exit();
}

$cedula_estudiante = $_GET['cedula_estudiante'];

try {
    $database = new Conexion();
    $db = $database->conectar();
    
    // Consulta para obtener información del representante a través del estudiante
    $query = "SELECT 
                rp.*,
                r.ocupacion,
                r.lugar_trabajo,
                pr.profesion,
                par.parentesco,
                d.direccion,
                d.calle,
                d.casa,
                parr.nom_parroquia,
                mun.nom_municipio,
                est.nom_estado
              FROM personas p_est
              INNER JOIN estudiantes e ON p_est.id_persona = e.id_persona
              INNER JOIN estudiantes_representantes er ON e.id_estudiante = er.id_estudiante
              INNER JOIN representantes r ON er.id_representante = r.id_representante
              INNER JOIN personas rp ON r.id_persona = rp.id_persona
              INNER JOIN parentesco par ON er.id_parentesco = par.id_parentesco
              INNER JOIN direcciones d ON rp.id_direccion = d.id_direccion
              INNER JOIN parroquias parr ON d.id_parroquia = parr.id_parroquia
              INNER JOIN municipios mun ON parr.id_municipio = mun.id_municipio
              INNER JOIN estados est ON mun.id_estado = est.id_estado
              LEFT JOIN profesiones pr ON r.id_profesion = pr.id_profesion
              WHERE p_est.cedula = :cedula_estudiante
              LIMIT 1";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':cedula_estudiante', $cedula_estudiante);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $representante = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calcular edad
        $edad = '';
        if ($representante['fecha_nac']) {
            $fecha_nac = new DateTime($representante['fecha_nac']);
            $hoy = new DateTime();
            $edad = $hoy->diff($fecha_nac)->y;
        }
        
        echo '
        <div class="row">
            <div class="col-md-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Información del Representante</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-id-card"></i> Datos de Identificación</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Cédula:</th>
                                        <td>' . htmlspecialchars($representante['cedula']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Nombre Completo:</th>
                                        <td>' . htmlspecialchars($representante['primer_nombre'] . ' ' . 
                                            ($representante['segundo_nombre'] ? $representante['segundo_nombre'] . ' ' : '') . 
                                            $representante['primer_apellido'] . ' ' . 
                                            ($representante['segundo_apellido'] ? $representante['segundo_apellido'] : '')) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de Nacimiento:</th>
                                        <td>' . ($representante['fecha_nac'] ? date('d/m/Y', strtotime($representante['fecha_nac'])) : 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Edad:</th>
                                        <td>' . $edad . ' años</td>
                                    </tr>
                                    <tr>
                                        <th>Sexo:</th>
                                        <td>' . htmlspecialchars($representante['sexo']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Lugar de Nacimiento:</th>
                                        <td>' . htmlspecialchars($representante['lugar_nac']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Nacionalidad:</th>
                                        <td>' . htmlspecialchars($representante['nacionalidad']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Parentesco:</th>
                                        <td>' . htmlspecialchars($representante['parentesco']) . '</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><i class="fas fa-phone"></i> Información de Contacto</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Teléfono Móvil:</th>
                                        <td>' . htmlspecialchars($representante['telefono'] ?: 'No especificado') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Teléfono Habitación:</th>
                                        <td>' . htmlspecialchars($representante['telefono_hab'] ?: 'No especificado') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Correo Electrónico:</th>
                                        <td>' . htmlspecialchars($representante['correo'] ?: 'No especificado') . '</td>
                                    </tr>
                                </table>
                                
                                <h5><i class="fas fa-briefcase"></i> Información Laboral</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Profesión:</th>
                                        <td>' . htmlspecialchars($representante['profesion'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Ocupación:</th>
                                        <td>' . htmlspecialchars($representante['ocupacion'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Lugar de Trabajo:</th>
                                        <td>' . htmlspecialchars($representante['lugar_trabajo'] ?: 'No especificado') . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5><i class="fas fa-home"></i> Dirección</h5>
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="20%">Dirección:</th>
                                        <td>' . htmlspecialchars($representante['direccion']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Calle/Avenida:</th>
                                        <td>' . htmlspecialchars($representante['calle'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Casa/Edificio:</th>
                                        <td>' . htmlspecialchars($representante['casa'] ?: 'No especificada') . '</td>
                                    </tr>
                                    <tr>
                                        <th>Parroquia:</th>
                                        <td>' . htmlspecialchars($representante['nom_parroquia']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Municipio:</th>
                                        <td>' . htmlspecialchars($representante['nom_municipio']) . '</td>
                                    </tr>
                                    <tr>
                                        <th>Estado:</th>
                                        <td>' . htmlspecialchars($representante['nom_estado']) . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
    } else {
        echo '<div class="alert alert-warning">No se encontró información del representante para este estudiante.</div>';
    }
    
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Error al cargar la información: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
?>