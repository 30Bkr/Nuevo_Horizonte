<?php
session_start();

// 1. CONFIGURACIÓN DE RUTAS Y CARGA DE LIBRERÍAS
$autoloadPath = 'C:/xampp/htdocs/final/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Error: No se encuentra Composer autoload.");
}
require_once $autoloadPath;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

include_once __DIR__ . '/../../conexion.php';

try {
    $id_inscripcion = $_GET['id_inscripcion'] ?? $_POST['id_inscripcion'] ?? 0;
    if ($id_inscripcion == 0) throw new Exception("ID de inscripción inválido.");

    date_default_timezone_set('America/Caracas');
    $fecha_actual_footer = date('d/m/Y H:i:s');

    $database = new Conexion();
    $db = $database->conectar();

    // Datos Directora
    $sql_globales = "SELECT nom_directora, ci_directora FROM globales WHERE id_globales = 1";
    $stmt_globales = $db->prepare($sql_globales);
    $stmt_globales->execute();
    $directora = $stmt_globales->fetch(PDO::FETCH_ASSOC);
    $directora_nombre = mb_strtoupper($directora['nom_directora'] ?? 'NO ASIGNADO', 'UTF-8');

    // Datos Inscripción
    $sql_inscripcion = "
        SELECT
            PE.cedula AS cedula_estudiante,
            UPPER(CONCAT(PE.primer_nombre, ' ', COALESCE(PE.segundo_nombre, ''), ' ', PE.primer_apellido, ' ', COALESCE(PE.segundo_apellido, ''))) AS nombre_estudiante,
            DATE_FORMAT(PE.fecha_nac, '%d/%m/%Y') AS fecha_nacimiento,
            UPPER(PE.lugar_nac) AS lugar_nacimiento,
            UPPER(PEE.descripcion_periodo) AS periodo_escolar,
            UPPER(N.nom_nivel) AS nivel_nombre,
            UPPER(CONCAT(N.nom_nivel, ' ', S.nom_seccion)) AS nivel_seccion,
            DATE_FORMAT(I.fecha_inscripcion, '%d') AS dia_ins,
            DATE_FORMAT(I.fecha_inscripcion, '%m') AS mes_ins,
            DATE_FORMAT(I.fecha_inscripcion, '%Y') AS anio_ins
        FROM inscripciones I
        JOIN estudiantes E ON I.id_estudiante = E.id_estudiante
        JOIN personas PE ON E.id_persona = PE.id_persona
        JOIN periodos PEE ON I.id_periodo = PEE.id_periodo
        LEFT JOIN niveles_secciones NS ON I.id_nivel_seccion = NS.id_nivel_seccion
        LEFT JOIN niveles N ON NS.id_nivel = N.id_nivel
        LEFT JOIN secciones S ON NS.id_seccion = S.id_seccion
        WHERE I.id_inscripcion = :id_inscripcion;
    ";

    $stmt = $db->prepare($sql_inscripcion);
    $stmt->execute([':id_inscripcion' => $id_inscripcion]);
    $datos = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$datos) throw new Exception("Inscripción no encontrada.");

    function get_mes_esp($n) {
        $meses = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        return $meses[(int)$n];
    }

    $tipo_nivel = (stripos($datos['nivel_nombre'], 'GRADO') !== false) ? 'Primaria' : 'Secundaria';

    // Imagen Cintillo
    $ruta_cintillo = $_SERVER['DOCUMENT_ROOT'] . '/final/public/images/cintillo_oficial.png';
    $cintillo_html = '';
    if (file_exists($ruta_cintillo)) {
        $base64 = 'data:image/png;base64,' . base64_encode(file_get_contents($ruta_cintillo));
        $cintillo_html = '<img src="' . $base64 . '" style="width: 100%;">';
    }

    // ESTRUCTURA HTML
    $html = '
    <style>
        page { color: #111; font-family: arial; }
        
        .cintillo { 
            text-align: center; 
            margin-bottom: 10px; 
            width: 100%; 
        }

        /* TÍTULO Y LÍNEA EN UN SOLO BLOQUE CENTRADO */
        .titulo-principal {
            width: 100%;
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            color: #003366;
            border-bottom: 4px solid #003366; 
            padding-bottom: 8px;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .cuerpo {
            text-align: justify;
            font-size: 12pt;
            line-height: 1.8;
            margin-top: 30px;
            width: 100%;
        }

        /* --- MODIFICACIÓN: VARIABLES DE LA BD EN NEGRITA Y AZUL --- */
        .negrita { 
            font-weight: bold; 
            color: #003366; 
        }
        /* --------------------------------------------------------- */
        
        .tabla-firma {
            width: 100%;
            margin-top: 100px;
        }
        .linea-firma {
            border-top: 2px solid #000;
            width: 400px; 
            padding-top: 5px;
        }
        .footer-info {
            text-align: center;
            font-size: 9pt;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>

    <page backtop="10mm" backbottom="20mm" backleft="20mm" backright="20mm">
        
        <page_footer>
            <div class="footer-info">
                U.E.N NUEVO HORIZONTE | Libertador - Sucre<br>
                Constancia generada el ' . $fecha_actual_footer . '<br>
                Página [[page_cu]] de [[page_nb]]
            </div>
        </page_footer>

        <div class="cintillo">' . $cintillo_html . '</div>

        <div class="titulo-principal">CONSTANCIA DE INSCRIPCIÓN</div>

        <div class="cuerpo">
            Quien suscribe <span class="negrita">' . $directora_nombre . '</span>, titular de la Cédula
            de Identidad Nº <span class="negrita">' . ($directora['ci_directora'] ?? '') . '</span>, en su condición de Director(a) de la 
            <span class="negrita">U.E.N NUEVO HORIZONTE</span>, ubicada en el municipio Libertador, 
            parroquia <span class="negrita">Sucre</span>, certifica por medio de la presente que 
            el (la) estudiante <span class="negrita">' . $datos['nombre_estudiante'] . '</span>, titular de la 
            Cédula Escolar Nº, Cédula de Identidad Nº o Pasaporte Nº <span class="negrita">' . $datos['cedula_estudiante'] . '</span>,
            nacido (a) en <span class="negrita">' . $datos['lugar_nacimiento'] . '</span> en fecha <span class="negrita">' . $datos['fecha_nacimiento'] . '</span>, 
            ha sido inscrito en esta institución para cursar el <span class="negrita">' . $datos['nivel_seccion'] . '</span> 
            de Educación ' . $tipo_nivel . ' durante el período escolar <span class="negrita">' . $datos['periodo_escolar'] . '</span>, 
            previo cumplimiento de los requisitos exigidos en la normativa legal vigente.
            <br><br>
            Constancia que se expide en <span class="negrita">Caracas</span>, a los <span class="negrita">' . date('d') . '</span> 
            días del mes de <span class="negrita">' . get_mes_esp(date('m')) . '</span> de <span class="negrita">' . date('Y') . '</span>.
        </div>

        <table class="tabla-firma" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 100%; text-align: center;">
                    <table align="center" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
                        <tr>
                            <td class="linea-firma">
                                <span class="negrita" style="font-size: 13pt;">' . $directora_nombre . '</span><br>
                                <span style="font-style: italic; font-size: 11pt; color: #444;">DIRECTOR(A)</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

    </page>';

    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
    $html2pdf->setDefaultFont('arial');
    $html2pdf->writeHTML($html);
    $html2pdf->output('Constancia_' . $datos['cedula_estudiante'] . '.pdf', 'I');

} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}