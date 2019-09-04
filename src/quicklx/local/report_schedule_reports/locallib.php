<?php

/**
 *  Filter form used on the report .
 *
 */
 require_once($CFG->dirroot.'/local/base/locallib.php');
class report_schedule_reports {
	 public static function sceduleddescription($report){
		$discription = ' ' ;
			$time = $report->startdate+$report->starttime;
			$time= base::usertimezonenextrun($time);
			$starttime = date("h:i A",$time);
			$discription = 'At '.$starttime;
			if($report->schedule == 'Once')
					$discription .= ' on '.date("m/d/Y",$report->startdate);
			else if($report->schedule == 'Daily'){
				if($report->opt1 >1)
					$discription .= '<br> every '.$report->opt1.' days';
				else
					$discription .= ' <br> every '.$report->opt1.' day';
			}
			else if($report->schedule == 'Weekly'){
				$opt2 = explode(',',$report->opt2);
				$days ='';
				foreach($opt2  as $opt){
					$days .= substr($opt, 0, 3).',';
			}
			$days = rtrim($days,",");
				if($report->opt1 >1)
					$discription .= '<br> every '.$days.'<br> of every '.$report->opt1.' weeks';
				else
					$discription .= ' <br> every '.$days.'<br> of every '.$report->opt1.' week';
			}
			else if($report->schedule == 'Monthly'){
				$opt4 = explode(',',$report->opt4);
				$months ='';
				foreach($opt4  as $opt){
					$months .= substr($opt, 0, 3).',';
				}
				$months = rtrim($months,",");
			
				if($report->opt2 != '0')
					$discription .= '<br> on the '.$report->opt2.' '.$report->opt3.'<br> of every '.$months;
				else
					$discription .= '<br> on day '.$report->opt1.'<br> of every '.$months;

			}
			if($report->enddate > 0){
				$enddate = date("m/d/Y",$report->enddate);
				$discription .= ',<br> ending '.$enddate;
			}
			
			return $discription;
	 }
	 public static function scheduled_reports($departmentid){
		global $DB;
		$sql = "select c.* from {schedule_report_config} c 
						join {schedule_report_filter} f on c.id=f.configid
						where c.delete=0 and f.departmentid=".$departmentid;
						
		$reports = $DB->get_records_sql($sql);
		$active = array();
		$inactive = array();
		if($reports){
			foreach($reports as $report){
				if($report->pause == 1)
					$inactive[] = $report;
				else if($report->schedule == 'Once' && $report->nextrun == 0 && $report->pause == 1)
					$inactive[] = $report;
				else
					$active[] = $report;
			}
			$returnobj = new stdclass();
			$returnobj->active= $active;
			$returnobj->inactive= $inactive;

			return $returnobj;
		}
		return null;
		
		
	}
	  public static function reportpage($report){
		  $reportname = $report->reportname;
		  $screen = $report->screen;
		  if($reportname == "learnertranscript"){
				$reportpage = 'report_card';
		  }
		  else if($reportname == "reportcard"){
			  if($screen == 2)
			  	$reportpage = 'learner_card';
			  else
			  	$reportpage = 'report_card';
		  }
		  
		  return $reportpage;
	  }
}
