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
			&& !empty($_POST['attach'])
			&& !empty($_POST['toemail']) 
			&& !empty($_POST['emailbody'])){
    
    // Submitted form data
    $params=array();
 
	if (isset($_POST['user']) && $_POST['user']) {
		$params['user'] = $_POST['user'];
	}
	if (isset($_POST['firstname']) && $_POST['firstname']) {
		$params['firstname'] = $_POST['firstname'];
	}
	if (isset($_POST['lastname']) && $_POST['lastname']) {
		$params['lastname'] = $_POST['lastname'];
	}
	if (isset($_POST['username']) && $_POST['username']) {
		$params['username'] = $_POST['username'];
	}
	if (isset($_POST['email']) && $_POST['email']) {
		$params['email'] = $_POST['email'];
	}
	if (isset($_POST['organization']) && $_POST['organization']) {
		$params['organization'] =$_POST['organization'];
	}
	if (isset($_POST['activestatus']) && $_POST['activestatus']) {
		$params['activestatus'] = $_POST['activestatus'];
	}
	if (isset($_POST['country']) && $_POST['country']) {
		$params['country'] = $_POST['country'];
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
    	//print_r($params);

    $params['download'] = 'download';
    	//print_r($params);

	$returnobj = report_course_completion::get_all_users_courses($params);
	$exportuserdataobj= $returnobj->courses ;
	unset($params['download']); 
	//print_r($exportuserdataobj);
	$countries = get_string_manager()->get_list_of_countries();
		
	if($exportuserdataobj){
		$fileLocation = $CFG->tempdir; 
		$fileLocation = rtrim($fileLocation, '/') . '/';
		if(isset($params['daterange']) && $params['daterange'] =='no' ){
			unset($params['datefrom']); 
			unset($params['dateto']); 	 
		}
		$reporthead = 'Course Completion Report';
		if($params['daterange'] !='no'){
			$reportdaterange = 'Date Range: '. get_string($params['daterange'], 'local_base').'<br>';
			$reportdate = date('m/d/Y',$params['datefrom']).' to '.date('m/d/Y',$params['dateto']);
		}
		else{
			$reportdaterange =  get_string($params['daterange'], 'local_base'); 
			$reportdate =  '';
		}    
			
		if($format=='pdf'){
			$pdf = base::createpdfobject();

		}
		if($format=='pdf'  || $format=='html'){

			 $tablepdf ='<table class="generaltable" id="ReportTable">
						<thead >
						<tr style="background-color: rgb(203, 205, 208);">
						<th  class="header c0" style="text-align:center;" >'.get_string('firstname', 'local_base').'</th>
						<th  class="header c1" style="text-align:center;" >'.get_string('lastname', 'local_base').'</th>
						<th class="header c3" style="text-align:center;" >'.get_string('username', 'local_base').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('address', 'local_report_course_completion').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('city', 'local_report_course_completion').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('country', 'local_base').'</th>
						<th colspan="2" class="header c4" style="text-align:center;" >'.get_string('email', 'local_base').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('phone', 'local_report_course_completion').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('coursename', 'local_base').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('coursecode', 'local_report_course_completion').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('organization', 'local_base').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('datelastupdated', 'local_report_course_completion').'</th>
						</tr>
						</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata  .='"'.$reportdaterange."\"\n"	;
			$csvdata  .='"'.$reportdate."\"\n"	;
			$csvdata  .= '"'.get_string('firstname', 'local_base').'","'
							.get_string('lastname', 'local_base').'","'
							.get_string('username', 'local_base').'","'
							.get_string('address', 'local_report_course_completion').'","'
							.get_string('city', 'local_report_course_completion').'","'
							.get_string('country', 'local_base').'","'
							.get_string('email', 'local_base').'","'
							.get_string('phone', 'local_report_course_completion').'","'
							.get_string('coursename', 'local_base').'","'
							.get_string('datecompleted', 'local_base').'","'
							.get_string('coursecode', 'local_report_course_completion').'","'
							.get_string('organization', 'local_base').'","'
							.get_string('datelastupdated', 'local_report_course_completion')."\"\n"	;
		}
		$i=1;

		foreach($exportuserdataobj as $user){
		
			$userid=$user->id;
			$courseid=$user->cid;
			$showcountry="";
			foreach($countries as $key=>$value){
				if($user->country ==$key ){
					$showcountry = $value;
					break;
				}
				
			}
			 $sql = "select * from {logstore_standard_log} 
			where eventname='\\\core\\\\event\\\course_viewed' and userid=".$userid." and courseid=".$courseid.
			" ORDER BY timecreated DESC LIMIT 0,1";
			$lastupdated = $DB->get_record_sql($sql);
			
			$sql = "select name from {company} c 
			join  {company_users} cu on c.id=cu.companyid 
			where cu.userid=".$userid;
			$userorganization = $DB->get_record_sql($sql);
			$orgname = $userorganization->name;
			
			if($format=='pdf'  || $format=='html'){
				if($i%2==0)
					$style = 'background-color: #ece9e9;';
				else
					$style ='';								  			
				$tablepdf  .= '<tr style="'.$style.'">
								<td >'.$user->firstname.' </td>
								<td >'.$user->lastname.'</td>
								<td >'.$user->username.' </td>
								<td >'.$user->address.' </td>
								<td >'.$user->city.' </td>
								<td >'.$showcountry.' </td>
								<td colspan="2" >'.$user->email.' </td>
								<td >'.$user->phone2.' </td>
								<td >'.$user->fullname.' </td>
								<td >'.date('m/d/Y',$user->timecompleted).'</td>
								<td >'.$user->cidnumber.' </td>
								<td >'.$orgname.' </td>
								<td >'.date('m/d/Y',$lastupdated->timecreated).'</td>
				</tr>';
	   		$i++;

			}
			if($format =='csv' || $format =='xlsx'){
				$csvdata .= '"'.$user->firstname.
							'","'.$user->lastname.
							'","'.$user->username.
							'","'.$user->address.
							'","'.$user->city.
							'","'.$showcountry.
							'","'.$user->email.
							'","'.$user->phone2.
							'","'.$user->fullname.
							'","'.date('m/d/Y',$user->timecompleted).
							'","'.$user->cidnumber.
							'","'.$orgname.
							'","'.date('m/d/Y',$lastupdated->timecreated). "\"\n";
			}
				
		}
		if($format=='pdf'  || $format=='html'){
			$tablepdf .= '	</tbody>
						</table>
			';
		
		}
		if($format=='html'){
		
			$fileLocation = '';
			$filename = '';
		}	
                if($format=='pdf' ){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('coursecompletion', true) . '.pdf';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
                        base::create_tempdf($reporthead,$reportdaterange.'<br>'.$reportdate,$tablepdf,$filename);
                }
                if($format =='csv' || $format =='xlsx'){
                        $fileLocation = $CFG->tempdir;
                        //$filename = 'learnerreport.csv';//.$format;
                        while (true) {
                                $filename = uniqid('coursecompletion', true) . '.csv';
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
                    $filename = "coursecompletion_". date('Y-m-d-H-i-s') . '.xlsx';
                    $objWriter->save($CFG->tempdir.'/'.$filename);
                }
	}
		
    $emailarray=explode(";",$email);
    
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $tablepdf;
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
