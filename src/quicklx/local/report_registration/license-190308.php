<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/excellib.class.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->dirroot.'/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');

require_login($SITE);
$context = context_system::instance();
iomad::require_capability('local/report_registration:view', $context);

// Url stuff.
$url = new moodle_url('/local/report_registration/index.php');
$dashboardurl = new moodle_url('/local/iomad_dashboard/index.php');

// Page stuff:.
$strcompletion = get_string('pluginname', 'local_report_registration');
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($strcompletion);

// Set the page heading.
$PAGE->set_heading(get_string('pluginname', 'block_iomad_reports') . " - $strcompletion");
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Set the url.
company_admin_fix_breadcrumb($PAGE, $strcompletion, $url);

$url = new moodle_url('/local/report_registration/index.php');

// Set the companyid
$companyid = iomad::get_my_companyid($context);
// Work out department level.
$department = $DB->get_record('department', array('parent' => 0,'company'=>$companyid));
$departmentid = $department->id;

// Only print the header if we are not downloading.
echo $output->header();
    if(is_siteadmin($USER->id)){
		echo '<h1>user_license_assigned event triger</h1>';
		echo 'array[user]=>course,license,logid';
		$allusers = base::selectusers($departmentid);
		$eventname = "'\\\block_iomad_company_admin\\\\event\\\courseeventuser_license_selfreg',
						'\\\block_iomad_company_admin\\\\event\\\user_license_assigned'";

		foreach($allusers as $id=>$name){
			if($id > 0){
				//print_r($id);
				//print_r($name);
				$params = array();
				$params['selectuser'] = $id;
				$params['download']='download';
				$returnobj = base::get_all_user_courses($params);
				$coursedataobj= $returnobj->courses ;
				$changedata='';
				foreach($coursedataobj as $key=>$course){
$context = context_course::instance($course->id);
					 $sql = "SELECT ls.* from {logstore_standard_log} ls
						join {user} u on u.id = ls.userid
						where u.deleted=0 and ls.eventname in ($eventname) 
						and ls.userid=$id and ls.courseid = ".$course->id;
		
						$events = $DB->get_records_sql($sql);
						if(!$events){

							$sql ="select cl.*,cu.id as cuid,cu.issuedate 
										from {companylicense} cl 
										join {companylicense_users} cu on cu.licenseid=cl.id
										where cu.userid=".$id." and cu.licensecourseid=".$course->id;
							
							$licenses = $DB->get_records_sql($sql);
							if($licenses){
								foreach($licenses as $license){

									$record2 = new stdClass();
									$record2->eventname  ='\block_iomad_company_admin\event\user_license_assigned';
									$record2->component  ='block_iomad_company_admin';
									$record2->action  ='assigned';
									$record2->target  ='user_license';
									$record2->objecttable  ='license';
									$record2->objectid  =$license->id;
									$record2->crud  ='c';
									$record2->edulevel  =0;
									$record2->contextid  =$context->id;
									$record2->contextlevel  =$context->contextlevel;
									$record2->contextinstanceid  =$context->instanceid;
									$record2->userid  =$id;
									$record2->courseid  =$course->id;
									$record2->timecreated  = $license->issuedate;
									$logid = $DB->insert_record('logstore_standard_log', $record2);
									
									$affected = array();		
                           			$affected[$id]=$course->id.','.$license->id.','.$logid;
									print_r($affected);

								
								}

							}
							
						}
						
					
				}
				echo '<hr>';
			}
		}
	}
	
echo $output->footer();
