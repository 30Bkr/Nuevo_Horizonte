<?php
// Incluir Composer Autoload (para Html2Pdf y otras librerías)
require __DIR__ . '/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf; //Clase principal
use Spipu\Html2Pdf\Exception\Html2PdfException; //Clase de excepción específica
use Spipu\Html2Pdf\Exception\ExceptionFormatter; //Clase del formateador de errores

// --- A. Configuración y conexión a la BD ---
$host = 'localhost';
$db   = 'nuevo';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// --- B. Obtener Datos con JOIN / O usar la Sentencia SQL que corresponda---
$sql_reporte = "
    SELECT
        gs.id_grados_secciones,
        g.grado,
        gs.id_seccion,
        gs.capacidad,
        DATE(gs.creacion) AS fecha_creacion
    FROM
        grados_secciones gs
    INNER JOIN
        grados g ON gs.id_grados = g.id_grados
    ORDER BY
        g.grado, gs.id_seccion
";

$stmt = $pdo->query($sql_reporte);
$secciones = $stmt->fetchAll();

if (empty($secciones)) {
    die("No se encontraron secciones de grados para generar el reporte.");
}

// --- C. Generación del Contenido HTML ---
ob_start();
?>

<page backtop="15mm" backbottom="15mm" backleft="10mm" backright="10mm" style="font-size: 11pt;">
    <h1 style="text-align: center; color: #3498db;">📜Reporte de Grados y Secciones</h1>
    <p><strong>Fecha del Reporte:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    
    <br>
    
    <table style="width: 100%; border: 1px solid #000; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="width: 15%; border: 1px solid #000; padding: 6px;">ID Sección Grado</th>
                <th style="width: 25%; border: 1px solid #000; padding: 6px;">Grado</th>
                <th style="width: 15%; border: 1px solid #000; padding: 6px;">ID Sección</th>
                <th style="width: 20%; border: 1px solid #000; padding: 6px;">Capacidad</th>
                <th style="width: 25%; border: 1px solid #000; padding: 6px;">Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($secciones as $registro): ?>
            <tr>
                <td style="border: 1px solid #000; padding: 6px; text-align: center;"><?php echo $registro['id_grados_secciones']; ?></td>
                <td style="border: 1px solid #000; padding: 6px;">**Grado <?php echo $registro['grado']; ?>**</td>
                <td style="border: 1px solid #000; padding: 6px; text-align: center;"><?php echo $registro['id_seccion']; ?></td>
                <td style="border: 1px solid #000; padding: 6px; text-align: center;"><?php echo $registro['capacidad']; ?> alumnos</td>
                <td style="border: 1px solid #000; padding: 6px; text-align: center;"><?php echo $registro['fecha_creacion']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p style="margin-top: 20px;">**Total de Registros:** <?php echo count($secciones); ?></p>
</page>

<?php
$content = ob_get_clean();

// --- D. Conversión a PDF y Salida ---
try {
    // Inicializar Html2Pdf
    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', [10, 10, 10, 10]);
    
    $html2pdf->writeHTML($content);
    
    // Generar el PDF para su descarga ('D')
    $html2pdf->Output('reporte_grados_secciones_' . date('Ymd') . '.pdf', 'D');

} catch (Html2PdfException $e) {
    // Manejo de la excepción con el formateador para ver el error
    $formatter = new ExceptionFormatter($e);
   echo "Error de Html2Pdf: " . $e->getMessage();
    exit;
} catch (\Exception $e) {
    echo "Un error inesperado ocurrió: " . $e->getMessage();
}
?>