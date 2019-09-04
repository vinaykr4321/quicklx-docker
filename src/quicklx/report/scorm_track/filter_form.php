<?php
require_once($CFG->libdir . '/formslib.php');

class scormtrack extends moodleform {
 
    public function definition() {
        global $CFG, $PAGE, $DB;
        $companydata = $DB->get_records_sql('SELECT company FROM {report_scorm_download} WHERE company !=""');
        $companynames = array();
        $companynames['All'] = get_string('selectcompany', 'report_scorm_track');
        foreach ($companydata as $companyname)
        {
            $companynames[$companyname->company] = $companyname->company;
        }

        $course_data= $DB->get_records_sql('SELECT  coursename FROM {report_scorm_download} WHERE coursename !=""');
        $course_data_arr = array();
        $course_data_arr['All'] = get_string('selectcompany_course', 'report_scorm_track');
        foreach ($course_data as $course_data_value)
        {
            $course_data_arr[$course_data_value->coursename] = $course_data_value->coursename;
        }
      


        $mform = & $this->_form;
        $mform->addElement('header', 'formheader', format_string(get_string('formheader', 'report_scorm_track')));
/*
        $mform->addElement('date_selector', 'lastvisitfrom', get_string('lastvisitfrom', 'report_scorm_track'), array('optional' => 'yes'));
        $mform->addElement('date_selector', 'lastvisitto', get_string('lastvisitto', 'report_scorm_track'), array('optional' => 'yes'));
*/
	$mform->addElement('date_selector', 'lastvisitfrom', get_string('lastvisitfrom', 'report_scorm_track'));
        $mform->addElement('date_selector', 'lastvisitto', get_string('lastvisitto', 'report_scorm_track'));

        $mform->setDefault('lastvisitfrom', strtotime(date('Y-m-d', strtotime('today - 360 days'))));

        
        $mform->addElement('select', 'company', get_string('company', 'report_scorm_track'), $companynames);

        /*$mform->addElement('text', 'coursename', get_string('coursename', 'report_scorm_track'), 'size="100"');
        $mform->setType('coursename', PARAM_CLEAN);*/



        $select = $mform->addElement('select', 'coursename', get_string('coursename', 'report_scorm_track'), $course_data_arr);
        $select->setMultiple(true);



        $mform->addElement('text', 'clientsite', get_string('clientsite', 'report_scorm_track'), 'size="100"');
        $mform->setType('clientsite', PARAM_CLEAN);
        
        $mform->addElement('text', 'clientip', get_string('clientip', 'report_scorm_track'), 'size="100"');
        $mform->setType('clientip', PARAM_CLEAN);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $mform->setDefault('lastvisit', $_GET['lastvisit']); 
                $mform->setDefault('company', $_GET['company']); 
		$trim_array = rtrim(ltrim($_GET['coursename'],"('"),"')");
                $cn_set_value = explode("','",$trim_array  );
                $mform->setDefault('coursename', $cn_set_value); 
                $mform->setDefault('clientsite', $_GET['clientsite']); 
                $mform->setDefault('clientip', $_GET['clientip']); 
                break;
            case 'POST':
                $mform->setDefault('lastvisit', $_POST['lastvisit']); 
                $mform->setDefault('company', $_POST['company']); 
                $mform->setDefault('coursename', $_POST['coursename']); 
                $mform->setDefault('clientsite', $_POST['clientsite']);         
                $mform->setDefault('clientip', $_POST['clientip']);         
                break;
            default:
                $param = $_REQUEST;
                break;
        }

        
        //$mform->addElement('date_selector', 'endtime', get_string('timeaccess', 'report_usersreport'), array('optional' => 'yes'));
        //$mform->setDefault('timestart', 0);

      //  $mform->setExpanded('usersearchfields', false);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('filterresult', 'report_scorm_track'));
	$buttonarray[] =  $mform->createElement('html','<button type="reset" class="btn btn-primary"  value="Remove Filters" 			id="abc">Remove Filters</button>');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

        
        //Comment below line for display filter button under form section 
        //$mform->closeHeaderBefore('buttonar');
    }
}
