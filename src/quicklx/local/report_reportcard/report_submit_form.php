<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
require_once $CFG->libdir.'/gradelib.php';
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');

require_login($SITE);

if(isset($_POST['contactFrmSubmit']) 
			&& !empty($_POST['emailsubject'])
			&& !empty($_POST['format'])
			&& !empty($_POST['attach'])
			&& !empty($_POST['toemail']) 
			&& !empty($_POST['emailbody'])){
    
    // Submitted form data
    $params=array();
 
	if (isset($_POST['user']) && $_POST['user']) {
		$params['user'] = $_POST['user'];
	}
	if (isset($_POST['selectuser']) && $_POST['selectuser']) {
		$params['selectuser'] = $_POST['selectuser'];
	}

	if(isset($_POST['datefrom']) && $_POST['datefrom']!=0){
		$params['datefrom'] = $_POST['datefrom'];
	}
	if(isset($_POST['dateto']) && $_POST['dateto']!=0){
		$params['dateto'] = $_POST['dateto'];
	}
	if (isset($_POST['course']) && $_POST['course']) {
		$params['course'] = $_POST['course'];
	}
	if (isset($_POST['selectcourse']) && $_POST['selectcourse']) {
		$params['selectcourse'] = $_POST['selectcourse'];
	}
	if (isset($_POST['departmentid']) && $_POST['departmentid']) {
		$params['departmentid'] =$_POST['departmentid'];
	}	
if (isset($_POST['daterange']) && $_POST['daterange']) {
		$params['daterange'] =$_POST['daterange'];
	}	
if (isset($_POST['subgroup']) && $_POST['subgroup']) {
		$params['subgroup'] =$_POST['subgroup'];
	}
    $departmentid = $_POST['departmentid'];   
    $email  = $_POST['toemail'];
    $emailsubject  = $_POST['emailsubject'];
    $emailbody  = $_POST['emailbody'];
    $format  = $_POST['format'];
    $attach  = $_POST['attach'];
    $selectcourse  = $_POST['selectcourse'];
    $selectuser  = $_POST['selectuser'];
  
	$modules = report_reportcard::get_all_course_modules( $params);

	if($modules){
		
		$reporthead = 'Report Card ';
		if($params['daterange'] !='no'){
		$reportdaterange = 'Date Range: '. get_string($params['daterange'], 'local_base');
		$reportdate = date('m/d/Y',$params['datefrom']).' to '.date('m/d/Y',$params['dateto']);
	}
	else{
		$reportdaterange =  get_string($params['daterange'], 'local_base');
		$reportdate =  '';
	} 
		$userid=$selectuser;
		$user=$DB->get_record('user',array('id'=>$userid));
		$userdetails =$user->username.' ( '.$user->firstname.' '.$user->lastname.') ';
		$courseid=$selectcourse;
		$course=$DB->get_record('course',array('id'=>$courseid));
		$coursedetails =$course->fullname;

			
		if($format=='pdf'){
			$pdf = base::createpdfobject();
			$html = $reportdaterange.'<br>'.$reportdate.'<br> User: '.$userdetails.'<br>Course: '.$coursedetails;

			// output the HTML content
			$pdf->SetY( 15 ); 
			$pdf->SetX( 15 ); 
			$pdf->SetFont('Helvetica','B',16);
			$pdf->writeHTML($reporthead, true, false, true, false, '');
			$pdf->SetFont('Helvetica','',10);
			$pdf->writeHTML('<br><br>'.$html.'<br>', true, false, true, false, '');

		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata  .='"'.$reportdaterange."\"\n"	;
			$csvdata  .='"'.$reportdate."\"\n\n"	;
			$csvdata  .='"User:'.$userdetails."\"\n";
			$csvdata  .='"Course:'.$coursedetails."\"\n\n";
		}
		
		$shtml = $reportdaterange.'<br>'.$reportdate.'<br> User: '.$userdetails.'<br>Course: '.$coursedetails;
		foreach($modules as $module){
			if($format =='csv' || $format =='xlsx'){
				$csvdata   .="\n".'"'.ucfirst($module->name)."\"\n"	;
			}
			if($format=='html' ){
				$shtml .= '<br><h3>'.ucfirst($module->name).'</h3>'; 
			}
			if($format=='pdf'  || $format=='html'){

			$tablepdf ='<table>
							<thead >
								<tr style="background-color: rgb(203, 205, 208);">
									<th  >'.get_string('name', 'local_base').'</th>
									<th >'.get_string('grade', 'local_base').'</th>
									<th  >'.get_string('completionstatus', 'local_base').'</th>
									<th >'.get_string('datecompleted', 'local_base').'</th>
								</tr>
							</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			
			$csvdata  .= '"'.get_string('name', 'local_base').'","'
					.get_string('grade', 'local_base').'","'
					.get_string('completionstatus', 'local_base').'","'
					.get_string('datecompleted', 'local_base')."\"\n"	;
		}
		
		$params['module']=$module->id;	
		$params['modulename']=$module->name;	
		$coursemodules = report_reportcard::get_all_module_details( $params);
		if($coursemodules){
									$i=1;

			foreach($coursemodules as $coursemodule){
				$name=$coursemodule->name;
				$sql="SELECT * FROM {course_modules_completion} 
						where coursemoduleid=".$coursemodule->id." AND userid=".$userid;
				$cmcompletion =$DB->get_record_sql($sql);
				if($cmcompletion){
					if($cmcompletion->completionstate >0){
						$completionstate ='Yes';
						$completiondate = date('m/d/Y',$cmcompletion->timemodified);
					}
					else{
						$completionstate ='No';
						$completiondate = '';
					}
				}
				else{
					$completionstate ='No';
					$completiondate = '';
				}
				$gradeitem = grade_get_grades($courseid, 'mod', $module->name, $coursemodule->instance, $userid);
				$grade ='';
				if($gradeitem->items){
					$grade = $gradeitem->items[0]->grades[$userid]->grade;
					$grade =number_format($grade,2);
				}
				
				if($format=='pdf'  || $format=='html'){
													if($i%2==0)
									$style = 'background-color: #ece9e9;';
								else
									$style ='';								  			
								$tablepdf  .= '<tr style="'.$style.'">
									<td >'.$name.' </td>
									<td  >'.$grade.'</td>
									<td >'.$completionstate.' </td>
									<td >'.$completiondate.' </td>
									</tr>';
										   		$i++;


				}
				if($format =='csv' || $format =='xlsx'){
					$csvdata .= '"'.$name.
										'","'.$grade.
										'","'.$completionstate.
										'","'.$completiondate. "\"\n";
				}
				
				
			}
		}
		if($format=='pdf'  || $format=='html'){
			$tablepdf .= '	</tbody>
						</table>
			';
			$shtml .=$tablepdf;
			
		}
		if($format=='pdf' ){
			$html = '<br><h3>'.ucfirst($module->name).'</h3>'; 
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->writeHTMLCell(0, 0, '', '', $tablepdf, 0, 1, 0, true, '', true);
		}
		
		
	}
		if( $format=='html'){
			$fileLocation = '';
			$filename = '';
			
		}	
                if($format=='pdf' ){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('reportcarduser', true) . '.pdf';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
			$pdf->Output($fileLocation.'/'.$filename, 'F');
                }
                if($format =='csv' || $format =='xlsx'){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('reportcarduser', true) . '.csv';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
                        file_put_contents($fileLocation.'/'.$filename, $csvdata);
                }
                if($format =='xlsx')
                {
                    include "$CFG->libdir/phpexcel/PHPExcel/IOFactory.php";
                    $objReader = PHPExcel_IOFactory::createReader('CSV');
                    $objPHPExcel = $objReader->load($fileLocation.'/'.$filename);
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $filename = "reportcarduser_". date('Y-m-d-H-i-s') . '.xlsx';
                    $objWriter->save($fileLocation.'/'.$filename);
                }
	}
		
    $emailarray=explode(";",$email);
    
  
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $shtml;
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
