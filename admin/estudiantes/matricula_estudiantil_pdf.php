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
        font-size: 9px; /* Reducido de 10px para mejor ajuste */
        line-height: 1.2; /* Reducido de 1.3 */
        margin: 0;
        padding: 0;
    }
    .container {
        width: 100%;
        margin: 0;
        padding: 0;
    }
    /* CINTILLO CON IMAGEN */
    .cintillo-imagen {
        width: 100%;
        text-align: center;
        margin-bottom: 2px; /* Reducido de 5px */
    }
    .cintillo-img {
        max-width: 95%; /* Reducido de 100% para márgenes laterales */
        height: 45px; /* Reducido de 50px */
    }
    .cintillo-texto {
        text-align: center;
        font-weight: bold;
        font-size: 11px; /* Ajustado de 12px */
        color: #003366;
        padding: 5px; /* Reducido de 8px */
        background-color: #f8f9fa;
        border: 1px solid #003366;
        margin-bottom: 5px; /* Reducido de 10px */
    }
    /* TÍTULO DEL REPORTE */
    .report-title {
        text-align: center;
        color: #003366;
        font-size: 12px; /* Reducido de 14px */
        font-weight: bold;
        margin: 3px 0 5px 0; /* Reducido márgenes */
        padding-bottom: 3px;
        border-bottom: 1px solid #003366; /* Reducido de 2px */
    }
    /* INFORMACIÓN DEL REPORTE */
    .report-info { 
        text-align: center;
        margin-bottom: 5px; /* Reducido de 10px */
        font-size: 8px; /* Reducido de 10px */
        background-color: #f8f9fa;
        padding: 4px; /* Reducido de 6px */
        border-radius: 3px;
        border: 1px solid #dee2e6;
    }
    /* TABLA - AJUSTES DE ANCHO CRÍTICOS */
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 3px;
        font-size: 8px; /* Asegurar tamaño consistente */
    }
    th { 
        background-color: #003366; 
        color: white;
        border: 1px solid #ddd;
        padding: 4px 2px; /* Reducido padding horizontal de 6px a 2px */
        text-align: center;
        font-weight: bold;
        font-size: 8px;
    }
    td { 
        border: 1px solid #ddd; 
        padding: 3px 2px; /* Reducido padding horizontal de 5px a 2px */
        font-size: 8px;
        vertical-align: top;
    }
    /* AJUSTES ESPECÍFICOS DE ANCHO DE COLUMNAS */
    th:nth-child(1), td:nth-child(1) { width: 4%; }  /* N° */
    th:nth-child(2), td:nth-child(2) { width: 12%; } /* CÉDULA */
    th:nth-child(3), td:nth-child(3) { width: 52%; } /* NOMBRE COMPLETO - AUMENTADO */
    th:nth-child(4), td:nth-child(4) { width: 32%; } /* GRADO/AÑO Y SECCIÓN - AUMENTADO */
    
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
        padding: 3px;
        border-left: 3px solid #003366;
        margin-top: 3px;
        font-size: 8px;
    }
    /* PIE DE PÁGINA */
    .footer {
        margin-top: 10px;
        text-align: center;
        font-size: 7px;
        color: #666;
        border-top: 1px solid #ccc;
        padding-top: 3px;
    }
    .no-data {
        text-align: center;
        padding: 15px;
        color: #666;
        font-size: 9px;
        font-style: italic;
    }
    /* CONTADOR TOTAL */
    .total-container {
        margin-top: 8px;
        padding: 6px;
        background-color: #f8f9fa;
        border: 1px solid #003366;
        border-radius: 3px;
        font-size: 9px;
        font-weight: bold;
        text-align: center;
    }
</style>;
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
                <strong>Reporte:</strong> Listado General por Grados, Años y Secciones
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
                    <th width="40%">GRADO/AÑO Y SECCIÓN</th>
                </tr>
            </thead>
            <tbody>';

    $contador = 1;
    $nivel_actual = '';
    
    while ($estudiante = $matricula->fetch(PDO::FETCH_ASSOC)) {
        // Formatear el grado/sección para mostrar mejor
        $grado_seccion = $estudiante['grado_seccion'];
        
        // Si cambió el nivel, agregar separador
        if ($estudiante['nombre_nivel'] != $nivel_actual) {
            $html .= '
                <tr>
                    <td colspan="4" class="grado-separator">
                        ' . htmlspecialchars($estudiante['nombre_nivel']) . '
                    </td>
                </tr>';
            $nivel_actual = $estudiante['nombre_nivel'];
        }
        
        $rowClass = ($contador % 2 == 0) ? 'even-row' : 'odd-row';
        
        $html .= '
                <tr class="' . $rowClass . '">
                    <td class="text-center">' . $contador . '</td>
                    <td class="text-center">' . htmlspecialchars($estudiante['cedula']) . '</td>
                    <td class="text-left">' . htmlspecialchars($estudiante['nombre_completo']) . '</td>
                    <td class="text-center">' . htmlspecialchars($grado_seccion) . '</td>
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