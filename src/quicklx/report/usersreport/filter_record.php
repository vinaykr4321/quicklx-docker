<?php
require_once($CFG->libdir . '/formslib.php');

class userreports extends moodleform {
 
    public function definition() {
        global $CFG, $PAGE, $DB;

        $mform = & $this->_form;
        $mform->addElement('header', 'usersearchfields', format_string(get_string('usersearchfields', 'report_usersreport')));

        $mform->addElement('text', 'firstname', get_string('firstnamefilter', 'report_usersreport'), 'size="20"');
        $mform->addElement('text', 'lastname', get_string('lastnamefilter', 'report_usersreport'), 'size="20"');
        $mform->addElement('text', 'email', get_string('emailfilter', 'report_usersreport'), 'size="20"');

        $mform->setType('firstname', PARAM_CLEAN);
        $mform->setType('lastname', PARAM_CLEAN);
        $mform->setType('email', PARAM_CLEAN);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $mform->setDefault('firstname', $_GET['firstname']); 
                $mform->setDefault('lastname', $_GET['lastname']); 
                $mform->setDefault('email', $_GET['email']); 
                break;
            case 'POST':
                $mform->setDefault('firstname', $_POST['firstname']); 
                $mform->setDefault('lastname', $_POST['lastname']); 
                $mform->setDefault('email', $_POST['email']);         
                break;
            default:
                $param = $_REQUEST;
                break;
        }

        
        //$mform->addElement('date_selector', 'starttime', get_string('timestart', 'report_usersreport'), array('optional' => 'yes'));
        //$mform->addElement('date_selector', 'endtime', get_string('timeaccess', 'report_usersreport'), array('optional' => 'yes'));
        //$mform->setDefault('timestart', 0);

      //  $mform->setExpanded('usersearchfields', false);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('userfilter', 'report_usersreport'));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

        
        //Comment below line for display filter button under form section 
        //$mform->closeHeaderBefore('buttonar');

    }



}

?>

