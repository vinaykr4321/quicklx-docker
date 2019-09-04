<?php
require_once($CFG->libdir . '/formslib.php');

class userreports extends moodleform {
 
    public function definition() {
        global $CFG, $PAGE, $DB;

        $mform = & $this->_form;
        $mform->addElement('header', 'usersearchfields', format_string(get_string('usersearchfields', 'report_usersreport')));

        $mform->addElement('html', '<div class="col-md-5 pull-left">');
        $mform->addElement('text', 'firstname', get_string('firstnamefilter', 'report_logireport'), 'size="20"');
        $mform->addElement('text', 'lastname', get_string('lastnamefilter', 'report_logireport'), 'size="20"');
        $mform->addElement('text', 'email', get_string('emailfilter', 'report_logireport'), 'size="20"');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="col-md-7 pull-left">');
        $mform->addElement('date_selector', 'firstaccessfrom', get_string('firstaccessfrom', 'report_logireport'));
        $mform->addElement('date_selector', 'firstaccessto', get_string('firstaccessto', 'report_logireport'));
        $mform->addElement('date_selector', 'lastaccessfrom', get_string('lastaccessfrom', 'report_logireport'));
        $mform->addElement('date_selector', 'lastaccessto', get_string('lastaccessto', 'report_logireport'));
        /*$mform->addElement('text', 'firstaccess', get_string('firstaccess', 'report_logireport'), 'size="20"');
        $mform->addElement('text', 'lastaccess', get_string('lastaccess', 'report_logireport'), 'size="20"');*/
        $mform->addElement('html', '</div>');

        $mform->setType('firstname', PARAM_CLEAN);
        $mform->setType('lastname', PARAM_CLEAN);
        $mform->setType('email', PARAM_CLEAN);
        $mform->setType('firstaccess', PARAM_CLEAN);
        $mform->setType('lastaccess', PARAM_CLEAN);
        
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', get_string('userfilter', 'report_usersreport'));
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        $mform->closeHeaderBefore('buttonar');

    }

}

?>


