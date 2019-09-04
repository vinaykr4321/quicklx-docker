<?php
require_once($CFG->libdir . '/formslib.php');

class userreports extends moodleform {
 
    public function definition() {
        global $CFG, $PAGE, $DB;

        $mform = & $this->_form;
        $mform->addElement('header', 'usersearchfields', format_string(get_string('usersearchfields', 'report_userreg')));

        $mform->addElement('html', '<div class="col-md-5 pull-left">');
        $mform->addElement('text', 'firstname', get_string('firstnamefilter', 'report_userreg'), 'size="20"');
        $mform->addElement('text', 'lastname', get_string('lastnamefilter', 'report_userreg'), 'size="20"');
        $mform->addElement('text', 'email', get_string('emailfilter', 'report_userreg'), 'size="20"');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="col-md-7 pull-left">');
        $mform->addElement('date_selector', 'firstaccessfrom', get_string('firstaccessfrom', 'report_userreg'));
        $mform->addElement('date_selector', 'firstaccessto', get_string('firstaccessto', 'report_userreg'));
        $mform->addElement('date_selector', 'lastaccessfrom', get_string('lastaccessfrom', 'report_userreg'));
        $mform->addElement('date_selector', 'lastaccessto', get_string('lastaccessto', 'report_userreg'));
        $mform->addElement('html', '</div>');

        $mform->setType('firstname', PARAM_CLEAN);
        $mform->setType('lastname', PARAM_CLEAN);
        $mform->setType('email', PARAM_CLEAN);
        $mform->setType('firstaccess', PARAM_CLEAN);
        $mform->setType('lastaccess', PARAM_CLEAN);
        
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('userfilter', 'report_userreg'));
        $buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('resetfilter', 'report_userreg'),array("class"=>"btn btn-primary margin_top_2"));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');

    }

}

?>


