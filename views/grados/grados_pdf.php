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

    // Configurar zona horaria de Venezuela
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y H:i:s');

    // Ruta de la imagen del cintillo
    $ruta_cintillo = $_SERVER['DOCUMENT_ROOT'] . '/final/public/images/cintillo_oficial.png';
    $cintillo_base64 = '';
    
    // Convertir imagen a base64 para incluirla en el HTML
    if (file_exists($ruta_cintillo)) {
        $image_data = file_get_contents($ruta_cintillo);
        $cintillo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
    }

    // Crear contenido HTML para el PDF
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Reporte de Grados, Años y Secciones</title>
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
                max-height: 80px;
            }
            .cintillo-texto {
                text-align: center;
                font-weight: bold;
                font-size: 14px;
                color: #003366;
                padding: 10px;
                background-color: #f8f9fa;
                border: 1px solid #003366;
                margin-bottom: 15px;
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
                text-align: center;
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
            /* PIE DE PÁGINA */
            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 8px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 5px;
            }
        </style>
    </head>
    <body>
        <!-- CINTILLO CON IMAGEN OFICIAL O TEXTO ALTERNATIVO -->
        ' . ($cintillo_base64 ? '
        <div class="cintillo-imagen">
            <img src="' . $cintillo_base64 . '" class="cintillo-img" alt="Cintillo Oficial">
        </div>' : '
        <div class="cintillo-texto">
            UNIDAD EDUCATIVA NACIONAL "NUEVO HORIZONTE"
        </div>') . '
        
        <!-- TÍTULO DEL REPORTE -->
        <div class="report-title">
            REPORTE DE GRADOS, AÑOS Y SECCIONES
        </div>
        
        <!-- INFORMACIÓN -->
        <div class="info">
            <strong>Fecha:</strong> ' . $fecha_actual . ' | 
            <strong>Período:</strong> 2024-2025 | 
            <strong>Secciones:</strong> ' . $stmt->rowCount() . '
        </div>';

    if ($stmt->rowCount() > 0) {
        $html .= '
        <table>
            <thead>
                <tr>
                    <th width="8%">ID</th>
                    <th width="25%">Grado/Año</th>
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
                    <td class="text-center">' . htmlspecialchars($row['id_nivel_seccion']) . '</td>
                    <td>' . htmlspecialchars($row['nombre_grado']) . '</td>
                    <td class="text-center">' . htmlspecialchars($row['seccion']) . '</td>
                    <td class="text-center">' . htmlspecialchars($row['capacidad']) . '</td>
                    <td class="text-center">' . htmlspecialchars($row['total_alumnos']) . '</td>
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
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right" style="border: none; padding: 8px 6px;"><strong>TOTALES:</strong></td>
                    <td class="text-center" style="border: 1px solid #ddd;"><strong>' . $totalCapacidad . '</strong></td>
                    <td class="text-center" style="border: 1px solid #ddd;"><strong>' . $totalAlumnos . '</strong></td>
                    <td class="text-center" style="border: 1px solid #ddd;"><strong>' . number_format($porcentajeTotal, 1) . '%</strong></td>
                    <td class="text-center" style="border: 1px solid #ddd;"><strong>' . $cuposDisponiblesTotal . ' cupos</strong></td>
                </tr>
            </tfoot>
        </table>';
    } else {
        $html .= '
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            No hay grados/secciones registrados en el sistema.
        </div>';
    }
    
    $html .= '
        <div class="footer">
    Unidad Educativa Nacional "Nuevo Horizonte"<br>
    Página <span style="color: #003366; font-weight: bold;">[[page_cu]]</span> de <span style="color: #003366; font-weight: bold;">[[page_nb]]</span>
</div>
    </body>
    </html>';

    // Configurar y generar PDF con márgenes de 2.54 cm (equivalente a 1 pulgada)
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(25.4, 25.4, 25.4, 25.4));
    $html2pdf->setDefaultFont('dejavusans');
    
    // Configurar la paginación automática
    $html2pdf->setTestTdInOnePage(false);
    
    $html2pdf->writeHTML($html);
    
    // Descargar el PDF
    $html2pdf->output('reporte_grados_' . date('Y-m-d') . '.pdf');

} catch (Html2PdfException $e) {
    // Manejar errores de HTML2PDF
    echo "<div style='padding: 20px; color: red; font-family: Arial;'>
            <h3>Error al generar PDF</h3>
            <p>" . $e->getMessage() . "</p>
            <p><strong>Archivo:</strong> " . $e->getFile() . "</p>
            <p><strong>Línea:</strong> " . $e->getLine() . "</p>
          </div>";
    
} catch (Exception $e) {
    // Manejar otros errores
    echo "<div style='padding: 20px; color: red; font-family: Arial;'>
            <h3>Error</h3>
            <p>" . $e->getMessage() . "</p>
            <p><strong>Archivo:</strong> " . $e->getFile() . "</p>
            <p><strong>Línea:</strong> " . $e->getLine() . "</p>
          </div>";
}
?>