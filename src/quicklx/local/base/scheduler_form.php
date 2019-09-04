<?php
require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot . '/local/base/locallib.php');

require_login($SITE);
//echo 'hi';
if(isset($_POST['contactFrmSubmit']) 
			&& !empty($_POST['emailsubject'])
			&& !empty($_POST['format'])
			&& !empty($_POST['emailusers']) 
			&& !empty($_POST['emailbody'])){
				
    $record=new stdclass();
    $sincestartrange =0;
    
	foreach($_POST as $key => $value)
	{
				//echo 'key : '.$key.' = value '.$value.'<br>';	

		if($key=='starttime' || $key == 'startdate' || $key == 'enddate' ){
			 if (isset($value)){
				 if($key=='startdate'){
					 $nextrunstartdate=$value;
					 $value = strtotime($value);
				 }
				else if($key=='enddate'){
					  $value = strtotime($value);
				 }	 			 	
			}
			else
				$value=0;
			
			
		}
		else{
			if($key=='selectuser'){
				  $selectuser = $value;
			 }
			else if($key=='selectcourse'){
				  $selectcourse = $value;
			 }
		
		}
		$record->$key=$value;
		$$key=$value;
		
		if($key=='startrange'){
			if($value == 'Sincerecent'){
				$sincestartrange =1;
			}
			else
				 $startrange =base::get_date($value);
		 }
		else if($key=='endrange'){
			  $endrange = base::get_date($value);
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

	if(isset($selectuser) && ( $screen==2 || $screen==3) ){
		$record->user=$selectuser;
	}
	if(isset($selectcourse) && $screen==3){
		$record->course=$selectcourse;
	}
	$record->nextrun = 0;
	  $nextrun = $nextrunstartdate.' '.$starttime;
	$nextrun  = base::servertimezonenextrun($nextrun);
	$nextruntime = date("H:i",$nextrun);
	$time = explode(':', $nextruntime);
	$record->starttime =($time[0]*3600) + ($time[1]*60);
	if($schedule == 'Once'){
		 if( $nextrun < time()) {
			 $nextrun = 0;
		 }
		 $record->nextrun = $nextrun; 

	}
	else if($schedule == 'Daily'){
		 if( $nextrun < time()) {
			 $nextrun = $nextrun+ 24*60*60;
		 }
		 $record->nextrun = $nextrun; 
	 }
	else if($schedule == 'Weekly'){
		$daynamearray=explode(',',$opt2);
		$nextrunstartdate = $nextrun;
		for($i=0; $i < 7; $i++){
			$daytime = $nextrunstartdate+($i*24*60*60);				
			 $dayname=  date('l',$daytime);
			 $daydate = date("Y-m-d",$daytime);
			if(in_array($dayname,$daynamearray)){
				 $nextrun = $daydate.' '.$nextruntime;
			 	$nextrun  = base::servertimezonenextrun($nextrun);
				 if( $nextrunstartdate <= $daytime) {
					$record->nextrun = $nextrun;
					break;
				}
			 }
		 } 
		 
	 }
	 else if($schedule == 'Monthly'){
		$monthnamearray=explode(',',$opt4);
		$nextrunstartdate =$nextrun;
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
			 	$nextrun  = base::servertimezonenextrun($nextrun);
				 if( $nextrunstartdate < $daytime) {
						 $record->nextrun = $nextrun;
						 break;
				 }
			 }
		 } 
		 
		 
		 
	 }
	 

	$record->reportdelivery = 'attach';
	$record->userid = $USER->id;
	$record->timecreated = time();
	$record->timemodified = time();
	if(!isset($enddate))
		$record->enddate = 0;

	$record->configid=$DB->insert_record('schedule_report_config',$record);
	if($record->configid){
		if($DB->insert_record('schedule_report_filter',$record))
			$status='ok';
	}
	else
		$status='err';
    
    // Output status
    echo $status;die;
}
