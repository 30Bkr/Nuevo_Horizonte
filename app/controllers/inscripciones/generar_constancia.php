<?php
session_start();

// Usar ruta absoluta
$autoloadPath = 'C:/xampp/htdocs/final/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Error: No se encuentra Composer autoload. Ejecuta 'composer install' en la carpeta del proyecto.");
}
require_once $autoloadPath;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// Incluir conexión a la base de datos
include_once __DIR__ . '/../../conexion.php';

try {
    // Obtener ID de la inscripción
    $id_inscripcion = $_GET['id_inscripcion'] ?? $_POST['id_inscripcion'] ?? 0;
    
    if ($id_inscripcion == 0) {
        throw new Exception("No se proporcionó un ID de inscripción válido");
    }

    // Configurar zona horaria de Venezuela
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y H:i:s');

    // Conectar a la base de datos
    $database = new Conexion();
    $db = $database->conectar();

    // OBTENER DATOS DE LA DIRECTORA DESDE LA TABLA GLOBALES
    $sql_globales = "SELECT nom_directora, ci_directora FROM globales WHERE id_globales = 1";
    $stmt_globales = $db->prepare($sql_globales);
    $stmt_globales->execute();
    $directora = $stmt_globales->fetch(PDO::FETCH_ASSOC);

    if (!$directora) {
        throw new Exception("No se encontraron datos de la directora en la tabla globales");
    }

    // CONVERTIR NOMBRE DE DIRECTORA A MAYÚSCULAS
    $directora_nombre_mayusculas = mb_strtoupper($directora['nom_directora'], 'UTF-8');

    // CONSULTA PARA OBTENER DATOS DE LA INSCRIPCIÓN (ACTUALIZADA)
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
            DATE_FORMAT(I.fecha_inscripcion, '%d') AS dia_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%m') AS mes_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%Y') AS anio_inscripcion
        FROM
            inscripciones I
        JOIN estudiantes E ON I.id_estudiante = E.id_estudiante
        JOIN personas PE ON E.id_persona = PE.id_persona
        JOIN periodos PEE ON I.id_periodo = PEE.id_periodo
        LEFT JOIN niveles_secciones NS ON I.id_nivel_seccion = NS.id_nivel_seccion
        LEFT JOIN niveles N ON NS.id_nivel = N.id_nivel
        LEFT JOIN secciones S ON NS.id_seccion = S.id_seccion
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
    $tipo_nivel = (stripos($datos['nivel_nombre'], 'GRADO') !== false) ? 'Primaria' : 'Secundaria';

    // Datos estáticos de la institución
    $NOMBRE_INSTITUCION = 'U.E.N NUEVO HORIZONTE';
    $PARROQUIA_INSTITUCION = 'Sucre';
    $MUNICIPIO_INSTITUCION = 'Libertador';
    $CIUDAD_EXPEDICION = 'Caracas'; // Ciudad fija para "Constancia que se expide en..."

    // Ruta de la imagen del cintillo
   $ruta_cintillo = $_SERVER['DOCUMENT_ROOT'] . '/final/public/images/cintillo_oficial.png';
    $cintillo_base64 = '';
    
    // Convertir imagen a base64 para incluirla en el HTML
    if (file_exists($ruta_cintillo)) {
        $image_data = file_get_contents($ruta_cintillo);
        $cintillo_base64 = 'data:image/png;base64,' . base64_encode($image_data);
    } else {
        // Si no existe la imagen, mostrar mensaje de debug
        error_log("No se encontró la imagen del cintillo en: " . $ruta_cintillo);
    }

    // Crear contenido HTML para el PDF
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
            /* CONTENIDO DE LA CONSTANCIA - CORREGIDO */
            .constancia-content {
                text-align: justify;
                margin: 20px 0;
                font-size: 13px;
                line-height: 1.6;
                text-justify: inter-character;
                word-spacing: -0.5px;
                letter-spacing: -0.1px;
                hyphens: auto;
            }
            .constancia-content strong {
                color: #003366;
            }
            /* FIRMA Y SELLO - CORREGIDO */
            .firma-section {
            margin-top: 100px;
            margin-bottom: 40px; /* Agregar margen inferior */
            text-align: center;
            }
            }
            .linea-firma {
                border-bottom: 1px solid #000;
                width: 350px;
                margin: 0 auto 15px auto;
                height: 1px;
            }
            .nombre-director {
                font-weight: bold;
                margin-top: 5px;
                text-align: center;
                font-size: 14px;
                text-transform: uppercase;
            }
            .cargo-director {
                font-style: italic;
                color: #666;
                text-align: center;
                margin-top: 3px;
                font-size: 12px;
            }
            /* INFORMACIÓN INSTITUCIONAL */
            .info-institucional {
                text-align: center;
                margin: 20px 0 10px 0; /* Reducir margen superior */
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
                Quien suscribe <strong>' . $directora_nombre_mayusculas . '</strong>, titular de la Cédula
                de Identidad Nº <strong>' . ($directora['ci_directora'] ?: 'No especificada') . '</strong>, en su condición de Director(a) de la 
                <strong>'. $NOMBRE_INSTITUCION . '</strong>, ubicada en el municipio ' . $MUNICIPIO_INSTITUCION . ', 
                parroquia <strong>' . $PARROQUIA_INSTITUCION . '</strong>, certifica por medio de la presente que 
                el (la) estudiante <strong>' . $datos['nombre_estudiante'] . '</strong>, titular de la 
                Cédula Escolar Nº, Cédula de Identidad Nº o Pasaporte Nº <strong>' . $datos['cedula_estudiante'] . '</strong>,
                nacido (a) en <strong>' . $datos['lugar_nacimiento'] . '</strong> en fecha <strong>' . $datos['fecha_nacimiento'] . '</strong>, 
                ha sido inscrito en esta institución para cursar el <strong>' . $datos['nivel_seccion'] . '</strong> 
                de Educación ' . $tipo_nivel . ' durante el período escolar <strong>' . $datos['periodo_escolar'] . '</strong>, 
                previo cumplimiento de los requisitos exigidos en la normativa legal vigente.
                <br><br>
                Constancia que se expide en <strong>' . $CIUDAD_EXPEDICION . '</strong>, a los <strong>' . $datos['dia_inscripcion'] . '</strong> 
                días del mes de <strong>' . strtoupper($mes_inscripcion_espanol) . '</strong> de <strong>' . $datos['anio_inscripcion'] . '</strong>.
            </div>
            
                       <!-- SECCIÓN DE FIRMA - CORREGIDA -->
            <div class="firma-section">
                <div class="linea-firma"></div>
                <div class="nombre-director">' . $directora_nombre_mayusculas . '</div>
                <div class="cargo-director">DIRECTOR(A)</div>
            </div>
            
            <!-- INFORMACIÓN INSTITUCIONAL - MÁS ESPACIO -->
            <div class="info-institucional">
                ' . $NOMBRE_INSTITUCION . ' | ' . $MUNICIPIO_INSTITUCION . ' - ' . $PARROQUIA_INSTITUCION . '
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

    // Configurar y generar PDF
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(25.4, 25.4, 25.4, 25.4));
    $html2pdf->setDefaultFont('dejavusans');
    $html2pdf->setTestTdInOnePage(false);
    $html2pdf->writeHTML($html);
    
    // En lugar de descargar automáticamente, mostrar en el navegador
    $filename = 'constancia_inscripcion_' . $datos['cedula_estudiante'] . '_' . date('Y-m-d') . '.pdf';
    
    // Mostrar en el navegador (I = inline)
    $html2pdf->output($filename, 'I');

} catch (Html2PdfException $e) {
    // Manejar errores de HTML2PDF
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
    
} catch (Exception $e) {
    // Manejar otros errores
    echo "<div class='alert alert-danger'>Error al generar la constancia: " . $e->getMessage() . "</div>";
}
?>