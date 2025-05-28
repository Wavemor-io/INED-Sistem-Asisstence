<?php
require('phpqrcode/qrlib.php');
require('db.php');

// Validar que se proporcione un código
if (!isset($_GET['codest'])) die("Falta el código del estudiante.");

$codest = $conn->real_escape_string($_GET['codest']);
$query = "SELECT * FROM alumnos WHERE codest = '$codest'";
$result = $conn->query($query);
if ($result->num_rows == 0) die("Estudiante no encontrado.");

$row = $result->fetch_assoc();
$nombre = $row['nombre'];

// Preparar el texto del QR
$data = "$codest";

// Limpiar el nombre para que sea un nombre de archivo válido
$nombreArchivo = preg_replace('/[^A-Za-z0-9_\-]/', '_', $codest);

// Ruta y nombre del archivo QR
$carpetaQR = 'qrs/';
if (!file_exists($carpetaQR)) mkdir($carpetaQR, 0777, true);
$qrPath = $carpetaQR . $nombreArchivo . ".png";

// Generar el código QR
QRcode::png($data, $qrPath, QR_ECLEVEL_L, 4);

// Forzar descarga del archivo generado
header('Content-Description: File Transfer');
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="' . basename($qrPath) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($qrPath));
readfile($qrPath);
exit;
?>
