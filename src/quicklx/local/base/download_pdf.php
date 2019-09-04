<?php
require('../../config.php');

require_once($CFG->dirroot.'/local/base/locallib.php');
extract($_POST);
$name = $name;
$dataToDownload = json_decode($pdfdata);

$pdf = base::createpdfobject();


$r=1;
if($name == 'modulereportcard'){
	foreach($dataToDownload as $rowValue)
	{
		if($r==4 || $r==6 || $r==8){
		
				$pdf->SetFont('Helvetica', '', 8);
				$pdf->writeHTMLCell(0, 0, '', '', $rowValue, 0, 1, 0, true, '', true);//table
				//$pdf->writeHTML($rowValue, true, false, false, false, '');

				$pdf->Ln();

		}
		else{
			foreach($rowValue as $columnValue)
			{
				if($r==1){
					$pdf->SetY( 15 ); 
					$pdf->SetX( 15 ); 
					$pdf->SetFont('Helvetica','B',18);
				}
				else
					$pdf->SetFont('Helvetica','',12);

				$pdf->writeHTML($columnValue, true, false, true, false, '');//text
				$pdf->Ln();
			}
		}
		$r++;

	}
}
else{
	foreach($dataToDownload as $rowValue){
		if($r==3 || $r==5 || $r==7){
			$pdf->SetFont('Helvetica', '', 8);
			$pdf->writeHTMLCell(0, 0, '', '', $rowValue, 0, 1, 0, true, '', true);//table
			$pdf->Ln();

		}
		else{
			foreach($rowValue as $columnValue)
			{
				if($r==1){
					$pdf->SetY( 15 ); 
					$pdf->SetX( 15 ); 
					$pdf->SetFont('Helvetica','B',18);
				}
				else
					$pdf->SetFont('Helvetica','',12);
			
				$pdf->writeHTML($columnValue, true, false, true, false, '');//text
				$pdf->Ln();
			}
		}
		$r++;
	}
}
$pdf->Output($name.'.pdf','D');
?>
