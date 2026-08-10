<?php
ob_clean(); // Limpia cualquier salida previa

require(__DIR__ . '/fpdf.php');

// 🔧 Conexión a la base de datos (actualizada para InfinityFree)
$conn = new mysqli("localhost","root","", "textil_db");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("ID no especificado");
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM notas WHERE idNota = $id");

if ($result->num_rows == 0) {
    die("Registro no encontrado");
}

$dato = $result->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Nota de Almacén N° ' . $dato['idNota']), 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
foreach ($dato as $campo => $valor) {
    $valor = $valor ?? ''; // Evita warnings por valores nulos
    $pdf->Cell(60, 8, utf8_decode(ucfirst($campo)) . ':', 0, 0);
    $pdf->Cell(0, 8, utf8_decode((string)$valor), 0, 1);
}

$pdf->Output();
exit; // Finaliza correctamente el script
?>
