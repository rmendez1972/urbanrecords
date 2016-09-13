<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->Cell(40,10,'¡Hola, Mundo!');

$pdf->Output();
?>
