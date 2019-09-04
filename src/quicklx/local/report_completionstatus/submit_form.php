<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');
require_once($CFG->dirroot.'/local/base/extend_excellib.php');
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
	$returnobj = base::get_all_user($departmentid, 0, $params);
	$userdataobj= $returnobj->users ;

if($userdataobj ){
	
	$userid='';
	foreach($userdataobj as $userobj){
		
		$userid .=$userobj->id.',';
	}
	$userid = trim($userid,',');
	
	$returnobj = report_completionstatus::get_all_users_courses($userid,$params);
	$exportcoursedataobj= $returnobj->users ;
	unset($params['download']); 
	
	if($exportcoursedataobj){
		$fileLocation = $CFG->tempdir; 
		$fileLocation = rtrim($fileLocation, '/') . '/';
		if(isset($params['daterange']) && $params['daterange'] =='no' ){
			unset($params['datefrom']); 
			unset($params['dateto']); 	 
		}
		$reporthead = 'Completion Status Report';
		if($params['daterange'] !='no'){
			$reportdaterange = 'Date Range: '. get_string($params['daterange'], 'local_base');
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

			  $tablepdf ='<table >
					<thead >
					<tr style="background-color: rgb(203, 205, 208);">
					<th  style="text-align:left;"  >'.get_string('firstname', 'local_base').'</th>
					<th   style="text-align:left;" >'.get_string('lastname', 'local_base').'</th>
					<th  style="text-align:left;" >'.get_string('username', 'local_base').'</th>
					<th  style="text-align:left;"  >'.get_string('organization', 'local_base').'</th>
					<th  style="text-align:left;"  >'.get_string('course', 'local_base').'</th>
					<th  style="text-align:left;" >'.get_string('status', 'local_report_completionstatus').'</th>
					<th  style="text-align:left;" >'.get_string('score', 'local_report_completionstatus').'</th>
					<th  style="text-align:left;"  >'.get_string('dateregistered', 'local_base').'</th>
					<th  style="text-align:left;"  >'.get_string('datecompleted', 'local_base').'</th>
					<th  style="text-align:left;"  >'.get_string('validuntil', 'local_report_completionstatus').'</th>
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
							.get_string('organization', 'local_base').'","'
							.get_string('course', 'local_base').'","'
							.get_string('status', 'local_report_completionstatus').'","'
							.get_string('score', 'local_report_completionstatus').'","'
							.get_string('dateregistered', 'local_base').'","'
							.get_string('datecompleted', 'local_base').'","'
							.get_string('validuntil', 'local_report_completionstatus')."\"\n"	;
		}
		$i=1;
		foreach($exportcoursedataobj as $coursedata){
		
			$userid=$coursedata->userid;
			$courseid=$coursedata->id;
			$user = $DB->get_record('user',array('id'=>$userid));

/*			$sql = "select name from {company} c 
					join  {company_users} cu on c.id=cu.companyid 
					where cu.userid=".$userid;
			$userorganization = $DB->get_record_sql($sql);
			$orgname = $userorganization->name;*/
			$sql = "select distinct(c.id),c.name from {company} c 
						join  {company_users} cu on c.id=cu.companyid 
						where cu.userid=".$userid;
			$userorganizations = $DB->get_records_sql($sql);
			$orgname = "";
			if($userorganizations){
				foreach($userorganizations as $userorganization){
					$orgname .= $userorganization->name.',';
				}
				$orgname = trim($orgname,',');
			}
			$sql = "SELECT * 
						FROM {grade_grades} gg
						JOIN {grade_items} gi ON (gi.id = gg.itemid  AND gi.itemtype = 'course')
					   WHERE gg.userid =$userid AND gi.courseid = $courseid"; 
			
			$score = '-';
			$course_score =$DB->get_record_sql($sql);
			if($course_score){
				if(isset($course_score->finalgrade))
						$score = round($course_score->finalgrade, 0)."%";
			}
			$sql = "SELECT ue.id as ueid,ue.timecreated as registered,ue.userid as userid,
								ue.timeend as due,c.* 
								FROM {user_enrolments} ue
								JOIN {user} u ON (u.id = ue.userid)
							   JOIN {enrol} e ON (e.id = ue.enrolid AND e.status = 0)
							   JOIN {course} c ON (e.courseid = c.id)
							   WHERE ue.userid =$userid AND c.id = $courseid"; 
			
			$registered = '-';
			$course_registered =$DB->get_record_sql($sql);
			if($course_registered){
				if(isset($course_registered->registered))
					$registered	= date('m/d/Y',$course_registered->registered);
			}
			
			$completedate = '-';
			$course_completions =$DB->get_record('course_completions',array('userid'=>$userid,'course'=>$coursedata->id));
			if($course_completions){
				if(isset($course_completions->timecompleted))
					$completedate	= date('m/d/Y',$course_completions->timecompleted);
			}
			if (!$iomadcourseinfo = $DB->get_record('iomad_courses', array('courseid' => $coursedata->id))) {
                        $iomadcourseinfo = new stdclass();
              }
			if (!empty($course_completions->timecompleted) && !empty($iomadcourseinfo->validlength)) {
				$validuntil = date('m/d/Y', $course_completions->timecompleted + ($iomadcourseinfo->validlength * 24 * 60 * 60) );
			} else {
				$validuntil = "-";
			}
			
			$completion = new completion_info($coursedata);
			$status ='';
			if (!$completion->has_criteria()) {
				$status = 'Not Started';
			}
			else{
				$modinfo = get_fast_modinfo($coursedata);
				$result = array();
				foreach ($modinfo->get_cms() as $cm) {
					if ($cm->completion != COMPLETION_TRACKING_NONE && !$cm->deletioninprogress) {
						$result[$cm->id] = $cm->id;
					}
				}
				 $coursemodules = count($result);
				
				
				if($coursemodules ==0)
					$status = 'Nil';
				else{
					 $completedcoursemodules = $DB->count_records_sql("select count(cm.id) 
											from {course_modules_completion} cmc 
											join {course_modules} cm on cm.id=cmc.coursemoduleid
											where cmc.completionstate=1 and
											 cm.course =:course and cmc.userid=$userid",
											 array('course'=>$coursedata->id));
					$status = round((($completedcoursemodules /$coursemodules)*100),2).'%'; 
					
				}
			}
			
			if($format=='pdf'  || $format=='html'){
				if($i%2==0)
					$style = 'background-color: #ece9e9;';
				else
					$style ='';								  			
				$tablepdf  .= '<tr style="'.$style.'">
							<td style="text-align:left;">'.$user->firstname.' </td>
							<td  style="text-align:left;">'.$user->lastname.'</td>
							<td  style="text-align:left;">'.$user->username.' </td>
							<td  style="text-align:left;">'.$orgname.' </td>
							<td  style="text-align:left;">'.$coursedata->fullname.' </td>
							<td  style="text-align:left;">'.$status.' </td>
							<td  style="text-align:left;">'.$score.' </td>
							<td  style="text-align:left;">'.$registered.'</td>
							<td  style="text-align:left;">'.$completedate.'</td>
							<td  style="text-align:left;">'.$validuntil.'</td>
							</tr>';
			$i++;

			}
			if($format =='csv' || $format =='xlsx'){
				$csvdata .= '"'.$user->firstname.
							'","'.$user->lastname.
							'","'.$user->username.
							'","'.$orgname.
							'","'.$coursedata->fullname.
							'","'.$status.
							'","'.$score.
							'","'.$registered.
							'","'.$completedate.
							'","'.$validuntil. "\"\n";
			}			
		}
		if($format=='pdf'  || $format=='html'){
			$tablepdf .= '	</tbody>
						</table>
			';
			$html = $reportdaterange.'<br>'.$reportdate;
		}
		if($format=='html'){
		
			$fileLocation = '';
			$filename = '';
		}	
                if($format=='pdf' ){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('completionstatus', true) . '.pdf';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
			base::create_tempdf($reporthead,$html,$tablepdf,$filename);
                }
                if($format =='csv' || $format =='xlsx'){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('completionstatus', true) . '.csv';
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
                    $filename = "completionstatus_". date('Y-m-d-H-i-s') . '.xlsx';
                    $objWriter->save($fileLocation.'/'.$filename);
                }
	}
}	
    $emailarray=explode(";",$email);
    
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $html.$tablepdf;
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
/*	if(!empty($users) ){
		 foreach($users as $user){	
			if(email_to_user($user, $sendfrom,$eventdata->subject,$eventdata->fullmessage ,$eventdata->fullmessagehtml, $fileLocation.'/'.$filename, $filename)){
				$status = 'ok';
			}else{
				$status = 'err';
			}
		 
		 }
	 }
  */  
    // Output status
    echo $status;die;
}
