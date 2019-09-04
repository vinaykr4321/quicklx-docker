<?php
//require('../../config.php');
require('fpdf.php');
extract($_POST);

$dataToDownload = json_decode($pdfdata);

$pdf = new FPDF();

$pdf->AddPage("L"); // For landscape mode

$pdf->SetFont('Arial','B',15);
$pdf->Cell(150,15,'User Registration',0,'L');
$pdf->Ln();

$pdf->SetFont('Arial','B',9); // For header
foreach($dataToDownload as $rowValue)
{
	$i=1;
	foreach($rowValue as $columnValue)
	{

		if ($i==1 || $i==2 || $i==5) 
		{

			$pdf->Cell(40,10,$columnValue,1,'L');
		}
		else
		{
			$pdf->Cell(25,10,$columnValue,1,'L');
		}
		$i++;
	}
		$pdf->SetFont('Arial','',8);
		$pdf->Ln();
}
$pdf->Output();
?>