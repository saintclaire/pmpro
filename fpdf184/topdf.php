<?php
require('./fpdf.php');

$pdf=new FPDF();
$id='avell';
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!dsgdsg');

$pdf->Output();
?>