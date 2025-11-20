<?php
session_start();

// Incluir autoload de Composer para HTML2PDF
require_once __DIR__ . '/../../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// Incluir archivos de la aplicación
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../models/Grado.php';

try {
    // Obtener información del grado/sección
    $id_nivel_seccion = $_GET['id_nivel_seccion'] ?? 0;

    // Validar que se haya proporcionado un ID válido
    if ($id_nivel_seccion == 0) {
        throw new Exception("Debe seleccionar un grado/sección válido");
    }

    // Obtener datos de la base de datos
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);

    $info_grado = $grado->obtenerGradoPorId($id_nivel_seccion);
    if (!$info_grado) {
        throw new Exception("Grado/Sección no encontrado");
    }

    // Obtener estudiantes del grado/sección
    $estudiantes = $grado->obtenerEstudiantesPorGrado($id_nivel_seccion);

    // Ruta de la imagen del cintillo
    $ruta_cintillo = __DIR__ . '/../../public/images/cintillo_oficial.png';
    $cintillo_base64 = '';
    
    // Convertir imagen a base64 para incluirla en el HTML
    if (file_exists($ruta_cintillo)) {
        $image_data = file_get_contents($ruta_cintillo);
        $cintillo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
    }

    // Calcular estadísticas
    $totalEstudiantes = $estudiantes->rowCount();
    $porcentajeOcupacion = $info_grado['capacidad'] > 0 ? ($totalEstudiantes / $info_grado['capacidad']) * 100 : 0;
    $cuposDisponibles = $info_grado['capacidad'] - $totalEstudiantes;

    // Crear contenido HTML para el PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Lista de Estudiantes</title>
        <style>
            body { 
                font-family: DejaVu Sans, Arial, sans-serif; 
                font-size: 11px;
                line-height: 1.3;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                margin: 0 auto;
            }
            /* CINTILLO CON IMAGEN */
            .cintillo-imagen {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
            .cintillo-img {
                max-width: 100%;
                height: 60px;
            }
            /* TÍTULO DEL REPORTE */
            .report-title {
                text-align: center;
                color: #003366;
                font-size: 16px;
                font-weight: bold;
                margin: 5px 0 10px 0;
                padding-bottom: 5px;
                border-bottom: 2px solid #003366;
            }
            /* INFORMACIÓN DEL GRADO */
            .grado-info { 
                text-align: center;
                margin-bottom: 10px;
                font-size: 11px;
                background-color: #f8f9fa;
                padding: 8px;
                border-radius: 4px;
                border: 1px solid #dee2e6;
            }
            /* ESTADÍSTICAS */
            .stats-container {
                display: table;
                width: 100%;
                margin-bottom: 15px;
                font-size: 10px;
                border-collapse: collapse;
            }
            .stat-box {
                display: table-cell;
                width: 33.33%;
                padding: 10px;
                background-color: #e9ecef;
                border: 1px solid #ced4da;
                text-align: center;
                vertical-align: middle;
            }
            .stat-number {
                font-size: 18px;
                font-weight: bold;
                color: #003366;
                display: block;
                margin-bottom: 5px;
            }
            .stat-text {
                font-size: 10px;
                color: #495057;
                font-weight: bold;
            }
            /* BARRA DE PROGRESO */
            .progress-section {
                margin-bottom: 15px;
                padding: 8px;
                background-color: #f8f9fa;
                border-radius: 4px;
                border: 1px solid #dee2e6;
            }
            .progress-label {
                font-weight: bold;
                margin-bottom: 5px;
                color: #495057;
                font-size: 11px;
            }
            .progress-container {
                width: 100%;
                background-color: #e9ecef;
                border-radius: 4px;
                overflow: hidden;
                height: 20px;
                border: 1px solid #ced4da;
            }
            .progress-bar {
                height: 100%;
                background-color: #003366;
                text-align: center;
                color: white;
                font-size: 11px;
                line-height: 20px;
                font-weight: bold;
            }
            /* TABLA */
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 10px;
                font-size: 9px;
            }
            th { 
                background-color: #003366; 
                color: white;
                border: 1px solid #ddd;
                padding: 8px;
                text-align: center;
                font-weight: bold;
                font-size: 9px;
            }
            td { 
                border: 1px solid #ddd; 
                padding: 6px;
                font-size: 9px;
            }
            .text-center { text-align: center; }
            .text-left { text-align: left; }
            .even-row {
                background-color: #f8f9fa;
            }
            .odd-row {
                background-color: #ffffff;
            }
            /* PIE DE PÁGINA */
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 9px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 8px;
            }
            .no-data {
                text-align: center;
                padding: 30px;
                color: #666;
                font-size: 12px;
                font-style: italic;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- CINTILLO CON IMAGEN OFICIAL -->
            <div class="cintillo-imagen">
                <img src="' . $cintillo_base64 . '" class="cintillo-img" alt="Cintillo Oficial">
            </div>
            
            <!-- TÍTULO DEL REPORTE -->
            <div class="report-title">
                LISTA DE ESTUDIANTES - ' . htmlspecialchars($info_grado['nombre_grado']) . ' - SECCIÓN ' . htmlspecialchars($info_grado['seccion']) . '
            </div>
            
            <!-- INFORMACIÓN DEL GRADO -->
            <div class="grado-info">
                <strong>Fecha de Generación:</strong> ' . date('d/m/Y H:i:s') . ' | 
                <strong>Período Escolar:</strong> 2024-2025 | 
                <strong>Capacidad Total:</strong> ' . $info_grado['capacidad'] . ' estudiantes
            </div>

            <!-- ESTADÍSTICAS -->
            <div class="stats-container">
                <div class="stat-box">
                    <span class="stat-number">' . $totalEstudiantes . '</span>
                    <span class="stat-text">TOTAL ESTUDIANTES</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">' . number_format($porcentajeOcupacion, 1) . '%</span>
                    <span class="stat-text">OCUPACIÓN</span>
                </div>
                <div class="stat-box">
                    <span class="stat-number">' . $cuposDisponibles . '</span>
                    <span class="stat-text">CUPOS DISPONIBLES</span>
                </div>
            </div>

            <!-- BARRA DE PROGRESO -->
            <div class="progress-section">
                <div class="progress-label">NIVEL DE OCUPACIÓN DEL AULA:</div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: ' . number_format($porcentajeOcupacion, 1) . '%;">
                        ' . number_format($porcentajeOcupacion, 1) . '% COMPLETADO
                    </div>
                </div>
            </div>';

    if ($totalEstudiantes > 0) {
        $html .= '
            <!-- TABLA DE ESTUDIANTES -->
            <table>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="12%">CÉDULA</th>
                        <th width="28%">NOMBRE COMPLETO</th>
                        <th width="8%">SEXO</th>
                        <th width="8%">EDAD</th>
                        <th width="12%">FECHA INSCRIPCIÓN</th>
                        <th width="27%">REPRESENTANTE</th>
                    </tr>
                </thead>
                <tbody>';

        $contador = 1;
        while ($estudiante = $estudiantes->fetch(PDO::FETCH_ASSOC)) {
            // Calcular edad correctamente
            $edad = 'N/A';
            if ($estudiante['fecha_nac'] && $estudiante['fecha_nac'] != '0000-00-00') {
                try {
                    $fechaNac = new DateTime($estudiante['fecha_nac']);
                    $hoy = new DateTime();
                    $edad = $hoy->diff($fechaNac)->y;
                } catch (Exception $e) {
                    $edad = 'N/A';
                }
            }

            // Formatear nombre completo
            $nombreCompleto = trim(
                $estudiante['primer_nombre'] . ' ' . 
                ($estudiante['segundo_nombre'] ? $estudiante['segundo_nombre'] . ' ' : '') .
                $estudiante['primer_apellido'] . ' ' . 
                ($estudiante['segundo_apellido'] ? $estudiante['segundo_apellido'] : '')
            );

            // Formatear representante
            $representante = 'No asignado';
            if ($estudiante['representante_nombre']) {
                $representante = htmlspecialchars($estudiante['representante_nombre']);
                if ($estudiante['parentesco']) {
                    $representante .= ' (' . htmlspecialchars($estudiante['parentesco']) . ')';
                }
            }

            // Corregir datos inconsistentes
            $sexo = $estudiante['sexo'];
            if (stripos($sexo, 'masc') !== false) {
                $sexo = 'Masculino';
            } elseif (stripos($sexo, 'feme') !== false) {
                $sexo = 'Femenino';
            }

            $rowClass = ($contador % 2 == 0) ? 'even-row' : 'odd-row';
            
            $html .= '
                    <tr class="' . $rowClass . '">
                        <td class="text-center">' . $contador . '</td>
                        <td class="text-center">' . htmlspecialchars($estudiante['cedula']) . '</td>
                        <td class="text-left">' . htmlspecialchars($nombreCompleto) . '</td>
                        <td class="text-center">' . htmlspecialchars($sexo) . '</td>
                        <td class="text-center">' . $edad . ' años</td>
                        <td class="text-center">' . date('d/m/Y', strtotime($estudiante['fecha_inscripcion'])) . '</td>
                        <td class="text-left">' . $representante . '</td>
                    </tr>';
            
            $contador++;
        }
        
        $html .= '
                </tbody>
            </table>';
    } else {
        $html .= '
            <div class="no-data">
                No hay estudiantes inscritos en este grado/sección.
            </div>';
    }
    
    $html .= '
            <!-- PIE DE PÁGINA -->
            <div class="footer">
                Unidad Educativa Nacional "Nuevo Horizonte" - Sistema de Gestión Escolar<br>
                Página <span style="color: #003366; font-weight: bold;">{PAGENO}</span> de <span style="color: #003366; font-weight: bold;">{nbpg}</span>
            </div>
        </div>
    </body>
    </html>';

    // Configurar y generar PDF
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
    $html2pdf->setDefaultFont('dejavusans');
    $html2pdf->setTestTdInOnePage(false);
    $html2pdf->writeHTML($html);
    
    // Descargar el PDF con nombre personalizado
    $filename = 'lista_estudiantes_' . 
                str_replace(' ', '_', $info_grado['nombre_grado']) . '_' . 
                $info_grado['seccion'] . '_' . 
                date('Y-m-d') . '.pdf';
    
    $html2pdf->output($filename);

} catch (Html2PdfException $e) {
    // Manejar errores de HTML2PDF
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
    
} catch (Exception $e) {
    // Manejar otros errores
    echo "<div class='alert alert-danger'>Error al generar el reporte: " . $e->getMessage() . "</div>";
}
?>