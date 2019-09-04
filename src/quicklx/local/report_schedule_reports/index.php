<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/excellib.class.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->dirroot.'/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');
require_login($SITE);

// Params.
$departmentid = optional_param('departmentid', 0, PARAM_INTEGER);
$sesskey = optional_param('sesskey', '', PARAM_RAW);
$confirm = optional_param('confirm', 0, PARAM_BOOL);
$configid = optional_param('configid', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_RAW);
$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 20, PARAM_INT);        // How many user per page.
if ($page) {
    $params['page'] = $page;
}
if ($perpage) {
    $params['perpage'] = $perpage;
}
	$record = new stdClass();
	$record->id = $configid;
	$record->timemodified = time();
if($action == 'pause'){
	$record->pause = 1;
	$DB->update_record('schedule_report_config',$record); 	
}
else if($action == 'play'){
	$record->pause = 0;
	$DB->update_record('schedule_report_config',$record); 	
}
else if($action == 'runimmediatly'){
	base::schedule($configid); 	
}

$context = context_system::instance();
iomad::require_capability('local/report_schedule_reports:view', $context);

// Url stuff.
$url = new moodle_url('/local/report_schedule_reports/index.php');
$dashboardurl = new moodle_url('/local/iomad_dashboard/index.php');

// Page stuff:.
$strcompletion = get_string('pluginname', 'local_report_schedule_reports');
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_title($strcompletion);
$PAGE->requires->css("/local/base/css/styles.css");
$PAGE->requires->css("/local/base/css/chosen.css");
$PAGE->requires->jquery();
$PAGE->requires->js('/local/base/js/chosen.jquery.js');
$PAGE->requires->js('/local/base/js/custom.js');
$PAGE->requires->css("/local/base/css/bootstrap-timepicker.min.css");
$PAGE->requires->js('/local/base/js/bootstrap-timepicker.min.js');
// get output renderer                                                                                                                                                                                         
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Javascript for fancy select.
// Parameter is name of proper select form element followed by 1=submit its form
$PAGE->requires->js_call_amd('block_iomad_company_admin/department_select', 'init', array('departmentid', 1, optional_param('departmentid', 0, PARAM_INT)));

// Set the page heading.
$PAGE->set_heading(get_string('pluginname', 'block_iomad_reports') . " - $strcompletion");


if($action == 'delete' ){
	$schedule=$DB->get_record('schedule_report_config',array('id'=>$configid));
	if ($confirm && data_submitted()) {
		if (!confirm_sesskey() ) {
			print_error('confirmsesskeybad','error',$returnurl);
		}
		else{
			$filters=$DB->get_record('schedule_report_filter',array('configid'=>$configid));
			$DB->delete_records('schedule_report_filter',array('id'=>$filters->id)); 	
			$DB->delete_records('schedule_report_config',array('id'=>$configid));
			}

    redirect('index.php');
	}
	else{
		
		echo $OUTPUT->header();
		$optionsyes = array('action'=>'delete', 'configid'=>$configid, 'sesskey'=>sesskey(), 'confirm'=>1);
		$optionsno = array('departmentid'=>$departmentid);
			$message="Are you sure you want to delete this schedule ".$schedule->description;

		$formcontinue = new single_button(new moodle_url('index.php', $optionsyes), get_string('yes'), 'post');
		$formcancel = new single_button(new moodle_url('index.php', $optionsno), get_string('no'), 'get');
		echo $OUTPUT->confirm($message, $formcontinue, $formcancel);
		echo $OUTPUT->footer();

	} 	
}
else{
	echo $output->header();

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

if ($departmentid) {
    $params['departmentid'] = $departmentid;
}

// Set the url.
company_admin_fix_breadcrumb($PAGE, $strcompletion, $url);

$url = new moodle_url('/local/report_schedule_reports/index.php', $params);

// Only print the header if we are not downloading.
// Check the department is valid.
if (!empty($departmentid) && !company::check_valid_department($companyid, $departmentid)) {
	print_error('invaliddepartment', 'block_iomad_company_admin');
}   
$reporthead = 'Scheduled Reports';
$activehead = 'Active Scheduled Reports';
$inactivehead = 'Inactive Scheduled Reports';
$returnobj = report_schedule_reports::scheduled_reports($departmentid);

if($returnobj){
	$active= $returnobj->active ;
	$inactive= $returnobj->inactive ;
	 $array_excel = array();
	 $array_pdf = array();
	 $array_excel[] = $array_pdf[] = array($reporthead);
	 $excelhead = array(get_string('description', 'local_report_schedule_reports'),
								get_string('nextrun', 'local_report_schedule_reports'),
								get_string('lastrun', 'local_report_schedule_reports'),
								get_string('recipients', 'local_report_schedule_reports'),
								get_string('scheduledescription', 'local_report_schedule_reports'),
								get_string('format', 'local_report_schedule_reports'),
								get_string('pause', 'local_report_schedule_reports'));
	 $tablehead = array(get_string('info', 'local_report_schedule_reports'),
								get_string('description', 'local_report_schedule_reports'),
								get_string('nextrun', 'local_report_schedule_reports'),
								get_string('lastrun', 'local_report_schedule_reports'),
								get_string('recipients', 'local_report_schedule_reports'),
								get_string('scheduledescription', 'local_report_schedule_reports'),
								get_string('format', 'local_report_schedule_reports'),
								get_string('pause', 'local_report_schedule_reports'),
								get_string('actions', 'local_report_schedule_reports'));
		$pdfhead = '<table class="generaltable" id="ReportTable">
						<thead  >
						<tr style="background-color: rgb(144, 140, 141);" >
						<th class="header c1" style="text-align:center;" >'.get_string('description', 'local_report_schedule_reports').'</th>
						<th class="header c3" style="text-align:center;" >'.get_string('nextrun', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('lastrun', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('recipients', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('scheduledescription', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('format', 'local_report_schedule_reports').'</th>
						<th class="header c4" style="text-align:center;" >'.get_string('pause', 'local_report_schedule_reports').'</th>
						</tr>
						</thead>
						<tbody> ';
						$array_excel[] =  array(' ');
	    $array_excel[] = $array_pdf[] = array($activehead);
	 if($active){
		
		 $array_excel[] = $excelhead;								
		 $tablepdf =$pdfhead;
		// Set up the course  table.
		$activetable = new html_table();
		$activetable->id = 'ReportTable';
		$activetable->head =$tablehead;
		$activetable->align = array('left', 'center', 'center', 'center', 'center', 'center'); 
		  $i=1;
		foreach($active as $key=>$report){
			$report->emailusers = str_replace(';', '; ', $report->emailusers);
			$nextrun ='';
			if($report->nextrun > 0)
				$nextrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->nextrun));
			$lastrun ='';
			if($report->lastrun > 0)
				$lastrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->lastrun));
			
			$discription = report_schedule_reports::sceduleddescription($report);
			$format = strtoupper($report->format);
			$timecreated =date("m/d/Y h:i:s A",$report->timecreated); 
			$timemodified =date("m/d/Y h:i:s A",$report->timemodified);
			
			$reportplugin = 'report_'.$report->reportname;
			$reportname = get_string('pluginname', 'local_'.$reportplugin);
			if($report->screen == 1)
				$reportpage = 'index';
			else{
				$reportpage =  report_schedule_reports::reportpage($report);
			}
			
			$params =base::schedulefilters($report->id);

			$runasreport = new moodle_url('/local/'.$reportplugin.'/'.$reportpage.'.php', $params);

			if($report->pause == 0){
				$exportpause = 'play';
				$pause = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=pause'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('y/tp'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Pause')); 
			}else{
				$exportpause = 'paused';
				$pause = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=play'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/enrolmentsuspended'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Play')); 
			}
			if($report->delete == 0)
				$delete = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=delete', array('sesskey' => sesskey())), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/invalid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Delete Schedule')); 
			else
				$delete = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=active'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/valid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Active')); 
			                   // $displayurl = new moodle_url($options->get_url(), array('sesskey' => sesskey()));
	
			$edit = html_writer::link(new moodle_url('edit_form.php?configid='.$report->id), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('t/edit'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Edit or Copy Schedule')); 
			$runreport = html_writer::link($runasreport, html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/report'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Run as Report')); 
			$runimmediatly = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=runimmediatly'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/valid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Run Schedule Immediatly')); 
			
			
			$info = html_writer::link('#', html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/info'), 'alt'=>'', 'class'=>'iconsmall')), array('class'=>'myHoverTitle')); 
			$info .='<div class="myContentHover">
						<p style ="text-align:center !important; font-weight:bold !important;font-size: 20px;">'.$report->description.'</p>
						<p class="contentlabel">Created: </p><span class="contentdata" >'.$timecreated.'</span>
						<p class="contentlabel">Modified: </p><span class="contentdata" >'.$timemodified.'</span>
						<p class="contentlabel">Report Name: </p><span class="contentdata" >'.$reportname.'</span>
						<p class="contentlabel">Report Delivery: </p><span class="contentdata" >'.ucfirst($report->reportdelivery).'</span>
						<p class="contentlabel">Report Format: </p><span class="contentdata" >'.$format.'</span>
						<p class="contentlabel">Report Range Start: </p><span class="contentdata" >'.base::dateRange_info($report->startrange).'</span>
						<p class="contentlabel">Report Range End: </p><span class="contentdata" >'.base::dateRange_info($report->endrange).'</span>
						<p class="contentlabel">Email Subject: </p><span class="contentdata" >'.$report->emailsubject.'</span>
						<p class="contentlabel">Email To: </p><span class="contentdata" >'.$report->emailusers.'</span>
						</div>' ;
			$exceldata = array($report->description,
								$nextrun,
								$lastrun,
								$report->emailusers,
								$discription,
								$format,
								$exportpause);
			$tabledata = array($info,
								$report->description,
								$nextrun,
								$lastrun,
								$report->emailusers,
								$discription,
								$format,
								$pause,
								$delete . ' '.$edit. ' '.$runreport. ' '.$runimmediatly,		
							);
			 $activetable->data[] = $tabledata;
			$array_excel[] = $exceldata;
			if($i%2==0)
				$style = 'background-color: #ece9e9;';
			else
				$style ='';	
			$tablepdf  .= '<tr style="'.$style.'">
								<td class="cell c1" style="text-align:center;">'.$report->description.'</td>
								<td class="cell c1" style="text-align:center;">'.$nextrun.'</td>
								<td class="cell c1" style="text-align:center;">'.$lastrun.'</td>
								<td class="cell c1" style="text-align:center;">'.$report->emailusers.'</td>
									<td class="cell c1" style="text-align:center;">'.$discription.'</td>
									<td class="cell c1" style="text-align:center;">'.$format.'</td>
									<td class="cell c1" style="text-align:center;">'.$exportpause.'</td>
									</tr>';
									
			$i++;									   				   			
		}
	}
	else{
 $array_excel[] =array( 'There are no Active scheduled reports.');	
 $tablepdf ='<table class="generaltable" id="ReportTable"><thead><tr><th></th></tr></thead>
 	<tbody><tr><td>There are no Active scheduled reports. </td></tr>';
}
$tablepdf .= '	</tbody>
					</table>
					';
		$array_pdf[] = $tablepdf; 
		 $array_excel[] =  array(' ');
	    $array_excel[] = $array_pdf[] = array($inactivehead);
	 if($inactive){
		
		 $array_excel[] = $excelhead;								
		 $tablepdf =$pdfhead;
		// Set up the course  table.
		$inactivetable = new html_table();
		$inactivetable->id = 'ReportTable';
		$inactivetable->head =$tablehead;
		$inactivetable->align = array('left', 'center', 'center', 'center', 'center', 'center'); 
		    $i=1;
		foreach($inactive as $key=>$report){
			$report->emailusers = str_replace(';', '; ', $report->emailusers);
			$nextrun ='';
		/*	if($report->nextrun > 0)
				$nextrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->nextrun));*/
			$lastrun ='';
			if($report->lastrun > 0)
				$lastrun =date("m/d/Y h:i:s A",base::usertimezonenextrun($report->lastrun));
				
			$discription = report_schedule_reports::sceduleddescription($report);
			$format = strtoupper($report->format);
			$timecreated =date("m/d/Y h:i:s A",$report->timecreated); 
			$timemodified =date("m/d/Y h:i:s A",$report->timemodified);
			
			$reportplugin = 'report_'.$report->reportname;
			$reportname = get_string('pluginname', 'local_'.$reportplugin);
			if($report->screen == 1)
				$reportpage = 'index';
			else{
				$reportpage =  report_schedule_reports::reportpage($report);
			}			
			$params =base::schedulefilters($report->id);
			
			$runasreport = new moodle_url('/local/'.$reportplugin.'/'.$reportpage.'.php', $params);
			//$editreport = new moodle_url('edit_form.php?configid='.$report->id, $params);
			
			if($report->pause == 0){
				$exportpause = 'play';
				$pause = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=pause'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('y/tp'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Pause')); 
			}else{
				$exportpause = 'paused';
				$pause = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=play'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/enrolmentsuspended'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Play')); 
			}
			
			if($report->delete == 0)
				$delete = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=delete', array('sesskey' => sesskey())), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/invalid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Delete Schedule')); 
			else
				$delete = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=active'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/valid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Active')); 
			
			$edit = html_writer::link(new moodle_url('edit_form.php?configid='.$report->id), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('t/edit'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Edit or Copy Schedule')); 
			$runreport = html_writer::link($runasreport, html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/report'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Run as Report')); 
			$runimmediatly = html_writer::link(new moodle_url('index.php?configid='.$report->id.'&action=runimmediatly'), html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/valid'), 'alt'=>'', 'class'=>'iconsmall')), array('title'=>'Run Schedule Immediatly')); 
			
			
			$info = html_writer::link('#', html_writer::empty_tag('img', array('src'=>$OUTPUT->image_url('i/info'), 'alt'=>'', 'class'=>'iconsmall')), array('class'=>'myHoverTitle')); 
			$info .='<div class="myContentHover">
						<p style ="text-align:center !important; font-weight:bold !important;font-size: 20px;">'.$report->description.'</p>
						<p class="contentlabel">Created: </p><span class="contentdata" >'.$timecreated.'</span>
						<p class="contentlabel">Modified: </p><span class="contentdata" >'.$timemodified.'</span>
						<p class="contentlabel">Report Name: </p><span class="contentdata" >'.$reportname.'</span>
						<p class="contentlabel">Report Delivery: </p><span class="contentdata" >'.ucfirst($report->reportdelivery).'</span>
						<p class="contentlabel">Report Format: </p><span class="contentdata" >'.$format.'</span>
						<p class="contentlabel">Report Range Start: </p><span class="contentdata" >'.base::dateRange_info($report->startrange).'</span>
						<p class="contentlabel">Report Range End: </p><span class="contentdata" >'.base::dateRange_info($report->endrange).'</span>
						<p class="contentlabel">Email Subject: </p><span class="contentdata" >'.$report->emailsubject.'</span>
						<p class="contentlabel">Email To: </p><span class="contentdata" >'.$report->emailusers.'</span>
						</div>' ;
			$exceldata = array($report->description,
								$nextrun,
								$lastrun,
								$report->emailusers,
								$discription,
								$format,
								$exportpause);
			$tabledata = array($info,
								$report->description,
								$nextrun,
								$lastrun,
								$report->emailusers,
								$discription,
								$format,
								$pause,
								$delete . ' '.$edit. ' '.$runreport. ' '.$runimmediatly,		
							);
			 $inactivetable->data[] = $tabledata;
			$array_excel[] = $exceldata;
			if($i%2==0)
				$style = 'background-color: #ece9e9;';
			else
				$style ='';	
			$tablepdf  .= '<tr style="'.$style.'">
								<td class="cell c1" style="text-align:center;">'.$report->description.'</td>
								<td class="cell c1" style="text-align:center;">'.$nextrun.'</td>
								<td class="cell c1" style="text-align:center;">'.$lastrun.'</td>
								<td class="cell c1" style="text-align:center;">'.$report->emailusers.'</td>
									<td class="cell c1" style="text-align:center;">'.$discription.'</td>
									<td class="cell c1" style="text-align:center;">'.$format.'</td>
									<td class="cell c1" style="text-align:center;">'.$exportpause.'</td>
									</tr>';
							$i++;		
								   				   			
		}
		
	}
	else{
 $array_excel[] =array( 'There are no Inactive scheduled reports.');	
 $tablepdf ='<table class="generaltable" id="ReportTable"><thead><tr><th></th></tr></thead>
 	<tbody><tr><td>There are no Inactive scheduled reports. </td></tr>';
}
$tablepdf .= '	</tbody>
					</table>
					';
		$array_pdf[] = $tablepdf; 

  echo '<div id="exportbuttons" style="text-align: center;">	';
		
	  $PAGE->set_url('/local/base/download_excel.php',array('name'=>'scheduledreports'));
    echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));
     
     $PAGE->set_url('/local/base/download_csv.php',array('name'=>'scheduledreports'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));
	
    $PAGE->set_url('/local/base/download_pdf.php',array('name'=>'scheduledreports'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));

	echo '&nbsp;<button class="btn btn-secondary" name="emailreport" id="id_emailreport" type="button" data-toggle="modal" data-target="#modalForm">
	'.get_string("emailreport", 'local_base').'</button>';
	
	echo "</div><br />";
	 
	echo '<h4>'.$activehead.'</h4>';
	if($active)	
		echo html_writer::table($activetable);
	else
		echo 'There are no Active scheduled reports';

	echo "<br /><br />";
	echo '<h4>'.$inactivehead.'</h4>';

	if($inactive)	
	echo html_writer::table($inactivetable);
	else
			echo 'There are no Inactive scheduled reports';

	echo "<br />";
	  echo '<div id="exportbuttons" style="text-align: center;">	';
		
	  $PAGE->set_url('/local/base/download_excel.php',array('name'=>'scheduledreports'));
    echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));
     
     $PAGE->set_url('/local/base/download_csv.php',array('name'=>'scheduledreports'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));
	
    $PAGE->set_url('/local/base/download_pdf.php',array('name'=>'scheduledreports'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));

echo '&nbsp;<button class="btn btn-secondary" name="emailreport" id="id_emailreport" type="button" data-toggle="modal" data-target="#modalForm">
	'.get_string("emailreport", 'local_base').'</button>';	
	echo "</div><br />"; 

   echo '<p>'. get_string("scheduletimenote", 'local_base').'</p>';
 
}
else{
		echo 'There are no scheduled reports';
		
}

if($returnobj ){
	$userarray=array();
	$scheduleuserarray=array();
	$userarrayid=array();
		$allusers =base::selectusers($departmentid);	
	foreach($allusers as $key => $value){
		if($key>0){
			$userid=$key;
			$sql = "select * from {user} where id =$userid";
			$user = $DB->get_record_sql($sql);
			$userarray[$key]=$value;
			$scheduleuserarray[$user->email]=$value;
			$userarrayid[]=$key;
		}
	}
	 $userarrayid = implode(', ',$userarrayid);


echo '
 <div class="modal fade" id="modalForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">      <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Email Configuration</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <p class="statusMsg"></p>
                <form role="form"> 
                <input type="hidden" name="departmentid" value="'. $departmentid. '">

			<input type="hidden" name="url" value="submit_form">
					<input type="hidden" name="userarrayid" value="'. $userarrayid. '">
					<div class="form-group">
                        <label for="inputEmail">Send To Email Addresses:</label>
                          <textarea class="form-control" id="inputEmail" 
                        placeholder="Separate email address with a semicolon (;)" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="inputuser">Please select users to email: (First/last/username)</label>
                        <select class="form-control" id="inputuser" name="inputuser" size="10">
                        ';
                        foreach($scheduleuserarray as $key=>$value){
							echo '<option value="'.$key.'">'.$value.'</option>';
							}
                   echo    ' </select>
                    </div>
                    <div class="form-group">
                        <label >Report Format:</label><br>
						  <input type="radio" id="inputformat1" name="format" value="pdf">
							<label for="inputformat1">PDF</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat2" name="format" value="csv" >
							<label for="inputformat2">CSV</label>
							<!--&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat3" name="format" value="html" checked>
							<label for="inputformat3">HTML</label>-->
							&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat4" name="format" value="xlsx" checked>
							<label for="inputformat4">XLS</label>
                        </div>
                    <!--  <div class="form-group">
                        <label >Report Delivery:</label><br>
						  <input type="radio" id="inputdelivery1" name="attach" value="link" >
							<label for="inputdelivery1">Link</label> 

							<input type="radio" id="inputdelivery2" name="attach" value="attach" checked>
							<label for="inputdelivery2">Attachment</label>
                        </div> -->
                    <div class="form-group">
                        <label for="inputEmailSubject">Email Subject:</label>
                        <input type="text" class="form-control" id="inputEmailSubject" 
                        placeholder="Enter email subject" value="Scheduled report"/>
                    </div>
                    <div class="form-group">
                        <label for="inputEmailBody">Email Body:</label>
                        <textarea class="form-control" id="inputEmailBody" 
                        placeholder="Enter email body" >Your requested report is attached</textarea>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="emailsubmitForm" class="btn btn-primary submitBtn" >SUBMIT</button>
            </div>
        </div>
    </div>
</div>';
$currenttime = date("H:i",time());

	$timeformat = 'UTC ';

 $timezone = $timeformat.base::usertimezone($USER->id);

echo '
 <div class="modal fade" id="schedulereportForm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">      <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Configure Schedule</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <p class="schedulestatusMsg" id="schedulestatusMsg"></p>
                <form role="form"> ';
                foreach($params as $key=>$value ){
					echo '<input type="hidden" name="'.$key.'" value="'. $value. '">';
				}
                
			echo' <input type="hidden" name="departmentid" value="'. $departmentid. '">
			<input type="hidden" name="reportname" value="schedule_reports">
			<input type="hidden" name="screen" value="1">
			  <div class="form-group">
                        <label for="description">Schedule Description:</label>
                        <input type="text" class="form-control" id="description" 
                        placeholder="Enter Description" value="Schedule report"/>
                    </div>
                      <div class="form-group">
                        <label >Report Format:</label><br>
						  <input type="radio" id="inputformat11" name="scheduleformat" value="pdf">
							<label for="inputformat11">PDF</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat12" name="scheduleformat" value="csv" >
							<label for="inputformat12">CSV</label>
							<!--&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat13" name="scheduleformat" value="html" checked>
							<label for="inputformat13">HTML</label>-->
							&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat14" name="scheduleformat" value="xlsx" checked>
							<label for="inputformat14">XLS</label>
                        </div>
                         <div class="form-group">
                        <label for="emailusers">Send To Email Addresses:</label>
                        <textarea class="form-control" id="emailusers" 
                        placeholder="Separate email address with a semicolon (;)" ></textarea>
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
                        <input type="text" class="form-control" id="scheduleEmailSubject" 
                        placeholder="Enter email subject" value="Schedule report"/>
                    </div>
                    <div class="form-group">
                        <label for="scheduleEmailBody">Email Body:</label>
                        <textarea class="form-control" id="scheduleEmailBody" 
                        placeholder="Enter email body" >Your requested report is attached</textarea>
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
						echo '<option value="0-Sincerecent">Since Most Recent Report Sent</option>';
							foreach($daterange as $key=>$value){
								echo '<option value="'.$key.'">'.$value.'</option>';
							}					echo '</select>
                    </div> <div class="form-group">
                     <label for="endrange">End Range:</label>
						<select  id="endrange" name="endrange" >';
							foreach($daterange as $key=>$value){
								echo '<option value="'.$key.'">'.$value.'</option>';
							}					echo '</select>
                    </div>   
                   <div class="form-group">
                     <label>Report Schedule:</label><br>
                     <label for="schedule">Frequency:</label>
						<select class="schedule-control" id="schedule" name="schedule" >
						   <option value="Once"  >Once</option>
						   <option value="Daily" data-target="#opt11,#enddatecheck">Daily</option>
						   <option value="Weekly" data-target="#opt21,#opt22,#enddatecheck" >Weekly</option>
						   <option value="Monthly" data-target="#opt31,#opt32,#enddatecheck" >Monthly</option>
                       </select>
                    </div>
                    
                   <div class="schedule-elements">
						<div class="form-group">
							<label for="opt11">Every</label>
							<input type="text" name="dayopt1" id="opt11" value="1"> day(s)                      
						</div>
						<div class="form-group">
							<label for="opt21">Every</label>
							<input type="text" name="weekopt1" id="opt21" value="1"> week(s)                      
						</div>
						
						<div class="form-group" >
								<label id="opt22"></label>';
                    
						$dayarray=array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
							foreach($dayarray as $value){
									echo '<label> <input type="checkbox" name="weekopt2" value="'.$value.'" checked/>'.$value.'</label>
							';
								}
						
						 echo ' </div>
						   <div class="form-group" >
						   <label id="opt31"></label>
							  <input type="radio" name="opt31" value="day1" id="opt31first"  > Day 
							  <input type="text" name="monthopt1" value="1">
							  of the month(s)<br>
							  <input type="radio" name="opt31" id="opt31second" value="month1"> The nth weekday of the month(s)<br>
						</div>
						
						  <div class="form-group" >
						   <label id="opt311"></label>
							  <input type="radio" name="opt31" value="day2" id="opt311first"> Day n of the month(s)<br>
							  <input type="radio" name="opt31" value="month2" id="opt311second" >The
							  
							  <select  id="day" name="day" >
						   <option value="first"  >first</option>
						   <option value="second" >second</option>
						   <option value="third" >third</option>
						   <option value="fourth" >fourth</option>
						   <option value="last" >last</option>
						  
                       </select>
                       <select name="weeks" id="weeks">';
                       foreach($dayarray as $value){
							echo '<option value="'.$value.'">'.$value.'</option>';
							}
						  
                     echo'  </select>
							  
							      of the month(s)<br>
						</div>
						
						 	<div class="form-group" >
						 	 <label id="opt32"></label>';
                    
						$opt2=array('January','February','March','April','May','June','July','August','September','October','November','December');
							foreach($opt2 as $value){
									echo '<label> <input name="month" type="checkbox" value="'.$value.'" checked/>'.$value.'</label>
							';
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
                        <input type="date" name="startdate" id="startdate" value="'.date('Y-m-d',time()).'">
                    </div>
                    <div class="end-date">
                     <div class="form-group" >
							<label> <input type="checkbox"  id="enddatecheck" name="enddatecheck" />End Date </label>
						</div>
						 
						   <div class="form-group">
                         <label for="enddate">	</label>
						  <input type="date" name="enddate" id="enddate" value="'.date('Y-m-d',time()).'">
                     
                    </div>
                    <label for="schedule">*Scheduled times are based on your current time zone setting: ('.$timezone.')</label>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submitBtn" id="schedulersubmitForm">SUBMIT</button>
            </div>
        </div>
    </div>
</div>';
}
echo $output->footer();
}
?>
  
<script>
	(function ($) {

		$(document).ready(function() {
			   $("td:has(span.user)").closest('tr').css("background-color", "#cbcdd0");
			  /* $( '.myHoverTitle' ).hover(function(){
				   $(this).toggleClass("showtooltip");
				});*/
			$( '.myHoverTitle' ).each(function(){
			      $(this).closest("td.cell").addClass("has-tooltip");
			 });
		}); 
		
		
}(jQuery));

</script>

<script>
var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}

</script>

