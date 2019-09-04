<?php

/**
 *  Filter form used on the report .
 *
 */
 require_once($CFG->dirroot.'/local/base/locallib.php');
class report_reportcard {
  
	public static function get_all_course_modules($params){
		global $DB,$USER;
		// print_r($params);
		if(!empty($params['selectcourse']) && $params['selectcourse'] != "0" ){
			$course =  $params['selectcourse'] ;
			
		}
		else if(!empty($params['course']) && $params['course'] != "0"){
			$course =  $params['course'] ;
			
		}
		$sql ="SELECT distinct(m.id),m.name FROM {modules} m
				JOIN {course_modules} cm on cm.module=m.id
				WHERE cm.course=".$course;
		$modules = $DB->get_records_sql($sql);
		if($modules){
			return $modules;
		}
		else
			return array();
	}
	
	 public static function get_all_module_details($params){
		global $DB,$USER;
        // print_r($params);
		if(!empty($params['selectcourse']) && $params['selectcourse'] != "0"){
			$course =  $params['selectcourse'] ;
			
		}
		if(!empty($params['module'])){
			$module =  $params['module'] ;
			
		}
		if(!empty($params['modulename'])){
			$modulename =  $params['modulename'] ;
			
		}
		
		$sql ="SELECT cm.*,m.name FROM mdl_$modulename m
				JOIN {course_modules} cm on (cm.instance=m.id and cm.course=m.course) 
				WHERE cm.course=".$course." AND cm.module=".$module;
		$coursemodules = $DB->get_records_sql($sql);
		if($coursemodules){
			return $coursemodules;
		}
		else
			return array();
	}
}
class iomad_reportcard_filter_form extends moodleform {
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
        $countries = base::selectcountry();
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
		$dateranges =array('no'=>get_string('no', 'local_base'),'datereg'=>get_string('dateregistered', 'local_base'),
					'datecomp'=>get_string('datecompleted', 'local_base'));
		$mform->addElement('select', 'daterange',  '<b>'.get_string('daterange', 'local_base').':</b>', $dateranges,'style="width: 40% !important;"');
        $mform->addElement('date_selector', 'datefrom',  '<b>'. get_string('datefrom', 'local_base').':</b>',array('class'=>'datelabel'));
        $mform->addElement('date_selector', 'dateto',   '<b>'.get_string('dateto', 'local_base').':</b>',array('class'=>'datelabel'));
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
					  <label for="checkactivestatus">
						<input type="checkbox" id="checkactivestatus" data-target="#id_activestatus" />&nbsp;'.get_string('activestatus', 'local_base').'</label>
					  <label for="checkcompletionstatus">
						<input type="checkbox" id="checkcompletionstatus" data-target="#id_completionstatus" />&nbsp;'.get_string('completionstatus', 'local_base').'</label>
					  <label for="checkenrolledstatus">
						<input type="checkbox" id="checkenrolledstatus" data-target="#id_enrolledstatus" />&nbsp;'.get_string('enrolledstatus', 'local_base').'</label>
					  <label for="checkcountry">
						<input type="checkbox" id="checkcountry"  data-target="#id_country" />&nbsp;'.get_string('country', 'local_base').'</label>
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
					  <label for="checkusername">
						<input type="checkbox" id="checkusername" /data-target="#id_username" >&nbsp;'.get_string('username', 'local_base').'</label>
					  <label for="checkuser">
						<input type="checkbox" id="checkuser" /data-target="#id_user" >&nbsp;'.get_string('user', 'local_base').'</label>
					  
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
		$activestatus= array('0'=>'Select Active Status','1'=>'Active','2'=>'Inactive');	
		$mform->addElement('select', 'activestatus','<b>'. get_string('activestatus', 'local_base').':</b>', $activestatus,'style="width: 40% !important;"');
	
		$completionstatus= array('0'=>'Select Completion Status','1'=>'All','2'=>'Users that are registered but not yet completed','3'=>'Users that completed');	
		$mform->addElement('select', 'completionstatus','<b>'. get_string('completionstatus', 'local_base').':</b>', $completionstatus,'style="width: 40% !important;"');
	
		$enrolledstatus= array('0'=>'Select Enrolled Status','1'=>'Enrolled','2'=>'Not Enrolled');	
		$mform->addElement('select', 'enrolledstatus','<b>'. get_string('enrolledstatus', 'local_base').':</b>', $enrolledstatus,'style="width: 40% !important;"');
	
        $mform->addElement('text', 'email','<b>'. get_string('email', 'local_base').':</b>','style="width: 40%;"');
		
		$mform->addElement('select', 'country', '<b>'.get_string('country', 'local_base').':</b>', $countries,'style="width: 40% !important;"');

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
class iomad_learnerreportcard_filter_form extends moodleform {
    protected $params = array();

    public function __construct($params) {
        $this->params = $params;
        parent::__construct();
    }

    public function definition() {
        global $CFG, $DB, $USER, $SESSION;

        $mform =& $this->_form;
    //print_r($this->params);
        foreach ($this->params as $param => $value) {
            if ($param == 'datefrom' || $param == 'dateto' || $param == 'course'
             || $param == 'completionstatus' || $param == 'daterange' 
             || $param == 'urlcourse' || $param == 'daterange' ) {
                continue;
            }
            if($param == 'departmentid')
				$departmentid=$value;
			if($param == 'username'){
				$usernameids = $value;			
			}
			$mform->addElement('hidden', $param, $value);
			$mform->setType($param, PARAM_CLEAN);
			
        }
        foreach ($this->params as $param => $value) {
            if ($param == 'datefrom' || $param == 'dateto' || $param == 'course'
             || $param == 'completionstatus' || $param == 'daterange' 
             || $param == 'urlcourse' || $param == 'daterange') {
                continue;
            }
               $mform->setDefault($param, $value);

        }
        $countries = base::selectcountry();
      	$courses =base::selectcourses($departmentid);
        $userarray =base::selectusers($departmentid);
		$organization =base::selectorganization($departmentid);	
		$usernames =base::selectusernames($departmentid,array());

		$mform->addElement('header', 'daterangefields', format_string(get_string('daterangeheader', 'local_base')));
		$mform->setExpanded('daterangefields', true);
		$dateranges =array('no'=>get_string('no', 'local_base'),'datereg'=>get_string('dateregistered', 'local_base'),
					'datecomp'=>get_string('datecompleted', 'local_base'));
		$mform->addElement('select', 'daterange',  '<b>'.get_string('daterange', 'local_base').':</b>', $dateranges,'style="width: 40% !important;"');
        $mform->addElement('date_selector', 'datefrom',  '<b>'. get_string('datefrom', 'local_base').':</b>',array('class'=>'datelabel'));
        $mform->addElement('date_selector', 'dateto',   '<b>'.get_string('dateto', 'local_base').':</b>',array('class'=>'datelabel'));
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
					  <label for="checkcompletionstatus">
						<input type="checkbox" id="checkcompletionstatus" data-target="#id_completionstatus" />&nbsp;'.get_string('completionstatus', 'local_base').'</label>
					 <label for="checkcourse" >
						<input type="checkbox" id="checkcourse"  data-target="#id_course" />&nbsp;'.get_string('course', 'local_base').'</label>
					  
				
					</div>
				</div> <button type="button" class="go-btn">'.get_string('go', 'local_base').'</button>
				');

		$mform->addElement('html', '<br><br><br>');
		$mform->addElement('html', '<div class="filter-elements">');
	
		$select = $mform->addElement('select', 'course', '<b>'.get_string('course', 'local_base').':</b>', $courses,'style="width: 40% !important;"');
		$select->setMultiple(true);

		$completionstatus= array('0'=>'Select Completion Status','1'=>'All','2'=>'Users that are registered but not yet completed','3'=>'Users that completed');	
		$mform->addElement('select', 'completionstatus','<b>'. get_string('completionstatus', 'local_base').':</b>', $completionstatus,'style="width: 40% !important;"');
			
		$mform->addElement('html', '</div>');
 
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('updatereport', 'local_base'));
        $buttonarray[] = $mform->createElement('button', 'removefilter',  get_string('removefilters', 'local_base'),array('class'=>'remove-btn'));
	
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');
    }
}
