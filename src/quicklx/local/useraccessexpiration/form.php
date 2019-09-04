<?php
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');

class user_access_expiration extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;

        $companyid = iomad::get_my_companyid(context_system::instance());
        $test =  $DB->get_records_sql("SELECT u.* FROM {user} AS u JOIN {company_users} AS cu on cu.userid = u.id WHERE cu.companyid = $companyid order by u.firstname asc");
        foreach ($test as $value) 
        {
            $choices[$value->id] = $value->firstname.' '.$value->lastname;
        }

        $mform->addElement('html', '<div class="row">');
            $mform->addElement('html', '<div class="col-md-3"></div>
                <div class="col-md-9 form-inline felement">
                    <div class="col-md-2 btn btn-secondary margin_right_1 margin_bottom_1" id = "selectall"> Select all</div>
                    <div class="col-md-2 btn btn-secondary margin_right_1 margin_bottom_1" id = "removeall"> Remove all</div>
                </div>');
        $mform->addElement('html', '</div>');

            $attribute = array('size'=>"15", 'style'=>'width:35%;');
            $select = $mform->addElement('select', 'users', 'Select users', $choices, $attribute);
            $select->setMultiple(true);

            $mform->addElement('html', '<button class="btn btn-secondary margin_right_1" name="view" id="view">View access expiry date</button>');
            $mform->addElement('html', '<button class="btn btn-secondary margin_right_1" name="set" id="set">Set access expiry date</button>');
            
        //$mform->addElement('button', 'view', 'View access expiry date','id="view"');
        // /$mform->addElement('button', 'set', 'Set access expiry date','id="set"');
    }
}

class set_expiration extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;

        $mform->addElement('date_selector', 'assesstimefinish', 'Set Expiry Date');
        $string = implode(',', $_GET);
        $mform->addElement('hidden', 'ids',$string);
        $mform->setType('ids', PARAM_RAW);

        $mform->addElement('checkbox', 'remaccess', "Remove access expiry date");
        $mform->disabledIf('assesstimefinish', 'remaccess', 'eq', 1);

        $this->add_action_buttons();

    }
}