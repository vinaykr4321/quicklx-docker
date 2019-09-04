<?php
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');

class user_access_expiration extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;

        $companyid = iomad::get_my_companyid(context_system::instance());
        $allData =  $DB->get_records_sql("SELECT u.* FROM {user} AS u JOIN {company_users} AS cu on cu.userid = u.id WHERE cu.companyid = $companyid order by u.firstname asc");
        foreach ($allData as $value) 
        {
            //$choices[$value->id] = $value->firstname.' '.$value->lastname;
            $option .= '<option value="'.$value->id.'">'.$value->firstname.' '.$value->lastname.'</option>';
        }

        $mform->addElement('html','<div class="container">');
            $mform->addElement('html','<div class="row">');
                $mform->addElement('html','<div class="col-md-5">');
                    $mform->addElement('html','<select name="pickusers[]" class = "pickusers" multiple="" size="15" style="width:100%;">');
                        $mform->addElement('html',$option);
                    $mform->addElement('html','</select>');
                $mform->addElement('html','</div>');

                $mform->addElement('html','<div class="col-md-2">');
                    $mform->addElement('html','<div class="actionbutton btn" id="add">Add &nbsp; &#9654;</div>');
                    $mform->addElement('html','<div class="actionbutton btn" id="remove">&#9664 &nbsp; Remove</div>');
                    $mform->addElement('html','<div class="actionbutton btn" id="addall">Add all &nbsp; &#9654;</div>');
                    $mform->addElement('html','<div class="actionbutton btn" id="removeall">&#9664 &nbsp; Remove all</div>');
                $mform->addElement('html','</div>');

                $mform->addElement('html','<div class="col-md-5">');
                    $mform->addElement('html','<select name="users[]" class = "users" multiple="" size="15" style="width:100%;">');
                    $mform->addElement('html','</select>');
                $mform->addElement('html','</div>');


                $mform->addElement('html','<select name="alluser[]" class = "alluser" multiple="" size="15" style="display:none;">');
                    $mform->addElement('html','</select>');


                $buttonarray = array();
                $buttonarray[] = $mform->createElement('submit', 'view', 'View access expiry date','style="margin:1%;" id="view"');
                $buttonarray[] = $mform->createElement('submit', 'set', 'Set access expiry date', 'id="set"');
                $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

                //$mform->addElement('submit', 'view', 'View access expiry date');
                //$mform->addElement('submit', 'set', 'Set access expiry date');
            $mform->addElement('html','</div>');
        $mform->addElement('html','</div>');

        //$mform->addElement('button', 'view', 'View access expiry date');
        //$mform->addElement('button', 'set', 'Set access expiry date');
        


        /*    $attribute = array('size'=>"15", 'style'=>'width:35%;');
            $select = $mform->addElement('select', 'users', '', $choices, $attribute);
            $select->setMultiple(true);

            $attribute = array('size'=>"15", 'style'=>'width:35%;');
            $select = $mform->addElement('select', 'users', '', '', $attribute);
            $select->setMultiple(true);*/
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