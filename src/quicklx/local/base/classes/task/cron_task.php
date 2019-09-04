<?php

namespace local_base\task;

class cron_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('crontask', 'local_base');
    }

    /**
     * Run email cron.
     */
    public function execute() {

        global $CFG,$DB,$SITE;
        require_once($CFG->dirroot . '/config.php');
        require_once($CFG->dirroot . '/local/base/locallib.php');
         
       // require_login($SITE);//to solve email to user issue

        echo "start base report cron";
        //echo date("Y-m-d H:i:s").'----';

        $timestamp = time(); 
        
       /* $timestamp = strtotime ("+1 month",time());
        $daydate = date("Y-m-d",$timestamp);
		$month = new \DateTime($daydate);
        $timestamp  = mktime(0, 0, 0, $month->format('m'), 5, $month->format('Y'));
        */
        
        
        $beginOfDay = strtotime("midnight", $timestamp); 
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;  
         $str_time = date("H:i",time());
		$time = explode(':', $str_time);
	 	$currenttime =($time[0]*3600) + ($time[1]*60);
        $sql ="select c.* from {schedule_report_config} c
					where c.pause = 0 and c.delete=0 and 
					c.nextrun > $beginOfDay and c.nextrun < $endOfDay 
					and c.starttime < $currenttime";//use <
        $schedules=$DB->get_records_sql($sql);
        
		
        foreach($schedules as $schedule){
				\base::runschedule($schedule);

		}
		
    }

}
