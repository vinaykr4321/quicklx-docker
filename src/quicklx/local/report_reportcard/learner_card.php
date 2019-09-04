<?php

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/excellib.class.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');
require_once($CFG->dirroot.'/blocks/iomad_company_admin/lib.php');
require_once($CFG->dirroot.'/local/base/locallib.php');
require_once('locallib.php');

// Params.
$departmentid = optional_param('departmentid', 0, PARAM_INTEGER);
$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 20, PARAM_INT);        // How many user per page.
$search      = optional_param('search', '', PARAM_CLEAN);// Search string.
$user       = optional_param('user', 0, PARAM_CLEAN);
$selectuser       = optional_param('selectuser', 0, PARAM_CLEAN);
$firstname       = optional_param('firstname', 0, PARAM_CLEAN);
$lastname      = optional_param('lastname', '', PARAM_CLEAN);
$username      = optional_param('usernameids', '', PARAM_CLEAN);
$email  = optional_param('email', 0, PARAM_CLEAN);
//$organization       = optional_param('organization', 0, PARAM_CLEAN);
$activestatus       = optional_param('activestatus', 0, PARAM_CLEAN);
$completionstatus       = optional_param('completionstatus', 0, PARAM_CLEAN);
$enrolledstatus       = optional_param('enrolledstatus', 0, PARAM_CLEAN);
$country       = optional_param('country', 0, PARAM_CLEAN);
$daterange      = optional_param('daterange', 'datereg', PARAM_TEXT);
$urldatefrom = optional_param('urldatefrom', null, PARAM_INT);
$urldateto = optional_param('urldateto', null, PARAM_INT);
$urlcourse = optional_param('urlcourse', null, PARAM_RAW);
if(!$urldatefrom)
	$datefromraw = optional_param_array('datefrom', null, PARAM_INT);
if(!$urldateto)
	$datetoraw = optional_param_array('dateto', null, PARAM_INT);
if(!$urlcourse)
	$courseraw = optional_param_array('course', null, PARAM_INT);

require_login($SITE);
$context = context_system::instance();
iomad::require_capability('local/report_reportcard:view', $context);
if(isset($_POST['username'])){
	$usernamearray = $_POST['username'];
	foreach($usernamearray as $key=>$value){
		$username .= $value.',';
		
	}
	$username = trim($username,',');
}
else
	$username = optional_param('username', null, PARAM_RAW);

$params = array();
$urlsubgroup = optional_param('urlsubgroup', null, PARAM_RAW);
if(!$urlsubgroup)
	$subgroupraw = optional_param_array('subgroup', null, PARAM_INT);

if (isset($subgroupraw)) {
	   if (is_array($subgroupraw)) {
		   	$subgroup = implode(',',$subgroupraw);
	   }

}
else{
	if (isset($urlsubgroup)) {
		$subgroup =$urlsubgroup;
	}

}
if (isset($subgroup)) {
    $params['subgroup'] = $subgroup;
    $params['urlsubgroup'] = $subgroup;
}
$urlorganization = optional_param('urlorganization', null, PARAM_RAW);
if(!$urlorganization)
	$organizationraw = optional_param_array('organization', null, PARAM_INT);

if (isset($organizationraw)) {
	   if (is_array($organizationraw)) {
		   	$organization = implode(',',$organizationraw);
	   }

}
else{
	if (isset($urlorganization)) {
		$organization =$urlorganization;
	}

}
if (isset($organization)) {
    $params['organization'] = $organization;
    $params['urlorganization'] = $organization;
}

if ($user) {
    $params['user'] = $user;
}
if ($selectuser) {
    $params['selectuser'] = $selectuser;
}
if ($firstname) {
    $params['firstname'] = $firstname;
}
if ($lastname) {
    $params['lastname'] = $lastname;
}
if ($username) {
    $params['username'] = $username;
    $params['usernameids'] = $username;

}
if ($email) {
    $params['email'] = $email;
}
if ($activestatus) {
    $params['activestatus'] = $activestatus;
}
if ($completionstatus) {
    $params['completionstatus'] = $completionstatus;
}
if ($enrolledstatus) {
    $params['enrolledstatus'] = $enrolledstatus;
}
if ($country) {
    $params['country'] = $country;
}

if ($page) {
    $params['page'] = $page;
}
if ($perpage) {
    $params['perpage'] = $perpage;
}
if ($search) {
    $params['search'] = $search;
}
if ($daterange) {
    $params['daterange'] = $daterange;
}

if (isset($courseraw)) {
	   if (is_array($courseraw)) {
		   	$course = implode(',',$courseraw);
	   }

}
else{
	if (isset($urlcourse)) {
		$course =$urlcourse;
	}

}
if (isset($course)) {
    $params['course'] = $course;
    $params['urlcourse'] = $course;
}
if (isset($datefromraw)) {
    if (is_array($datefromraw)) {
        $datefrom = mktime(0, 0, 0, $datefromraw['month'], $datefromraw['day'], $datefromraw['year']);
    } else {
        $datefrom = $datefromraw;
    }

} else {
	if (isset($urldatefrom)) {
		$params['urldatefrom'] = $urldatefrom;
		$datefrom = $urldatefrom;
	}
	else
		$datefrom = strtotime(date('Y-m-d', strtotime('today - 30 days'))); 
		
}
   $params['datefrom'] = $datefrom;
   $params['urldatefrom'] = $datefrom;

if (isset($datetoraw)) {
    if (is_array($datetoraw)) {
        $dateto = mktime(0, 0, 0, $datetoraw['month'], $datetoraw['day'], $datetoraw['year']);
    } else {
        $dateto = $datetoraw;
    }

} else {    
	if (isset($urldateto)) {
		$params['urldateto'] = $urldateto;
		$dateto = $urldateto;
	}
	else
		$dateto = time();

 }
$params['dateto'] = $dateto;
$params['urldateto'] = $dateto;

$url = new moodle_url('/local/report_reportcard/learner_card.php', $params);
$baseurl = new moodle_url('/local/report_reportcard/index.php');

// Page stuff:.
$strcompletion = get_string('pluginname', 'local_report_reportcard');
$PAGE->set_url($baseurl);
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

// Get the renderer.
$output = $PAGE->get_renderer('block_iomad_company_admin');

// Set the companyid
$companyid = iomad::get_my_companyid($context);

// Work out department level.
$department = $DB->get_record('department', array('parent' => 0,'company'=>$companyid));
$departmentid = $department->id;

if ($departmentid) {
    $params['departmentid'] = $departmentid;
}

// Set the url.
company_admin_fix_breadcrumb($PAGE, $strcompletion, $baseurl);

// Only print the header if we are not downloading.
    echo $output->header();
    // Check the department is valid.
    if (!empty($departmentid) && !company::check_valid_department($companyid, $departmentid)) {
        print_error('invaliddepartment', 'block_iomad_company_admin');
    }   

if(isset($params['daterange']) && $params['daterange'] =='no' ){
	unset($params['datefrom']); 
	unset($params['dateto']); 	 
}
$reporthead = 'Learner Report Card';
if($params['daterange'] !='no'){
		$reportdaterange = 'Date Range: '. get_string($params['daterange'], 'local_base');
		$reportdate = date('m/d/Y',$params['datefrom']).' to '.date('m/d/Y',$params['dateto']);
	}
	else{
		$reportdaterange =  get_string($params['daterange'], 'local_base');
		$reportdate =  '';
	}

$userid=$selectuser;
$dbuser=$DB->get_record('user',array('id'=>$userid));
$userdetails =$dbuser->username.' ( '.$dbuser->firstname.' '.$dbuser->lastname.') ';
//print_r( $params);

	$returnobj = base::get_all_user_courses( $params);
	$coursedataobj= $returnobj->courses ;
	$totalcount= $returnobj->totalcount ;

    $params['download'] = 'download';
	$returnobj = base::get_all_user_courses($params);
	$exportcoursedataobj= $returnobj->courses ;
	unset($params['download']); 


//print_r($params);
	// Set up the filter form.
	$mform = new iomad_learnerreportcard_filter_form($params);
	$mform->set_data(array('departmentid' => $departmentid));
	$mform->set_data($params);
	$data =$mform->get_data();

	$alluserurl = new moodle_url('/local/report_reportcard/index.php', $params);
	$useraction = html_writer::link($alluserurl, 'All Users',array('title'=>'All Users'));  
	echo '<div style="float: right;"><b>'.$useraction.'</b></div>';
	echo '<h2>'.$reporthead.'</h2>';
		echo '<h5>'.$reportdaterange.'</h5>';
	echo '<h5>'.$reportdate.'</h5><br><hr style="background-color: black;margin-left: -35%;"><br>';
	echo '<h3>User: '." ".$userdetails.'</h3><br><br>';

	$mform->display();
		echo '<hr style="border-top: 1px solid #151414;">';

if(count($exportcoursedataobj)>0){
	$array_excel = array();
	 $array_pdf = array();
	 $array_excel[] = $array_pdf[] = array($reporthead);
	$array_excel[] =array();
	 $array_excel[] =array($reportdaterange);
	 $array_excel[] =array($reportdate);
	 $array_excel[] =array();
	 $array_pdf[] = array($reportdaterange.'<br>'.$reportdate);
	 $array_excel[] = array(
        get_string('coursename', 'local_base'),
        get_string('completed', 'local_base'),
        get_string('dateregistered', 'local_base'),
        get_string('datecompleted', 'local_base') );
	   
		$tablepdf ='<table class="generaltable" id="ReportTable">
					<thead  >
					<tr style="background-color: rgb(203, 205, 208);">
					<th  style="text-align:left;" >'.get_string('coursename', 'local_base').'</th>
					<th  style="text-align:left;" >'.get_string('completed', 'local_base').'</th>
					<th style="text-align:left;" >'.get_string('dateregistered', 'local_base').'</th>
					<th  style="text-align:left;" >'.get_string('datecompleted', 'local_base').'</th>
					</tr>
					</thead>
					<tbody> ';
 		$i=1;

	foreach($exportcoursedataobj as $coursedata)	{
		//print_r($coursedata);
		$completedate = '';
		$iscomplete = 'No';
		$course_completions =$DB->get_record('course_completions',array('userid'=>$userid,'course'=>$coursedata->id));
		if($course_completions){
			if(isset($course_completions->timecompleted)){
				$completedate	= date('m/d/Y',$course_completions->timecompleted);
				$iscomplete = 'Yes';
			}
		}
		$array_excel[]= array($coursedata->fullname,
										$iscomplete,
										date('m/d/Y',$coursedata->registered),
										$completedate);
	
			if($i%2==0)
			$style = 'background-color: #ece9e9;';
		else
			$style ='';								  			
		$tablepdf  .= '<tr style="'.$style.'">
						<td  style="text-align:left;">'.$coursedata->fullname.' </td>
						<td  style="text-align:left;">'.$iscomplete.'</td>
						<td  style="text-align:left;">'.date('m/d/Y',$coursedata->registered).'</td>
						<td  style="text-align:left;">'.$completedate.'</td>
						</tr>';
						$i++;
					  
	}
	$tablepdf .= '	</tbody>
				</table>
				';
	$array_pdf[] = $tablepdf; 		   	
}

if(count($coursedataobj)>0){
	// Set up the course  table.
	$coursecomptable = new html_table();
	$coursecomptable->id = 'ReportTable';
    $coursecomptable->head = array(
        get_string('coursename', 'local_base'),
        get_string('completed', 'local_base'),
        get_string('dateregistered', 'local_base'),
        get_string('datecompleted', 'local_base'),
       
    );
    $coursecomptable->align = array('left', 'center', 'center', 'center', 'center', 'center');

	foreach($coursedataobj as $coursedata)	{
		//print_r($coursedata);
		$completedate = '';
		$iscomplete = 'No';
		$course_completions =$DB->get_record('course_completions',array('userid'=>$userid,'course'=>$coursedata->id));
		if($course_completions){
			if(isset($course_completions->timecompleted)){
				$completedate	= date('m/d/Y',$course_completions->timecompleted);
				$iscomplete = 'Yes';
			}
		}
		$params['selectcourse']=$coursedata->id;
		$courseurl = new moodle_url('/local/report_reportcard/report_card.php', $params);

		$courseaction = html_writer::link($courseurl, $coursedata->fullname,array('title'=>'Report Card'));  

		 $coursecomptable->data[] = array(
										$courseaction,
										$iscomplete,
										date('m/d/Y',$coursedata->registered),
										$completedate,
									);
	}
	echo '<div id="exportbuttons" style="text-align: center;">	';
		
	  $PAGE->set_url('/local/base/download_excel.php',array('name'=>'learnerreportcard'));
    echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));
     
     $PAGE->set_url('/local/base/download_csv.php',array('name'=>'learnerreportcard'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));
	
    $PAGE->set_url('/local/base/download_pdf.php',array('name'=>'learnerreportcard'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));

	
	echo $report_button = base::report_button();
	
	echo "</div><br />"; 
		$perpageopt = array('20'=>20,'30'=>30,'40'=>40,'50'=>50,'75'=>75,'100'=>100);
		echo '<div class="show-per-page" >
			<label  for="id_perpage">
				Show per page:
			</label>

			<select name="perpage" id="id_perpage">';
			foreach($perpageopt as $key=>$value){
				if($key == $perpage)
					echo ' <option value="'.$key.'" selected>'.$value.'</option>';
				else
					echo ' <option value="'.$key.'" >'.$value.'</option>';
			}
		   
		   echo  '</select>           
					</div>';  
		echo $output->paging_bar($totalcount, $page, $perpage, new moodle_url('/local/report_reportcard/learner_card.php', $params));
		echo "<br />";
		echo html_writer::table($coursecomptable);
		echo "<br />";
		echo $output->paging_bar($totalcount, $page, $perpage, new moodle_url('/local/report_reportcard/learner_card.php', $params));
		echo "<br />";
		echo '<div id="exportbuttons" style="text-align: center;">	';
		
	  $PAGE->set_url('/local/base/download_excel.php',array('name'=>'learnerreportcard'));
    echo "<br>".$OUTPUT->single_button(new moodle_url($PAGE->url, array('exceldata' => json_encode($array_excel))),  get_string("downloadexcel", 'local_base'));
     
     $PAGE->set_url('/local/base/download_csv.php',array('name'=>'learnerreportcard'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('csvdata' => json_encode($array_excel))), get_string("downloadcsv", 'local_base'));
	
    $PAGE->set_url('/local/base/download_pdf.php',array('name'=>'learnerreportcard'));
    echo $OUTPUT->single_button(new moodle_url($PAGE->url, array('pdfdata' => json_encode($array_pdf))),  get_string("downloadpdf", 'local_base'));

	
	echo $report_button = base::report_button();
	
	echo "</div><br />"; 
			   	
}	
else{
		echo 'There are no results. Please try a different search.';
		
}			
if(count($exportcoursedataobj)>0){
	
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
                <form role="form"> ';
                foreach($params as $key=>$value ){
					echo '<input type="hidden" name="'.$key.'" value="'. $value. '">';
				}
                
			echo' <input type="hidden" name="url" value="learner_submit_form">
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
                        placeholder="Enter email subject" value="Report Card report"/>
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
//print_r($USER);
$currenttime = base::usertimezone($USER->id);

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
                <p class="schedulestatusMsg"></p>
                <form role="form"> ';
                foreach($params as $key=>$value ){
					echo '<input type="hidden" name="'.$key.'" value="'. $value. '">';
				}
                
			echo' <input type="hidden" name="departmentid" value="'. $departmentid. '">
			<input type="hidden" name="reportname" value="reportcard">
			<input type="hidden" name="screen" value="2">
			  <div class="form-group">
                        <label for="description">Schedule Description:</label>
                        <input type="text" class="form-control" id="description" 
                        placeholder="Enter Description" value="Learner Report Card"/>
                    </div>
                      <div class="form-group">
                        <label >Report Format:</label><br>
						  <input type="radio" id="inputformat11" name="scheduleformat" value="pdf">
							<label for="inputformat11">PDF</label>
							&nbsp;&nbsp;&nbsp;
							<input type="radio" id="inputformat12" name="scheduleformat" value="csv" >
							<label for="inputformat12">CSV</label>
						<!--	&nbsp;&nbsp;&nbsp;
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
                        placeholder="Enter email subject" value="Learner Report Card"/>
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
			  <input type="text" class="input-small" name="starttime" id="starttime" value="'.$currenttime.'">
                     
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
                    <label for="schedule">'. get_string("scheduletimenote", 'local_base').'</label>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="schedulersubmitForm" class="btn btn-primary submitBtn" >SUBMIT</button>
            </div>
        </div>
    </div>
</div>';
}
echo $output->footer();
?>

<script>
	(function ($) {
		$(document).ready(function() {
			   $("td:has(span.user)").closest('tr').css("background-color", "#cbcdd0");
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

