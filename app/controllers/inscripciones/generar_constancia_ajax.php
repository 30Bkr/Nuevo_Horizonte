<?php
session_start();

// Usar ruta absoluta
$autoloadPath = 'C:/xampp/htdocs/final/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No se encuentra Composer autoload']);
    exit;
}
require_once $autoloadPath;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// Incluir conexión a la base de datos
include_once __DIR__ . '/../../conexion.php';

header('Content-Type: application/json');

try {
    // Obtener ID de la inscripción
    $id_inscripcion = $_POST['id_inscripcion'] ?? 0;
    
    if ($id_inscripcion == 0) {
        throw new Exception("No se proporcionó un ID de inscripción válido");
    }

    // Configurar zona horaria de Venezuela
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y H:i:s');

    // Conectar a la base de datos
    $database = new Conexion();
    $db = $database->conectar();

    // CONSULTA PARA OBTENER DATOS DE LA INSCRIPCIÓN (la misma que tenías)
    $sql_inscripcion = "
        SELECT
            I.id_inscripcion,
            PE.cedula AS cedula_estudiante,
            UPPER(CONCAT(PE.primer_nombre, ' ', COALESCE(PE.segundo_nombre, ''), ' ', PE.primer_apellido, ' ', COALESCE(PE.segundo_apellido, ''))) AS nombre_estudiante,
            DATE_FORMAT(PE.fecha_nac, '%d/%m/%Y') AS fecha_nacimiento,
            UPPER(PE.lugar_nac) AS lugar_nacimiento,
            I.fecha_inscripcion,
            UPPER(PEE.descripcion_periodo) AS periodo_escolar,
            UPPER(N.nom_nivel) AS nivel_nombre,
            UPPER(S.nom_seccion) AS seccion_nombre,
            UPPER(CONCAT(N.nom_nivel, ' ', S.nom_seccion)) AS nivel_seccion,
            UPPER(CONCAT(PDIR.primer_nombre, ' ', COALESCE(PDIR.segundo_nombre, ''), ' ', PDIR.primer_apellido, ' ', COALESCE(PDIR.segundo_apellido, ''))) AS nombre_director,
            PDIR.cedula AS cedula_director,
            MDIR.nom_municipio AS municipio_emisor,
            UPPER(EE.nom_estado) AS estado_zona_educativa,
            DATE_FORMAT(I.fecha_inscripcion, '%d') AS dia_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%m') AS mes_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%Y') AS anio_inscripcion,
            PDIR.primer_apellido AS apellido_director
        FROM
            inscripciones I
        JOIN estudiantes E ON I.id_estudiante = E.id_estudiante
        JOIN personas PE ON E.id_persona = PE.id_persona
        JOIN periodos PEE ON I.id_periodo = PEE.id_periodo
        LEFT JOIN niveles_secciones NS ON I.id_nivel_seccion = NS.id_nivel_seccion
        LEFT JOIN niveles N ON NS.id_nivel = N.id_nivel
        LEFT JOIN secciones S ON NS.id_seccion = S.id_seccion
        LEFT JOIN direcciones DE ON PE.id_direccion = DE.id_direccion
        LEFT JOIN parroquias PAE ON DE.id_parroquia = PAE.id_parroquia
        LEFT JOIN municipios ME ON PAE.id_municipio = ME.id_municipio
        LEFT JOIN estados EE ON ME.id_estado = EE.id_estado
        JOIN usuarios U ON I.id_usuario = U.id_usuario
        JOIN personas PDIR ON U.id_persona = PDIR.id_persona
        JOIN direcciones DDIR ON PDIR.id_direccion = DDIR.id_direccion
        JOIN parroquias PADIR ON DDIR.id_parroquia = PADIR.id_parroquia
        JOIN municipios MDIR ON PADIR.id_municipio = MDIR.id_municipio
        WHERE
            I.id_inscripcion = :id_inscripcion;
    ";

    $stmt = $db->prepare($sql_inscripcion);
    $stmt->execute([':id_inscripcion' => $id_inscripcion]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datos) {
        throw new Exception("No se encontraron datos de inscripción para el ID: " . $id_inscripcion);
    }

    // Función para obtener nombre del mes en español
    function obtener_nombre_mes_espanol($numero) {
        $meses = array(
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        );
        return $meses[$numero - 1];
    }

    // Procesar datos para la presentación
    $mes_numero = (int)$datos['mes_inscripcion'];
    $mes_inscripcion_espanol = obtener_nombre_mes_espanol($mes_numero);
    
    // Determinar tipo de nivel
    $tipo_nivel = (stripos($datos['nivel_nombre'], 'GRADO') !== false) ? 'Primaria' : 'Inicial';

    // Datos estáticos de la institución
    $NOMBRE_INSTITUCION = 'U.E.N NUEVO HORIZONTE';
    $PARROQUIA_INSTITUCION = 'Sucre';
    $MUNICIPIO_INSTITUCION = 'Libertador';

    // Ruta de la imagen del cintillo
    $ruta_cintillo = __DIR__ . '/../../../../public/images/cintillo_oficial.png';
    $cintillo_base64 = '';
    
    // Convertir imagen a base64 para incluirla en el HTML
    if (file_exists($ruta_cintillo)) {
        $image_data = file_get_contents($ruta_cintillo);
        $cintillo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
    }

    // Crear contenido HTML para el PDF (el mismo que tenías)
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Constancia de Inscripción</title>
        <style>
            body { 
                font-family: DejaVu Sans, Arial, sans-serif; 
                font-size: 12px;
                line-height: 1.4;
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
                margin-bottom: 15px;
            }
            .cintillo-img {
                max-width: 100%;
                height: 70px;
            }
            .cintillo-texto {
                text-align: center;
                font-weight: bold;
                font-size: 16px;
                color: #003366;
                padding: 12px;
                background-color: #f8f9fa;
                border: 2px solid #003366;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            /* TÍTULO DEL DOCUMENTO */
            .document-title {
                text-align: center;
                color: #003366;
                font-size: 18px;
                font-weight: bold;
                margin: 15px 0 25px 0;
                padding-bottom: 10px;
                border-bottom: 3px solid #003366;
            }
            /* CONTENIDO DE LA CONSTANCIA */
            .constancia-content {
                text-align: justify;
                margin: 20px 0;
                font-size: 13px;
                line-height: 1.6;
            }
            .constancia-content strong {
                color: #003366;
            }
            /* FIRMA Y SELLO */
            .firma-section {
                margin-top: 60px;
                text-align: center;
            }
            .linea-firma {
                border-bottom: 1px solid #000;
                width: 300px;
                margin: 0 auto 5px auto;
            }
            .nombre-director {
                font-weight: bold;
                margin-top: 10px;
            }
            .cargo-director {
                font-style: italic;
                color: #666;
            }
            /* INFORMACIÓN INSTITUCIONAL */
            .info-institucional {
                text-align: center;
                margin: 10px 0;
                font-size: 11px;
                color: #666;
                border-top: 1px solid #ccc;
                padding-top: 10px;
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
        <div class="container">
            <!-- CINTILLO CON IMAGEN OFICIAL O TEXTO ALTERNATIVO -->
            ' . ($cintillo_base64 ? '
            <div class="cintillo-imagen">
                <img src="' . $cintillo_base64 . '" class="cintillo-img" alt="Cintillo Oficial">
            </div>' : '
            <div class="cintillo-texto">
                UNIDAD EDUCATIVA NACIONAL "NUEVO HORIZONTE"
            </div>') . '
            
            <!-- TÍTULO DEL DOCUMENTO -->
            <div class="document-title">
                CONSTANCIA DE INSCRIPCIÓN
            </div>
            
            <!-- CONTENIDO DE LA CONSTANCIA -->
            <div class="constancia-content">
                Quien suscribe <strong>' . $datos['nombre_director'] . '</strong>, titular de la Cédula
                de Identidad Nº <strong>' . $datos['cedula_director'] . '</strong> en su condición de Director(a) de la 
                <strong>' . $NOMBRE_INSTITUCION . '</strong>, ubicado en el municipio ' . $MUNICIPIO_INSTITUCION . ', 
                parroquia <strong>' . $PARROQUIA_INSTITUCION . '</strong> adscrita a la Zona Educativa del estado 
                <strong>' . $datos['estado_zona_educativa'] . '</strong>, certifica por medio de la presente que 
                el (la) estudiante <strong>' . $datos['nombre_estudiante'] . '</strong> titular de la 
                Cédula Escolar Nº, Cédula de Identidad Nº o Pasaporte Nº <strong>' . $datos['cedula_estudiante'] . '</strong>,
                nacido (a) en <strong>' . $datos['lugar_nacimiento'] . '</strong> en fecha <strong>' . $datos['fecha_nacimiento'] . '</strong>, 
                ha sido inscrito en esta institución para cursar el <strong>' . $datos['nivel_seccion'] . '</strong> 
                del Nivel de Educación ' . $tipo_nivel . ' durante el período escolar <strong>' . $datos['periodo_escolar'] . '</strong>, 
                previo cumplimiento de los requisitos exigidos en la normativa legal vigente.
                <br><br>
                Constancia que se expide en <strong>' . $datos['municipio_emisor'] . '</strong>, a los <strong>' . $datos['dia_inscripcion'] . '</strong> 
                días del mes de <strong>' . strtoupper($mes_inscripcion_espanol) . '</strong> de <strong>' . $datos['anio_inscripcion'] . '</strong>.
            </div>
            
            <!-- SECCIÓN DE FIRMA -->
            <div class="firma-section">
                <div class="linea-firma"></div>
                <div class="nombre-director">' . $datos['nombre_director'] . '</div>
                <div class="cargo-director">DIRECTOR(A)</div>
            </div>
            
            <!-- INFORMACIÓN INSTITUCIONAL -->
            <div class="info-institucional">
                ' . $NOMBRE_INSTITUCION . ' | ' . $MUNICIPIO_INSTITUCION . ' - ' . $PARROQUIA_INSTITUCION . ' | Estado ' . $datos['estado_zona_educativa'] . '
                <br>Constancia generada el ' . $fecha_actual . '
            </div>

            <!-- PIE DE PÁGINA -->
            <div class="footer">
                Unidad Educativa Nacional "Nuevo Horizonte"<br>
                Página <span style="color: #003366; font-weight: bold;">[[page_cu]]</span> de <span style="color: #003366; font-weight: bold;">[[page_nb]]</span>
            </div>
        </div>
    </body>
    </html>';

    // Generar PDF y guardarlo en el servidor
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(25.4, 25.4, 25.4, 25.4));
    $html2pdf->setDefaultFont('dejavusans');
    $html2pdf->setTestTdInOnePage(false);
    $html2pdf->writeHTML($html);
    
    // Guardar el PDF en el servidor
    $filename = 'constancia_inscripcion_' . $datos['cedula_estudiante'] . '_' . date('Y-m-d') . '.pdf';
    $pdfPath = __DIR__ . '/../../../../storage/constancias/' . $filename;
    
    // Crear directorio si no existe
    $storageDir = __DIR__ . '/../../../../storage/constancias/';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0755, true);
    }
    
    // Guardar PDF
    $html2pdf->output($pdfPath, 'F');
    
    // Devolver URL para descargar
    $downloadUrl = '/final/storage/constancias/' . $filename;
    
    echo json_encode([
        'success' => true,
        'message' => 'Constancia generada exitosamente',
        'download_url' => $downloadUrl,
        'filename' => $filename
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al generar la constancia: ' . $e->getMessage()
    ]);
}
?>