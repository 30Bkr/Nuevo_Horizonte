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
    // Obtener datos de la base de datos
    $database = new Conexion();
    $db = $database->conectar();
    $grado = new Grado($db);
    $stmt = $grado->listarGradosConAlumnos();

    // Ruta de la imagen del cintillo
    $ruta_cintillo = __DIR__ . '/../../public/images/cintillo_oficial.png';
    $cintillo_base64 = '';
    
    // Convertir imagen a base64 para incluirla en el HTML
    if (file_exists($ruta_cintillo)) {
        $image_data = file_get_contents($ruta_cintillo);
        $cintillo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
    } else {
        // Si no encuentra la imagen, mostrar mensaje de error
        throw new Exception("No se encontró la imagen del cintillo en: " . $ruta_cintillo);
    }

    // Crear contenido HTML para el PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Grados y Secciones</title>
        <style>
            body { 
                font-family: DejaVu Sans, Arial, sans-serif; 
                font-size: 11px;
                line-height: 1.3;
                margin: 0;
                padding: 0;
            }
            /* CINTILLO CON IMAGEN */
            .cintillo-imagen {
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }
            .cintillo-img {
                max-width: 100%;
                height: auto;
            }
            /* TÍTULO DEL REPORTE */
            .report-title {
                text-align: center;
                color: #003366;
                font-size: 14px;
                font-weight: bold;
                margin: 10px 0 15px 0;
                padding-bottom: 5px;
                border-bottom: 1px solid #003366;
            }
            /* INFORMACIÓN DEL REPORTE */
            .info { 
                margin-bottom: 12px;
                font-size: 10px;
                background-color: #f8f9fa;
                padding: 8px;
                border-radius: 3px;
            }
            /* TABLA */
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 8px;
                font-size: 9px;
            }
            th { 
                background-color: #003366; 
                color: white;
                border: 1px solid #ddd;
                padding: 6px;
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
            .text-right { text-align: right; }
            .even-row {
                background-color: #f8f9fa;
            }
            .odd-row {
                background-color: #ffffff;
            }
            /* RESUMEN */
            .summary { 
                margin-top: 15px;
                padding: 10px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-size: 10px;
            }
            .summary h3 {
                margin: 0 0 8px 0;
                color: #003366;
                font-size: 11px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 3px;
            }
            .progress-container {
                width: 100%;
                background-color: #e9ecef;
                border-radius: 2px;
                overflow: hidden;
                height: 16px;
                margin: 3px 0;
            }
            .progress-bar {
                height: 100%;
                background-color: #003366;
                text-align: center;
                color: white;
                font-size: 9px;
                line-height: 16px;
                font-weight: bold;
            }
            /* PIE DE PÁGINA */
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 8px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 5px;
                position: fixed;
                bottom: 0;
                width: 100%;
            }
            /* ESTILOS PARA PAGINACIÓN */
            .page {
                page-break-after: always;
            }
            .page:last-child {
                page-break-after: avoid;
            }
        </style>
    </head>
    <body>
        <!-- CINTILLO CON IMAGEN OFICIAL -->
        <div class="cintillo-imagen">
            <img src="' . $cintillo_base64 . '" class="cintillo-img" alt="Cintillo Oficial">
        </div>
        
        <!-- TÍTULO DEL REPORTE -->
        <div class="report-title">
            REPORTE DE GRADOS Y SECCIONES
        </div>
        
        <!-- INFORMACIÓN -->
        <div class="info">
            <strong>Fecha:</strong> ' . date('d/m/Y H:i:s') . ' | 
            <strong>Período:</strong> 2024-2025 | 
            <strong>Secciones:</strong> ' . $stmt->rowCount() . '
        </div>';

    if ($stmt->rowCount() > 0) {
        $html .= '
        <table>
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="25%">Grado</th>
                    <th width="12%">Sección</th>
                    <th width="12%">Capacidad</th>
                    <th width="12%">Alumnos</th>
                    <th width="16%">Ocupación %</th>
                    <th width="15%">Disponibilidad</th>
                </tr>
            </thead>
            <tbody>';

        $totalCapacidad = 0;
        $totalAlumnos = 0;
        $rowCount = 0;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $porcentaje = $row['capacidad'] > 0 ? ($row['total_alumnos'] / $row['capacidad']) * 100 : 0;
            $cuposDisponibles = $row['capacidad'] - $row['total_alumnos'];
            $rowClass = ($rowCount % 2 == 0) ? 'even-row' : 'odd-row';
            
            $html .= '
                <tr class="' . $rowClass . '">
                    <td class="text-center">' . $row['id_nivel_seccion'] . '</td>
                    <td>' . htmlspecialchars($row['nombre_grado']) . '</td>
                    <td class="text-center">' . htmlspecialchars($row['seccion']) . '</td>
                    <td class="text-center">' . $row['capacidad'] . '</td>
                    <td class="text-center">' . $row['total_alumnos'] . '</td>
                    <td class="text-center">' . number_format($porcentaje, 1) . '%</td>
                    <td class="text-center">' . $cuposDisponibles . ' cupos</td>
                </tr>';
            
            $totalCapacidad += $row['capacidad'];
            $totalAlumnos += $row['total_alumnos'];
            $rowCount++;
        }
        
        $porcentajeTotal = $totalCapacidad > 0 ? ($totalAlumnos / $totalCapacidad) * 100 : 0;
        $cuposDisponiblesTotal = $totalCapacidad - $totalAlumnos;
        
        $html .= '
            </tbody>
        </table>
        
        <div class="summary">
            <h3>RESUMEN ESTADÍSTICO</h3>
            <table style="width: 100%; border: none; background: transparent;">
                <tr>
                    <td style="border: none; padding: 2px;"><strong>Total Capacidad:</strong></td>
                    <td style="border: none; padding: 2px;">' . $totalCapacidad . ' estudiantes</td>
                    <td style="border: none; padding: 2px;"><strong>Alumnos Inscritos:</strong></td>
                    <td style="border: none; padding: 2px;">' . $totalAlumnos . ' estudiantes</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px;"><strong>Ocupación:</strong></td>
                    <td style="border: none; padding: 2px;">' . number_format($porcentajeTotal, 1) . '%</td>
                    <td style="border: none; padding: 2px;"><strong>Cupos Disponibles:</strong></td>
                    <td style="border: none; padding: 2px;">' . $cuposDisponiblesTotal . ' cupos</td>
                </tr>
            </table>
            
            <div style="margin-top: 8px;">
                <strong>Nivel de Ocupación General:</strong>
                <div class="progress-container">
                    <div class="progress-bar" style="width: ' . number_format($porcentajeTotal, 1) . '%;">
                        ' . number_format($porcentajeTotal, 1) . '%
                    </div>
                </div>
            </div>
        </div>';
    } else {
        $html .= '
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            No hay grados/secciones registrados en el sistema.
        </div>';
    }
    
    $html .= '
        <div class="footer">
            Unidad Educativa Nacional "Nuevo Horizonte" - Sistema de Gestión Escolar<br>
            Página <span style="color: #003366; font-weight: bold;">{PAGENO}</span> de <span style="color: #003366; font-weight: bold;">{nbpg}</span>
        </div>
    </body>
    </html>';

    // Configurar y generar PDF
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(8, 8, 8, 8));
    $html2pdf->setDefaultFont('dejavusans');
    
    // Configurar la paginación automática
    $html2pdf->setTestTdInOnePage(false);
    
    $html2pdf->writeHTML($html);
    
    // Descargar el PDF
    $html2pdf->output('reporte_grados_' . date('Y-m-d') . '.pdf');

} catch (Html2PdfException $e) {
    // Manejar errores de HTML2PDF
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
    
} catch (Exception $e) {
    // Manejar otros errores
    echo "<div class='alert alert-danger'>Error al generar el reporte: " . $e->getMessage() . "</div>";
}
?>