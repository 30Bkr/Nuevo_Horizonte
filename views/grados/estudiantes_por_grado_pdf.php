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

    // Configurar zona horaria de Venezuela
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y H:i:s');

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
                font-size: 10px;
                line-height: 1.2;
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
                margin-bottom: 8px;
            }
            .cintillo-img {
                max-width: 100%;
                height: 55px;
            }
            .cintillo-texto {
                text-align: center;
                font-weight: bold;
                font-size: 13px;
                color: #003366;
                padding: 8px;
                background-color: #f8f9fa;
                border: 1px solid #003366;
                margin-bottom: 12px;
            }
            /* TÍTULO DEL REPORTE */
            .report-title {
                text-align: center;
                color: #003366;
                font-size: 14px;
                font-weight: bold;
                margin: 3px 0 8px 0;
                padding-bottom: 3px;
                border-bottom: 1.5px solid #003366;
            }
            /* INFORMACIÓN DEL GRADO */
            .grado-info { 
                text-align: center;
                margin-bottom: 8px;
                font-size: 9px;
                background-color: #f8f9fa;
                padding: 6px;
                border-radius: 3px;
                border: 1px solid #dee2e6;
            }
            /* TABLA */
            table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-top: 8px;
                font-size: 8px;
            }
            th { 
                background-color: #003366; 
                color: white;
                border: 1px solid #ddd;
                padding: 5px 3px;
                text-align: center;
                font-weight: bold;
                font-size: 8px;
            }
            td { 
                border: 1px solid #ddd; 
                padding: 4px 3px;
                font-size: 8px;
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
            /* ESTADÍSTICAS AL FINAL */
            .stats-final {
                margin-top: 12px;
                padding: 8px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
                border-radius: 3px;
                font-size: 9px;
            }
            .stats-final h3 {
                margin: 0 0 6px 0;
                color: #003366;
                font-size: 10px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 2px;
            }
            /* PIE DE PÁGINA */
            .footer {
                margin-top: 15px;
                text-align: center;
                font-size: 7px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 3px;
            }
            .no-data {
                text-align: center;
                padding: 20px;
                color: #666;
                font-size: 11px;
                font-style: italic;
            }
            /* NUEVO: Contenedor para centrar contenido */
            .content-wrapper {
                width: 100%;
                box-sizing: border-box;
            }
            /* NUEVO: Ajustar distribución de columnas */
            .col-numero { width: 4%; }
            .col-cedula { width: 11%; }
            .col-nombre { width: 30%; }
            .col-sexo { width: 7%; }
            .col-edad { width: 6%; }
            .col-fecha { width: 11%; }
            .col-representante { width: 31%; }
        </style>
    </head>
    <body>
        <div class="content-wrapper">
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
                LISTA DE ESTUDIANTES - ' . htmlspecialchars($info_grado['nombre_grado']) . ' - SECCIÓN ' . htmlspecialchars($info_grado['seccion']) . '
            </div>
            
            <!-- INFORMACIÓN DEL GRADO -->
            <div class="grado-info">
                <strong>Fecha de Generación:</strong> ' . $fecha_actual . ' | 
                <strong>Período Escolar:</strong> 2024-2025 | 
                <strong>Capacidad Total:</strong> ' . $info_grado['capacidad'] . ' estudiantes
            </div>';

    if ($totalEstudiantes > 0) {
        $html .= '
            <!-- TABLA DE ESTUDIANTES -->
            <table>
                <thead>
                    <tr>
                        <th class="col-numero">#</th>
                        <th class="col-cedula">CÉDULA</th>
                        <th class="col-nombre">NOMBRE COMPLETO</th>
                        <th class="col-sexo">SEXO</th>
                        <th class="col-edad">EDAD</th>
                        <th class="col-fecha">FECHA INSCRIPCIÓN</th>
                        <th class="col-representante">REPRESENTANTE</th>
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
                        <td class="text-center col-numero">' . $contador . '</td>
                        <td class="text-center col-cedula">' . htmlspecialchars($estudiante['cedula']) . '</td>
                        <td class="text-left col-nombre">' . htmlspecialchars($nombreCompleto) . '</td>
                        <td class="text-center col-sexo">' . htmlspecialchars($sexo) . '</td>
                        <td class="text-center col-edad">' . $edad . ' años</td>
                        <td class="text-center col-fecha">' . date('d/m/Y', strtotime($estudiante['fecha_inscripcion'])) . '</td>
                        <td class="text-left col-representante">' . $representante . '</td>
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

    // ESTADÍSTICAS AL FINAL (después de la tabla)
    $html .= '
            <div class="stats-final">
                <h3>RESUMEN ESTADÍSTICO</h3>
                <table style="width: 100%; border: none; background: transparent;">
                    <tr>
                        <td style="border: none; padding: 1px;"><strong>Total Estudiantes:</strong></td>
                        <td style="border: none; padding: 1px;">' . $totalEstudiantes . ' estudiantes</td>
                        <td style="border: none; padding: 1px;"><strong>Capacidad Total:</strong></td>
                        <td style="border: none; padding: 1px;">' . $info_grado['capacidad'] . ' estudiantes</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding: 1px;"><strong>Ocupación:</strong></td>
                        <td style="border: none; padding: 1px;">' . number_format($porcentajeOcupacion, 1) . '%</td>
                        <td style="border: none; padding: 1px;"><strong>Cupos Disponibles:</strong></td>
                        <td style="border: none; padding: 1px;">' . $cuposDisponibles . ' cupos</td>
                    </tr>
                </table>
            </div>';
    
    $html .= '
            <!-- PIE DE PÁGINA -->
            <div class="footer">
                Unidad Educativa Nacional "Nuevo Horizonte"<br>
                Página <span style="color: #003366; font-weight: bold;">[[page_cu]]</span> de <span style="color: #003366; font-weight: bold;">[[page_nb]]</span>
            </div>
        </div>
    </body>
    </html>';

    // Configurar y generar PDF con márgenes de 2.54 cm (25.4 mm)
    // Los márgenes se establecen en el constructor: array(izquierda, arriba, derecha, abajo)
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(25.4, 25.4, 25.4, 25.4));
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