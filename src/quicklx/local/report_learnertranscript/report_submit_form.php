<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
require_once $CFG->libdir.'/gradelib.php';
require_once($CFG->dirroot.'/grade/querylib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');

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
	if (isset($_POST['perpage']) && $_POST['perpage']) {
		$params['perpage'] = $_POST['perpage'];
	}
	if (isset($_POST['daterange']) && $_POST['daterange']) {
		$params['daterange'] = $_POST['daterange'];
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
if (isset($_POST['subgroup']) && $_POST['subgroup']) {
		$params['subgroup'] =$_POST['subgroup'];
	}
		
    $departmentid = $_POST['departmentid'];   
    $email  = $_POST['toemail'];
    $emailsubject  = $_POST['emailsubject'];
    $emailbody  = $_POST['emailbody'];
    $format  = $_POST['format'];
    $attach  = $_POST['attach'];
    $selectuser  = $_POST['selectuser'];
  
	$reporthead = 'Learner\'s Transcript Report';
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
	$userdetails =$user->username.' - '.$user->firstname.' '.$user->lastname;

  	if($format=='pdf'){
		$pdf = base::createpdfobject();
		
		$pdf->SetY( 15 ); 
		$pdf->SetX( 15 ); 
		$pdf->SetFont('Helvetica','B',16);
		$pdf->writeHTML($reporthead, true, false, true, false, '');
		$pdf->SetFont('Helvetica','',10);
		$pdf->writeHTML('<br><br>'.$reportdaterange.'<br>'.$reportdate.'<br>', true, false, true, false, '');
		$pdf->SetFont('Helvetica','',10);
		$pdf->writeHTML($userdetails, true, false, true, false, '');

	}
	if($format =='csv' || $format =='xlsx'){
		$csvdata   ='"'.$reporthead."\"\n"	;
		$csvdata  .='"'.$reportdaterange."\"\n"	;
		$csvdata  .='"'.$reportdate."\"\n\n";
		$csvdata  .='"User:'.$userdetails."\"\n\n";
	}
  
$coursehead = 'My Courses';

$returnobj = base::get_all_user_courses($params);
$coursedataobj= $returnobj->courses ;
if($format =='csv' || $format =='xlsx'){
			$csvdata   .="\n".'"'.$coursehead."\"\n"	;
	}
	if($format=='html' || $format=='pdf' ){
		$html = '<br><h3>'.$coursehead.'</h3>'; 
	}	
	
//print_r($coursedataobj);
if($coursedataobj){
	
	if($format=='pdf'  || $format=='html'){

		$tablepdf ='<table>
						<thead >
							<tr style="background-color: rgb(203, 205, 208);">
								<th  >'.get_string('coursename', 'local_base').'</th>
								<th >'.get_string('score', 'local_base').'</th>
								<th  >'.get_string('datestarted', 'local_base').'</th>
								<th >'.get_string('datecompleted', 'local_base').'</th>
								<th >'.get_string('licensed', 'local_base').'</th>
							</tr>
						</thead>
					<tbody> ';
	}
	if($format =='csv' || $format =='xlsx'){
		
		$csvdata  .= '"'.get_string('coursename', 'local_base').'","'
				.get_string('score', 'local_base').'","'
				.get_string('datestarted', 'local_base').'","'
				.get_string('datecompleted', 'local_base').'","'
				.get_string('licensed', 'local_base')."\"\n"	;
	}
								$i=1;

	foreach($coursedataobj as $coursedata)	{
		//print_r($coursedata);
		$completedate = '';
		$course_completions =$DB->get_record('course_completions',array('userid'=>$userid,'course'=>$coursedata->id));
		if($course_completions){
			if(isset($course_completions->timecompleted)){
				$completedate	= date('m/d/Y',$course_completions->timecompleted);
			}
		}
		
		$grade = '';
		$grades =grade_get_course_grade($userid, $coursedata->id);
		//print_r($grades);
		if($grades){
				$grade = $grades->str_grade;			
		}
		
		$islicensed = 'No';
		$license =$DB->get_record('companylicense_users',array('userid'=>$userid,'licensecourseid'=>$coursedata->id));
		if($license){
				$islicensed = 'Yes';			
		}
		
		
		if($format=='pdf'  || $format=='html'){
											if($i%2==0)
									$style = 'background-color: #ece9e9;';
								else
									$style ='';								  			
								$tablepdf  .= '<tr style="'.$style.'">
							<td >'.$coursedata->fullname.' </td>
							<td  >'.$grade.'</td>
							<td >'.date('m/d/Y',$coursedata->timestart).' </td>
							<td >'.$completedate.' </td>
							<td >'.$islicensed.' </td>
							</tr>';
								   		$i++;


		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata .= '"'.$coursedata->fullname.
						'","'.$grade.
						'","'.date('m/d/Y',$coursedata->timestart).
						'","'.$completedate.
						'","'.$islicensed. "\"\n";
		}
				
				
	}
		if($format=='pdf'  || $format=='html'){
			$tablepdf .= '	</tbody>
						</table>
			';
			
		}
		if($format=='pdf' ){
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML($html, true, false, true, false, '');
			$pdf->writeHTMLCell(0, 0, '', '', $tablepdf, 0, 1, 0, true, '', true);
		}

}
else{
	if($format =='csv' || $format =='xlsx'){
			$csvdata   .='"There are no courses","'	;
	}
	if($format=='html' || $format=='pdf'){
		$html .= '<br>There are no courses'; 
	}
	if($format=='pdf' ){
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML($html, true, false, true, false, '');
	}
	
}		
	

$licenseshead = 'My Licenses';
$licensesobj = array();
$explicensesobj = array();
$sql ="select cu.id,cu.*,cl.*,cl.name,cl.validlength,cu.issuedate from {companylicense} cl 
		join {companylicense_users} cu on cu.licenseid=cl.id
		where cu.userid=".$userid." ORDER BY cl.name ASC";
	$userlicenses = $DB->get_records_sql($sql);
	if($userlicenses){
		foreach($userlicenses as $userlicense){
			$expiredon = strtotime("+".$userlicense->validlength." day", $userlicense->issuedate );
			if($expiredon > time()){
				$licenses = new stdclass();
				$licenses->name =$userlicense-> name;
				$licenses->issuedate =date('m/d/Y', $userlicense->issuedate);
				$licenses->expdate =date('m/d/Y', $expiredon);
				$licensesobj[$userlicense->id] =$licenses ;
			}
			else{
				$explicenses = new stdclass();
				$explicenses->name =$userlicense-> name;
				$explicenses->issuedate =date('m/d/Y', $userlicense->issuedate);
				$explicenses->expdate =date('m/d/Y', $expiredon);
				$explicensesobj[$userlicense->id] =$explicenses ;
			}
		}
		
	}	
	
if($format =='csv' || $format =='xlsx'){
			$licensecsvdata   ="\n".'"'.$licenseshead."\"\n"	;
	}
	if($format=='html' || $format=='pdf' ){
		$licensehtml = '<br><h3>'.$licenseshead.'</h3>';
		$licensetablepdf =''; 
	}	
	
if($licensesobj){
	
	if($format=='pdf'  || $format=='html'){

		$licensetablepdf ='<table>
						<thead >
							<tr style="background-color: rgb(203, 205, 208);">
								<th  >'.get_string('license', 'local_base').'</th>
								<th >'.get_string('dateregistered', 'local_base').'</th>
								<th >'.get_string('dateexp', 'local_base').'</th>
							</tr>
						</thead>
					<tbody> ';
	}
	if($format =='csv' || $format =='xlsx'){
		
		$licensecsvdata  .= '"'.get_string('license', 'local_base').'","'
				.get_string('dateregistered', 'local_base').'","'
				.get_string('dateexp', 'local_base')."\"\n"	;
	}
		
	foreach($licensesobj as $license)	{
		
		if($format=='pdf'  || $format=='html'){
			$licensetablepdf  .= '<tr>
							<td >'.$license->name.' </td>
							<td  >'.$license->issuedate.'</td>
							<td >'.$license->expdate.' </td>
							</tr>';

		}
		if($format =='csv' || $format =='xlsx'){
			$licensecsvdata .= '"'.$license->name.
						'","'.$license->issuedate.
						'","'.$license->expdate. "\"\n";
		}
				
				
	}
		if($format=='pdf'  || $format=='html'){
			$licensetablepdf .= '	</tbody>
						</table>
			';
			
		}
		if($format=='pdf' ){
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML('<br><br>'.$licensehtml, true, false, true, false, '');
			$pdf->writeHTMLCell(0, 0, '', '', $licensetablepdf, 0, 1, 0, true, '', true);
		}

}
else{
	if($format =='csv' || $format =='xlsx'){
			$licensecsvdata   .='"There are no licenses","'	;
	}
	if($format=='html' || $format=='pdf'){
		$licensehtml .= '<br>There are no licenses'; 
	}
	if($format=='pdf' ){
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML($licensehtml, true, false, true, false, '');
	}
	
}		
$explicenseshead = 'My Expired Licenses';		
	
	
	
if($format =='csv' || $format =='xlsx'){
			$explicensecsvdata   ="\n".'"'.$explicenseshead."\"\n"	;
	}
	if($format=='html' || $format=='pdf' ){
		$explicensehtml = '<br><h3>'.$explicenseshead.'</h3>';
		$explicensetablepdf=''; 
	}	
	
if($explicensesobj){
	
	if($format=='pdf'  || $format=='html'){

		$explicensetablepdf ='<table>
						<thead >
							<tr style="background-color: rgb(203, 205, 208);">
								<th  >'.get_string('license', 'local_base').'</th>
								<th >'.get_string('dateregistered', 'local_base').'</th>
								<th >'.get_string('dateexp', 'local_base').'</th>
							</tr>
						</thead>
					<tbody> ';
	}
	if($format =='csv' || $format =='xlsx'){
		
		$explicensecsvdata  .= '"'.get_string('license', 'local_base').'","'
				.get_string('dateregistered', 'local_base').'","'
				.get_string('dateexp', 'local_base')."\"\n"	;
	}
		
	foreach($explicensesobj as $explicense)	{
		
		if($format=='pdf'  || $format=='html'){
			$explicensetablepdf  .= '<tr>
							<td >'.$explicense->name.' </td>
							<td  >'.$explicense->issuedate.'</td>
							<td >'.$explicense->expdate.' </td>
							</tr>';

		}
		if($format =='csv' || $format =='xlsx'){
			$explicensecsvdata .= '"'.$explicense->name.
						'","'.$explicense->issuedate.
						'","'.$explicense->expdate. "\"\n";
		}
				
				
	}
		if($format=='pdf'  || $format=='html'){
			$explicensetablepdf .= '	</tbody>
						</table>
			';
			
		}
		if($format=='pdf' ){
			$pdf->SetFont('Helvetica','',7);
			$pdf->writeHTML('<br><br>'.$explicensehtml, true, false, true, false, '');
			$pdf->writeHTMLCell(0, 0, '', '', $explicensetablepdf, 0, 1, 0, true, '', true);
		}

}
else{
	if($format =='csv' || $format =='xlsx'){
			$explicensecsvdata   .='"There are no expired licenses","'	;
	}
	if($format=='html' || $format=='pdf'){
		$explicensehtml .= '<br>There are no expired licenses'; 
	}
	if($format=='pdf' ){
			$pdf->SetFont('Helvetica', '', 7);
			$pdf->writeHTML($explicensehtml, true, false, true, false, '');
	}
	
}		
	
if( $format=='html'){
	$fileLocation = '';
	$filename = '';
	
}

/*if($format=='pdf' ){
	$fileLocation = $CFG->tempdir;
	$filename = 'learnertranscriptreport.pdf';				
	$pdf->Output($fileLocation.'/'.$filename, 'F');
}
if($format =='csv' || $format =='xlsx'){

	$fileLocation = $CFG->tempdir;
	$filename = 'learnertranscriptreport.'.$format;
	file_put_contents($CFG->tempdir.'/'.$filename, $csvdata.$licensecsvdata.$explicensecsvdata);
}*/

	if($format=='pdf' ){
		$fileLocation = $CFG->tempdir;	
		while (true) {
			$filename = uniqid('learnerreportdetail', true) . '.pdf';
			if (!file_exists($fileLocation .'/'. $filename)) break;
		}
		$pdf->Output($fileLocation.'/'.$filename, 'F');
		//base::create_tempdf($reporthead,$reportdaterange.'<br>'.$reportdate,$tablepdf,$filename);
	}
	if($format =='csv' || $format =='xlsx'){
		$fileLocation = $CFG->tempdir;
		while (true) {
			$filename = uniqid('learnerreportdetail', true) . '.csv';
			if (!file_exists($fileLocation .'/'. $filename)) break;
		}
		file_put_contents($fileLocation.'/'.$filename, $csvdata.$licensecsvdata.$explicensecsvdata);
		//file_put_contents($CFG->tempdir.'/'.$filename, $csvdata);
	}
	if($format =='xlsx')
	{
	    include "$CFG->libdir/phpexcel/PHPExcel/IOFactory.php";
	    $objReader = PHPExcel_IOFactory::createReader('CSV');
	    $objPHPExcel = $objReader->load($fileLocation.'/'.$filename);
	    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	    $filename = "learnerreportdetail_". date('Y-m-d-H-i-s') . '.xlsx';
	    $objWriter->save($fileLocation.'/'.$filename);
	}

    $emailarray=explode(";",$email);
    
   
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $reportdaterange.'<br>'.$reportdate.$html.$tablepdf.$licensehtml.$licensetablepdf.$explicensehtml.$explicensetablepdf;
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
