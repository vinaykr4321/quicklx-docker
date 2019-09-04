<?php

/**
 *  Filter form used on the report .
 *
 */
 
 require_once($CFG->dirroot.'/local/base/locallib.php');
 
class report_course_completion {
	
	public static function get_all_users_courses($params){
		
		global $DB,$USER;
         //print_r($params);
        if(isset($params['organization'])){
			 	$companyid = $params['organization'];
			 	$department = $DB->get_record('department', array('parent' => 0,'company'=>$companyid));
				$departmentid = $department->id;
		 }

         if(isset($params['departmentid'])){
			 $departmentid = $params['departmentid'];
		 }
		 
         $unsetdownload=1;
		if(isset($params['download']))
			$unsetdownload=0;
		else	
			$params['download'] = 'download';
			
		$returnobj = base::get_all_user($departmentid, 0, $params);
		$userdataobj= $returnobj->users ;
		//print_r($userdataobj);
		if($unsetdownload==1)
			unset($params['download']); 
		if($userdataobj){	
			$userid='';	
			foreach($userdataobj as $user){
				
				$userid .=$user->id.',';
				
			}
			 $userid=rtrim($userid,',');
			 
			
			 if(isset($params['page']))
				$page =$params['page'];
			else
				$page =0;
				
			if(isset($params['perpage']))	
			  $perpage = $params['perpage'];
			  
			 if(isset($params['daterange']) && $params['daterange'] =='no' ){
					unset($params['datefrom']); 
					unset($params['dateto']); 	 
			 }
			if(!empty($params['selectuser']))
					$userid =$params['selectuser'];
					
					$courseearch ="";

			if(!empty($params['course']) && $params['course'] != "0"){
				$coursestr = $params['course'] ;
				$course = explode(',',$coursestr);
				$courses=implode(", ", $course);
				$courseearch =" AND c.id IN ($courses)" ;
			}
						
						// 	 print_r($course);
					$completesearch ="";
			if(isset($params['daterange']) && $params['daterange'] =='datecomp' ){

				$beginOfDay = strtotime("midnight", $params['datefrom']);
				$endOfDay   = strtotime("tomorrow", $params['dateto']) - 1;

				 $completesearch= "AND cc.timecompleted > $beginOfDay AND  cc.timecompleted < $endOfDay " ; 
			
			}
		
			$sqlselect = "SELECT cc.id as ccid,cc.timecompleted as timecompleted,
								c.id as cid,c.fullname,c.idnumber as cidnumber,u.*";
			$sqlwhere =" FROM {course_completions} cc
							   JOIN {course} c ON (cc.course = c.id)
								JOIN {user} u ON (cc.userid = u.id)
							   WHERE cc.timecompleted IS NOT NULL and cc.userid in ($userid) ".$courseearch.$completesearch;

			 
			 if(isset($params['download'])){
				$courses = $DB->get_records_sql($sqlselect.$sqlwhere);
			}
			else{
				$courses = $DB->get_records_sql($sqlselect.$sqlwhere,null, $page * $perpage, $perpage);
				
			}

			$countcourses = $DB->get_records_sql($sqlselect.$sqlwhere);
			$numcourses = count($countcourses);
			if($courses){
				$returnobj = new stdclass();
				$returnobj->courses = $courses;
				$returnobj->totalcount = $numcourses;

				return $returnobj;
				
			}
			else{
				$returnobj = new stdclass();
				$returnobj->courses = array();
				$returnobj->totalcount = 0;

				return $returnobj;
			}
		}
		else{
			$returnobj = new stdclass();
			$returnobj->courses = array();
			$returnobj->totalcount = 0;

			return $returnobj;
		}
		 
	 }
 }
class iomad_course_completion_filter_form extends moodleform {
    protected $params = array();

    public function __construct($params) {
        $this->params = $params;
        parent::__construct();
    }

    public function definition() {
        global $CFG, $DB, $USER, $SESSION;

        $mform =& $this->_form;
      
        foreach ($this->params as $param => $value) {
            if ($param == 'datefrom' || $param == 'dateto') {
                continue;
            }
            if($param == 'departmentid'){
				$departmentid=$value;
				$mform->addElement('hidden', $param, $value);
				$mform->setType($param, PARAM_CLEAN);
			}
            if($param == 'username'){
				$usernameids = $value;			
			}
if($param == 'organization'){
				$companyid = $value;			
			}
            if($param == 'subgroup'){
				$subgroup = $value;			
			}
        }
        foreach ($this->params as $param => $value) {
            if ($param == 'datefrom' || $param == 'dateto' || $param == 'departmentid') {
                continue;
            }
               $mform->setDefault($param, $value);

        }
		$courses =base::selectcourses($departmentid);
        $userarray =base::selectusers($departmentid);	
		$organization =base::selectorganization($departmentid);
		if(isset($companyid) && isset($subgroup)){
			$subgroups=array('0'=>'Select Subgroup');
			$companyids =explode(',',$companyid);

			foreach($companyids as $key=>$value){	
				if($value != '0'){
					$comsubgroups =base::selectsubgroup($value);
					foreach($comsubgroups as $key1=>$value1){
						if($key1 != '0'){
							$subgroups[$key1] = $value1;
						}
					}
				}		
			}
			//$subgroups =base::selectsubgroup($companyid);
		}
		else
			$subgroups =base::get_all_subgroup($departmentid);
		

				$usernames =base::selectusernames($departmentid,array());
      	$mform->addElement('header', 'daterangefields', format_string(get_string('daterangeheader', 'local_base')));
		$mform->setExpanded('daterangefields', true);

		$dateranges =array('no'=>get_string('no', 'local_base'),'datecomp'=>get_string('datecompleted', 'local_base'));
		$mform->addElement('select', 'daterange',  '<b>'.get_string('daterange', 'local_base').':</b>', $dateranges,'style="width: 40% !important;"');
		$mform->addElement('date_selector', 'datefrom',  '<b>'. get_string('datefrom', 'local_base').':</b>');
        $mform->addElement('date_selector', 'dateto',   '<b>'.get_string('dateto', 'local_base').':</b>');
       $mform->setDefault('daterange', 'datecomp');
       $mform->setDefault('datefrom', strtotime(date('Y-m-d', strtotime('today - 30 days'))));
       
		$mform->addElement('header', 'usersearchfields', format_string(get_string('filterheader', 'local_base')));
		$mform->setExpanded('usersearchfields', false);
		$mform->addElement('html', '<div class="active-label"><label><b>'.get_string('selectfilter', 'local_base').':</b></label></div>
				  <div class="multiselect">
					<div class="selectBox" onclick="showCheckboxes()">
					  <select name="activesearch" id="activesearch">
						<option>Select Filter(s)</option>
					  </select>
					  <div class="overSelect"></div>
					</div>
					<div id="checkboxes" class="usr-srch-field">
					   <label for="checkcourse">
						<input type="checkbox" id="checkcourse"  data-target="#id_deletedcourse,#id_coursego,#id_course" />&nbsp;'.get_string('course', 'local_base').'</label>
					  <label for="checkemail">
						<input type="checkbox" id="checkemail" data-target="#id_email" />&nbsp;'.get_string('email', 'local_base').'</label>
					 <label for="checkname">
						<input type="checkbox" id="checkname" data-target="#id_firstname,#id_lastname" />&nbsp;'.get_string('name', 'local_base').'</label>
					  <label for="checkorganization">
						<input type="checkbox" id="checkorganization"  data-target="#id_organization" />&nbsp;'.get_string('organization', 'local_base').'</label>
					<label for="checksubgroup">
						<input type="checkbox" id="checksubgroup"  data-target="#id_subgroup" />&nbsp;'.get_string('subgroup', 'local_base').'</label>
					 <label for="checkuser">
						<input type="checkbox" id="checkuser" /data-target="#id_user" >&nbsp;'.get_string('user', 'local_base').'</label>
					  <label for="checkusername">
						<input type="checkbox" id="checkusername" /data-target="#id_username" >&nbsp;'.get_string('username', 'local_base').'</label>					  
					</div>
				 </div> <button type="button" class="go-btn">'.get_string('go', 'local_base').'</button>
				');
	  
		$mform->addElement('html', '<br><br><br>');
		$mform->addElement('html', '<div class="filter-elements">');
		$mform->addElement('select', 'user', '<b>'.get_string('user', 'local_base').':</b>', $userarray,'style="width: 40% !important;"');
		if(isset($usernameids)){
				$usernameids = explode(",",$usernameids);		
		}
		
		$mform->addElement('html', '<div class="fitem" style="margin-bottom: 1.429rem;">');
		$mform->addElement('html', '<div class="active-label"><label><b>'.get_string('username', 'local_base').':</b></label></div><div class="multiselect">');
		$mform->addElement('html', ' <select id="id_username" name="username[]" data-placeholder="Choose a User Name..." class="chosen-select" multiple tabindex="40" style="width: 250px;" >');
		$mform->addElement('html', ' <option value=""></option>');
		
		foreach($usernames as $key=>$value){
			if(isset($usernameids)){
				if(in_array($key,$usernameids))
					$mform->addElement('html', ' <option value="'.$key.'" selected >'.$value.'</option>');
				else
					$mform->addElement('html', ' <option value="'.$key.'" >'.$value.'</option>');

			}
			else
				$mform->addElement('html', ' <option value="'.$key.'">'.$value.'</option>');
		}
		          
		$mform->addElement('html', ' </select></div></div>');


		$select = $mform->addElement('select', 'course', '<b>'.get_string('course', 'local_base').':</b>', $courses,'style="width: 40% !important;"');
		$select->setMultiple(true);
		
		$mform->addElement('text', 'firstname', '<b>'.get_string('firstname', 'local_base').':</b>','style="width: 40%;"');
        $mform->addElement('text', 'lastname', '<b>'.get_string('lastname', 'local_base').':</b>','style="width: 40%;"');
       		
//       	$mform->addElement('select', 'organization','<b>'. get_string('organization', 'local_base').':</b>', $organization,'style="width: 40% !important;"');
        $select = $mform->addElement('select', 'organization', '<b>'.get_string('organization', 'local_base').':</b>', $organization,'style="width: 40% !important;"');
		$select->setMultiple(true);
$select = $mform->addElement('select', 'subgroup', '<b>'.get_string('subgroup', 'local_base').':</b>', $subgroups,'style="width: 40% !important;"');
$select->setMultiple(true);
		
        $mform->addElement('text', 'email','<b>'. get_string('email', 'local_base').':</b>','style="width: 40%;"');
		

        $mform->setType('username', PARAM_RAW);
        $mform->setType('firstname', PARAM_RAW);
        $mform->setType('lastname', PARAM_RAW);
        $mform->setType('email', PARAM_RAW);
		$mform->addElement('html', '</div>');

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('updatefilters', 'local_base'));
        $buttonarray[] = $mform->createElement('button', 'removefilter', get_string('removefilters', 'local_base'),array('class'=>'remove-btn'));
		$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');
    }
}
