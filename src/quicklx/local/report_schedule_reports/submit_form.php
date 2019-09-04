<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');

require_login($SITE);

if(isset($_POST['contactFrmSubmit']) 
			&& !empty($_POST['emailsubject'])
			&& !empty($_POST['format'])
			&& !empty($_POST['toemail']) 
			&& !empty($_POST['emailbody'])){
    
    // Submitted form data
    $params=array();

    $departmentid = $_POST['departmentid'];   
    $email  = $_POST['toemail'];
    $emailsubject  = $_POST['emailsubject'];
    $emailbody  = $_POST['emailbody'];
    $format  = $_POST['format'];
    //$attach  = $_POST['attach'];
    	
	$reporthead = 'Scheduled Reports';
	$activehead = 'Active Scheduled Reports';
	$inactivehead = 'Inactive Scheduled Reports';
	$returnobj = report_schedule_reports::scheduled_reports($departmentid);

	if($returnobj){
		$active= $returnobj->active ;
		$inactive= $returnobj->inactive ;
		
			
		if($format=='pdf'){
				$pdf = base::createpdfobject();
			$pdf->SetY( 15 ); 
			$pdf->SetX( 15 ); 
			$pdf->SetFont('Helvetica','B',16);
			$pdf->writeHTML($reporthead, true, false, true, false, '');
		
		}
		if($format=='pdf'  || $format=='html'){
		$htmlreport = '';
		$activepdf = $inactivepdf ='<table >
						<thead  >
						<tr style="background-color: rgb(203, 205, 208);">
						<th class="header c1" style="text-align:center;" >'.get_string('description', 'local_report_schedule_reports').'</th>
						<th class="header c3" style="text-align:center;" >'.get_string('nextrun', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('lastrun', 'local_report_schedule_reports').'</th>
						<th colspan="2" class="header c4" style="text-align:center;" >'.get_string('recipients', 'local_report_schedule_reports').'</th>
						<th colspan="2" class="header c4" style="text-align:center;" >'.get_string('scheduledescription', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('format', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('pause', 'local_report_schedule_reports').'</th>
						</tr>
						</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata   .="\n".'"'.$activehead."\"\n"	;
			$data  = '"'.get_string('description', 'local_report_schedule_reports').'","'
						.get_string('nextrun', 'local_report_schedule_reports').'","'
						.get_string('lastrun', 'local_report_schedule_reports').'","'
						.get_string('recipients', 'local_report_schedule_reports').'","'
						.get_string('scheduledescription', 'local_report_schedule_reports').'","'
						.get_string('format', 'local_report_schedule_reports').'","'
						.get_string('pause', 'local_report_schedule_reports')."\"\n"	;
			$inactivedata = $data ;
			$csvdata  .= $data;	
		}
		if($format=='html' || $format=='pdf' ){
				$html = '<br><h3>'.$activehead.'</h3>'; 
		}
		if($active){			
			$i=1;	
			foreach($active as $key=>$report){
				$report->emailusers = str_replace(';', '; ', $report->emailusers);
				$nextrun ='';
				if($report->nextrun > 0)
					$nextrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->nextrun));
				$lastrun ='';
				if($report->lastrun > 0)
					$lastrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->lastrun));
					
				$discription = report_schedule_reports::sceduleddescription($report);
				$reportformat = strtoupper($report->format);
				
				if($report->pause == 0){
					$exportpause = 'play';
				}else{
					$exportpause = 'paused';
				}
				if($format=='pdf'  || $format=='html'){
					if($i%2==0)
									$style = 'background-color: #ece9e9;';
								else
									$style ='';	
					$activepdf  .= '<tr style="'.$style.'">
										<td class="cell c1" style="text-align:center;">'.$report->description.'</td>
										<td class="cell c1" style="text-align:center;">'.$nextrun.'</td>
										<td class="cell c1" style="text-align:center;">'.$lastrun.'</td>
										<td colspan="2" class="cell c1" style="text-align:center;">'.$report->emailusers.'</td>
										<td colspan="2" class="cell c1" style="text-align:center;">'.$discription.'</td>
										<td class="cell c1" style="text-align:center;">'.$reportformat.'</td>
										<td class="cell c1" style="text-align:center;">'.$exportpause.'</td>
										</tr>';
									   		$i++;

				}
				if($format =='csv' || $format =='xlsx'){
					$csvdata .= '"'.$report->description.
										'","'.$nextrun.
										'","'.$lastrun.
										'","'.$report->emailusers.
										'","'.$discription.
										'","'.$reportformat.
										'","'.$exportpause. "\"\n";
				}		
				
			}
			if($format=='pdf'  || $format=='html'){
				$activepdf .= '	</tbody>
							</table>
				';
				
			}
			if($format=='html'){
				$htmlreport .=$html.$activepdf;
			}
			if($format=='pdf' ){
				$pdf->SetFont('Helvetica','',7);
				$pdf->writeHTML($html, true, false, true, false, '');
				$pdf->writeHTMLCell(0, 0, '', '', $activepdf, 0, 1, 0, true, '', true);
			}
			
		}
		else{
			if($format =='csv' || $format =='xlsx'){
					$csvdata   .='"There are no active report","'	;
			}
			if($format=='html' || $format=='pdf'){
				$html .= '<br>There are no active report'; 
			}
			if($format=='html'){
				$htmlreport =$html;
			}
			if($format=='pdf' ){
					$pdf->SetFont('Helvetica','',7);
					$pdf->writeHTML($html, true, false, true, false, '');
			}
			
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   .="\n".'"'.$inactivehead."\"\n"	;
		}
		if($format=='html' || $format=='pdf' ){
			$html = '<br><h3>'.$inactivehead.'</h3>'; 
		}		
		if($inactive){	
											$i=1;	
			foreach($inactive as $key=>$report){
				$report->emailusers = str_replace(';', '; ', $report->emailusers);
				$nextrun ='';
				/*if($report->nextrun > 0)
					$nextrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->nextrun));*/
				$lastrun ='';
				if($report->lastrun > 0)
					$lastrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->lastrun));

					
				$discription = report_schedule_reports::sceduleddescription($report);
				$reportformat = strtoupper($report->format);
				
				if($report->pause == 0){
					$exportpause = 'play';
				}else{
					$exportpause = 'paused';
				}
				if($format=='pdf'  || $format=='html'){
					if($i%2==0)
									$style = 'background-color: #ece9e9;';
								else
									$style ='';	
					$inactivepdf  .= '<tr style="'.$style.'">
										<td class="cell c1" style="text-align:center;">'.$report->description.'</td>
										<td class="cell c1" style="text-align:center;">'.$nextrun.'</td>
										<td class="cell c1" style="text-align:center;">'.$lastrun.'</td>
										<td colspan="2" class="cell c1" style="text-align:center;">'.$report->emailusers.'</td>
										<td colspan="2" class="cell c1" style="text-align:center;">'.$discription.'</td>
										<td class="cell c1" style="text-align:center;">'.$reportformat.'</td>
										<td class="cell c1" style="text-align:center;">'.$exportpause.'</td>
										</tr>';
									   		$i++;

				}
				if($format =='csv' || $format =='xlsx'){
					$inactivedata .= '"'.$report->description.
										'","'.$nextrun.
										'","'.$lastrun.
										'","'.$report->emailusers.
										'","'.$discription.
										'","'.$reportformat.
										'","'.$exportpause. "\"\n";
				}		
				
			}
			if($format=='pdf'  || $format=='html'){
				$inactivepdf .= '	</tbody>
							</table>
				';
				
			}
			if($format=='html'){
				$htmlreport .=$html.$inactivepdf;
			}
			if($format=='pdf' ){
				$pdf->SetFont('Helvetica','',7);
				$pdf->writeHTML($html, true, false, true, false, '');
				$pdf->writeHTMLCell(0, 0, '', '', $inactivepdf, 0, 1, 0, true, '', true);
			}
			
		}
		else{
			if($format =='csv' || $format =='xlsx'){
					$csvdata   .='"There are no inactive report","'	;
			}
			if($format=='html' || $format=='pdf'){
				$html .= '<br>There are no inactive report'; 
			}
			if($format=='html'){
				$htmlreport .=$html;
			}
			if($format=='pdf' ){
				$pdf->SetFont('Helvetica','',7);
					$pdf->writeHTML($html, true, false, true, false, '');
			}
			
		}	
		
	}	
	
	
		if($format=='html'){
			
			$fileLocation = '';
			$filename = '';
			
		}		
                if($format=='pdf' ){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('scheduledreport', true) . '.pdf';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
			$pdf->Output($fileLocation.'/'.$filename, 'F');
                }
                if($format =='csv' || $format =='xlsx'){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('scheduledreport', true) . '.csv';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
                        file_put_contents($fileLocation.'/'.$filename, $csvdata.$inactivedata);
                }
                if($format =='xlsx')
                {
                    include "$CFG->libdir/phpexcel/PHPExcel/IOFactory.php";
                    $objReader = PHPExcel_IOFactory::createReader('CSV');
                    $objPHPExcel = $objReader->load($fileLocation.'/'.$filename);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $filename = "scheduledreport_". date('Y-m-d-H-i-s') . '.xlsx';
                    $objWriter->save($fileLocation.'/'.$filename);
                }
	
    $emailarray=explode(";",$email);
    
   
 
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $htmlreport;
	else
		$eventdata->fullmessagehtml   = '';

	$sendfrom=get_admin();
	foreach($emailarray as $key=>$value){
		//echo $value;
		if(!empty($value)){
			$sendto=$DB->get_record('user',array('email'=>$value));
			if(!$sendto){
				$sendto = core_user::get_user(1);
				$sendto->email = $value;
				$sendto->firstname = '';
				$sendto->lastname='';
			}			 
			if(email_to_user($sendto, $sendfrom,$eventdata->subject,$eventdata->fullmessage ,$eventdata->fullmessagehtml, $fileLocation.'/'.$filename, $filename)){
					$status = 'ok';
			}else{
				$status = 'err';
			}
		} 
	 }
	if(!empty($users) ){
		 foreach($users as $user){	
			if(email_to_user($user, $sendfrom,$eventdata->subject,$eventdata->fullmessage ,$eventdata->fullmessagehtml, $fileLocation.'/'.$filename, $filename)){
				$status = 'ok';
			}else{
				$status = 'err';
			}
		 
		 }
	 }
    
    // Output status
    echo $status;die;
}
