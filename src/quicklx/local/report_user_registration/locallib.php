<?php

/**
 *  Filter form used on the report .
 *
 */
 require_once($CFG->dirroot.'/local/base/locallib.php');
class report_user_registration {
  public static function countUserLoggedin($userid)
	{
		global $DB;
		$data = $DB->get_record_sql("SELECT count(id) AS total FROM mdl_logstore_standard_log WHERE action = 'loggedin' AND crud = 'r' AND userid = $userid");
		return $data->total;

	}

	//Custom Code - Syllametrics - Updated (27 th march,2019)
	public static function get_all_registered_users($departmentid, $courseid=0,$params){
         global $DB,$USER;
         //print_r($params);
        
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
        
         			
		if(isset($params['departmentid'])){
			 $departmentid = $params['departmentid'];
			$alldepartments = company::get_all_subdepartments($departmentid);
		}
		      
		if(isset($params['organization']) && $params['organization'] != "0"){
			//print_r($params['organization']);
			$organizations =explode(",",$params['organization']);
			$alldepartments =array();
			foreach($organizations as $key=>$value){
				$companyid = $value;
				$department = $DB->get_record('department', array('parent' => 0,'company'=>$companyid));
				$departmentid = $department->id;
				$orgdepartments = company::get_all_subdepartments($departmentid);
				foreach($orgdepartments as $key=>$value){
					$alldepartments[$key] = $value;
				}
			}			
		}

        if (count($alldepartments) > 0 ) {
			 $departmentids = implode(',', array_keys($alldepartments));
            // Deal with suspended or not.  
            $searchusername='';
            $searchfirstname='';
            $searchlastname='';
            $searchuser='';
			//Custom Code - Syllametrics - Updated (1 st April,2019)

            $searchcreatedon='';
	if(isset($params['daterange']) && $params['daterange'] =='createdon' ){
				$beginOfDay = strtotime("midnight", $params['datefrom']);
				$endOfDay   = strtotime("tomorrow", $params['dateto']) - 1;
				$searchcreatedon=" AND timecreated > $beginOfDay AND  timecreated < $endOfDay";
			}
	if(isset($params['daterange']) && $params['daterange'] =='lastloggedin' ){
				$beginOfDay = strtotime("midnight", $params['datefrom']);
				$endOfDay   = strtotime("tomorrow", $params['dateto']) - 1;
				$searchcreatedon=" AND lastaccess > $beginOfDay AND  lastaccess < $endOfDay";
			}
    //end custom changes on 1 st April,2019

			if(isset($params['subgroup']) && $params['subgroup'] != "0" ){
				$departmentids =  $params['subgroup'];
			}

            if(isset($params['username']) )
					 $searchusername = " AND id IN (".$params['username'].") ";
       
            if(isset($params['user']) )
					 $searchuser = " AND id = ".$params['user'];
       
            if(isset($params['firstname']) )
					 $searchfirstname = " AND firstname LIKE '%".$params['firstname']."%' ";
       
            if(isset($params['lastname']) )
					 $searchlastname = " AND lastname LIKE '%".$params['lastname']."%' ";
					 
			$usernamefilter =$searchusername.$searchfirstname.$searchlastname.$searchuser.$searchcreatedon;
			
             $suspendedsql = " AND userid IN (select id FROM {user} WHERE 1 $usernamefilter) ";
              if(isset($params['activestatus']) && $params['activestatus'] == '1')
					 $suspendedsql = " AND userid IN (select id FROM {user} WHERE suspended = 0 AND deleted=0 $usernamefilter) ";

              if(isset($params['activestatus']) && $params['activestatus'] == '2')
					 $suspendedsql = " AND userid IN (select id FROM {user} WHERE suspended = 1 OR deleted=1 $usernamefilter) ";

			$sql = "SELECT u.* FROM {user} u
								JOIN {company_users} cu on cu.userid=u.id
								WHERE departmentid IN ($departmentids) $suspendedsql ORDER BY firstname ASC";
       
			 if(isset($params['download']))
				$users = $DB->get_records_sql($sql);
			else
				$users = $DB->get_records_sql($sql,null, $page * $perpage, $perpage);

			$countusers = $DB->get_records_sql($sql);
			$numusers = count($countusers);

			$returnobj = new stdclass();
			$returnobj->users = $users;
			$returnobj->totalcount = $numusers;

			return $returnobj;
			
		}
		else{
			$returnobj = new stdclass();
			$returnobj->users = array();
			$returnobj->totalcount = 0;

			return $returnobj;
		}
        
    }
    //end custom changes on 27 th march,2019
 }
class iomad_user_registration_filter_form extends moodleform {
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
		$usernames =base::selectusernames($departmentid,array());
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

		$mform->addElement('header', 'daterangefields', format_string(get_string('daterangeheader', 'local_base')));
		$mform->setExpanded('daterangefields', true);
 		//Custom Code - Syllametrics - Updated (1 st April,2019)
		$dateranges =array('no'=>get_string('no', 'local_base'),'createdon'=>get_string('createdon', 'local_base')
			,'lastloggedin'=>get_string('lastloggedin', 'local_base'));		$mform->addElement('select', 'daterange',  '<b>'.get_string('daterange', 'local_base').':</b>', $dateranges,'style="width: 40% !important;"');		
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
					  <label for="checkactivestatus">
						<input type="checkbox" id="checkactivestatus" data-target="#id_activestatus" />&nbsp;'.get_string('activestatus', 'local_base').'</label>
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
