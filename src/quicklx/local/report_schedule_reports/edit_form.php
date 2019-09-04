<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/excellib.class.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->dirroot.'/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_login($SITE);

// Params.
$departmentid = optional_param('departmentid', 0, PARAM_INTEGER);
$configid = optional_param('configid', 0, PARAM_INT);

$context = context_system::instance();
iomad::require_capability('local/report_schedule_reports:view', $context);

// Url stuff.
$url = new moodle_url('/local/report_schedule_reports/edit_form.php');

// Page stuff:.
$strcompletion = get_string('pluginname', 'local_report_schedule_reports');
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($strcompletion);
$PAGE->requires->css("/local/base/css/styles.css");
$PAGE->requires->jquery();
$PAGE->requires->js('/local/base/js/custom.js');
// get output renderer                                                                                                                                                                                         
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Set the page heading.
$PAGE->set_heading(get_string('pluginname', 'block_iomad_reports') . " - $strcompletion");

// Get the renderer.
$output = $PAGE->get_renderer('block_iomad_company_admin');
// Set the companyid
$companyid = iomad::get_my_companyid($context);

// Work out department level.
$company = new company($companyid);
$parentlevel = company::get_company_parentnode($company->id);
$companydepartment = $parentlevel->id;

if (iomad::has_capability('block/iomad_company_admin:edit_all_departments', context_system::instance()) ||
    !empty($SESSION->currenteditingcompany)) {
    $userhierarchylevel = $parentlevel->id;
} else {
    $userlevel = $company->get_userlevel($USER);
    $userhierarchylevel = $userlevel->id;
}
if ($departmentid == 0 ) {
    $departmentid = $userhierarchylevel;
}
// Set the url.
company_admin_fix_breadcrumb($PAGE, $strcompletion, $url);

//echo $output->header();

// Only print the header if we are not downloading.
// Check the department is valid.
		$errormessage = '';

if(!empty($_POST)){
	if($_POST['submit'] == "Cancel")
		redirect('index.php');
	else{
		//print_r($_POST);
			//echo '<br>';	
		$record=new stdclass();
		$sincestartrange =0;
		foreach($_POST as $key => $value){
			if(is_array($value))
				$value = implode(',',$value);
			$$key=$value;
			$record->$key=$value;
			//echo 'key : '.$key.' = value '.$value.'<br>';	

		}
		$startrangearr = explode('-',$startrange);
		 $startrangeorder = $startrangearr[0];
		
		$endrangearr = explode('-',$endrange);
		$endrangeorder = $endrangearr[0];
		
		//$opt1=0;
		//$opt2=0;
		//$opt3=0;
		//$opt4=0;
		foreach($_POST as $key => $value){
			if($key=='starttime' || $key == 'startdate' || $key == 'enddate' ){
				 if (isset($value)){
					 if($key=='starttime'){
						 $nextruntime=$value;
						$time = explode(':', $value);
						//$value =($time[0]*3600) + ($time[1]*60);
						$value =0;
						if(isset($time[0]) && is_numeric($time[0]))
							$value =$value  + ($time[0]*3600);
						if(isset($time[1]) && is_numeric($time[1]))
							$value =$value  + ($time[1]*60);
						if(isset($time[2]) && is_numeric($time[2]))
							$value =$value  + ($time[2]*60);
					 }
					 else if($key=='startdate'){
						 $nextrunstartdate=$value;
						 $value = strtotime($value);
					 }
					else if($key=='enddate'){
						  $value = strtotime($value);
					 }	 			 	
				}
				else
					$value=0;
					
				$record->$key=$value;
			}
			else if($key=='schedule'){
				if($value == 'Once'){
					$opt1 = $record->opt1=0;
				}
				else if($value == 'Daily'){
					$opt1 = $record->opt1=$dayopt1;
				}
				else if($value == 'Weekly'){
					$opt1 = $record->opt1=$weekopt1;
					$opt2 = $record->opt2=$weekopt2;
				}
				else if($value == 'Monthly'){
					if($monthopt1>0)
						$opt1 = $record->opt1=$monthopt1;
					else{
						$opt1 = $record->opt1=0;
						$opt2 = $record->opt2=$day;
						$opt3 = $record->opt3=$weeks;
						$opt4 = $record->opt4=$month;
					}
				}
				
			}
			else if($key=='startrange'){
				$array = explode('-',$value);
				$value = $array[1];
				if($value == 'Sincerecent'){
					$sincestartrange =1;
				}
				else
					 $startrange =base::get_date($value);
				$record->startrange=$value;	
			 }
			else if($key=='endrange'){
				$array = explode('-',$value);
				$value = $array[1];
				$endrange = base::get_date($value);
				$record->endrange=$value;	
			 }
			
		}
		if($sincestartrange == 1){
			$sql = "select * from {schedule_report_config} 
						where reportname='$reportname' AND screen=$screen ORDER BY timemodified DESC Limit 0,1";
			$lasttrack = $DB->get_record_sql($sql);
			if($lasttrack){
				$startrange = $lasttrack->timemodified;//lastrun;
			}
			else
				$startrange = time();
		}
		$record->datefrom=$startrange;	
		$record->dateto=$endrange;
			
		if($schedule == 'Once'){
			 $nextrun = $nextrunstartdate.' '.$nextruntime;
			 $nextrun = strtotime($nextrun);
			 if( $nextrun < time()) {
				 $nextrun = 0;
			 }
			 $record->nextrun = $nextrun; 

		}
		else if($schedule == 'Daily'){
			 $nextrun = $nextrunstartdate.' '.$nextruntime;
			 $nextrun = strtotime($nextrun);
			 if( $nextrun < time()) {
				 $nextrun = $nextrun+ 24*60*60;
			 }
			 $record->nextrun = $nextrun; 
		 }
		else if($schedule == 'Weekly'){
			$daynamearray=explode(',',$opt2);
			 $nextrunstartdate = $nextrunstartdate.' '.$nextruntime;
			$nextrunstartdate = strtotime($nextrunstartdate);
			for($i=0; $i < 7; $i++){
				$daytime = $nextrunstartdate+($i*24*60*60);				
				 $dayname=  date('l',$daytime);
				 $daydate = date("Y-m-d",$daytime);
				if(in_array($dayname,$daynamearray)){
					 $nextrun = $daydate.' '.$nextruntime;
					 $nextrun = strtotime($nextrun);
					 if( $nextrunstartdate <= $daytime) {
						$record->nextrun = $nextrun;
						break;
					}
				 }
			 } 
			 
		 }
		 else if($schedule == 'Monthly'){
			$monthnamearray=explode(',',$opt4);
			$nextrunstartdate = $nextrunstartdate.' '.$nextruntime;
			$nextrunstartdate = strtotime($nextrunstartdate);
			for($i=0; $i < 12; $i++){
				$daytime = strtotime ("+".$i." month",$nextrunstartdate);
				 $monthname=  date('F',$daytime);		
				if(in_array($monthname,$monthnamearray)){
					if($opt1 > 0){
						$daydate = date("Y-m-d",$daytime);
						$month = new DateTime($daydate);
						$opt= $opt1-1 ;//since we add with first day
						$opt1thday  = mktime(0, 0, 0, $month->format('m'), 1, $month->format('Y'))+($opt*24*60*60);
						 $nextrun = $opt1thday;
					 }
					 else{
						 $date = date('Y-m',$daytime);
						$dayofweek = strtotime($opt2." ".$opt3." ".$date);
						 $nextrun = $dayofweek;

					 }
					 $nextrun = date("Y-m-d",$nextrun);
					 $nextrun = $nextrun.' '.$nextruntime;
					 $nextrun = strtotime($nextrun);
					 if( $nextrunstartdate < $daytime) {
							 $record->nextrun = $nextrun;
							 break;
					 }
				 }
			 } 
			 
			 
			 
		 }
		 	$record->nextrun = base::usertimezonenextrun($record->nextrun,$USER->id);

		$record->reportdelivery = 'attach';
		$record->timecreated = time();
		$record->timemodified = time();
		if(!isset($enddate))
			$record->enddate = 0;
		if(!isset($enddatecheck))
			$record->enddate = 0;
		//print_r($record);
		
		$schedule_report=$DB->get_record('schedule_report_config',array('id'=>$configid));

		$today = time();
		$today = strtotime("midnight",$today);
		$start = strtotime($startdate);
		$end = strtotime($enddate);
	
		if($emailusers == '' ){
			$errormessage = "Please enter email.";
		}
		else {
				$arr = explode(';',$emailusers);
				for($i=0; $i < count($arr); $i++){	
					$tempemail = $arr[$i];	
					if($tempemail == ''){ 
						$errormessage = "Please check email address.";
						break;
					}
					else if(!filter_var($tempemail, FILTER_VALIDATE_EMAIL)) {
						$errormessage = "Please check email format.";
						break;
					}

			}
		}
		if($emailsubject == '' ){
				$errormessage = "Please enter Email Subject.";

		}
		else if($emailbody == '' ){
				$errormessage = "Please enter Email Body";

		}
		else if($start < $today && $schedule_report->startdate != $start){
				$errormessage = "Start date should be greater than today";

		}
		else if(isset($enddatecheck) && $enddatecheck == 'on' && $end <= $start){
				$errormessage = "End date should be greater than start date.";
		}
		else if($startrangeorder > 0 && $endrangeorder > $startrangeorder){
				$errormessage = "End range should be greater than start range.";
		}
		else if(!$starttime){
				$errormessage = "Please check Start time (00:00:00).";
		}
		if($errormessage == ''){
		  		$filter=$DB->get_record('schedule_report_filter',array('configid'=>$configid));

			 if($_POST['submit'] == "Update"){
				$record->id=$configid;
				$DB->update_record('schedule_report_config',$record);

				$record->id=$filter->id;
				$DB->update_record('schedule_report_filter',$record);

			}
			else if($_POST['submit'] == "New"){
				$record->userid = $USER->id;
				$record->configid=$DB->insert_record('schedule_report_config',$record);
				//print_r($record);
				if($record->configid){
					$filter->configid=$record->configid;
					$DB->insert_record('schedule_report_filter',$filter);
				}

			}
			redirect('index.php');
					
		}

	}
	
	
	
}
echo $output->header();
echo  '<span style="color:red;"><b>'.$errormessage.'</b></span>';

$params['download'] = 'download';
$returnobj = base::get_all_user($departmentid, 0,$params);
$userdataobj= $returnobj->users ;
if($userdataobj ){
	$userarray=array();
	$scheduleuserarray=array();
	$userarrayid=array();
	foreach($userdataobj as $user){
		$userid=$user->id;
		$sql = "select * from {user} where id =$userid";
		$user = $DB->get_record_sql($sql);
		$fullname =$user->username.' ('.$user->firstname.' '.$user->lastname.') ';

		$userarray[$user->id]=$fullname;
		$scheduleuserarray[$user->email]=$fullname;
		$userarrayid[]=$user->id;
	}
					 $userarrayid = implode(', ',$userarrayid);



if($USER->timezone == 99 || $USER->timezone == 'UTC')
	$timeformat = '';
else
	$timeformat = 'UTC ';

 $timezone = $timeformat.base::usertimezone($USER->id);


$schedule = $DB->get_record('schedule_report_config',array('id'=>$configid)); 
$currenttime = date("H:i",$schedule->starttime);
$format = $schedule->format; 
$startrange = $schedule->startrange; 
$endrange = $schedule->endrange; 
$frequency = $schedule->schedule; 
$opt2 = explode(',',$schedule->opt2); 
$opt4 = explode(',',$schedule->opt4); 
if($schedule->opt2 != '0')
	$target = '#opt311';
else
	$target = '#opt31';
	
echo '
 
	<form role="form" method="post"> ';
		foreach($params as $key=>$value ){
			echo '<input type="hidden" name="'.$key.'" value="'. $value. '">';
		}
		
		echo' <input type="hidden" name="departmentid" value="'. $departmentid. '">
		<input type="hidden" name="configid" value="'.$configid.'">
		<input type="hidden" name="reportname" value="'.$schedule->reportname.'">
		<input type="hidden" name="screen" value="'.$schedule->screen.'">
		<input type="hidden" name="contactFrmSubmit" value="1">
		<div class="form-group">
			<label for="description">Schedule Description:</label>
			<input type="text" class="form-control"  id="description" name="description" 
			placeholder="Enter Description" value="'.$schedule->description.'"/>
		</div>
		<div class="form-group">
			<label >Report Format:</label><br>
				<input type="radio" id="inputformat11" name="format" value="pdf" ';echo ($format =='pdf')? 'checked':'' ; echo '>
			<label for="inputformat11">PDF</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="inputformat12" name="format" value="csv" ';echo  ($format =='csv')? 'checked':'' ; echo' >
				<label for="inputformat12">CSV</label>
			&nbsp;&nbsp;&nbsp;
			<input type="radio" id="inputformat13" name="format" value="html" ';echo  ($format =='html')? 'checked':'' ; echo'>
				<label for="inputformat13">HTML</label>
		</div>
		<div class="form-group">
			<label for="emailusers">Send To Email Addresses:</label>
			<textarea class="form-control"  id="emailusers" name="emailusers"  
			placeholder="Separate email address with a semicolon (;)" >'.$schedule->emailusers.'</textarea>
		</div>
		<div class="form-group">
				<label for="scheduleinputuser">Please select users to email: (username/First/last)</label>
				<select class="form-control" id="scheduleinputuser" name="scheduleinputuser" size="10">
				';
				foreach($scheduleuserarray as $key=>$value){
					echo '<option value="'.$key.'">'.$value.'</option>';
					}
		   echo    ' </select>
		</div>
		 <div class="form-group">
				<label for="scheduleEmailSubject">Email Subject:</label>
				<input type="text" class="form-control" id="scheduleEmailSubject" name="emailsubject" 
				placeholder="Enter email subject" value="'.$schedule->emailsubject.'"/>
		</div>
		<div class="form-group">
				<label for="scheduleEmailBody">Email Body:</label>
				<textarea class="form-control" id="scheduleEmailBody" name="emailbody" 
				placeholder="Enter email body" >'.$schedule->emailbody.'</textarea>
		</div>
		<!--  <div class="form-group">
				<label >Report Delivery:</label><br>
				  <input type="radio" id="inputdelivery11" name="attach" value="link" >
					<label for="inputdelivery11">Link</label> 

					<input type="radio" id="inputdelivery12" name="attach" value="attach" checked>
					<label for="inputdelivery12">Attachment</label>
		</div> -->
		 <div class="form-group">
			 <label for="startrange">Start Range:</label>
				<select  id="startrange" name="startrange" >';
				$daterange = base::dateRange();
				if($startrange == 'Sincerecent')
					echo '<option value="0-Sincerecent" selected >Since Most Recent Report Sent</option>';
				else
					echo '<option value="0-Sincerecent">Since Most Recent Report Sent</option>';
					foreach($daterange as $key=>$value){
						$startrangeopt = explode('-',$key);
						if($startrangeopt[1] == $startrange)
							echo '<option value="'.$key.'" selected>'.$value.'</option>';
						else
							echo '<option value="'.$key.'">'.$value.'</option>';
					}					echo '</select>
		</div> 
		<div class="form-group">
			 <label for="endrange">End Range:</label>
				<select  id="endrange" name="endrange" >';
					foreach($daterange as $key=>$value){
						$endrangeopt = explode('-',$key);
						if($endrangeopt[1] == $endrange)
							echo '<option value="'.$key.'" selected>'.$value.'</option>';
						else
							echo '<option value="'.$key.'">'.$value.'</option>';
					}					echo '</select>
		</div>   
		<div class="form-group">
			 <label>Report Schedule:</label><br>
			 <label for="schedule">Frequency:</label>
				<select class="schedule-control" id="schedule" name="schedule" >
				   <option value="Once"  ';echo ($frequency =='Once')? 'selected':'' ; echo ' >Once</option>
				   <option value="Daily" ';echo ($frequency =='Daily')? 'selected':'' ; echo ' data-target="#opt11,#enddatecheck">Daily</option>
				   <option value="Weekly" ';echo ($frequency =='Weekly')? 'selected':'' ; echo ' data-target="#opt21,#opt22,#enddatecheck" >Weekly</option>
				   <option value="Monthly" ';echo ($frequency =='Monthly')? 'selected':'' ; echo ' data-target="'.$target.',#opt32,#enddatecheck" >Monthly</option>
			   </select>
		</div>
			
		<div class="schedule-elements">
			<div class="form-group">
					<label for="opt11">Every</label>
					<input type="text" name="dayopt1" id="opt11" value="'.$schedule->opt1.'"> day(s)                      
			</div>
			<div class="form-group">
					<label for="opt21">Every</label>
					<input type="text" name="weekopt1" id="opt21" value="'.$schedule->opt1.'"> week(s)                      
			</div>
				
			<div class="form-group" >
						<label id="opt22"></label>';
			
				$dayarray=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
					foreach($dayarray as $value){
						if(in_array($value,$opt2))
							echo '<label> <input type="checkbox" name="weekopt2[]" value="'.$value.'" checked/>'.$value.'</label>	';
						else
							echo '<label> <input type="checkbox" name="weekopt2[]" value="'.$value.'" />'.$value.'</label>	';
						}
				
			 echo ' </div>
			<div class="form-group" >
				   <label id="opt31"></label>
					  <input type="radio" name="opt31" value="day1" id="opt31first"  > Day 
					  <input type="text" name="monthopt1" value="'.$schedule->opt1.'">
					  of the month(s)<br>
					  <input type="radio" name="opt31" id="opt31second" value="month1"> The nth weekday of the month(s)<br>
			</div>
				
			<div class="form-group" >
				   <label id="opt311"></label>
					  <input type="radio" name="opt31" value="day2" id="opt311first"> Day n of the month(s)<br>
					  <input type="radio" name="opt31" value="month2" id="opt311second" >The
					  
					  <select  id="day" name="day" >
				   <option value="first"  ';echo ($opt2[0] =='first')? 'selected':'' ; echo ' >first</option>
				   <option value="second" ';echo ($opt2[0] =='second')? 'selected':'' ; echo ' >second</option>
				   <option value="third" ';echo ($opt2[0] =='third')? 'selected':'' ; echo ' >third</option>
				   <option value="fourth" ';echo ($opt2[0] =='fourth')? 'selected':'' ; echo '>fourth</option>
				   <option value="last" ';echo ($opt2[0] =='last')? 'selected':'' ; echo '>last</option>
				  
			   </select>
			   <select name="weeks" id="weeks">';
			   foreach($dayarray as $value){
				   if($schedule->opt3 == $value)
					echo '<option value="'.$value.'" selected>'.$value.'</option>';
				else
					echo '<option value="'.$value.'">'.$value.'</option>';
					}
				  
			 echo'  </select>
					  
						  of the month(s)<br>
			</div>
				
			<div class="form-group" >
					 <label id="opt32"></label>';
			
				$months=array('January','February','March','April','May','June','July','August','September','October','November','December');
					foreach($months as $value){
						if(in_array($value,$opt4))
							echo '<label> <input name="month[]" type="checkbox" value="'.$value.'" checked/>'.$value.'</label>';
						else
							echo '<label> <input name="month[]" type="checkbox" value="'.$value.'" />'.$value.'</label>';
						}
				
				 echo ' 
			 </div>
				 
		 </div>
			 
		 <div class="form-group">
				 <label for="starttime">Start Time	*</label>
				  <input type="time" name="starttime" id="starttime" value="'.$currenttime.'">
			 
		</div>
		<div class="form-group">
				<label for="startdate">Start Date</label>
				<input type="date" name="startdate" id="startdate" value="'.date('Y-m-d',$schedule->startdate).'">
		</div>
		<div class="end-date">
			 <div class="form-group" >
					<label>
					 <input type="checkbox"  id="enddatecheck" name="enddatecheck" ';echo ($schedule->enddate > 0 )? 'checked':'' ; echo '/>
					 End Date </label>
			</div>
				 
			 <div class="form-group">
				 <label for="enddate">	</label>
				  <input type="date" name="enddate" id="enddate" value="';echo ($schedule->enddate > 0 )? date('Y-m-d',$schedule->enddate):date('Y-m-d',time()) ; echo '">
			 
			</div>
			<label for="schedule">*Scheduled times are based on your current time zone setting: ('.$timezone.')</label>
	  </div>
	  <input type="submit" class="btn btn-primary" name="submit" id="update_schedule" value="Update">
	  <input type="submit" class="btn btn-primary" name="submit" id="new_schedule" value="New">
	  <input type="submit" class="btn btn-primary" name="submit" id="cancel_schedule" value="Cancel">
	
</form>
   ';
}
echo $output->footer();
