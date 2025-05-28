<?php
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');
require('db.php');

$query = "SELECT * FROM alumnos";
$result = $conn->query($query);
if ($result->num_rows == 0) die("No hay estudiantes.");

// Crear carpeta QR si no existe
$carpetaQR = 'qrs/';
if (!file_exists($carpetaQR)) mkdir($carpetaQR, 0777, true);

// Crear PDF
$pdf = new FPDF('P', 'mm', 'A4');

while ($row = $result->fetch_assoc()) {
    $codest = $row['codest'];
    $nombre = $row['nombre'];

    // Generar nombre de archivo seguro
    $qrNombre = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombre);
    $qrPath = $carpetaQR . $qrNombre . ".png";

    // Datos del QR
    $data = "Código: $codest | Nombre: $nombre";
    QRcode::png($data, $qrPath, QR_ECLEVEL_L, 6); // tamaño 6 para buena calidad

    // Nueva página
    $pdf->AddPage();

    // Insertar QR centrado
    $pdf->Image($qrPath, 70, 80, 70, 70);

    // Texto debajo del QR
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetY(155);
    $pdf->SetX(10);
    $pdf->Cell(0, 10, utf8_decode($nombre), 0, 1, 'C');

    // Eliminar QR temporal
    unlink($qrPath);
}

// Descargar PDF
$pdf->Output("D", "QR_Estudiantes.pdf");
?>
