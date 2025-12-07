<?php
session_start();

// // Verificar permisos
// if (!isset($_SESSION['usuario_id'])) {
//     die('Acceso no autorizado');
// }

// Incluir autoload de Composer para HTML2PDF
require_once __DIR__ . '/../../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// Incluir archivos de la aplicación
include_once __DIR__ . '/../../app/conexion.php';
include_once __DIR__ . '/../../app/controllers/estudiantes/EstudianteController.php';

try {
    // Configurar zona horaria de Venezuela
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y H:i:s');

    // Obtener datos de la base de datos
    $database = new Conexion();
    $db = $database->conectar();
    
    // Obtener la matrícula completa
    $estudianteController = new EstudianteController($db);
    $estudiante = $estudianteController->estudiante;
    
    // Obtener matrícula usando el método nuevo
    $matricula = $estudiante->obtenerMatriculaCompleta();
    $totalEstudiantes = $matricula->rowCount();

    // Ruta de la imagen del cintillo
    $ruta_cintillo = __DIR__ . '/../../public/images/cintillo_oficial.png';
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
        <title>Matrícula Estudiantil</title>
        <style>
            body { 
                font-family: DejaVu Sans, Arial, sans-serif; 
                font-size: 10px;
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
                margin-bottom: 5px;
            }
            .cintillo-img {
                max-width: 100%;
                height: 50px;
            }
            .cintillo-texto {
                text-align: center;
                font-weight: bold;
                font-size: 12px;
                color: #003366;
                padding: 8px;
                background-color: #f8f9fa;
                border: 1px solid #003366;
                margin-bottom: 10px;
            }
            /* TÍTULO DEL REPORTE */
            .report-title {
                text-align: center;
                color: #003366;
                font-size: 14px;
                font-weight: bold;
                margin: 5px 0 10px 0;
                padding-bottom: 5px;
                border-bottom: 2px solid #003366;
            }
            /* INFORMACIÓN DEL REPORTE */
            .report-info { 
                text-align: center;
                margin-bottom: 10px;
                font-size: 10px;
                background-color: #f8f9fa;
                padding: 6px;
                border-radius: 4px;
                border: 1px solid #dee2e6;
            }
            /* TABLA */
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 5px;
                font-size: 8px;
            }
            th { 
                background-color: #003366; 
                color: white;
                border: 1px solid #ddd;
                padding: 6px;
                text-align: center;
                font-weight: bold;
                font-size: 8px;
            }
            td { 
                border: 1px solid #ddd; 
                padding: 5px;
                font-size: 8px;
                vertical-align: top;
            }
            .text-center { text-align: center; }
            .text-left { text-align: left; }
            .text-right { text-align: right; }
            .even-row {
                background-color: #f8f9fa;
            }
            .odd-row {
                background-color: #ffffff;
            }
            /* SEPARADOR DE GRADO */
            .grado-separator {
                background-color: #e9ecef;
                font-weight: bold;
                color: #003366;
                padding: 4px;
                border-left: 4px solid #003366;
                margin-top: 5px;
                font-size: 9px;
            }
            /* PIE DE PÁGINA */
            .footer {
                margin-top: 15px;
                text-align: center;
                font-size: 7px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 5px;
            }
            .no-data {
                text-align: center;
                padding: 20px;
                color: #666;
                font-size: 10px;
                font-style: italic;
            }
            /* CONTADOR TOTAL */
            .total-container {
                margin-top: 10px;
                padding: 8px;
                background-color: #f8f9fa;
                border: 1px solid #003366;
                border-radius: 4px;
                font-size: 10px;
                font-weight: bold;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
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
                MATRÍCULA ESTUDIANTIL - AÑO ESCOLAR 2025-2026
            </div>
            
            <!-- INFORMACIÓN DEL REPORTE -->
            <div class="report-info">
                <strong>Fecha de Generación:</strong> ' . $fecha_actual . ' | 
                <strong>Total de Estudiantes:</strong> ' . $totalEstudiantes . ' | 
                <strong>Reporte:</strong> Listado General por Grados y Secciones
            </div>';

    if ($totalEstudiantes > 0) {
        $html .= '
            <!-- TABLA DE MATRÍCULA -->
            <table>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">CÉDULA</th>
                        <th width="40%">NOMBRE COMPLETO</th>
                        <th width="40%">GRADO Y SECCIÓN</th>
                    </tr>
                </thead>
                <tbody>';

        $contador = 1;
        $grado_actual = '';
        $seccion_actual = '';
        
        while ($estudiante = $matricula->fetch(PDO::FETCH_ASSOC)) {
            // Crear identificador de grado-sección actual
            $grado_seccion_actual = $estudiante['grado_seccion'];
            
            // Si cambió el grado-sección, agregar separador
            if ($grado_seccion_actual != $grado_actual) {
                $html .= '
                    <tr>
                        <td colspan="4" class="grado-separator">
                            ' . htmlspecialchars($grado_seccion_actual) . '
                        </td>
                    </tr>';
                $grado_actual = $grado_seccion_actual;
            }
            
            $rowClass = ($contador % 2 == 0) ? 'even-row' : 'odd-row';
            
            $html .= '
                    <tr class="' . $rowClass . '">
                        <td class="text-center">' . $contador . '</td>
                        <td class="text-center">' . htmlspecialchars($estudiante['cedula']) . '</td>
                        <td class="text-left">' . htmlspecialchars($estudiante['nombre_completo']) . '</td>
                        <td class="text-center">' . htmlspecialchars($estudiante['grado_seccion']) . '</td>
                    </tr>';
            
            $contador++;
        }
        
        $html .= '
                </tbody>
            </table>';
        
        // Total de estudiantes al final
        $html .= '
            <div class="total-container">
                TOTAL GENERAL DE ESTUDIANTES MATRICULADOS: ' . $totalEstudiantes . '
            </div>';
            
    } else {
        $html .= '
            <div class="no-data">
                No hay estudiantes matriculados en el período escolar actual.
            </div>';
    }
    
    $html .= '
            <!-- PIE DE PÁGINA -->
            <div class="footer">
                Unidad Educativa Nacional "Nuevo Horizonte" - Sistema de Gestión Escolar<br>
                Página <span style="color: #003366; font-weight: bold;">[[page_cu]]</span> de <span style="color: #003366; font-weight: bold;">[[page_nb]]</span>
            </div>
        </div>
    </body>
    </html>';

    // Configurar y generar PDF
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(15, 15, 15, 15));
    $html2pdf->setDefaultFont('dejavusans');
    $html2pdf->setTestTdInOnePage(false);
    $html2pdf->writeHTML($html);
    
    // Descargar el PDF con nombre personalizado
    $filename = 'matricula_estudiantil_' . date('Y-m-d') . '.pdf';
    $html2pdf->output($filename, 'I');  // 'I' = Inline (ver en navegador)

} catch (Html2PdfException $e) {
    // Manejar errores de HTML2PDF
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
    
} catch (Exception $e) {
    // Manejar otros errores
    echo "<div class='alert alert-danger'>Error al generar el reporte: " . $e->getMessage() . "</div>";
}
?>