<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');

require_login($SITE);

if(isset($_POST['contactFrmSubmit']) 
			&& !empty($_POST['emailsubject'])
			&& !empty($_POST['format'])
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
			
    $departmentid = $_POST['departmentid'];   
    $email  = $_POST['toemail'];
    $emailsubject  = $_POST['emailsubject'];
    $emailbody  = $_POST['emailbody'];
    $format  = $_POST['format'];
    //$attach  = $_POST['attach'];
    $userarrayid  = $_POST['userarrayid'];
    $userarrayids =explode(", ",$userarrayid);
	$userdataobj= $userarrayids ;
	
	if($userdataobj){
		
		if(isset($params['daterange']) && $params['daterange'] =='no' ){
			unset($params['datefrom']); 
			unset($params['dateto']); 	 
		}
		$reporthead = 'Course Progress Report';
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

			$tablepdf ='<table>
							<thead >
								<tr  style="background-color: rgb(144, 140, 141);">
									<th  colspan="2">'.get_string('coursename', 'local_base').'</th>
									<th colspan="2">'.get_string('courseprogress', 'local_base').'</th>
									<th  >'.get_string('dateregistered', 'local_base').'</th>
									<th >'.get_string('datecompleted', 'local_base').'</th>
									<th >'.get_string('duedate', 'local_base').'</th>
									<th >'.get_string('subgroup', 'local_base').'</th>

								</tr>
							</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata  .='"'.$reportdaterange."\"\n"	;
			$csvdata  .='"'.$reportdate."\"\n"	;
			$csvdata  .= '"'.get_string('coursename', 'local_base').'","'
						.get_string('courseprogress', 'local_base').'","'
						.get_string('dateregistered', 'local_base').'","'
						.get_string('datecompleted', 'local_base').'","'
						.get_string('duedate', 'local_base').'","'
						.get_string('subgroup', 'local_base')."\"\n"	;
		}
//        	$returnobj = base::get_all_user($departmentid, 0, $params);
  //      	$exportuserdataobj= $returnobj->users ;		
		foreach($userdataobj as $user){
			$userid=$user;
			$user=$DB->get_record('user',array('id'=>$userid));
			$fullname = $user->lastname.', '.$user->firstname.' ('.$user->username.') ';
/*			$sql = "select name,cu.departmentid from {company} c 
		join  {company_users} cu on c.id=cu.companyid 
		where cu.userid=".$userid;
		if(isset($params['subgroup']) && $params['subgroup'] != "0" ){
				$sql .=" AND cu.departmentid in (".$params['subgroup'].")";
		}

		$userorganization = $DB->get_record_sql($sql);
		$orgname = $userorganization->name;
		$subgroupname = base::get_subgroupname($userorganization->departmentid);
*/
$sql = "select distinct(c.id),name from {company} c 
						join  {company_users} cu on c.id=cu.companyid 
						where cu.userid=".$userid;
				if(isset($params['subgroup']) && $params['subgroup'] != "0" ){
							$sql .=" AND cu.departmentid in (".$params['subgroup'].")";
				}
				$userorganizations = $DB->get_records_sql($sql);
				if($userorganizations){
					$orgname = "";
					foreach($userorganizations as $userorganization){
						$orgname .= $userorganization->name.',';
					}
					$orgname = trim($orgname,',');
					 $sql = "select distinct(d.id),name from {department} d 
								join  {company_users} cu on d.id=cu.departmentid 
								where cu.userid=".$userid." and d.parent <> 0";
					if(isset($params['subgroup']) && $params['subgroup'] != "0" ){
								$sql .=" AND cu.departmentid in (".$params['subgroup'].")";
					}
					$usersubgroups = $DB->get_records_sql($sql);
					$subgroupname = "";
					if($usersubgroups){
						foreach($usersubgroups as $usersubgroup){
							$subgroupname .= $usersubgroup->name.',';
						}
						$subgroupname = trim($subgroupname,',');
					}
}
			if($format=='pdf'  || $format=='html'){
				$tablepdf  .= '<tr class="" style="background-color: rgb(203, 205, 208);">
							<td colspan="2"><b>'.$fullname.' </b></td>
							<td colspan="2">'.$user->email.'</td>
							<td>'.$orgname.'</td>
							<td ></td>
							<td ></td>
							<td >'.$subgroupname.'</td>
							</tr>';

			}
			if($format =='csv'|| $format =='xlsx'){
				$csvdata .= '"'.$fullname.
									'","'.$user->email.
									'","'.$orgname.
									'","'.' '.
									'","'.' '.
									'","'.$subgroupname. "\"\n";
			}
			 		$coursedataobj = base::get_user_course($userid,$params);

		 						$i=1; 
		foreach($coursedataobj as $coursedata)	{
			
			$duedate ='';
			if($coursedata->due > 0)
				$duedate =date('m/d/Y',$coursedata->due);
				
			$completedate = '';
			$course_completions =$DB->get_record('course_completions',array('userid'=>$userid,'course'=>$coursedata->id));
			if($course_completions){
				if(isset($course_completions->timecompleted))
					$completedate	= date('m/d/Y',$course_completions->timecompleted);
			}
			
			// $coursemodules = $DB->count_records('course_modules',array('course'=>$coursedata->id));
			// Get criteria for course
			$completion = new completion_info($coursedata);
			$progress ='';
			if (!$completion->has_criteria()) {
				$progress = 'Not Started';
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
					$progress = 'Nil';
				else{
					 $completedcoursemodules = $DB->count_records_sql("select count(cm.id) 
											from {course_modules_completion} cmc 
											join {course_modules} cm on cm.id=cmc.coursemoduleid
											where cmc.completionstate=1 and
											 cm.course =:course and cmc.userid=$userid",
											 array('course'=>$coursedata->id));
					$progress = round((($completedcoursemodules /$coursemodules)*100),2).'%'; 
					
				}
			}
			if($format=='pdf' || $format=='html'){
					if($i%2==0)
			$style = 'background-color: #ece9e9;';
		else
			$style ='';								  			
		$tablepdf  .= '<tr style="'.$style.'">
								<td colspan="2">'.$coursedata->fullname.' </td>
								<td colspan="2">'.$progress.'</td>
								<td>'.date('m/d/Y',$coursedata->registered).'</td>
								<td >'.$completedate.'</td>
								<td >'.$duedate.'</td>
								</tr>';
									   		$i++;

											  
		   }
		   	if($format =='csv' || $format =='xlsx'){
				$csvdata .= '"'.$coursedata->fullname.
								'","'.$progress.
								'","'.date('m/d/Y',$coursedata->registered).
								'","'.$completedate.
								'","'.$duedate. "\"\n";
			} 
			 
		 }
			
		}
		if($format=='pdf'  || $format=='html'){
			$tablepdf .= '	</tbody>
						</table>
			';
			$fileLocation = '';
			$filename = '';
			
		}	
                if($format=='pdf' ){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('courseprogress', true) . '.pdf';
                                if (!file_exists($fileLocation .'/'. $filename)) break;
                        }
                        base::create_tempdf($reporthead,$reportdaterange.'<br>'.$reportdate,$tablepdf,$filename);
                }
                if($format =='csv' || $format =='xlsx'){
                        $fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('courseprogress', true) . '.csv';
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
                    $filename = "courseprogress_". date('Y-m-d-H-i-s') . '.xlsx';
                    $objWriter->save($fileLocation.'/'.$filename);
                }
	}
		
    $emailarray=explode(";",$email);
    
   
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   = $reportdaterange.'<br>'.$reportdate.$tablepdf;
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
