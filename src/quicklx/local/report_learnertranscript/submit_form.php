<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->libdir.'/completionlib.php');
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
    $userarrayid  = $_POST['userarrayid'];
    $userarrayids =explode(", ",$userarrayid);
	$userdataobj= $userarrayids ;
	
	if($userdataobj){
		
		if(isset($params['daterange']) && $params['daterange'] =='no' ){
			unset($params['datefrom']); 
			unset($params['dateto']); 	 
		}
		$reporthead = 'Learner Transcript Report';
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
								<tr style="background-color: rgb(203, 205, 208);">
									<th style="text-align:left;" >'.get_string('login', 'local_base').'</th>
									<th style="text-align:center;">'.get_string('firstname', 'local_base').'</th>
									<th  style="text-align:left;">'.get_string('lastname', 'local_base').'</th>
									<th colspan="2" style="text-align:left;">'.get_string('email', 'local_base').'</th>
									<th style="text-align:left;">'.get_string('organization', 'local_base').'</th>
								</tr>
							</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata  .='"'.$reportdaterange."\"\n"	;
			$csvdata  .='"'.$reportdate."\"\n"	;
			$csvdata  .= '"'.get_string('login', 'local_base').'","'
						.get_string('firstname', 'local_base').'","'
						.get_string('lastname', 'local_base').'","'
						.get_string('email', 'local_base').'","'
						.get_string('organization', 'local_base')."\"\n"	;
		}
								$i=1;

		/*foreach($userdataobj as $user){
			$userid=$user;
			$user=$DB->get_record('user',array('id'=>$userid));
			$sql = "select name from {company} c 
		join  {company_users} cu on c.id=cu.companyid 
		where cu.userid=".$userid;
		$userorganization = $DB->get_record_sql($sql);
		$orgname = $userorganization->name;
		*/
		
		$returnobj = base::get_all_user($departmentid, 0,$params);
        	$exportuserdataobj= $returnobj->users ;
        	foreach($exportuserdataobj as $user){
                	$userid=$user->id;
                	$user=$DB->get_record('user',array('id'=>$userid));
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
								<td style="text-align:left;" >'.$user->username.' </td>
								<td style="text-align:center;" >'.$user->firstname.'</td>
								<td style="text-align:left;">'.$user->lastname.' </td>
								<td colspan="2" style="text-align:left;">'.$user->email.' </td>
								<td style="text-align:left;">'.$orgname.'</td>
								</tr>';
									   		$i++;


			}
			if($format =='csv' || $format =='xlsx'){
				$csvdata .= '"'.$user->username.
									'","'.$user->firstname.
									'","'.$user->lastname.
									'","'.$user->email.
									'","'.$orgname. "\"\n";
			}
			 $coursesearch ='';
			if(!empty($params['course'])){
				$course = $params['course'];
				$coursesearch = " AND c.id in ($course) ";
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
 				$filename = uniqid('learnerreport', true) . '.pdf';
 				if (!file_exists($fileLocation .'/'. $filename)) break;
			}
			base::create_tempdf($reporthead,$reportdaterange.'<br>'.$reportdate,$tablepdf,$filename);	
		}
		if($format =='csv' || $format =='xlsx'){
			$fileLocation = $CFG->tempdir;
                        while (true) {
                                $filename = uniqid('learnerreport', true) . '.csv';
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
                    $filename = "learnerreport_". date('Y-m-d-H-i-s') . '.xlsx';
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
