<?php
session_start();

// CONFIGURACIÓN DE RUTAS
$autoloadPath = 'C:/xampp/htdocs/final/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Error: No se encuentra Composer autoload.");
}
require_once $autoloadPath;

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// CONEXIÓN BD
include_once __DIR__ . '/../../conexion.php';

try {
    // 1. OBTENER ID
    $id_inscripcion = $_GET['id_inscripcion'] ?? $_POST['id_inscripcion'] ?? 0;
    
    if ($id_inscripcion == 0) throw new Exception("ID de inscripción inválido.");

    // Configuración Regional
    date_default_timezone_set('America/Caracas');
    $fecha_actual = date('d/m/Y h:i:s A'); // Formato con AM/PM

    // 2. CONECTAR Y CONSULTAR DATOS
    $database = new Conexion();
    $db = $database->conectar();

    // Datos Directora
    $sql_globales = "SELECT nom_directora, ci_directora FROM globales WHERE id_globales = 1";
    $stmt_globales = $db->prepare($sql_globales);
    $stmt_globales->execute();
    $directora = $stmt_globales->fetch(PDO::FETCH_ASSOC);

    if (!$directora) throw new Exception("Faltan datos de la directora.");

    $directora_nombre = mb_strtoupper($directora['nom_directora'], 'UTF-8');

    // Datos Inscripción
    $sql_inscripcion = "
        SELECT
            I.id_inscripcion,
            PE.cedula AS cedula_estudiante,
            UPPER(CONCAT(PE.primer_nombre, ' ', COALESCE(PE.segundo_nombre, ''), ' ', PE.primer_apellido, ' ', COALESCE(PE.segundo_apellido, ''))) AS nombre_estudiante,
            DATE_FORMAT(PE.fecha_nac, '%d/%m/%Y') AS fecha_nacimiento,
            UPPER(PE.lugar_nac) AS lugar_nacimiento,
            UPPER(PEE.descripcion_periodo) AS periodo_escolar,
            UPPER(N.nom_nivel) AS nivel_nombre,
            UPPER(S.nom_seccion) AS seccion_nombre,
            UPPER(CONCAT(N.nom_nivel, ' ', S.nom_seccion)) AS nivel_seccion,
            DATE_FORMAT(I.fecha_inscripcion, '%d') AS dia_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%m') AS mes_inscripcion,
            DATE_FORMAT(I.fecha_inscripcion, '%Y') AS anio_inscripcion
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

    // Meses
    function get_mes($n) {
        $meses = ['', 'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
        return $meses[(int)$n];
    }
    $mes_texto = get_mes($datos['mes_inscripcion']);
    $tipo_nivel = (stripos($datos['nivel_nombre'], 'GRADO') !== false) ? 'Primaria' : 'Secundaria';

    // Datos Fijos
    $INSTITUCION = 'U.E.N NUEVO HORIZONTE';
    $PARROQUIA = 'Sucre';
    $MUNICIPIO = 'Libertador';
    $CIUDAD = 'Caracas'; 

    // Imagen Cintillo
    $ruta_cintillo = $_SERVER['DOCUMENT_ROOT'] . '/final/public/images/cintillo_oficial.png';
    $cintillo_html = '<div style="padding:20px; text-align:center; font-weight:bold;">' . $INSTITUCION . '</div>';
    
    if (file_exists($ruta_cintillo)) {
        $img_data = file_get_contents($ruta_cintillo);
        $base64 = 'data:image/png;base64,' . base64_encode($img_data);
        $cintillo_html = '<img src="' . $base64 . '" style="width: 700px; height: auto;" alt="Cintillo">';
    }

    // 3. HTML ESTRUCTURADO CON ETIQUETAS <PAGE>
    $html = '
    <style>
        .titulo {
            text-align: center; font-weight: bold; font-size: 16pt; color: #003366;
            text-decoration: underline; margin: 20px 0 40px 0;
        }
        .texto-cuerpo {
            text-align: justify; text-justify: inter-word;
            font-size: 13pt; line-height: 1.6; margin-bottom: 20px;
        }
        .negrita { font-weight: bold; color: #003366; }
    </style>

    <page backtop="20mm" backbottom="20mm" backleft="20mm" backright="20mm">

        <page_footer>
            <div style="text-align: center; font-size: 9pt; color: #555; border-top: 1px solid #ccc; padding-top: 5px; width: 100%;">
                ' . $INSTITUCION . ' | ' . $MUNICIPIO . ' - ' . $PARROQUIA . '<br>
                Generado el: ' . $fecha_actual . '<br>
                Página [[page_cu]] de [[page_nb]]
            </div>
        </page_footer>

        <div style="text-align: center; margin-bottom: 20px;">
            ' . $cintillo_html . '
        </div>

        <div class="titulo">CONSTANCIA DE INSCRIPCIÓN</div>

        <div class="texto-cuerpo">
            Quien suscribe <span class="negrita">' . $directora_nombre . '</span>, titular de la Cédula
            de Identidad Nº <span class="negrita">' . $directora['ci_directora'] . '</span>, en su condición de Director(a) de la 
            <span class="negrita">'. $INSTITUCION . '</span>, ubicada en el municipio ' . $MUNICIPIO . ', 
            parroquia <span class="negrita">' . $PARROQUIA . '</span>, certifica por medio de la presente que 
            el (la) estudiante <span class="negrita">' . $datos['nombre_estudiante'] . '</span>, titular de la 
            Cédula Escolar Nº, Cédula de Identidad Nº o Pasaporte Nº <span class="negrita">' . $datos['cedula_estudiante'] . '</span>,
            nacido (a) en <span class="negrita">' . $datos['lugar_nacimiento'] . '</span> en fecha <span class="negrita">' . $datos['fecha_nacimiento'] . '</span>, 
            ha sido inscrito en esta institución para cursar el <span class="negrita">' . $datos['nivel_seccion'] . '</span> 
            de Educación ' . $tipo_nivel . ' durante el período escolar <span class="negrita">' . $datos['periodo_escolar'] . '</span>, 
            previo cumplimiento de los requisitos exigidos en la normativa legal vigente.
            <br><br>
            Constancia que se expide en <span class="negrita">' . $CIUDAD . '</span>, a los <span class="negrita">' . $datos['dia_inscripcion'] . '</span> 
            días del mes de <span class="negrita">' . $mes_texto . '</span> de <span class="negrita">' . $datos['anio_inscripcion'] . '</span>.
        </div>

        <div style="margin-top: 80px; text-align: center; width: 100%;">
            <table align="center" style="margin: 0 auto;">
                <tr>
                    <td style="width: 300px; border-top: 1px solid #000; text-align: center; padding-top: 5px;">
                        <span style="font-weight: bold; font-size: 14px;">' . $directora_nombre . '</span><br>
                        <span style="font-style: italic; font-size: 12px; color: #666;">DIRECTOR(A)</span>
                    </td>
                </tr>
            </table>
        </div>

    </page>';

    // 4. GENERAR PDF
    // OJO: Quitamos los márgenes del array final porque ya los definimos en <page>
    $html2pdf = new Html2Pdf('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
    $html2pdf->setDefaultFont('arial');
    $html2pdf->writeHTML($html);
    
    $nombre_archivo = 'Constancia_' . $datos['cedula_estudiante'] . '.pdf';
    $html2pdf->output($nombre_archivo, 'I');

} catch (Html2PdfException $e) {
    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>