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
	require_once($CFG->dirroot.'/local/report_login_activity/locallib.php');
	$returnobj = report_login_activity::get_users_login($departmentid, $params);
	if($userdataobj){
		$fileLocation = $CFG->tempdir; 
		$fileLocation = rtrim($fileLocation, '/') . '/';
		if(isset($params['daterange']) && $params['daterange'] =='no' ){
			unset($params['datefrom']); 
			unset($params['dateto']); 	 
		}
		$reporthead = 'Login Activity Report';
		
		$searchsessionfrom=" ";
	if($params['daterange'] !='no'){
		$reportdaterange = 'Date Range: '. get_string($params['daterange'], 'local_base');
		$reportdate = date('m/d/Y',$params['datefrom']).' to '.date('m/d/Y',$params['dateto']);
		$beginOfDay = strtotime("midnight", $params['datefrom']);
		$endOfDay   = strtotime("tomorrow", $params['dateto']) - 1;
		$searchsessionfrom=" AND timecreated > $beginOfDay AND  timecreated < $endOfDay";
				
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
									<th style="text-align:left;" >'.get_string('username', 'local_base').'</th>
									<th style="text-align:center;">'.get_string('firstname', 'local_base').'</th>
									<th  style="text-align:left;">'.get_string('lastname', 'local_base').'</th>
									<th style="text-align:left;">'.get_string('sessionstart', 'local_base').'</th>
									<th style="text-align:left;">'.get_string('sessionend', 'local_base').'</th>
									<th style="text-align:left;">'.get_string('timeconnect', 'local_base').'</th>
								</tr>
							</thead>
						<tbody> ';
		}
		if($format =='csv' || $format =='xlsx'){
			$csvdata   ='"'.$reporthead."\"\n"	;
			$csvdata  .='"'.$reportdaterange."\"\n"	;
			$csvdata  .='"'.$reportdate."\"\n"	;
			$csvdata  .= '"'.get_string('username', 'local_base').'","'
						.get_string('firstname', 'local_base').'","'
						.get_string('lastname', 'local_base').'","'
						.get_string('sessionstart', 'local_base').'","'
						.get_string('sessionend', 'local_base').'","'
						.get_string('timeconnect', 'local_base')."\"\n"	;
		}
			
		$isdata = 0;
		$i=1;
    	$returnobj = report_login_activity::get_users_login($departmentid, $params);
    	$exportuserdataobj=$userdataobj= $returnobj->users ;

		//Custom Code - Syllametrics - Updated (25 th march,2019)
		$hassessionend = true;
        foreach($exportuserdataobj as $usersession){
			$sessionend =0;	
			if($usersession->action == 'loggedin'){
				if($hassessionend){
					$sessionstart = $usersession->timecreated;
					$hassessionend = false;
					$prvusersession = $usersession;
				}
				else {
					$beginOfDay = strtotime("midnight", $prvusersession->timecreated);
					$endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
					$sql = "select * from {logstore_standard_log} 
						where  id > ".$prvusersession->id." AND userid = ".$prvusersession->userid;
					if($prvusersession->userid == $usersession->userid){
						//target !='user_login' there is a chance of failed log in , we couldn't count it as valid session.
						$sql .=" AND target !='user_login' AND id < ".$usersession->id;
					}
					$sql .=" AND timecreated <= $endOfDay ORDER BY timecreated DESC LIMIT 0,1" ;
					 
					$userlastsession = $DB->get_record_sql($sql);
				
					if($userlastsession)
						$sessionend = $userlastsession->timecreated;
					else
						$sessionend = $sessionstart +10;//if there is no session other than log in
						
					$hassessionend = true;
				
					if($sessionend > 0){
					
						$user=$DB->get_record('user',array('id'=>$prvusersession->userid));
						if($user){
							$time= $sessionend - $sessionstart;
							$str_time =  base::format_time($time);

							if($format=='pdf'  || $format=='html'){
								
								if($i%2==0)
									$style = 'background-color: #ece9e9;';
								else
									$style ='';		
																		
								$tablepdf  .= '<tr style="'.$style.'">
									<td style="text-align:left;">'.$user->username.' </td>
									<td style="text-align:center;" >'.$user->firstname.'</td>
									<td style="text-align:left;">'.$user->lastname.' </td>
									<td style="text-align:left;">'.date('m/d/Y h:i:s A',base::usertimezonenextrun($sessionstart)).'</td>
									<td style="text-align:left;" >'.date('m/d/Y h:i:s A' ,base::usertimezonenextrun($sessionend)).'</td>
									<td style="text-align:left;">'.$str_time.'</td>
									</tr>';
									
								$i++;
							}
							if($format =='csv' || $format =='xlsx'){
								$csvdata .= '"'.$user->username.
													'","'.$user->firstname.
													'","'.$user->lastname.
													'","'.date('m/d/Y h:i:s A',$sessionstart).
													'","'.date('m/d/Y h:i:s A' ,$sessionend).
													'","'.$str_time. "\"\n";
							}
						}	
					}
					if($hassessionend){
						$sessionstart = $usersession->timecreated;
						$hassessionend = false;
						$prvusersession = $usersession;
					}
					$sessionend =0;	
				}
			} else if($usersession->action == 'loggedout'){
				$sessionend = $usersession->timecreated;
				$hassessionend = true;
			}	
			if($sessionend > 0){
				$userid=$usersession->userid;
				$user=$DB->get_record('user',array('id'=>$userid));
//End Custom Changes - Syllametrics - March 25th, 2019
				$time= $sessionend - $sessionstart;
				$str_time =  base::format_time($time);

				if($format=='pdf'  || $format=='html'){
					if($i%2==0) 
						$style = 'background-color: #ece9e9;';
					else
						$style ='';								  			
					$tablepdf  .= '<tr style="'.$style.'">
						<td style="text-align:left;">'.$user->username.' </td>
						<td style="text-align:center;" >'.$user->firstname.'</td>
						<td style="text-align:left;">'.$user->lastname.' </td>
						<td style="text-align:left;">'.date('m/d/Y h:i:s A',base::usertimezonenextrun($sessionstart)).'</td>
						<td style="text-align:left;" >'.date('m/d/Y h:i:s A' ,base::usertimezonenextrun($sessionend)).'</td>
						<td style="text-align:left;">'.$str_time.'</td>
						</tr>';
					$i++;
				}
				if($format =='csv' || $format =='xlsx'){
					$csvdata .= '"'.$user->username.
										'","'.$user->firstname.
										'","'.$user->lastname.
										'","'.date('m/d/Y h:i:s A',base::usertimezonenextrun($sessionstart)).
										'","'.date('m/d/Y h:i:s A' ,base::usertimezonenextrun($sessionend)).
										'","'.$str_time. "\"\n";
				}
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
                        $filename = uniqid('loginactivity', true) . '.pdf';
                        if (!file_exists($fileLocation .'/'. $filename)) break;
                }
	base::create_tempdf($reporthead,$reportdaterange.'<br>'.$reportdate,$tablepdf,$filename);
        }
        if($format =='csv' || $format =='xlsx'){
                $fileLocation = $CFG->tempdir;
                while (true) {
                        $filename = uniqid('loginactivity', true) . '.csv';
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
            $filename = "loginactivity_". date('Y-m-d-H-i-s') . '.xlsx';
            $objWriter->save($fileLocation.'/'.$filename);
        }
	}
		
    $emailarray=explode(";",$email);
    
   
     // Send email
	$eventdata = new stdClass();
	$eventdata->subject           = $emailsubject;
	$eventdata->fullmessage   = $emailbody;
	if($format=='html')
		$eventdata->fullmessagehtml   =$reportdaterange.'<br>'.$reportdate.$tablepdf;
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
